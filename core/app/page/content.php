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
 * @remark 指定SN码获取页面内容
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;
fm_load()->fm_func('page');
fm_load()->fm_func('share');//链接分享管理
fm_load()->fm_func('view'); 	//浏览量处理
$return = array();
$errno = 0;
$message = '指定SN码获取页面内容';

if(empty($_GPC['sn'])){
	$data = null;
	$errno = 4530001;
	$message = '参数不存在';
}else{
	$sn = $_GPC['sn'];
	if(substr_count($sn,'_')==2){
		$data = fmFunc_page_detail($sn);
		if(empty($data['data'])){
			$data = fmFunc_page_detail($sn,1);
		}
		if(empty($data['data'])){
			$data = null;
			$errno = 4530003;
			$message = '页面数据不存在';
		}
	}else{
		$data = null;
		$errno = 4530002;
		$message = '参数不全';
	}
}

//$data['data']['params']['content'] = htmlspecialchars($data['data']['params']['content']);

$return['errno'] = $errno;
$return['message'] = $message;
$return['data'] = $data;

die(json_encode($return,JSON_UNESCAPED_UNICODE));

