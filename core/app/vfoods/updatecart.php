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
 * @remark 微餐饮
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;

//入口判断
$do            =$_GPC['do'];
$ac            =$_GPC['ac'];
$op            = !empty($_GPC['op']) ? $_GPC['op'] : 'index';

//开始操作管理
$shopname      =$settings['brands']['shopname'];
$shopname      = !empty($shopname) ? $shopname :FM_NAME_CN;

//会员自定义设置
$mine_settings    =$_FM['member']['settings'];

//页面具体操作
$result = array('status' => 0, 'message' => '');
$operation = $_GPC['op'];
//取店铺信息
$shopid = intval($_GPC['shopid']);
$cache_key = md5(FM_NAME.'_'.$_W['uniacid'].'_'.'vfoods[category]'.'_'.$category['id']);
$category = cache_load($cache_key);
if(!$category){
	$category = pdo_fetch("SELECT * FROM ".tablename('fm453_vfoods_category')." WHERE uniacid = '{$_W['uniacid']}' AND id = '{$shopid}' ");
	$cache_key = md5(FM_NAME.'_'.$_W['uniacid'].'_'.'vfoods[category]'.'_'.$category['id']);
	cache_write($cache_key,$category);
}

$ptime1 = $category['time1'] ? strtotime($category['time1']) : strtotime('00:00:01');
$ptime2 = $category['time2'] ? strtotime($category['time2']) : strtotime('12:00:00');
$ptime3 = $category['time3'] ? strtotime($category['time3']) : strtotime('12:00:00');
$ptime4 = $category['time4'] ? strtotime($category['time4']) : strtotime('23:59:59');

$foodsid = intval($_GPC['foodsid']);
$orderfoodstotal = 0;
if($ptime1<$ptime2 && $ptime2<=$ptime3 && $ptime3<$ptime4){
	if(TIMESTAMP<$ptime2){
		$orderfoodstotal = pdo_fetchcolumn("SELECT SUM(total) FROM ".tablename('fm453_vfoods_order_foods')." WHERE foodsid = :foodsid AND createtime>= ".$ptime1." AND createtime< ". TIMESTAMP , array(':foodsid' => $foodsid));
	}
	elseif(TIMESTAMP<$ptime4){
		$orderfoodstotal = pdo_fetchcolumn("SELECT SUM(total) FROM ".tablename('fm453_vfoods_order_foods')." WHERE foodsid = :foodsid AND createtime>= ".$ptime3." AND createtime< ". TIMESTAMP , array(':foodsid' => $foodsid));
	}
}

$foods = pdo_fetch("SELECT * FROM ".tablename('fm453_vfoods_foods')." WHERE id = :id", array(':id' => $foodsid));

if (empty($foods)) {
	$result['message'] = '抱歉，该菜品不存在或是已经被删除了。';
	message($result, '', 'ajax');
}elseif($foods['stock'] && $orderfoodstotal>=(intval($foods['stock'])+intval($foods['overbook']))){
	$result['message'] = '抱歉，该菜品已被预订完了。';	//根据已销售情况判断库存
	message($result, '', 'ajax');
}

$row = pdo_fetch("SELECT id, total FROM ".tablename('fm453_vfoods_cart')." WHERE from_user = :from_user AND uniacid = '{$_W['uniacid']}' AND sn = :foodsid", array(':from_user' => $_W['fans']['from_user'], ':foodsid' => $foodsid));
$nomore = false;
$foods['total'] = $foods['total']-$row['total']-$orderfoodstotal;
if (empty($row['id'])) {
	//新预订
	if ($operation == 'add') {
		if($foods['stock'] && (intval($foods['stock'])+intval($foods['overbook']))==0){
			$result['message'] = '抱歉，该菜品暂时没有了，不能预订。';	//根据当前选择数量判断库存
			message($result, '', 'ajax');
		}
		$data = array(
			'uniacid' => $_W['uniacid'],
			'sn' => $foodsid,
			'from_user' => $_W['fans']['from_user'],
			'total' => '1',
		);
		if(!empty($_W['fans']['from_user'])){
			pdo_insert('fm453_vfoods_cart', $data);
		}
	}
} else {
	//修改订单
	$row['total'] = $operation == 'reduce' ? ($row['total'] - 1) : ($row['total'] + 1);
	if (empty($row['total'])) {
		pdo_delete('fm453_vfoods_cart', array('from_user' => $_W['fans']['from_user'], 'uniacid' => $_W['uniacid'], 'sn' => $foodsid));
	} else {
		//设置了菜品库存，预订量超出可库存+超售量时
		if($foods['stock'] && ($foods['stock']+$foods['overbook'])<$row['total']){
			$data = array(
				'total' => $foods['stock'],
			);
			pdo_update('fm453_vfoods_cart', $data, array('from_user' => $_W['fans']['from_user'], 'uniacid' => $_W['uniacid'], 'sn' => $foodsid));
			$unit = $foods['unit'] ? $foods['unit'] : '份';
			$result['status'] = 2;
			$result['message'] = '抱歉，该菜品最多可预订'.$foods['stock'].$unit.',系统已自动为您更新，请留意。';	//根据当前选择数量判断库存
			$nomore = true;
		}else{
			$data = array(
				'total' => $row['total'],
			);
			if(!empty($_W['fans']['from_user'])){
				pdo_update('fm453_vfoods_cart', $data, array('from_user' => $_W['fans']['from_user'], 'uniacid' => $_W['uniacid'], 'sn' => $foodsid));
			}
		}
	}
}


$ccate = pdo_fetch("SELECT * FROM ".tablename('fm453_vfoods_foods')." WHERE uniacid = '{$_W['uniacid']}' AND ccate = '{$_GPC['ccate']}' ");

$pcatefoods = pdo_fetchall("SELECT * FROM ".tablename('fm453_vfoods_foods')." WHERE uniacid = '{$_W['uniacid']}' AND pcate = '{$category['id']}' ");
$pricetotal =0;
foreach ($pcatefoods as &$row) {
	$pcatecart = pdo_fetch("SELECT * FROM ".tablename('fm453_vfoods_cart')." WHERE from_user = :from_user AND uniacid = '{$_W['uniacid']}' AND sn = '{$row['id']}'", array(':from_user' => $_W['fans']['from_user']));
	$pcatetotal += $pcatecart['total'];
	$price = pdo_fetch("SELECT * FROM ".tablename('fm453_vfoods_foods')." WHERE uniacid = '{$_W['uniacid']}' AND id = '{$pcatecart['sn']}'");
	if($price['preprice']){
		$pricetotal += $price['preprice']*$pcatecart['total'];
	}else{
		$pricetotal += $price['oriprice']*$pcatecart['total'];
	}
	if($price['ccate'] == $foods['ccate']){
		$ccatenum['num'] += $pcatecart['total'];
	}
}

if($pricetotal < $category['sendprice']){
	$a =$category['sendprice']-$pricetotal;
    $between = "差￥".$a."起送";
    $target = "#";
}else{
	$between = "去结算";
	$target = "1";
}

if(!$nomore){
	$result['status'] = 1;
	$result['message'] = '菜品数据更新成功。';
}
$result['total'] = intval($data['total']);
$result['pcatetotal'] = intval($pcatetotal);
$result['pricetotal'] = floatval($pricetotal);
$result['between'] = $between;
$result['target'] = $target;
$result['ccatenum'] = $ccatenum['num'];
$result['ccate'] = $foods['ccate'];
message($result, '', 'ajax');