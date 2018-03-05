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
 * @remark：公用函数
 */
defined('IN_IA') or exit('Access Denied');

//PHP stdClass Object转array
function fm_object2array($array) {
    if(is_object($array)) {
        $array = (array)$array;
     }
    if(is_array($array)) {
         foreach($array as $key=>$value) {
             $array[$key] = fm_object2array($value);
         }
     }
     return $array;
}

//根据后台uid获取用户名
function fm_w_username($uid){
	$uid=intval($uid);
	if($uid==0) {
		$username='系统';
	}elseif($uid>0){
		$username=pdo_fetchcolumn("SELECT `username` FROM ".tablename('users')." WHERE `uid` = :uid",array(':uid'=>$uid));
	}
	return $username;
}

//商城是否营业
function fm_checkopen($onoffs=NULL){
	if(!is_array($onoffs)) {
		$result = fmMod_setting_query_setfor($_W['uniacid'],'onoffs',127);
		if($result['result']){
			$onoffs=$result['data'];
		}else{
			return TRUE;
		}
	}
	if(!$onoffs['isopenning']){
		$msg=array();
		$msg['title'] = '本店打烊啦!';
		$msg['body'] = $onoffs['closed_reason'];
		$url = fm_murl('error','index','index',array('msg[title]'=>$msg['title'],'msg[body]'=>$msg['body']));
		header("Location: ".$url);
		exit();
	}
	return TRUE;
}

//计算分页数, 返回分页总数(PHP默认取整后再进行求余MOD，因此所有运算先转换数字为整数类型)
function fm_page($total, $psize){
	$psize = (intval($psize)>0) ? intval($psize) : 1;
	$pages = round($total/$psize);
	return $pages;
}

//重构前台错误提示页面
function fm_error($msgbody=NULL,$msgtitle=NULL,$backurl=NULL){
	global $_GPC;
	global $_W;
	global $_FM;
	$hasnewurl = empty($backurl) ? FALSE : TRUE;
	$backurl= !empty($backurl) ? $backurl : $_W['siteurl'];
	$backurl= urlencode($backurl);
	$msgtitle = urlencode($msgtitle);
	$msgbody = urlencode($msgbody);
	$url = fm_murl('error','index','index',array('msg[title]'=>$msgtitle,'msg[body]'=>$msgbody,'backurl'=>$backurl,'hasnewurl'=>$hasnewurl));
	header("Location: ".$url);
	exit();
}

//前台提示页面
function fm_tips($msgbody=NULL,$msgtitle=NULL,$backurl=NULL){
	$msgtitle = !$msgtitle ? "温馨提示" : $msgtitle;
	$msgbody = !$msgbody ? "您可以需要联系客服寻求帮助。" : $msgbody;

	$backurl= !empty($backurl) ? $backurl : $_W['siteurl'];
	$backurl= urlencode($backurl);
	$msgtitle = urlencode($msgtitle);
	$msgbody = urlencode($msgbody);
	$url = fm_murl('help','tip','index',array('msg[title]'=>$msgtitle,'msg[body]'=>$msgbody,'backurl'=>$backurl));
	header("Location: ".$url);
	exit();
}

//全局设定，商城平台模式参数
function fmFunc_getPlatids($settings=null){
	global $_W, $_GPC,$_FM;
	if(!$settings){
		$settings = $_FM['settings'];
	}
	$platids=$settings['platids'];
	if($platids="-1"){
		$platids=array(
			'oauthid'=>$settings['plat']['oauthid'],
			'fendianids'=>$settings['plat']['fendianids'],
			'supplydianids'=>$settings['plat']['supplydianids'],
			'blackids'=>$settings['plat']['blackids']
		);
	}
	return $platids;
}

//全局设定，主行业;
function fmFunc_industry() {
	global $_W;
	global $_FM;
	$fm453industry =$_FM['settings']['industry'];
	if (empty($fm453industry))
	{
		$fm453industry='ota';
		//支持主行业包括：hotel-酒店住宿类；ota-旅行社平台类；ebiz-电子商务类
	}
	return $fm453industry;
}

/*
*返回倒计时
*/
function fmFunc_time_tran($the_time) {
	global $_W;
	global $_FM;
	$timediff = $the_time - time();
	$days = intval($timediff / 86400);
	if (strlen($days) <= 1) {
		$days = "0" . $days;
	}
	$remain = $timediff % 86400;
	$hours = intval($remain / 3600);
	;
	if (strlen($hours) <= 1) {
		$hours = "0" . $hours;
	}
	$remain = $remain % 3600;
	$mins = intval($remain / 60);
	if (strlen($mins) <= 1) {
		$mins = "0" . $mins;
	}
	$secs = $remain % 60;
	if (strlen($secs) <= 1) {
		$secs = "0" . $secs;
	}
	$ret = "";
	if ($days > 0) {
		$ret.=$days . " 天 ";
	}
	if ($hours > 0) {
		$ret.=$hours . ":";
	}
	if ($mins > 0) {
		$ret.=$mins . ":";
	}
	$ret.=$secs;
	return array("倒计时 " . $ret, $timediff);
}

/**
* 判断浏览器名称和版本
*/
function  fmFunc_check_browser (){
	if (!empty($_SERVER['HTTP_USER_AGENT'])){
		$agent=$_SERVER['HTTP_USER_AGENT'];
		$browser='';
		$browser_ver='';
		if (preg_match('/MSIE\s([^\s;]+)/i',$agent,$regs)){
			$browser='Internet Explorer';
			$browser_ver=$regs[1];
		}
		elseif (preg_match('/FireFox\/([^\s]+)/i',$agent,$regs)){
			$browser='FireFox';
			$browser_ver=$regs[1];
		}
		elseif (preg_match('/Maxthon/i',$agent,$regs)){
			$browser='(Internet Explorer '.$browser_ver.') Maxthon';
			$browser_ver='';
		}
		elseif (preg_match('/Opera[\s\/]([^\s]+)/i',$agent,$regs)){
			$browser='Opera';
			$browser_ver=$regs[1];
		}
		elseif (preg_match('/OmniWeb\/(v*)([^\s;]+)/i',$agent,$regs)){
			$browser='OmniWeb';
			$browser_ver=$regs[2];
		}
		elseif (preg_match('/Netscape([\d]*)\/([^\s]+)/i',$agent,$regs)){
			$browser='Netscape';
			$browser_ver=$regs[2];
		}
		elseif (preg_match('/safari\/([^\s]+)/i',$agent,$regs)){
			$browser='Safari';
			$browser_ver=$regs[1];
		}
		elseif (preg_match('/NetCaptor\s([^\s;]+)/i',$agent,$regs)){
			$browser='(Internet Explorer '.$browser_ver.') NetCaptor';
			$browser_ver=$regs[1];
		}
		elseif (preg_match('/Lynx\/([^\s]+)/i',$agent,$regs)){
			$browser='Lynx';
			$browser_ver=$regs[1];
		}elseif (preg_match('/MicroMessenger\/([^\s]+)/i',$agent,$regs)){
			$browser='wechat';
			$browser_ver=$regs[1];
		}

		if (!empty($browser)){
			return addslashes($browser.' '.$browser_ver);
		}else {
			return 'Unknow browser';
		}
	}else{
	    return 'Unknow browser';
	}
}

/**
* 正则修改电话号码（加上点击拨号链接）
*/
function  fmFunc_tel_addlink($content){
    global $_W;
    global $_GPC;
    global $_FM;
    $message = $content;
	$regStr = '/([(\\|\/|\_|\>|\.)]{0}1[0-9]{10}[( |\s|\S)])/'; //数字片段筛选
	$replace = '';

	if(preg_match_all($regStr, $message, $matchaids)) {
        $matchaids[1] = array_unique($matchaids[1]);
        foreach($matchaids[1] as $key){
             //var_dump($key);
             //var_dump(intval($key));
            $str = trim($key);
            $regStr = '/[((\\|\/|\_|\>|\.){1}|\d{0,1})]'.$str.'[((\\|\/|\_|\>|\.){1}|\d{0,1})]/'; //电话号码前后加缀
            $regStr = '/((\\|\/|\_|\>|\.){1}|\d{0,1})'.$str.'((\\|\/|\_|\>|\.){1}|\d{0,1})/'; //电话号码前后加缀
            // !strstr($str, "abc");    //文本判断不包含
            // preg_match("/^((?!abc).)*$/is", $str);   //正则判断不包含
            // preg_replace("/<[^>]+>/", "", $msg);  删除<>和中间的内容
            if(strlen($str)==11 || (strlen($str)==12 && intval($str) < 20000000000)){
                $regStr = '/((0\d{2,3}-\d{7,8})|(1[34578]\d{9}))/'; //电话号码判断
                $str = preg_replace($regStr, '<a href="tel:'."\\0".'" style="color:#FF5722;">'."\\0".'</a>', $str);   // \\0 是匹配的结果字段
                $replacefrom = $key;
                $replaceto = $str;
                $message = str_replace($replacefrom,$replaceto,$message);
            }
        }
    }
    return $message;
}

/**
* 获取毫秒级时间
*/
function fmFunc_getMillisecond($microtime=null){
	$time = !empty($microtime) ? $microtime : microtime();
	list($t1,$t2) = explode(' ',$time);
	return (float)sprintf('%.f',(floatval($t1) + floatval($t2))*1000);
}
