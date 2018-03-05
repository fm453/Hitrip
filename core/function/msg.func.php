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
 * @remark：消息通知公用函数
 */
defined('IN_IA') or exit('Access Denied');

//调用系统函数类发送模板通知,支持跨公众号请求
function fmFunc_msg_sendTplNotice($tousers, $template_id, $postdata, $url = NULL, $platid = NULL, $WeAccount = NULL) {
	global $_GPC;
	global $_W;
	global $_FM;
	if(!is_array($tousers)) {
		$tousers =explode(',',$tousers);
		$tousers =array_unique($tousers);
	}

	if(!$tousers){
		return FALSE;
	}
	if(!$WeAccount){
		load() -> model('account');
		$uniacid = ($platid>0) ? $platid : $_W['uniacid'];
		$acid = pdo_fetchcolumn("SELECT acid FROM " . tablename('account_wechats') . " WHERE `uniacid`=:uniacid LIMIT 1", array(':uniacid' => $uniacid));
		$WeAccount = WeAccount::create($acid);
	}
	if (!$WeAccount) {
		return FALSE;
	}

	$return = array();
	foreach($tousers as $touser){
		$isSent_60 = fmMod_log_query_openid($_W['uniacid'],$do='msg',$tablename='fm453_shopping_logs',$sn,$first=0,$getnum=1,$addons=array('openid'=>$touser,'time'=>array('start'=>$_W['timestamp']-15,'end'=>$_W['timestamp'])));	//强制判断15秒内是否有发送消息的记录
		$isSent_60 = $isSent_60['result'];
		if(!$isSent_60){
			$return[] = $WeAccount -> sendTplNotice($touser, $template_id, $postdata, $url,$topcolor);
			$fanid=mc_openid2uid($touser);
			$details=array('touser'=>$touser,'template_id'=>$template_id,'postdata'=>$postdata,'url'=>$url);
			fmMod_log_record($platid=$_W['uniacid'],$uniacid=$_W['uniacid'],$uid='',$fanid,$openid=$touser,$tablename='fm453_shopping_logs',$sn='',$do='msg',$details);
		}else{
			$return[]= FALSE;
		}
	}
	return $return;
}

//调用系统函数类处理客服接口消息,支持跨公众号请求
function fmFunc_msg_sendCustomNotice($tousers, $msg, $url = NULL, $platid=NULL, $WeAccount = NULL, $msgType=NULL) {
	global $_GPC;
	global $_W;
	global $_FM;
	if(!is_array($tousers)) {
		$tousers =explode(',',$tousers);
		$tousers =array_unique($tousers);
	}
	if(!$tousers){
		return FALSE;
	}
	if(!$WeAccount){
		load() -> model('account');
		$uniacid = ($platid>0) ? $platid : $_W['uniacid'];
		$acid = pdo_fetchcolumn("SELECT acid FROM " . tablename('account_wechats') . " WHERE `uniacid`=:uniacid LIMIT 1", array(':uniacid' => $uniacid));
		$WeAccount = WeAccount::create($acid);
	}
	if (!$WeAccount) {
		return FALSE;
	}
	$content = "";
	if (is_array($msg)) {
		foreach ($msg as $key => $value) {
			if (!empty($value['title'])) {
				$content .= $value['title'] . ":" . $value['value'] . "\r\n";
			} else {
				$content .= $value['value'] . "\r\n";
				if ($key == 0) {
					$content .= "\r\n";
				}
			}
		}
	} else {
		$content = $msg;
	}
	if (!empty($url)) {
		$content .= "<a href='".$url."'>点击查看详情</a>";
	}
	$msgType = ($msgType) ? $msgType : "text";	//text 文本消息； image  图片消息； voice 语音消息;  music 音乐;   news 图文消息
	switch($msgType) {
		case "text":
		break;
		case "image":
		break;
	}
	$return = array();
	foreach($tousers as $touser){
		$postdata = array(
			"touser" => $touser,
			"msgtype" => $msgType,
			"text" => array('content' => urlencode($content))
		);
		$isSent_60 = fmMod_log_query_openid($_W['uniacid'],$do='msg',$tablename='fm453_shopping_logs',$sn,$first=0,$getnum=1,$addons=array('openid'=>$touser,'time'=>array('start'=>$_W['timestamp']-15,'end'=>$_W['timestamp'])));	//强制判断15秒内是否有发送消息的记录
		$isSent_60 = $isSent_60['result'];
		if(!$isSent_60){
			$return[] = $WeAccount -> sendCustomNotice($postdata);
			$fanid=mc_openid2uid($touser);
			$details=$postdata;
			fmMod_log_record($platid=$_W['uniacid'],$uniacid=$_W['uniacid'],$uid='',$fanid,$openid=$touser,$tablename='fm453_shopping_logs',$sn='',$do='msg',$details);
		}else{
			$return[]= FALSE;
		}
	}
	return $return;
}
