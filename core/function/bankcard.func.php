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
 * @remark：银行卡处理函数
 */
defined('IN_IA') or exit('Access Denied');

//银行卡号加空格隔断
function fmFunc_bankcard_space($cardnumber){
	$card ='';
	for($i=0; $i<5; $i++){
		$card = $card. substr($cardnumber,4*$i,4). ' ';
	}
	return $card;
}
