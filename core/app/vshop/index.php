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
 * @remark 积分商城
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
$appsrc =fmFunc_ui_appsrc();
$pagename = "积分商城";

$userinfo=fmFunc_fans_oauth_getInfo();//网页授权获取头像、昵称等信息；

$uniacid=$_W['uniacid'];
$share_user=$_GPC['share_user'];
$shareid = intval($_GPC['shareid']);//分享来源ID
$lastid = intval($_GPC['lastid']);//分享来源ID的上一个来源ID
$currentid = intval($_W['member']['uid']);//当前用户的会员ID（从mc_members表中读取）
$from_user = $_W['openid'];;
$url_condition="";

$award_list = pdo_fetchall("SELECT * FROM ".tablename('fm453_shopping_credit_award')." WHERE uniacid = '{$_W['uniacid']}' and NOW() < deadline and amount > 0");
$profile = fans_search($from_user);

//自定义页面默认的分享内容
$_share = array();
$_share['title']=$settings['brands']['shopname'];
$_share['link']=fm_murl($do,$ac,$operation,array('isshare'=>1));
$_share['link']=$_share['link'].$url_condition;
$_share['imgUrl']=$_W['attachurl'].$settings['brands']['logo'];
$_share['desc']=htmlspecialchars_decode($settings['brands']['description']);
$_share['desc']= preg_replace("/<(.*?)>/","",$_share['desc']);
if(!empty($shareid)){
	fmFunc_share_click($lastid,$shareid,$currentid,$share_user);
}
//include $this->template($appstyle.'award_mall');
include $this->template($appstyle.$do.'/453');
