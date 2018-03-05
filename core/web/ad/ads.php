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
 * @remark 广告管理；
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;
load()->func('tpl');
load()->model('account');//加载公众号函数
fm_load()->fm_model('category'); //分类管理模块

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

$sql = "SELECT * FROM " . tablename('fm453_shopping_adboxes') .$condition.$showorder;
$adboxes = pdo_fetchall($sql,$params,'id');

$category = $adboxes;
if (!empty($category)) {
	$parent = $children = array();
	foreach ($category as $cid => $cate) {
		if (!empty($cate['parentid'])) {
			$children[$cate['parentid']][] = $cate;
		} else {
			$parent[$cate['id']] = $cate;
		}
	}
}

if ($operation == 'display') {
	$list = pdo_fetchall("SELECT * FROM " . tablename('fm453_shopping_ads') . " WHERE uniacid = '{$_W['uniacid']}' ORDER BY displayorder DESC");
			//准备添加判断广告图启用状态的 start
			$enabled = array (
					'0' => array('name' => '停用'),
					'1' => array('name' => '启用'),
			);
			foreach ($list as &$value) {
				$value['enabled'] = $enabled[$value['enabled']]['name'];
				$sql = "SELECT name FROM " . tablename('fm453_shopping_adboxes') . " WHERE id = '{$value['ccate']}' ";
				$value['catname'] = pdo_fetchcolumn($sql);

			}
			//准备添加判断广告图启用状态的 end
	if (!empty($_GPC['displayorder'])) {
		foreach ($_GPC['displayorder'] as $id => $displayorder) {
			pdo_update('fm453_shopping_ads', array('displayorder' => $displayorder), array('id' => $id));
		}
		message('广告位排序更新成功！', 'referer', 'success');
	}
include $this->template($fm453style.$do.'/453');
}
elseif ($operation == 'modify') {
	$id = intval($_GPC['id']);
	if($id<1) {
		message('请先选择一个广告再操作！', 'referer', 'info');
	}
	$ads=pdo_fetch("SELECT * FROM " . tablename('fm453_shopping_ads') . " WHERE id = :id ",array(':id'=>$id));
	if (checksubmit('submit')) {
				$data = array(
					'uniacid' => $_W['uniacid'],
					'pcate' => intval($_GPC['category']['parentid']),
			    'ccate' => intval($_GPC['category']['childid']),
					'adname' => $_GPC['adname'],
					'link' => $_GPC['link'],
					'enabled' => intval($_GPC['enabled']),
					'displayorder' => intval($_GPC['displayorder']),
					'thumb'=>$_GPC['thumb'],
					'viewcount'=>$_GPC['viewcount'],
					'clickcount'=>$_GPC['clickcount']
			);
		$result=pdo_update('fm453_shopping_ads', $data, array('id' => $id));
		if($result) {
			message('更新广告成功！', fm_wurl($do,$ac,'modify', array('id'=>$id)), 'success');
		}
	}
	include $this->template($fm453style.$do.'/453');
}
elseif ($operation == 'add') {
        if (checksubmit('submit')) {
				$data = array(
					'uniacid' => $_W['uniacid'],
					'pcate' => intval($_GPC['category']['parentid']),
			    'ccate' => intval($_GPC['category']['childid']),
					'adname' => $_GPC['adname'],
					'link' => $_GPC['link'],
					'enabled' => intval($_GPC['enabled']),
					'displayorder' => intval($_GPC['displayorder']),
					'thumb'=>$_GPC['thumb'],
					'viewcount'=>$_GPC['viewcount'],
					'clickcount'=>$_GPC['clickcount']
            );
					pdo_insert('fm453_shopping_ads', $data);
					$id = pdo_insertid();
				message('添加广告成功！', fm_wurl($do,$ac,'modify', array('id'=>$id)), 'success');
			}
	include $this->template($fm453style.$do.'/453');
}
elseif ($operation == 'copy') {
	$id = intval($_GPC['id']);
	if($id<1) {
		message('请先选择一个广告再操作！', 'referer', 'info');
	}
	$ads=pdo_fetch("SELECT * FROM " . tablename('fm453_shopping_ads') . " WHERE id = :id ",array(':id'=>$id));
        if (checksubmit('submit')) {
				$data = array(
					'uniacid' => $_W['uniacid'],
					'pcate' => intval($_GPC['category']['parentid']),
			    'ccate' => intval($_GPC['category']['childid']),
					'adname' => $_GPC['adname'],
					'link' => $_GPC['link'],
					'enabled' => intval($_GPC['enabled']),
					'displayorder' => intval($_GPC['displayorder']),
					'thumb'=>$_GPC['thumb'],
					'viewcount'=>$_GPC['viewcount'],
					'clickcount'=>$_GPC['clickcount']
            );

					pdo_insert('fm453_shopping_ads', $data);
					$id = pdo_insertid();
				message('广告复制成功！', fm_wurl($do,$ac,'modify', array('id'=>$id)), 'success');
			}
	include $this->template($fm453style.$do.'/453');
}
else {
    	message('请求方式不存在');
}
