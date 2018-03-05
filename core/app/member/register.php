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
 * @remark 代理人注册
 */
defined('IN_IA') or exit('Access Denied');
checkAuth();//先使用系统的检测授权机制让用户登陆或注册成为系统的会员

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
$shopname=$settings['brands']['shopname'];
$op = $_GPC['op']?$_GPC['op']:'display';
$profile = pdo_fetch('SELECT * FROM '.tablename('fm453_shopping_member')." WHERE `uniacid` = :uniacid AND from_user=:from_user ",array(':uniacid' => $_W['uniacid'],':from_user' => $from_user));
$id = $profile['id'];
$cfg = $this->module['config'];
if($op=='display'){
	$opp = $_GPC['opp'];
	$rule = pdo_fetch('SELECT * FROM '.tablename('fm453_shopping_rules')." WHERE `uniacid` = :uniacid ",array(':uniacid' => $_W['uniacid']));
	$fans = fans_search($from_user, array('realname'));
	if(empty($profile['realname'])){
			$profile['realname']=$fans['realname'];
	}
	if (checksubmit('gengxin')) {
		$data=array(
			'realname'=>$_GPC['realname'],
			'mobile'=>$_GPC['mobile'],
			'bankcard'=>$_GPC['bankcard'],
			'banktype'=>$_GPC['banktype'],
			'alipay'=>$_GPC['alipay'],
			'wxhao'=>$_GPC['wxhao'],
		);
		if(!empty($_GPC['password'])){
			$data['pwd'] =md5($_GPC['password']);
		}
		pdo_update('fm453_shopping_member',$data, array('id'=>$profile['id']));
	}

		//注册
			$shareid = 'fm453_shopping_sid'.$_W['uniacid'];
			$seid=$_COOKIE[$shareid];
			if(empty($seid)){
				$seid=0;
			}
	if (checksubmit('addreg')) {
			$theone = pdo_fetch('SELECT * FROM '.tablename('fm453_shopping_rules')." WHERE  uniacid = :uniacid" , array(':uniacid' => $_W['uniacid']));
			if($theone['promotertimes'] == 1){
				$data=array(
					'uniacid'=>$_W['uniacid'],
					'from_user'=> $from_user,
					'uid'=> $_W['member']['uid'],
					'realname'=>$_GPC['realname'],
					'mobile'=>$_GPC['mobile'],
					'alipay'=>$_GPC['alipay'],
					'wxhao'=>$_GPC['wxhao'],
					'commission'=>0,
					'createtime'=>TIMESTAMP,
					'flagtime'=>TIMESTAMP,
					'shareid'=> $seid,
					'status'=>0,//0为禁用，1为可用；
					'flag'=>1//1为推广人，0为非推广人；
				);
		if(!empty($_GPC['password'])){
			$data['pwd'] =md5($_GPC['password']);
		}
			}else{
				$data=array(
					'uniacid'=>$_W['uniacid'],
					'from_user'=> $from_user,
					'uid'=> $_W['member']['uid'],
					'realname'=>$_GPC['realname'],
					'mobile'=>$_GPC['mobile'],
					'alipay'=>$_GPC['alipay'],
					'wxhao'=>$_GPC['wxhao'],
					'commission'=>0,
					'createtime'=>TIMESTAMP,
					'flagtime'=>TIMESTAMP,
					'shareid'=> $seid,
					'status'=>0,//0为禁用，1为可用；
					'flag'=>0//1为推广人，0为非推广人；
				);
				if(!empty($_GPC['password'])){
			$data['pwd'] =md5($_GPC['password']);
		}
			}
			pdo_insert('fm453_shopping_member',$data);
			message('申请提交成功',$this->createMobileUrl('fansindex'),'success');
			}
}
//include $this->template($appstyle.'register');
include $this->template($appstyle.$do.'/453');
