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
 * @remark：快递/配送处理函数
 */
defined('IN_IA') or exit('Access Denied');

//配送方式列表
function fmFunc_express_dispatch_list(){
	global $_GPC;
	global $_W;
	global $_FM;
	$list = pdo_fetchall("SELECT * FROM " . tablename('fm453_shopping_dispatch') . " WHERE `uniacid` = '{$_W['uniacid']}' AND `isavailable`=1 AND `deleted`=0 ORDER BY displayorder DESC");
	return $list;
}

//已录入快递公司列表
function fmFunc_express_company_list(){
	global $_GPC;
	global $_W;
	global $_FM;
	$list = pdo_fetchall("select * from " . tablename('fm453_shopping_express') . " WHERE `uniacid` = '{$_W['uniacid']}' AND `deleted`=0 ORDER BY displayorder DESC");
	return $list;
}

//快递品牌列表
function fmFunc_express_brand_list(){
	global $_GPC;
	global $_W;
	global $_FM;
	include FM_PUBLIC.'express.php';
	return $expresses;
}
