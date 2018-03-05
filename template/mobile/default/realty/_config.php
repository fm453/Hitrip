<?php
/**
 前台配置集
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;

$s_sn = 1491892570;	//所使用的房产模型编码
//页面与内容模型的对应关系
$allPageSn = array();
$allPageSn['aboutus'] = '1494382388_1494381706_1494381214';	//关于我们
$allPageSn['contactus'] = '1494476593_1494381706_1494381214';	//联系我们
$allPageSn['joinus'] = '1494382388_1494381706_1494381214';	//加入我们
$allPageSn['business'] = '1494476922_1494381706_1494381214';	//合作加盟
$allPageSn['service'] = '1494478459_1494381706_1494381214';	//服务说明
$allPageSn['aboutcity'] = array(
	'haikou'=>'1494479246_1494381706_1494381214',
	'qionghai'=>'1494479333_1494381706_1494381214',
	'boao'=>'1495440756_1494381706_1494381214'
);	//城市概况
$allPageSn['abouttraffic'] = array(
	'haikou'=>'1494479052_1494381706_1494381214',
	'qionghai'=>'1494478898_1494381706_1494381214',
	//'boao'=>'1495440756_1494381706_1494381214'
);	//交通概况

	$getData = array();
	$getData['platid'] = $oauthid;
	$getData['uniacid'] = $_W['uniacid'];
	$getData['shopid'] = 0;
	//取楼盘模型信息
	$getData['ac'] = 'model';
	$postUrl = '/index.php?r=realty/detail';
	$postData = array();
	$postData['sn'] = $s_sn;
	$result = fmFunc_api_push($postUrl,$postData,$getData);
	if($result) {
		$ModelData = $result;
	}

//取房产模型对应的引用内容页编码
$_allPages = isset($ModelData['params']['pages']) ? $ModelData['params']['pages'] : array();
$allPages = array();
foreach($_allPages as $v){
	if($v['status']==1){
		$allPages[$v['sn']] = $v;
	}
}

//获取并存储各页面的引用内容
fm_load()->fm_func('page');
$allPageVars = array();
foreach($allPageSn as $k => $v){
	$allPageVars[$k] = array();
	if(is_array($v)) {
		foreach($v as $kk => $vv){
			$allPageVars[$k][$kk] = fmFunc_page_detail($vv);
		}
	}else{
		$allPageVars[$k] = fmFunc_page_detail($v);
	}

}

//引入公用字段集
require FM_TEMPLATE.'mobile/'.$appstyle.$do.'/_fields.php';

//各单页面具体信息-与$ac对应
$allPageInfo = array();
$allPageInfo['search'] = array();
foreach($allareas as $k => $v){
	$allPageInfo['search'][$k] = $allPageVars['aboutcity'][$k]['data'];
}

//风格定义
$_W['style']['HomeLogo'] = isset($_W['style']['HomeLogo']) ? $_W['style']['HomeLogo'] : 'http://public.hiluker.com/favicon.ico';
$_W['style']['HomeLogo']  = $appsrc .'img/logo.png';