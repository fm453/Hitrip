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
 * @remark：支付结果处理函数
 */
defined('IN_IA') or exit('Access Denied');
//转定义支付方式
function fmFunc_pay_types(){
	return $types=array(
		'yue'=>'wechat',	//钱包,直接发起微信支付
		'jifen'=>'credit1',	//扣减积分
		'kaquan'=>'credit'	//调用系统支付方式，可支持卡券
	);
}

//回调支付结果
/*
  @from=>string(6) "return"
  @result=>string(7) "success"
  @type=>string
  @tid =>string
*/
function FM_CHECK_PAY_RESULT($params){
	//接收支付系统回调信息并进行解析
	global $_W, $_GPC,$_FM;
	fm_load()->fm_func('wechat');//微信定义管理
	fm_load()->fm_model('log'); //日志模块
	fm_load()->fm_func('msg');//消息通知前置函数
	fm_load()->fm_model('notice');//消息通知模块
	$settings = $FM['settings'];

	$ordersn = $params['tid'];
	$isArticle = (stripos($ordersn, 'A')===0);	//是否文章打赏订单
	$isNeeds = (stripos($ordersn, 'N')===0);	//是否有求必应订单
	$isQuickpay = (stripos($ordersn, 'Q')===0);	//是否快捷支付订单
	$isVfoods = (stripos($ordersn, 'VFOOD')===0);	//是否微餐饮订单

	$fee = intval($params['fee']);

	if($isVfoods){
		//微餐饮订单处理
		$order = pdo_fetch("SELECT * FROM ".tablename('fm453_vfoods_order')." WHERE ordersn =:ordersn",array(':ordersn'=>$ordersn));
		if($order['address']){
			if(is_numeric($order['address'])){
				$addresses = $settings['vfoods']['waimai']['fanwei'];
				$order['address'] = trim($addresses[intval($order['address'])]);
			}
		}
		$orderid = $order['id'];
		pdo_update('fm453_vfoods_order', array('status' => 2), array('id' => $orderid));
		switch($params['type']){
			case 'credit':
				$paytype = "余额支付";
			break;
			case 'wechat':
				$paytype = "微信支付";
			break;
			default:
				$paytype = "在线支付";
			break;
		}

		if ($params['from'] == 'return') {
			$orderfoods = pdo_fetchall("SELECT * FROM ".tablename('fm453_vfoods_order_foods')." WHERE orderid =:orderid", array(':orderid'=>$orderid), 'foodsid');
			$ids = implode("','", array_keys($orderfoods));
			$foods = pdo_fetchall("SELECT * FROM ".tablename('fm453_vfoods_foods')." WHERE id IN ('".$ids."')");
			$pcate = pdo_fetch("SELECT * FROM ".tablename('fm453_vfoods_category')." WHERE uniacid =:uniacid AND id = :id",array(':uniacid'=>$_W['uniacid'],':id'=>$foods[0]['pcate']));

			//各种知会
			//微信知会
			//给核销人员发消息（客服接口）
			$WeAccount = fmFunc_wechat_weAccount();
			//调用行业消息模板
			$fm453industry='ebiz';
			$template_id=$settings['msgtpls']['ebiz_order_payed'];
			$buyer = $_W['openid'];
			$goodsadms = $pcate['managers'];
			$settings['manageropenids'] = $settings['manageropenids'].','.$settings['vfoods']['basic']['managers'];
			if (!empty($template_id)) {
				require MODULE_ROOT.'/core/msgtpls/tpls/vfoods.php';
				//发微信模板通知
		    	$postdata = $tplnotice_data['vfoods']['payresult'][$fm453industry]['buyer'];//下单人
			    fmMod_notice_tpl($postdata, $platid=NULL, $WeAccount);
			    $postdata = $tplnotice_data['vfoods']['payresult'][$fm453industry]['admin'];//管理员
			    fmMod_notice_tpl($postdata, $platid=NULL, $WeAccount);
			    if($goodsadms){
				    $postdata = $tplnotice_data['vfoods']['payresult'][$fm453industry]['goodsadmin'];//产品专员
				    fmMod_notice_tpl($postdata, $platid=NULL, $WeAccount);
				}
			}else{
				require MODULE_ROOT.'/core/msgtpls/msg/vfoods.php';
				$msgdata=$notice_data['vfoods']['payresult']['admin'];
				fmMod_notice($settings['manageropenids'],$msgdata,'',$WeAccount);
				$msgdata=$notice_data['vfoods']['payresult']['buyer'];
				fmMod_notice($_W['openid'],$msgdata,'',$WeAccount);
				if($goodsadms){
					$msgdata=$notice_data['vfoods']['payresult']['goodadm'];
					fmMod_notice($goodsadms,$msgdata,'',$WeAccount);
				}
			}

			//邮件知会
			$body = "<h3>{$pcate['title']}，您有一条订单</h3> <br />";
			if (!empty($foods)) {
				foreach ($foods as $row) {
					if($row['preprice']){
						$rowprice = $row['preprice'];
					}else{
						$rowprice = $row['oriprice'];
					}
					$body .= "{$row['title']}X{$orderfoods[$row['id']]['total']}{$row['unit']}，".$orderfoods[$row['id']]['total']*$rowprice."元<br />";
				}
			}
			$body .= "<br />总价格：{$order['price']}元<br />";
			$body .= "<h3>订单详情</h3> <br />";
			$body .= "订餐号：{$order['ordersn']}<br />";
			$body .= "用餐人：{$order['username']}<br />";
			$body .= "联系电话：{$order['mobile']} <br />";
			if($order['time']){
				$body .= "用餐时间：{$order['time']}<br />";
			}
			if($order['address']){
				$body .= "送餐地址：{$order['address']} <br />";
			}
			if($order['desknum']){
				$body .= "就餐桌号：{$order['desknum']} <br />";
			}
			$body .= "支付方式：{$paytype}<br />";
			$body .= "订单备注：{$order['other']} <br />";

			load()->func('communication');
			if($settings['noticeemail']){
				ihttp_email($settings['noticeemail'], FM_NAME_CN.'订单提醒', $body);
			}
			if($pcate['email']){
				ihttp_email($pcate['email'], FM_NAME_CN.'订单提醒', $body);
			}
			if($settings['vfoods']['basic']['noticeemail']){
				ihttp_email($settings['vfoods']['basic'], FM_NAME_CN.'订单提醒', $body);
			}

			// 短信提醒(设置了接收短信的手机及允许短信发送时)
			if ($settings['vfoods']['basic']['is_sms_send']) {
				load()->model('cloud');
				cloud_prepare();
				$body = $shopname.'订单提醒：用户' . $order['username'] . ',电话:' . $order['mobile'] . '于' . date('m月d日H:i') . '成功支付订单' . $order['ordersn']. ',共' . $order['price'] . '元' .'' ;
				if(!empty($settings['vfoods']['basic']['mobile'])){
					cloud_sms_send($settings['mobile'], $body);
				}
				if($pcate['mobile']){
					cloud_sms_send($pcate['mobile'], $body);
				}

			}
			//以上各种知会完成

			//订单打印
			fm_load()->fm_func('foods');//微餐饮函数
			//查找打印机
			$prints = pdo_fetchall("SELECT * FROM ".tablename('fm453_vfoods_print')." WHERE cateid = '{$pcate['id']}' AND enabled = 1");
			foreach($prints as $printrow){
				if($printrow['deviceno'] && $printrow['key'] && $printrow['printtime'] > 0){
					$device_no = $printrow['deviceno'];
					$device_key =$printrow['key'];
					$printtime = $printrow['printtime'];
					$printOrderInfo = fmFunc_foods_printOrderInfo($shopname=$pcate['title'],$order,$foods,$qrcode=fm_murl('appwebvfoods','order','detail',array('id'=>$orderid)));	//格式化要打印的数据
					fmFunc_foods_orderPrint($device_no,$device_key,$printtime,$printOrderInfo);	//打印
				}
			}


			//跳转到订单详情页
			header('Location:'.fm_murl('vfoods','myorder','detail',array('id'=>$orderid)));
			exit();

			if ($params['type'] == 'credit2') {
				// message('支付成功！现在跳转至查询订单页面。', fm_murl('vfoods','myorder','index',array()), 'success');
			} else {
				// message('支付完成！现在跳转至查询订单页面。', fm_murl('vfoods','myorder','index',array()), 'success');
			}
		}
	}
	//微餐饮订单处理方法结束

	load()->model('mc');
	fm_load()->fm_model('order');
	fm_load()->fm_func('order');
	$settings=$_FM['settings'];
	$shopname = $settings['brands']['shopname'];

	$isPayComplete=TRUE;	//是否完成全部支付（下面要校验支付金额）
	$status = ($params['result'] == 'success') ? 1 : 0;	//支付是否成功
	$data = array();
	$data['status'] =$status;
	$paytype = array('credit' => '1', 'wechat' => '2', 'alipay' => '2', 'baifubao' => '2', 'unionpay' => '2','delivery' => '3');
	$payname = array('credit' => '系统余额', 'wechat' => '微信', 'alipay' => '支付宝', 'baifubao' => '百付宝', 'unionpay' => '银联','delivery' => '货到付款');
	// 卡券代金券备注
	if (!empty($params['is_usecard'])) {
		$cardType = array('1' => '微信卡券', '2' => '系统代金券');
		$data['paydetail'] = '使用' . $cardType[$params['card_type']] . '支付了' . ($params['fee'] - $params['card_fee']);
		$data['paydetail'] .= '元，实际支付了' . $params['card_fee'] . '元。';
	}
	$data['paytype'] = $paytype[$params['type']];
	if ($params['type'] == 'wechat') {
		$data['transid'] = $params['tag']['transaction_id'];
	}
	if ($params['type'] == 'delivery') {
		$data['status'] = 1;	//选择货到付款的，支付结果设为成功
	}


	$settings['manageropenids'] = explode(',',$settings['manageropenids']);
	$address=array();

	//取订单ID
	$orderid = fmMod_order_sn2id($ordersn);
	if($isArticle){
		//文章打赏订单处理
		//更新订单状态
		pdo_update('fm453_shopping_order', $data, array('id' => $orderid));
		//通过orderid查找关联产品ID
		$sql = 'SELECT `articleid` FROM ' . tablename('fm453_shopping_order_goods') . ' WHERE `orderid` = :orderid';
		$articleId = pdo_fetchcolumn($sql, array(':orderid' => $orderid));
		//列出订单及订单中的产品
		$order = pdo_fetch("SELECT `id`,`ordersn`, `price`, `paytype`, `fromuid`,`from_user`, `addressid`,`remark`,`contactinfo`,`aboutinfos`,`createtime` FROM " . tablename('fm453_shopping_order') . " WHERE ordersn = '{$ordersn}'");
		$goodsid = $articleId;
		$sn = pdo_fetchcolumn("SELECT sn FROM " . tablename('fm453_site_article') . " WHERE id ='{$goodsid}'",array());
		$title = pdo_fetchcolumn("SELECT title FROM " . tablename('site_article') . " WHERE id ='{$sn}'",array());
		$articleAdm = pdo_fetchcolumn("SELECT goodadm FROM " . tablename('fm453_site_article') . " WHERE id ='{$goodsid}'",array());
		$contactinfo=unserialize($order['contactinfo']);
		$address['username']=$contactinfo['username'];
		$address['mobile']=$contactinfo['mobile'];
		$WeAccount = fmFunc_wechat_weAccount();
		//调用行业消息模板
		require_once MODULE_ROOT.'/core/msgtpls/tpls/task.php';
		require_once MODULE_ROOT.'/core/msgtpls/msg/article.php';
		$postdata = $tplnotice_data['task']['dashang']['admin'];
		$msg_result=fmMod_notice_tpl($postdata, $platid=NULL, $WeAccount);
		if(!$msg_result) {
			$msgdata=$notice_data['dashang']['result']['admin'];
			fmMod_notice($settings['manageropenids'],$msgdata,'',$WeAccount);
		}
		if($articleAdm && !in_array($articleAdm,$settings['manageropenids'])) {
			$postdata = $tplnotice_data['task']['dashang']['author'];
			$msg_result=fmMod_notice_tpl($postdata, $platid=NULL, $WeAccount);
			if(!$msg_result) {
				$msgdata=$notice_data['dashang']['result']['author'];
				fmMod_notice($settings['manageropenids'],$msgdata,'',$WeAccount);
			}
		}
		exit(message("感谢您的热心支持！", fm_murl('article','detail','index',array('id'=>'','sn'=>$sn)), 'success'));

	}elseif($isNeeds){
		//有求必应订单处理
		//更新订单状态
		pdo_update('fm453_shopping_order', $data, array('id' => $orderid));

		exit(message("感谢您的热心支持！", referer(), 'success'));

	}elseif($isQuickpay){
		//快捷支付订单处理
		//更新订单状态
		pdo_update('fm453_shopping_order', $data, array('id' => $orderid));

		exit(message("感谢您的热心支持！", referer(), 'success'));

	}else{
		//产品订单处理
		//通过orderid查找关联产品ID
		$sql = 'SELECT `goodsid` FROM ' . tablename('fm453_shopping_order_goods') . ' WHERE `orderid` = :orderid';
		$goodsId = pdo_fetchcolumn($sql, array(':orderid' => $orderid));
		//列出订单及订单中的产品
		$order = pdo_fetch("SELECT `id`,`ordersn`, `price`, `paytype`, `status`,`from_user`, `addressid`,`remark`,`contactinfo`,`aboutinfos`,`createtime` FROM " . tablename('fm453_shopping_order') . " WHERE ordersn = '{$ordersn}'");
		if($order['price']>$params['fee'] && $params['type'] != 'delivery'){
			$isPayComplete=FALSE;	//实付金额小于订单订单价格，核实为支付不完整
		}
		$ordergoods = pdo_fetchall("SELECT goodsid, total, optionid, optionname FROM " . tablename('fm453_shopping_order_goods') . " WHERE orderid = '{$orderid}'", array(), 'goodsid');
		$goods = pdo_fetchall("SELECT id, title, thumb, marketprice, unit, total, goodadm, goodtpl FROM " . tablename('fm453_shopping_goods') . " WHERE id IN ('" . implode("','", array_keys($ordergoods)) . "')");
		//通过goodsid查找关联产品为库存及销量等信息
		$gtype = $goods[0]['goodtpl'];
		fmFunc_order_setStock($orderid,$minus=true,$gtype);
	}

	//更新订单状态
	if($isPayComplete==FALSE){
		$data['status'] = 2;	//支付不完整的订单，订单状态改为货到付款
	}
	pdo_update('fm453_shopping_order', $data, array('id' => $orderid));
	//根据支付回调信息做相关操作
	if (($params['result'] == 'success' && $params['from'] == 'notify') || $params['type'] == 'delivery') {
		//积分变更
		fmFunc_order_setCredit($params['tid'],'+');//向订单积分处理函数传入订单号ordersn
		$SYSsetting = uni_setting($_W['uniacid'], array('creditbehaviors'));//调用系统配置信息
		$credit = $SYSsetting['creditbehaviors']['currency'];//判断默认支付是使用积分credit1还是余额credit2
		//各种知会
		$address = pdo_fetch("SELECT * FROM " . tablename('mc_member_address') . " WHERE id = :id", array(':id' => $order['addressid']));
		//下面是获取新添加的联系信息、订单更多信息
		$row=$order;
		$contactinfo=unserialize($row['contactinfo']);
		$row['username'] =$contactinfo['username'];
		$row['mobile'] =$contactinfo['mobile'];
		if(!$address) {
			$address=array();
			$address['username']=$row['username'];
			$address['mobile']=$row['mobile'];
		}
			$aboutinfos=unserialize($row['aboutinfos']);
		$row['goodstpl'] = !empty($aboutinfos['goodstpl']) ? $aboutinfos['goodstpl'] : $aboutinfos['goodtpl'];
			$goodstpl=$row['goodstpl'];
		$row['mpaccountname'] =$aboutinfos['mpaccountname'];
			$mpaccountname =$row['mpaccountname'];
		$row['ucontainer'] =$aboutinfos['ucontainer'];
		$row['uos'] =$aboutinfos['uos'];
			$goodstplinfos =unserialize($aboutinfos['infos']);
		include_once  FM_CORE.'goodstpl/forreminder.php';
		$row['tips']=$tips;

		// 邮件提醒
		if (!empty($setting['noticeemail'])) {
			$body = "<h3>购买商品清单（单号{$order['ordersn']}）</h3> <br />";
			if (!empty($goods)) {
				foreach ($goods as &$row) {
					$body .= "名称：{$row['title']}|{$ordergoods[$row['id']]['optionname']} ，数量：{$ordergoods[$row['id']]['total']}<br />";
				}
			}
			if ($order['paytype'] == '1'){
				$paytype ='系统余额支付';
			}elseif($order['paytype'] == '3'){
				$paytype ='货到付款';
			}elseif($order['paytype'] == '2'){
				$paytype ='在线付款';
			}
			//更新支付方式判断 BY FM453 151101;
			$body .= '总金额：' . $order['price'] . '元（' . $paytype . '）<br />';
			$body .= '<h3>购买用户详情</h3> <br />';
			$body .= '真实姓名：' . $address['username'] . '<br />';
			$body .= '地区：' . $address['province'] . ' - ' . $address['city'] . ' - ' . $address['area'] . '<br />';
			$body .= '详细地址：' . $address['address'] . '<br />';
			$body .= '手机：' . $address['mobile']  . '<br />';
			$body .= '关联信息：<br/>' . $row['tips']  . '<br />';
			load()->func('communication');
			ihttp_email($settings['noticeemail'], '{FM_NAME_CN}订单提醒', $body);
		}

		// 短信提醒
		if (!empty($settings['mobile'])) {
			load()->model('cloud');
			cloud_prepare();
			$body = $shopname.'订单提醒：用户' . $address['username'] . ',电话:' . $address['mobile'] . '于' . date('m月d日H:i') . '成功支付订单' . $order['ordersn']. ',共' . $order['price'] . '元' .'.'. random(3) ;
			cloud_sms_send($settings['mobile'], $body);
		}

		// 模板消息提醒-预订成功通知-给超级管理员、产品专员	(加载模板消息行业类型)
		//加载产品信息（待优化）
		if (!empty($goods)) {
			$goodstitle='';
				foreach ($goods as &$row) {
					$goodstitle .= "{$row['title']}|{$ordergoods[$row['id']]['optionname']}（{$ordergoods[$row['id']]['total']} 份）；";
				}
			}
			$fm453industry=$settings['industry'];
			$buyer=$order['from_user'];
			//处理产品专员数据
			$admsize=count($goods);
			$gadm=array();
			$newgadm = '';
			for($i=0;$i<$admsize;$i++) {
				$newgadm .= $goods[$i]['goodadm'];
			}
			$newtousers = explode(',',$newgadm);
			foreach($newtousers as $key=>$newtouser){
				if(!empty($newtouser) && ! is_numeric($newtouser) &&  !in_array($newtouser,$settings['manageropenids'])) {
					$gadm[] = $newtouser;
				}
			}
			$goodsadms=array_unique($gadm);

			//发送订单生成的相关通知
			//给管理员们发个微信消息（客服接口）
			$WeAccount = fmFunc_wechat_weAccount();
			//调用行业消息模板
			if($fm453industry=='ota') {
				$template_id=$settings['msgtpls']['ota_book_success'];
			}elseif($fm453industry=='ebiz') {
				$template_id=$settings['msgtpls']['ebiz_order_payed'];
			}
			if (!empty($template_id)) {
				require MODULE_ROOT.'/core/msgtpls/tpls/pay.php';
				//发微信模板通知
			    	$postdata = $tplnotice_data['pay']['payresult'][$fm453industry]['buyer'];//下单人
				    fmMod_notice_tpl($postdata, $platid=NULL, $WeAccount);
				    $postdata = $tplnotice_data['pay']['payresult'][$fm453industry]['admin'];//管理员
				    fmMod_notice_tpl($postdata, $platid=NULL, $WeAccount);
				    $postdata = $tplnotice_data['pay']['payresult'][$fm453industry]['goodsadmin'];//产品专员
				    fmMod_notice_tpl($postdata, $platid=NULL, $WeAccount);
			}else{
				require MODULE_ROOT.'/core/msgtpls/msg/pay.php';
				$msgdata=$notice_data['pay']['result']['admin'];
				fmMod_notice($settings['manageropenids'],$msgdata,'',$WeAccount);
				$msgdata=$notice_data['pay']['result']['buyer'];
				fmMod_notice($_W['openid'],$msgdata,'',$WeAccount);
				if($goodsadms){
					$msgdata=$notice_data['pay']['result']['goodadm'];
					fmMod_notice($goodsadms,$msgdata,'',$WeAccount);
				}
			}
			//以上各种知会完成

			//货到付款的订单没有return，需要自行添加一个通知跳转
			if($params['type'] == 'delivery'){
			    message('恭喜，您已经成功下单！',  fm_murl('order','detail','index',array('id' => $orderid)), 'success');
			}

		}
	//以上，根据支付返回结果进行操作的过程结束

	//以下返回对用户的支付成功状态提示（页面可能会被用户关闭）
	if ($params['from'] == 'return' && $params['result'] == 'success') {
		if($isPayComplete==FALSE){
			exit(message("感谢您的支付，您的订单未已经支付了".$params['fee']."元/积分", fm_murl('order','detail','index',array('id' => $orderid)), 'info'));
		}
		if ($params['type'] == "credit") {
			message("恭喜，您的订单支付成功，消耗了".$params['fee']."余额！", fm_murl('order','detail','index',array('id' => $orderid)), 'success');
		} else {
			message('恭喜，您已经成功支付！',  fm_murl('order','detail','index',array('id' => $orderid)), 'success');
		}
	}
	//相关操作结束
}
