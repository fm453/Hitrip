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
 * @remark	商品模型管理模块
 */
defined('IN_IA') or exit('Access Denied');
//获取产品模型列表
function fmMod_goodstpl_type_get(){
	global $_GPC;
	global $_W;
	global $_FM;
	$gtpltype=array();
	$gtpltype['default']=array(
		'name'=>'默认',
		'status'=>'127',
	);
	$gtpltype['hotel']=array(
		'name'=>'酒店',
		'status'=>'127',
	);
	$gtpltype['tickets']=array(
		'name'=>'景区门票',
		'status'=>'127',
	);
	$gtpltype['onedaytrip']=array(
		'name'=>'一日游',
		'status'=>'127',
	);
	$gtpltype['package_hx']=array(
		'name'=>'酒+X套餐',
		'status'=>'127',
	);
	$gtpltype['jiesong']=array(
		'name'=>'接送站/机',
		'status'=>'127',
	);
	$gtpltype['needs']=array(
		'name'=>'有求必应',
		'status'=>'127',
	);
	$gtpltype['fruit']=array(
		'name'=>'水果',
		'status'=>'127',
	);
	$gtpltype['seafood']=array(
		'name'=>'海鲜',
		'status'=>'127',
	);
	$gtpltype['halffresh']=array(
		'name'=>'生鲜',
		'status'=>'127',
	);
	$gtpltype['car']=array(
		'name'=>'租车',
		'status'=>'127',
	);
	return $gtpltype;
}

//获取产品模型项参数（每一项的数据类型、字段名称、是否必填）
function fmMod_goodstpl_query_param($tpl,$platid){
	global $_GPC;
	global $_W;
	global $_FM;
	$goodstplparam=array();
	$tpls = pdo_fetchall("select * from " . tablename('fm453_shopping_goodstpl') . " where goodstpl = :goodstpl AND uniacid = :uniacid AND statuscode = :statuscode", array(':goodstpl' => $tpl,":uniacid" => 0,':statuscode'=>64));
	if(is_array($tpls)){
	foreach($tpls as $key){
		$goodstplparam[$key['param']]=array();
		$goodstplparam[$key['param']]['datatype']=$key['datatype'];
		$goodstplparam[$key['param']]['value']=$key['value'];
		$goodstplparam[$key['param']]['isneeded']=$key['isneeded'];
	}
	unset($key);
	}
	if($platid>0) {
		$tpls_plus = pdo_fetchall("select * from " . tablename('fm453_shopping_goodstpl') . " where goodstpl = :goodstpl AND uniacid = :uniacid AND statuscode = :statuscode", array(':goodstpl' => $tpl,":uniacid" => $platid,':statuscode'=>64));
		if(is_array($tpls)){
		foreach($tpls_plus as $key){
			unset($goodstplparam[$key['param']]);
			$goodstplparam[$key['param']]=array();
			$goodstplparam[$key['param']]['datatype']=$key['datatype'];
			$goodstplparam[$key['param']]['value']=$key['value'];
			$goodstplparam[$key['param']]['isneeded']=$key['isneeded'];
		}
		unset($key);
		}
	}
	return $goodstplparam;
}
