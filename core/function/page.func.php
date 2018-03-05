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
 * @remark：页面数据的处理方法
 */
defined('IN_IA') or exit('Access Denied');

//全局设定，数据类型
function fmFunc_page_detail($sn,$isrefresh=null){
	global $_FM;
	global $_W;
	global $_GPC;
	$platids = fmFunc_getPlatids();//获取平台模式关联的公众号商户ID参数
	$platid=$_W['uniacid'];
	$oauthid=$platids['oauthid'];
	$fendianids=$platids['fendianids'];
	$supplydianids=$platids['supplydianids'];
	$blackids=$platids['blackids'];
	$page = array();

	//分解sn
	$sns = explode('_',$sn);
	$page['s_sn'] = $sns[2];
	$page['f_sn'] = $sns[1];
	$page['o_sn'] = $sns[0];

	$getData = array();
	$getData['platid'] = $oauthid;
	$getData['uniacid'] = $_W['uniacid'];
	$getData['shopid'] = 0;
	if($isrefresh){
		$getData['nocache'] = 1;
	}

	//取页面信息
	$getData['ac'] = 'content';
	$getData['nocache'] = 0;	//指定使用缓存,提高效率

	$postUrl = '/index.php?r=page/detail';

	$postData = array();
	$postData['sn'] = $page['o_sn'];
	$postData['f_sn'] = $page['f_sn'];
	$postData['s_sn'] = $page['s_sn'];

	$isread = !$isrefresh ? 1 : 0;
	$result = fmFunc_api_push($postUrl,$postData,$getData,$isread);
	if($result) {
		$data = $result;
		$page['data'] = $data;
	}

	return $page;
}
