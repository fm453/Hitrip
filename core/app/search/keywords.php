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
 * @remark 关键词入库与输出
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;
$LIB_keywords = array();
$LIB_keywords['techan'] = array('苹果','地瓜','豆粑','红薯粉','葛藤粉','秘制');
$LIB_keywords['goods'] = array('门票','套票','客栈','情趣','hellokity','主题','西线');
$LIB_keywords['article'] = array('安庆','阜阳','合肥','');
