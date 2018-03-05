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
 * @remark 后台总入口（主工作台）
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
$do=$_GPC['do'];
$ac=$_GPC['ac'];

//开始操作管理
$shopname=$settings['brands']['shopname'];
$shopname = !empty($shopname) ? $shopname :FM_NAME_CN;
$_W['page']['title'] = $shopname.$routes[$do]['title'];
$direct_url=fm_wurl($do,$ac,'index',array());

$settings = fmMod_settings_all();//全局加载配置
$_FM['settings']=$settings;

$shopname=isset($settings['brands']['shopname']) ? $settings['brands']['shopname'] : FM_NAME_CN;
$_W['page']['title'] = $shopname.'|'.$routes[$do]['title'];

$fm453style = "web/default/";
$fm453resource =FM_RESOURCE;

//模块绑定菜单
$sql = 'SELECT * FROM '.tablename('modules_bindings');
$params=array();
$condition = ' WHERE ';
$condition .= 'module = :module ';
$params[':module']= FM_NAME;
$condition .= ' AND entry = :entry ';
$params[':entry']= 'cover';
$entries=pdo_fetchall($sql.$condition,$params);
foreach($entries as &$entry){
  $entry['url'] = "./index.php?c=platform&a=cover&do=post&m=".FM_NAME."&eid=".$entry['eid'];
}
$menus['cover'] = $entries;

require_once FM_VAR ."web.php"; //全局可变变量
require_once FM_VAR ."fastlinks.php"; //列举一些前端常用链接
require_once FM_VAR ."adminlinks.php";  //后台普通管理员快捷链接
require_once FM_VAR ."menulinks.php"; //后台通用顶部菜单栏链接

include $this->template('index');
