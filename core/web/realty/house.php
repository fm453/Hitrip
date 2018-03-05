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
 * @remark 楼盘模型管理-楼盘管理；
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

$s_sn = intval($_GPC['s_sn']);
if($s_sn<=0){
	  die(message('请先选择一个有效的楼盘模型(s_sn)！'));
}

//加载风格模板及资源路径

$fm453style = fmFunc_ui_shopstyle();
$fm453resource =FM_RESOURCE;
$appstyle = fmFunc_ui_appstyle();

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
$direct_url=fm_wurl($do,$ac,$operation,array('s_sn'=>$s_sn));
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

//可用搜索条件
//引入公用字段集
$fields = FM_TEMPLATE.'mobile/'.$appstyle.$do.'/_fields.php';
if(!file_exists($fields)) {
	$fields = FM_TEMPLATE.'mobile/'.'default'.$do.'/_fields.php';
}
require $fields;

	$getData = array();
	$getData['platid'] = $oauthid;
	$getData['uniacid'] = $_W['uniacid'];
	$getData['shopid'] = 0;
	//先取楼盘模型信息
		$getData['ac'] = 'model';
		$postUrl = '/index.php?r=realty/detail';
		$postData = array();
		$postData['sn'] = $s_sn;
		$result = fmFunc_api_push($postUrl,$postData,$getData);
		if($result) {
			$ModelData = $result;
		}

	$getData['ac'] = 'house';

if ($operation == 'display')
{
	$list = array();

	$getData['op'] = 'all';

	$postUrl = '/index.php?r=realty/get';
	$postData = array();
	$postData['s_sn'] = $s_sn;
	$postData['searching']['deleted'] = 1;
	if($_GPC['keyword']) {
		$postData['searching']['keyword'] = trim($_GPC['keyword']);
	}
	if($_GPC['status']) {
		$postData['searching']['status'] = $_GPC['status'];
	}
	if($_GPC['fromplats']) {
		$postData['searching']['plat'] = $_GPC['fromplats'];
	}
	$postData['sql_limits'] = array(
		'start' => ($pindex-1)*$psize,
		'end' => $psize,
	);

	$result = fmFunc_api_push($postUrl,$postData,$getData);

	$list = array();
    $total =0;
	$isSuccess = false;
	if($result){
		$list = $result;
		unset($list['total']);
		$total =$result['total'];
		foreach($list as $k => &$v){
			$v['plataccount']['name'] = $accounts[$v['uniacid']];
		}
		$isSuccess = true;
	}else{
		$list = array();
	}
	unset($v);

	$pager = pagination($total, $pindex, $psize);


	if($total==0){
		if(!$isSearching){
			message('还没有相应的楼盘数据，现在跳转至新增链接！', fm_wurl($do,$ac,'add',array('s_sn'=>$s_sn)), 'info');
		}
	}

	include $this->template($fm453style.$do.'/453');
}
elseif ($operation == 'add')
{
	if(!$_W['ispost']) {

		include $this->template($fm453style.$do.'/453');
	}else{
		//新增一个楼盘
		$data = array();
		$getData['ac'] = 'house';
		$postData = array();
		$postUrl = '/index.php?r=realty/save';

		$postData = array();

		$Data = $_GPC['data'];
		//数据过滤组
		$filters = array('title','keywords','displayorder','status','deleted');
		foreach($filters as $k){
			$postData[$k] = $Data[$k];
		}

		//数据规则
		$postData['value'] = isset($_GPC['value']) ? htmlspecialchars_decode($_GPC['value']) : '';
		$postData['opendate'] = isset($_GPC['opendate']) ? strtotime($_GPC['opendate']) : '';
		$postData['jiaofangdate'] = isset($_GPC['jiaofangdate']) ? strtotime($_GPC['jiaofangdate']) : '';
		$postData['status'] = isset($Data['status']) ? intval($Data['status']) : 1;
		$postData['deleted'] = isset($Data['deleted']) ? intval($Data['deleted']) : 0;
		$postData['open'] = isset($Data['open']) ? intval($Data['open']) : 0;
		$postData['jiaofang'] = isset($Data['jiaofang']) ? intval($Data['jiaofang']) : 0;
		$postData['s_sn'] = $s_sn;
		$postData['sn'] = 0;	//新增数据时，必须将SN指定为0

		$result = fmFunc_api_push($postUrl,$postData,$getData);
		$isSuccess = false;
		if($result){
			$f_sn = $result;
			$data = array();
			$data['s_sn'] = $s_sn;
			$data['sn'] = $f_sn;
			$isSuccess = true;
		}

		//结果通知
		if ($isSuccess) {
			message('添加楼盘成功！',fm_wurl($do,$ac,'modify',$data), 'success');
		}
	}
}
elseif ($operation == 'modify')
{
	//更新楼盘信息

	$f_sn = $sn = intval($_GPC['sn']);
	$data = array();

	if($_W['ispost']) {
		//数据过滤与析构
		$postUrl = '/index.php?r=realty/save';

		$Data = $_GPC['data'];

		$opp = $_GPC['opp'];
		$setfor = $_GPC['setfor'];
		$is_submit_withoutNotice = !empty($_GPC['submit_withoutNotice']) ? true : false;
		$is_submit_withSysNotice = !empty($_GPC['submit_withSysNotice']) ? true : false;
		$withNotice = ($is_submit_withSysNotice) ? true : false;

		$postData = array();
		//数据规则
		$postData = $Data;
		//富文本编辑器数据处理(gpc对应postdata的键)
		$textData = array('value'=>'value','developer_content'=>'content','property_content'=>'content','peripheral_traffic'=>'traffic','peripheral_trip'=>'trip','peripheral_main'=>'main');
		$Data = $textData;
		if(!empty($Data)){
			foreach($Data as $k => $v){
				if(isset($_GPC[$k])){
					$postData[$v] = htmlspecialchars_decode($_GPC[$k]);
				}
			}
		}

		//图片数据处理(gpc对应postdata的键)
		$picData = array('thumb'=>'thumb','rec_thumb'=>'rec_thumb');
		$Data = $picData;
		if(!empty($Data)){
			foreach($Data as $k => $v){
				if(isset($_GPC[$k])){
					$postData[$v] = tomedia($_GPC[$k]);	//转换成附件链接
				}
			}
		}

		//相册数据处理(gpc对应postdata的键)
		$albumData = array('thumbs'=>'thumbs','scene'=>'scene','virtual'=>'virtual','plan'=>'plan');
		$Data = $albumData;
		if(!empty($Data)){
			foreach($Data as $k => $v){
				if(isset($_GPC[$k])){
					$temp = array();
					$i = count($_GPC[$k]);
					foreach($_GPC[$k] as $kk){
						$i--;
						$temp[$i] = tomedia($kk);	//转换成附件链接
					}
					$temp = array_reverse($temp,true);	//逆向排序并保留原各数组单元
					$postData[$v][$v] = $temp;		//转换成能被API服务器方法正确保存的格式
				}
			}
		}

		//日期数据处理
		$dateData = array('opendate','jiaofangdate');
		$Data = $dateData;
		if(!empty($Data)){
			foreach($Data as $k){
				if(isset($_GPC[$k])){
					$postData[$k] = strtotime($_GPC[$k]);
				}
			}
		}

		//整型数据处理
		$interData = array('status','deleted','open','jiaofang','rec','hot');
		$Data = $interData;
		if(!empty($Data)){
			foreach($Data as $k){
				if(isset($postData[$k])){
					$postData[$k] = intval($postData[$k]);
				}
			}
		}

		//参数型数据重排
		$paramData = $_GPC['param'];
		if(!empty($paramData)){
			foreach($paramData as $k => $v){
				$_titles = $v['title'];
				$_status = $v['status'];
				$_temp = array();
				$i = count($_titles);
				foreach($_titles as $kkid => $vv){
					$i--;
					//通过规定$postData[$k][$i]键值$i来确保同前台传入的排序一致，每一次传入时都是先传入的数值排序在前
					$_temp[$i] = array('title'=>$vv,'status'=>$_status[$kkid]);
				}
				$postData[$k] = $_temp;
			}
		}

		//搜索条件数据处理(gpc对应postdata的键)
		$searchData = array('prices'=>'prices','features'=>'features','roomtypes'=>'roomtypes','housetypes'=>'housetypes');
		$Data = $searchData;
		if(!empty($Data)){
			foreach($Data as $k => $v){
				if(isset($_GPC[$k])){
					if(is_array($_GPC[$k])){
						$i=0;
						foreach($_GPC[$k] as $kk => $vv){
							$postData[$v][$i] = $vv;
							$i++;
						}
					}else{
						$postData[$v] = $_GPC[$k];
					}
					unset($_GPC[$k]);
				}
			}
		}

		//短新闻与时间轴数据处理(gpc对应postdata的键)
		$extraData = array('shortnews','events');
		$Data = $extraData;
		if(!empty($Data)){
			foreach($Data as $k => $v){
				if(isset($_GPC['data'][$v])){
					$postData[$v] = $_GPC['data'][$v];
				}
			}
		}

		//其他附加数据处理(gpc对应postdata的键)
		$extraData = array();
		$Data = $extraData;
		if(!empty($Data)){
			foreach($Data as $k => $v){
				if(isset($_GPC[$k])){
					$postData[$v] = $_GPC[$k];
				}
			}
		}

		if(!empty($setfor)){
			$_temp = array();
			foreach($postData as $k => $v){
				$_temp[$k] = $v;
			}
			unset($postData);
			$postData = array();
			$postData[$setfor] = $_temp;
		}

		//SN编码从后台取用
		$postData['s_sn'] = $s_sn;
		$postData['sn'] = $f_sn;

		$getData['op'] = $opp;
		$isSuccess = false;

		$result = fmFunc_api_push($postUrl,$postData,$getData);

		if($result){
			$f_sn = $result;
			$data = $postData;
			$isSuccess = true;
		}

		$isSuccess = ($isSuccess) ? 1 : 0;
		if($opp=='noajax'){
			if($isSuccess){
				message('编辑成功！',referer(), 'success');
			}else{
				message('保存失败！','', 'error');
			}
		}
		die(json_encode($isSuccess));
	}else{

		//取楼盘已有信息
		$getData['ac'] = 'house';
		$postUrl = '/index.php?r=realty/detail';
		$postData = array();
		$postData['sn'] = $f_sn;
		$postData['s_sn'] = $s_sn;

		$result = fmFunc_api_push($postUrl,$postData,$getData);
		if($result){
			$data = $result;
			$item = $data['params'];
		}

		$areas = $item['areas'];
		$searchs = $item['search'];

		include $this->template($fm453style.$do.'/453');
	}

}
elseif ($operation == 'copy')
{
	//复制一个楼盘

	$f_sn = $_GPC['sn'];
	$data = array();

	if(!$_W['ispost']) {

		//取楼盘已有信息
		$getData['ac'] = 'house';
		$postUrl = '/index.php?r=realty/detail';
		$postData = array();
		$postData['sn'] = $f_sn;
		$postData['s_sn'] = $s_sn;

		$result = fmFunc_api_push($postUrl,$postData,$getData);
		//var_dump($result);
		if($result){
			$data = $result;
			$item = $data['params'];
			//var_dump($item['peitao']['facilities']);
		}

		//重设$sn\f_sn
		$sn = $f_sn = 0;
		include $this->template($fm453style.$do.'/453');
	}else{
		//复制一个楼盘
		$data = array();
		$getData['ac'] = 'house';
		$postData = array();
		$postUrl = '/index.php?r=realty/save';

		$postData = array();

		$Data = $_GPC['data'];
		//数据过滤组
		$filters = ['title','keywords','displayorder','status','deleted'];
		foreach($filters as $k){
			$postData[$k] = $Data[$k];
		}

		//数据规则
		$postData['value'] = isset($_GPC['value']) ? htmlspecialchars_decode($_GPC['value']) : '';
		$postData['opendate'] = isset($_GPC['opendate']) ? strtotime($_GPC['opendate']) : '';
		$postData['jiaofangdate'] = isset($_GPC['jiaofangdate']) ? strtotime($_GPC['jiaofangdate']) : '';
		$postData['status'] = isset($Data['status']) ? intval($Data['status']) : 1;
		$postData['deleted'] = isset($Data['deleted']) ? intval($Data['deleted']) : 0;
		$postData['open'] = isset($Data['open']) ? intval($Data['open']) : 0;
		$postData['jiaofang'] = isset($Data['jiaofang']) ? intval($Data['jiaofang']) : 0;
		$postData['s_sn'] = $s_sn;
		$postData['sn'] = 0;	//新增数据时，必须将SN指定为0

		$result = fmFunc_api_push($postUrl,$postData,$getData);
		$isSuccess = false;
		if($result){
			$f_sn = $result;
			$data = array();
			$data['s_sn'] = $s_sn;
			$data['sn'] = $f_sn;
			$isSuccess = true;
		}

		//结果通知
		if ($isSuccess) {
			message('复制楼盘成功！',fm_wurl($do,$ac,'modify',$data), 'success');
		}
	}
}
elseif ($operation == 'delete')
{
	//结果通知
	$isForbidden = true;
	if ($_W['isfounder'] || $_W['username'] == $settings['maniuser']) {
		$isForbidden = false;
	}
	if($isForbidden){
		message('抱歉，只有超级管理员才能进行此操作！',referer(), 'error');
	}
	//删除一个楼盘

	$f_sn = $_GPC['sn'];

	$postUrl = '/index.php?r=realty/save';

	$postData = array();
	$postData['sn'] = $f_sn;
	$postData['s_sn'] = $s_sn;
	$postData['deleted'] = 1;

		$result = fmFunc_api_push($postUrl,$postData,$getData);
		$isSuccess = false;
		if($result) {
			$isSuccess = true;
		}

		//结果通知
		if ($isSuccess) {
			message('删除楼盘成功！',referer().'&nocache=1', 'success');
		}
}