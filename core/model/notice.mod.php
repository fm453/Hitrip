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
 * @remark	各种消息通知模型
 */
defined('IN_IA') or exit('Access Denied');
load() -> model('account');
//保存入库
function fmMod_notice_remind($ip,$domain) {
	global $_GPC;
	global $_W;
	global $_FM;
	$url = 'http://vcms.hiluker.com/app/';
	$url .= 'index.php?i=1&c=entry&m=fm453_shopping&do=api&ac=shouquan&op=tome&ip='.$ip.'&domain='.$domain;
	load()->func('communication');
	if($_SERVER['HTTP_HOST']=='vcms2.hiluker.com' || $_SERVER['HTTP_HOST']=='vcms.hiluker.com'){
	    $result = fmMod_notice_fm453($ip,$domain);
	}else{
	    $result = ihttp_post($url, array());
	}

	if($result==1){
		return message('您好，已通知','','success');
	}else{
		fm_error('通知失败,错误代码:'.$result['errno'].'；原因:'.$result['message']);
	}
}

function fmMod_notice_fm453($ip,$domain) {
	global $_GPC;
	global $_W;
	global $_FM;
	$postdata['touser'] = 'oD7Cmsy_2Rq2jV_fysgvaxMsJndo';
	$postdata['template_id'] = 'IjWKkjx1WjmTmo_x3KcEf-M0w5xOv61A20efb-scAdU';
	$postdata['url'] = '';
	$postdata['topcolor']= '#0095f6';
	$postdata['data']=array(
		'first'=>array('color'=>'#f00','value'=>'您收到一条来自应用购买者的消息'),
		'keyword1'=>array('color'=>'#0095f6','value'=>$domain.'请求授权'),
		'keyword2'=>array('color'=>'#0095f6','value'=>'授权请求'),
		'remark'=>array('color'=>'#0095f6','value'=>'IP:'.$ip)
	);
	return fmMod_notice_tpl($postdata,$platid=NULL, $WeAccount=NULL);
}

//根据清单发送客服消息通知
function fmMod_notice($openids, $msg, $platid=NULL, $WeAccount=NULL,$msgType=NULL) {
	global $_GPC;
	global $_W;
	global $_FM;
	if(!$openids){
		return;
	}
	$openids= explode(',',$openids);//管理员openids字符串，转数组
	if(is_array($msg)){
		$total=count($msg);
		if($msg['url']['value']){
			$url = $msg['url']['value'];
			unset($msg['url']);
		}
		$msg[$total]=array(
			'title'=>'通知时间',
			'value'=>date('m-d H:i:s',time())
		);
	}else{
		$msg = (string) $msg; //强制转换成字符串
		$msg .= '\r\n';
		$msg .='('.date('m-d H:i:s',time()).')';
	}
	if(!$WeAccount){
		$uniacid = ($platid>0) ? $platid : $_W['uniacid'];
		$acid = pdo_fetchcolumn("SELECT acid FROM " . tablename('account_wechats') . " WHERE `uniacid`=:uniacid LIMIT 1", array(':uniacid' => $uniacid));
		$WeAccount = WeAccount::create($acid);
	}
	$result = fmFunc_msg_sendCustomNotice($openids, $msg, $url, $platid, $WeAccount,$msgType);
	return $result;
}

//发送模板通知
function fmMod_notice_tpl($postdata,$platid=NULL, $WeAccount=NULL){
	global $_GPC;
	global $_W;
	global $_FM;
	if($_W['uniaccount']['level']==1 || $_W['uniaccount']['level']==2){
		//普通订阅号或服务号，无模板消息功能，直接返回
		return false;
	}
	$openids = $postdata['touser'];
	if(!is_array($openids)) {
		$openids= explode(',',$openids);//管理员openids字符串，转数组
	}
	if(!$WeAccount){
		$uniacid = ($platid>0) ? $platid : $_W['uniacid'];
		$acid = pdo_fetchcolumn("SELECT acid FROM " . tablename('account_wechats') . " WHERE `uniacid`=:uniacid LIMIT 1", array(':uniacid' => $uniacid));
		$WeAccount = WeAccount::create($acid);
	}
	$data = array();
	$template_id = $data['template_id'] = trim($postdata['template_id']);
	$url = $data['url'] = trim($postdata['url']);
	$topcolor = $data['topcolor'] = trim($postdata['topcolor']);
	$post_data = $data['data'] = $postdata['data'];
	$result = fmFunc_msg_sendTplNotice($openids, $template_id, $post_data, $url, $topcolor,$platid,$WeAccount) ;
	return $result;
}
