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
 * @remark：营销管理处理函数
 */
defined('IN_IA') or exit('Access Denied');

//全局设定，商城营销模型标识清单获取
function fmFunc_market_typesFull(){
	$types=array();
	$types['presale']=array
	(
		'name'=>'预售',
		'status'=>'127',
	);
	$types['pintuan']=array
	(
		'name'=>'拼团',
		'status'=>'127',
	);
	$types['limitnum']=array
	(
		'name'=>'限量',
		'status'=>'127',
	);
	$types['limittime']=array
	(
		'name'=>'限时',
		'status'=>'127',
	);
	$types['freedispatch']=array
	(
		'name'=>'包邮',
		'status'=>'127',
	);
	$types['minus']=array
	(
		'name'=>'满减',
		'status'=>'127',
	);
	$types['giftbale']=array
	(
		'name'=>'满赠',
		'status'=>'127',
	);
	$types['addable']=array
	(
		'name'=>'加价购',
		'status'=>'127',
	);
	$types['guessable']=array
	(
		'name'=>'竞猜',
		'status'=>'127',
	);
	$types['lucky']=array
	(
		'name'=>'博奖',
		'status'=>'127',
	);
	$types['discount']=array
	(
		'name'=>'折扣',	//具体的折扣数值
		'status'=>'127',
	);
	$types['onlynewuser']=array
	(
		'name'=>'新用户专享',
		'status'=>'127',
	);
	$types['onlyolduser']=array
	(
		'name'=>'老用户专享',
		'status'=>'127',
	);
	$types['onlyfemale']=array
	(
		'name'=>'女士专享',
		'status'=>'127',
	);
	$types['onlymale']=array
	(
		'name'=>'男士专享',
		'status'=>'127',
	);
	$types['onlymember']=array
	(
		'name'=>'会员专享',
		'status'=>'127',
	);

	return $types;
}

function fmFunc_market_types(){
	$fullTypes=fmFunc_market_typesFull();
	$types=array();
	foreach($fullTypes as $key=>$value)
	{
		if($value['status']==0)
		{
			unset($fullTypes[$key]);
		}else{
			$types[$key]=$value['name'];
		}
	}
	return $types;
}

?>
