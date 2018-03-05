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
 * @remark	聊天程序模型
 */
defined('IN_IA') or exit('Access Denied');

function fmMod_chat_query_orderby(){
	return $showOrder=' displayorder DESC, createtime DESC ';//优先设定的排序规则
}

function fmMod_chat_types(){
	//聊天表单类型汇总
	$types = array(
		'default'=>array('title'=>'默认(群聊)','status'=>1),
		'kefu'=>array('title'=>'客服沟通','status'=>1),
		'all'=>array('title'=>'全员聊','status'=>1),
		'm2m'=>array('title'=>'会员沟通','status'=>1),
		'me'=>array('title'=>'个人动态','status'=>1),
		're'=>array('title'=>'评论回复','status'=>1),
		'other'=>array('title'=>'其他','status'=>1)
	);
	return $types;
}

function fmMod_chat_type(){
	$tpls = fmMod_chat_types();
	//当前聊天聊天表单类型
	$templates=array();
	foreach($tpls as $key=>$tpl){
		if($tpl['status']==1) {
			$templates[$key]=$tpl['title'];
		}
	}
	return $templates;
}

/** ————聊天室主表数据(聊天室模型)处理——— **/

/*
	格式化self主表数据返回
	@data 要格式化的数据
	@type 数据类型(表本身self或参数param)
*/
function fmMod_chat_self_format($data,$type=null){
	global $_W,$_FM;
	$type = !empty($type) ? $type : 'self';
	if($type=='self'){
		$data['title']= htmlspecialchars($data['title']);
		$data['setfor']= htmlspecialchars($data['setfor']);
		$data['keywords']= htmlspecialchars($data['keywords']);
		$data['siteid'] = !empty($data['siteid']) ? $data['siteid'] : $_W['setting']['site']['key'];
		$data['platid']= intval($data['platid']);
		$data['uniacid']= intval($data['uniacid']);
		$data['shopid']= intval($data['shopid']);
		$data['partnerid']= intval($data['partnerid']);
		$data['uid']= intval($data['uid']);
		$data['mid']= intval($data['mid']);
		$data['fid']= intval($data['fid']);
		$data['pcate']= intval($data['pcate']);
		$data['ccate']= intval($data['ccate']);
		$data['displayorder']= intval($data['displayorder']);
		$data['status']= intval($data['status']);
		$data['statuscode']= intval($data['statuscode']);
		$data['deleted']= intval($data['deleted']);
		unset($data['updatetime']);
		unset($data['createtime']);
	}elseif($type=='param'){
		$data['id']= intval($data['id']);
		$data['sn']= intval($data['sn']);
		$data['title']= htmlspecialchars($data['title']);
		$data['setfor']= htmlspecialchars($data['setfor']);
		$data['key']= htmlspecialchars($data['key']);
		if(!($data['title'] && $data['setfor'] && $data['key'])){
			return FALSE;
		}
		$data['value']= htmlspecialchars($data['value']);
		$data['siteid'] = !empty($data['siteid']) ? $data['siteid'] : $_W['setting']['site']['key'];
		$data['platid']= intval($data['platid']);
		$data['uniacid']= intval($data['uniacid']);
		$data['shopid']= intval($data['shopid']);
		$data['partnerid']= intval($data['partnerid']);
		$data['uid']= intval($data['uid']);
		$data['mid']= intval($data['mid']);
		$data['fid']= intval($data['fid']);
		$data['pcate']= intval($data['pcate']);
		$data['ccate']= intval($data['ccate']);
		$data['displayorder']= intval($data['displayorder']);
		$data['status']= intval($data['status']);
		$data['statuscode']= intval($data['statuscode']);
		$data['deleted']= intval($data['deleted']);
		unset($data['updatetime']);
		unset($data['createtime']);

		$nowTable="hi_chat_basic_param";

		if(!$data['id']){
			$data['id'] = pdo_fetchcolumn(
			" SELECT id FROM ".tablename($nowTable)." WHERE sn = :sn AND siteid = :siteid AND uniacid = :uniacid AND shopid = :shopid AND partnerid = :partnerid ORDER BY createtime DESC",
			array(":sn"=>$data['sn'],":siteid"=>$data['siteid'],":uniacid"=>$data['uniacid'],":shopid"=>$data['shopid'],":partnerid"=>$data['partnerid'])
			);
			if(!$data['id']){
				pdo_insert($nowTable,$data);
				$data['id'] = pdo_insertid();
			}else{
				pdo_update($nowTable,$data,array("id"=>$data['id']));
			}
		}else{
			pdo_update($nowTable,$data,array("id"=>$data['id']));
		}
	}
	return $data;
}

/*
	管理聊天室模型（在主表self中创建/编辑数据）
	@id		模型的记录id
	@data		表单内的数据或需要写入表单内的数据(basic,params)
	返回影响的记录序号sn
*/
function fmMod_chat_self_modify($data,$id){
	global $_W,$_FM;
	$id=intval($id);
	$timestamp=$_W['timestamp'];
	$return=array();
	if(!is_numeric($id)){
		$return['result']=FALSE;
		$error = '表单ID非法，需是整数';
		$return['msg']=$error;
		trigger_error($error, E_USER_ERROR);
		return $return;
	}
	if($id<0)	{
		$return['result']=FALSE;
		$error = '未传入有效的表单ID';
		$return['msg']=$error;
		trigger_error($error, E_USER_ERROR);
		return $return;
	}
	if(!is_array($data)){
		$return['result']=FALSE;
		$error = '传入的数据非法，需是数组';
		$return['msg']=$error;
		trigger_error($error, E_USER_ERROR);
		return $return;
	}
	if(!is_array($data['basic'])){
		$return['result']=FALSE;
		$error ='传入的数据非法，未包含基本参数data[\'basic\']';
		$return['msg']=$error;
		trigger_error($error, E_USER_ERROR);
		return $return;
	}
	if($data['param']){
		if(!is_array($data['params'])){
			$return['result']=FALSE;
			$error ='传入的表单参数data[\'params\']数据非法，应为数组类型';
			$return['msg']=$error;
			trigger_error($error, E_USER_ERROR);
			return $return;
		}
	}
	//处理基础数据，存入self
	$basicData = fmMod_chat_self_format($data['basic'],'self');
	$nowTable = 'hi_chat_basic_self';
	if($id==0){
		$basicData['sn']=$timestamp;
		$sn = $basicData['sn'];
		$basicData['createtime']=$timestamp;
		$basicData['updatetime']=$timestamp;
		$result = pdo_insert($nowTable,$basicData);
		if($result){
			$id=pdo_insertid();
			$return['result']=TRUE;
			$return['msg'] =$nowTable.'数据插入成功'.'\r\n';
			$return['data']['id']=$id;
		}else{
			$return['result']=FALSE;
			$error =$nowTable.'数据插入失败'.'\r\n';
			$return['msg']=$error;
			trigger_error($error, E_USER_ERROR);
		}
	}else{
		$basicData['updatetime']=$timestamp;
		$result = pdo_update($nowTable,$data,array('id'=>$id));
		$sn = pdo_fetchcolumn("SELECT sn FROM ".tablename($nowTable)." WHERE id = :id",array(":id"=>$id));
		if($result){
			$return['result']=TRUE;
			$return['msg'] =$nowTable.'数据更新成功'.'\r\n';
			$return['data']['id']=$id;
		}else{
			$return['result']=FALSE;
			$error =$nowTable.'数据插入失败'.'\r\n';
			$return['msg'] .=$error;
			trigger_error($error, E_USER_ERROR);
		}
	}
	//处理参数数据，存入param
	$paramsData = $data['params'];
	$result = fmMod_chat_self_paramsModify($paramsData,$sn);

	return $return;
}

/*
	聊天室模型参数编辑入库
	@sn		聊天室模型编码
	@data		模型参数数据
*/
function fmMod_chat_self_paramsModify($data,$sn){
	global $_W,$_FM;
	$sn=intval($sn);
	$timestamp=$_W['timestamp'];
	$return=array();
	if(!is_numeric($sn)){
		$return['result']=FALSE;
		$error = '表单序号SN非法，需是整数';
		$return['msg']=$error;
		trigger_error($error, E_USER_ERROR);
		return $return;
	}
	if($sn<=0){
		$return['result']=FALSE;
		$error = '未传入有效的表单序号SN';
		$return['msg']=$error;
		trigger_error($error, E_USER_ERROR);
		return $return;
	}
	if(!is_array($data)){
		$return['result']=FALSE;
		$error = '传入的数据非法，需是数组';
		$return['msg']=$error;
		trigger_error($error, E_USER_ERROR);
		return $return;
	}
	 foreach($data as $key=>$paramData){
	 	if(is_array($paramData)){
	 		$paramData['sn'] = $sn;
			$return['msg'] .= fmMod_chat_self_format($paramData,'param');
		}
	}
	return $return;
}

/*
	取聊天室模型数据，返回模型信息(info)与参数(param)
	@sn		聊天室模型编码
	@platid	平台ID
	@shopid	商城ID
	@partnerid	商户ID
	参数权重partnerid	> shopid > platid > 0
*/
function fmMod_chat_self_query($sn,$platid=null,$shopid=null,$partnerid=null){
	global $_W,$_FM;
	$sn=intval($sn);
	$return=array();
	if(!is_numeric($sn)){
		$return['result']=FALSE;
		$error = '表单序号SN非法，需是整数';
		$return['msg']=$error;
		trigger_error($error, E_USER_ERROR);
		return $return;
	}
	if($sn<=0){
		$return['result']=FALSE;
		$error = '未传入有效的表单序号SN';
		$return['msg']=$error;
		trigger_error($error, E_USER_ERROR);
		return $return;
	}
	$platid = intval($platid);
	$shopid = intval($shopid);
	$partnerid = intval($partnerid);
	$nowTable = "hi_chat_basic_self";
	$sqlFields=" * ";
	$sql = "SELECT ".$sqlFields." FROM ".tablename($nowTable);
	$sqlCondition = " WHERE ";
	$sqlParas = array();
	$sqlCondition .= " `sn` = :sn ";
	$sqlParas[':sn'] = $sn;
	$sqlCondition .= " AND ";
	$sqlCondition .= " `siteid` = :siteid ";
	$sqlParas[':siteid'] = $_W['setting']['site']['key'];
	$sqlCondition .= " AND ";
	$sqlCondition .= " `uniaicid` = :uniacid ";
	$sqlParas[':uniacid'] = $_W['uniacid'];
	$chatModel['info'] = pdo_fetch($sql.$condition,$sqlParas);
	$chatModel['params'] = array();
	if($chatModel['info']) {
		$s_sn = $sn;
		$nowTable = "hi_chat_basic_param";
		$sqlFields=" * ";
		$sql = "SELECT ".$sqlFields." FROM ".tablename($nowTable);
		$sqlCondition = " WHERE ";
		$sqlParas = array();
		$sqlCondition .= " `s_sn` = :s_sn ";
		$sqlParas[':s_sn'] = $s_sn;
		$sqlCondition .= " AND ";
		$sqlCondition .= " `siteid` = :siteid ";
		$sqlParas[':siteid'] = $_W['setting']['site']['key'];
		$sqlCondition .= " AND ";
		$sqlCondition .= " `uniaicid` = :uniacid ";
		$sqlParas[':uniacid'] = $_W['uniacid'];
		$sqlCondition .= " AND ";
		$sqlCondition .= " `status` != :status ";
		$sqlParas[':status'] = 0;
		$sqlCondition .= " AND ";
		$sqlCondition .= " `deleted` = :deleted ";
		$sqlParas[':deleted'] = 0;
		$showOrder = " ORDER BY platid ASC , shopid ASC , parterid ASC ,displayorder DESC , id DESC ";	//platid\shopid\partnerid\必须正序,且顺序不可调换，以便下一步的同键值数据覆盖(比如同一个setfor, platid =0 和 platid=1 都有存值，则取的是platid=1的)
		$temp = pdo_fetchall($sql.$condition.$showOrder,$sqlParas);

		$params = array();
		if($temp) {
			foreach($temp as $i => $t){
				if($platid>0) {
					if($t['platid'] !=$platid || $t['platid'] !=0) {
						unset($temp[$i]);
					}
				}
				if($shopid>0) {
					if($t['shopid'] !=$shopid || $t['shopid'] !=0) {
						unset($temp[$i]);
					}
				}
				if($partnerid>0) {
					if($t['partnerid'] !=$partnerid || $t['partnerid'] !=0) {
						unset($temp[$i]);
					}
				}
			}
		}
		$temp_2=array();
		if($temp) {
			foreach($temp as $i => $t){
				$setfor=$t['setfor'];
				$title=$t['title'];
				$key=$t['key'];
				$value=$t['value'];
				if(!$title || $title == $key) {
					$temp_2[$setfor][$title][$key]=$value;
				}else{
					$temp_2[$setfor][$key]=$value;
				}
			}
		}
		$chatModel['params']=$temp_2;
	}

	cache_write('chat_self_'.$sn.'_'.$platid.'_'.$shopid.'_'.$partnerid,iserializer($chatModel));	//同时将记录集写入缓存
	return $chatModel;
}

/*——————聊天室实例处理——————————*/
/*
	实例列表
	@id		实例记录序号
	@sn		实例记录编码
	@mid		实例的会员创建者
	@status		实例状态
*/
function fmMod_chat_form_list($id=null,$sn=null,$mid=null,$status=null){
	global $_W,$_FM;
	$id = intval($id);
	$sn = intval($sn);
	$mid = intval($mid);
	$status = intval($status);
	$siteid = $_W['setting']['site']['key'];
	$nowTable = "hi_chat_basic_form";
	//$sqlFields=" `id`,`title`,`value`,`displayorder`,`createtime`,`updatetime` ";
	$sqlFields=" * ";
	$sql = "SELECT ".$sqlFields." FROM ".tablename($nowTable);
	$sqlCondition = " WHERE ";
	$sqlParas = array();
	if($id){
		$sqlCondition .= " `id` = :id ";
		$sqlParas[':id'] = $id;
		$sqlCondition .= " AND ";
	}elseif($sn){
		$sqlCondition .= " `sn` = :sn ";
		$sqlParas[':sn'] = $sn;
		$sqlCondition .= " AND ";
	}
	$sqlCondition .= " `siteid` = :siteid ";
	$sqlParas[':siteid'] = $siteid;
	$sqlCondition .= " AND ";
	$sqlCondition .= " `uniaicid` = :uniacid ";
	$sqlParas[':uniacid'] = $_W['uniacid'];
	if($mid){
		$sqlCondition .= " AND ";
		$sqlCondition .= " `mid` = :mid ";
		$sqlParas[':mid'] = $mid;
	}
	if($status) {
		$sqlCondition .= " AND ";
		$sqlCondition .= " `status` = :status ";
		$sqlParas[':status'] = $status;
	}
	$sqlCondition .= " AND ";
	$sqlCondition .= " `deleted` = :deleted ";
	$sqlParas[':deleted'] = 0;
	$showOrder = " ORDER BY id DESC";
	$chatForm = pdo_fetch($sql.$condition.$showOrder,$sqlParas);

	cache_write('chat_form_'.$id.'_'.$sn.'_'.$mid.'_'.$siteid,iserializer($chatForm));	//同时将记录集写入缓存
	return $chatForm;
}

/*——————聊天室订单表处理——————————*/
/**参数设置入库
* 是否应用到全局(参数生效范围:全局，或当前会员)
**/
function fmMod_chat_addon_modify($o_sn,$data) {
	global $_W,$_FM;
	$return=array();
	if(!is_array($data)){
		$return['result']=FALSE;
		$return['msg']='传入数据非法，需是数组';
		return $return;
	}
	$o_sn=intval($o_sn);
	if(!is_numeric($o_sn)){
		$return['result']=FALSE;
		$return['msg']='表单编码o_sn非法，需是正整数';
		return $return;
	}
	if($o_sn<=0){
		$return['result']=FALSE;
		$return['msg']='未传入表单编码o_sn';
		return $return;
	}
	//查询该o_sn表单的对应参数数据
	$order = fmMod_chat_order_query($o_sn);
	if(!$order) {
		$return['result']=FALSE;
		$return['msg']='未找到指定记录的聊天室表记录';
		return $return;
	}
	$nowTable = "hi_chat_basic_addon";
	$nowTime = $_W['timestamp'];
	$temp = array();
	$temp['sn'] = $nowTime;
	$temp['s_sn'] = $order['s_sn'];
	$temp['f_sn'] = $order['f_sn'];
	$temp['o_sn'] = $o_sn;
	$temp['siteid'] = $order['siteid'];
	$temp['platid'] = $order['platid'];
	$temp['uniacid'] = $_W['uniacid'];
	$temp['shopid'] = $order['shopid'];
	$temp['partnerid'] = $order['partnerid'];

	$temp['mid']=intval($data['mid']);	//对应会员
	$temp['fid']=intval($data['fid']);	//对应粉丝

	$temp['displayorder'] = intval($data['displayorder']);
	$temp['status'] = intval($data['status']);
	$temp['deleted'] = intval($data['deleted']);

	$temp['createtime'] = $nowTime;	//消息发出的时间
	$temp['updatetime'] = $nowTime;	//消息发出的时间
	$temp['statuscode'] = intval($data['statuscode']);

	//其他附加参数
	$isGlobal = $data['isGlobal'];	//是否应用到全局
	unset($data['isGlobal']);

	$exist_fields = array('id','sn','s_sn','f_sn','o_sn','siteid','platid','uniacid','shopid','partnerid','mid','fid','createtime','updatetime','statuscode');
	$data_paras = array('setfor','title','key','value','status','deleted','displayorder');

	//清除$data中的无用数据
	foreach($exist_fields as $key){
		if(isset($data[$key])){
			unset($data[$key]);
		}
	}

	foreach($data as $key=>$val){
		if(is_array($val)) {
			foreach($val as $k=>$v){
				if(is_array($v)) {
					foreach($v as $kk=>$vv){
						$temp['setfor'] = $key;	//数据归属对象
						$temp['title'] = $k;	//数据标题
						$temp['key'] = $kk;	//数据键值
						$temp['value'] = json_encode($vv);	//数据值
						if($isGlobal){
							$temp['id']= pdo_fetchcolumn(" SELECT id FROM ".tablename($nowTable)." WHERE o_sn = :o_sn AND uniacid = :uniacid AND siteid = :siteid AND setfor LIKE :setfor AND title LIKE :title AND key LIKE :key ",array(":o_sn"=>$o_sn,":uniacid"=>$uniacid,":siteid"=>$siteid,":setfor"=>$key,":title"=>$k,":key"=>$kk));
						}else{
							$temp['id']= pdo_fetchcolumn(" SELECT id FROM ".tablename($nowTable)." WHERE o_sn = :o_sn AND mid = :mid  AND uniacid = :uniacid AND siteid = :siteid AND setfor LIKE :setfor AND title LIKE :title AND key LIKE :key ",array(":o_sn"=>$o_sn,":mid"=>$_FM['member']['info']['uid'],":uniacid"=>$uniacid,":siteid"=>$siteid,":setfor"=>$key,":title"=>$k,":key"=>$kk));
						}
						if($temp['id']) {
							unset($temp['createtime']);
							pdo_update($nowTable,$temp,array('id'=>$temp['id']));
						}else{
							pdo_insert($nowTable,$temp);
						}
					}
				}else{
					$temp['setfor'] = $key;	//数据归属对象
					$temp['title'] = $k;	//数据标题
					$temp['key'] = $k;	//数据键值
					$temp['value'] = json_encode($v);	//数据值
					if($isGlobal){
						$temp['id']= pdo_fetchcolumn(" SELECT id FROM ".tablename($nowTable)." WHERE o_sn = :o_sn AND uniacid = :uniacid AND siteid = :siteid AND setfor LIKE :setfor AND title LIKE :title AND key LIKE :key ",array(":o_sn"=>$o_sn,":uniacid"=>$uniacid,":siteid"=>$siteid,":setfor"=>$key,":title"=>$k,":key"=>$k));
					}else{
						$temp['id']= pdo_fetchcolumn(" SELECT id FROM ".tablename($nowTable)." WHERE o_sn = :o_sn AND mid = :mid  AND uniacid = :uniacid AND siteid = :siteid AND setfor LIKE :setfor AND title LIKE :title AND key LIKE :key ",array(":o_sn"=>$o_sn,":mid"=>$_FM['member']['info']['uid'],":uniacid"=>$uniacid,":siteid"=>$siteid,":setfor"=>$key,":title"=>$k,":key"=>$k));
					}
					if($temp['id']) {
						unset($temp['createtime']);
						pdo_update($nowTable,$temp,array('id'=>$temp['id']));
					}else{
						pdo_insert($nowTable,$temp);
					}
				}
			}
		}else{
			$temp['setfor'] = $key;	//数据归属对象
			$temp['title'] = $key;	//数据标题
			$temp['key'] = $key;	//数据键值
			$temp['value'] = json_encode($val);	//数据值
			if($isGlobal){
				$temp['id']= pdo_fetchcolumn(" SELECT id FROM ".tablename($nowTable)." WHERE o_sn = :o_sn AND uniacid = :uniacid AND siteid = :siteid AND setfor LIKE :setfor AND title LIKE :title AND key LIKE :key ",array(":o_sn"=>$o_sn,":uniacid"=>$uniacid,":siteid"=>$siteid,":setfor"=>$key,":title"=>$key,":key"=>$key));
			}else{
				$temp['id']= pdo_fetchcolumn(" SELECT id FROM ".tablename($nowTable)." WHERE o_sn = :o_sn AND mid = :mid  AND uniacid = :uniacid AND siteid = :siteid AND setfor LIKE :setfor AND title LIKE :title AND key LIKE :key ",array(":o_sn"=>$o_sn,":mid"=>$_FM['member']['info']['uid'],":uniacid"=>$uniacid,":siteid"=>$siteid,":setfor"=>$key,":title"=>$key,":key"=>$key));
			}
			if($temp['id']) {
				unset($temp['createtime']);
				pdo_update($nowTable,$temp,array('id'=>$temp['id']));
			}else{
				pdo_insert($nowTable,$temp);
			}
		}
	}

	$return['result']=TRUE;
	return $return;
}

/*——————查询聊天表单相关信息——————————*/
/*
	查询某聊天室模型生成的聊天室实例编号集
*/
function fmMod_chat_self_sns($type=null){
	global $_W,$_FM;
	$nowTable = "hi_chat_basic_param";
	$types = fmMod_chat_type();
	$type = ($types[$type]) ? $type : 'default';
	$sqlFields=" `sn` ";
	$sql = "SELECT ".$sqlFields." FROM ".tablename($nowTable);
	$sqlCondition = " WHERE ";
	$sqlParas = array();
	$sqlCondition .= " `siteid` = :siteid ";
	$sqlParas[':siteid'] = $_W['setting']['site']['key'];
	$sqlCondition .= " AND ";
	$sqlCondition .= " `uniaicid` = :uniacid ";
	$sqlParas[':uniacid'] = $_W['uniacid'];
	$sqlCondition .= " AND ";
	$sqlCondition .= " `key` LIKE :key ";
	$sqlParas[':key'] = 'chat_type';
	$sqlCondition .= " AND ";
	$sqlCondition .= " `value` LIKE :value ";
	$sqlParas[':key'] = 'default';
	$showOrder = " ORDER BY id DESC";
	$chatModelSns = pdo_fetchall($sql.$condition.$showOrder,$sqlParas);
	cache_write('chat_model_sn_'.$_W['uniacid'],iserializer($chatModelSns));	//同时将记录集写入缓存
	return $chatModelSns;
}

/*
	查询指定的聊天室订单表
	@sn		聊天室订单表的记录编码
*/
function fmMod_chat_order_query($o_sn){
	global $_W,$_FM;
	$sn=intval($o_sn);
	$return=array();
	if(!is_numeric($sn)){
		$return['result']=FALSE;
		$error = '表单序号SN非法，需是整数';
		$return['msg']=$error;
		trigger_error($error, E_USER_ERROR);
		return $return;
	}
	if($sn<=0){
		$return['result']=FALSE;
		$error = '未传入有效的表单序号SN';
		$return['msg']=$error;
		trigger_error($error, E_USER_ERROR);
		return $return;
	}
	$nowTable = "hi_chat_basic_order";
	$sqlFields=" * ";
	$sql = "SELECT ".$sqlFields." FROM ".tablename($nowTable);
	$sqlCondition = " WHERE ";
	$sqlParas = array();
	$sqlCondition .= " `sn` = :sn ";
	$sqlParas[':sn'] = $sn;
	$sqlCondition .= " AND ";
	$sqlCondition .= " `uniaicid` = :uniacid ";
	$sqlParas[':uniacid'] = $_W['uniacid'];
	$sqlCondition .= " AND ";
	$sqlCondition .= " `siteid` = :siteid ";
	$sqlParas[':siteid'] = $_W['setting']['site']['key'];
	$showOrder = " ORDER BY displayorder DESC , id ASC";	//同等条件下取最先创建的，避免某些错误原因造成不必要的新订单表创建
	$chatOrder = pdo_fetch($sql.$condition.$showOrder,$sqlParas);
	cache_write('chat_order_'.$sn.'_'.$_W['uniacid'],iserializer($chatOrder));	//同时将数据序列化后写入缓存，以减少查询
	return $chatOrder;
}

/*
	查询指定的聊天室订单表的参数
	@o_sn		聊天表订单的记录编码
*/
function fmMod_chat_addon_query($o_sn){
	global $_W,$_FM;
	$sn=intval($o_sn);
	$return=array();
	if(!is_numeric($sn)){
		$return['result']=FALSE;
		$error = '表单序号SN非法，需是整数';
		$return['msg']=$error;
		trigger_error($error, E_USER_ERROR);
		return $return;
	}
	if($sn<=0){
		$return['result']=FALSE;
		$error = '未传入有效的表单序号SN';
		$return['msg']=$error;
		trigger_error($error, E_USER_ERROR);
		return $return;
	}
	$siteid = $_W['setting']['site']['key'];
	$nowTable = "hi_chat_basic_addon";
	$sqlFields=" * ";
	$sql = "SELECT ".$sqlFields." FROM ".tablename($nowTable);
	$sqlCondition = " WHERE ";
	$sqlParas = array();
	$sqlCondition .= " `o_sn` = :sn ";
	$sqlParas[':sn'] = $sn;
	$sqlCondition .= " AND ";
	$sqlCondition .= " `siteid` = :siteid ";
	$sqlParas[':siteid'] = $siteid;
	$sqlCondition .= " AND ";
	$sqlCondition .= " `status` != :status ";
	$sqlParas[':status'] = 0;
	$sqlCondition .= " AND ";
	$sqlCondition .= " `deleted` = :deleted ";
	$sqlParas[':deleted'] = 0;
	$showOrder = " ORDER BY id DESC ";
	$chatAddon = pdo_fetchall($sql.$condition.$showOrder,$sqlParas);
	//该参数需要前端根据业务情况(粉丝id\会员id等)进行更细一步的解析之后才可用,其中各value值需要反json
	cache_write('chat_addon_'.$sn.'_'.$siteid,iserializer($chatAddon));	//同时将数据序列化后写入缓存
	return $chatAddon;
}



/** ————聊天记录数据处理———— **/
//存入
function fmMod_chat_data_new($data,$o_sn) {
	global $_W,$_FM;
	$return=array();
	if(!is_array($data)){
		$return['result']=FALSE;
		$return['msg']='传入数据非法，需是数组';
		return $return;
	}
	$o_sn=intval($o_sn);
	if(!is_numeric($o_sn)){
		$return['result']=FALSE;
		$return['msg']='表单编码o_sn非法，需是正整数';
		return $return;
	}
	if($o_sn<=0){
		$return['result']=FALSE;
		$return['msg']='未传入表单编码o_sn';
		return $return;
	}
	//查询该o_sn表单的对应参数数据
	$order = fmMod_chat_order_query($o_sn);
	if(!$order) {
		$return['result']=FALSE;
		$return['msg']='未找到指定记录的聊天室表记录';
		return $return;
	}
	$nowTable = "hi_chat_basic_data";
	$nowTime = $_W['timestamp'];
	$message = array();
	$message['sn'] = $nowTime;
	$message['s_sn'] = $order['s_sn'];
	$message['f_sn'] = $order['f_sn'];
	$message['o_sn'] = $o_sn;
	$message['siteid'] = $order['siteid'];
	$message['platid'] = $order['platid'];
	$message['uniacid'] = $_W['uniacid'];
	$message['shopid'] = $order['shopid'];
	$message['partnerid'] = $order['partnerid'];
	$message['createtime'] = $nowTime;	//消息发出的时间
	$message['updatetime'] = $nowTime;	//消息发出的时间
	$message['deleted'] = 0;
	$message['statuscode'] = 0;

	$message['status'] = intval($data['status']);
	$message['mid']=intval($data['mid']);	//对应会员
	$message['fid']=intval($data['fid']);	//对应粉丝

	$message['setfor'] = $data['setfor'];	//数据归属对象
	$message['title'] = $data['title'];	//数据标题
	$message['key'] = $data['key'];	//数据键值

	$value = array();
	$value['type']=intval($data['type']);	//消息类型（-1,系统消息；-2表情；-3快捷表态；-4 红包；0,默认，文本消息；1图片；3音频； 4视频；）
	$value['avatar'] = $data['avatar'];	//会员的头像
	$value['username'] = $data['username'];	//会员昵称
	$value['openid'] = $data['openid'];	//会员OPENID
	$value['content'] = $data['content'];	//消息内容

	$message['value'] = json_encode($value);	///记录值

	$result = pdo_insert($nowTable,$message);
	if($result) {
		$return['result']=TRUE;
		$return['msg'] = '';
		$message['id'] = pdo_insertid();
		$return['data'] = $message;
	}else{
		$return['result']=FALSE;
		$return['msg']='保存数据失败';
	}
	return $return;
}

//读取聊天记录
/*
@o_sn 聊天室编码
@starttime 记录查询的起始时段
@$timelenth  记录查询的覆盖时长,以秒为单位
@num 数据查询量限制
@excluded 排除会员自己
@默认查询最近1周的10条数据，不允许跨站查询
*/
function fmMod_chat_data_get($o_sn,$starttime,$endtime,$timelenth,$num,$excluded) {
	global $_W,$_FM;
	$return=array();
	$o_sn=intval($o_sn);
	if(!is_numeric($o_sn)){
		$return['result']=FALSE;
		$return['msg']='表单编码o_sn非法，需是正整数';
		return $return;
	}
	if($o_sn<=0){
		$return['result']=FALSE;
		$return['msg']='未传入表单编码o_sn';
		return $return;
	}
	//查询该o_sn表单的对应参数数据
	$order = fmMod_chat_order_query($o_sn);
	if(!$order) {
		$return['result']=FALSE;
		$return['msg']='未找到指定记录的聊天室表记录';
		return $return;
	}
	$nowTable = "hi_chat_basic_data";
	$nowTime = $_W['timestamp'];
	$siteid = $_W['setting']['site']['key'];

	$default_num = 10;
	$default_lenth = 7*24*60*60;
	if(intval($num)==-1) {
		$num = false;
		$limit = "";
	}else{
		$num = (is_numeric($num) && intval($num)>0) ? intval($num) : 10;
		$limit = " LIMIT 0, ".$num;
	}
	$timelenth = (is_numeric($timelenth) && intval($timelenth)>0) ? intval($timelenth) : $default_lenth;
	$inittime = 1488297599;//1488297599,即2017年3月1日的前1秒,此前全无记录
	$endtime = intval($endtime);
	$endtime = ($endtime<=$inittime) ? false : $endtime;
	if(!$endtime) {
		$starttime = intval($starttime);
		$starttime = ($starttime>$inittime) ? $starttime : ($nowTime - $default_lenth);
		$endtime = $starttime + $timelenth;
	}else{
		$starttime = false;
	}

	$sqlFields=" * ";
	$sql = "SELECT ".$sqlFields." FROM ".tablename($nowTable);
	$sqlCondition = " WHERE ";
	$sqlParas = array();
	$sqlCondition .= " `o_sn` = :o_sn ";
	$sqlParas[':o_sn'] = $o_sn;
	$sqlCondition .= " AND ";
	$sqlCondition .= " `siteid` = :siteid ";
	$sqlParas[':siteid'] = $siteid;
	if($starttime) {
		$sqlCondition .= " AND ";
		$sqlCondition .= " `createtime` >= :starttime ";
		$sqlParas[':starttime'] = $starttime;
		if($num) {
			$sqlCondition .= " AND ";
			$sqlCondition .= " `createtime` < :endtime ";
			$sqlParas[':endtime'] = $endtime;
		}
	}elseif($endtime) {
		$sqlCondition .= " AND ";
		$sqlCondition .= " `createtime` < :endtime ";
		$sqlParas[':endtime'] = $endtime;
	}

	$sqlCondition .= " AND ";
	$sqlCondition .= " `status` != :status ";
	$sqlParas[':status'] = 0;
	$sqlCondition .= " AND ";
	$sqlCondition .= " `deleted` = :deleted ";
	$sqlParas[':deleted'] = 0;
	if($excluded){
		$sqlCondition .= " AND ";
		$sqlCondition .= " `mid` != :mid ";
		$sqlParas[':mid'] = $_FM['member']['info']['uid'];
	}
	$showOrder = " ORDER BY createtime DESC ";

	$chatData = pdo_fetchall($sql.$sqlCondition.$showOrder.$limit,$sqlParas);
	if(!$chatData && $endtime) {
		$hasQueryAll = cache_load('hasQueryAll_chat_data_'.$o_sn.'_'.$siteid);
		$hasQueryAll = intval($hasQueryAll);
		$hasQueryAll++;
		//if($hasQueryAll<=2) {
			$sqlCondition = str_replace(" AND "." `createtime` < :endtime ", "", $sqlCondition);
			unset($sqlParas[':endtime']);
			$chatData = pdo_fetchall($sql.$sqlCondition.$showOrder.$limit,$sqlParas);
			cache_write('hasQueryAll_chat_data_'.$o_sn.'_'.$siteid,$hasQueryAll);	//缓存标记该查询已执行过一次
		//}
	}
	//exit(json_encode($sql.$sqlCondition.$showOrder.$limit));
	//该参数需要前端根据业务情况(粉丝id\会员id等)进行更细一步的解析之后才可用,其中各value值需要反json
	cache_write('chat_data_'.$o_sn.'_'.$siteid,iserializer($chatData));	//同时将数据序列化后写入缓存
	$result = $chatData;

	if($result) {
		$return['result']=TRUE;
		$return['msg'] = '';
		$messages = $result;
		$return['data'] = $messages;
	}else{
		$return['result']=FALSE;
		$return['msg']='未查询到相关的数据';
	}
	return $return;
}

//备份一下，这个函数无使用
function fmMod_chat_data_get_b_a_c_k_u_p($o_sn) {
	global $_W,$_FM;
	$return=array();
	if(!is_array($data)){
		$return['result']=FALSE;
		$return['msg']='传入数据非法，需是数组';
		return $return;
	}
	$o_sn=intval($o_sn);
	if(!is_numeric($o_sn)){
		$return['result']=FALSE;
		$return['msg']='表单编码o_sn非法，需是正整数';
		return $return;
	}
	if($o_sn<=0){
		$return['result']=FALSE;
		$return['msg']='未传入表单编码o_sn';
		return $return;
	}
	//查询该o_sn表单的对应参数数据
	$order = fmMod_chat_order_query($o_sn);
	if(!$order) {
		$return['result']=FALSE;
		$return['msg']='未找到指定记录的聊天室表记录';
		return $return;
	}
	$nowTable = "hi_chat_basic_data";
	$nowTime = $_W['timestamp'];
	$message = array();
	$message['sn'] = $nowTime;
	$message['s_sn'] = $order['s_sn'];
	$message['f_sn'] = $order['f_sn'];
	$message['o_sn'] = $o_sn;
	$message['siteid'] = $order['siteid'];
	$message['platid'] = $order['platid'];
	$message['uniacid'] = $_W['uniacid'];
	$message['shopid'] = $order['shopid'];
	$message['partnerid'] = $order['partnerid'];
	$message['createtime'] = $nowTime;	//消息发出的时间
	$message['updatetime'] = $nowTime;	//消息发出的时间
	$message['deleted'] = 0;
	$message['statuscode'] = 0;

	$message['status'] = intval($data['status']);
	$message['mid']=intval($data['mid']);	//对应会员
	$message['fid']=intval($data['fid']);	//对应粉丝

	$message['setfor'] = $data['setfor'];	//数据归属对象
	$message['title'] = $data['title'];	//数据标题
	$message['key'] = $data['key'];	//数据键值

/** 数据单条保存的方法，考虑到数据库压力，暂未使用 **/
/**
	$exist_fields = array('id','sn','s_sn','f_sn','o_sn','siteid','platid','uniacid','shopid','partnerid','mid','fid','createtime','updatetime','statuscode');
	$data_paras = array('setfor','title','key','value','status','deleted','displayorder');

	//清除$data中的无用数据
	foreach($exist_fields as $key){
		if(isset($data[$key])){
			unset($data[$key]);
		}
	}

	foreach($data as $key=>$val){
		$message['setfor'] = $nowTime;	//数据归属对象
		$message['title'] = $key;	//数据标题
		$message['key'] = $key;	//数据键值
		$message['value'] = json_encode($val);	//数据值
		pdo_insert($nowTable,$message);
	}

	$return['result']=TRUE;
	$return['msg'] = '';
	$return['data'] = $message['sn'];

	return $return;

**/
	$value = array();
	$value['type']=intval($data['type']);	//消息类型（-1,系统消息；-2表情；-3快捷表态；-4 红包；0,默认，文本消息；1图片；3音频； 4视频；）
	$value['avatar'] = $data['avatar'];	//会员的头像
	$value['username'] = $data['username'];	//会员昵称
	$value['openid'] = $data['openid'];	//会员OPENID
	$value['content'] = $data['content'];	//消息内容

	$message['value'] = json_encode($value);	///记录值

	$result = pdo_insert($nowTable,$message);
	if($result) {
		$return['result']=TRUE;
		$return['msg'] = '';
		$message['id'] = pdo_insertid();
		$return['data'] = $message;
	}else{
		$return['result']=FALSE;
		$return['msg']='保存数据失败';
	}
	return $return;
}


/** ————初始化聊天室——OK!—— **/
/**
	根据步骤生成一个当前公众号的全员群聊(全员群聊每个公众号仅支持创建一个)
	1.生成全员群聊模型
	2.生成全员群
	3.配置基本参数（全员群要求唯一）
	4.生成群聊表单
	5.写入模拟数据
	6.返回群数据array(self,param,form)
 **/
function fmMod_chat_init(){
	global $_W;
	global $_FM;
	$siteid = $_W['setting']['site']['key'];
	$uniacid = $_W['uniacid'];
	$nowTime = $_W['timestamp'];

	$chatSelf =array();
	$nowTable="hi_chat_basic_self";
	$sqlFields=" * ";
	$sql = "SELECT ".$sqlFields." FROM ".tablename($nowTable);
	$chatSelf = pdo_fetch($sql." WHERE uniacid = :uniacid AND siteid = :siteid ORDER BY id DESC",array(":siteid"=>$siteid,":uniacid"=>$_W['uniacid']));
	if(!$chatSelf){
		$sn = $nowTime;
		$data=array();
		$data['sn']=$sn;
		$data['siteid'] = $siteid;
		$data['platid']= intval($_FM['settings']['platid']);
		$data['uniacid']= $_W['uniacid'];
		$data['shopid']= intval($_FM['settings']['shopid']);
		$data['title']= "全员群聊";
		$data['keywords']= "全员群聊|".$_W['uniaccount']['name'];
		$data['displayorder']= 0;
		$data['status']=1;
		$data['deleted']=0;
		$data['statuscode']= 0;
		$data['createtime']= $nowTime;
		$data['updatetime']= $nowTime;
		pdo_insert($nowTable,$data);
		$id = pdo_insertid();
		$data['id']=$id;
		$chatSelf=$data;
	}
	$sn = $chatSelf['sn'];

	$chatParam = array();
	$nowTable="hi_chat_basic_param";
	$sqlFields=" * ";
	$sql = "SELECT ".$sqlFields." FROM ".tablename($nowTable);
	$chatParam = pdo_fetchall($sql." WHERE s_sn = :s_sn AND uniacid = :uniacid AND siteid = :siteid ORDER BY displayorder DESC ,id ASC ",array(":s_sn"=>$sn,":siteid"=>$siteid,":uniacid"=>$_W['uniacid']));

	$chatForm = array();
	$nowTable="hi_chat_basic_form";
	$sqlFields=" * ";
	$sql = "SELECT ".$sqlFields." FROM ".tablename($nowTable);
	$chatForm = pdo_fetch($sql." WHERE s_sn = :s_sn AND uniacid = :uniacid AND siteid = :siteid ORDER BY displayorder DESC ,id DESC ",array(":s_sn"=>$sn,":siteid"=>$siteid,":uniacid"=>$_W['uniacid']));
	if(!$chatForm){
		$f_sn = $nowTime;
		$data=array();
		$data['sn']=$f_sn;
		$data['s_sn']=$sn;
		$data['siteid'] = $siteid;
		$data['platid']= intval($_FM['settings']['platid']);
		$data['uniacid']= $_W['uniacid'];
		$data['shopid']= intval($_FM['settings']['shopid']);
		$data['partnerid']= intval($_FM['settings']['partnerid']);
		$data['uid']=0;
		$data['title']= "全员群聊";
		$data['value']= "欢迎您加入微群";
		$data['displayorder']= 0;
		$data['status']=1;
		$data['deleted']=0;
		$data['statuscode']= 0;
		$data['createtime']= $nowTime;
		$data['updatetime']= $nowTime;
		pdo_insert($nowTable,$data);
		$id = pdo_insertid();
		$data['id']=$id;
		$chatForm=$data;
	}
	$f_sn = $chatSelf['sn'];

	$chatOrder = array();
	$nowTable="hi_chat_basic_order";
	$sqlFields=" * ";
	$sql = "SELECT ".$sqlFields." FROM ".tablename($nowTable);
	$chatOrder = pdo_fetch($sql." WHERE s_sn = :s_sn AND f_sn = :f_sn AND uniacid = :uniacid AND siteid = :siteid ORDER BY displayorder DESC ,id ASC ",array(":s_sn"=>$sn,":f_sn"=>$f_sn,":siteid"=>$siteid,":uniacid"=>$_W['uniacid']));
	if(!$chatOrder){
		$o_sn = $nowTime;
		$data=array();
		$data['sn']=$o_sn;
		$data['s_sn']=$sn;
		$data['f_sn']=$f_sn;
		$data['siteid'] = $siteid;
		$data['platid']= intval($_FM['settings']['platid']);
		$data['uniacid']= $_W['uniacid'];
		$data['shopid']= intval($_FM['settings']['shopid']);
		$data['partnerid']= intval($_FM['settings']['partnerid']);
		$data['mid']=0;
		$data['fid']=0;
		$data['title']= "全员群聊";
		$data['value']= "欢迎您加入微群";
		$data['displayorder']= 0;
		$data['status']=1;
		$data['deleted']=0;
		$data['statuscode']= 0;
		$data['createtime']= $nowTime;
		$data['updatetime']= $nowTime;
		pdo_insert($nowTable,$data);
		$id = pdo_insertid();
		$data['id']=$id;
		$chatOrder=$data;
	}
	$o_sn = $chatOrder['sn'];

	$chatAddon = array();
	$nowTable="hi_chat_basic_addon";
	$sqlFields=" * ";
	$sql = "SELECT ".$sqlFields." FROM ".tablename($nowTable);
	$chatAddon = pdo_fetchall($sql." WHERE  o_sn = :o_sn AND f_sn = :f_sn AND  s_sn = :s_sn AND uniacid = :uniacid AND siteid = :siteid ORDER BY displayorder DESC ,id ASC ",array(":o_sn"=>$o_sn,":f_sn"=>$f_sn,":s_sn"=>$sn,":siteid"=>$siteid,":uniacid"=>$_W['uniacid']));

	$chatData = array();
	$nowTable="hi_chat_basic_data";
	$sqlFields=" * ";
	$sql = "SELECT ".$sqlFields." FROM ".tablename($nowTable);
	$sqlCondition = " WHERE ";
	$sqlParas = array();
	$sqlCondition .= " `o_sn` = :o_sn ";
	$sqlParas[':o_sn'] = $o_sn;
	$sqlCondition .= " AND ";
	$sqlCondition .= " `f_sn` = :f_sn ";
	$sqlParas[':f_sn'] = $f_sn;
	$sqlCondition .= " AND ";
	$sqlCondition .= " `s_sn` = :s_sn ";
	$sqlParas[':s_sn'] = $sn;
	$sqlCondition .= " AND ";
	$sqlCondition .= " `siteid` = :siteid ";
	$sqlParas[':siteid'] = $_W['setting']['site']['key'];
	$sqlCondition .= " AND ";
	$sqlCondition .= " `uniaicid` = :uniacid ";
	$sqlParas[':uniacid'] = $_W['uniacid'];
	$sqlCondition .= " AND ";
	$sqlCondition .= " `deleted` = :deleted ";
	$sqlParas[':deleted'] = 0;
	$showOrder = " ORDER BY id DESC";
	$chatData = pdo_fetch($sql.$condition.$showOrder,$sqlParas);

	$chats=array(
		'self'=>$chatSelf,
		'param'=>$chatParam,
		'form'=>$chatForm,
		'order'=>$chatOrder,
		'data'=>$chatData,
	);
	return $chats;
}
