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
 * @remark 消息模板设置；
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;
load()->func('tpl');
load()->func('file');
load()->model('account');//加载公众号函数

//加载风格模板及资源路径
$fm453style = fmFunc_ui_shopstyle();
$fm453resource =FM_RESOURCE;

//入口判断
$routes=fmFunc_route_web();
$routes_do=fmFunc_route_web_do();
$do=$_GPC['do'];
$ac=$_GPC['ac'];
$all_ac=fmFunc_route_web_ac($do);
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$all_op=fmFunc_route_web_op_single($do,$ac);
if(!in_array($ac,$all_ac) || !in_array($operation,$all_op)){
	die(message('非法访问，请通过有效路径进入！'));
}

fmMod_privilege_adm();//检查用户授权

//开始操作管理
$shopname=$settings['brands']['shopname'];
$shopname = !empty($shopname) ? $shopname : FM_NAME_CN;
$_W['page']['title'] = $shopname.'品牌信息设置';

$fm453industry =$settings['industry'];
if (empty($fm453industry)) {
	$fm453industry='ebiz';
}
if($fm453industry=='ebiz') {
	$fm453industry_name='所在行业：IT科技/互联网/电子商务';
}elseif($fm453industry=='hotel') {
	$fm453industry_name='所在行业：酒店住宿';
}elseif($fm453industry=='ota') {
	$fm453industry_name='所在行业：旅行社';
}else{
	$fm453industry_name='所在行业：默认';
}

if($operation=='display') {

	include $this->template('modset/msgtpls_'.$fm453industry);
}
elseif($operation=='modify') {
	$industry=$_GPC['industry'];
	if($industry=='ota') {
		$msgtpls=array(
//旅行社通知模板
			'ota_order_status' => $_GPC['ota_order_status'],        //订单状态变更通知
			'ota_book_success' => $_GPC['ota_book_success'],    //预订成功通知
			'ota_ask_responce' => $_GPC['ota_ask_responce'],      //咨询服务跟踪通知
			'ota_order_addinfos' => $_GPC['ota_order_addinfos'],    //订单信息补充提醒
			'ota_hotel_confirm' => $_GPC['ota_hotel_confirm'],      //酒店预订确认通知
			'ota_book_service' => $_GPC['ota_book_service'],      //上门服务预约通知
			'ota_activity_success' => $_GPC['ota_activity_success'],   //活动报名成功通知
			'ota_money_refun' => $_GPC['ota_money_refun'],        //退款通知
			'ota_credit_exchange' => $_GPC['ota_credit_exchange'],    //积分兑换服务通知
			'ota_coupon_confirm' => $_GPC['ota_coupon_confirm'],    //卡券核验通知
			'ota_goods_confirm' => $_GPC['ota_goods_confirm'],      //确认收货通知
			'ota_order_fenxiao' => $_GPC['ota_order_fenxiao'],      //分销订单下单成功通知
			'ota_cash_to_fenxiao' => $_GPC['ota_cash_to_fenxiao'],    //分销佣金结算通知
			'ota_cash_to_confirm' => $_GPC['ota_cash_to_confirm'],  //返现到账通知
			'ota_gift_code_send' => $_GPC['ota_gift_code_send'],    //返礼码获取通知
		);
	}
	elseif($industry=='ebiz'){
		$msgtpls=array(
	//电子商务通知模板
			'ebiz_order_new' => $_GPC['ebiz_order_new'],        //订单生成通知
			'ebiz_task' => $_GPC['ebiz_task'],    //任务处理通知
			'ebiz_order_post' => $_GPC['ebiz_order_post'],    //订单提交成功通知
			'ebiz_order_cancel' => $_GPC['ebiz_order_cancel'],      //订单取消通知
			'ebiz_order_payed' => $_GPC['ebiz_order_payed'],    //订单支付成功通知
			'ebiz_order_send' => $_GPC['ebiz_order_send'],      //订单发货通知
			'ebiz_book_service' => $_GPC['ebiz_book_service'],      //订单确认收货通知
			'ebiz_memlevel_up' => $_GPC['ebiz_memlevel_up'],   //会员升级通知
			'ebiz_charged' => $_GPC['ebiz_charged'],    //充值成功通知
			'ebiz_refun_send' => $_GPC['ebiz_refun_send'],        //退款申请通知
			'ebiz_refun_refuse' => $_GPC['ebiz_refun_refuse'],    //退款申请驳回通知
			'ebiz_case_send' => $_GPC['ebiz_case_send'],    //提现提交通知
			'ebiz_case_success' => $_GPC['ebiz_case_success'],      //提现成功通知
			'ebiz_case_fail' => $_GPC['ebiz_case_fail'],      //提现失败通知
		);
	}
	elseif($industry=='hotel'){
		$msgtpls=array(
	//酒店通知模板
			'hotel_order_status' => $_GPC['hotel_order_status'],        //订单状态变更通知
			'hotel_book_success' => $_GPC['hotel_book_success'],    //预订成功通知
			'hotel_ask_responce' => $_GPC['hotel_ask_responce'],      //咨询服务跟踪通知
			'hotel_order_addinfos' => $_GPC['hotel_order_addinfos'],    //订单信息补充提醒
			'hotel_confirm' => $_GPC['hotel_confirm'],      //酒店预订确认通知
			'hotel_book_service' => $_GPC['hotel_book_service'],      //上门服务预约通知
			'hotel_activity_success' => $_GPC['hotel_activity_success'],   //活动报名成功通知
			'hotel_money_refun' => $_GPC['hotel_money_refun'],        //退款通知
			'hotel_credit_exchange' => $_GPC['hotel_credit_exchange'],    //积分兑换服务通知
			'hotel_coupon_confirm' => $_GPC['hotel_coupon_confirm'],    //卡券核验通知
			'hotel_goods_confirm' => $_GPC['hotel_goods_confirm'],      //确认收货通知
			'hotel_order_fenxiao' => $_GPC['hotel_order_fenxiao'],      //分销订单下单成功通知
			'hotel_cash_to_fenxiao' => $_GPC['hotel_cash_to_fenxiao'],    //分销佣金结算通知
			'hotel_cash_to_confirm' => $_GPC['hotel_cash_to_confirm'],  //返现到账通知
			'hotel_gift_code_send' => $_GPC['hotel_gift_code_send'],    //返礼码获取通知
		);
	}
	$setfor='msgtpls';
	$record = array();
	$record['value']=$msgtpls;
	$record['status']='127';
	$record=iserializer($record);
	$result=fmMod_setting_save($record,$setfor,$_W['uniacid']);
	if($result['result']) {
		//写入操作日志START
		$dologs=array(
			'url'=>$_W['siteurl'],
			'description'=>'消息模板设置',
			'addons'=>$msgtpls
		);
		fmMod_log_record($platid,$_W['uniacid'],$_W['uid'],$fanid,$openid,'fm453_shopping_settings',$id,'modify',$dologs);
		unset($dologs);
		//写入操作日志END
		message('消息模板保存成功','referer','success');
	}else{
		message('消息模板保存失败，原因：'.$result['msg'],'referer','error');
	}
}
elseif($operation=='test'){
		$msgtpls=array(
//旅行社通知模板
    'ota_order_status' => $_GPC['ota_order_status'],        //订单状态变更通知
    'ota_book_success' => $_GPC['ota_book_success'],    //预订成功通知
    'ota_ask_responce' => $_GPC['ota_ask_responce'],      //咨询服务跟踪通知
    'ota_order_addinfos' => $_GPC['ota_order_addinfos'],    //订单信息补充提醒
    'ota_hotel_confirm' => $_GPC['ota_hotel_confirm'],      //酒店预订确认通知
     'ota_book_service' => $_GPC['ota_book_service'],      //上门服务预约通知
     'ota_activity_success' => $_GPC['ota_activity_success'],   //活动报名成功通知
    'ota_money_refun' => $_GPC['ota_money_refun'],        //退款通知
    'ota_credit_exchange' => $_GPC['ota_credit_exchange'],    //积分兑换服务通知
    'ota_coupon_confirm' => $_GPC['ota_coupon_confirm'],    //卡券核验通知
    'ota_goods_confirm' => $_GPC['ota_goods_confirm'],      //确认收货通知
    'ota_order_fenxiao' => $_GPC['ota_order_fenxiao'],      //分销订单下单成功通知
    'ota_cash_to_fenxiao' => $_GPC['ota_cash_to_fenxiao'],    //分销佣金结算通知
    'ota_cash_to_confirm' => $_GPC['ota_cash_to_confirm'],  //返现到账通知
    'ota_gift_code_send' => $_GPC['ota_gift_code_send'],    //返礼码获取通知
//电子商务通知模板
    'ebiz_order_new' => $_GPC['ebiz_order_new'],        //订单生成通知
    'ebiz_task' => $_GPC['ebiz_task'],    //任务处理通知
    'ebiz_order_post' => $_GPC['ebiz_order_post'],    //订单提交成功通知
    'ebiz_order_cancel' => $_GPC['ebiz_order_cancel'],      //订单取消通知
    'ebiz_order_payed' => $_GPC['ebiz_order_payed'],    //订单支付成功通知
    'ebiz_order_send' => $_GPC['ebiz_order_send'],      //订单发货通知
     'ebiz_book_service' => $_GPC['ebiz_book_service'],      //订单确认收货通知
     'ebiz_memlevel_up' => $_GPC['ebiz_memlevel_up'],   //会员升级通知
     'ebiz_charged' => $_GPC['ebiz_charged'],    //充值成功通知
    'ebiz_refun_send' => $_GPC['ebiz_refun_send'],        //退款申请通知
    'ebiz_refun_refuse' => $_GPC['ebiz_refun_refuse'],    //退款申请驳回通知
    'ebiz_case_send' => $_GPC['ebiz_case_send'],    //提现提交通知
    'ebiz_case_success' => $_GPC['ebiz_case_success'],      //提现成功通知
    'ebiz_case_fail' => $_GPC['ebiz_case_fail'],      //提现失败通知
//酒店通知模板
    'hotel_order_status' => $_GPC['hotel_order_status'],        //订单状态变更通知
    'hotel_book_success' => $_GPC['hotel_book_success'],    //预订成功通知
    'hotel_ask_responce' => $_GPC['hotel_ask_responce'],      //咨询服务跟踪通知
    'hotel_order_addinfos' => $_GPC['hotel_order_addinfos'],    //订单信息补充提醒
    'hotel_confirm' => $_GPC['hotel_confirm'],      //酒店预订确认通知
     'hotel_book_service' => $_GPC['hotel_book_service'],      //上门服务预约通知
     'hotel_activity_success' => $_GPC['hotel_activity_success'],   //活动报名成功通知
    'hotel_money_refun' => $_GPC['hotel_money_refun'],        //退款通知
    'hotel_credit_exchange' => $_GPC['hotel_credit_exchange'],    //积分兑换服务通知
    'hotel_coupon_confirm' => $_GPC['hotel_coupon_confirm'],    //卡券核验通知
    'hotel_goods_confirm' => $_GPC['hotel_goods_confirm'],      //确认收货通知
    'hotel_order_fenxiao' => $_GPC['hotel_order_fenxiao'],      //分销订单下单成功通知
    'hotel_cash_to_fenxiao' => $_GPC['hotel_cash_to_fenxiao'],    //分销佣金结算通知
    'hotel_cash_to_confirm' => $_GPC['hotel_cash_to_confirm'],  //返现到账通知
    'hotel_gift_code_send' => $_GPC['hotel_gift_code_send'],    //返礼码获取通知
	);
}
