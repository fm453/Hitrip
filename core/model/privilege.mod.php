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
 * @remark	权限控制模型
 */
defined('IN_IA') or exit('Access Denied');
fm_load()->fm_func('tables');
//检查管理员权限
function fmMod_privilege_adm($setfor=NULL) {
	global $_GPC,$_W,$_FM;
	$return = array();
	$do=$_GPC['do'];
	$ac=$_GPC['ac'];
	$op=!empty($_GPC['op']) ? $_GPC['op'] : 'display';
	$nowroute=$do.'_'.$ac.'_'.$op;
	$setfor = !empty($setfor) ? $nowroute.'_'.$setfor : $nowroute;
	$privilege=fmMod_privilege_get();
	if(is_numeric($privilege[$setfor])) {
		return TRUE;
	}elseif(!empty($privilege[$setfor])){
		return $privilege[$setfor];
	}elseif(empty($privilege[$nowroute])){
		//当前路径未作任何权限设置时，默认拥有全部权限
		return TRUE;
	}else{
		message('抱歉，您不具备该项操作权限！如果确认您应该有此权限，请联系站长开通！','','error');
	}
}

//保存权限入库
function fmMod_privilege_save($data){
	global $_GPC,$_W,$_FM;
	$return = array();
	$table = 'fm453_shopping_privilege';
	if(is_array($data)){
		$ids=array();
		$fields=fmFunc_tables_fields('privilege');
		foreach($data as $da){
			foreach($da as $key=>$d){
				if(!in_array($key,$fields)){
					unset($data[$key]);
				}
			}
			if(empty($data)) {
				return FALSE;
			}
			$params=array();
			$sql="SELECT `id` FROM ".tablename($table);
			$condition =" WHERE ";
			$condition .= "uniacid = :uniacid";
			$params[':uniacid']=$da['uniacid'];
			$condition .= ' AND ';
			$condition .= '`m`=:m';
			$params[':m']= FM_NAME;
			$condition .=" AND ";
			$condition .= "platid = :platid";
			$params[':platid']=$da['platid'];
			$condition .=" AND ";
			$condition .= "uid = :uid";
			$params[':uid']=$da['uid'];
			$condition .=" AND ";
			$condition .= "do = :do";
			$params[':do']=$da['do'];
			$condition .=" AND ";
			$condition .= "ac = :ac";
			$params[':ac']=$da['ac'];
			$condition .=" AND ";
			$condition .= "op = :op";
			$params[':op']=$da['op'];
			$id=pdo_fetchcolumn($sql.$condition,$params);
			if($id) {
				$new_da=array();
				$new_da['view']=$da['view'];
				$new_da['modify']=$da['modify'];
				$new_da['delete']=$da['delete'];
				$new_da['role']=$da['role'];
				pdo_update($table,$new_da,array('id'=>$id));
			}else{
				$da['createtime']=TIMESTAMP;
				pdo_insert($table,$da);
				$id=pdo_insertid();
			}
			$ids[]=$id;
		}
		return $ids;
	}
	else{
		return FALSE;
	}
}

//获取指定用户的权限
function fmMod_privilege_get($uid=NULL,$uniacid=NULL,$platid=NULL){
	global $_GPC,$_W,$_FM;
	$uid = empty($uid) ? $_W['uid'] : $uid;
	$table = 'fm453_shopping_privilege';
	if(is_numeric($uid) && $uid >0){
		$uniacid = (intval($uniacid)>0) ? intval($uniacid) : $_W['uniacid'];
		$platid = (intval($platid)>0) ? intval($platid) : $_W['uniacid'];
		$fields = fmFunc_tables_fields('privilege');
		foreach($fields as &$field){
			$field = '`'.$field.'`';//有些字段如delete是mysql的保留字段，需要转义
		}
		$fields = implode(',',$fields) ;
		$params=array();
		$sql = "SELECT ".$fields." FROM ".tablename($table);
		$condition = " WHERE ";
		$condition .="uniacid = :uniacid";
		$params[':uniacid']=$uniacid;
		$condition .= " AND ";
		$condition .="platid = :platid";
		$params[':platid']=$platid;
		$condition .= " AND ";
		$condition .="uid = :uid";
		$params[':uid']=$uid;
		$condition .= ' AND ';
		$condition .= '`m`=:m';
		$params[':m']= FM_NAME;
		$privileges=pdo_fetchall($sql.$condition,$params);
		$privilege=array();
		if($privileges){
			foreach($privileges as $key => $p){
				if(!empty($p['do'])){
					$privilege[$p['do']]=1;
					if(!empty($p['ac'])){
						$privilege[$p['do'].'_'.$p['ac']]=1;
						if(!empty($p['op'])){
							$privilege[$p['do'].'_'.$p['ac'].'_'.$p['op']]=1;
							$privilege[$p['do'].'_'.$p['ac'].'_'.$p['op'].'_view']=$p['view'];
							$privilege[$p['do'].'_'.$p['ac'].'_'.$p['op'].'_modify']=$p['modify'];
							$privilege[$p['do'].'_'.$p['ac'].'_'.$p['op'].'_delete']=$p['delete'];
							$privilege[$p['do'].'_'.$p['ac'].'_'.$p['op'].'_role']=$p['role'];
						}
					}
				}
			}
		}
		return $privilege;
	}
}