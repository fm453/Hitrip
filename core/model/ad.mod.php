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
 * @remark	广告管理模型
 */
defined('IN_IA') or exit('Access Denied');
fm_load()->fm_func('tables');
fm_load()->fm_func('status');

//获取当前公众号的幻灯片
function fmMod_ad_adv_mine($useCache=null){
	global $_GPC;
	global $_W;
	global $_FM;
	$return=array();
	$cache_key = md5("fmMod_ad_adv_mine_".$_W['uniacid']);
	if($useCache){
		$cache = cache_load($cache_key);
		if($cache){
			$return=$cache['data'];
			return $return;
		}
	}

	$advs = pdo_fetchall("select * from " . tablename('fm453_shopping_adv') . " where enabled=1 and uniacid= '{$_W['uniacid']}' order by displayorder desc ");
	if(is_array($advs)) {
		foreach ($advs as &$adv) {
			if ($adv['link'] == '#' || empty($adv['link'])) {
				$adv['link'] = "#";
			}
			elseif (substr($adv['link'], 0, 5) != 'http:') {
				$adv['link'] = "http://" . $adv['link'];
			}
			$adv['thumb'] = tomedia($adv['thumb']);
		}
		$return['result']=TRUE;
		$return['data']=$advs;
		$cache = array('res'=>true,'data'=>$return);
		cache_write($cache_key,$cache);
		return $return;
	}
	else{
		$return['result']=FALSE;
		$return['msg']='未获取到幻灯片';
		return $return;
	}
}

//广告位对应的页面列表
function fmMod_ad_pages(){
	$pages=array('index','search','special','shopcart','myorder','category','articlelist');
	return $pages;
}

//获取当前公众号的指定页面的广告
function fmMod_ads_mine($page=NULL){
	global $_GPC;
	global $_W;
	global $_FM;
	header("Content-type:text/html;charset=utf-8");//加上网页字符集强制设置，避免中文传输乱码
	$return=array();
	$pages = fmMod_ad_pages();
	//$page = (in_array($page,$pages) && !empty($page)) ? $page : '' ;
	$currenttime = $_W['timestamp'];
	$fields = '*';
	$params=array();
	$sql = "select ".$fields." from " . tablename('fm453_shopping_adboxes');
	$condition = ' WHERE ';
	$condition .= ' enabled = :enabled ';
	$params[':enabled'] = 1;
	$condition .= ' AND ';
	$condition .= ' uniacid = :uniacid ';
	$params[':uniacid'] = $_W['uniacid'];
	//$condition .= ' AND ';
	//$condition .= ' isrecommand = :isrecommand ';
	//$params[':isrecommand'] = 1;
	$condition .= ' AND ';
	$condition .= ' parentid > :parentid ';
	$params[':parentid'] = 0;	//只调用广告位子分类
	$condition .= ' AND ';
	$condition .= ' starttime < :starttime ';
	$params[':starttime'] = $currenttime;
	$condition .= ' AND ';
	$condition .= ' endtime > :endtime ';
	$params[':endtime'] = $currenttime;
	if(!empty($page)){
		$condition .= ' AND ';
		$condition .= ' forpage = :forpage ';
		$params[':forpage'] = $page;
	}
	$showorder = " ORDER BY displayorder DESC ";
	$limit = " LIMIT 0,3";
	$adboxes = pdo_fetchall($sql.$condition.$showorder.$limit,$params);	//为了保障运行效率，仅加载三个资源位,且排除了没有子分类的资源

	//分别加载广告
	$ads=array();
	$has_ads=0;
	if(empty($adboxes)){
		$return['result']=FALSE;
		$return['msg']='未获取到广告位';
		return $return;
	}

	foreach ($adboxes as $key => &$adbox) {
		$ads_key = pdo_fetchall("select * from " . tablename('fm453_shopping_ads') . " where enabled=1 and uniacid= '{$_W['uniacid']}' and ccate='{$adbox['id']}' ");//按当前分类ccate调用广告图
		foreach ($ads_key as &$adv) {
			if ($adv['link'] == '#' || empty($adv['link'])) {
				$adv['link'] = "";
			}
			elseif (substr($adv['link'], 0, 5) != 'http:') {
				$adv['link'] = "http://" . $adv['link'];
			}
			$adv['thumb'] = tomedia($adv['thumb']);
		}
		//改造广告位的最后一个节点(取其中最后一个有设置了图片的广告)
		for($i=count($ads_key)-1;$i>=0;$i--){
			if(!empty($ads_key[$i]['thumb'])) {
				$adbox['lastad']=$ads_key[$i];
			}else{
				//同时按规则清理无用广告（无图的）
				unset($ads_key[$i]);
			}
		}
		$has_ads += count($ads_key);
		$ads[]=$ads_key;
	}
	if($has_ads>0){
		$return['result']=TRUE;
		$return['data']=$ads;
		$return['adboxes']=$adboxes;
		return $return;
	}else{
		$return['result']=FALSE;
		$return['msg']='未获取到广告';
		return $return;
	}
}
//获取当前分类的Banner
function fmMod_ad_banner_mine($pcate=NULL,$ccate=NULL){
	global $_W,$_GPC;
	header("Content-type:text/html;charset=utf-8");//加上网页字符集强制设置，避免中文传输乱码
	$return=array();
	$fields = "*";
	$showorder = " ORDER BY displayorder DESC";
	$sql = " SELECT ".$fields." FROM ".tablename('fm453_shopping_ppt');
	$condition = " WHERE ";
	$params =array();
	$condition .= "uniacid = :uniacid";
	$params[':uniacid']=$_W['uniacid'];
	$condition .= " AND enabled = :enabled";
	$params[':enabled']=1;

	$pcate= (intval($_GPC['pcate']>0)) ? intval($_GPC['pcate']) : intval($pcate);
	if($pcate) {
		$condition .= " AND pcate = :pcate";
		$params[':pcate'] = $pcate;
	}
	$pcate= (intval($_GPC['ccate']>0)) ? intval($_GPC['ccate']) : intval($ccate);
	if($ccate) {
		$condition .= " AND ccate = :ccate";
		$params[':ccate'] = $ccate;
	}
	$banner=pdo_fetchall($sql.$condition.$showorder,$params);
	$advs = $banner;
	if(is_array($advs)) {
		foreach ($advs as &$adv) {
			if ($adv['link'] == '#' || empty($adv['link'])) {
				$adv['link'] = "#";
			}
			elseif (substr($adv['link'], 0, 5) != 'http:') {
				$adv['link'] = "http://" . $adv['link'];
			}
			$adv['thumb'] = tomedia($adv['thumb']);
		}
		$return['result']=TRUE;
		$return['data']=$advs;
		return $return;
	}
	else{
		$return['result']=FALSE;
		$return['msg']='未获取到BANNER';
		return $return;
	}
}
