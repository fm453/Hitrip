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
 * @remark 主入口页
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;

//根据相关设置及规则，得出商城真正的首页或跳转到指定入口页
if(isset($settings['index']['url']) && !empty($settings['index']['url'])) {
	header('location:'.$settings['index']['url']);
	exit();
}

fm_load()->fm_func('share');//链接分享管理
fm_load()->fm_model('shopcart'); //购物车模块
fm_load()->fm_func('view'); 	//浏览量处理
fm_load()->fm_func('wechat');//微信定义管理

//加载风格模板及资源路径
$appstyle = empty($settings['appstyle']) ? FM_APPSTYLE : $settings['appstyle'];
$appsrc =MODULE_URL.'template/mobile/'.$appstyle.'453/';
$htmlsrc =MODULE_URL.'template/mobile/'.$appstyle;
$fm453resource = FM_RESOURCE;
//入口判断
$do= $_GPC['do'];
$ac=$_GPC['ac'];
$op = $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'index';
//开始操作管理
$shopname=$settings['brands']['shopname'];
$shopname = !empty($shopname) ? $shopname :FM_NAME_CN;
$pagename = $shopname.'首页';
$pagename .='|'.$_W['account']['name'];

$uniacid=$_W['uniacid'];
$plattype=$settings['plattype'];
$platids= fmFunc_getPlatids();//获取平台模式关联的公众号商户ID参数
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

//根据商城类型进入不同的首页
$new_ac= (!isset($settings['shoptype']) || !in_array($settings['shoptype'],array('home','simple','site'))) ? 'home' : $settings['shoptype'];
$showguide = cache_load('index_showguide_'.$_W['uniacid']);
$url = fm_murl('index',$new_ac,'index',array('showguide'=>$showguide,'needentry'=>true));
$GuideToUrl = $url;
$showguide = (isset($_GPC['showguide']) && $_GPC['showguide']==true) ? true : $showguide;
if($showguide) {
	$settings['guide']['guide_page_num'] = (isset($settings['guide']['guide_page_num']) && intval($settings['guide']['guide_page_num'])>3) ? intval($settings['guide']['guide_page_num']) : 5;
	if($settings['guide']['page_'.$settings['guide']['guide_page_num'].'_link']){
	    $GuideToUrl = $settings['guide']['page_'.$settings['guide']['guide_page_num'].'_link'];
	}
	cache_write('index_showguide_'.$_W['uniacid'],FALSE);
	include $this->template($appstyle.'guide');	//启动屏,没有设置否时，会默认显示启动屏
}else{
	include $this->template($appstyle.'index');//全局总入口页
}
exit;
