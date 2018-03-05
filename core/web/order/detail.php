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
 * @remark 订单详情；
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;
load()->func('tpl');
load()->model('account');//加载公众号函数
load()->model('mc');
fm_load()->fm_model('order'); //订单管理模块
fm_load()->fm_model('goods'); //产品管理模块
fm_load()->fm_func('order'); //订单处理函数
fm_load()->fm_func('express'); //快递配送处理函数

//加载风格模板及资源路径

$fm453style = fmFunc_ui_shopstyle();
$fm453resource =FM_RESOURCE;

//入口判断
$routes=fmFunc_route_web();
$routes_do=fmFunc_route_web_do();
$do= $_GPC['do'];
$ac=$_GPC['ac'];
$all_ac=fmFunc_route_web_ac($do);
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$all_op=fmFunc_route_web_op_single($do,$ac);
if(!in_array($ac,$all_ac) || !in_array($operation,$all_op)){
	die(message('非法访问，请通过有效路径进入！'));
}

fmMod_privilege_adm();//检查用户授权

//开始操作管理
$shopname=$_FM['settngs']['brands']['shopname'];
$shopname = !empty($shopname) ? $shopname :FM_NAME_CN;
$_W['page']['title'] = $shopname.$routes[$do]['title'];
$direct_url=fm_wurl($do,$ac,$operation,'');

$uniacid=$_W['uniacid'];
$plattype=$settings['plattype'];
$platids = fmFunc_getPlatids();//获取平台模式关联的公众号商户ID参数
$platid=$_W['uniacid'];
$oauthid=$platids['oauthid'];
$fendianids=$platids['fendianids'];
$supplydianids=$platids['supplydianids'];
$blackids=$platids['blackids'];

$gtpltype = fmMod_goodstpl_type_get();//列出全部产品模型清单
$marketmodeltypes =fmFunc_market_types();
$allorderstatus=fmFunc_status_get('order');
$paytype=fmFunc_status_get('paytype');
$datatype= fmFunc_data_types();

//平台模式处理
include_once FM_PUBLIC.'plat.php';
$supplydians=explode(',',$supplydianids);//字符串转数组
$supplydians=array_filter($supplydians);//数组去空

//按平台模式前置筛选条件
$condition=' WHERE ';
$params=array();
include_once FM_PUBLIC.'forsearch.php';

$limit=" LIMIT ".($pindex-1)*$psize.",".$psize;
$mlist = pdo_fetchall("SELECT `name`,`title` FROM ".tablename('modules'),array(),'name');
$ordersn = $_GPC['ordersn'];
$isArticle= (stripos($ordersn, 'A')===0);	//是否文章打赏订单
if($isArticle)
{
	message("文章打赏类订单，暂不支持直接查看!", referer(), "info");
}
$result = fmMod_order_detail($ordersn);
$item = $result['data'];

if (empty($item)) {
	message("抱歉，订单不存在!", referer(), "error");
}
$goodsName = '';
$goodsAdm = '';
foreach($item['allgoods'] as $item_goods){
	$goodsName .= $item_goods['detail']['title'];
	$goodsAdm .= $item_goods['detail']['goodadm'].',';
}
$goodsAdm = explode(',',$goodsAdm);
$goodsAdm = array_unique($goodsAdm);

if($operation=='display'){
	$id=$item['id'];
	$userid = $item['fromuid'];
	$defaultaddress = fmMod_member_address($userid,1);    //默认的收货地址

	$value = $item;
	$paylogs=$item['paylog'];
	$webpayid = $paylogs[0];

	$expresses=fmFunc_express_dispatch_list();
	if (checksubmit('confirmsend')) {
		if (!empty($_GPC['isexpress']) && empty($_GPC['expresssn'])) {
			message('请输入快递单号！');
		}
		if (!empty($item['transid'])) {
			fmFunc_order_WechatSend($ordersn, 1);
		}
		pdo_update('fm453_shopping_order',
			array(
				'status' => 2,
				'express' => $_GPC['express'],
				'expresscom' => $_GPC['expresscom'],
				'expresssn' => $_GPC['expresssn'],
			),
			 array('id' => $id)
		);
		$logs ='确认发货，快递单号：'.$_GPC['expresssn'];
			$logs .='---by  ' . $_W['username'];
			$logs .='(UID:'. $_W['uid'] .')操作时间:' . date('y年m月d日 h:i:s', $timestamp =  TIMESTAMP);
			$logs .='；||';
			$logs .=$value['logs'];
			pdo_update('fm453_shopping_order', array('logs' => $logs ), array('id' => $id));

			//写入操作日志START
			$dologs=array(
				'url'=>$_W['siteurl'],
				'description'=>'商城后台发货；',
				'addons'=>$logs,
			);
			fmMod_log_record($platid,$_W['uniacid'],$_W['uid'],$fanid='',$openid,'fm453_shopping_order',$uid,'update',$dologs);
			unset($dologs);
			//写入操作日志END
		//给管理员发个微信消息
		include_once MODULE_ROOT.'/core/msgtpls/tpls/order.php';
		$noticeData = $tplnotice_data['order']['send']['admin'];
		$msgResult = fmMod_notice_tpl($noticeData);
		if(!$msgResult) {
			include_once MODULE_ROOT.'/core/msgtpls/msg/order.php';
			$noticeData = $notice_data['order']['send']['admin'];
			fmMod_notice($settings['manageropenids'],$noticeData);
		}
		$noticeData = $tplnotice_data['order']['send']['goodsadm'];
		$msgResult = fmMod_notice_tpl($noticeData);
		if(!$msgResult) {
			include_once MODULE_ROOT.'/core/msgtpls/msg/order.php';
			$noticeData = $notice_data['order']['send']['goodsadm'];
			fmMod_notice($goodsAdm,$noticeData);
		}

		$noticeData = $tplnotice_data['order']['send']['buyer'];
		$msgResult = fmMod_notice_tpl($noticeData);
		if(!$msgResult) {
			include_once MODULE_ROOT.'/core/msgtpls/msg/order.php';
			$noticeData = $notice_data['order']['send']['buyer'];
			fmMod_notice($item['fromuser'],$noticeData);
		}
		message('发货操作成功！', referer(), 'success');
	}

	if (checksubmit('cancelsend')) {
		$item = pdo_fetch("SELECT transid FROM " . tablename('fm453_shopping_order') . " WHERE ordersn LIKE :ordersn",  array(':ordersn' => $ordersn));
		if (!empty($item['transid'])) {
			fmFunc_order_WechatSend($ordersn, 0, $_GPC['cancelreson']);
		}
		pdo_update('fm453_shopping_order',
			array(
				'status' => 1,
				'remark_kf' => $_GPC['remark_kf'],
			),
			array('id' => $id)
		);
		$logs ='取消发货';
			$logs .='---by  ' . $_W['username'];
			$logs .='(UID:'. $_W['uid'] .')操作时间:' . date('y年m月d日 h:i:s', $timestamp =  TIMESTAMP);
			$logs .='；||';
			$logs .=$value['logs'];
			pdo_update('fm453_shopping_order', array('logs' => $logs ), array('id' => $id));

			//写入操作日志START
			$dologs=array(
				'url'=>$_W['siteurl'],
				'description'=>'商城后台取消发货；',
				'addons'=>$logs,
			);
			fmMod_log_record($platid,$_W['uniacid'],$_W['uid'],$fanid='',$openid,'fm453_shopping_order',$uid,'update',$dologs);
			unset($dologs);
			//写入操作日志END
		//给管理员发个微信消息
		include_once MODULE_ROOT.'/core/msgtpls/msg/order.php';
			fmMod_notice($settings['manageropenids'],$notice_data['order']['cancelsend']['admin']);

		message('取消发货操作成功！', referer(), 'success');
		}
		if (checksubmit('kfbeizhu')) {
			$remark_kf =$_GPC['remark_kf'];
			$remark_kf .='---by  ' . $_W['username'];
			$remark_kf .='(UID:'. $_W['uid'] .')操作时间:' . date('y年m月d日 h:i:s', $timestamp =  TIMESTAMP);
			$remark_kf .='；';
			pdo_update('fm453_shopping_order', array('remark_kf' => $remark_kf ), array('id' => $id));
			$logs ='添加客服备注:'.$_GPC['remark_kf'];
			$logs .='---by  ' . $_W['username'];
			$logs .='(UID:'. $_W['uid'] .')操作时间:' . date('y年m月d日 h:i:s', $timestamp =  TIMESTAMP);
			$logs .='；||';
			$logs .=$value['logs'];
			pdo_update('fm453_shopping_order', array('logs' => $logs ), array('id' => $id));
			//写入操作日志START
			$dologs=array(
				'url'=>$_W['siteurl'],
				'description'=>'商城后台添加客服备注；',
				'addons'=>$logs,
			);
			fmMod_log_record($platid,$_W['uniacid'],$_W['uid'],$fanid='',$openid,'fm453_shopping_order',$uid,'update',$dologs);
			unset($dologs);
			//写入操作日志END
		//给管理员发个微信消息
		include_once MODULE_ROOT.'/core/msgtpls/msg/order.php';
			fmMod_notice($settings['manageropenids'],$notice_data['order']['kefubeizhu']['admin']);

			message('客服备注成功！', referer(), 'success');
		}
		if (checksubmit('changeprice') ) {
			//未付款的订单才可改价
			if($value['originaldata']['status'] ==0) {
				//改价前，先软清支付记录（在原tid前加上删除标记）
				$ischanged=pdo_update('core_paylog', array('tid' => 'DEL'.$ordersn), array('plid' => $webpayid['plid']));//修改支付记录表中的金额记录；
				if($ischanged) {
					pdo_update('fm453_shopping_order', array('price' => $_GPC['newprice']), array('id' => $id));//修改订单中的价格
					$logs ='改价：:'.$_GPC['newprice'];
					$logs .='---by  ' . $_W['username'];
					$logs .='(UID:'. $_W['uid'] .')操作时间:' . date('y年m月d日 h:i:s', $timestamp =  TIMESTAMP);
					$logs .='；||';
					$logs .=$value['logs'];
					pdo_update('fm453_shopping_order', array('logs' => $logs ), array('id' => $id));

			//写入操作日志START
			$dologs=array(
				'url'=>$_W['siteurl'],
				'description'=>'商城后台改价；',
				'addons'=>$logs,
			);
			fmMod_log_record($platid,$_W['uniacid'],$_W['uid'],$fanid='',$openid,'fm453_shopping_order',$uid,'update',$dologs);
			unset($dologs);
			//写入操作日志END
		//给管理员发个微信消息
		include_once MODULE_ROOT.'/core/msgtpls/msg/order.php';
			fmMod_notice($settings['manageropenids'],$notice_data['order']['changeprice']['admin']);

					message('改价成功，请通知客人完成支付！', referer(), 'success');
				}else{
					message('订单不是待支付状态；错误状态码为'.$value['status'].'！', referer(), 'error');
				}
			}
		}
		if (checksubmit('finish')) {
			pdo_update('fm453_shopping_order', array('status' => 3, 'remark_kf' => $_GPC['remark_kf']), array('id' => $id));
			$logs ='确认完成订单';
			$logs .='---by  ' . $_W['username'];
			$logs .='(UID:'. $_W['uid'] .')操作时间:' . date('y年m月d日 h:i:s', $timestamp =  TIMESTAMP);
			$logs .='；||';
			$logs .=$value['logs'];
			pdo_update('fm453_shopping_order', array('logs' => $logs ), array('id' => $id));
			//写入操作日志START
			$dologs=array(
				'url'=>$_W['siteurl'],
				'description'=>'商城后台确认完成订单；',
				'addons'=>$logs,
			);
			fmMod_log_record($platid,$_W['uniacid'],$_W['uid'],$fanid='',$openid,'fm453_shopping_order',$uid,'update',$dologs);
			unset($dologs);
			//写入操作日志END
		//给管理员发个微信消息
		include_once MODULE_ROOT.'/core/msgtpls/msg/order.php';
			fmMod_notice($settings['manageropenids'],$notice_data['order']['finish']['admin']);
			message('订单操作成功！', referer(), 'success');
		}
		if (checksubmit('cancel')) {
			pdo_update('fm453_shopping_order', array('status' => 1, 'remark_kf' => $_GPC['remark_kf']), array('id' => $id));
			$logs ='取消订单';
			$logs .='---by  ' . $_W['username'];
			$logs .='(UID:'. $_W['uid'] .')操作时间:' . date('y年m月d日 h:i:s', $timestamp =  TIMESTAMP);
			$logs .='；||';
			$logs .=$value['logs'];
			pdo_update('fm453_shopping_order', array('logs' => $logs ), array('id' => $id));
		}
		if (checksubmit('cancelpay')) {
			pdo_update('fm453_shopping_order', array('status' => 0, 'remark_kf' => $_GPC['remark_kf']), array('id' => $id));
			//设置库存
			fmFunc_order_setStock($id, false);
			//减少积分
			fmFunc_order_setCredit($ordersn, '-');
			$logs ='取消订单付款';
			$logs .='---by  ' . $_W['username'];
			$logs .='(UID:'. $_W['uid'] .')操作时间:' . date('y年m月d日 h:i:s', $timestamp =  TIMESTAMP);
			$logs .='；||';
			$logs .=$value['logs'];
			pdo_update('fm453_shopping_order', array('logs' => $logs ), array('id' => $id));
			message('取消订单付款操作成功！', referer(), 'success');
		}
		if (checksubmit('confrimpay')) {
			pdo_update('fm453_shopping_order', array('status' => 1, 'paytype' => 2, 'remark_kf' => $_GPC['remark_kf']), array('id' => $id));
			//设置库存
			fmFunc_order_setStock($id);
			//增加积分
    		fmFunc_order_setCredit($ordersn,"+");
    		$logs ='后台手动确认订单付款';
			$logs .='---by  ' . $_W['username'];
			$logs .='(UID:'. $_W['uid'] .')操作时间:' . date('y年m月d日 h:i:s', $timestamp =  TIMESTAMP);
			$logs .='；||';
			$logs .=$value['logs'];
			pdo_update('fm453_shopping_order', array('logs' => $logs ), array('id' => $id));
			message('确认订单付款操作成功！', referer(), 'success');
		}
		if (checksubmit('close')) {
			$item = pdo_fetch("SELECT transid FROM " . tablename('fm453_shopping_order') . " WHERE id = :id", array(':id' => $id));
			if (!empty($item['transid'])) {
				fmFunc_order_WechatSend($id, 0, $_GPC['reason']);
			}
			pdo_update('fm453_shopping_order', array('status' => -1, 'remark_kf' => $_GPC['remark_kf']), array('id' => $id));
			$logs ='关闭订单';
			$logs .='---by  ' . $_W['username'];
			$logs .='(UID:'. $_W['uid'] .')操作时间:' . date('y年m月d日 h:i:s', $timestamp =  TIMESTAMP);
			$logs .='；||';
			$logs .=$value['logs'];
			pdo_update('fm453_shopping_order', array('logs' => $logs ), array('id' => $id));
			message('订单关闭操作成功！', referer(), 'success');
		}
		if (checksubmit('open')) {
			$logs ='开启订单';
			$logs .='---by  ' . $_W['username'];
			$logs .='(UID:'. $_W['uid'] .')操作时间:' . date('y年m月d日 h:i:s', $timestamp =  TIMESTAMP);
			$logs .='；||';
			$logs .=$value['logs'];
			pdo_update('fm453_shopping_order', array('logs' => $logs ), array('id' => $id));
			pdo_update('fm453_shopping_order', array('status' => 0, 'remark_kf' => $_GPC['remark_kf']), array('id' => $id));
			message('开启订单操作成功！', referer(), 'success');
		}
		//订单详情页删除订单按钮
		if (checksubmit('deleteorder')) {
				pdo_update('fm453_shopping_order', array('deleted' => '1'), array('id' => $item['id']));	//deleted为1时，删除；为0时保留
			//写入操作日志START
			$dologs=array(
				'url'=>$_W['siteurl'],
				'description'=>'商城后台删除订单；',
				'addons'=>$logs,
			);
			fmMod_log_record($platid,$_W['uniacid'],$_W['uid'],$fanid='',$openid,'fm453_shopping_order',$uid,'update',$dologs);
			unset($dologs);
			//写入操作日志END
		//给管理员发个微信消息
		include_once MODULE_ROOT.'/core/msgtpls/msg/order.php';
			fmMod_notice($settings['manageropenids'],$notice_data['order']['delete']['admin']);

				message('订单删除操作成功！',fm_wurl('order', 'list', '', array()), 'success');
		}
		//订单详情页恢复订单按钮
		if (checksubmit('recoveryorder')) {
			pdo_update('fm453_shopping_order', array('deleted' => '0'), array('id' => $item['id']));
			message('订单恢复操作成功！', referer(), 'success');
		}
		// 订单取消
		if (checksubmit('cancelorder')) {
			if ($item['status'] == 1) {
				load()->model('mc');
				$memberId = mc_openid2uid($item['from_user']);
				mc_credit_update($memberId, 'credit2', $item['price'], array($_W['uid'], '嗨旅行微商城取消订单，系统退回款项到会员余额'));
			}
			$logs ='后台取消订单';
			$logs .='---by  ' . $_W['username'];
			$logs .='(UID:'. $_W['uid'] .')操作时间:' . date('y年m月d日 h:i:s', $timestamp =  TIMESTAMP);
			$logs .='；||';
			$logs .=$value['logs'];
			pdo_update('fm453_shopping_order', array('logs' => $logs ), array('id' => $id));
			pdo_update('fm453_shopping_order', array('status' => '-1'), array('id' => $item['id']));

			//写入操作日志START
			$dologs=array(
				'url'=>$_W['siteurl'],
				'description'=>'商城后台取消订单；',
				'addons'=>$logs,
			);
			fmMod_log_record($platid,$_W['uniacid'],$_W['uid'],$fanid='',$openid,'fm453_shopping_order',$uid,'update',$dologs);
			unset($dologs);
			//写入操作日志END
		//给管理员发个微信消息
		include_once MODULE_ROOT.'/core/msgtpls/msg/order.php';
			fmMod_notice($settings['manageropenids'],$notice_data['order']['cancel']['admin']);
			message('订单取消操作成功！', referer(), 'success');
		}
	//URL追加参数，方便同一主程序间调用关联
	$urladdons=array(
		'ordersn'=>$ordersn,
	);
	include $this->template($fm453style.$do.'/453');
}
elseif($operation=='print') {
		$qrcodeurl['mp']=$_W['siteroot']. '/attachment/qrcode_'.$_W['acid'].'.jpg';//从公众号的系统配置中提取二维码图片
		$id=$orderid=$item['id'];
		$link_preview =  fm_murl('myorder','index','detail',array('id' => $orderid));
		$qrcodeurl['order']=fmFunc_qrcode_name_m($platid,'detail','index','display',$id,0,0);
		fmFunc_qrcode_check($qrcodeurl['order'],$link_preview);
		$qrcodeurl['order']=tomedia($qrcodeurl['order']);
	//URL追加参数，方便同一主程序间调用关联
	$urladdons=array(
		'ordersn'=>$ordersn,
	);
		include $this->template($fm453style.$do.'/453');
}
