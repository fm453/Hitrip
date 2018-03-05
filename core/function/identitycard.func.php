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
 * @remark：身份证合法性校验
 */
defined('IN_IA') or exit('Access Denied');

//返回身份证校验结果
function fmFunc_idcard_validation_filter($id_card){
	if(strlen($id_card)==18){
		return fmFunc_idcard_checksum18($id_card);
	}elseif((strlen($id_card)==15)){
		$id_card=fmFunc_idcard_15to18($id_card);
		return fmFunc_idcard_checksum18($id_card);
	}else{
		return false;
	}
}

//给身份证号加空格隔断、加掩码
function fmFunc_idcard_mask($id_card){
    if(!$id_card) {
        return;
    }
    $str = substr($id_card, 0, 2) . '**' . substr($id_card, 5,2).'********'.substr($id_card, 14);
	$card ='';
	for($i=0; $i<5; $i++){
		$card = $card. substr($id_card,4*$i,4). ' ';
	}
	return $card;
}

// 计算身份证校验码，根据国家标准GB 11643-1999
function fmFunc_idcard_verify_number($idcard_base){
	if(strlen($idcard_base)!=17){
		return false;
	}
	//加权因子
	$factor=array(7,9,10,5,8,4,2,1,6,3,7,9,10,5,8,4,2);
	//校验码对应值
	$verify_number_list=array('1','0','X','9','8','7','6','5','4','3','2');
	$checksum=0;
	for($i=0;$i<strlen($idcard_base);$i++){
		$checksum += substr($idcard_base,$i,1) * $factor[$i];
	}
	$mod=$checksum % 11;
	$verify_number=$verify_number_list[$mod];
	return $verify_number;
}

// 将15位身份证升级到18位
function fmFunc_idcard_15to18($idcard){
	if(strlen($idcard)!=15){
		return false;
	}else{
	 	// 如果身份证顺序码是996 997 998 999，这些是为百岁以上老人的特殊编码
		if(array_search(substr($idcard,12,3),array('996','997','998','999')) !== false){
  		$idcard=substr($idcard,0,6).'18'.substr($idcard,6,9);
		}else{
			$idcard=substr($idcard,0,6).'19'.substr($idcard,6,9);
		}
	}
	$idcard=$idcard.fmFunc_idcard_verify_number($idcard);
	return $idcard;
}

// 18位身份证校验码有效性检查
function fmFunc_idcard_checksum18($idcard){
	if(strlen($idcard)!=18){
		return false;
	}
	$idcard_base=substr($idcard,0,17);
	if(fmFunc_idcard_verify_number($idcard_base)!=strtoupper(substr($idcard,17,1))){
		return false;
	}else{
		return true;
	}
}
