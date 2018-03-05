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
 * @remark 积分商城兑换
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;
load()->func('tpl');
fm_load()->fm_func('share');//链接分享管理
fm_load()->fm_func('view'); 	//浏览量处理
fm_load()->fm_func('wechat');//微信定义管理

$appstyle =$this->appstyle();
$fm453resource =FM_RESOURCE;
$appsrc =fmFunc_ui_appsrc();
$pagename = "余额";

$from_user =$this->getFromUser();
$uniacid=$_W['uniacid'];
$award_id = intval($_GPC['award_id']);
if (!empty($_GPC['award_id'])){
	$fans = fans_search($from_user , array('credit1'));
	$award_info = pdo_fetch("SELECT * FROM ".tablename('fm453_shopping_credit_award')." WHERE award_id = $award_id AND uniacid = '{$_W['uniacid']}'");
	if ($fans['credit1'] >= $award_info['credit_cost'] && $award_info['amount'] > 0){
		$data = array(
			'amount' => $award_info['amount'] - 1
		);
		pdo_update('fm453_shopping_credit_award', $data, array('uniacid' => $_W['uniacid'], 'award_id' => $award_id));
		$data = array(
			'uniacid' => $_W['uniacid'],
			'from_user' => $from_user ,
			'award_id' => $award_id,
			'createtime' => TIMESTAMP
		);
		pdo_insert('fm453_shopping_credit_request', $data);
		$data = array(
			'realname' => $_GPC['realname'],
			'mobile' => $_GPC['mobile'],
			'credit1' => $fans['credit1'] - $award_info['credit_cost'],
			'residedist' => $_GPC['residedist'],
		);
		fans_update($from_user , $data);
		// navigate to user profile page
		message('积分兑换成功！', create_url('mobile/module/mycredit', array('uniacid' => $_W['uniacid'], 'name' => 'fm453_shopping', 'do' => 'mycredit','op' => 'display')), 'success');
	}else{
		message('积分不足或商品已经兑空，请重新选择商品！<br>当前商品所需积分:'.$award_info['credit_cost'].'<br>您的积分:'.$fans['credit1']
			. '. 商品剩余数量:' . $award_info['amount']
			. '<br><br>小提示：<br>每日签到，在线订票、订房、购物等，都可以赚取积分哦',
			create_url('mobile/module/award', array('uniacid' => $_W['uniacid'], 'name' => 'fm453_shopping')), 'error');
	}
}else{
	message('请选择要兑换的商品！', create_url('mobile/module/award', array('uniacid' => $_W['uniacid'], 'name' => 'fm453_shopping')), 'error');
}
