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
 * @remark：路由及链接处理函数
 */
defined('IN_IA') or exit('Access Denied');

//过滤url,返回参数数组
function fm_url_clean($url=NULL){
	global $_GPC;
	global $_W;
	global $_FM;
	$url = !empty($url) ? $url : $_W['siteurl'];
	$url = str_replace($_W['siteroot'],'',$url);
	$url = str_replace('app/','',$url);
	$url = str_replace('index.php?','',$url);
	$url = str_replace('c=entry&','',$url);
	$url = str_replace('&from=groupmessage&','',$url);
	$url = str_replace('isappinstalled=0&','',$url);
	$url = str_replace('wxref=mp.weixin.qq.com','',$url);
	$url = str_replace('#wechat_redirect','',$url);
	$url_array = explode('&',$url);
	$arguments=array();
	foreach($url_array as $u){
		$a=explode('=',$u);
		$arguments[$a[0]]=$a[1];
	}
	return $arguments;
}

//重构url路由；WEB端
function fm_wurl($do,$ac,$op,$addons=NULL){
	global $_GPC;
	global $_W;
	global $_FM;
	$url =$_W['siteroot'].'web/';
	$url .='index.php?c=site&a=entry&i='.$_W['uniacid'];
	if(empty($do)) {
		return FALSE;
	}
	$op = !empty($op) ? $op : 'display';
	$condition ='&m='.FM_NAME;
	$condition .='&do='.$do;
	$condition .='&ac='.$ac;
	$condition .='&op='.$op;
	if(is_array($addons)) {
		foreach($addons as $key => $addon){
			$condition .='&'.$key.'='.$addon;
		}
	}
	return $url.$condition;
}

//重构url路由；APP端
/*
@issimple 是否简写，简写方式不添加expiretime\fromplatid等后缀
*/
function fm_murl($do=NULL,$ac=NULL,$op=NULL,$addons=NULL,$issimple=null){
	global $_GPC;
	global $_W;
	global $_FM;

	$arguments = fm_url_clean();

	$_GPC['do'] = $arguments['do'];
	$_GPC['ac'] = $arguments['ac'];
	$_GPC['op'] = $arguments['op'];
	$_GPC['i'] = intval($arguments['i']);
	$_GPC['lastid'] = intval($arguments['lastid']);
	$_GPC['shareid'] = intval($arguments['shareid']);
	if($_GPC['shareid']==0 && $_W['member']['uid']>0){
	    $_GPC['shareid'] = $_W['member']['uid'];
	}

	$url =$_W['siteroot'].'app/';
	$url .='index.php?c=entry';
	if(empty($do)) {
		if(empty($addons['do'])) {
			if(!empty($_GPC['do'])) {
				$do = $_GPC['do'];
			}else{
				return FALSE;
			}
		}else{
			$do = $addons['do'];
		}
	}

	if(empty($ac)) {
		if(empty($addons['ac'])) {
			$ac = 'index';
		}else {
			$ac = $addons['ac'];
		}
	}

	if(empty($op)) {
		if(empty($addons['op'])) {
			$op =$_GPC['op'];
		}else{
			$op = $addons['op'];
		}
	}

	if(!empty($addons['i'])) {
		$platid = $addons['i'];
	}
	$platid= ($platid>0) ? $platid : $_W['uniacid'];

	$url .='&i='.$platid;
	$url .='&m='.FM_NAME;
	$url .='&do='.$do;
	$url .='&ac='.$ac;
	$url .='&op='.$op;

	if($issimple){
		$condition ='';
		if(is_array($addons)) {
			unset($addons['do']);
			unset($addons['ac']);
			unset($addons['op']);
			unset($addons['i']);
			unset($addons['lastid']);
			unset($addons['shareid']);
			unset($addons['share_user']);
			unset($addons['expiretime']);
			foreach($addons as $key => $addon){
				$condition .='&'.$key.'='.$addon;
			}
		}
		return $url.$condition;
	}

	$url .='&fromplatid='.$_W['uniacid'];//加上平台来源后缀

	$condition ='';
	if(is_array($addons)) {
		unset($addons['do']);
		unset($addons['ac']);
		unset($addons['op']);
		unset($addons['i']);
		foreach($addons as $key => $addon){
			if($key=='lastid'){
				$newlastid = TRUE;
			}elseif($key=='shareid'){
				$newshareid = TRUE;
			}elseif($key=='share_user'){
				$newshare_user = TRUE;
			}else{
				$condition .='&'.$key.'='.$addon;
			}
		}
	}
	//处理分享参数
	if($addons['isshare']){
		$newlastid = TRUE;
		$newshareid = TRUE;
		$newshare_user = TRUE;
	}
	if($newlastid) {
		$url .= "&lastid=".$_GPC['shareid'];
	}else{
		$url .= "&lastid=".$_GPC['lastid'];
	}

	if($newshareid) {
		$url .= "&shareid=".$_W['member']['uid'];
	}else{
		$url .= "&shareid=".$_GPC['shareid'];
	}

	if(!$newshare_user) {
		$url .= "&share_user=".$_W['openid'];
	}

	$expiretime = isset($_FM['settings']['fenxiao']['expiretime']) ? intval($_FM['settings']['fenxiao']['expiretime']) : 24*60*60;	//默认分享的有效时间为24小时（指统计分享数据的指标，并非直接禁止访问）
	$expire = "&expiretime=".$expiretime;

	return $url.$condition.$expire;
}

//重构url路由；API请求地址
function fm_apiurl($controller,$action,$op,$addons=NULL){
	global $_GPC;
	global $_W;
	global $_FM;
	$do = $controller;
	$ac = $action;
	$url = 'index.php?r=';
	$url .= $do.'/'.$ac;
	if(empty($do)) {
		return FALSE;
	}
	$op = !empty($op) ? $op : '';
	$condition ='';
	if(is_array($addons)) {
		foreach($addons as $key => $addon){
			$condition .='&'.$key.'='.$addon;
		}
	}
	return $url.$condition;
}

//wxapp 小程序路由
function fm_wxappurl($do=NULL,$ac=NULL,$op=NULL,$addons=NULL){
	global $_GPC;
	global $_W;
	global $_FM;
	$arguments = fm_url_clean();

	$_GPC['do'] = $arguments['do'];
	$_GPC['ac'] = $arguments['ac'];
	$_GPC['op'] = $arguments['op'];
	$_GPC['i'] = intval($arguments['i']);
	$_GPC['lastid'] = intval($arguments['lastid']);
	$_GPC['shareid'] = intval($arguments['shareid']);
	if($_GPC['shareid']==0 && $_W['member']['uid']>0){
	    $_GPC['shareid'] = $_W['member']['uid'];
	}

	$url =$_W['siteroot'].'app/';
	$url .='index.php?c=entry';
	if(empty($do)) {
		if(empty($addons['do'])) {
			if(!empty($_GPC['do'])) {
				$do = $_GPC['do'];
			}else{
				return FALSE;
			}
		}else{
			$do = $addons['do'];
		}
	}

	if(empty($ac)) {
		if(empty($addons['ac'])) {
			$ac = 'index';
		}else {
			$ac = $addons['ac'];
		}
	}

	if(empty($op)) {
		if(empty($addons['op'])) {
			$op =$_GPC['op'];
		}else{
			$op = $addons['op'];
		}
	}

	if(!empty($addons['i'])) {
		$platid = $addons['i'];
	}
	$platid= ($platid>0) ? $platid : $_W['uniacid'];

	$url .='&a=wxapp';
	$url .='&i='.$platid;
	$url .='&m='.FM_NAME;
	$url .='&do='.$do;
	$url .='&ac='.$ac;
	$url .='&op='.$op;
	$url .='&fromplatid='.$_W['uniacid'];//加上平台来源后缀

	$condition ='';
	if(is_array($addons)) {
		unset($addons['do']);
		unset($addons['ac']);
		unset($addons['op']);
		unset($addons['i']);
		foreach($addons as $key => $addon){
			if($key=='lastid'){
				$newlastid = TRUE;
			}elseif($key=='shareid'){
				$newshareid = TRUE;
			}elseif($key=='share_user'){
				$newshare_user = TRUE;
			}else{
				$condition .='&'.$key.'='.$addon;
			}
		}
	}
	//处理分享参数
	if($addons['isshare']){
		$newlastid = TRUE;
		$newshareid = TRUE;
		$newshare_user = TRUE;
	}
	if($newlastid) {
		$url .= "&lastid=".$_GPC['shareid'];
	}else{
		$url .= "&lastid=".$_GPC['lastid'];
	}

	if($newshareid) {
		$url .= "&shareid=".$_W['member']['uid'];
	}else{
		$url .= "&shareid=".$_GPC['shareid'];
	}

	if(!$newshare_user) {
		$url .= "&share_user=".$_W['openid'];
	}

	$expiretime = isset($_FM['settings']['fenxiao']['expiretime']) ? intval($_FM['settings']['fenxiao']['expiretime']) : 24*60*60;	//默认分享的有效时间为24小时（指统计分享数据的指标，并非直接禁止访问）
	$expire = "&expiretime=".$expiretime;

	return $url.$condition.$expire;
}


//长网址转短网址
function fm_shorturl($longurl){
	global $_W;
	if($_W['uniaccount']['level']==1 || $_W['uniaccount']['level']==2){
		//普通订阅号或服务号，无此功能，直接返回
		return $longurl;
	}
	load() -> model('account');
	fm_load()->fm_func('wechat');
	$token = fmFunc_wechat_getAccessToken();
	$url = "https://api.weixin.qq.com/cgi-bin/shorturl?access_token={$token}";
	$send = array();
	$send['action'] = 'long2short';
	$send['long_url'] = $longurl;
	$response = ihttp_request($url, json_encode($send));
	if (is_error($response)) {
		return $longurl;
	}
	$result = @json_decode($response['content'],true);
	if($result['errorcode']==0){
		return $result['short_url'];
	}
	return $longurl;
}

//判断是否https协议
function fm_isHttpsUrl() {
    if ( !empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off') {
        return true;
    } elseif ( isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https' ) {
        return true;
    } elseif ( !empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off') {
        return true;
    }
    return false;
}

//网址跳转
/*
@state 短网址标识
@in_out 是生成记录还是取出记录(in . out）
$urls 生成记录，来路地址与要跳转的地址
*/
function fm_redirectUrl($state,$in_out=null,$urls=null) {
	global $_W;
	if(empty($state) && !$in_out){
		return false;
	}
	if($in_out=='in'){
		$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		$state ='';
		$length = 8;
		for ( $i = 0; $i < $length; $i++ )
		{
			// 使用 substr 截取$chars中的任意一位字符；
			$state .= substr($chars, mt_rand(0, strlen($chars)-1), 1);
		}
		$data = array('uniacid'=>$_W['uniacid'],'state'=>$state);
		$data['from'] = isset($urls['from']) ? $urls['from'] : $_W['siteurl'];
		$data['to'] = isset($urls['to']) ? $urls['to'] : '';
		pdo_insert('fm453_imms_redirect',$data);
		return $state;
	}else{
		$fields = "`id`,`from`,`to`";
		$table = "fm453_imms_redirect";
		$orderby  = " ORDER BY id ASC";
		$condition = " WHERE ";
		$params = array();
		$condition .= "`uniacid`=:uniacid";
		$params[':uniacid']=$_W['uniacid'];
		$condition .=" AND ";
		$condition .= "`state`=:state";
		$params[':state']=$state;
	    $sql="select ".$fields." from" . tablename($table) . $condition. $orderby;
		$result=pdo_fetch($sql,$params);
		if(!$result){
			return false;
		}else{
			if($result['to']){
				pdo_delete($table,array('id'=>$result['id']));
				header("Location:".$result['to']);
				exit();
			}elseif($result['from']){
				header("Location:".$result['from']);
				exit();
			}
		}
	}
}

//网址跳转，只跳转不记录,跳转向退出以结束当前动作
/*
@url 要转向的URL
*/
function fm_header_url($url) {
	header("Location:".$url);
	exit();
}
