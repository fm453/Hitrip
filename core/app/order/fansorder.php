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
 * @remark 代理人粉丝订单
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;
load()->func('tpl');
fm_load()->fm_func('share');//链接分享管理
fm_load()->fm_func('view'); 	//浏览量处理
fm_load()->fm_func('wechat');//微信定义管理

//是否关店歇业
fm_checkopen($settings['onoffs']);
//加载风格模板及资源路径
$appstyle = empty($settings['appstyle']) ? FM_APPSTYLE : $settings['appstyle'];
$appsrc =MODULE_URL.'template/mobile/'.$appstyle.'453/';
$htmlsrc =MODULE_URL.'template/mobile/'.$appstyle;
$fm453resource = FM_RESOURCE;

$uniacid=$_W['uniacid'];
$op = $_GPC['op']?$_GPC['op']:'display';
$profile = pdo_fetch('SELECT * FROM '.tablename('fm453_shopping_member')." WHERE  uniacid = :uniacid  AND from_user = :from_user" , array(':uniacid' => $_W['uniacid'],':from_user' => $from_user));
$id = $profile['id'];
if(intval($profile['id']) && $profile['status']==0){
	include $this->template($appstyle.'forbidden');
	exit;
}
if(empty($profile)){
	message('请先注册',$this->mturl('register'),'error');
	exit;
}
$pindex = max(1, intval($_GPC['page']));
$psize = 20;
$list = pdo_fetchall("SELECT o.createtime,o.ordersn,o.status, g.commission, g.total, g.goodsid FROM " . tablename('fm453_shopping_order') . " as o left join ".tablename('fm453_shopping_order_goods')." as g on o.id = g.orderid and o.uniacid = g.uniacid WHERE o.shareid = ".$id." and o.uniacid = ".$_W['uniacid']." and o.from_user<>'".$profile['from_user']."' ORDER BY o.createtime DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
$goods = pdo_fetchall("select id, title from ".tablename('fm453_shopping_goods'). " where uniacid = ".$_W['uniacid']. " and status = 1");
$good = array();
foreach($goods as $g){
	$good[$g['id']] = $g['title'];
}
$total = pdo_fetchcolumn('SELECT COUNT(id) FROM ' .tablename('fm453_shopping_order'). " WHERE uniacid = ".$_W['uniacid']." AND shareid = ".$id);
$pager = pagination($total, $pindex, $psize);

include $this->template($appstyle.$do.'/453');
