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
 * @remark 微查询模型管理-内容管理；
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

$f_sn = intval($_GPC['f_sn']);
if($f_sn<=0){
	die(message('请先选择一个实例(f_sn)！'));
}

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

	$getData = array();
	$getData['platid'] = $oauthid;
	$getData['uniacid'] = $_W['uniacid'];
	$getData['shopid'] = 0;

		//先取模型信息
		$getData['ac'] = 'model';
		$postUrl = '/index.php?r=search/detail';
		$postData = array();
		$postData['sn'] = $s_sn;
		$result = fmFunc_api_push($postUrl,$postData,$getData);
		if($result) {
			$ModelData = $result;
		}
		//取实例信息
		$getData['ac'] = 'instance';
		$postUrl = '/index.php?r=search/detail';
		$postData = array();
		$postData['sn'] = $f_sn;
		$postData['s_sn'] = $s_sn;

		$result = fmFunc_api_push($postUrl,$postData,$getData);
		if($result){
			$InstanceData = $result;
		}

$category =$ModelData['params']['category'];

if (!empty($category)) {
	$pcates = $ccates = array();
	$pcates = $category['pcates'];
	$ccates = $category['ccates'];
	$parent = $children = array();
	foreach ($category['pcates'] as $cid => $cate) {
		if($cate['status']>0) {
			$pcates[] =  $cate;
			$parent[$cate['key']] = $cate['title'];
		}
	}
	if(!empty($parent)){
		foreach ($category['ccates'] as $cid => $cate) {
			if($cate['status']>0) {
				$ccates[] =  $cate;
			}
			if (!in_array($cate['pcate'],$pcates)) {
				$children[$cate['pcate']][$cate['key']] = $cate['title'];
			}
		}
	}
}
//var_dump($parent);
//var_dump($children);
$category = array();
if(!empty($parent)){
	foreach($parent as $k => $v){
		if(!empty($children[$k])){
			foreach($children[$k] as $kk => $vv){
				$category[$k.'+'.$kk] = $v.'|'.$vv;
			}
		}
	}
}
//var_dump($category);

//页面风格定义取出
$pagetpl = isset($InstanceData['params']['pagetpl']) ? $InstanceData['params']['pagetpl'] : 'default';
$managetpl = isset($InstanceData['params']['managetpl']) ? $InstanceData['params']['managetpl'] : 'default';
$pagestyle = isset($InstanceData['params']['pagestyle']) ? $InstanceData['params']['pagestyle'] : 'default';

$_temp_tpl = $fm453style.$do.'/_templates/'.$managetpl.'/web';
if(!file_exists(FM_PATH.'template/'.$_temp_tpl.'.html')) {
	$_temp_tpl = $fm453style.$do.'/_templates/default/web';
	if(!file_exists(FM_PATH.'template/'.$_temp_tpl.'.html')) {
		$_temp_tpl  = '';
	}
}
$managetpl = $_temp_tpl;
//var_dump($managetpl);

	$getData['ac'] = 'content';


if ($operation == 'display')
{
	$list = array();

	$getData['op'] = 'all';

	$postUrl = '/index.php?r=search/get';
	$postData = array();
	$postData['s_sn'] = $s_sn;
	$postData['f_sn'] = $f_sn;
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

	$isSuccess = false;
	if($result){
		$list = $result;
		foreach($list as $k => &$v){
			$v['plataccount']['name'] = $accounts[$v['uniacid']];
		}
		$isSuccess = true;
	}else{
		$list = array();
	}
	unset($v);

	$total =count($list);
	$pager = pagination($total, $pindex, $psize);

	if($total==0){
		if(!$isSearching){
			message('还没有相应的内容数据，现在跳转至新增链接！', fm_wurl($do,$ac,'add',array('s_sn'=>$s_sn,'f_sn'=>$f_sn)), 'info');
		}
	}

	include $this->template($fm453style.$do.'/453');
}
elseif ($operation == 'add')
{
	if(!$_W['ispost']) {
		$item = array();
		if(isset($InstanceData['params']['default']) && is_array($InstanceData['params']['default'])){
			foreach($InstanceData['params']['default'] as $k => $v){
				$item[$k] = $v;
			}
		}
		$interData = [
			'status'=>1,
			'deleted'=>0,
			'open'=>1,
		];

		foreach($interData as $k => $v){
			$item[$k] = $v;
		}
		include $this->template($fm453style.$do.'/453');
	}else{
		//新增一个内容
		$data = array();
		$getData['ac'] = 'content';
		$postData = array();
		$postUrl = '/index.php?r=search/save';

		$Data = $_GPC['data'];
		$postData = $_GPC['data'];

		//数据规则
		$postData['s_sn'] = $s_sn;
		$postData['f_sn'] = $f_sn;
		$postData['sn'] = 0;	//新增数据时，必须将SN指定为0

		$result = fmFunc_api_push($postUrl,$postData,$getData);
		$isSuccess = false;
		if($result){
			$o_sn = $result;
			$data = array();
			$data['s_sn'] = $s_sn;
			$data['f_sn'] = $f_sn;
			$data['sn'] = $o_sn;
			$isSuccess = true;
		}

		//结果通知
		if ($isSuccess) {
				message('添加内容成功！',fm_wurl($do,$ac,'modify',$data), 'success');
			}
	}
}
elseif ($operation == 'modify')
{
	//更新内容信息

	$o_sn = $sn = intval($_GPC['sn']);
	$data = array();

	if($_W['ispost']) {
		//页面变量存入前转换
	$vars = isset($InstanceData['params']['vars']) ? $InstanceData['params']['vars'] : '';
	$_Data = [];
	if(is_array($vars)) {
		foreach($vars as $k=>$v){
			if(isset($_POST[$k])) {
				$_Data[$k] = $_POST[$k];
			}
		}
	}

		//数据过滤与析构
		$postUrl = '/index.php?r=search/save';

		$Data = !empty($_GPC['data']) ? $_GPC['data'] : [];

		$opp = $_GPC['opp'];
		$setfor = $_GPC['setfor'];
		$is_submit_withoutNotice = !empty($_GPC['submit_withoutNotice']) ? true : false;
		$is_submit_withSysNotice = !empty($_GPC['submit_withSysNotice']) ? true : false;
		$withNotice = ($is_submit_withSysNotice) ? true : false;

		$postData = array();
		//数据规则
		$postData = array_merge($Data,$_Data);

		//富文本编辑器数据处理(gpc对应postdata的键)
		$textData = [];
		$Data = $textData;
		if(!empty($Data)){
			foreach($Data as $k => $v){
				if(isset($_GPC[$k])){
					$postData[$v] = htmlspecialchars_decode($_GPC[$k]);
				}
			}
		}

		//图片数据处理(gpc对应postdata的键)
		$picData = [];
		$Data = $picData;
		if(!empty($Data)){
			foreach($Data as $k => $v){
				if(isset($_GPC[$k])){
					$postData[$v] = tomedia($_GPC[$k]);	//转换成附件链接
				}
			}
		}

		//相册数据处理(gpc对应postdata的键)
		$albumData = [];
		$Data = $albumData;
		if(!empty($Data)){
			foreach($Data as $k => $v){
				if(isset($_GPC[$k])){
					$temp = array();
					$i = count($_GPC[$k]);
					foreach($_GPC[$k] as $kk){
						$i--;
						$temp[$i] = tomedia($kk);	//转换成附件链接(前加上'_'以便改为非数字键值)
					}
					$temp = array_reverse($temp,true);	//逆向排序并保留原各数组单元
					$postData[$v][$v] = $temp;		//转换成能被API服务器方法正确保存的格式
				}
			}
		}

		//日期数据处理
		$dateData = [];
		$Data = $dateData;
		if(!empty($Data)){
			foreach($Data as $k){
				if(isset($_GPC[$k])){
					$postData[$k] = strtotime($_GPC[$k]);
				}
			}
		}

		//整型数据处理
		$interData = ['status','deleted','open'];
		$Data = $interData;
		if(!empty($Data)){
			foreach($Data as $k){
				if(isset($postData[$k])){
					$postData[$k] = intval($postData[$k]);
				}
			}
		}

		//参数型数据重排-由用户动态增加的数据
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
			}
			$postData[$k] = $_temp;
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
		$postData['f_sn'] = $f_sn;
		$postData['sn'] = $o_sn;

		$getData['op'] = $opp;
		$isSuccess = false;

		$result = fmFunc_api_push($postUrl,$postData,$getData);

		if($result){
			$s_sn = $result;
			$data = $postData;
			$isSuccess = true;
		}

		$isSuccess = ($isSuccess) ? 1 : 0;
		if($opp=='noajax'){
			message('编辑成功！',referer(), 'success');
		}
		die(json_encode($isSuccess));
	}else{
		//取当前内容已有信息
		$getData['ac'] = 'content';
		$postUrl = '/index.php?r=search/detail';
		$postData = array();
		$postData['sn'] = $o_sn;
		$postData['f_sn'] = $f_sn;
		$postData['s_sn'] = $s_sn;

		$result = fmFunc_api_push($postUrl,$postData,$getData);
		if($result){
			$data = $result;
			$item = $data['params'];
		}

		//页面变量自定义取出
$vars = isset($InstanceData['params']['vars']) ? $InstanceData['params']['vars'] : '';

$html = [];
if(is_array($vars)) {
	foreach($vars as $k=>&$v){
		if($v['status']>0){
			switch($v['type']) {
				case 'txt':
					$html[$k] = '<input name="'.$k.'" id="vars_'.$k.'" class="form-control"  value="'.$item['vars'][$k].'" />';
				break;
				case 'textarea':
					$html[$k] = fmFunc_tpl_ueditor($k,$item['vars'][$k],'web','html',array('height'=>200,'width'=>900));
				break;
				case 'pic':
					$html[$k] = tpl_form_field_image($k, $item['vars'][$k]);
				break;
				case 'album':
					$html[$k] = tpl_form_field_multi_image($k, $item['vars'][$k]);
				break;
				case 'date':
					$html[$k] = tpl_form_field_date($k,$item['vars'][$k],false);
				break;
				case 'datetime':
					$html[$k] = tpl_form_field_date($k,$item['vars'][$k],true);
				break;
				case 'daterange':
					$html[$k] = tpl_form_field_daterange($k, $item['vars'][$k],true);
				break;
				case 'link':
					$html[$k] = tpl_form_field_link($k, $item['vars'][$k]);
				break;
				case 'icon':
					$html[$k] = tpl_form_field_icon($k, $item['vars'][$k]);
				break;
				case 'color':
					$html[$k] = tpl_form_field_color($k, $item['vars'][$k]);
				break;
				case 'lbs':
					$html[$k] = tpl_form_field_coordinate($k,$item['vars'][$k]);
				break;
				case 'emoji':
					$html[$k] = tpl_form_field_emoji($k,$item['vars'][$k]);
				break;
				case 'audio':
					$html[$k] = fmFunc_tpl_audio($k,$item['vars'][$k]);
				break;
				case 'video':
					$html[$k] = tpl_form_field_video($k,$item['vars'][$k]);
				break;
				default:
					$html[$k] = '<textarea name="'.$k.'" id="vars_'.$k.'" class="form-control"  />'.$item['vars'][$k].'</textarea>';
				break;
			}
		}else{
			unset($vars[$k]);
		}
	}
	unset($k);
	unset($v);
}
//var_dump($html);

	//添加复制功能标识
	if($_GET['copy']==1){
		$sn = $o_sn = 0;
		$iscopy = true;
	}

		include $this->template($fm453style.$do.'/453');
	}

}
elseif ($operation == 'copy')
{
	//复制一个内容

	$o_sn = $_GPC['sn'];
	$data = array();

	if(!$_W['ispost']) {
		//取当前内容已有信息
		$getData['ac'] = 'content';
		$postUrl = '/index.php?r=search/detail';
		$postData = array();
		$postData['sn'] = $o_sn;
		$postData['f_sn'] = $f_sn;
		$postData['s_sn'] = $s_sn;

		$result = fmFunc_api_push($postUrl,$postData,$getData);
		if($result){
			$data = $result;
			$item = $data['params'];
		}
		//重设$sn\f_sn
		$sn = $o_sn = 0;
		include $this->template($fm453style.$do.'/453');
	}else{
		//复制一个
		$data = array();
		$getData['ac'] = 'content';
		$postData = array();
		$postUrl = '/index.php?r=search/save';

		$Data = $_GPC['data'];
		//数据规则
		$postData = $Data;
		$postData['title'] = $Data['seo']['title'];
		$postData['value'] = $Data['seo']['keywords'];

		//富文本编辑器数据处理(gpc对应postdata的键)
		$textData = [];
		$Data = $textData;
		if(!empty($Data)){
			foreach($Data as $k => $v){
				if(isset($_GPC[$k])){
					$postData[$v] = htmlspecialchars_decode($_GPC[$k]);
				}
			}
		}

		$postData['s_sn'] = $s_sn;
		$postData['f_sn'] = $f_sn;
		$postData['sn'] = 0;	//新增数据时，必须将SN指定为0

		$result = fmFunc_api_push($postUrl,$postData,$getData);
		$isSuccess = false;
		if($result){
			$o_sn = $result;
			$data = array();
			$data['s_sn'] = $s_sn;
			$data['f_sn'] = $f_sn;
			$data['sn'] = $o_sn;
			$isSuccess = true;
		}

		//结果通知
		if ($isSuccess) {
			message('复制成功！',fm_wurl($do,$ac,'modify',$data), 'success');
		}
	}
}
elseif ($operation == 'delete')
{
	//结果通知
	$isForbidden = true;
	$isForbidden = false;
	if($isForbidden){
		message('抱歉，您不允许进行此操作！',referer(), 'error');
	}
	//删除一个内容

	$o_sn = $_GPC['sn'];

	$postUrl = '/index.php?r=search/save';

	$postData = array();
	$postData['sn'] = $o_sn;
	$postData['f_sn'] = $f_sn;
	$postData['s_sn'] = $s_sn;
	$postData['deleted'] = 1;

		$result = fmFunc_api_push($postUrl,$postData,$getData);
		$isSuccess = false;
		if($result) {
			$isSuccess = true;
		}

		//结果通知
		if ($isSuccess) {
			message('删除内容成功！',referer(), 'success');
		}
}