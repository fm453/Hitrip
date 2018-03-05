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
 * @remark 微餐饮管理；
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;

load()->func('tpl');
load()->model('account');//加载公众号函数
fm_load()->fm_func('route'); //获取路径函数
fm_load()->fm_model('category'); //分类管理模块

//加载风格模板及资源路径
$fm453style    = fmFunc_ui_shopstyle();
$fm453resource =FM_RESOURCE;

//入口判断
$routes        =fmFunc_route_web();
$routes_do     =fmFunc_route_web_do();
$do            = $_GPC['do'];
$ac            =$_GPC['ac'];
$all_ac        =fmFunc_route_web_ac($do);
$operation     = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$all_op=fmFunc_route_web_op_single($do,$ac);
if(!in_array($ac,$all_ac) || !in_array($operation,$all_op)){
	die(message('非法访问，请通过有效路径进入！'));
}

fmMod_privilege_adm();//检查用户授权

//开始操作管理
$shopname            =$settings['brands']['shopname'];
$shopname            = !empty($shopname) ? $shopname :FM_NAME_CN;
$_W['page']['title'] = $shopname.$routes[$do]['title'];
$direct_url          =fm_wurl($do,$ac,$operation,'');
$pindex              =max(1,intval($_GPC['page']));
$psize               =(intval($_GPC['psize'])>10) ? intval($_GPC['psize']) : 10;//最少显示10条主数据

$uniacid             =$_W['uniacid'];
$plattype            =$settings['plattype'];
$platids             = fmFunc_getPlatids();//获取平台模式关联的公众号商户ID参数
$oauthid             =$platids['oauthid'];
$fendianids          =$platids['fendianids'];
$supplydianids       =$platids['supplydianids'];
$blackids            =$platids['blackids'];

//平台模式处理
include_once FM_PUBLIC.'plat.php';
$supplydians        =explode(',',$supplydianids);//字符串转数组
$supplydians        =array_filter($supplydians);//数组去空

//按平台模式前置筛选条件
$condition          =' WHERE ';
$params             =array();
include_once FM_PUBLIC.'forsearch.php';
$condition          .= ' AND `deleted` = :deleted';
$params[':deleted'] = 0;


if ($operation == 'display') {
	$category = pdo_fetchall("SELECT * FROM ".tablename('fm453_vfoods_shoptype')." WHERE uniacid ='{$_W['uniacid']}' ORDER BY displayorder ASC");
	include $this->template($fm453style.$do.'/453');
} elseif ($operation == 'post') {
	$id = intval($_GPC['id']);

	if(!empty($id)) {
		$category = pdo_fetch("SELECT * FROM ".tablename('fm453_vfoods_shoptype')." WHERE id = '$id'");
	} else {
		$category = array(
			'displayorder' => 0,
		);
	}
	if (checksubmit('submit')) {
		if (empty($_GPC['typename'])) {
			message('抱歉，请输入分类名称！');
		}
		$data = array(
			'uniacid' => $_W['uniacid'],
			'title' => $_GPC['typename'],
			'displayorder' => intval($_GPC['displayorder']),
			'description' => $_GPC['description'],

		);
		if (!empty($id)) {
			pdo_update('fm453_vfoods_shoptype', $data, array('id' => $id));
		} else {
			pdo_insert('fm453_vfoods_shoptype', $data);
			$id = pdo_insertid();
		}
		message('更新分类成功！', fm_wurl($do,$ac,'', array('id' => $id)), 'success');
	}
	include $this->template($fm453style.$do.'/453');
} elseif ($operation == 'delete') {
	$id = intval($_GPC['id']);
	$category = pdo_fetch("SELECT * FROM ".tablename('fm453_vfoods_shoptype')." WHERE id = '$id'");
	if (empty($category)) {
		message('抱歉，分类不存在或是已经被删除！', fm_wurl($do,$ac,'', array()), 'error');
	}
	pdo_delete('fm453_vfoods_shoptype', array('id' => $id));
	message('分类删除成功！', fm_wurl($do,$ac,'', array()), 'success');
}