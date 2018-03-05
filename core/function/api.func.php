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
 * @remark：数据接口API站连接函数
 */
defined('IN_IA') or exit('Access Denied');

load()->func('communication');
load()->func('file');

//定义服务器
function fmFunc_api_server(){
	global $_FM;
	global $_GPC;
	$username = $_FM['settings']['shouquan']['suapi'];
	$password = $_FM['settings']['shouquan']['susecret'];
	$code = $_FM['settings']['shouquan']['sufm453code'];

	if($_FM['settings']['api']) {
		$username = !empty($_FM['settings']['api']['dacms_username']) ? $_FM['settings']['api']['dacms_username'] : $username;
		$password = !empty($_FM['settings']['api']['dacms_password']) ? $_FM['settings']['api']['dacms_password'] : $password;
		$code = !empty($_FM['settings']['api']['dacms_code']) ? $_FM['settings']['api']['dacms_code'] : $code;
	}

	$username = !empty($username) ? $username : 'publicapi';
	$password = !empty($password) ? $password : 'api.hiluker';
	$code = !empty($code) ? $code : 'public';
	$server = array(
		'http' => 'http://',	//API站点http协议头
		'host' => 'api.hiluker.com', //API站点域名
		//'http' => 'http://',	//API站点http协议头
		//'host' => 'api.yii.lukegzs.com', //API站点域名
		'username' => $username, //登陆API站点的用户名
		'password' => $password, //登陆API站点的密码
		'code' => $code, //登陆API站点的授权码
	);
	return $server;
}

//登陆服务器,返回登陆COOKIE，登记accesstoken缓存
/*
@refreshToken 是否刷新token（传入后，服务侧将刷新token；否则，将已有token返回本地，可方便本地刷新token而不影响同一账号的其他登陆）；默认不刷新
*/
function fmFunc_api_login($refreshToken=null){
	global $_FM;
	global $_W;
	global $_GPC;
	$server = fmFunc_api_server();
	//登陆地址（必须携带api标识参数）
	$loginurl = $server['http'].$server['host'].'/index.php?r=oss/login&api='.$_SERVER['HTTP_HOST'];
	if($refreshToken) {
		$loginurl .='&refreshToken=1';
	}
	//附加表单数据 用户名和密码
	$post = array(
		'LoginForm[username]' => $server['username'],
		'LoginForm[password]' => $server['password'],
		'LoginForm[rememberMe]' => 1
	);

	foreach($post as $k => $v){
		$loginurl .= '&'.urlencode($k).'='.urlencode($v);
	}
	$response = ihttp_get($loginurl);

	$result = json_decode($response['content'],true);

	if($result['errorcode']>0) {
		return $result;
		//有错误时
	}else{

		if(is_string($result['data'])){
			//保存换得的accesstoken
			$AccessToken = $result['data'];
			$cache_key = FM_NAME.'_'.$_W['uniacid'].'_'.'api[AccessToken]';
			cache_write($cache_key,$AccessToken);
		}

		//登录成功后，把返回的cookie\token信息存储过来
		$cookie = $response['headers']['Set-Cookie'];
		if(!$cookie){
			//无coookie返回时不记录（接口站后期可能都不返回cookie）
		}elseif(is_array($cookie)){
			$_FM['api']['loginCookie'] = $cookie;
			$temp = array();
			$temp2 = '';
			foreach($cookie as $v){
				$temp[] = urldecode($v);
				$temp2 .= '||'. urldecode($v);
			}
			$cookie = $temp2;
			isetcookie (FM_NAME.'_'.$_W['uniacid'].'_'.'api[loginCookie]', $cookie, 3600*24*30);

			//同时把登陆缓存写到cookie中，方便app/手机端(前台用户)调用判断
			$cache_key = FM_NAME.'_'.$_W['uniacid'].'_'.'api[loginCookie]';
			cache_write($cache_key,$temp);

			//返回登陆Cookie数组
			return $temp;
		}else{
			//未返回有效的数据
			$isdebug = FM_DEBUG;
			if(!FM_API_DEBUG){
				$isdebug = false;
			}elseif(!FM_APP_DEBUG && $_FM['isMobile']){
				$isdebug = false;
			}
			if($isdebug){
				print_r($response['content']);
			}
			return false;
		}
	}
}

//取与API通信的accesstoken
/*
@refresh 是否刷新
*/
function fmFunc_api_AccessToken($refresh=null){
	global $_W;
	$cache_key = FM_NAME.'_'.$_W['uniacid'].'_'.'api[AccessToken]';
	if($refresh){
		fmFunc_api_login($refresh);
		$AccessToken = cache_load($cache_key);
		return $AccessToken;
	}
	$AccessToken = cache_load($cache_key);
	if(!$AccessToken) {
		fmFunc_api_login();
		$AccessToken = cache_load($cache_key);
	}
	return $AccessToken;
}

//推送数据
/*
@get			取数据列表
@detail		取单条数据的完整详情
@save			保存数据
*url API接口地址
*data 传送的数据 POST
*urladdon 地址附加数据 GET
*$isread 是否属于只读行为（是，则直接从缓存的数据文件中取数据，减少不必要的API连接请求）;优先级为最高
*$withcount 是否携带统计数
*/
function fmFunc_api_push($url, $data=array(), $urladdon=null, $isread=null,$withcount=null){
	global $_FM;
	global $_W;
	global $_GPC;
	$START_TIME = time();
	$server = fmFunc_api_server();
	$_tempUrl = $url;	//传入的该url同时作为缓存cache及接口数据cache的键值依据
	$_tempUrl = str_replace('/index.php?r=','',$_tempUrl);

	//设置缓存键值(公众号_请求控制器_各sn_页面控制器ac)
	$cache_key = FM_NAME.$_W['uniacid'].'_'.$_tempUrl;	//当前请求操作对应的键

	$issave = strpos($_tempUrl,'save');	//参数中包含save时，为保存动作
	if($issave !== false){
		$issave  = true;
	}

	//拆解接口请求地址，构造对应的取数据列表(get)与取数据详情(detail)动作的缓存键值
	$_tempUrl = str_replace('/','_',$_tempUrl);
	$_tempUrls = explode('_',$_tempUrl);	//拆分路径成数组(2个键)
	$get_cache_key = FM_NAME.$_W['uniacid'].'_'.$_tempUrls[0].'/get';		//get取数据的缓存键值
	$detail_cache_key = FM_NAME.$_W['uniacid'].'_'.$_tempUrls[0].'/detail';		//detail取数据的缓存键值

	//对各个键进行附参
	$key_addons = array('sn','s_sn','f_sn','o_sn');	//POST的数据中包含这些键时，提出对应的值追加到缓存键
	foreach($key_addons as $k){
		if(isset($data[$k])) {
			$cache_key .= '_'.$data[$k];
			$get_cache_key .= '_'.$data[$k];
			$detail_cache_key .= '_'.$data[$k];
		}
	}
	$key_addons = array('ac');	//地址附加数据中包含这些键时，提出对应的值追加到缓存键
	foreach($key_addons as $k){
		if(isset($urladdon[$k])) {
			$cache_key .= '_'.$urladdon[$k];
			$get_cache_key .=  '_'.$urladdon[$k];
			$detail_cache_key .= '_'.$urladdon[$k];
		}
	}
	//由于微擎数据库设置的原因，限制长度在50字节内, 故此对之进行md5加密
	$origin_cache_key = $cache_key;
	$cache_key = md5($cache_key);
	$get_cache_key = md5($get_cache_key);
	$detail_cache_key = md5($detail_cache_key);

	//如果是读操作，直接读缓存数据文件
	if($isread){
		$filename = $cache_key;
		return fmFunc_api_cache_load($filename);
  }

	//后台类型的操作，进一步处理与判断
	$url = $server['http'].$server['host'].$url;
	$nowTime = $_W['timestamp'];

	//是否使用缓存的设定
	$cacheable = ($issave==true) ? false : true;
	if(isset($urladdon['nocache']) && $urladdon['nocache']==1){
		$cacheable = false;	//附加参数指定了不要缓存
	}
	if($_GPC['nocache']){
		$cacheable = false;	//直接指定了不要缓存（如在网址中添加nocache参数）
	}
	if($_FM['isWeb']){
		$cacheable = false;    //后台操作时，不要缓存
  }

  //当前执行的是保存动作时
	if($issave){
		//逻辑：数据发送至服务端，并立即从服务端取得完整详细数据并写入缓存文件
		//TBD

	}

	//加载登陆API站的accesstoken,将连同站点账号推送至服务器
	$AccessToken = fmFunc_api_AccessToken();

	//如果当前请求url对应的accesstoken有效，且允许使用缓存值时，调用之
	if($cacheable && !empty($AccessToken)){
		$cache = cache_load($cache_key);
		return $cache;
	}

	//设置头部信息
	$headers = array();
	//Content-Type如果有设置了，会导致POST的数据无法获取
	//$headers[] = 'Content-Type:application/x-www-form-urlencoded';	//传送的是字符串
	//$headers[] = 'Content-Type:multipart/form-data';	//传送的是二维数组

	$headers[] = 'Expect:'; //头部要送出'Expect: ',以解决curl首次请求过慢的问题
	$extra = array(
		'CURLOPT_REFERER' => 'http://api.hiluker.com/',
		'CURLOPT_HTTPHEADER' => $headers,
		'CURLOPT_PROXY' => '',  //不使用代理
		'CURLOPT_HTTP_VERSION' => 'CURL_HTTP_VERSION_1_0', //强制协议为1.0
		'CURLOPT_IPRESOLVE' => 'CURL_IPRESOLVE_V4',	//强制使用IP4协议
		//'CURLOPT_COOKIE' => implode(';', $cookie)
	);

	//优先将登陆数据写入要GET的数据中（避免url过长出现意外）
	$urlcondition1 = '';
	$getData = array();
	$getData['appid'] = $server['username'];
	$getData['sceret'] = $server['code'];
	$getData['accesstoken'] = $AccessToken;

	foreach($getData as $k => $v){
		$urlcondition1 .= '&'. urlencode($k).'='. urlencode($v);
	}
	$_AccessToken = $AccessToken;
	//将其他附加参数写入GET数据中(踢出登陆参数)
	$urlcondition2 = '';
	$getData = $urladdon;
	unset($getData['appid']);
	unset($getData['sceret']);
	unset($getData['accesstoken']);

	foreach($getData as $k => $v){
		$urlcondition2 .= '&'. urlencode($k).'='. urlencode($v);
	}

	//处理POST数据
	foreach($data as $k => &$v){
		$v = json_encode($v);	//(POST的data各键值需是string)
	}
	unset($v);

	//向接口站发送请求
	$response = ihttp_request($url.$urlcondition1.$urlcondition2, $data, $extra);
	$result = json_decode($response['content'], true);
	if($result['errorcode']==45300201){
		//未登陆服务器时(accesstoken不匹配时)
		$cookie = fmFunc_api_login();
		if(!empty($cookie)){
			$headers = array();
			$extra = array(
				'CURLOPT_REFERER' => 'http://api.hiluker.com/',
				'CURLOPT_HTTPHEADER' => $headers,
				//'CURLOPT_COOKIE' => implode(';', $cookie)
			);
			$AccessToken = fmFunc_api_AccessToken();
			$urlcondition1 = str_replace('accesstoken='.$_AccessToken,'accesstoken='.$AccessToken,$urlcondition1);
			$response = ihttp_request($url.$urlcondition1.$urlcondition2, $data, $extra);
			$result = json_decode($response['content'], true);
		}else{
			//未返回有效的数据
			if((FM_DEBUG && FM_API_DEBUG) || $_GPC['debug']){
				print_r($response['content']);
			}
			return false;
		}
	}

	//继续过滤返回数据
	if(!isset($result['errorcode'])) {
		if (isset($urladdon['test']) && $urladdon['test']==true){
			return $result;
		}else{
			//未返回有效的数据
			if((FM_DEBUG && FM_API_DEBUG) || $_GPC['debug']){
				print_r($response['content']);
			}
			return false;
		}
	}elseif($result['errorcode']>0){
			if(FM_DEBUG || $_GPC['debug']){
				$error_msg= $result['msg'];
				trigger_error($error_msg);
			}
			return false;
		}else{
			$list = $result['data'];
            if($withcount){
                $list['total']=$result['total'];
            }
			fmFunc_api_cache_save($cache_key,$list,$url.$urlcondition,$origin_cache_key);
			//写缓存
			cache_write($cache_key,$list);
			//开发调试时查看执行时间
			$isdebug = FM_DEBUG;
			if(!FM_API_DEBUG){
				$isdebug = false;
			}elseif(!FM_APP_DEBUG && $_FM['isMobile']){
				$isdebug = false;
			}
			if($issave){
				$isdebug = false;
			}
			if($_GPC['debug']){
				$isdebug = false;
			}
			$isdebug = false;
			if($isdebug){
				$END_TIME = time();
				print_r('接口耗时<span style="color:red;">'.$result['timeused'].'</span>毫秒;');
				print_r('通讯耗时<<span style="color:red;">'.($END_TIME-$START_TIME+1).'</span>秒;');
				print_r('云端数据共计<span style="color:red;">'.$result['total'].'</span>条;');
				print_r('此次传输数据量为<span style="color:red;">'.$result['bytes'].'</span>bytes;');
			}
			return $list;
		}
	return false;
}

/*更新数据缓存,主要配合push接口使用，其他情况下使用可以直接通过nocache附参实现 tbd
* @url push函数中的直接url
* @urlcondition push接口中根据GET参数转换的附加url
* @extra 缓存附加数组
*/
function fmFunc_api_cache_refresh($url,$urlcondition, $data){
	global $_FM;
	global $_W;
	global $_GPC;
	//调用登陆缓存COOKIE对应的头部信息
	$headers = array();
	$headers[] = 'Expect:'; //头部要送出'Expect: ',以解决curl首次请求过慢的问题
	$extra = array(
		'CURLOPT_REFERER' => 'http://api.hiluker.com/',
		'CURLOPT_HTTPHEADER' => $headers,
		'CURLOPT_PROXY' => '',  //不使用代理
		'CURLOPT_HTTP_VERSION' => 'CURL_HTTP_VERSION_1_0', //强制协议为1.0
		'CURLOPT_IPRESOLVE' => 'CURL_IPRESOLVE_V4',	//强制使用IP4协议
//		'CURLOPT_COOKIE' => implode(';', $cookie)
	);


	//向接口站发送请求
	$response = ihttp_request($url.$urlcondition, $data, $extra);
	$result = json_decode($response['content'], true);
	$list = $result['data'];
	fmFunc_api_cache_save($cache_key,$list);
}

//将数据缓存写入指定文件
function fmFunc_api_cache_save($filename,$data,$uri=null,$origin_cache_key=null){
	global $_FM;
	global $_W;
	global $_GPC;
	// 在数据目录中添加一个文件并写入内容信息
	$filelink = 'fm453/data/';
	$content = "<?php";
	$content .= "\r\n";
	$content .= "/*";
	$content .= "\r\n";

	if($uri) {
		$content .= "* @url 请求源:".$uri;
		$content .= "\r\n";
	}
	if($origin_cache_key) {
		$content .= "* @key 关联键:".$origin_cache_key;
		$content .= "\r\n";
		$content .= "* @checkmd5 文件名校验:".md5($origin_cache_key);
		$content .= "\r\n";
	}

	$content .= "*/";
	$content .= "\r\n";
	$content .= "\$FmApiDataCache = '';";
	$content .= "\r\n";
	$content .= "\$FmApiDataCache = ";
	$content .= "'".iserializer($data)."'";	//写入前进行序列化操作，以便能存储各种类型的数据
	$content .= ";";
	$file = $filelink.$filename.'.php';
	file_write($file,$content);
}

//从指定文件读取数据
function fmFunc_api_cache_load($filename){
	global $_FM;
	global $_W;
	global $_GPC;
	// 引用数据缓存文件，执行并返回数据
	//$filelink = IA_ROOT.'/data/fm453/';
	$filelink = IA_ROOT.'/attachment/fm453/data/';
	$file = $filelink.$filename.'.php';
	if(file_exists($file)){
	    include $file;	//不可使用include_once  会导致数据只是读第一次就中断了
	    $data = iunserializer($FmApiDataCache);
	}
	return $data;
}
