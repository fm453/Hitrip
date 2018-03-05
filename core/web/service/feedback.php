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
 * @remark 反馈处理
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC, $_W,$_FM;

load()->func('tpl');
load()->model('account');//加载公众号函数
fm_load()->fm_func('feedback');//反馈处理函数

//加载风格模板及资源路径

$fm453style = fmFunc_ui_shopstyle();
$fm453resource = fmFunc_ui_resource();

//入口判断
$routes=fmFunc_route_web();
$routes_do=fmFunc_route_web_do();
$do= $_GPC['do'];
$ac=$_GPC['ac'];
$all_ac=fmFunc_route_web_ac($do);
$all_op=fmFunc_route_web_op_single($do,$ac);
$operation = empty($_GPC['op']) ? 'display' : $_GPC['op'];
if(!in_array($ac,$all_ac) || !in_array($operation,$all_op)){
	die(message('非法访问，请通过有效路径进入！'));
}

fmMod_privilege_adm();//检查用户授权

//开始操作管理
$shopname=$settings['brands']['shopname'];
$shopname = !empty($shopname) ? $shopname :FM_NAME_CN;
$_W['page']['title'] = $shopname.$routes[$do]['title'];
$direct_url=fm_wurl($do,$ac,$operation,'');
$pindex=max(1,intval($_GPC['page']));
$psize=(intval($_GPC['psize'])>10) ? intval($_GPC['psize']) : 10;//最少显示10条主数据

$uniacid=$_W['uniacid'];
$platids = fmFunc_getPlatids();//获取平台模式关联的公众号商户ID
$oauthid=$platids['oauthid'];
$fendianids=$platids['fendianids'];
$supplydianids=$platids['supplydianids'];
$blackids=$platids['blackids'];

//平台模式处理
include_once FM_PUBLIC.'plat.php';
$supplydians=explode(',',$supplydianids);//字符串转数组
$supplydians=array_filter($supplydians);//数组去空

//按平台模式前置筛选条件
$condition=' WHERE ';
$params=array();
include_once FM_PUBLIC.'forsearch.php';
$condition .= ' AND `deleted` = :deleted';
$params[':deleted'] = 0;

$showorder=" ORDER BY uniacid ASC, parentid ASC, displayorder DESC ";
$limit=" LIMIT ".($pindex-1)*$psize.",".$psize;

if ($operation == 'display') {
	if (!empty($_GPC['date'])) {
			$starttime = strtotime($_GPC['date']['start']);
			$endtime = strtotime($_GPC['date']['end']) + 86399;
		} else {
			//$starttime = strtotime('-1 month');
			$starttime = strtotime('-3 year');
			$endtime = time();
		}
		$where = " WHERE `uniacid` = :uniacid AND `createtime` >= :starttime AND `createtime` < :endtime";
		$paras = array(
			':uniacid' => $_W['uniacid'],
			':starttime' => $starttime,
			':endtime' => $endtime
		);
		$keyword = $_GPC['keyword'];
		if (!empty($keyword)) {
			$where .= " AND `feedbackid`=:feedbackid";
			$paras[':feedbackid'] = $keyword;
		}
		$type = empty($_GPC['type']) ? 0 : $_GPC['type'];
		$type = intval($type);
		if ($type != 0) {
			$where .= " AND `type`=:type";
			$paras[':type'] = $type;
		}
		$status = empty($_GPC['status']) ? 0 : intval($_GPC['status']);
		$status = intval($status);
		if ($status != -1) {
			$where .= " AND `status` = :status";
			$paras[':status'] = $status;
		}
		$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('fm453_shopping_feedback') . $where, $paras);
		$list = pdo_fetchall("SELECT * FROM " . tablename('fm453_shopping_feedback') . $where . " ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $paras);
		$pager = pagination($total, $pindex, $psize);
		$transids = array();
		foreach ($list as $row) {
			$transids[] = $row['transid'];
		}
		if (!empty($transids)) {
			$sql = "SELECT * FROM " . tablename('fm453_shopping_order') . " WHERE uniacid='{$_W['uniacid']}' AND transid IN ( '" . implode("','", $transids) . "' )";
			$orders = pdo_fetchall($sql, array(), 'transid');
		}
		$addressids = array();
		if(is_array($orders)){
			foreach ($orders as $transid => $order) {
				$addressids[] = $order['addressid'];
			}
		}
		$addresses = array();
		if (!empty($addressids)) {
			$sql = "SELECT * FROM " . tablename('mc_member_address') . " WHERE uniacid='{$_W['uniacid']}' AND id IN ( '" . implode("','", $addressids) . "' )";
			$addresses = pdo_fetchall($sql, array(), 'id');
		}
		foreach ($list as &$feedback) {
			$transid = $feedback['transid'];
			$order = $orders[$transid];
			$feedback['order'] = $order;
			$addressid = $order['addressid'];
			$feedback['address'] = $addresses[$addressid];
		}
	include $this->template($fm453style.$do.'/453');
}
elseif($operation=='detail') {
	$id=intval($_GPC['id']);
	$where = " WHERE `id` = :id ";
	$paras = array(
		':id' => $id
	);
	$feedback=pdo_fetch("SELECT * FROM " . tablename('fm453_shopping_feedback') . $where, $paras);
	include $this->template($fm453style.$do.'/453');
}
else {
    	message('请求方式不存在');
}