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
 * @remark：企业微信调用处理函数
 */
defined('IN_IA') or exit('Access Denied');

fm_load()->fm_class('workweixin.account'); //企业微信定义管理

//发起企业微信网页授权
function fmFunc_corpwechat_oauth() {
	global $_W;
	global $_FM;
	$settings =  $_FM['settings'];
	$WorkWeiXinAccount = array('corpid'=>$settings['api']['workweixin_corpid'],'corpsecret'=>$settings['api']['workweixin_corpsecret'],'agentid'=>$settings['api']['workweixin_agentid']);
    $workweixin = new  WorkWeiXinAccount($WorkWeiXinAccount);
    $state = fm_redirectUrl('','in',$urls=array());
    $workweixin->AppOAuthCode($state);
}

//记录企业微信授权码
function fmFunc_corpwechat_authcode_record($uniacid,$userid,$code){
	$cachekey = md5("fm_oauthcode:{$uniacid}_{$userid}");
	$record = array();
	$record['code'] = $code;
	$record['expire'] = TIMESTAMP + FM_QYAPI_WECHAT_OAUTHCODE_EXPIRE - 30;
	cache_write($cachekey, $record);
}

//取企业微信授权码
function fmFunc_corpwechat_authcode($uniacid,$userid){
	$cachekey = md5("fm_oauthcode:{$uniacid}_{$userid}");
	$cache = cache_load($cachekey);
	if (!empty($cache) && !empty($cache['code']) && $cache['expire'] > TIMESTAMP) {
		return $cache['code'];
	}
	return false;
}

//获取企业微信成员基本信息
function fmFunc_corpwechat_user_info($code){
	global $_W;
	global $_FM;
	$settings =  $_FM['settings'];
	$WorkWeiXinAccount = array('corpid'=>$settings['api']['workweixin_corpid'],'corpsecret'=>$settings['api']['workweixin_corpsecret'],'agentid'=>$settings['api']['workweixin_agentid']);
    $workweixin = new  WorkWeiXinAccount($WorkWeiXinAccount);
	$info = $workweixin->getUserInfo($code);
	return $info;
}

//取企业微信成员详情
function fmFunc_corpwechat_user_detail($user_ticket){
	global $_W;
	global $_FM;
	$settings =  $_FM['settings'];
	$WorkWeiXinAccount = array('corpid'=>$settings['api']['workweixin_corpid'],'corpsecret'=>$settings['api']['workweixin_corpsecret'],'agentid'=>$settings['api']['workweixin_agentid']);
    $workweixin = new  WorkWeiXinAccount($WorkWeiXinAccount);
    $detail = $workweixin->getUserDetail($user_ticket);
    return $detail;
}

//企业微信浏览器判断
function fmFunc_corpwechat_is() {
	if (strpos($_SERVER['HTTP_USER_AGENT'], 'wxwork') === true) {
		return TRUE;
	}
	return FALSE;
}

//TBD
//判断是否企业微信内部成员
function fmFunc_corpwechat_checkfollow($code){
	global $_W, $_GPC,$_FM;
	$settings =  $_FM['settings'];
	$WorkWeiXinAccount = array('corpid'=>$settings['api']['workweixin_corpid'],'corpsecret'=>$settings['api']['workweixin_corpsecret'],'agentid'=>$settings['api']['workweixin_agentid']);
    $workweixin = new  WorkWeiXinAccount($WorkWeiXinAccount);

	$access_token = $workweixin::getAccessToken();
	$code = $workweixin::AppOAuthCode($state);
		$url = "https://qyapi.weixin.qq.com/cgi-bin/user/getuserinfo?access_token={$access_token}&code={$code}";
		$content = ihttp_get($url);
		if(is_error($content)) {
			return error('-1', '获取用户信息失败, 请稍后重试！错误详情: ' . $content['errmsg']);
		}
		if (empty($content['content'])) {
			return error('-1', '用户获取失败！');
		}
		$info = @json_decode($content['content'], true);
		if(empty($info) || !is_array($info)) {
			$errorinfo = substr($content['meta'], strpos($content['meta'], '{'));
			$errorinfo = @json_decode($errorinfo, true);
			return error('-1', '获取用户信息失败，返回原始数据为: 错误代码-' . $errorinfo['errcode'] . '，错误信息-' . $errorinfo['errmsg']);
		}
		if($info['errcode']>0){
		    return error('-1', '获取用户详情失败，返回原始数据为: 错误代码-' . $errorinfo['errcode'] . '，错误信息-' . $errorinfo['errmsg']);
		}
		unset($info['errcode']);
		unset($info['errmsg']);
		return $info;
}


/*以下未完成或拟删除*/
//利用系统wxaccount类取得公号函数
function fmFunc_corpwechat_weAccount($platid=NULL){
	global $_GPC;
	global $_W;
	global $_FM;
	load() -> model('account');
	$uniacid = ($platid>0) ? $platid : $_W['uniacid'];
	$acid = pdo_fetchcolumn("SELECT acid FROM " . tablename('account_wechats') . " WHERE `uniacid`=:uniacid LIMIT 1", array(':uniacid' => $uniacid));
	$WeAccount = WeAccount::create($acid);
	return $WeAccount;
}

//取指定公众号的accesstoken值
function fmFunc_corpwechat_getAccessToken($platid=NULL,$type=NULL,$WeAccount=NULL){
	global $_GPC;
	global $_W;
	global $_FM;
	$type = !empty($type) ? $type : 'wechat';
	if($type=='wechat') {
		if(!$WeAccount){
			load() -> model('account');
			$uniacid = ($platid>0) ? $platid : $_W['uniacid'];
			$acid = pdo_fetchcolumn("SELECT acid FROM " . tablename('account_wechats') . " WHERE `uniacid`=:uniacid LIMIT 1", array(':uniacid' => $uniacid));
			$WeAccount = WeAccount::create($acid);
		}
		if(!$WeAccount){
			return FALSE;
		}
		$token = $WeAccount ->getAccessToken();
		return $token;
	}
}
