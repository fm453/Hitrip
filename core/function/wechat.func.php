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
 * @remark：微信调用处理函数
 */
defined('IN_IA') or exit('Access Denied');

//微信浏览器判断
function fmFunc_wechat_is() {
	if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') === true) {
		return TRUE;
	}
	return FALSE;
}

//判断是否关注
function fmFunc_wechat_checkfollow($openid,$redirct=null){
	global $_W, $_GPC,$_FM;
	$settings['follow_ur']=$_FM['settings']['follow_url'];
	$follow_url = !empty($settings['follow_ur']) ? $settings['follow_ur'] : $_W['uniaccount']['subscribeurl'];
	$openid= !($openid) ? $_W['openid'] : $openid;
	if(!$openid){
		checkauth();
		$openid = $_W['openid'];
	}
	$auth_uniacid = $_FM['settings']['plat']['oauthid'];
	if($auth_uniacid !=$_W['uniacid']){
		$auth_data = 	pdo_get('mc_mapping_fans', array('openid' => $openid, 'uniacid' => $auth_uniacid));
		$fan = pdo_get('mc_mapping_fans', array('nickname' => $auth_data['nickname'], 'uniacid' => $_W['uniacid']));
	}else{
		$fan = $_W['fans'];
	}

	$isfollow = false;
	if(is_array($fan)){
		if($fan['follow'] ==1) {
			$isfollow = true;
		}
	}
	if(!$isfollow && $redirct) {
		$history_url = json_encode(urlencode($redirct));
		$follow_url = fm_murl('followus','index','index',array('history_url'=>$history_url));
		header("Location: ".$follow_url);
		exit;
	}else{
		return json_encode($isfollow);
	}
}

//利用系统wxaccount类取得公号函数
function fmFunc_wechat_weAccount($platid=NULL){
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
function fmFunc_wechat_getAccessToken($platid=NULL,$type=NULL,$WeAccount=NULL){
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

//发起微信网页授权(仅认证服务号有效)
function fmFunc_wechat_auth(){
    global $_GPC;
	global $_W;
	global $_FM;
	$cache_key = $_W['openid'].'_'.$_W['uniacid'];
	$result = cache_load($cache_key);
	if($result){
		return $result;
	}
	$state = fm_redirectUrl('','in',$urls=array());
	$state = empty($state) ? " CALLBACK" : $state;
	$REDIRECT_URI = urlencode(fm_murl('weixin','oauth2','callback',array()));	//源$redirect_url为原始网址
	$response_type = 'code';
	$scope = 'snsapi_userinfo';	//手动授权，拿到昵称、性别、所在地等信息。
	$APPID = $_W['uniaccount']['key'];
	$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$APPID."&redirect_uri=".$REDIRECT_URI."&response_type=".$response_type."&scope=".$scope."&state=".$state."#wechat_redirect";
	header('Location:'.$url);
	exit();
}

//取微信网页授权返回的token
function fmFunc_wechat_gettoken($code,$state){
    global $_GPC;
	global $_W;
	global $_FM;
	load()->func('communication');
    $APPID = $_W['uniaccount']['key'];
    $SECRET = $_W['uniaccount']['secret'];
    $scope = 'snsapi_userinfo';	//手动授权，拿到昵称、性别、所在地等信息。
    $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$APPID."&secret=".$SECRET."&code=".$code."&grant_type=authorization_code";
    $result = ihttp_get($url);
    $content = json_decode($result['content'], JSON_UNESCAPED_UNICODE);
    /*
    { "access_token":"ACCESS_TOKEN",
"expires_in":7200,
"refresh_token":"REFRESH_TOKEN",
"openid":"OPENID",
"scope":"SCOPE" }
    */
    return $content;
}

//取网页授权返回的用户信息
function fmFunc_wechat_getinfo($access_token,$openid){
    global $_GPC;
	global $_W;
	global $_FM;
	load()->func('communication');
    $url = "https://api.weixin.qq.com/sns/userinfo?access_token=".$access_token."&openid=".$openid."&lang=zh_CN";
    $result = ihttp_get($url);
    $content = json_decode($result['content'], JSON_UNESCAPED_UNICODE);
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
    return $content;
}