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
 * @remark 微查询模型管理-模型设置；
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;

load()->func('tpl');
load()->model('account');//加载公众号函数
fm_load()->fm_model('category'); //分类管理模块
fm_load()->fm_func('tpl');
fm_load()->fm_func('pinyin');

//加载风格模板及资源路径

$fm453style = fmFunc_ui_shopstyle();
$fm453resource =FM_RESOURCE;

//入口判断
$routes=fmFunc_route_web();
$routes_do=fmFunc_route_web_do();
$do= $_GPC['do'];
$ac=$_GPC['ac'];
$all_ac=fmFunc_route_web_ac($do);
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$all_op=fmFunc_route_web_op_single($do,$ac);
if(!in_array($ac,$all_ac) || !in_array($operation,$all_op)){
	die(message('非法访问，请通过有效路径进入！'));
}

fmMod_privilege_adm();//检查用户授权

//开始操作管理
$shopname=$settings['brands']['shopname'];
$shopname = !empty($shopname) ? $shopname :FM_NAME_CN;
$_W['page']['title'] = $shopname.$routes[$do]['title'];
$direct_url=fm_wurl($do,$ac,$operation,'');
$pindex=max(1,intval($_GPC['page']));
$psize=(intval($_GPC['psize'])>10) ? intval($_GPC['psize']) : 10;//最少显示10条主数据

$uniacid=$_W['uniacid'];
$plattype=$settings['plattype'];
$platids = fmFunc_getPlatids();//获取平台模式关联的公众号商户ID参数
$oauthid=$platids['oauthid'];
$fendianids=$platids['fendianids'];
$supplydianids=$platids['supplydianids'];
$blackids=$platids['blackids'];

//平台模式处理
include_once FM_PUBLIC.'plat.php';
$supplydians=explode(',',$supplydianids);//字符串转数组
$supplydians=array_filter($supplydians);//数组去空

//开始处理
$isSearching = false;

	$getData = array();
	$getData['platid'] = $oauthid;
	$getData['uniacid'] = $_W['uniacid'];
	$getData['shopid'] = 0;
	$getData['ac'] = 'model';

if ($operation == 'display')
{
	$list = array();

	$getData['op'] = 'all';

	$postUrl = '/index.php?r=search/get';
	$postData = array();

	$postData['sql_limits'] = array(
		'start' => ($pindex-1)*$psize,
		'end' => $psize,
	);

	$result = fmFunc_api_push($postUrl,$postData,$getData);

	$list = array();

	$isSuccess = false;
	if($result){
		$list = $result;
		foreach($list as $k => &$v){
			$v['plataccount']['name'] = $accounts[$v['uniacid']];
		}
		$isSuccess = true;
	}

	$total =count($list);
	$pager = pagination($total, $pindex, $psize);

	if($total==0){
		if(!$isSearching){
			message('还没有相应的模型数据，请联系管理员或开发者！', '', 'error');
		}
	}

	include $this->template($fm453style.$do.'/453');
}
elseif ($operation == 'param')
{
	//编辑模型参数
	$s_sn = intval($_GPC['sn']);

	if($_W['ispost']) {
		//数据过滤与析构
		$postUrl = '/index.php?r=search/save';

		$postData = $_GPC['data'];

		//SN编码从后台取用
		$postData['sn'] = $s_sn;
		//数据规则
		$postData['status'] = isset($postData['status']) ? intval($postData['status']) : 1;

		$opp = $_GPC['opp'];
		$setfor = $_GPC['setfor'];
		$is_submit_withoutNotice = !empty($_GPC['submit_withoutNotice']) ? true : false;
		$is_submit_withSysNotice = !empty($_GPC['submit_withSysNotice']) ? true : false;
		$withNotice = ($is_submit_withSysNotice) ? true : false;

		$getData['op'] = 'param';

		$isSuccess = false;

		$result = fmFunc_api_push($postUrl,$postData,$getData);

		if($result){
			$s_sn = $result;
			$data = $postData;
			$isSuccess = true;
		}

		$isSuccess = ($isSuccess) ? 1 : 0;
		if($opp=='noajax'){
			if($isSuccess){
				message('数据已保存成功！',referer(), 'success');
			}else{
				message('数据保存失败！',referer(), 'success');
			}
		}
		die(json_encode($isSuccess));
	}else{
		$postUrl = '/index.php?r=search/detail';
		$postData = array();
		$postData['sn'] = $s_sn;

		$result = fmFunc_api_push($postUrl,$postData,$getData);
		if($result){
			$data = $result;
			$item = $data['params'];
		}

		include $this->template($fm453style.$do.'/453');
	}
}