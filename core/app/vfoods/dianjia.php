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
fm_load()->fm_func('foods');
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
$mine_settings    =$_FM['member']['settings'];

//页面具体操作
//MUI侧边栏链接
$shoptype = pdo_fetchall("SELECT * FROM ".tablename('fm453_vfoods_shoptype')." WHERE uniacid = '{$_W['uniacid']}' ");	//全部店家
$appNavs=array();
require_once FM_APP.$do.DIRECTORY_SEPARATOR.'_aside.php';

if($op=="index"){
	//更新流量、链路统计
	if(!empty($shareid)){
		fmFunc_share_click($lastid,$shareid,$currentid,$share_user);
	}
	fmFunc_view();//记录访问
	// fmMod_member_check($_W['openid']);//检测会员
	//模板主框架（父页面）
	include fmFunc_template_m($do.'/453');
	exit();
}

$fansloc = pdo_fetch("SELECT * FROM ".tablename('fm453_vfoods_loc')." WHERE uniacid = '{$_W['uniacid']}' AND from_user = '{$_W['openid']}'");
if($op == "locate"){
	if($fansloc['loc_x']){
		pdo_update('fm453_vfoods_loc', array('loc_x' => $_GPC['loc_x'], 'loc_y' => $_GPC['loc_y'], 'createtime' =>TIMESTAMP), array('from_user' => $_W['fans']['from_user'], 'uniacid' => $_W['uniacid']));
		$difference = fmFunc_lbs_getDistance($fansloc["loc_x"],$fansloc["loc_y"],$_GPC['loc_x'],$_GPC['loc_y']);
        if($difference > 0.3) $result = "refresh";
	}else{
		pdo_insert('fm453_vfoods_loc', array('loc_x' => $_GPC['loc_x'], 'loc_y' => $_GPC['loc_y'], 'createtime' =>TIMESTAMP,'from_user' => $_W['fans']['from_user'], 'uniacid' => $_W['uniacid']));
		$result = "refresh";
	}
	message($result, '', 'ajax');
	exit();
}

fm_load()->fm_model('ad');
//幻灯片
$result_advs = fmMod_ad_adv_mine();
$advs = $result_advs['data'];
$lastadvno=count($advs)-1;
$lastadv=$advs[$lastadvno];
$firstadv=$advs[0];

//广告
$result_ads = fmMod_ads_mine();
$ads = $result_ads['data'];
$adboxes = $result_ads['adboxes'];
// 根据模板需要，改造广告位的广告结构
//改造第二个广告位的默认节点(取其中最后两个有设置了图片的广告)（必须保证有3个以上的广告才有效）

if(count($ads[1])>=3) {
	$li_count = 2; //各节点要求多少个广告
	$node_count = round(count($ads[1])/$li_count,0);
	$node_count_1=$node_count;
	$i = count($ads[1])-1;
	for ($n=0; $n<$node_count; $n++) {
		for ($k=0;$k<$li_count;$k++){
			$m = $li_count*$n+$k;
			if($m>$i) {
				$m = $k;	//最后一个节点广告不足时，从头再取补齐
			}
			$adboxes[1]['fornode'][$n][] = $ads[1][$m];
		}
	}
	$adboxes[1]['first'] = $adboxes[1]['fornode'][$node_count-1];
	$adboxes[1]['last'] = $adboxes[1]['fornode'][0];
}
else{
	$adboxes[1] = FALSE;
}
//改造第三个广告位（必须显示4个）
if(count($ads[2])>=4) {
	$node_count = 1; //节点数
	$node_count_2=$node_count;
	$li_count = 4; //节点要求多少个广告
	$i = count($ads[2])-1;
	for ($n=0; $n<$li_count; $n++) {
		$m =$n;
		$adboxes[2]['fornode'][0][] = $ads[2][$m];
	}
}else{
	$adboxes[2] = FALSE;
}

//百度地图AK
$settings['api']['baidu_map_ak'] = isset($settings['api']['baidu_map_ak']) ? $settings['api']['baidu_map_ak'] : 'ng0vVZSU3GLhmdhZKR5FZ2gD2oCNyhyG';

$pindex = max(1, intval($_GPC['page']));
$psize = 15;

$typeid = intval($_GPC['typeid']);
switch($_GPC['order']){
	default: $orderStr = 'displayorder DESC';break;
	case '1': $orderStr = 'total DESC';break;
	case '2': $orderStr = 'sendprice ASC';break;
	case '3': $orderStr = 'enabled DESC';break;
}
if(!empty($typeid)){$typeidStr = " AND typeid = '{$typeid}'";}else{$typeidStr = '';}
$shop = pdo_fetchall("SELECT * FROM ".tablename('fm453_vfoods_category')." WHERE uniacid = '{$_W['uniacid']}' AND psn = '0'".$typeidStr." ORDER BY ".$orderStr." LIMIT ".($pindex - 1) * $psize.','.$psize);

foreach($shop as &$row){
	//判断时效
	$isInTime = fmFunc_foods_checkTime($row['time1'],$row['time2'],$row['time3'],$row['time4']);
	if(!$isInTime){
		$row['isopen'] = 0;
	}else{
		$row['isopen'] = 1;
	}

	//计算距离
	$dist = fmFunc_lbs_getDistance($fansloc["loc_x"],$fansloc["loc_y"],$row['loc_x'],$row['loc_y']);
	if($dist >= 10){
		$dist = round($dist/10,1);$dist .= "千米";
	}else{
		$dist = round($dist*100,-1);$dist .= "米";
	}
	$row['dist'] = $dist;
}

$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('fm453_vfoods_category')." WHERE uniacid = '{$_W['uniacid']}' AND psn = '0'".$typeidStr." ORDER BY displayorder DESC");

$pager = pagination($total, $pindex, $psize, $url = '', $context = array('before' => 0, 'after' => 0, 'ajaxcallback' => ''));

include fmFunc_template_m($do.'/453');
