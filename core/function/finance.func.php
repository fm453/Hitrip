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
 * @remark：财务接口函数
 */
defined('IN_IA') or exit('Access Denied');

//查询企业付款API－用于商户的企业付款操作进行结果查询，返回付款操作详细结果。
function fmFunc_finance_result($openid = '', $paytype = 0, $money = 0, $trade_no = '', $desc = '') {
	global $_GPC;
	global $_W;
	global $_FM;
	if (empty($openid)) {
		return error(-1, 'openid不能为空');
	}
	$setting = uni_setting($_W['uniacid'], array('payment'));
	if (!is_array($setting['payment'])) {
		return error(1, '没有设定支付参数');
	}
	$wechat = $setting['payment']['wechat'];
	$sql = 'SELECT `key`,`secret` FROM ' . tablename('account_wechats') . ' WHERE `uniacid`=:uniacid limit 1';
	$row = pdo_fetch($sql, array(':uniacid' => $_W['uniacid']));
	$url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
	$pars = array();
	$pars['mch_appid'] = $row['key'];
	$pars['mchid'] = $wechat['mchid'];
	$pars['nonce_str'] = random(32);
	$pars['partner_trade_no'] = empty($trade_no) ? time() . random(4, true) : $trade_no;
	$pars['openid'] = $openid;
	$pars['check_name'] = 'NO_CHECK';
	$pars['amount'] = $money;
	$pars['desc'] = empty($desc) ? '商家佣金提现' : $desc;
	$pars['spbill_create_ip'] = gethostbyname($_SERVER["HTTP_HOST"]);
	ksort($pars, SORT_STRING);
	$string1 = '';
	foreach ($pars as $k => $v) {
		$string1 .= "{$k}={$v}&";
	}
	$string1 .= "key=" . $wechat['apikey'];
	$pars['sign'] = strtoupper(md5($string1));
	$xml = array2xml($pars);
	$path_cert = IA_ROOT . '/attachment/fm453/cert/' . $_W['uniacid'] . '/apiclient_cert.pem';
	//证书路径
	$path_key = IA_ROOT . '/attachment/fm453/cert/' . $_W['uniacid'] . '/apiclient_key.pem';
	//证书路径
	if (!file_exists($path_cert) || !file_exists($path_key)) {
		return error(-2, '无商户证书');
	}
	$extras = array();
	$extras['CURLOPT_SSLCERT'] = $path_cert;
	$extras['CURLOPT_SSLKEY'] = $path_key;
	load() -> func('communication');
	$resp = ihttp_request($url, $xml, $extras);

	if (empty($resp['content'])) {
		return error(-2, '网络错误');
	} else {
		$arr = json_decode(json_encode((array) simplexml_load_string($resp['content'])), true);
		$xml = '<?xml version="1.0" encoding="utf-8"?>' . $resp['content'];
		$dom = new \DOMDocument();
		if ($dom -> loadXML($xml)) {
			$xpath = new \DOMXPath($dom);
			$code = $xpath -> evaluate('string(//xml/return_code)');
			$ret = $xpath -> evaluate('string(//xml/result_code)');
			if (strtolower($code) == 'success' && strtolower($ret) == 'success') {
				return true;
			} else {
				$error = $xpath -> evaluate('string(//xml/err_code_des)');
				return error(-2, $error);
			}
		} else {
			return error(-1, '未知错误');
		}
	}

}

//退款
function fmFunc_finance_refund($orderno, $type) {
	global $_GPC;
	global $_W;
	global $_FM;
	include_once TG_CERT . 'WxPay.Api.php';
	load() -> model('account');
	load() -> func('communication');
	wl_load() -> model('setting');
	$WxPayApi = new WxPayApi();
	$input = new WxPayRefund();
	$accounts = uni_accounts();
	$acid = $_W['uniacid'];
	$path_cert = IA_ROOT . '/attachment/feng_fightgroups/cert/' . $_W['uniacid'] . '/apiclient_cert.pem';
	$path_key = IA_ROOT . '/attachment/feng_fightgroups/cert/' . $_W['uniacid'] . '/apiclient_key.pem';
	$account_info = pdo_fetch("select * from" . tablename('account_wechats') . "where uniacid={$_W['uniacid']}");
	$refund_order = pdo_fetch("select * from" . tablename('tg_order') . "where orderno ='{$orderno}'");
	$goods = pdo_fetch("select * from" . tablename('tg_goods') . "where id='{$refund_order['g_id']}'");
	$settings = setting_get_by_name('refund');

	if (!file_exists($path_cert) || !file_exists($path_key)) {
		$path_cert = TG_CERT . $_W['uniacid'] . '/apiclient_cert.pem';
		$path_key = TG_CERT . $_W['uniacid'] . '/apiclient_key.pem';
	}
	$key = $settings['apikey'];
	$mchid = $settings['mchid'];
	$appid = $account_info['key'];
	$fee = $refund_order['price'] * 100;
	$refundid = $refund_order['transid'];
	$input -> SetAppid($appid);
	$input -> SetMch_id($mchid);
	$input -> SetOp_user_id($mchid);
	$input -> SetRefund_fee($fee);
	$input -> SetTotal_fee($refund_order['price'] * 100);
	$input -> SetTransaction_id($refundid);
	$input -> SetOut_refund_no($refund_order['orderno']);
	$result = $WxPayApi -> refund($input, 6, $path_cert, $path_key, $key);

	$data = array('merchantid' => $refund_order['merchantid'], 'transid' => $refund_order['transid'], 'refund_id' => $result['refund_id'], 'createtime' => TIMESTAMP, 'status' => 0, 'type' => $type, 'goodsid' => $refund_order['g_id'], 'orderid' => $refund_order['id'], 'payfee' => $refund_order['price'], 'refundfee' => $fee * 0.01, 'refundername' => $refund_order['addname'], 'refundermobile' => $refund_order['mobile'], 'goodsname' => $goods['gname'], 'uniacid' => $_W['uniacid']);
	pdo_insert('tg_refund_record', $data);
	if ($result['return_code'] == 'SUCCESS') {
		if ($type == 3) {
			pdo_update('tg_order', array('status' => 7, 'is_tuan' => 2), array('id' => $refund_order['id']));
		} else {
			pdo_update('tg_order', array('status' => 7), array('id' => $refund_order['id']));
		}
		pdo_update('tg_refund_record', array('status' => 1), array('transid' => $refund_order['transid']));
		pdo_query("update" . tablename('tg_goods') . " set gnum=gnum+1 where id = '{$refund_order['g_id']}'");
		return 'success';
	} else {
		if ($type == 3) {
			pdo_update('tg_order', array('status' => 6, 'is_tuan' => 2), array('id' => $refund_order['id']));
		} else {
			pdo_update('tg_order', array('status' => 6), array('id' => $refund_order['id']));
		}
		return 'fail';
	}
}

//检查退款状态
function fmFunc_finance_refund_check() {
	global $_GPC;
	global $_W;
	global $_FM;
	$sql = "SELECT orderno,id,openid,price FROM " . tablename('tg_order') . " where mobile<>'虚拟' and status=:status and openid=:openid  and uniacid=:uniacid";
	$params[':status'] = 6;
	$params[':openid'] = $_W['openid'];
	$params[':uniacid'] = $_W['uniacid'];
	$orders = pdo_fetchall($sql, $params);
	foreach ($orders as $key => $value) {
		$r = refund($value['orderno'], 1);
		if ($r == 'success') {
			/*退款成功消息提醒*/
			$url = app_url('order/order/detail', array('id' => $value['id']));
			refund_success($value['openid'], $value['price'], $url);
		}
	}

}
