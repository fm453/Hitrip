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
 * @remark 搜索页
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;

fm_load()->fm_model('category');
fm_load()->fm_model('ad');
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
$do=$_GPC['do'];
$ac=$_GPC['ac'];
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'index';
//开始操作管理
$shopname=$settings['brands']['shopname'];
$shopname = !empty($shopname) ? $shopname :FM_NAME_CN;
$pagename = '搜索页';

$uniacid=$_W['uniacid'];
$plattype=$settings['plattype'];
$platids= fmFunc_getPlatids();
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

$shareid = intval($_GPC['shareid']);
$lastid = intval($_GPC['lastid']);
$currentid = intval($_W['member']['uid']);
$fromplatid = intval($_GPC['fromplatid']);
$from_user = $_W['openid'];
$url_condition="";
$direct_url = fm_murl($do,$ac,'index',array());

//初始化一些数据
$allcategory = array();
$allchidren = array();
$allhouses = array();
$alltotal = array();
$allpager = array();
$allurl = array();

//开始整理搜索条件
$OnlyThisMp = ($_GPC['fromplats'] != $_W['uniacid']) ? FALSE : TRUE;
$allcategory = fmMod_category_get('article');
$allchidren = $allcategory['child'];
$nowtime = TIMESTAMP;
//分页设置
$pindex = max(1, intval($_GPC['page']));
$settings['search']['realty_list_num']=($settings['search']['realty_list_num']>0) ? $settings['search']['realty_list_num'] :5;
$psize = (intval($_GPC['psize'])>0) ? intval($_GPC['psize']) : $settings['search']['realty_list_num'];

//排序及截断(推荐>手动设置的排序数字>拼音首字母)
$showorder = " ORDER BY ";
$limit = " LIMIT ".($pindex - 1) * $psize . ',' . $psize;

	//动态调用Banner图
	$result_banners = fmMod_ad_banner_mine();
	$banners = $result_banners['data'];

//幻灯片
$result_advs = fmMod_ad_adv_mine();
$advs = $result_advs['data'];

$appbanner = (!$advs && !$banners) ? FALSE : TRUE;

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

$area = isset($_GPC['area']) ? $_GPC['area'] : 'boao' ;
$area_pcate = $area;
$area_pcate_name = $allareas[$area];
$area_ccates = $areas[$area_pcate];

//追加区域名到页面标题
$pagename .= '|'.$area_pcate_name;

//取页面关联文章
//取页面引用变量(由于部分键比较特殊，前台页面需要直接用echo打印出来)
$PageVars = ['pm2.5'=>15,'oxygen'=>2800,'year waterish degree'=>84,'year air temperature'=>20,'green degress'=>'37.8'];
switch($area) {
	case 'haikou':
		$PageData = $allPageInfo[$ac][$area];
		if($PageData) {
			$PageVars = array_merge($PageVars,$PageData['params']['vars']);
		}
	break;

	default:
	break;
}

//POST传入的数据
$_tempPost = $_POST;
$_search_areas = $_tempPost['areas'];	//区域搜索原始条件

//设置搜索条件
//从别的页面传入关键词时
$keywords = trim($_GPC['keywords']);
//POST关键词
$keyword = trim($_tempPost['keywords']);
if(!$keyword && $keywords){
	$keyword = $keywords;
}
$_GPC['fromplats'] = $_tempPost['fromplats'];
//设置筛选条件(采用字段参数过滤的方法)
$searching_filter = array();
$searching_filters = ['areas','prices','features','roomtypes','housetypes'];
foreach($searching_filters as $col){
	if(is_array($_tempPost[$col]) && !in_array('all',$_tempPost[$col])) {
		$searching_filter[$col] =  $_tempPost[$col];
	}
}

//取楼盘列表
$getData['ac'] = 'house';
$getData['op'] = 'all';
	$postUrl = '/index.php?r=realty/get';
	$postData = array();
	$postData['s_sn'] = $s_sn;
	if($keyword) {
		$postData['searching']['keyword'] = $keyword;
	}
	if($_GPC['status']) {
		$postData['searching']['status'] = $_GPC['status'];
	}
	if($_GPC['fromplats']) {
		$postData['searching']['plat'] = $_GPC['fromplats'];
	}
	if($searching_filter) {
		$postData['searching']['filter']	= $searching_filter;
	}
	$postData['sql_limits'] = array(
		'start' => ($pindex-1)*$psize,
		'end' => $psize,
	);
	//$postData['sql_limits'] = array();	//升级接口后取消掉

	$result = fmFunc_api_push($postUrl,$postData,$getData,0,1);
	$list = array();
    $total =0;
	$isSuccess = false;
	if($result){
		$list = $result;
		$total =$result['total'];
		unset($list['total']);
		foreach($list as $k => &$v){
			$v['params']['location']['lat'] = !isset($v['params']['location']['lat']) ? '' : $v['params']['location']['lat'];
			$v['params']['location']['lng'] = !isset($v['params']['location']['lng']) ? '' : $v['params']['location']['lng'];
			$v['plataccount']['name'] = $accounts[$v['uniacid']];
		}
		$isSuccess = true;
	}
	unset($v);

//用于异步加载的推荐列表
$rec_list = $list;

//临时办法-START
//先从服务器取全部数据，然后本地筛除
//API升级后再更换，更换同时需要恢复$postData['sql_limits']的使用限制
//设置筛选条件

foreach($list as $key => &$item){
	$searching_filter['areas'] = $_search_areas;
	if(isset($item['params'])){
		$_tempParams = $item['params'];

		//进行一级区域匹配
		$isIn = false;
		if($item['params']['area']['pcate'] ==$area){
			$isIn = true;
		}
		if(!$isIn){
			unset($list[$key]);
		}

		//传入了区域搜索条件时，进行区域筛选
		$isIn = false;
		if($searching_filter['areas']['ccate']){
			$_temp_areas_pcate = $_search_areas['pcate'];
			$_temp_areas_ccates = $areas[$_temp_areas_pcate];
			//将不在当前一级区域中的二级区域搜索条件剔除掉
			foreach($_search_areas['ccate'] as $k => $v){
				if(!isset($_temp_areas_ccates[$v])){
					unset($searching_filter['areas']['ccate'][$k]);
				}
			}

			//进行二级区域匹配
			if(in_array($item['params']['area']['ccate'],$searching_filter['areas']['ccate'])){
				$isIn = true;
			}
		}else{
			$isIn = true;
		}

		if(!$isIn){
			unset($list[$key]);
		}

		//根据剩下的搜索条件进行筛选
		$isIn = false;
		if(isset($item['params']['search'])) {
			unset($searching_filter['areas']);
			foreach($searching_filter as $col => $filter){
				//格式化有效的筛选条件
				foreach($filter as $k => $v){
					//楼盘设置了搜索项的对应条件时，进行比对筛选；否则无视该项筛选
					if(isset($item['params']['search'][$col])){
						if(in_array($v,$item['params']['search'][$col])){
							$isIn = true;
						}
					}else{
						$isIn = true;
					}
				}

				if(!$isIn){
					unset($list[$key]);
				}
			}
		}

		//对结果进行一些赋值
		if(!empty($list[$key])) {
			$item['params']['rec_thumb'] = !isset($item['params']['rec_thumb']) ? $appsrc.'img/list_default_thumb.jpg' : $item['params']['rec_thumb'];
		$item['params']['prices']['start'] = !isset($item['params']['prices']['start']) ? 5000 : $item['params']['prices']['start'];
			$item['params']['prices']['end'] = !isset($item['params']['prices']['end']) ? 13000 : $item['params']['prices']['end'];
			$item['params']['coverage']['start'] = !isset($item['params']['coverage']['start']) ? 45 : $item['params']['coverage']['start'];
			$item['params']['coverage']['end'] = !isset($item['params']['coverage']['end']) ? 130 : $item['params']['coverage']['end'];
			foreach($searching_filters as $col){
				unset($item['params']['searching'][$col]['all']);
				unset($item['params']['searching'][$col]['more']);
			}
		}
	}else{
		unset($list[$key]);
	}
}
unset($item);
//临时方法 END
	$pager = pagination($total, $pindex, $psize);
	$maxpages = fm_page($total , $psize);

//风格细化定义
$isShowAllAreas = true;	//在搜索页显示全部区域

//自定义页面默认的分享内容
$_share = array();

/* ————————不同请求类型的处理————————————————  */
if($operation=='index') {
	fmFunc_view();
	if(!empty($shareid)){
		fmFunc_share_click($lastid,$shareid,$currentid,$share_user);
	}
	include $this->template($appstyle.$do.'/453');
}
elseif($operation=='ajax'){
	include $this->template($appstyle.$do.'/453');
}
elseif($operation=='load') {
	if($_GPC['refresh']==1) {
		include $this->template($appstyle.$do.'/'.$ac.'/'.$operation.'/page');
	}else{
		include $this->template($appstyle.$do.'/453');
	}
}
elseif($operation=='loadmore'){
	include $this->template($appstyle.$do.'/'.$ac.'/'.$operation.'/page');
}
elseif($operation=='addsearch'){
	if($_GPC['has_search']==1) {
		include $this->template($appstyle.$do.'/453');
	}else{
		include $this->template($appstyle.$do.'/'.$ac.'/'.$operation.'/page');
	}
}
