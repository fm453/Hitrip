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
 * @remark	需求表单模型
 */
defined('IN_IA') or exit('Access Denied');

function fmMod_needs_query_orderby(){
	return $showorder=' displayorder DESC, createtime DESC ';//优先设定的排序规则
}

function fmMod_needs_tpls(){
	global $_W;
	$templates = array();	//需求表单类型汇总
	// 利用模板目录来自动加载各模型配置文件
	$files = glob(FM_TEMPLATE.'mobile/default/needs/detail/*/_config.php');	//需要PHP v>=4
	foreach($files as $file){
		$_config = require_once($file);
		$_key = str_replace(FM_TEMPLATE.'mobile/default/needs/detail/','',$file);
		$_key = str_replace('/_config.php','',$_key);
		$templates[$_key] = array();
		$templates[$_key] = $_config;
		if(isset($_config['show'])){
			if(!in_array($_W['uniacid'],$_config['show'])){
				unset($templates[$_key]);
			}
		}elseif(isset($_config['hide'])){
			if(in_array($_W['uniacid'],$_config['hide'])){
				unset($templates[$_key]);
			}
		}
	}

	return $templates;
}

function fmMod_needs_tpl(){
	$tpls = fmMod_needs_tpls();
	//当前可用需求表单类型
	$templates=array();
	foreach($tpls as $key=>$tpl){
		if($tpl['status']==1) {
			$templates[$key]=$tpl['title'];
		}
	}
	return $templates;
}

/*
	单个需求表单数据汇总的检查（从needs_data表中提取数据，对应到needs_order表；提高后期调取表单统计时的效率）
	@sn		某类表单下的某个数据序列的编码
	@data		表单内的数据
*/
function fmMod_needs_order_check($nid,$sn,$data,$openid){
	//header("Content-type:text/html;charset=utf-8");//加上网页字符集强制设置，避免中文传输乱码
	global $_GPC;
	global $_W;
	global $_FM;
	$order=pdo_fetch("SELECT * FROM ".tablename('fm453_shopping_needs_order')." WHERE ordersn = :ordersn ORDER BY id DESC",array(':ordersn'=>$sn));//取最近更新的一条数据
	$fields= fmFunc_tables_fields('needs_order','shopping');
	$orderdata=array();
		$orderdata['uniacid']=$_W['uniaccount']['uniacid'];
		$orderdata['nid']=$nid;
		$orderdata['from_user']=$openid;
		$orderdata['fromuid']=$data['setfor'];
		$orderdata['shareid']=$data['shareid'];
		$orderdata['ordersn']=$sn;
		$orderdata['price']=$data['price'];
		$orderdata['status']=$data['status'];
		$orderdata['paytype']=$data['paytype'];
		$orderdata['transid']=$data['transid'];
		$orderdata['remark']=$data['remark'];
		$orderdata['logs']=$data['logs'];
		$orderdata['createtime']=$sn;
		$orderdata['remark_kf']=$data['remark_kf'];
		$orderdata['reply']=$data['reply'];
		$orderdata['deleted']=$data['deleted'];
	if(!$order) {
		$result = pdo_insert('fm453_shopping_needs_order',$orderdata);
		$orderid=pdo_insertid();
	}else{
		pdo_update('fm453_shopping_needs_order',$orderdata,array('id'=>$order['id']));
		$orderid=$order['id'];
	}
	return $orderid;
}

/*
*	取表单参数
@ nid 表单id
@ nobind 是否拼接数据，默认拼接
*/
function fmMod_needs_params($nid,$nobind=null){
	header("Content-type:text/html;charset=utf-8");//加上网页字符集强制设置，避免中文传输乱码
	global $_GPC;
	global $_W;
	global $_FM;
	$needs_params=pdo_fetchall("SELECT * FROM ".tablename('fm453_shopping_needs_param')." WHERE nid=:nid ",array(':nid'=>$nid),'setfor');
	if($nobind) {
		return $needs_params;
	}
	$settings['needs']=array();
	if(is_array($needs_params) && !empty($needs_params)) {
		foreach($needs_params as $key=>$value){
			if($value['status']==1) {
				$settings['needs'][$key]=$value['value'];
			}
		}
	}
	$settings['needs']['guide_page_total'] = !empty($settings['needs']['guide_page_total']) ? $settings['needs']['guide_page_total'] : 1;	//引导屏数量（不包含首尾二屏）
	$settings['needs']['guide_page_1_status']=TRUE;
	return $settings['needs'];
}

//保存入库
function fmMod_needs_w_modify($data,$id) {
	header("Content-type:text/html;charset=utf-8");//加上网页字符集强制设置，避免中文传输乱码
	global $_GPC;
	global $_W;
	global $_FM;
	$id=intval($id);
	$return=array();
	if(!is_numeric($id)){
		$return['result']=FALSE;
		$return['msg']='表单ID非法，需是正整数';
		return $return;
	}
	if($id<=0){
		$return['result']=FALSE;
		$return['msg']='未传入表单ID';
		return $return;
	}
	if(!is_array($data)){
		$return['result']=FALSE;
		$return['msg']='传入数据非法，需是数组';
		return $return;
	}
	//$data['starttime']= strtotime($data['starttime']);
	//$data['endtime']= strtotime($data['endtime']);
	//$data['content']= htmlspecialchars($data['content']);
	$result = pdo_update('fm453_shopping_needs',$data,array('id'=>$id));	//留疑：content数据被html转义了，取出时需还原
	if($result) {
		$return['result']=TRUE;
		$return['msg']='';
		$return['data']=$result;
	}else{
		$return['result']=FALSE;
		$return['msg']='修改数据失败';
	}
	return $return;
}


/*
*	保存表单参数
@ nid 表单id
*/
function fmMod_needs_params_save($data,$nid){
	global $_GPC;
	global $_W;
	global $_FM;
	$params = array();
	$param = array();
	$table = 'fm453_shopping_needs_param';
	if(!is_array($data)){
		return;
	}
	if(intval($nid)<=0){
		return;
	}

	foreach($data as $key=>$v){
		//$v的数据结构： status , value
		$setfor = $key;
		$value = $v['value'];
		$status = $v['status'];
		$param = array('value'=>$value,'status'=>$status);
		//检测数据是否存在
			$hasThisKey=pdo_fetchcolumn("SELECT id FROM ".tablename($table)." WHERE `nid`= :nid  AND `setfor` = :setfor ORDER BY createtime ASC",array(':nid'=>$nid,':setfor'=>$setfor));
			if($hasThisKey) {
				pdo_update($table,$param,array('id'=>$hasThisKey));
			}else{
				$param['nid']=$nid;
				$param['setfor']=$setfor;
				$param['createtime']=$_W['timestamp'];
				pdo_insert($table,$param);
			}
	}
}

//前端保存基础数据入库
function fmMod_needs_m_modify($data,$id) {
	header("Content-type:text/html;charset=utf-8");//加上网页字符集强制设置，避免中文传输乱码
	global $_GPC;
	global $_W;
	global $_FM;
	$id=intval($id);
	$return=array();
	if(!is_numeric($id)){
		$return['result']=FALSE;
		$return['msg']='表单ID非法，需是正整数';
		return $return;
	}
	if($id<=0){
		$return['result']=FALSE;
		$return['msg']='未传入表单ID';
		return $return;
	}
	if(!is_array($data)){
		$return['result']=FALSE;
		$return['msg']='传入数据非法，需是数组';
		return $return;
	}
	//$data['content']= htmlspecialchars($data['content']);
	$result = pdo_update('fm453_shopping_needs',$data,array('id'=>$id));	//留疑：content数据被莫名地给html转义了，取出时需还原
	if($result) {
		$return['result']=TRUE;
		$return['msg']='';
		$return['data']=$result;
	}else{
		$return['result']=FALSE;
		$return['msg']='修改数据失败';
	}
	return $return;
}
