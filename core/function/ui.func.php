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
 * @remark：UI处理函数
 */
defined('IN_IA') or exit('Access Denied');

//全局设定，商城资源包路径（js\css\fonts等文件）
function fmFunc_ui_resource(){
	global $_GPC;
	global $_W;
	global $_FM;
	$fm453resource = FM_RESOURCE;
	return $fm453resource;
}

//全局设定，网站后台风格
function fmFunc_ui_shopstyle($moduleConf=null){
	global $_GPC;
	global $_W;
	global $_FM;
	$moduleConf = !empty($moduleConf) ? $moduleConf : $_FM['settings'];
	$fm453style = $moduleConf['shopstyle'];
	if (empty($fm453style)){
		$fm453style='web/default/';
	}
	return $fm453style;
}

//全局设定，客户端前台风格;
function fmFunc_ui_appstyle($moduleConf=null){
	global $_GPC;
	global $_W;
	global $_FM;
	$moduleConf = !empty($moduleConf) ? $moduleConf : $_FM['settings'];
	$appstyle = $moduleConf['appstyle'];
	if (empty($appstyle) || $appstyle=='mui/'){
		$appstyle='default/';
	}
	return $appstyle;
}

//全局设定，客户端前台风格关联的默认资源文件夹;
function fmFunc_ui_appsrc(){
	$appstyle = fmFunc_ui_appstyle();
	$appsrc = MODULE_URL.'template/mobile/'.$appstyle.'453/';
	return $appsrc;
}
