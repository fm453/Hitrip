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
 * @remark 店铺点餐下单
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;
load()->func('tpl');
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

// //平台模式处理
// require_once FM_PUBLIC.'plat.php';
// $supplydians   =explode(',',$supplydianids);//字符串转数组
// $supplydians   =array_filter($supplydians);//数组去空

// //按平台模式前置筛选条件
// $condition     =' WHERE ';
// $params        =array();
// require_once FM_PUBLIC.'forsearch.php';

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

fm_load()->fm_func('foods');
//页面具体操作
$shopid = intval($_GPC['pcate']);	//店铺ID
if($_GPC['id']){
	$shopid = intval($_GPC['id']);
}
checkauth();	//要求登陆

$keywords=$_GPC['keywords'];

//SESSION判断订单类型
$ordertype = isset($_SESSION['ordertype']) ? $_SESSION['ordertype'] : 'waimai';		//将订单类型写入SESSION,无设定时默认为外卖
$_dingcantypes = $settings['vfoods']['dingcantypes'];	//设置的订餐模式外显数据
$_dingcantypes['tangshi']['title'] = isset($_dingcantypes['tangshi']['title']) ? $_dingcantypes['tangshi']['title'] : '堂食';
$_dingcantypes['waimai']['title'] = isset($_dingcantypes['waimai']['title']) ? $_dingcantypes['waimai']['title'] : '外卖';
$_dingcantypes['ziqu']['title'] = isset($_dingcantypes['ziqu']['title']) ? $_dingcantypes['ziqu']['title'] : '自取';

$_diancantypes_showorder = isset($_dingcantypes['displayorder']) ? $_dingcantypes['displayorder'] : array('tangshi','waimai','ziqu');

//百度地图AK
$settings['api']['baidu_map_ak'] = isset($settings['api']['baidu_map_ak']) ? $settings['api']['baidu_map_ak'] : 'ng0vVZSU3GLhmdhZKR5FZ2gD2oCNyhyG';

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

$cache_key = md5(FM_NAME.'_'.$_W['uniacid'].'_'.'vfoods[category]'.'_'.$category['id']);
cache_write($cache_key,$category);

if($category['enabled']==0){
	fm_tips($msgbody="该餐厅已暂停开放！",$msgtitle="温馨提示",$backurl=fm_murl('vfoods','index','index',array('ordertype'=>$ordertype)));
}

$ptime1 = $category['time1'];
$ptime2 = $category['time2'];
$ptime3 = $category['time3'];
$ptime4 = $category['time4'];

//判断时效
$isInTime = fmFunc_foods_checkTime($ptime1,$ptime2,$ptime3,$ptime4);
if(!$isInTime){
	fm_tips($msgbody="该餐厅未到营业时间或已打烊，请在{$category['time1']}~{$category['time2']}或{$category['time3']}~{$category['time4']}内访问使用！",$msgtitle="温馨提示",$backurl=fm_murl('vfoods','index','index',array('ordertype'=>$ordertype)));
}

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
$condition .= " AND `status` =1 ";
if($keywords){
	$condition .= " AND `title` LIKE '%".$keywords."%' ";
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

if($op=="index"){
	//更新流量、链路统计
	if(!empty($shareid)){
		fmFunc_share_click($lastid,$shareid,$currentid,$share_user);
	}
	fmFunc_view();//记录访问
	//fmMod_member_check($_W['openid']);//检测会员
}
include fmFunc_template_m($do.'/453');
