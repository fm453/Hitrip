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
 * @remark 佣金管理
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;
load()->func('tpl');
fm_load()->fm_func('share');//链接分享管理
fm_load()->fm_model('shopcart'); //购物车模块
fm_load()->fm_func('view'); 	//浏览量处理
fm_load()->fm_func('wechat');//微信定义管理

//是否关店歇业
fm_checkopen($settings['onoffs']);
//加载风格模板及资源路径
$appstyle = empty($settings['appstyle']) ? FM_APPSTYLE : $settings['appstyle'];
$appsrc =MODULE_URL.'template/mobile/'.$appstyle.'453/';
$htmlsrc =MODULE_URL.'template/mobile/'.$appstyle;
$fm453resource = FM_RESOURCE;
//入口判断
$do= $_GPC['do'];
$ac=$_GPC['ac'];
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'index';
//开始操作管理
$shopname=$settings['brands']['shopname'];
$shopname = !empty($shopname) ? $shopname :FM_NAME_CN;
$pagename = '佣金信息';
//$pagename .='|'.$_W['account']['name'];

$uniacid=$_W['uniacid'];
$plattype=$settings['plattype'];
$platids = fmFunc_getPlatids();//获取平台模式关联的公众号商户ID参数
$platid=$_W['uniacid'];
$oauthid=$platids['oauthid'];
$fendianids=$platids['fendianids'];
$supplydianids=$platids['supplydianids'];
$blackids=$platids['blackids'];

//平台模式处理
require_once FM_PUBLIC.'plat.php';
$supplydians=explode(',',$supplydianids);//字符串转数组
$supplydians=array_filter($supplydians);//数组去空

//按平台模式前置筛选条件
$condition=' WHERE ';
$params=array();
require_once FM_PUBLIC.'forsearch.php';

$share_user=$_GPC['share_user'];
$shareid = intval($_GPC['shareid']);
$lastid = intval($_GPC['lastid']);
$currentid = intval($_W['member']['uid']);
$fromplatid = intval($_GPC['fromplatid']);
$from_user = $_W['openid'];
$url_condition="";
$direct_url = fm_murl($do,$ac,$operation,array());

//自定义微信分享内容
$_share = array();
$_share['title'] = $pagename;
$_share['link'] = fm_murl($do,$ac,$operation,array('isshare'=>1));
$_share['link'] = $_share['link'].$url_condition;
$_share['imgUrl'] = tomedia($settings['brands']['logo']);
$_share['desc'] = $_share['desc'] = $settings['brands']['share_des'];

$resultmember = fmMod_member_query($currentid);
$FM_member=$resultmember['data'];

$op = $_GPC['op']?$_GPC['op']:'display';
$profile = pdo_fetch('SELECT * FROM '.tablename('fm453_shopping_member')." WHERE uniacid = :uniacid  AND from_user = :from_user" , array(':uniacid' => $_W['uniacid'],':from_user' => $from_user));
$id = $profile['id'];
		if(intval($profile['id']) && $profile['status']==0){
			include $this->template($appstyle.'forbidden');
			exit;
		}
		if(empty($profile)){
			message('请先注册',$this->mturl('register'),'error');
			exit;
		}

		if($op=='display'){
			$commtime = pdo_fetch("select commtime, promotertimes from ".tablename('fm453_shopping_rules')." where uniacid = ".$_W['uniacid']);
			$commissioningpe =0;
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


			// 总佣金
			$commissioning = pdo_fetchcolumn("select sum(commission) from ".tablename('fm453_shopping_commission')." where flag = 0 and mid = ".$profile['id']." and uniacid = ".$_W['uniacid']);
			$commissioning = empty($commissioning)?0:$commissioning;
			// 可结佣
			//	$commissioningpe = $commissioningpe-$profile['commission'];

			// 已结佣
			$commissioned = $profile['commission'];
			$total = pdo_fetchcolumn("select count(id) from ". tablename('fm453_shopping_commission'). " where mid =". $profile['id']. " and flag = 0");
			if($_GPC['opp'] == 'more'){
				$opp = 'more';
				$pindex = max(1, intval($_GPC['page']));
				$psize = 15;
				// 账户充值记录
				$list = pdo_fetchall("select co.isshare,co.commission, co.createtime, og.orderid, og.goodsid, og.total,oo.ordersn from ". tablename('fm453_shopping_commission'). " as co left join ".tablename('fm453_shopping_order_goods')." as og on co.ogid = og.id and co.uniacid = og.uniacid left join ".tablename('fm453_shopping_order')." as oo on oo.id = og.orderid and co.uniacid = og.uniacid where co.mid =". $profile['id']. " and co.flag = 0 ORDER BY co.createtime DESC limit ".($pindex - 1) * $psize . ',' . $psize);
				$pager = pagination1($total, $pindex, $psize);
			}else{
				// 账户充值记录
				$list = pdo_fetchall("select co.isshare,co.commission, co.createtime, og.orderid, og.goodsid, og.total,oo.ordersn from ". tablename('fm453_shopping_commission'). " as co left join ".tablename('fm453_shopping_order_goods')." as og on co.ogid = og.id and co.uniacid = og.uniacid left join ".tablename('fm453_shopping_order')." as oo on oo.id = og.orderid and co.uniacid = og.uniacid where co.mid =". $profile['id']. " and co.flag = 0 ORDER BY co.createtime DESC limit 10");
			}
			$addresss = pdo_fetchall("select id, realname from ".tablename('fm453_shopping_address')." where uniacid = ".$_W['uniacid']);
			$address = array();
			foreach($addresss as $adr){
				$address[$adr['id']] = $adr['realname'];
			}
			$goods = pdo_fetchall("select id, title from ".tablename('fm453_shopping_goods')." where uniacid = ".$_W['uniacid']);
			$good = array();
			foreach($goods as $g){
				$good[$g['id']] = $g['title'];
			}
		}

		// 申请佣金
		if($op=='commapply'){
			// 提现周期
			$commtime = pdo_fetch("select commtime, promotertimes from ".tablename('fm453_shopping_rules')." where uniacid = ".$_W['uniacid']);
			if(empty($commtime) && $commtime['commtime']<0){
				message("此功能还未开放，请耐心等待...");
			}
			$moneytime = time()-3600*24*$commtime['commtime'];
			$pindex = max(1, intval($_GPC['page']));
			$psize = 15;
			$user = pdo_fetch("select * from ".tablename('fm453_shopping_member')." where from_user = '".$from_user."'");
			$list = pdo_fetchall("SELECT o.createtime, g.commission, g.total, g.goodsid, g.id,o.ordersn FROM " .tablename('fm453_shopping_order')." as o left join ".tablename('fm453_shopping_order_goods')." as g on o.id = g.orderid and o.uniacid = g.uniacid WHERE o.shareid = ".$id." and o.uniacid = ".$_W['uniacid']." and g.status = 0 and o.status >= 3 and o.from_user != '".$from_user."' and g.createtime < ".$moneytime." and g.createtime>=".$user['flagtime']." ORDER BY o.createtime DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
			$total = pdo_fetchcolumn("SELECT count(g.id) FROM " .tablename('fm453_shopping_order')." as o left join ".tablename('fm453_shopping_order_goods')." as g on o.id = g.orderid and o.uniacid = g.uniacid WHERE o.shareid = ".$id." and o.uniacid = ".$_W['uniacid']." and o.status = 3 and g.createtime < ".$moneytime." and g.createtime>=".$user['flagtime']);

			if($profile['flag']==0){
				if($total>=$commtime['promotertimes']){
					pdo_update('fm453_shopping_member', array('flag'=>1), array('id'=>$profile['id']));
					$profile['flag'] = 1;
				}
			}
			$pager = pagination1($total, $pindex, $psize);
			$goods = pdo_fetchall("select id, title from ".tablename('fm453_shopping_goods'). " where uniacid = ".$_W['uniacid']. " and status = 1");
			$good = array();
			foreach($goods as $g){
				$good[$g['id']] = $g['title'];
			}
			include $this->template($appstyle.'commapply');
			exit;
		}
		// 处理申请
		if($op=='applyed'){
			if($profile['flag']==0){
				message('申请佣金失败！');
			}
			$isbank = pdo_fetch("select id, bankcard, banktype from ".tablename('fm453_shopping_member')." where uniacid = ".$_W['uniacid']." and from_user = '".$from_user."'");
			if(empty($isbank['bankcard']) || empty($isbank['banktype'])){
				message('请先完善银行卡信息！', $this->mturl('bankcard', array('id'=>$isbank['id'], 'opp'=>'complated')), 'error');
			}
			$update = array(
				'status'=>1,
				'applytime'=>time()
			);
			// 申请订单ID数组
			$selected = explode(',',trim($_GPC['selected']));
			for($i=0; $i<sizeof($selected); $i++){
				$temp = pdo_update('fm453_shopping_order_goods', $update, array('id'=>$selected[$i]));
			}
			if(!$temp){
				message('申请失败，请重新申请！', $this->mturl('commission', array('op'=>'commapply')), 'error');
			}else{
				message('申请成功！', $this->mturl('commission'), 'success');
			}
		}

	//自定义页面默认的分享内容
$_share = array();
$_share['title']=$this->module['config']['brands']['shopname'];
$_share['link']=$_W['siteroot'].'app'. ltrim($this->createMobileUrl('commission'),'.');
$_share['link']=$_share['link'].$url_condition;
//print_r($_share['link']);
$_share['imgUrl']=$_W['attachurl'].$this->module['config']['brands']['logo'];
$_share['desc']=htmlspecialchars_decode($this->module['config']['brands']['description']);
$_share['desc']= preg_replace("/<(.*?)>/","",$_share['desc']);
if(!empty($shareid)){
	fmFunc_share_click($lastid,$shareid,$currentid,$share_user);
}

//include $this->template($appstyle.$do.'/453');
include $this->template($appstyle.$do.'/old_'.$ac.'/index');
