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
 * @remark：电话号码处理函数
 */
defined('IN_IA') or exit('Access Denied');

//手机号掩码
function fmFunc_mobile_mask($mobile) {
	return substr($mobile, 0, 3) . '****' . substr($mobile, 7);
}
