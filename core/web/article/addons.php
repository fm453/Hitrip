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
 * @remark 文章管理；
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
message('功能暂未开放！','referer','info');

load()->func('tpl');
load()->model('account');//加载公众号函数
fm_load()->fm_model('category'); //分类管理模块
fm_load()->fm_model('goods'); //商品管理模块
fm_load()->fm_model('goodstpl'); //商品模型管理模块

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
$shopname=$settings['brands']['shopname'];
$shopname = !empty($shopname) ? $shopname :FM_NAME_CN;
$_W['page']['title'] = $shopname.$routes[$do]['title'];
$direct_url=fm_wurl($do,$ac,$operation,'');
$pindex=max(1,intval($_GPC['page']));
$psize=(intval($_GPC['psize'])>5) ? intval($_GPC['psize']) : 5;//最少显示5条主数据

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
$catestatus=fmFunc_status_get('category');
$datatype= fmFunc_data_types();

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

//获取分类
$sql = 'SELECT * FROM ' . tablename('fm453_shopping_category') . $condition.' ORDER BY `psn`, `displayorder` DESC, `createtime` ASC';
$category = pdo_fetchall($sql, $params, 'sn');
if (!empty($category)) {
	$parent = $children = array();
	foreach ($category as $cid => $cate) {
		if (!empty($cate['psn'])) {
			$children[$cate['psn']][] = $cate;
		} else {
			$parent[$cate['sn']] = $cate;
		}
	}
}

//POST筛选数据
	if (!empty($_GPC['keyword'])) {
		$condition .= ' AND `title` LIKE :title';
		$params[':title'] = '%' . trim($_GPC['keyword']) . '%';
	}

	if (!empty($_GPC['goodstpl'])) {
		if($_GPC['goodstpl']=='all') {
		}else{
			$condition .= ' AND `goodtpl` LIKE :goodstpl';
			$params[':goodstpl'] = '%' . trim($_GPC['goodstpl']) . '%';
		}
	}

	if (isset($_GPC['status'])) {
		if($_GPC['status']=='all') {
		}else{
			$condition .= ' AND `status` = :status';
			$params[':status'] = intval($_GPC['status']);
		}
	}

	if (isset($_GPC['shuxing'])) {
		if($_GPC['shuxing']=='all') {
		}else{
			$condition .= ' AND `'.$_GPC['shuxing'].'` = 1';
		}
	}

	if (!empty($_GPC['category']['childid'])) {
		$condition .= ' AND `ccate` = :ccate';
		$params[':ccate'] = intval($_GPC['category']['childid']);
	}
	if (!empty($_GPC['category']['parentid'])) {
		$condition .= ' AND `pcate` = :pcate';
		$params[':pcate'] = intval($_GPC['category']['parentid']);
	}

	if (isset($_GPC['type'])) {
		if($_GPC['type']=='all') {
		}else{
			$condition .= ' AND `type` = :type';
			$params[':type'] = intval($_GPC['type']);
		}
	}
//按当前条件所得的产品总量并且分页
$sql = 'SELECT COUNT(id) FROM ' . tablename('fm453_shopping_goods') . $condition;
$total = pdo_fetchcolumn($sql, $params);
$pager = pagination($total, $pindex, $psize);
//排序及截断
$showorder=" ORDER BY uniacid ASC, displayorder DESC, createtime DESC, id DESC ";
$limit=" LIMIT ".($pindex-1)*$psize.",".$psize;

if ($operation == 'display') {
	message('该功能不可被直接访问！');
}
elseif($operation=='spec'){
	$spec = array(
		"id" => random(32),
		"title" => $_GPC['title']
	);
	include $this->template($fm453style.$do.'/'.'addons'.'/'.'spec');
}
elseif($operation=='specitem'){
	$spec = array(
		"id" => $_GPC['specid']
	);
	$specitem = array(
		"id" => random(32),
		"title" => $_GPC['title'],
		"show" => 1
	);
	include $this->template($fm453style.$do.'/'.'addons'.'/'.'spec_item');
}
elseif($operation=='param'){
	include $this->template($fm453style.$do.'/'.'addons'.'/'.'param');
}
elseif($operation=='label'){
	include $this->template($fm453style.$do.'/'.'addons'.'/'.'label');
}
elseif($operation=='goodstpl'){
	$tpl=$_GPC['goodstpl'];
	$gsn=$_GPC['gsn'];
	$goodstplparams = fmMod_goodstpl_query_param($tpl,$platid);
	include $this->template($fm453style.$do.'/'.'addons'.'/'.'goodstpl');
}
elseif($operation=='option'){
	return;
}
