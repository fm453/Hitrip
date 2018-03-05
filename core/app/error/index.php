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
 * @remark 错误提示页
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;

fm_load()->fm_func('share');//链接分享管理
fm_load()->fm_func('data');//统一数据处理方法
fm_load()->fm_func('view'); 	//浏览量处理
fm_load()->fm_func('wechat');//微信定义管理

//加载风格模板及资源路径
$appstyle = empty($settings['appstyle']) ? FM_APPSTYLE : $settings['appstyle'];
$appsrc =MODULE_URL.'template/mobile/'.$appstyle.'453/';
$fm453resource = FM_RESOURCE;
//入口判断
$do= $_GPC['do'];
$ac=$_GPC['ac'];
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'index';
//来路计算
$share_user=$_GPC['share_user'];
$shareid = intval($_GPC['shareid']);//分享来源ID
$lastid = intval($_GPC['lastid']);//分享来源ID的上一个来源ID
$currentid = intval($_W['member']['uid']);//当前用户的会员ID（从mc_members表中读取）
$fromplatid = intval($_GPC['fromplatid']);	//来源平台（用于跨平台支付等情形）
$from_user = $_W['openid'];
$url_condition="";
//初始权限判断
$_FM['settings']['force_follow']=$settings['force_follow']=FALSE;
$_FM['settings']['force_login']=$settings['force_login']=FALSE;

//开始操作管理
$shopname=$settings['brands']['shopname'];
$shopname = !empty($shopname) ? $shopname :FM_NAME_CN;

$platids=fmFunc_getPlatids();//获取平台模式关联的公众号商户ID参数
$platid=$_W['uniacid'];
$oauthid=$platids['oauthid'];
$fendianids=$platids['fendianids'];
$supplydianids=$platids['supplydianids'];
$blackids=$platids['blackids'];

$backurl = $_GPC['backurl'];
$backurl = urldecode($backurl);
$msg = $_GPC['msg'];
if(empty($msg['title'])){
	$msg['title'] = '呀，报错了！';
}else{
	$msg['title'] = urldecode($msg['title']);
}
if(empty($msg['body'])){
	$msg['body'] = '未知的错误信息';
}else{
	$msg['body'] =urldecode($msg['body']);
}

//视图渲染前完成总权限判断
if($settings['force_login']) {
	checkauth();
}
if($settings['force_follow']) {
	checkfollow($FM_member);
}

//细分操作
if($operation=='index') {
	$pagename = $shopname.'信息提示';
	$pagename .='|'.$_W['account']['name'];
	//包含的模板文件
	include $this->template($appstyle.$do.'/453');
}
elseif($operation=='feedback') {
	//提交反馈
	$reportUrl=fm_murl('error','index','index',array('msg[title]'=>$msg['title'],'msg[body]'=>$msg['body']));
	$pagename = $shopname.'测试报错信息反馈';
	$pagename .='|'.$_W['account']['name'];
	$errorinfo = $_GPC['question'];
	$uid = $_GPC['uid'];
	$contact = $_GPC['contact'];
	$WeAccount = fmFunc_wechat_weAccount();
	require_once MODULE_ROOT.'/core/msgtpls/tpls/task.php';
	require_once MODULE_ROOT.'/core/msgtpls/msg/task.php';
	//发任务处理通知
	$postdata = $tplnotice_data['task']['errorreport']['admin'];
	$result=array();
	$result[0]=fmMod_notice_tpl($postdata, $platid=NULL, $WeAccount);
	$postdata = $tplnotice_data['task']['errorreport']['reporter'];
	$result[1]=fmMod_notice_tpl($postdata, $platid=NULL, $WeAccount);
	if(!$result[0]) {
		//给管理员发个微信消息
		$postdata = $notice_data['errorreport']['admin'];
		$result[0]=fmMod_notice($settings['manageropenids'],$postdata);
	}
	$return=0;
	foreach($result as $res){
		if($res){
			$return +=1;
		}
	}
	return $return;
}
