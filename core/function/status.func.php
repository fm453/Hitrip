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
 * @remark：状态集处理函数
 */
defined('IN_IA') or exit('Access Denied');

//全局设定，商城状态清单获取
function fmFunc_status_get($type)
{
	$status=array();
	require FM_CORE.'status/'.$type.'.php';
	return $status;
}
