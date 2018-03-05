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
 * @remark 微信网页授权
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;
if(intval($_GPC['i'])<=0) {
	$_W['uniacid']=1;
	$_GPC['i']=1;
}
checkauth();

fm_load()->fm_func('share');//链接分享管理
fm_load()->fm_func('view'); 	//浏览量处理
fm_load()->fm_func('wechat');//微信定义管理

$appstyle = empty($settings['appstyle']) ? FM_APPSTYLE : $settings['appstyle'];
$appsrc =MODULE_URL.'template/mobile/'.$appstyle.'453/';
$htmlsrc =MODULE_URL.'template/mobile/'.$appstyle;
$fm453resource = FM_RESOURCE;

$do=$_GPC['do'];
$ac=$_GPC['ac'];
$op= !empty($_GPC['op']) ? $_GPC['op'] : 'index';
$uniacid = $_W['uniacid'];
$uid = $_W['member']['uid'];

if($op=="index"){
	//发起网页授权
	fmFunc_wechat_auth();
}
if($op=='callback'){
    $state = $_GET['state'];
	$code = $_GET['code'];	//取得授权码
	//取accesstoken
	$result = fmFunc_wechat_gettoken($code,$state);
	$access_token = $result['access_token'];
	$openid = $result['openid'];
	//取用户资料
	$result = fmFunc_wechat_getinfo($access_token,$openid);
	/*
    {    "openid":" OPENID",
" nickname": NICKNAME,
"sex":"1",
"province":"PROVINCE"
"city":"CITY",
"country":"COUNTRY",
"headimgurl":    "http://wx.qlogo.cn/mmopen/g3MonUZtNHkdmzicIlibx6iaFqAc56vxLSUfpb6n5WKSYVY0ChQKkiaJSgQ1dZuTOgvLLrhJbERQQ4eMsv84eavHiaiceqxibJxCfHe/46",
"privilege":[ "PRIVILEGE1" "PRIVILEGE2"     ],
"unionid": "o6_bmasdasdsad6_2sgVt7hMZOPfL"
}*/
	if($result){
		if(!$result['openid']){
			//openid
			fm_error("您的资料未获取完整","企业微信提示",$backurl=NULL);
		}else{
			$openid = $result['openid'];
			//取成员详情
			$detail = $result;
			$message = "系统温馨提示：";
			$message = " ";
			$message .= $detail['nickname']."，您已授权".$_W['uniaccount']['name']."获取您的信息如下："."\r\n";
			$message .= "OPENID为".$detail['openid']."\r\n";
			$message .= "性别：".$detail['sex']."\r\n";
			$message .= "头像链接".$detail['headimgurl']."\r\n";

			$msgtitle = urlencode("系统温馨提示");
			$msgbody = urlencode($message);

			$backurl=$hasnewurl=false;
			$url = fm_murl('help','tip','index',array('msg[title]'=>$msgtitle,'msg[body]'=>$msgbody,'backurl'=>$backurl,'hasnewurl'=>$hasnewurl));
			header("Location: ".$url);
			exit();
		}
	}
	if($state){
		fm_redirectUrl($state);
	}else{
		fm_murl('index','index','index',array());
	}
}
