<?php
/**
 * @author Fm453(方少)
 * @DACMS https://api.hiluker.com
 * @site https://www.hiluker.com
 * @url http://s.we7.cc/index.php?c=home&a=author&do=index&uid=662
 * @email fm453@lukegzs.com
 * @QQ 393213759
 * @wechat 393213759
*/

/*
 * @remark 登陆
 */
defined('IN_IA') or exit('Access Denied');
checkAuth();//调用系统的登陆验证
global $_GPC;
global $_W;
global $_FM;
fm_load()->fm_func('share');//链接分享管理
fm_load()->fm_func('view'); 	//浏览量处理
fm_load()->fm_func('wechat');//微信定义管理

$appstyle =$this->appstyle();
$fm453resource =FM_RESOURCE;
$appsrc =fmFunc_ui_appsrc();
$uniacid=$_W['uniacid'];
$userinfo=fmFunc_fans_oauth_getInfo();//网页授权获取头像、昵称等信息；

$pagename = "会员登陆";
$shopname=$settings['brands']['shopname'];
$op = $_GPC['op']?$_GPC['op']:'display';
$profile = pdo_fetch('SELECT * FROM '.tablename('fm453_shopping_member')." WHERE  uniacid = :uniacid  AND from_user = :from_user" , array(':uniacid' => $_W['uniacid'],':from_user' => $from_user));//从商城会员表中读取资料
//已经注册会员时
if(!empty($profile)){
	$count1 = pdo_fetchcolumn("select count(*) from ("."select from_user from ".tablename('fm453_shopping_order')." where  shareid = ".$profile['id'].'  group by from_user'.") x");
	$count1_2 = pdo_fetchcolumn("select count(mber.id) from ".tablename('fm453_shopping_member')." mber where mber.shareid = ".$profile['id']." and mber.from_user not in ("."select orders.from_user from ".tablename('fm453_shopping_order')." orders where  orders.shareid = ".$profile['id']." group by from_user)");
	$count1=$count1+$count1_2;
	if($count1>0){
		$countall = pdo_fetch("select id from ".tablename('fm453_shopping_member')." where shareid = ".$profile['id']);
		$count2=0;
		$count3=0;
		if ($countall) {
				foreach ($countall as &$citem){
					$tcount2 = pdo_fetchcolumn("select count(id) from ".tablename('fm453_shopping_member')." where shareid = ".$citem);
					$count2=$count2+$tcount2;
					$count2all = pdo_fetch("select id from ".tablename('fm453_shopping_member')." where shareid = ".$citem);
					foreach ($count2all as &$citem2){
						$tcount3 = pdo_fetchcolumn("select count(*) from ("."select from_user from ".tablename('fm453_shopping_order')." where  shareid = ".$citem2.' and shareid!='.$citem.' and shareid!='.$profile['id'].' group by from_user'.") y"  );
						$count3=$count3+$tcount3;
					}
				}
			}
		}else{
			$count1=0;
			$count2=0;
			$count3=0;
		}
		$count1=$count1+$count2+$count3;
	}else{
		$count1=0;
	}
	$id = $profile['id'];
	if(intval($profile['id']) && $profile['status']==0){
			include $this->template($appstyle.'forbidden');
			exit;
		}
		//未注册会员时
	if(empty($profile)){
			$rule = pdo_fetch('SELECT * FROM '.tablename('fm453_shopping_rules')." WHERE  uniacid = :uniacid ",array(':uniacid' => $_W['uniacid']));
			//$profile =fans_search($from_user, array('realname'));
			load()->model('mc');
			$profile  = mc_fetch($_W['member']['uid']);
			$settings = $this->module['config'];
			include $this->template($appstyle.'register');
			exit;
		}
		$theone = pdo_fetch('SELECT * FROM '.tablename('fm453_shopping_rules')." WHERE  uniacid = :uniacid" , array(':uniacid' => $_W['uniacid']));
		if($theone['promotertimes'] == 0 && $profile['flag'] == 0){
				$isorder = pdo_fetch('SELECT * FROM '.tablename('fm453_shopping_order')." WHERE status= '3' AND  uniacid = :uniacid  AND from_user = :from_user" , array(':uniacid' => $_W['uniacid'],':from_user' => $from_user));
			if(!$isorder){
				message('您还未通过分销员审核，请先购买一笔订单才能成为分销员！', $this->createMobileUrl('list',array('mid'=>$id)), 'success');
			}else{
				pdo_update('fm453_shopping_member', array('flag' => 1), array('id' => $profile['id']));
				$profile['flag'] = 1;
			}
		}else{
			if(empty($profile['flagtime'])||$profile['flag']!=1){
				pdo_update('fm453_shopping_member', array('flagtime'=>TIMESTAMP), array('id' => $profile['id']));
			}
			pdo_update('fm453_shopping_member', array('flag' => 1), array('id' => $profile['id']));
		}

		load()->model('mc');
		$myheadimg = mc_fetch($_W['member']['uid']);
		$avatar=$this->fmFunc_fans_getAvatar();
		$share = "fm453_shoppingshareQrcode".$_W['uniacid'];
		if($_COOKIE[$share] != $_W['uniacid']."share".$id){
			include "../framework/library/qrcode/phpqrcode.php";//引入PHP QR库文件
			$value = $_W['siteroot']."app/".$this->murl('detail',array('mid'=>$id));
			$imgname = "share".$id.".png";
			$errorCorrectionLevel = "L";
			$matrixPointSize = "4";
			$imgurl = "../addons/fm453_shopping/erweima/$imgname";
		//	QRcode::png($value,$imgname,$imgurl,$errorCorrectionLevel,$matrixPointSize);
			setCookie($share, $_W['uniacid']."share".$id, time()+3600*24);
		}

		$commtime = pdo_fetch("select commtime, promotertimes from ".tablename('fm453_shopping_rules')." where uniacid = ".$_W['uniacid']);
		$commissioningpe = 0;
		if(empty($commtime) && $commtime['commtime']<=0){
			$commtime = array();
			$commtime['commtime']=0;
		}
		$moneytime = time()-3600*24*$commtime['commtime'];
		$userx = pdo_fetch("select * from ".tablename('fm453_shopping_member')." where from_user = '".$from_user."'");
		$commissioningpe = pdo_fetchcolumn("SELECT sum((g.commission*g.total)) FROM " .tablename('fm453_shopping_order')." as o left join ".tablename('fm453_shopping_order_goods')." as g on o.id = g.orderid and o.uniacid = g.uniacid WHERE o.shareid = ".$id." and o.uniacid = ".$_W['uniacid']." and (g.status = 0 or g.status = 1) and o.status >= 3 and o.from_user != '".$from_user."' and  g.createtime>=".$userx['flagtime']);
		if(empty($commissioningpe)){
			$commissioningpe =0;
		}
//include $this->template($appstyle.'fansindex');
include $this->template($appstyle.$do.'/453');
