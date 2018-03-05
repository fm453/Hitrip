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
 * @remark 归集自定义常量及预定义
*/

defined('IN_IA') or exit('Access Denied');
define('FM_TIME_S',microtime());	//记录程序开始执行的时间(毫秒级)
//下面采用 FM_ 变量，作为一些“常量”的定义；约定用法，不能在后续文件中更改;
//所有文件路径定义，是相对于网站根目录的绝对路径,结尾带／ (DIRECTORY_SEPARATOR)
defined('FM') or define('FM','fm453');
define('FM_NAME_CN','营销管理系统');
define('FM_PATH', IA_ROOT.DIRECTORY_SEPARATOR.'addons'.DIRECTORY_SEPARATOR.FM_NAME.DIRECTORY_SEPARATOR);
define('FM_CORE',FM_PATH . 'core'.DIRECTORY_SEPARATOR);
define('FM_APP',FM_CORE . 'app'.DIRECTORY_SEPARATOR);
define('FM_WXAPP',FM_CORE . 'wxapp'.DIRECTORY_SEPARATOR);
define('FM_WEB',FM_CORE . 'web'.DIRECTORY_SEPARATOR);
define('FM_VAR',FM_CORE . 'vars'.DIRECTORY_SEPARATOR);
define('FM_PUBLIC',FM_CORE . 'public'.DIRECTORY_SEPARATOR);
define('FM_PLUGIN',FM_CORE . 'plugin'.DIRECTORY_SEPARATOR);
define('FM_ATTACHMENT', IA_ROOT.DIRECTORY_SEPARATOR.'attachment'.DIRECTORY_SEPARATOR.'fm453'.DIRECTORY_SEPARATOR);
define('FM_DATA',FM_ATTACHMENT . 'data'.DIRECTORY_SEPARATOR);
define('FM_CERT',FM_ATTACHMENT . 'cert'.DIRECTORY_SEPARATOR);
define('FM_RESOURCE','..'.DIRECTORY_SEPARATOR.'addons'.DIRECTORY_SEPARATOR.FM_NAME.DIRECTORY_SEPARATOR . 'resource'.DIRECTORY_SEPARATOR);
define('FM_TEMPLATE',FM_PATH.'template'.DIRECTORY_SEPARATOR);
define('FM_SHOPSTYLE','web'.DIRECTORY_SEPARATOR.'default'.DIRECTORY_SEPARATOR);
define('FM_APPSTYLE','default'.DIRECTORY_SEPARATOR);
//调试开关
define('FM_DEBUG',TRUE);
define('FM_API_DEBUG',TRUE);
define('FM_APP_DEBUG',TRUE);

session_start();	//启用SESSION
/**
* 以下是本模块经常用到的，微擎系统的自定义变量与常量
*
$_GPC 全局请求变量, 获取 $_GET, $_POST, $_COOKIES 中的变量
$_W['config'] 	array 	系统设置
$_W['timestamp'] 	int 	当前时刻时间戳
$_W['charset'] 	string 	系统字符编码
$_W['token'] 	string 	系统表单验证来源
$_W['clientip'] 	string 	当前客户端 IP 地址
$_W['script_name'] 	string 	当前脚本名称，包含子路径 	“/web/index.php”
$_W['siteroot'] 	string 	网站URL根目录 	"http://pro/"
$_W['siteurl'] 	string 	原始链接 	"http://pro/test.php?a=1&b=2"
$_W['attachurl'] 	string 	附件URL根目录 	"http://pro/attachment/"
$_W['isajax'] 	boolean 	是否为AJAX请求
$_W['ispost'] 	boolean 	是否为POST请求
$_W['uniacid'] 	int 	当前统一公号与account内容一致
$_W['uniaccount'] 	array 	当前统一公号(主公号)信息
$_W['uniaccount']['uniacid'] 	int 	当前统一公号 ID (uniacid)
$_W['uniaccount']['groupid'] 	int 	当前统一公号套餐
$_W['uniaccount']['name'] 	string 	当前统一公号名称

$_W['setting']['site']	  array  系统站点信息	key:站点id  url: 站点绑定的网址

Web 端可见
$_W['uid'] 	int 	当前登录的操作用户 uid
$_W['username'] 	string 	当前操作用户名称
$_W['user'] 	array 	当前操作用户信息
$_W['isfounder'] 	boolean 	是否站长
$_W['role'] 	string 	角色

App 端可见
$_W['template'] 	string 	当前公号使用的微站模板名称
$_W['container'] 	string 	微站客户端平台 	取值范围: wechat, android, ipad, iphone, ipod, unknown
$_W['os'] 	string 	微站客户端平台 	取值范围: windows (pc端), mobile(手机端), unknown
$_W['member'] 	array 	当前粉丝用户信息
$_W['member']['uid'] 	int 	当前粉丝用户 uid
$_W['openid'] 	string 	当前粉丝用户标识 	可能是真实的 openid 可能是借用的 oauth_openid
$_W['fans'] 	array 	当前粉丝用户信息
网页授权
$_W['oauth_account'] 	array 	当前子公号,可使用的网页授权公众号信息 	可能是自己(level=4) 可能是借用的别的认证服务号 可能没有(level<4 且未借用)
*/
?>
