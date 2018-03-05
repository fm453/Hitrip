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
 * @remark：通用型数据的处理方法
 */
defined('IN_IA') or exit('Access Denied');

//全局设定，数据类型
function fmFunc_data_types(){
	$datatype=array();
	$datatype['text']=array(
		'name'=>'文本',
		'status'=>'127',
	);
	$datatype['number']=array(
		'name'=>'数字',
		'status'=>'127',
	);
	$datatype['area']=array(
		'name'=>'文本域',
		'status'=>'127',
	);
	$datatype['datetime']=array(
		'name'=>'日期时间',
		'status'=>'127',
	);
	$datatype['img']=array(
		'name'=>'单张图片',
		'status'=>'127',
	);
	$datatype['imgs']=array(
		'name'=>'图片组',
		'status'=>'127',
	);
	$datatype['location']=array(
		'name'=>'地理位置',
		'status'=>'127',
	);
	return $datatype;
}

/*
数据格式化，以便入库
@table  目标数据表（数据表名——表类型前缀+类表名称，不含系统前缀，如 form_diy）
@type  目标数据表后缀（self,表本身；param,表关联参数；data,表关联数据; pre,预编辑数据; form,表关联表单(订单)）
@sn  对应目标数据编号（为空时则新建）
@setfor  目标数据对象（键值）
@data  要存入的数据（统一强制要求为字符串格式——先进行json_decode转码，然后iserializer序列化，转换为字符串格式）
@mchid  目标商户id（多商户模式下，为商户编号；0时为全局）
@platid  目标平台id（默认与uniacid一致；0时为全局）
@uniacid  目标公众号id（默认为当前uniacid；0时为全局）
@siteid  目标站点id（默认为当前站点$_W['setting']['site']	['key']；0时也为当前站点）
*/
function fmFunc_data_save($table, $type, $data) {
	global $_GPC;
	global $_W;
	global $_FM;
	$return=array();
	$tablename=trim($tablename);
	if(empty($tablename)){
		$return['result']=FALSE;
		$return['msg']='表名table无效';
		return $return;
	}
	//先检查数据是否数组，不是，则数据非法
	if (!is_array($data)){
		$return['result']=FALSE;
		$return['msg']='要格式化的数据必须为数组(array)！';
		return $return;
	}
	//解析重构数据
	$type=trim($data['type']);
	if(empty($type)){
		$return['result']=FALSE;
		$return['msg']='未设定存储目标数据的类型type';
		return $return;
	}
	$sn=intval($data['sn']);
	if($sn<0){
		$return['result']=FALSE;
		$return['msg']='存储数据sn非法';
		return $return;
	}
	$setfor=trim($data['setfor']);
	if(empty($setfor)){
		$return['result']=FALSE;
		$return['msg']='未设定存储目标对象setfor';
		return $return;
	}
	$mchid=intval($data['mchid']);
	if($mchid<0){
		$return['result']=FALSE;
		$return['msg']='商户mchid参数无效';
		return $return;
	}
	$uniacid=intval($data['uniacid']);
	if ($uniacid<0) {
		$return['result']=FALSE;
		$return['msg']='公号uniacid无效';
		return $return;
	}
	$platid =!empty($data['platid']) ? intval($data['platid']) : $uniacid;
	$siteid=$_W['setting']['site']['key'];	//限定只允许操作站点自身数据
	$tempdata = iserializer(json_encode($data['data']));
	fm_load()->fm_model('data');
	$result = fmMod_data_save($tablename, $type, $sn, $setfor, $tempdata, $mchid, $uniacid, $platid, $siteid);
	if($result['result']){
		$return['result']=TRUE;
		$return['msg']='表'.$table.'的数据'.$setfor.'保存成功！';
		return $return;
	}
}

