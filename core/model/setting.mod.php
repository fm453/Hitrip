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
 * @remark 模块配置模型
 */
defined('IN_IA') or exit('Access Denied');

//普通设置，保存入库（新增或更新,确保每个公众对应的项只有一个设置记录;）
//完成
function fmMod_setting_save($data, $setfor, $uniacid)
{
	global $_GPC,$_W,$_FM;
	$return=array();
	//数字化传入的数值，防恶意查询
	$uniacid=intval($uniacid);
	if (empty($uniacid)) {
		$return['result']=FALSE;
		$return['msg']='公号uniacid无效';
		return $return;
	}
	if(!empty($setfor)) {
		//传入了setfor时，保存的数据取出时都归属于setfor组内
		$record = array();
		$data=iunserializer($data);
		if (!is_array($data)) {
			$return['result']=FALSE;
			$return['msg']='传入的数据格式非法';
			return $return;
		}

		$record['uniacid']=intval($uniacid);
		$record['setfor']=$setfor;
		$record['status']=$data['status'];
		$record['createtime']=time();
		$record['m']= FM_NAME;
		$return['result']=TRUE;
		$return['msg']='';
		if(!is_array($data['value'])){
			$return['result']=FALSE;
			$return['msg'] .= "未传入任何数据";
			return $return;
		}
		foreach($data['value'] as $key=>$kvalue){
			$record['title']=$key;
			$record['value']=$kvalue;
			$record['value'] = (is_array($kvalue)) ? iserializer($kvalue) : $kvalue;
			$sql='SELECT `id`FROM '.tablename('fm453_shopping_settings').' WHERE `setfor`=:setfor AND `uniacid` =:uniacid AND `title` =:title AND `m`=:m';
			$params=array();
			$params[':setfor']=$setfor;
			$params[':uniacid']=$uniacid;
			$params[':title']=$record['title'];
			$params['m']= FM_NAME;
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
		$record['m']= FM_NAME;
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
					$sql='SELECT `id` FROM '.tablename('fm453_shopping_settings').' WHERE `setfor`=:setfor AND `uniacid` =:uniacid AND `title` =:title AND `m`=:m';
					$params=array();
					$params[':setfor']=$setfor;
					$params[':uniacid']=$uniacid;
					$params[':title']=$record['title'];
					$params['m']= FM_NAME;
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
				$sql='SELECT `id` FROM '.tablename('fm453_shopping_settings').' WHERE `setfor`=:setfor AND `uniacid` =:uniacid AND `title` =:title AND `m`=:m';
				$params=array();
				$params[':setfor']=$setfor;
				$params[':uniacid']=$uniacid;
				$params[':title']=$record['title'];
				$params['m']= FM_NAME;
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

//模块全局设置，保存入库（新增或更新,确保每个项只有一个设置记录;）
//完成
function fmMod_setting_save_sys($data, $setfor)
{
	global $_GPC,$_W,$_FM;
	$return=array();
	$uniacid=0;
	if (empty($setfor)) {
		$return['result']=FALSE;
		$return['msg']='未传入所需设置项setfor';
		return $return;
	}
	$record = array();
	$data=iunserializer($data);
	if (!is_array($data)) {
		$return['result']=FALSE;
		$return['msg']='传入的数据格式非法';
		return $return;
	}
	$record['uniacid']=intval($uniacid);
	$record['setfor']=$setfor;
	$record['status']=$data['status'];
	$record['createtime']=time();
	$record['m']= FM_NAME;
	$return['result']=TRUE;
	$return['msg']='';
	if(!is_array($data['value'])){
		$return['result']=FALSE;
		$return['msg'] .= "未传入任何数据";
		return $return;
	}
	foreach($data['value'] as $key=>$kvalue){
		$record['title']=$key;
		$record['value']=$kvalue;
		$record['value'] = (is_array($kvalue)) ? iserializer($kvalue) : $kvalue;
		$sql='SELECT `id`FROM '.tablename('fm453_shopping_settings').' WHERE `setfor`=:setfor AND `uniacid` =:uniacid AND `title` =:title AND m =:m';
		$params=array();
		$params[':setfor']=$setfor;
		$params[':uniacid']=$uniacid;
		$params[':title']=$record['title'];
		$params[':m']=FM_NAME;
		$isexist = pdo_fetch($sql,$params);
		if ($isexist) {
			$result = pdo_update('fm453_shopping_settings', $record, array('id'=>$isexist));
			if(!$result) {
				$return['result']=FALSE;
				$return['msg'] .= $setfor." --".$key." 保存失败";
			}
		} else{
			$result = pdo_insert('fm453_shopping_settings', $record);
			if(!$result) {
				$return['result']=FALSE;
				$return['msg'] .= $setfor." --".$key." 保存失败";
			}
		}
	}
	return $return;
}

//获取模块的全局设置(uniacid=0),返回数据为数组格式
//完成
function fmMod_setting_query_sys($useCache=NULL)
{
	global $_GPC,$_W,$_FM;
	$cache_key = md5("fmMod_setting_query_sys");
	if($useCache){
		$cache = cache_load($cache_key);
		if($cache){
			$return=$cache['data'];
			return $return;
		}
	}
	$params =array();
	$return =array();

	$fields='`id`,`uniacid`,`setfor`,`title`,`value`,`status`,`createtime`';	//要查询的字段范围
	$tablename='fm453_shopping_settings';
	$uniacid=0;
	$sql='SELECT '.$fields.' FROM '.tablename($tablename);
	$condition = ' WHERE ';
	$condition .= '`uniacid`=:uniacid';
	$params[':uniacid']=$uniacid;
	$condition .= ' AND ';
	$condition .= '`m`=:m';
	$params[':m']= FM_NAME;
	$sql =$sql.$condition;

	$settings= pdo_fetchall($sql,$params,'title');
	if(is_array($settings)){
		if(empty($settings)){
			$return['result']=FALSE;
			$return['msg'] ='未获取到数据';
			$return['data']='';
		}else{
			$result=array();
			foreach($settings as $key=>&$setting){
				$setting['value'] = (is_serialized($setting['value'])) ? iunserializer($setting['value']) : $setting['value'];	//如果被序列化，需要先还原
				$result[$setting['setfor']][$key]=$setting['value'];
			}
			$return['result']=TRUE;
			$return['msg'] ='';
			$return['data']=$result;
		}
	}else{
		$return['result']=FALSE;
		$return['msg'] ='数据格式非法';
		$return['data']='';
	}
	$cache = array('res'=>true,'data'=>$return);
	cache_write($cache_key,$cache);
	return $return;
}

//获取指定公号设置清单(uniacid, status),该方法不可获取模块全局配置
//完成
function fmMod_setting_query_list($uniacid,$status,$useCache=NULL)
{
	global $_GPC,$_W,$_FM;
	//数字化传入的数值，防恶意查询
	$uniacid=intval($uniacid);
	$status=intval($status);
	if(empty($uniacid)){
		$uniacid=$_W['uniacid'];
	}

	$cache_key = md5('fmMod_setting_query_list_'.$uniacid.'_'.$status);
	if($useCache){
		$cache = cache_load($cache_key);
		if($cache){
			$return=$cache['data'];
			return $return;
		}
	}
	$params =array();
	$return =array();
	$fields='`id`,`uniacid`,`setfor`,`title`,`value`,`status`,`createtime`';	//要查询的字段范围
	$tablename='fm453_shopping_settings';

	$sql='SELECT '.$fields.'FROM'.tablename($tablename);
	$condition = ' WHERE ';
	$condition .= '`uniacid`=:uniacid';
	$params[':uniacid']=$uniacid;
	$condition .= ' AND ';
	$condition .= '`m`=:m';
	$params[':m']= FM_NAME;
	$condition .=' AND ';
	$condition .= '`status`=:status';
	$params[':status']=$status;
	$sql =$sql.$condition;
	$settings= pdo_fetchall($sql,$params);

	if(is_array($settings)){
		if(empty($settings)){
			$return['result']=FALSE;
			$return['msg'] ='未获取到数据';
			$return['data']='';
		}else{
			$result=array();
			foreach($settings as $key=>&$setting){
				$setting['value'] = (is_serialized($setting['value'])) ? iunserializer($setting['value']) : $setting['value'];	//如果被序列化，需要先还原
				$setfor=$setting['setfor'];
				$title=$setting['title'];
				if($title !=$setfor){
					$result[$setfor][$title]=$setting['value'];
				}else{
					$result[$title]=$setting['value'];
				}
			}
			$return['result']=TRUE;
			$return['msg'] ='';
			$return['data']=$result;
		}
	}else{
		$return['result']=FALSE;
		$return['msg'] ='数据格式非法';
		$return['data']='';
	}
	$cache = array('res'=>true,'data'=>$return);
	cache_write($cache_key,$cache);
	return $return;
}

//获取指定公号指定类设置清单(uniacid，setfor, status)，可查询到未生效的设置项
//完成
function fmMod_setting_query_setfor($uniacid=NULL,$setfor,$status=NULL,$useCache=NULL)
{
	global $_GPC,$_W,$_FM;
	$return=array();
	if (empty($setfor)) {
		$return['result']=FALSE;
		$return['msg']='未传入所需设置项setfor';
		return $return;
	}
	//数字化传入的数值，防恶意查询
	$uniacid=intval($uniacid);
	$status=intval($status);
	if(empty($uniacid)){
		$uniacid=$_W['uniacid'];
	}

	$cache_key = md5('fmMod_setting_query_setfor_'.$uniacid.'_'.$status.'_'.$setfor);
	if($useCache){
		$cache = cache_load($cache_key);
		if($cache){
			$return=$cache['data'];
			return $return;
		}
	}

	$params =array();
	$fields='`id`,`uniacid`,`setfor`,`title`,`value`,`status`,`createtime`';	//要查询的字段范围
	$tablename='fm453_shopping_settings';

	$sql='SELECT '.$fields.' FROM '.tablename($tablename);
	$condition = ' WHERE ';
	$condition .= '`uniacid`= :uniacid';
	$params[':uniacid']=$uniacid;
	$condition .= ' AND ';
	$condition .= '`m`=:m';
	$params[':m']= FM_NAME;
	$condition .=' AND ';
	$condition .= '`setfor`= :setfor';
	$params[':setfor']=$setfor;
	$condition .=' AND ';
	$condition .= '`status`=:status';
	$params[':status']=$status;
	$sql =$sql.$condition;
	$settings= pdo_fetchall($sql,$params,'title');
	if(is_array($settings)){
		if(empty($settings)){
			$return['result']=FALSE;
			$return['msg'] ='未获取到数据';
			$return['data']='';
		}else{
			$result=array();
			foreach($settings as $key=>&$setting){
				$setting['value'] = (is_serialized($setting['value'])) ? iunserializer($setting['value']) : $setting['value'];	//如果被序列化，需要先还原
				$result[$key]=$setting['value'];
			}
			$return['result']=TRUE;
			$return['msg'] ='';
			$return['data']=$result;
		}
	}else{
		$return['result']=FALSE;
		$return['msg'] ='数据格式非法';
		$return['data']='';
	}
	$cache = array('res'=>true,'data'=>$return);
	cache_write($cache_key,$cache);
	return $return;
}

//获取公众号具体指定项设置清单(uniacid, setfor, title),如果结果有效，直接返回具体值
//完成
function fmMod_setting_query_title($uniacid=NULL,$setfor,$title,$useCache=NULL)
{
	global $_GPC,$_W,$_FM;
	$return=array();
	if (empty($setfor)) {
		$return['result']=FALSE;
		$return['msg']='未传入所需设置项setfor';
		return $return;
	}
	if (empty($title)) {
		$return['result']=FALSE;
		$return['msg']='未传入所需设置项title';
		return $return;
	}
	$params =array();
	$fields='`id`,`uniacid`,`setfor`,`title`,`value`,`status`,`createtime`';	//要查询的字段范围
	$tablename='fm453_shopping_settings';
	//数字化传入的数值，防恶意查询
	$uniacid=intval($uniacid);
	if(empty($uniacid)){
		$uniacid=$_W['uniacid'];
	}

	$cache_key = md5('fmMod_setting_query_title_'.$uniacid.'_'.$setfor.'_'.$title);
	if($useCache){
		$cache = cache_load($cache_key);
		if($cache){
			$return=$cache['data'];
			return $return;
		}
	}

	$sql='SELECT '.$fields.' FROM '.tablename($tablename);
	$condition = ' WHERE ';
	$condition .= '`uniacid`= :uniacid';
	$params[':uniacid']=$uniacid;
	$condition .= ' AND ';
	$condition .= '`m`=:m';
	$params[':m']= FM_NAME;
	$condition .=' AND ';
	$condition .= '`setfor`= :setfor';
	$params[':setfor']=$setfor;
	$condition .=' AND ';
	$condition .= '`title`=:title';
	$params[':title']=$title;
	$sql =$sql.$condition;
	$setting= pdo_fetch($sql,$params);
	if(empty($setting))
	{
		$return['result']=FALSE;
		$return['msg'] ='未获取到数据';
		$return['data']='';
	}elseif(is_array($settings)){
		$result=array();
		$setting['value'] = (is_serialized($setting['value'])) ? iunserializer($setting['value']) : $setting['value'];	//如果被序列化，需要先还原
		$result=$setting['value'];
		$return['result']=TRUE;
		$return['msg'] ='';
		$return['data']=$result;
	}else{
		$return['result']=FALSE;
		$return['msg'] ='数据格式非法';
		$return['data']='';
	}
	$cache = array('res'=>true,'data'=>$return);
	cache_write($cache_key,$cache);
	return $return;
}

//获取整理后的全部设置,包含系统设置及当前公号对应的设置
function fmMod_settings_all($useCache=NULL)
{
	global $_GPC,$_W,$_FM;
	$cache_key = md5('fmMod_settings_all_'.$_W['uniacid']);
	if($useCache){
		$cache = cache_load($cache_key);
		if($cache){
			$return=$cache['data'];
			return $return;
		}
	}

	$result=fmMod_setting_query_list($_W['uniacid'],'127');
	if($result['result']) {
		$settings=$result['data'];
	}
	$result=fmMod_setting_query_sys();
	if($result['result']) {
		$settings['shouquan']=$result['data']['shouquan'];
	}
	if(empty($settings['navsnum'])){
		$navsnum = 1;
		$settings['navmenus']=$settings['navmenus']['nav0'];
	}
	$cache = array('res'=>true,'data'=>$settings);
	cache_write($cache_key,$cache);
	return $settings;
}

//清除指定公号指定设置(uniacid，setfor，title),无法清除模块全局设置
//完成
function fmMod_setting_clear($uniacid,$setfor,$title)
{
	global $_GPC,$_W,$_FM;
	//数字化传入的数值，防恶意查询
	$uniacid=intval($uniacid);

	$return=array();
	if (empty($setfor) || empty($uniacid)) {
		$return['result']=FALSE;
		$return['msg'] ='未传入有效的setfor或公号uniacid';
		return $return;
	}
	$record=array();
	if(!empty($title)){
		//如果传入了具体设置项，则仅清除对应的一条记录
		$sql='SELECT `id` FROM '.tablename('fm453_shopping_settings').' WHERE `setfor`=:setfor AND `uniacid` =:uniacid AND `title` =:title AND `m` =:m';
		$params=array();
		$params[':setfor']=$setfor;
		$params[':uniacid']=$uniacid;
		$params[':title']=$title;
		$params['m']= FM_NAME;
		$isexist = pdo_fetch($sql,$params);
		if ($isexist) {
			//移除设置项的value
			$record['value']='';
			$result = pdo_update('fm453_shopping_settings', $record, array('id'=>$isexist));
			if($result) {
				$return['result']=TRUE;
				$return['msg'] =$setfor.'-'.$title.'清除设置成功';
				return $return;
			}else{
				$return['result']=FALSE;
				$return['msg'] =$setfor.'-'.$title.'清除设置失败';
				return $return;
			}
		} else{
			$return['result']=FALSE;
			$return['msg'] ='Nothing for '.$setfor.'-'.$title;
			return $return;
		}
	}else{
		//未传入具体设置项，则清除设置类下的全部项
		$sql='SELECT `id` FROM '.tablename('fm453_shopping_settings').' WHERE `setfor`=:setfor AND `uniacid` =:uniacid AND `m` =:m';
		$params=array();
		$params[':setfor']=$setfor;
		$params[':uniacid']=$uniacid;
		$params[':m'] = FM_NAME;
		$isexists = pdo_fetchall($sql,$params,'title');
		if ($isexists) {
			$return['result']=TRUE;
			$return['msg']='';
			foreach($isexists as $key=>$isexist){
				//移除设置项的value
				$record['value']='';
				$result=pdo_update('fm453_shopping_settings', $record, array('id'=>$isexist));
				if(!$result) {
					$return['result']=FALSE;
					$return['msg'] .=$setfor.'-'.$key.'清除设置失败';
				}
			}
			return $return;
		}else{
			$return['result']=FALSE;
			$return['msg'] ='Nothing for '.$setfor;
			return $result;
		}
	}
}

//物理删除指定公号指定设置(uniacid，setfor，title),系统级（uniacid=0）的配置参数不受影响
//完成
function fmMod_setting_delete($uniacid,$setfor,$title)
{
	global $_GPC,$_W,$_FM;
	$return=array();
	if (empty($setfor) || empty($uniacid)) {
		$return['result']=FALSE;
		$return['msg']='未传入所需设置项setfor或公号uniacid';
		return $return;
	}
	$uniacid=intval($uniacid);
	$params=array();
	$params['uniacid']=$uniacid;
	$params['setfor']=$setfor;
	$params['m'] = FM_NAME;
	if(!empty($title)){
		//如果传入了具体设置项，则仅清除对应的一条记录
		$params['title']=$title;
	}
	$result=pdo_delete('fm453_shopping_settings',$params);
	if($result) {
		$return['result']=TRUE;
		$return['msg']='已成功删除'.$setfor.'-'.$title.'的记录';
	}else{
		$return['result']=FALSE;
		$return['msg']='删除失败';
	}
	return $return;
}

//物理删除指定全局设置(setfor，title),系统级（uniacid=0）的配置参数不受影响
function fmMod_setting_delete_sys($setfor,$title)
{
	global $_GPC,$_W,$_FM;
	$return=array();
	if (empty($setfor)) {
		$return['result']=FALSE;
		$return['msg']='未传入所需设置项setfor';
		return $return;
	}
	$uniacid=0;
	$params=array();
	$params['uniacid']=$uniacid;
	$params['setfor']=$setfor;
	$params['m'] = FM_NAME;
	if(!empty($title)){
		//如果传入了具体设置项，则仅清除对应的一条记录
		$params['title']=$title;
	}
	$result=pdo_delete('fm453_shopping_settings',$params);
	if($result) {
		$return['result']=TRUE;
		$return['msg']='已成功删除'.$setfor.'-'.$title.'的记录';
	}else{
		$return['result']=FALSE;
		$return['msg']='删除失败';
	}
	return $return;
}

//修复模块在微擎中的各项入口
function fmMod_setting_entry_check()
{
	$entry_type = 'cover,profile,menu,home,mine';
	$entry_new['menu']=fmFunc_route_entry_get('menu');
	$entry_new['cover']=fmFunc_route_entry_get('cover');
	$sql = 'SELECT * FROM '.tablename('modules_bindings');
	$params=array();
	$condition = ' WHERE ';
	$condition .= 'module = :module ';
	$params[':module']= FM_NAME;
	$entries=pdo_fetchall($sql.$condition,$params);
	$entry_old=array();
	foreach($entries as $key => $entry){
		$eid=$entry['eid'];
		$entrytype=$entry['entry'];
		$entrydo=$entry['do'];
		if($entrytype=='cover'){
		//前台入口
			$entry_old['cover'][$eid]=$entry['do'];
			if(!empty($entry_new[$entrytype][$entrydo])){
				//旧菜单在新菜单列表中时，用更新的方式
				$data=array();
				$data['icon']=$entry_new[$entrytype][$entrydo]['icon'];
				$data['title']=$entry_new[$entrytype][$entrydo]['title'];
				pdo_update('modules_bindings',$data,array('eid'=>$eid));
				unset($entry_new[$entrytype][$entrydo]);//从新菜单列表中踢除该菜单
			}elseif($entrydo=='list'){
				$data=array();
				$data['do']='index';
				$data['icon']=$entry_new[$entrytype][$entrydo]['icon'];
				$data['title']=$entry_new[$entrytype][$entrydo]['title'];
				pdo_update('modules_bindings',$data,array('eid'=>$eid));
				unset($entry_new[$entrytype][$entrydo]);//从新菜单列表中踢除该菜单
			}elseif($entry['do']=='list2'){
				$data=array();
				$data['do']='search';
				$data['icon']=$entry_new[$entrytype][$entrydo]['icon'];
				$data['title']=$entry_new[$entrytype][$entrydo]['title'];
				pdo_update('modules_bindings',$data,array('eid'=>$eid));
				unset($entry_new[$entrytype][$entrydo]);//从新菜单列表中踢除该菜单
			}elseif($entry['do']=='award'){
				$data=array();
				$data['do']='vshop';
				$data['icon']=$entry_new[$entrytype][$entrydo]['icon'];
				$data['title']=$entry_new[$entrytype][$entrydo]['title'];
				pdo_update('modules_bindings',$data,array('eid'=>$eid));
				unset($entry_new[$entrytype][$entrydo]);//从新菜单列表中踢除该菜单
			}elseif($entry['do']=='fansindex'){
				$data=array();
				$data['do']='fenxiao';
				$data['icon']=$entry_new[$entrytype][$entrydo]['icon'];
				$data['title']=$entry_new[$entrytype][$entrydo]['title'];
				pdo_update('modules_bindings',$data,array('eid'=>$eid));
				unset($entry_new[$entrytype][$entrydo]);//从新菜单列表中踢除该菜单
			}else{
				//清除该旧菜单
				pdo_delete('modules_bindings',array('eid'=>$eid));
			}
		}
		else
		if($entry['entry']=='profile'){
			//个人主页入口
			$entry_old['profile'][$eid]=$entry['do'];
		}
		else
		if($entry['entry']=='home'){
			//微站入口
			$entry_old['home'][$eid]=$entry['do'];
		}
		else
		if($entry['entry']=='menu'){
			//业务功能菜单
			$entry_old['menu'][$eid]=$entry['do'];
			if(!empty($entry_new['menu'][$entry['do']])){
				//旧菜单在新菜单列表中时，用更新的方式
					$data=array();
					$data['icon']=$entry_new['menu'][$entry['do']]['icon'];
					$data['title']=$entry_new['menu'][$entry['do']]['title'];
					pdo_update('modules_bindings',$data,array('eid'=>$eid));
					unset($entry_new['menu'][$entry['do']]);//从新菜单列表中踢除该菜单
				}else{
					//清除该旧菜单
					pdo_delete('modules_bindings',array('eid'=>$eid));
				}
		}
		elseif($entry['entry']=='mine'){
			//自定义后台入口
			$entry_old['mine'][$eid]=$entry['do'];
		}
	}
	$entry_new['menu']=array_filter($entry_new['menu']);//去空
	$entry_new['cover']=array_filter($entry_new['cover']);//去空
	//插入新菜单
	foreach($entry_new['menu'] as $key=>$entry){
		$data=array();
		$data['module']=FM_NAME;
		$data['entry']='menu';
		$data['title']=$entry['title'];
		$data['do']=$key;
		$data['icon']=$entry['icon'];
		pdo_insert('modules_bindings',$data);
	}
	foreach($entry_new['cover'] as $key=>$entry){
		$data=array();
		$data['module']=FM_NAME;
		$data['entry']='cover';
		$data['title']=$entry['title'];
		$data['do']=$key;
		$data['icon']=$entry['icon'];
		pdo_insert('modules_bindings',$data);
	}
}

//更新或查询版本
function fmMod_setting_version($version=null)
{
	if(!$version){
		$version = pdo_fetchcolumn("SELECT `version` FROM " . tablename('modules') . " WHERE `name` = :name", array(':name' => FM_NAME));
		return $version;
	}else{
		pdo_update('modules',array('version'=>$version),array('name'=> FM_NAME));
		return;
	}
}
