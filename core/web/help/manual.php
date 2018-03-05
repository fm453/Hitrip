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
 * @remark 自助开发帮助说明
 */
defined('IN_IA') or exit('Access Denied');
global $_W, $_GPC,$_FM;
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
$_W['page']['title'] = $shopname.'操作手册';

$fm453style = fmFunc_ui_shopstyle();
$fm453resource =FM_RESOURCE;
$result=fmMod_setting_query_list($_W['uniacid'],'127');
if($result['result']) {
	$settings=$result['data'];
}
$result=fmMod_setting_query_sys();
if($result['result']) {
	$settings['shouquan']=$result['data']['shouquan'];
}
$ac=$_GPC['ac'];
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'modify'; //操作项有modify，develop
$url_modify =  $this->createWebUrl('help',array('ac'=>'manual','op'=>'modify'));
$url_develop =  $this->createWebUrl('help',array('ac'=>'manual','op'=>'develop'));


include $this->template('help/manual');