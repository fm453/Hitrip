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
 * @remark	商户模型
 */
defined('IN_IA') or exit('Access Denied');

fm_load()->fm_func("tables");

function fmMod_partner_orderby(){
	return $showorder=' ORDER BY uniacid ASC, displayorder DESC, createtime DESC ';//强制设定排序规则
}

//查询商城代理人资料
function fmMod_partner_query($id){
	global $_GPC;
	global $_W;
	global $_FM;
	$return=array();
	if(!is_numeric($id)) {
		$return['result']=FALSE;
		$return['msg']='传入的商户id非法（必须是正整数）';
		return $return;
	}
	$table = 'fm453_shopping_partner';
	$result = pdo_fetch("SELECT * FROM ".tablename($table)." WHERE id = :id",array(':id'=>$id));
	if($result) {
		$return['result']=TRUE;
		$return['data'] = $result;
		return $return;
	}else{
		$return['result']=FALSE;
		$return['msg']='未查询到该商户资料';
		return $return;
	}
}

?>
