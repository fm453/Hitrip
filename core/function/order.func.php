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
 * @remark	产品订单处理函数
 */
defined('IN_IA') or exit('Access Denied');
fm_load()->fm_func('status');
fm_load()->fm_model('goods');
fm_load()->fm_model('order');

/*
	根据订单号设置产品库存，不判断减库存的时机（下单时，或是支付后，对应产品减库存方式-拍下减OR付款减； 该判断逻辑交由前台具体场景自行控制）
	@orderid 订单ID
	@minus 减或加
	@gtype 产品类型
*/
function fmFunc_order_setStock($orderid,$minus=true,$gtype){
	/*
	产品数据表关联的主要字段
	@total 外显库存
	@stock 真实库存
	@totalcnf 减库存方式 0拍下减 1付款减 2永久不减
	@sales 外显销量
	@realsales 真实销量
	*/
	global $_GPC;
	global $_W;
	global $_FM;

	//先判断是否执行过相同动作，有则返回、不执行
	$cachekey = md5('OrderSetStock_'.$orderid.'_'.$_W['uniacid'].'_'.$minus);
	$_FM['cache']['lastOrderSetStock'] = cache_load($cachekey);
	if($_FM['cache']['lastOrderSetStock']==$orderid){
		return;
	}else{
		cache_write($cachekey,$orderid);
	}

	$goods = pdo_fetchall("SELECT g.id, g.title, g.thumb, g.unit, g.marketprice,g.total as goodstotal,g.stock,g.sales,g.realsales,g.totalcnf,o.total,o.optionid FROM " . tablename('fm453_shopping_order_goods') . " AS o INNER JOIN " . tablename('fm453_shopping_goods') . " AS g on o.goodsid=g.id ". " WHERE o.orderid='{$orderid}'");
	//订单中包含的产品量（total,总量），原产品表total字段值转存为goodstotal
	$data = array();
	foreach ($goods as $item) {
		if ($minus) {
			//减库存
			//更新产品规格库存
			if($item['totalcnf']!=2){
				if (!empty($item['optionid'])) {
					pdo_query("update " . tablename('fm453_shopping_goods_option') . " set stock=stock-:stock where id=:id", array(":stock" => $item['total'], ":id" => $item['optionid']));
				}

				if (!empty($item['goodstotal']) && $item['goodstotal'] != -1) {
					//产品总库存非空且不是无限库存模式
					$data['total'] = $item['goodstotal'] - $item['total'];	//外显库存修改
					$data['stock'] = $item['stock'] - $item['total'];	//真实库存修改
				}
			}
			$data['sales'] = $item['sales'] + $item['total'];	//外显销量修改
			$data['realsales'] = $item['realsales'] + $item['total'];	//真实销量修改
			pdo_update('fm453_shopping_goods', $data, array('id' => $item['id']));
		} else {
			//从订单中追回库存
			//恢复产品规格库存
			if($item['totalcnf']!=2){
				if (!empty($item['optionid'])) {
					pdo_query("update " . tablename('fm453_shopping_goods_option') . " set stock=stock+:stock where id=:id", array(":stock" => $item['total'], ":id" => $item['optionid']));
				}

				if (!empty($item['goodstotal']) && $item['goodstotal'] != -1) {
					//产品总库存非空且不是无限库存模式
					$data['total'] = $item['goodstotal'] + $item['total'];	//外显库存恢复
					$data['stock'] = $item['stock'] + $item['total'];	//真实库存恢复
				}
			}
			$data['sales'] = $item['sales'] - $item['total'];	//外显销量恢复
			$data['realsales'] = $item['realsales'] - $item['total'];	//真实销量恢复
			pdo_update('fm453_shopping_goods', $data, array('id' => $item['id']));
		}
	}
}

/*
	订单积分处理
	@ordersn 订单编号
	@add 增（+）或减（-）
*/
function fmFunc_order_setCredit($ordersn, $add){
	global $_GPC;
	global $_W;
	global $_FM;
		$sql = 'SELECT * FROM ' . tablename('fm453_shopping_order') . ' WHERE `ordersn` = :ordersn limit 1';
		$order = pdo_fetch($sql, array(':ordersn' => $ordersn));
		if (empty($order)) {
			return false;
		}
		$orderid=$order['id'];
		$sql ="SELECT `goodsid`, `total` FROM " . tablename('fm453_shopping_order_goods') . " WHERE `orderid` = :orderid";
		$orderGoods = pdo_fetchall($sql, array(':orderid' => $orderid));
		if (!empty($orderGoods)) {
			$credit = 0.00;
			$sqlcredit = "SELECT `credit` FROM " . tablename('fm453_shopping_goods') . " WHERE `id` = :id";
//			$sql = 'SELECT `id`,`title`, FROM ' . tablename('fm453_shopping_goods') . ' WHERE `id` = :id';     //保留语句，后期对日志进行丰富（添加关联产品）时使用
			foreach ($orderGoods as $goods) {
				$goodsCredit = pdo_fetchcolumn($sqlcredit, array(':id' => $goods['goodsid']));
				$credit += $goodsCredit * floatval($goods['total']);
			}
		}
		//增加积分credit1
		if (!empty($credit)) {
			load()->model('mc');
			load()->func('compat.biz');
			$uid = mc_openid2uid($order['from_user']);
			$fans = mc_fetch($uid, array("credit1"));
			if (!empty($fans)) {
				if ($add=='+') {
				    $tmptag='购买嗨旅行商城商品获赠，关联订单：'.$order['ordersn'];
					mc_credit_update($_W['member']['uid'], 'credit1', $credit, array('0' => 0,'1' =>$tmptag));
				} else {
				    $tmptag='嗨旅行商城订单取消，扣减相应积分，关联订单号：'.$order['ordersn'];
					mc_credit_update($_W['member']['uid'], 'credit1', 0 - $credit, array('0' => 0,'1'=> $tmptag));
				}
			}
		}
}

/*
	向商户平台传送支付请求、订单号、订单状态
	@id\ordersn 订单编号，对应支付记录中的tid
	@status 订单状态
	@msg 备注
*/
	function fmFunc_order_WechatSend($id, $status, $msg = ''){
		global $_GPC;
		global $_W;
		global $_FM;
		$paylog = pdo_fetch("SELECT plid, openid, tag FROM " . tablename('core_paylog') . " WHERE tid = '{$id}' AND status = 1 AND type = 'wechat'");
		$order = pdo_fetch("SELECT id, ordersn FROM " . tablename('fm453_shopping_order') . " WHERE id = '{$id}'");
		//新添加订单序号的关联搜索，以便将订单编号传送到微信商户平台；
		if (!empty($paylog['openid'])){
			$paylog['tag'] = iunserializer($paylog['tag']);
			$acid = $paylog['tag']['acid'];
			$account = account_fetch($acid);
			$payment = uni_setting($account['uniacid'], 'payment');
			if ($payment['payment']['wechat']['version'] == '2'){
				return true;
			}
			$send = array(
					'appid' => $account['key'],
					'openid' => $paylog['openid'],
					'transid' => $paylog['tag']['transaction_id'],
					'out_trade_no' => $order['ordersn'],
					'deliver_timestamp' => TIMESTAMP,
					'deliver_status' => $status,
					'deliver_msg' => $msg,
			);
			$sign = $send;
			$sign['appkey'] = $payment['payment']['wechat']['signkey'];
			ksort($sign);
			$string = '';
			foreach ($sign as $key => $v){
				$key = strtolower($key);
				$string .= "{$key}={$v}&";
			}
			$send['app_signature'] = sha1(rtrim($string, '&'));
			$send['sign_method'] = 'sha1';
			$account = WeAccount::create($acid);
			$response = $account->changeOrderStatus($send);
			if (is_error($response)){
				message($response['message']);
			}
		}
	}

?>
