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
 * @remark 房产入口页
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;
fm_load()->fm_model('ad');
fm_load()->fm_model('category');
fm_load()->fm_func('share');//链接分享管理
fm_load()->fm_func('view'); 	//浏览量处理
fm_load()->fm_func('wechat');//微信定义管理

//是否关店歇业
fm_checkopen($settings['onoffs']);
//加载风格模板及资源路径
$appstyle = empty($settings['appstyle']) ? FM_APPSTYLE : $settings['appstyle'];
$appsrc =MODULE_URL.'template/mobile/'.$appstyle.'453/';
$htmlsrc =MODULE_URL.'template/mobile/'.$appstyle;
$fm453resource = FM_RESOURCE;
//入口判断
$do= $_GPC['do'];
$ac=$_GPC['ac'];
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'index';
//开始操作管理
$shopname=$settings['brands']['shopname'];
$shopname = !empty($shopname) ? $shopname : '嗨旅行商城';
$shopname = '海居梦';
$pagename = $shopname.'官方网站';
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

//幻灯片
$result_advs = fmMod_ad_adv_mine();
$advs = $result_advs['data'];

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
	//for($i=count($ads[1])-1;$i>=0;$i--){
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
	//}
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

//定义数据接口相关
require MODULE_ROOT.'/template/mobile/'.$appstyle.$do.'/_config.php';	//引用定义文件

//取楼盘列表
$getData['ac'] = 'house';
$getData['op'] = 'all';
	$postUrl = '/index.php?r=realty/get';
	$postData = array();
	$postData['s_sn'] = $s_sn;
	$result = fmFunc_api_push($postUrl,$postData,$getData,$isread=1);
	$list = array();

	$isSuccess = false;
	if($result){
		$list = $result;
		foreach($list as $k => &$v){
			$v['params']['location']['lat'] = !isset($v['params']['location']['lat']) ? '' : $v['params']['location']['lat'];
			$v['params']['location']['lng'] = !isset($v['params']['location']['lng']) ? '' : $v['params']['location']['lng'];
			$v['plataccount']['name'] = $accounts[$v['uniacid']];
		}
		$isSuccess = true;
	}

//推荐楼盘列表
$list_rec = array();
//热门楼盘列表
$list_hot = array();
$i=$j=0;
foreach($list as $key => $item){
	if(isset($item['params'])){
		$_tempParams = $item['params'];
		if(isset($_tempParams['rec']) && $_tempParams['rec']==1) {
			$item['params']['prices']['start'] = !isset($item['params']['prices']['start']) ? 5000 : $item['params']['prices']['start'];
			$item['params']['prices']['end'] = !isset($item['params']['prices']['end']) ? 13000 : $item['params']['prices']['end'];
			$item['params']['coverage']['start'] = !isset($item['params']['coverage']['start']) ? 45 : $item['params']['coverage']['start'];
			$item['params']['coverage']['end'] = !isset($item['params']['coverage']['end']) ? 130 : $item['params']['coverage']['end'];
			$item['params']['rec_thumb'] = !isset($item['params']['rec_thumb']) ? $appsrc.'img/rec_default_thumb.jpg' : $item['params']['rec_thumb'];

			$list_rec[] = $item;
			$i++;
		}
		if(isset($_tempParams['hot']) && $_tempParams['hot']==1) {
			$item['params']['thumb'] = !isset($item['params']['thumb']) ? $appsrc.'img/hot_default_thumb.jpg' : $item['params']['thumb'];

			$list_hot[] = $item;
			$j++;
		}
	}
}

if($i>0 && $i<6) {
	$k = count($list_rec);
	for($k;$k<6;$k++){
		$list_rec[$k] = $list_rec[0];
	}
}else{

}

if($j>0 && $j<3) {
	$k = count($list_hot);
	for($k;$k<3;$k++){
		$list_hot[$k] = $list_hot[0];
	}
}else{

}

//风格细化定义


//页面流量处理
	fmFunc_view();
	if(!empty($shareid)){
		fmFunc_share_click($lastid,$shareid,$currentid,$share_user);
	}

include $this->template($appstyle.$do.'/453');
