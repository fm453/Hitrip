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
 * @remark 商城各表中状态数据说明规范
 */
defined('IN_IA') or exit('Access Denied');
global $_W, $_GPC,$_FM;

if(!pdo_tableexists('fm453_shopping_status')) {
	message('该功能仅供本地调试使用','referer','false');
	exit();
}

load()->func('tpl');

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
$_W['page']['title'] = $shopname.'各种状态规范说明';

$lists=array();

$sql = "SELECT * FROM " . tablename('fm453_shopping_status') . " WHERE statuscode > 0 ";
$displayorder =" ORDER BY workflow DESC ,sn DESC";

$tables = fmFunc_tables_types();
foreach($tables as $key=>$table){
//	$condition ="AND (`setfor` LIKE '%{$key}%' OR `setfor` LIKE '%all%' ) ";//用OR这句也行，但是推荐用in
//	$condition ="AND `setfor` in ('{$key}','all') ";
	$condition ="AND `setfor` LIKE '%{$key}%' ";
	$result=pdo_fetchall($sql.$condition.$displayorder);
	if($result){
		$lists[$key]=$result;
	}
	unset($result);
}
if($operation=='display'){

}elseif($operation=='add') {
	if(checksubmit('add')) {
		$sn=$_GPC['sn'];
		if(empty($sn)) {
			break;
		}
		$des=$_GPC['description'];
		if(empty($des)) {
			break;
		}
		$setfor=$_GPC['setfor'];
		if(empty($setfor)) {
			break;
		}
		$showname=$_GPC['showname'];
		if(empty($showname)) {
			break;
		}
		$workflow=$_GPC['workflow'];
		if(empty($workflow)) {
			break;
		}
		$data=array(
			'sn'=>$sn,
			'des'=>$des,
			'setfor'=>$setfor,
			'showname'=>$showname,
			'workflow'=>$workflow,
			'statuscode'=>127,
		);
		pdo_insert('fm453_shopping_status', $data);
		message('添加成功','referer','success');
	}
}elseif($operation=='modify') {
	$id=$_GPC['id'];
	$sql = "SELECT * FROM " . tablename('fm453_shopping_status') . " WHERE id = {$id} ";
	$sitem=pdo_fetch($sql);
	if(checksubmit('modify')) {
		$sn=$_GPC['sn'];
		if(empty($sn)) {
			break;
		}
		$des=$_GPC['description'];
		if(empty($des)) {
			break;
		}
		$setfor=$_GPC['setfor'];
		if(empty($setfor)) {
			break;
		}
		$showname=$_GPC['showname'];
		if(empty($showname)) {
			break;
		}
		$workflow=$_GPC['workflow'];
		if(empty($workflow)) {
			break;
		}
		$data=array(
			'sn'=>$sn,
			'des'=>$des,
			'setfor'=>$setfor,
			'showname'=>$showname,
			'workflow'=>$workflow,
			'statuscode'=>127,
		);
		pdo_update('fm453_shopping_status', $data,array('id'=>$id));
		message('修改成功','referer','success');
	}
}
include $this->template('help/status');