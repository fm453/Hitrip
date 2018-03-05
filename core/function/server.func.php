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

/**
 * @remark：服务器定义函数
 */
defined('IN_IA') or exit('Access Denied');
//定义服务器
function fmFunc_server_db(){
	$server_database = array(
		'host' => 'dev.hiluker.com', //数据库IP或是域名
		'username' => 'formodules', // 数据库连接用户名
		'password' => '', // 数据库连接密码
		'database' => 'fm453_access', // 数据库名
		'port' => 3306, // 数据库连接端口
		'tablepre' => '', // 表前缀，如果没有前缀留空即可pre_
		'charset' => 'utf8', // 数据库默认编码
		'pconnect' => 0, // 是否使用长连接
	);
	return $server_database;
}

//全局设定，服务器授权信息；
function fmFunc_server_check() {
	global $_GPC;
	global $_W;
	global $_FM;
	//添加远程数据库连接
	$server_database = fmFunc_server_db();
	$dbconn = new DB($server_database);
	if (!$dbconn)  {
		die('远程连接失败，请稍后再试!');
	}
	$sql = 'Select id,sitename,ip,domain,url,api,secret,authcode,price,remark,price_log,status,super,haschange,module from `buyers` where `module`= "'.FM_NAME.'" and `domain`="'.$_SERVER['HTTP_HOST'].'" order by id desc';
	$result = $dbconn->fetch($sql);
	if($result){
		$serverinfos=$result;
		return $serverinfos;
	}else{
		return FALSE;
	}
}
//全局设定，服务器版本检查;
function fmFunc_server_getVersions() {
	global $_GPC;
	global $_W;
	global $_FM;
	//添加远程数据库连接
	$server_database = fmFunc_server_db();
	$dbconn = new DB($server_database);
	if (!$dbconn)  {
		die('远程连接失败，请稍后再试!');
	}
	$sql = 'Select *  from `versions` where `module`= "'.FM_NAME.'" and `status`=1 order by id desc';
	$result = $dbconn->fetchall($sql);
	if($result){
		$versions=$result;
		return $versions;
	}else{
		return FALSE;
	}
}

//判断来路域名
function fmFunc_server_via_domain() {
	global $_FM;
	if(isset($_SERVER["HTTP_REFERER"])) {
		$url = $_SERVER["HTTP_REFERER"];   //获取完整的来路URL
		$str = str_replace("http://","",$url);  //去掉http://
		$str = str_replace("https://","",$url);  //去掉https://
		$strdomain = explode("/",$str);               // 以“/”分开成数组
		$domain = $strdomain[0];              //取第一个“/”以前的字符
	}else{
		$domain = $_SERVER["HTTP_HOST"];
	}
	$_FM['viaDomain'] = $domain;
	return $domain;
}

//判断来路ip
function fmFunc_server_via_ip() {
	global $_FM;
	$ip = '';
 	if(!empty($_SERVER["HTTP_CLIENT_IP"])){
		$ip = $_SERVER["HTTP_CLIENT_IP"];
	}elseif(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
		$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
	}elseif(!empty($_SERVER["REMOTE_ADDR"])){
		$ip = $_SERVER["REMOTE_ADDR"];
	}
	$_FM['viaIp'] = $ip;
	return $ip;
}
