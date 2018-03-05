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
 * @remark 微查询模型管理；
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC, $_W,$_FM;

load()->func('tpl');
load()->model('account');//加载公众号函数

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

if ($operation == 'display') {
	$ac_length=count($all_ac)-1;
	unset($all_ac[$ac_length]);//不将ajax列出；ajax必须在路径表中被列在最后此操作才有效
	include $this->template($fm453style.'index');
}