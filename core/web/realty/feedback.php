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
 * @remark 预约表单临时数据管理
*/

defined('IN_IA') or exit('Access Denied');
global $_GPC,$_W,$_FM;

load()->func('file');
load()->func('tpl');
load()->model('account');//加载公众号函数

//加载风格模板及资源路径
$fm453style = fmFunc_ui_shopstyle();
$fm453resource =FM_RESOURCE;

//入口判断
$routes=fmFunc_route_web();
$routes_do=fmFunc_route_web_do();
$do = $_GPC['do'];
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
$shopname = !empty($shopname) ? $shopname : FM_NAME_CN;
$_W['page']['title'] = $shopname.'数据报告';
$direct_url=fm_wurl($do,$ac,$operation,'');
$pindex=max(1,intval($_GPC['page']));
$psize=(intval($_GPC['psize'])>10) ? intval($_GPC['psize']) : 10;//最少显示10条主数据

$uniacid=$_W['uniacid'];
$plattype=$settings['plattype'];
$platids = fmFunc_getPlatids();//获取平台模式关联的公众号商户ID参数
$oauthid=$platids['oauthid'];
$fendianids=$platids['fendianids'];
$supplydianids=$platids['supplydianids'];
$blackids=$platids['blackids'];

//平台模式处理
include_once FM_CORE.'public/plat.php';
$supplydians=explode(',',$supplydianids);//字符串转数组
$supplydians=array_filter($supplydians);//数组去空

//按平台模式前置筛选条件
$condition=' WHERE ';
$params=array();
include_once FM_CORE.'public/forsearch.php';
$condition .= ' AND `deleted` = :deleted';
$params[':deleted'] = 0;

if ($operation == 'display') {
	$list = array();
	// 引用数据缓存文件，执行并返回数据
	$files = glob(FM_DATA.'form/'.$_W['uniacid'].'/*.php');	//需要PHP v>=4
	foreach($files as $file)
	{
		include $file;	//不可使用include_once  会导致数据只是读第一次就中断了
		$data = iunserializer($FmFormDataCache);
		$file  = str_replace(FM_DATA.'form/'.$_W['uniacid'].'/','',$file);
		$file = str_replace('.php','',$file);
		$key = $file;
		$list[$key] = $data;
	}
	include $this->template($fm453style.$do.'/453');
}