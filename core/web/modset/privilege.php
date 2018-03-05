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
 * @remark 权限设置(一次只允许针对当前公号所辖的全部分店设置;对于同一用户在不同的公号内，管理同一个分店时，亦会有不同的权限)；
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;

//加载风格模板及资源路径
$fm453style = fmFunc_ui_shopstyle();
$fm453resource =FM_RESOURCE;

//入口判断
$routes=fmFunc_route_web();
$routes_do=fmFunc_route_web_do();
$do=$_GPC['do'];
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
$shopname = !empty($shopname) ? $shopname : FM_NAME_CN;
$_W['page']['title'] = $shopname.$routes[$do]['title'];
$fendianids=$settings['plat']['fendianids'];//获取平台模式关联的公众号下属商户公号ID参数
$fendianids = !empty($fendianids)? $fendianids : $_W['uniacid'];
$accounts=pdo_fetchall("SELECT * FROM " . tablename('uni_account')." WHERE uniacid IN (".$fendianids.')',array(),'uniacid');
$fendianids=explode(',',$fendianids);
$nowuid =$_GPC['nowuid'];
$nowdo =$_GPC['nowdo'];
$nowplat = $_GPC['nowplat'];
$nowplat = in_array(intval($nowplat),$fendianids) ? intval($nowplat) : $_W['uniacid'];
$users=pdo_fetchall("SELECT * FROM ".tablename('users'),array(),'uid');
$roles = fmFunc_status_get('role');
if($operation=='display') {
	$privilege = fmMod_privilege_get($nowuid,$_W['uniacid'],$nowplat);
	include $this->template('modset/privilege');
}
elseif($operation=='modify') {
	if(intval($nowuid)<=0 || !in_array($nowdo,$routes_do)){
		if(intval($nowuid)<=0){
			message('未选择一个用户！','','error');
		}
		if(!in_array($nowdo,$routes_do)){
			message('传入的数据非法！','','error');
		}
	}
	$data=array();
	if($_GPC[$nowdo]) {
		//确保仅更新当前入口下的权限，不影响其他
		$j=0;
		foreach($routes[$nowdo]['ac'] as $ac_key => $a_c){
			$i=0;
			foreach($a_c['op'] as $op_key => $o_p){
				$op_name=$nowdo.'_'.$ac_key.'_'.$op_key;
				$ac_name=$nowdo.'_'.$ac_key;
				$data[$j][$i]['uniacid']=$_W['uniacid'];
				$data[$j][$i]['platid']=$nowplat;
				$data[$j][$i]['uid']=$nowuid;
				$data[$j][$i]['do']=$_GPC[$nowdo];
				$data[$j][$i]['ac']=$_GPC[$ac_name];
				$data[$j][$i]['op']=$_GPC[$op_name];
				$data[$j][$i]['view']=$_GPC[$op_name.'_view'];
				$data[$j][$i]['modify']=$_GPC[$op_name.'_modify'];
				$data[$j][$i]['delete']=$_GPC[$op_name.'_delete'];
				$data[$j][$i]['role']=  !empty($_GPC[$op_name.'_role']) ? $_GPC[$op_name.'_role'] : $roles['code']['value'];//默认取第一种角色
				$i +=1;
			}
			$j +=1;
		}
	}
	$new_data=array();
	foreach($data as $j_d){
		foreach($j_d as $i_d){
			$new_data[]=$i_d;
		}
	}
	$result=fmMod_privilege_save($new_data);
	if($result) {
		$new_privilege = fmMod_privilege_get($nowuid,$_W['uniacid'],$nowplat);
		//写入操作日志START
		$dologs=array(
			'url'=>$_W['siteurl'],
			'description'=>'模块权限设置',
			'addons'=>$new_privilege
		);
		fmMod_log_record($platid,$_W['uniacid'],$_W['uid'],$fanid,$openid,'fm453_shopping_privilege',$id,'modify',$dologs);
		unset($dologs);
		//写入操作日志END
		message('商城权限设置成功','referer','success');
	}
	else{
		message('权限设置失败','','error');
	}
}