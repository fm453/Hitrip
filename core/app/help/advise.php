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
 * @remark 意见建议提交
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;
fm_load()->fm_func('share');//链接分享管理
fm_load()->fm_func('view'); 	//浏览量处理
fm_load()->fm_func('wechat');//微信定义管理

//加载风格模板及资源路径
$appstyle = empty($settings['appstyle']) ? FM_APPSTYLE : $settings['appstyle'];
$appsrc =MODULE_URL.'template/mobile/'.$appstyle.'453/';
$htmlsrc =MODULE_URL.'template/mobile/'.$appstyle;
$fm453resource = FM_RESOURCE;
//入口判断
$do= $_GPC['do'];
$ac=$_GPC['ac'];
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'index';
//开始操作管理
$shopname=$settings['brands']['shopname'];
$shopname = !empty($shopname) ? $shopname :FM_NAME_CN;
$pagename = '投诉及建议';
$pagename .='|'.$_W['account']['name'];

$uniacid=$_W['uniacid'];
$plattype=$settings['plattype'];
$platids= fmFunc_getPlatids();//获取平台模式关联的公众号商户ID参数
$platid=$_W['uniacid'];
$oauthid=$platids['oauthid'];
$fendianids=$platids['fendianids'];
$supplydianids=$platids['supplydianids'];
$blackids=$platids['blackids'];

//平台模式处理
require_once FM_PUBLIC.'plat.php';
$supplydians=explode(',',$supplydianids);//字符串转数组
$supplydians=array_filter($supplydians);//数组去空

//按平台模式前置筛选条件
$condition=' WHERE ';
$params=array();
require_once FM_PUBLIC.'forsearch.php';

$share_user=$_GPC['share_user'];
$shareid = intval($_GPC['shareid']);
$lastid = intval($_GPC['lastid']);
$currentid = intval($_W['member']['uid']);
$fromplatid = intval($_GPC['fromplatid']);
$from_user = $_W['openid'];
$url_condition="";
$direct_url = fm_murl($do,$ac,$operation,array());

//自定义微信分享内容
$_share = array();
$_share['title'] = $settings['brands']['shopname'];
$_share['link'] = fm_murl($do,$ac,$operation,array('isshare'=>1));
$_share['link'] = $_share['link'].$url_condition;
$_share['imgUrl'] = tomedia($settings['brands']['logo']);
$_share['desc'] = $settings['brands']['share_des'];

if($operation=='index') {
	$info = $_GPC['question'];
	$stars = $_GPC['stars'];
	$contact = $_GPC['contact'];
	$qrcodeurl=$_W['siteroot']. 'attachment/qrcode_'.$_W['acid'].'.jpg';//从公众号的系统配置中提取二维码图片
	if(!empty($shareid)){
		fmFunc_share_click($lastid,$shareid,$currentid,$share_user);
	}
	//包含的模板文件
	include $this->template($appstyle.$do.'/453');
}
elseif($operation=='feedback') {
	//提交反馈
	$uid = $_GPC['uid'];
	$info = $_GPC['question'];
	$stars = $_GPC['stars'];
	$contact = $_GPC['contact'];
	$WeAccount = fmFunc_wechat_weAccount();
	require_once MODULE_ROOT.'/core/msgtpls/tpls/task.php';
	//发任务处理通知
	$result=array();
	$postdata = $tplnotice_data['task']['feedback']['admin'];
	$result=fmMod_notice_tpl($postdata, $platid=NULL, $WeAccount);
	if($result['errno']==-1) {
		require_once MODULE_ROOT.'/core/msgtpls/msg/task.php';
		$noticedata = $notice_data['feedback']['help']['admin'];
		$result=fmMod_notice($settings['manageropenids'],$noticedata,$platid=NULL, $WeAccount);
	}

	$postdata = $tplnotice_data['task']['feedback']['reporter'];
	fmMod_notice_tpl($postdata, $platid=NULL, $WeAccount);

	return $return;
}
