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
 * @remark 楼盘模型管理-基础设置；
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;
load()->func('tpl');
load()->model('account');//加载公众号函数
fm_load()->fm_model('category'); //分类管理模块
fm_load()->fm_func('api');
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

	$postUrl = '/index.php?r=realty/get';
	$postData = array();
	$postData['sql_limits'] = array(
		'start' => ($pindex-1)*$psize,
		'end' => $psize,
	);

	$result = fmFunc_api_push($postUrl,$postData,$getData,$isread=0);

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
			//message('还没有相应的房产模型数据，现在跳转至新增链接！', fm_wurl($do,$ac,'add',array()), 'info');
		}
	}

	include $this->template($fm453style.$do.'/453');
}
elseif ($operation == 'add')
{
	//结果通知
	if (!$_W['isfounder']) {
		message('抱歉，只有站长才能进行此操作！',referer(), 'error');
	}
	//新增一个楼盘模型

	$data = array();

	$postUrl = '/index.php?r=realty/save';

	$postData = array();

	if(checksubmit()){
		$Data = $_GPC['data'];
		//数据过滤组
		$filters = ['title','keywords','displayorder','status','deleted'];
		foreach($filters as $k){
			$postData[$k] = $Data[$k];
		}
		//数据规则
		$postData['status'] = isset($Data['enabled']) ? intval($Data['enabled']) : 1;
		$postData['deleted'] = isset($Data['deleted']) ? intval($Data['deleted']) : 0;
		$postData['sn'] = 0;	//新增数据时，必须将SN指定为0

		$result = fmFunc_api_push($postUrl,$postData,$getData);
		$isSuccess = false;
		if($result){
			$s_sn = $result;
			$data = $postData;
			$data['sn'] = $s_sn;
			$isSuccess = true;
		}

		//结果通知
		if ($isSuccess) {
			message('添加房产模型成功！',fm_wurl($do,$ac,'modify',$data), 'success');
		}
	}

	include $this->template($fm453style.$do.'/453');
}
elseif ($operation == 'modify')
{
	//结果通知
	if (!$_W['isfounder']) {
		message('抱歉，只有站长才能进行此操作！',referer(), 'error');
	}
	//编辑一个楼盘模型

	$sn = $s_sn = $_GPC['sn'];

	$data = array();

	//数据过滤与析构
	$data = $_GPC;
	$data['enabled'] = (abs($data['status']))>0 ? 1 : 0;

	if($data['title']==''){
		$getData['ac'] = 'model';
		$postUrl = '/index.php?r=realty/detail';
		$postData = array();
		$postData['sn'] = $s_sn;

		$result = fmFunc_api_push($postUrl,$postData,$getData);
		//var_dump($result);
		$isSuccess = false;
		if($result){
			$data = $result;
			$data['enabled'] = (abs($data['status']))>0 ? 1 : 0;
			$isSuccess = true;
		}
	}

	$postUrl = '/index.php?r=realty/save';

	$postData = array();

	if(checksubmit()){
		$Data = $_GPC['data'];
		//数据过滤组
		$filters = ['title','keywords','displayorder','status','deleted'];
		foreach($filters as $k){
			$postData[$k] = $Data[$k];
		}
		//SN编码从后台取用
		$postData['sn'] = $s_sn;
		//数据规则
		$postData['status'] = isset($Data['enabled']) ? intval($Data['enabled']) : 1;
		$postData['deleted'] = isset($Data['deleted']) ? intval($Data['deleted']) : 0;

		$result = fmFunc_api_push($postUrl,$postData,$getData);
		$isSuccess = false;
		if($result){
			$s_sn = $result;
			$data = $postData;
			$isSuccess = true;
		}

		//结果通知
		if ($isSuccess) {
			message('编辑房产模型成功！',fm_wurl($do,$ac,'modify',$data), 'success');
		}
	}

	include $this->template($fm453style.$do.'/453');
}
elseif ($operation == 'copy')
{
	//复制一个楼盘模型

	$s_sn = $_GPC['sn'];
	$data = array();

	//数据过滤与析构
	$data = $_GPC;
	$data['enabled'] = (abs($data['status']))>0 ? 1 : 0;

	if($data['title']==''){
		$getData['ac'] = 'model';
		$postUrl = '/index.php?r=realty/detail';
		$postData = array();
		$postData['sn'] = $s_sn;

		$result = fmFunc_api_push($postUrl,$postData,$getData);
		//var_dump($result);
		$isSuccess = false;
		if($result) {
			$data = $result;
			$data['enabled'] = (abs($data['status']))>0 ? 1 : 0;
			$isSuccess = true;
		}
	}

	$s_sn = 0;

	$postUrl = '/index.php?r=realty/save';

	$postData = array();

	if(checksubmit()){
		$Data = $_GPC['data'];
		//数据过滤组
		$filters = ['title','keywords','displayorder','status','deleted'];
		foreach($filters as $k){
			$postData[$k] = $Data[$k];
		}
		//SN编码从后台取用，设置为0时，接口服务端将新建数据
		$postData['sn'] = 0;
		//数据规则
		$postData['status'] = isset($Data['enabled']) ? intval($Data['enabled']) : 1;
		$postData['deleted'] = isset($Data['deleted']) ? intval($Data['deleted']) : 0;

		$result = fmFunc_api_push($postUrl,$postData,$getData);
		$isSuccess = false;

		if($result) {
			$data = $postData;
			$data['sn'] = $result;
			$isSuccess = true;
		}

		//结果通知
		if ($isSuccess) {
			message('复制房产模型成功！',fm_wurl($do,$ac,'modify',$data), 'success');
		}
	}

	include $this->template($fm453style.$do.'/453');
}
elseif ($operation == 'delete')
{
	//结果通知
	if (!$_W['isfounder']) {
		message('抱歉，只有站长才能进行此操作！',referer(), 'error');
	}
	//删除一个产品模型

	$s_sn = $_GPC['sn'];

	$postUrl = '/index.php?r=realty/save';

	$postData = array();
	$postData['sn'] = $s_sn;
	$postData['deleted'] = 1;

		$result = fmFunc_api_push($postUrl,$postData,$getData);
		$isSuccess = false;
		if($result) {
			$isSuccess = true;
		}

		//结果通知
		if ($isSuccess) {
			message('删除房产模型成功！',referer(), 'success');
		}
}
elseif ($operation == 'param')
{
	//编辑楼盘参数
	$s_sn = intval($_GPC['sn']);

	if($_W['ispost']) {
		//数据过滤与析构
		$postUrl = '/index.php?r=realty/save';

		$postData = $_GPC['data'];
		//SN编码从后台取用
		$postData['sn'] = $s_sn;
		//数据规则
		$postData['status'] = isset($postData['enabled']) ? intval($postData['enabled']) : 1;
		unset($postData['enabled']);

		$opp = $_GPC['opp'];
		$setfor = $_GPC['setfor'];
		$is_submit_withoutNotice = !empty($_GPC['submit_withoutNotice']) ? true : false;
		$is_submit_withSysNotice = !empty($_GPC['submit_withSysNotice']) ? true : false;
		$withNotice = ($is_submit_withSysNotice) ? true : false;

		$getData['op'] = 'param';

		$isSuccess = false;

		switch($setfor) {
			case 'areas':
				if(isset($postData[$setfor]['pcates'])) {
					$_tempData = [];
					$_tempKeys = [];
					$i = 0;
					foreach($postData[$setfor]['pcates'] as $k => $v){
						if(!isset($v['key'])){
							$v['key'] = fmFunc_pinyin_get($v['title']);
						}
						if(!empty($v['key'])){
							$i++;
							$j = intval($v['displayorder']).'_'.$i;
							$_tempData[$j] = $v;
							$_tempKeys[$i] =  intval($v['displayorder']);
						}
						unset($postData[$setfor]['pcates'][$k]);
					}
					arsort($_tempKeys);	//根据键值(displayorder的值)进行降序排列
					//var_dump($_tempKeys);
					$_data = [];
					foreach($_tempKeys as $k =>$v){
						$_data[] = $_tempData[$v.'_'.$k];
					}
					$_tempData = $_data;
					//var_dump($_tempData);

					if($i>0) {
						foreach($_tempData as $k => $v){
							$i--;
							$postData[$setfor]['pcates'][$i] = $v;
						}
					}else{
						$postData[$setfor]['pcates'] = $_tempData;
					}
				}

				if(isset($postData[$setfor]['ccates'])) {
					$_tempData = [];
					$_tempKeys = [];
					$i = 0;
					foreach($postData[$setfor]['ccates'] as $k => $v){
						if(!isset($v['key'])){
							$v['key'] = fmFunc_pinyin_get($v['title']);
						}
						if(!empty($v['key'])){
							$i++;
							$j = intval($v['displayorder']).'_'.$i;
							$_tempData[$j] = $v;
							$_tempKeys[$i] =  intval($v['displayorder']);
						}
						unset($postData[$setfor]['ccates'][$k]);
					}
					arsort($_tempKeys);	//根据键值(displayorder的值)进行降序排列
					//var_dump($_tempKeys);
					$_data = [];
					foreach($_tempKeys as $k =>$v){
						$_data[] = $_tempData[$v.'_'.$k];
					}
					$_tempData = $_data;
					//var_dump($_tempData);

					if($i>0) {
						foreach($_tempData as $k => $v){
							$i--;
							$postData[$setfor]['ccates'][$i] = $v;
						}
					}else{
						$postData[$setfor]['ccates'] = $_tempData;
					}
				}
			break;

			case 'pages':
			if(isset($postData[$setfor])) {
					$_tempData = [];
					$_tempKeys = [];
					$i = 0;
					foreach($postData[$setfor] as $k => $v){
						if(!isset($v['key'])){
							$v['key'] = fmFunc_pinyin_get($v['title']);
						}
						if(!empty($v['key'])){
							$i++;
							$_tempData[$i] = $v;
						}
						unset($postData[$setfor][$k]);
					}
					//var_dump($_tempData);

					if($i>0) {
						foreach($_tempData as $k => $v){
							$i--;
							$postData[$setfor][$v['key']] = $v;
						}
					}else{
						$postData[$setfor] = $_tempData;
					}
				}
			break;

			default:
			break;
		}

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
		$postUrl = '/index.php?r=realty/detail';
		$postData = array();
		$postData['sn'] = $s_sn;

		$result = fmFunc_api_push($postUrl,$postData,$getData);

		if($result){
			$data = $result;
			$data['enabled'] = (abs($data['status']))>0 ? 1 : 0;

			$item = $data['params'];
			//var_dump($item['areas']);
		}
		include $this->template($fm453style.$do.'/453');
	}
}