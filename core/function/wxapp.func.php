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

/**
 * @remark：小程序专用的一些函数
 */
defined('IN_IA') or exit('Access Denied');

	//检查登陆
	function fmFunc_wxapp_checkLogin()
	{
		global $_W;
		if (empty($_W['fans'])) {
			return error(1, '请先登录');
		}
		return true;
	}
