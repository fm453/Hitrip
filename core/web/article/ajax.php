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
 * @remark 文章管理；
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;

load()->func('tpl');
load()->model('account');//加载公众号函数
fm_load()->fm_model('category'); //分类管理模块

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
	return FALSE;
}

fmMod_privilege_adm();//检查用户授权

//开始操作管理
$shopname=$settings['brands']['shopname'];
$shopname = !empty($shopname) ? $shopname :FM_NAME_CN;
$_W['page']['title'] = $shopname.$routes[$do]['title'];
$direct_url=fm_wurl($do,$ac,$operation,'');

$id=intval($_GPC['id']);//ajax仅用于单条数据处理，依赖于id的传入
$from_ac=$_GPC['from_ac'];	//来路ac
$from_op=$_GPC['from_op'];	//来路operation
$delete=$_GPC['delete'];	//要处理的具体对象
$update=$_GPC['update'];	//要处理的具体对象

if($id <=0 ){
	return FALSE;
}
if($operation=='display'){
	die();
}
elseif ($operation == 'update') {
	if($from_ac=='recyle' && $from_op=='display' && $update=='article') {
		$sn=intval($_GPC['sn']);//将商品SN序号作为并行条件，避免前端错误传入商品ID
		$result=pdo_update('fm453_site_article',array('deleted'=>0),array('id' => $id,'sn'=>$sn), 'AND');
		if($result){
			return message('数据恢复成功！','referer','success');
		}else{
			die();
		}
	}else{
		$type = $_GPC['type'];
		$data = intval($_GPC['data']);
		if (in_array($type, array('isnew', 'ishot', 'isrecommand', 'isdiscount', 'status','type'))) {
			$data = ($data==1?'0':'1');
			pdo_update("fm453_site_article", array($type => $data), array("id" => $id));
			die(json_encode(array("result" => 1, "data" => $data)));
		}
	}
	die(json_encode(array("result" => 0)));
} elseif ($operation == 'delete') {
	if($from_ac=='list' && $from_op=='display' && $delete=='article') {
		$sn=intval($_GPC['sn']);//将商品SN序号作为并行条件，避免前端错误传入商品ID
		$result=pdo_update('fm453_site_article',array('deleted'=>1),array('id' => $id,'sn'=>$sn), 'AND');
		if($result){
			return message('数据删除成功！','referer','success');
		}else{
			die();
		}
	}
} elseif ($operation == 'clear') {

return $return;
}