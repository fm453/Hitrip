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
 * @remark：前台反馈
 */
defined('IN_IA') or exit('Access Denied');

//获取反馈类型
function fmFunc_feedback_type($type){
	$types = array	(
		1 => '维权',
		2 => '告警'
	);
	return $types[intval($type)];
}

//获取反馈状态
function fmFunc_feedback_status($status){
	$statuses = array(
		1=>'未解决',
		2=>'用户同意',
		3=>'用户拒绝'
	);
	return $statuses[intval($status)];
}

?>
