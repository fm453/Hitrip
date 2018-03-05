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
 * @remark 接口-提醒开发者，获取授权
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;
load()->model('account');//加载公众号函数

//入口判断
$do        = $_GPC['do'];
$ac        =$_GPC['ac'];
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'index';
//开始操作管理
$shopname  =$settings['brands']['shopname'];
$shopname  = !empty($shopname) ? $shopname :FM_NAME_CN;

if($op=='remind'){
	$ip=$_GPC['ip'];
	$domain=$_GPC['domain'];
	fmMod_notice_remind($ip, $domain);
}
elseif($op=='tome'){
	$ip=$_GPC['ip'];
	$domain=$_GPC['domain'];
	fmMod_notice_fm453($ip, $domain);
}
