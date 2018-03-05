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
 * @remark 接口-默认入口，发送各种通知
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;
fm_load()->fm_func('share');//链接分享管理
fm_load()->fm_func('view'); 	//浏览量处理

//加载风格模板及资源路径
$appstyle = empty($settings['appstyle']) ? FM_APPSTYLE : $settings['appstyle'];
$appsrc =MODULE_URL.'template/mobile/'.$appstyle.'453/';
$fm453resource = FM_RESOURCE;
//入口判断
$do= $_GPC['do'];
$ac=$_GPC['ac'];
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'index';
//来路计算
$share_user=$_GPC['share_user'];
$shareid = intval($_GPC['shareid']);//分享来源ID
$lastid = intval($_GPC['lastid']);//分享来源ID的上一个来源ID
$currentid = intval($_W['member']['uid']);//当前用户的会员ID（从mc_members表中读取）
$fromplatid = intval($_GPC['fromplatid']);	//来源平台（用于跨平台支付等情形）
$from_user = $_W['openid'];
$url_condition="";
$url_condition .= "&lastid=".$shareid;
$url_condition .= "&shareid=".$currentid;
$url_condition .= "&share_user=".$from_user;
$url_condition .= "&fromplatid=".$fromplatid;
//开始操作管理
$shopname=$settings['brands']['shopname'];
$shopname = !empty($shopname) ? $shopname :FM_NAME_CN;
message('感谢您光顾'.$shopname.'您访问了一个错误的网址，现在将为您跳转至商城首页',fm_murl('index','index','index',array('lastid'=>$shareid,'shareid'=>$currentid,'share_user'=>$from_user,'fromplatid'=>$frompaltid,'isshare'=>1)),'info');
return false;
