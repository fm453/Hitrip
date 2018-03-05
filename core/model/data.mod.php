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
 * @remark	数据存入取出模型
 */
defined('IN_IA') or exit('Access Denied');

function fmMod_data_query_orderby(){
	return $showOrder=' displayorder DESC, createtime DESC ';//优先设定的排序规则
}

/*
	数据保存方法
	@table  目标数据表（数据表名——表类型前缀+类表名称，不含系统前缀）
	@type  目标数据表后缀（self,表本身；param,表关联参数；data,表关联数据; pre,预编辑数据; form,表关联表单(订单)；addon,表附加数据，针对部分表初建时规划不完成的情况对self进行补充）
	@sn  对应目标数据编号（为空时则新建）
	@setfor  目标数据对象（键值）
	@data  要存入的数据（统一强制要求为字符串格式——先进行json_encode转码，然后iserializer序列化，转换为字符串格式）
	@mchid  目标商户id（多商户模式下，为商户编号；0时为全局）
	@platid  目标平台id（默认与uniacid一致；0时为全局）
	@uniacid  目标公众号id（默认为当前uniacid；0时为全局）
	@siteid  目标站点id（默认为当前站点$_W['setting']['site']	['key']；0时也为当前站点）
*/
function fmMod_data_save($table, $type, $sn, $setfor, $data, $mchid=null, $uniacid=null, $platid=null, $siteid=null) {
	global $_GPC;
	global $_W;
	global $_FM;
	$return=array();
	$table=trim($table);
	if(empty($table)){
		$return['result']=FALSE;
		$return['msg']='表名table无效';
		return $return;
	}
	$type=trim($type);
	$type = empty($type) ? 'self' : $type;
	$setfor=trim($setfor);
	if(empty($setfor)){
		$return['result']=FALSE;
		$return['msg']='未设定存储目标对象setfor';
		return $return;
	}
	$mchid=intval($mchid);
	if($mchid<0){
		$return['result']=FALSE;
		$return['msg']='商户mchid参数无效';
		return $return;
	}
	$uniacid=intval($uniacid);
	if ($uniacid<0) {
		$return['result']=FALSE;
		$return['msg']='公号uniacid无效';
		return $return;
	}
	$platid =!empty($platid) ? intval($platid) : $uniacid;
	$siteid=$_W['setting']['site']['key'];
	//检查数据
	if (is_array($data)){
		$return['result']=FALSE;
		$return['msg']='传入的数据格式非法(array);必须为序列化字符串';
		return $return;
	}

	//开始存入
	if($type=='self'){
		$record = array();
		$data=iunserializer($data);
		if (is_array($data)) {
			$return['result']=FALSE;
			$return['msg']='传入的数据格式非法';
			return $return;
		}
		$record['uniacid']=intval($uniacid);
		$record['setfor']=$setfor;
		$record['status']=$data['status'];
		$record['createtime']=time();
		$return['result']=TRUE;
		$return['msg']='';
		foreach($data['value'] as $key=>$kvalue){
			$record['title']=$key;
			$record['value']=$kvalue;
			$record['value'] = (is_array($kvalue)) ? iserializer($kvalue) : $kvalue;
			$sql='SELECT `id`FROM '.tablename('fm453_shopping_settings').' WHERE `setfor`=:setfor AND `uniacid` =:uniacid AND `title` =:title';
			$params=array();
			$params[':setfor']=$setfor;
			$params[':uniacid']=$uniacid;
			$params[':title']=$record['title'];
			$isexist = pdo_fetch($sql,$params);
			if ($isexist) {
				$result = pdo_update('fm453_shopping_settings', $record, array('id'=>$isexist));
				if(!$result) {
					$return['result']=FALSE;
					$return['msg'] .= $setfor." --".$key." 保存失败";
				}
			}else{
				$result = pdo_insert('fm453_shopping_settings', $record);
				if(!$result) {
					$return['result']=FALSE;
					$return['msg'] .= $setfor." --".$key." 保存失败";
				}
			}
		}
		return $return;
	}else{
		//未传入setfor时，根据传入的数据进行拆分,取出其中的setfor，适用于一次性传入多组setfor时
		$record = array();
		$data=iunserializer($data);
		if (!is_array($data)) {
			$return['result']=FALSE;
			$return['msg']='传入的数据格式非法';
			return $return;
		}
		$record['uniacid']=intval($uniacid);
		$record['status']=$data['status'];
		$record['createtime']=time();
		$return['result']=TRUE;
		$return['msg']='';
		foreach($data['value'] as $setfor=>$d){
			$record['setfor']=$setfor;
			//存留问题：传入的某一值为数组时，会出现逻辑混乱。——$settings['test']=$data['value'][]['test']=array(****),前端逻辑此test应是一个单项设置，经此处会被保存为一个设置类；——留待后观，不影响前台表现及业务处理——BY FM453 160827
			if(is_array($d)) {
				foreach($d as $key=>$kvalue){
					$record['title']=$key;
					$record['value']=$kvalue;
					$record['value'] = (is_array($kvalue)) ? iserializer($kvalue) : $kvalue;
					$sql='SELECT `id`FROM '.tablename('fm453_shopping_settings').' WHERE `setfor`=:setfor AND `uniacid` =:uniacid AND `title` =:title';
					$params=array();
					$params[':setfor']=$setfor;
					$params[':uniacid']=$uniacid;
					$params[':title']=$record['title'];
					$isexist = pdo_fetch($sql,$params);
					if ($isexist) {
						$result = pdo_update('fm453_shopping_settings', $record, array('id'=>$isexist));
						if(!$result) {
							$return['result']=FALSE;
							$return['msg'] .= $setfor." --".$key." 保存失败";
						}
					}else{
						$result = pdo_insert('fm453_shopping_settings', $record);
						if(!$result) {
							$return['result']=FALSE;
							$return['msg'] .= $setfor." --".$key." 保存失败";
						}
					}
				}
			}else{
				$record['title']=$setfor;
				$record['value']=$d;
				$sql='SELECT `id`FROM '.tablename('fm453_shopping_settings').' WHERE `setfor`=:setfor AND `uniacid` =:uniacid AND `title` =:title';
				$params=array();
				$params[':setfor']=$setfor;
				$params[':uniacid']=$uniacid;
				$params[':title']=$record['title'];
				$isexist = pdo_fetch($sql,$params);
				if ($isexist) {
					$result = pdo_update('fm453_shopping_settings', $record, array('id'=>$isexist));
					if(!$result) {
						$return['result']=FALSE;
						$return['msg'] .= $setfor." --".$setfor." 保存失败";
					}
				}else{
					$result = pdo_insert('fm453_shopping_settings', $record);
					if(!$result) {
						$return['result']=FALSE;
						$return['msg'] .= $setfor." --".$setfor." 保存失败";
					}
				}
			}
		}
		return $return;
	}
}
?>
