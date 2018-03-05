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
 * @remark 企业微信授权
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
fm_load()->fm_func('wechat.corp'); //企业微信函数集

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
	fmFunc_corpwechat_oauth() ;
}
if($op=="callback"){
	$state = $_GET['state'];
	$authcode = $_GET['code'];	//取得授权码
	$result = fmFunc_corpwechat_user_info($authcode);

	if($result){
		if(!$result['UserId']){
			//无UserId，非企业成员
			fm_error("抱歉，您不是企业成员，无法使用本企业内部功能","企业微信提示",$backurl=NULL);
		}else{
			$userid = $result['UserId'];
			fmFunc_corpwechat_authcode_record($uniacid,$userid,$authcode);	//记录code,写入缓存

			//取成员详情
			$user_ticket = $result['user_ticket'];
			$detail = fmFunc_corpwechat_user_detail($user_ticket);

			/*
			   "userid":"lisi",
			   "name":"李四",
			   "department":[3],
			   "position": "后台工程师",
			   "mobile":"15050495892",
			   "gender":1,	//男
			   "email":"xxx@xx.com",
			   "avatar":"http://shp.qpic.cn/bizmp/xxxxxxxxxxx/0"
			*/
			$message = "系统温馨提示：";
			$message = " ";
			$message .= $detail['name']."，您已授权企业微信获取您的信息如下："."\r\n";
			$message .= "用户ID(userid)为".$detail['userid']."\r\n";
			$message .= "手机号为".$detail['mobile']."\r\n";
			$message .= "邮箱为".$detail['email']."\r\n";
			$message .= "头像链接".$detail['avatar']."\r\n";

			$msgtitle = urlencode("系统温馨提示");
			$msgbody = urlencode($message);

			//更新会员资料
			$info = $detail;
			$info['qywx_userid'] = $detail['userid'];
			$info['realname'] = $detail['name'];
			$info['status'] = 1;
			$uid = fmMod_member_reg($info,$sure=null);	//如果未在系统及商城会员表中注册，则完善其资料
			// $result = pdo_fetch("SELECT * FROM ".tablename('fm453_shopping_member')." WHERE uid = :uid",array(':uid'=>$uid));
			//会员登陆
			$mobile = $detail['mobile'];
			fmMod_member_login($uid,$mobile,$email=null,$password=null);
			$backurl=$hasnewurl=false;
			$url = fm_murl('help','tip','index',array('msg[title]'=>$msgtitle,'msg[body]'=>$msgbody,'backurl'=>$backurl,'hasnewurl'=>$hasnewurl));
			header("Location: ".$url);
			exit;
		}
	}
	if($state){
		fm_redirectUrl($state);
	}else{
		fm_murl('index','index','index',array());
	}
}
