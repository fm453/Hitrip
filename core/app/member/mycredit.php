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
 * @remark 积分管理
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;
load()->func('tpl');
fm_load()->fm_func('share');//链接分享管理
fm_load()->fm_func('view'); 	//浏览量处理
fm_load()->fm_func('wechat');//微信定义管理

$appstyle =$this->appstyle();
$fm453resource =FM_RESOURCE;
$appsrc =fmFunc_ui_appsrc();
$shopname=$settings['brands']['shopname'];
$pagename = "";//定义页面标题

$uniacid=$_W['uniacid'];
$award_list = pdo_fetchall("SELECT * FROM ".tablename('fm453_shopping_credit_award')." as t1,".tablename('fm453_shopping_credit_request')."as t2 WHERE t1.award_id=t2.award_id AND from_user='".$from_user."' AND t1.uniacid = '{$_W['uniacid']}' ORDER BY t2.createtime DESC");
$profile = fans_search($from_user);
$user = pdo_fetchall('SELECT * FROM '.tablename('fm453_shopping_member')." WHERE  uniacid = :uniacid  AND from_user = :from_user" , array(':uniacid' => $_W['uniacid'],':from_user' => $from_user));

include $this->template($appstyle.$do.'/453');
