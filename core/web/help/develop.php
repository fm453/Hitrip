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
fm_load()->fm_func('fm'); //内置专用函数库（函数用于获取内容，不涉及数据库写入）
fm_load()->fm_func('tpl');
fm_load()->fm_model('log'); //日志模块
fm_load()->fm_model('setting'); //内置模块配置模块

//加载风格模板及资源路径

$fm453style = fmFunc_ui_shopstyle();
$fm453resource =FM_RESOURCE;

//入口判断
$routes=fmFunc_route_web();
$routes_do=fmFunc_route_web_do();
$do= $_GPC['do'];
$ac=$_GPC['ac'];
$all_ac=fmFunc_route_web_ac($do);
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'modify'; //操作项有modify，develop
$all_op=fmFunc_route_web_op_single($do,$ac);
if(!in_array($ac,$all_ac) || !in_array($operation,$all_op)){
	//die(message('非法访问，请通过有效路径进入！'));
}

//fmMod_privilege_adm();//检查用户授权

//开始操作管理
$shopname=$settings['brands']['shopname'];
$shopname = !empty($shopname) ? $shopname :FM_NAME_CN;
$_W['page']['title'] = $shopname.'自助开发帮助文档';

$url_modify =  $this->createWebUrl('help',array('ac'=>'develop','op'=>'modify'));
$url_develop =  $this->createWebUrl('help',array('ac'=>'develop','op'=>'develop'));

$uniacid=$_W['uniacid'];
$plattype=$settings['plattype'];
$platids = fmFunc_getPlatids();//获取平台模式关联的公众号商户ID参数
$oauthid=$platids['oauthid'];
$fendianids=$platids['fendianids'];
$supplydianids=$platids['supplydianids'];
$blackids=$platids['blackids'];

//获取服务器信息
$systemInfo = array();
  // 系统
 $systemInfo['os']['name'] ='操作系统：';
 $systemInfo['os']['value'] =PHP_OS;

  $systemInfo['phpversion']['name'] ='PHP版本：';
 $systemInfo['phpversion']['value'] = PHP_VERSION;
  // Apache版本
 //$systemInfo['apacheversion']['name'] ='网站服务器系统：';
 //$systemInfo['apacheversion']['value'] = apache_get_version();
  // ZEND版本
 $systemInfo['zendversion']['name'] ='ZEND版本：';
 $systemInfo['zendversion']['value'] = zend_version();
  // GD相关
  $systemInfo['gdsupport']['name'] ='是否支持GD库：';
 if (function_exists('gd_info'))
 {
  $gdInfo = gd_info();
  $systemInfo['gdsupport']['value'] = true;
  $systemInfo['gdversion']['name'] ='GD库版本：';
  $systemInfo['gdversion']['value'] = $gdInfo['GD Version'];
 }
 else
 {
  $systemInfo['gdsupport']['value'] = false;
 }
 // 最大上传文件大小
 $systemInfo['maxuploadfile']['name'] ='最大上传文件大小：';
 $systemInfo['maxuploadfile']['value'] = ini_get('upload_max_filesize');

 // 脚本运行占用最大内存
 $systemInfo['memorylimit']['name'] ='脚本运行占用最大内存：';
 $systemInfo['memorylimit']['value'] = get_cfg_var("memory_limit") ? get_cfg_var("memory_limit") : '-';

include $this->template('help/develop');