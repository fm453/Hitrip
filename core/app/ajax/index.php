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
 * 说明：AJAX更新一些数据；
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;

fm_load()->fm_func('tables'); //数据表函数
fm_load()->fm_model('shopcart'); //购物车模块
fm_load()->fm_func('view'); 	//浏览量处理

$_FM['member']['settings'] = fmMod_member_settings($member_id);	//粉丝个性设置

//加载风格模板及资源路径
$appstyle = empty($settings['appstyle']) ? FM_APPSTYLE : $settings['appstyle'];
$appsrc =MODULE_URL.'template/mobile/'.$appstyle.'453/';
$fm453resource = FM_RESOURCE;

//入口判断
$do=$_GPC['do'];
$ac=$_GPC['ac'];
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'index';
//开始操作管理
$shopname=$settings['brands']['shopname'];
$shopname = !empty($shopname) ? $shopname :FM_NAME_CN;
$pagename = $shopname.'首页';
$pagename .='|'.$_W['account']['name'];

$uniacid=$_W['uniacid'];
$plattype=$settings['plattype'];
$platids = fmFunc_getPlatids();//获取平台模式关联的公众号商户ID参数
$platid=$_W['uniacid'];
$oauthid=$platids['oauthid'];
$fendianids=$platids['fendianids'];
$supplydianids=$platids['supplydianids'];
$blackids=$platids['blackids'];

//平台模式处理
require_once FM_PUBLIC.'plat.php';
$supplydians=explode(',',$supplydianids);//字符串转数组
$supplydians=array_filter($supplydians);//数组去空

//按平台模式前置筛选条件
$condition=' WHERE ';
$params=array();
require_once FM_PUBLIC.'forsearch.php';

//AJAX处理
$return = array();
$setfor=$_GPC['setfor'];
if($op=='index'){
	if($setfor=='ads') {
		$id = $_GPC['id'];
		$d_o = $_GPC['d_o'];
		if($d_o == 'view') {
			$rturn['view'] = view('ads',$id);
		}
	}
	return $return;
}
elseif($op='randkeyword') {
	$type = ($_GPC['type']) ? $_GPC['type'] : 'goods';
	include_once MODULE_ROOT.'/core/app/search/keywords.php';
	$count = count($LIB_keywords[$type]);
	$key = array_rand($LIB_keywords[$type],1);
	$keywords = ($_GPC['keywords']) ? $_GPC['keywords'] : $LIB_keywords[$type][$key];
	echo json_encode($keywords);
}
