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
 * @remark 接口-返回URL
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;

fm_load()->fm_func('share');//链接分享管理
fm_load()->fm_func('view'); 	//浏览量处理

//入口判断
$do= $_GPC['do'];
$ac=$_GPC['ac'];
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'index';
//来路计算
$share_user=$_GPC['share_user'];
$shareid = intval($_GPC['shareid']);//分享来源ID
$lastid = intval($_GPC['lastid']);//分享来源ID的上一个来源ID
$currentid = intval($_W['member']['uid']);//当前用户的会员ID
$fromplatid = intval($_GPC['fromplatid']);	//来源平台（用于跨平台支付等情形）
$from_user = $_W['openid'];

//开始操作管理

if($op=='index'){
	$newurl = fm_murl($do=NULL,$ac=NULL,$op=NULL,$addons=NULL);
}
echo json_encode($newurl);
die();
