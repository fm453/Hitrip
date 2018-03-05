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
 * @remark：搜索处理函数
 */
defined('IN_IA') or exit('Access Denied');

//判断会员是否有消费记录
function fmFunc_search_getKwd(){
	global $_W, $_GPC,$_FM;
	$LIB_keywords = array();
	$LIB_keywords['techan'] = array('苹果','地瓜','豆粑','红薯粉','葛藤粉','秘制');
	$LIB_keywords['goods'] = array('门票','套票','客栈','情趣','hellokity','主题','西线');
	$LIB_keywords['article'] = array('安庆','阜阳','合肥','');
}
