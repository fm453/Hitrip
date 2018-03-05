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
 * @remark 微餐饮
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;
load()->func('tpl');
fm_load()->fm_func('lbs');
fm_load()->fm_func('share');//链接分享管理
fm_load()->fm_func('view'); 	//浏览量处理
fm_load()->fm_func('wechat');//微信定义管理

//加载风格模板及资源路径
$appstyle      = empty($settings['appstyle']) ? FM_APPSTYLE : $settings['appstyle'];
$appsrc        =MODULE_URL.'template/mobile/'.$appstyle.'453/';
$htmlsrc       =MODULE_URL.'template/mobile/'.$appstyle;
$fm453resource = FM_RESOURCE;
//入口判断
$do            = $_GPC['do'];
$ac            =$_GPC['ac'];
$op            = !empty($_GPC['op']) ? $_GPC['op'] : 'index';

//开始操作管理
$shopname      =$settings['brands']['shopname'];
$shopname      = !empty($shopname) ? $shopname :FM_NAME_CN;

$uniacid       =$_W['uniacid'];
$plattype      =$settings['plattype'];
$platids       =fmFunc_getPlatids();//获取平台模式关联的公众号商户ID参数
$platid        =$_W['uniacid'];
$oauthid       =$platids['oauthid'];
$fendianids    =$platids['fendianids'];
$supplydianids =$platids['supplydianids'];
$blackids      =$platids['blackids'];

//平台模式处理
require_once FM_PUBLIC.'plat.php';
$supplydians   =explode(',',$supplydianids);//字符串转数组
$supplydians   =array_filter($supplydians);//数组去空

//按平台模式前置筛选条件
$condition     =' WHERE ';
$params        =array();
require_once FM_PUBLIC.'forsearch.php';

$share_user    =$_GPC['share_user'];
$shareid       = intval($_GPC['shareid']);
$lastid        = intval($_GPC['lastid']);
$currentid     = intval($_W['member']['uid']);
$fromplatid    = intval($_GPC['fromplatid']);
$from_user     = $_W['openid'];
$url_condition ="";
$direct_url    = fm_murl($do,$ac,$op,array());

//自定义微信分享内容
$_share           = array();
$_share['title']  = $shopname.'|'.$_W['account']['name'];
$_share['link']   = fm_murl($do,$ac,$operation,array('isshare'=>1));
$_share['link']   = $_share['link'].$url_condition;
$_share['imgUrl'] = tomedia($settings['brands']['logo']);
$_share['desc']   = $settings['brands']['share_des'];

// $resultmember     = fmMod_member_query($currentid);
// $FM_member        =$resultmember['data'];

// //会员自定义设置
// $mine_settings    =$_FM['member']['settings'];

//页面具体操作
//MUI侧边栏链接
$shoptype = pdo_fetchall("SELECT * FROM ".tablename('fm453_vfoods_shoptype')." WHERE uniacid = '{$_W['uniacid']}' ");	//全部店家
$appNavs=array();
require_once FM_APP.$do.DIRECTORY_SEPARATOR.'_aside.php';

$shopid = intval($_GPC['pcate']);	//店铺ID
if($_GPC['id']){
	$shopid = intval($_GPC['id']);
}

checkauth();	//要求登陆

$pindex = max(1, intval($_GPC['page']));
$psize = 15;
$condition = '';
if (!empty($_GPC['ccate'])) {
	$cid = intval($_GPC['ccate']);
	$condition .= " AND ccate = '{$cid}'";
	$ccate = pdo_fetch("SELECT * FROM ".tablename('fm453_vfoods_category')." WHERE uniacid = '{$_W['uniacid']}' AND id = '{$_GPC['ccate']}' ");
	$category = pdo_fetch("SELECT * FROM ".tablename('fm453_vfoods_category')." WHERE uniacid = '{$_W['uniacid']}' AND id = '{$ccate['psn']}' ");
	$sort = pdo_fetchall("SELECT * FROM ".tablename('fm453_vfoods_category')." WHERE uniacid = '{$_W['uniacid']}' AND psn = '{$ccate['psn']}' ");
} elseif (!empty($shopid)) {
	$cid = intval($shopid);
	$condition .= " AND pcate = '{$cid}'";
	$category = pdo_fetch("SELECT * FROM ".tablename('fm453_vfoods_category')." WHERE uniacid = '{$_W['uniacid']}' AND id = '{$shopid}' ");
	$sort = pdo_fetchall("SELECT * FROM ".tablename('fm453_vfoods_category')." WHERE uniacid = '{$_W['uniacid']}' AND psn = '{$shopid}' ");
}

//强制跳出本详情页
$isnodetail = false;
if($category['nodetail']) {
	$isnodetail = true;
}
if($isnodetail) {
	header("Location:".fm_murl($do,'list','index',array('id'=>$shopid)));
}

$ptime1 = $category['time1'];
$ptime2 = $category['time2'];
$ptime3 = $category['time3'];
$ptime4 = $category['time4'];
$pcatefoods = pdo_fetchall("SELECT * FROM ".tablename('fm453_vfoods_foods')." WHERE uniacid = '{$_W['uniacid']}' AND pcate = '{$category['id']}' ");
$pricetotal =0;
$pcatetotal =0;
foreach ($pcatefoods as &$row) {
	$pcatecart = pdo_fetch("SELECT * FROM ".tablename('fm453_vfoods_cart')." WHERE from_user = :from_user AND uniacid = '{$_W['uniacid']}' AND sn = '{$row['id']}'", array(':from_user' => $_W['fans']['from_user']));
	$pcatetotal += $pcatecart['total'];
	$price = pdo_fetch("SELECT * FROM ".tablename('fm453_vfoods_foods')." WHERE uniacid = '{$_W['uniacid']}' AND id = '{$pcatecart['sn']}'");
	if($price['preprice']){
		$pricetotal += $price['preprice']*$pcatecart['total'];
	}else{
		$pricetotal += $price['oriprice']*$pcatecart['total'];
	}
	$ccatenum[$price['ccate']]['num'] += $pcatecart['total'];
	$ccatenum[$price['ccate']]['id'] = $price['ccate'];
}
$between = $category['sendprice']-$pricetotal;

switch($_GPC['order']){
	default: $orderStr = 'ishot DESC';break;
	case '1': $orderStr = 'hits DESC';break;
	case '2': $orderStr = 'preprice ASC';break;
	case '3': $orderStr = 'title ASC';break;
}
$list = pdo_fetchall("SELECT * FROM ".tablename('fm453_vfoods_foods')." WHERE uniacid = '{$_W['uniacid']}' $condition ORDER BY $orderStr LIMIT ".($pindex - 1) * $psize.','.$psize);

$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fm453_vfoods_foods') . " WHERE uniacid = '{$_W['uniacid']}' $condition");
$pager = pagination($total, $pindex, $psize, $url = '', $context = array('before' => 0, 'after' => 0, 'ajaxcallback' => ''));
if (!empty($list)) {
	foreach ($list as &$row) {
		$foodsid = pdo_fetchall("SELECT sn,total FROM ".tablename('fm453_vfoods_cart')." WHERE sn = '{$row['id']}' AND from_user = '{$_W['fans']['from_user']}'", array(), 'sn');
		$row['sn'] = $foodsid;
	}
}

$pagename = $category['title'];
$pagename = "餐厅详情";

if($op=="index"){
	//更新流量、链路统计
	if(!empty($shareid)){
		fmFunc_share_click($lastid,$shareid,$currentid,$share_user);
	}
	fmFunc_view();//记录访问
	// fmMod_member_check($_W['openid']);//检测会员
}
include fmFunc_template_m($do.'/453');
