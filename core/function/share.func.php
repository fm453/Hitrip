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
 * @remark：分享记录处理函数
 */
defined('IN_IA') or exit('Access Denied');

/*
分享链接被点击处理
@lastid 上一级分享人（会员id）
@shareid 本链接的分享人（会员id）
@currentid 点击人（当前会员id）
@share_user	本链接分享人的openid
@url 本链接
*/
function fmFunc_share_click($lastid,$shareid,$currentid,$share_user,$url=null) {
	global $_GPC;
	global $_W;
	global $_FM;
	load()->model('mc');
	$settings = $_FM['settings'];
	$shopname = $settings['brands']['shopname'];
	$url = !empty($url) ? $url : $_W['siteurl'];
	$arguments = fm_url_clean($url);
	$do = isset($arguments['do']) ? $arguments['do'] : 'index';
	$ac = isset($arguments['ac']) ? $arguments['ac'] : 'index';
	$op = isset($arguments['op']) ? $arguments['op'] : 'index';
	$id = isset($arguments['id']) ? intval($arguments['id']) : 0;
	$sn = isset($arguments['sn']) ? intval($arguments['sn']) : 0;

	//传入上次分享人ID、本次分享人ID，当前会员ID，本次分享人openid,来路url
	$need_rec=1;
	$need_notice=1;
	//第一步，查询上次点击记录，判断是否需要进行下一步的记录；同一来路被同一人点击时，15分钟内仅计算一次;分享者点击自己的链接时，不记录、不通知
	if(!empty($shareid)){
		$share_user = fmMod_member_uid2openid($shareid);
		$share_user = $share_user['data'];
		if($shareid==$currentid){
			$need_notice = 0;
			$need_rec = 0;
		}
	}

	$share = pdo_fetch("SELECT from_user,createtime, url FROM " . tablename('fm453_shopping_share_history') . " WHERE shareid =:shareid AND from_user=:from_user AND do=:do AND ac=:ac AND op=:op AND obj_id=:id AND obj_sn=:sn AND uniacid=:uniacid ORDER BY id DESC ", array(':shareid' => $shareid,':from_user' =>$_W['openid'],':do'=>$do,':ac'=>$ac,':op'=>$op,':id'=>$id,':sn'=>$sn,':uniacid' => $_W['uniacid']));

	//分销规则
	$fx_rule = $_FM['settings']['fenxiao'];
	$clicktime = isset($_FM['settings']['fenxiao']['clicktime']) ? intval($_FM['settings']['fenxiao']['clicktime']) : 15*60;

	if($share){
		$_arguments = fm_url_clean($share['url']);
		$_expiretime = isset($_arguments['expiretime']) ? intval($_arguments['expiretime']) : 24*60*60;	//默认链接有效期为24小时
		//有效期内点击时才允许知会
		if($share['createtime'] <= (TIMESTAMP-$_expiretime)) {
			$need_notice=0;
		}

		//15分钟内的点击才允许记录
		if($share['createtime'] <= (TIMESTAMP-$clicktime)) {
			$need_rec=0;
		}

	}else{
		//完全无关联记录时
	}

	//如果点击的是本人分享出去后被分享回来的链接，不通知
	if($lastid == $currentid){
		$need_notice=0;
	}

	switch($need_rec) {
		case 1:
		//第二步，记录链接点击
		$data = array(
			'uniacid' => $_W['uniacid'],
			'lastid' => $lastid,
			'shareid' => $shareid,
			'mid' => $currentid,
			'from_user' => $_W['openid'],
			'share_user' => $share_user,
			'do' => $do,
			'ac' => $ac,
			'op' => $op,
			'obj_id' => $id,
			'obj_sn' => $sn,
			'url' => $url,
			'createtime' => TIMESTAMP
		);
		pdo_insert('fm453_shopping_share_history', $data);
		//第三步，取当本次分享人的资料;仅从商城会员表中加载
		$member = pdo_fetch('SELECT shareid,flagtime,status,flag,clickcount FROM ' . tablename('fm453_shopping_member') . " WHERE uniacid = '{$_W['uniacid']}' AND uid = '{$shareid}'");
		if(!empty($member)){
			pdo_update('fm453_shopping_member', array('clickcount' => $member['clickcount']+1), array('id' => $shareid));
			//根据分销规则计算相应的积分
			if(!empty($fx_rule['clickcredit'])){
				$tmptag='嗨旅行商城分享链接点击，获赠积分。链接地址：'.$url;
				//赠送信息
				$array=array(
					'module'=>FM_NAME,
					'sign'=>$currentid,
					'action'=>'share',
					'credit_value'=>$fx_rule['clickcredit'],
					'credit_log'=>$tmptag
				);
				mc_handsel($shareid, -1, $array,$_W['uniacid']);
			}
		}

		//第四步，发送消息通知
	if($need_notice){
		$WeAccount = fmFunc_wechat_weAccount();
		require_once MODULE_ROOT.'/core/msgtpls/tpls/task.php';
		//发任务处理通知
		$result=array();
		$postdata = $tplnotice_data['task']['share']['sharefrom'];
		$result=fmMod_notice_tpl($postdata, $platid=NULL, $WeAccount);
		//模板消息发送失败时，采用客服消息接口给分享者发个微信消息
		if($result['errno']==-1) {
			require_once MODULE_ROOT.'/core/msgtpls/msg/task.php';
			$noticedata = $notice_data['task']['share']['sharefrom'];
			$result=fmMod_notice($share_user,$noticedata,$platid=NULL, $WeAccount);
		}
	}
		break;

		default:
		break;
	}
}

/**
* 检查是否为分享链接
*/
function fmFunc_share_check($url){
	global $_W;
	global $_GPC;
	global $_FM;
	fm_load()->fm_func('url');
	$_urladdons = fm_url_clean($url);
	if($_GPC['shareid'] && $_urladdons['shareid']){
		if($_GPC['shareid'] == $_urladdons['shareid'] && $_GPC['shareid'] != $_W['member']['uid']){
			return true;
		}
	}
	return false;
}
