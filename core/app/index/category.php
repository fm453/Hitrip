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
 * @remark 分类入口页-待
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;
fm_load()->fm_func('share');//链接分享管理
fm_load()->fm_func('view'); 	//浏览量处理
fm_load()->fm_func('wechat');//微信定义管理

$appstyle =$this->appstyle();
$fm453resource =FM_RESOURCE;
$appsrc =fmFunc_ui_appsrc();
$shopname=$settings['brands']['shopname'];
$pagename = "商城分类";
$carttotal = fmMod_shopcart_total();
$share_user=$_GPC['share_user'];
$shareid = intval($_GPC['shareid']);//分享来源ID
$lastid = intval($_GPC['lastid']);//分享来源ID的上一个来源ID
$currentid = intval($_W['member']['uid']);//当前用户的会员ID（从mc_members表中读取）
$from_user = $_W['openid'];;
$url_condition="";

$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

$category = pdo_fetchall("SELECT * FROM " . tablename('fm453_shopping_category') . " WHERE uniacid = '{$_W['uniacid']}' and enabled=1 ORDER BY parentid ASC , displayorder DESC", array(), 'id'); //按分类顺序倒序，数字越大超靠前
foreach ($category as $index => $row) {
	if (!empty($row['parentid'])) {
		$children[$row['parentid']][$row['id']] = $row;
		unset($category[$index]);
	}
}

//自定义页面默认的分享内容
$_share = array();
$_share['title']=$this->module['config']['brands']['shopname'];
$_share['link']=$_W['siteroot'].'app'. ltrim($this->createMobileUrl('category'),'.');
$_share['link']=$_share['link'].$url_condition;

$_share['imgUrl']=$_W['attachurl'].$this->module['config']['brands']['logo'];
$_share['desc']=htmlspecialchars_decode($this->module['config']['brands']['description']);
$_share['desc']= preg_replace("/<(.*?)>/","",$_share['desc']);
if(!empty($shareid)){
	fmFunc_share_click($lastid,$shareid,$currentid,$share_user);
}
include $this->template($appstyle.$do.'/453');
