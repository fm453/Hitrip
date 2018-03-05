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
 * @remark	企业微信类
 */
defined('IN_IA') or exit('Access Denied');
define('FM_QYAPI_WECHAT_OAUTHCODE_EXPIRE',300);	//企业微信API网页授权返回码的有效期
load()->func('communication');

class WorkWeiXinAccount{
	protected $account = null;
	protected $corpid = null;	//企业ID
	protected $corpsecret = null;	//应用的凭证密钥

	/*传入企业微信基本信息*/
	//__construct()方法到实例化时自动加载function
	public function __construct($account = array()) {
		if (empty($account)) {
			return true;
		}
		$this->account = $account;
		if(empty($this->account['corpid'])) {
			trigger_error('未传入有效的企业ID(corpid)；类函数： ' . __CLASS__, E_USER_WARNING);
		}elseif(empty($this->account['corpsecret'])) {
			trigger_error('未传入有效的应用凭证密钥(corpsecret)；类函数： ' . __CLASS__, E_USER_WARNING);
		}
		$this->corpid = $account['corpid'];
		$this->corpsecret = $account['corpsecret'];
		$this->agentid = $account['agentid'];
	}

	//__call()方法用来获取没有定义的function
	public function __call($name, $param) {
		return;
	}

	//__get()得到私有变量值
	public function __get($name) {
		if (isset ($this-> $name)) {
			return ($this-> $name);
		} else {
			return null;
		}
	}

	//__set()方法用来设置私有变量值
	public function __set($name, $value) {
		$this-> $name = $value;
	}

	//__unset()方法删除私有变量值
	public function __unset($name) {
		unset ($this-> $name);
	}

	/*获取access_token*/
	/* 服务器返回
	// "errcode":0，
	// "errmsg":""，
	// "access_token": "accesstoken000001",
	// "expires_in": 7200
	/* 缓存取出
	// "token":"",
	// "expire":""
	*/
	public function getAccessToken() {
		$cachekey = md5("fm_accesstoken:{$this->account['corpid']}");
		$cache = cache_load($cachekey);
		if (!empty($cache) && !empty($cache['token']) && $cache['expire'] > TIMESTAMP) {
			$this->account['access_token'] = $cache;
			return $cache['token'];
		}
		if (empty($this->account['corpid']) || empty($this->account['corpsecret'])) {
			return error('-1', '未填写企业号的 corpid 或 corpsecret！');
		}
		$url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid={$this->account['corpid']}&corpsecret={$this->account['corpsecret']}";
		$content = ihttp_get($url);
		if(is_error($content)) {
			return error('-1', '获取微信企业号授权失败, 请稍后重试！错误详情: ' . $content['errmsg']);
		}
		if (empty($content['content'])) {
			return error('-1', 'AccessToken获取失败，请检查corpid和corpsecret的值是否与微信企业号一致！');
		}
		$token = @json_decode($content['content'], true);
		if(empty($token) || !is_array($token) || empty($token['access_token']) || empty($token['expires_in'])) {
			$errorinfo = substr($content['meta'], strpos($content['meta'], '{'));
			$errorinfo = @json_decode($errorinfo, true);
			return error('-1', '获取微信公众号授权失败, 请稍后重试！ 公众平台返回原始数据为: 错误代码-' . $errorinfo['errcode'] . '，错误信息-' . $errorinfo['errmsg']);
		}
		$record = array();
		$record['token'] = $token['access_token'];
		$record['expire'] = TIMESTAMP + $token['expires_in'] - 200;
		$this->account['access_token'] = $record;
		cache_write($cachekey, $record);
		return $record['token'];
	}

	/*类实例后重组的账号信息*/
	public function getAccount(){
		return $this->account;
	}

	/*移动端网页授权*/
	/*跳转生成授权网址,取回code*/
	public function AppOAuthCode($state){
		$corpid = $this->corpid;
		$corpsecret = $this->corpsecret;
		$redirect_url = urlencode(fm_murl('workweixin','oauth2','callback',array()));	//源$redirect_url为原始网址
		$response_type = 'code';
		$scope = 'snsapi_privateinfo';	//手动授权，可获取成员的详细信息，包含手机、邮箱。
		$agentid = $this->agentid;
		$state = empty($state) ? " CALLBACK" : $state;
		$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$corpid}&redirect_uri={$redirect_url}&response_type=code&scope={$scope}&agentid={$agentid}&state={$state}#wechat_redirect";
		header('Location:'.$url);
		return;
	}

	/*根据code获取用户信息*/
	public function getUserInfo($code){
		$access_token = self::getAccessToken();
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

	/*使用user_ticket获取成员详情*/
	public function getUserDetail($user_ticket){
		$access_token = self::getAccessToken();
		$url = "https://qyapi.weixin.qq.com/cgi-bin/user/getuserdetail?access_token={$access_token}";
		$postdata = array("user_ticket"=>$user_ticket);
		$postdata = json_encode($postdata); //必须json处理再post
		$content = ihttp_post($url,$postdata);

		if(is_error($content)) {
			return error('-1', '获取用户详情失败, 请稍后重试！错误详情: ' . $content['errmsg']);
		}
		if (empty($content['content'])) {
			return error('-1', '用户详情获取失败！');
		}
		$info = @json_decode($content['content'], true);
		if(empty($info) || !is_array($info)) {
			$errorinfo = substr($content['meta'], strpos($content['meta'], '{'));
			$errorinfo = @json_decode($errorinfo, true);
			return error('-1', '获取用户详情失败，返回原始数据为: 错误代码-' . $errorinfo['errcode'] . '，错误信息-' . $errorinfo['errmsg']);
		}
		if($info['errcode']>0){
		    return error('-1', '获取用户详情失败，返回原始数据为: 错误代码-' . $errorinfo['errcode'] . '，错误信息-' . $errorinfo['errmsg']);
		}
		unset($info['errcode']);
		unset($info['errmsg']);
		return $info;
	}
}
