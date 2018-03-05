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
 * @remark 在线测试浏览器类型
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;
if(intval($_GPC['i'])<=0) {
	$_W['uniacid']=1;
	$_GPC['i']=1;
}

$browser = fmFunc_check_browser();
print_r($browser);
echo "<br>";
print_r($_SERVER['HTTP_USER_AGENT']);
echo "<br>";
if(fmFunc_wechat_is()){
    echo "当前是微信浏览器";
}
exit();