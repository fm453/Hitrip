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
 * @remark 详情页
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;

fm_load()->fm_model('ad');
fm_load()->fm_model('category');
fm_load()->fm_func('share');//链接分享管理
fm_load()->fm_func('view'); 	//浏览量处理

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
$shopname = !empty($shopname) ? $shopname :FM_NAME_CN;
$pagename = $shopname.'官方网站';
$pagename .='|'.$_W['account']['name'];
$pagetitle = "楼盘详情";

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
//var_dump($advs);

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
require FM_TEMPLATE.'mobile/'.$appstyle.$do.'/_config.php';	//引用定义文件
$f_sn = $_GPC['sn'];	//对应楼盘编码

	//取楼盘信息
	$getData['ac'] = 'house';
	$postUrl = '/index.php?r=realty/detail';
	$postData = array();
	$postData['sn'] = $f_sn;
	$postData['s_sn'] = $s_sn;

	$result = fmFunc_api_push($postUrl,$postData,$getData);
	if($result){
		$HouseData = $result;
		$pagename .= '|'.$HouseData['title'];
	}

	/*楼盘相册
	@virtual		样板间
	@scene		实景图
	@thumbs		效果图
	*/
	$listAlbum = array();
	$listAlbum = $HouseData['params']['album'];
	$albumTitle = array();
	$albumTitle['virtual']	=	"效果图";
	$albumTitle['scene']	=	"实景图";
	$albumTitle['thumbs']	=	"样板间";
	//$albumTitle['plan']	=	"规划图";
	unset($listAlbum['plan']);

	//取户型信息
	$getData['ac'] = 'room';
	$getData['op'] = 'all';
	$postUrl = '/index.php?r=realty/get';
	$postData = array();
	$postData['s_sn'] = $s_sn;
	$postData['f_sn'] = $f_sn;
	$result = fmFunc_api_push($postUrl,$postData,$getData);
	if($result){
		$RoomData = $result;
	}
	$listRoom = $RoomData;

//风格细化定义
$HouseData['params']['search']['prices'][0] = !isset($HouseData['params']['search']['prices']) ? '10000' : $HouseData['params']['search']['prices'][0];
$HouseData['params']['search']['prices'][0] = ($HouseData['params']['search']['prices'][0]=='more') ? '10000' : $HouseData['params']['search']['prices'][0];

//进一步的操作--TODO

fmFunc_view();
if(!empty($shareid)){
	fmFunc_share_click($lastid,$shareid,$currentid,$share_user);
}

include $this->template($appstyle.$do.'/453');
