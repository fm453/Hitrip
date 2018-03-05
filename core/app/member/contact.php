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
 * @remark 粉丝填写联系方式
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;
fm_load()->fm_func('share');//链接分享管理
fm_load()->fm_func('view'); 	//浏览量处理
fm_load()->fm_func('wechat');//微信定义管理

//是否关店歇业
fm_checkopen($settings['onoffs']);
//加载风格模板及资源路径
$appstyle = empty($settings['appstyle']) ? FM_APPSTYLE : $settings['appstyle'];
$appsrc =MODULE_URL.'template/mobile/'.$appstyle.'453/';
$htmlsrc =MODULE_URL.'template/mobile/'.$appstyle;
$fm453resource = FM_RESOURCE;
//入口判断
$do= $_GPC['do'];
$ac=$_GPC['ac'];
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'index';
//开始操作管理
$shopname=$settings['brands']['shopname'];
$shopname = !empty($shopname) ? $shopname :FM_NAME_CN;
$pagename = '会员卡';
//$pagename .='|'.$_W['account']['name'];

$uniacid=$_W['uniacid'];
$plattype=$settings['plattype'];
$platids = fmFunc_getPlatids();
$platid=$_W['uniacid'];
$oauthid=$platids['oauthid'];
$fendianids=$platids['fendianids'];
$supplydianids=$platids['supplydianids'];
$blackids=$platids['blackids'];

//平台模式处理
require_once FM_PUBLIC.'plat.php';
$supplydians=explode(',',$supplydianids);//字符串转数组
$supplydians=array_filter($supplydians);//数组去空

//按平台模式前置筛选条件
$condition=' WHERE ';
$params=array();
require_once FM_PUBLIC.'forsearch.php';

$share_user=$_GPC['share_user'];
$shareid = intval($_GPC['shareid']);
$lastid = intval($_GPC['lastid']);
$currentid = intval($_W['member']['uid']);
$fromplatid = intval($_GPC['fromplatid']);
$from_user = $_W['openid'];
$url_condition="";
$direct_url = fm_murl($do,$ac,$operation,array());

//自定义微信分享内容
$_share = array();
$_share['title'] = $settings['brands']['shopname'];
$_share['link'] = fm_murl($do,$ac,$operation,array('isshare'=>1));
$_share['link'] = $_share['link'].$url_condition;
$_share['imgUrl'] = tomedia($settings['brands']['logo']);
$_share['desc'] = $_share['desc'] = $settings['brands']['share_des'];

$operation = $_GPC['op'];
if(empty($operation)) {
	$operation = 'list';
}
if ($operation == 'post') {
	$id = intval($_GPC['id']);
	$data = array(
		'uniacid' => $_W['uniacid'],
		'uid' => $_W['member']['uid'],
		'username' => $_GPC['username'],
		'mobile' => $_GPC['mobile'],
	);
	if (empty($_GPC['username']) || empty($_GPC['mobile'])) {
		message('请完善您的联系姓名与手机号资料，以便客服与您联系哦！');
	}
    if (!empty($id)) {
		unset($data['uniacid']);
		unset($data['uid']);
		pdo_update('mc_member_address', $data, array('id' => $id));
		message($id, '', 'ajax');
	} else {
		pdo_update('mc_member_address', array('isdefault' => 0), array('uniacid' => $_W['uniacid'], 'uid' => $_W['member']['uid']));
		$data['isdefault'] = 1;
		pdo_insert('mc_member_address', $data);
		$id = pdo_insertid();
		if (!empty($id)) {
			message($id, '', 'ajax');
		} else {
			message(0, '', 'ajax');
		}
	}
} elseif ($operation == 'default') {
	$id = intval($_GPC['id']);
	$sql = 'SELECT `isdefault` FROM ' . tablename('mc_member_address') . ' WHERE `id` = :id AND `uniacid` = :uniacid	 AND `uid` = :uid';
	$params = array(':id' => $id, ':uniacid' => $_W['uniacid'], ':uid' => $_W['member']['uid']);
	$mobordercontact = pdo_fetch($sql, $params);
	if (!empty($mobordercontact) && empty($mobordercontact['isdefault'])) {
		pdo_update('mc_member_address', array('isdefault' => 0), array('uniacid' => $_W['uniacid'], 'uid' =>$_W['member']['uid']));
		pdo_update('mc_member_address', array('isdefault' => 1), array('uniacid' => $_W['uniacid'], 'uid' => $_W['member']['uid'], 'id' => $id));
	}
	message(1, '', 'ajax');
} elseif ($operation == 'detail') {
	$id = intval($_GPC['id']);
	$sql = 'SELECT * FROM ' . tablename('mc_member_address') . ' WHERE `id` = :id';
	$row = pdo_fetch($sql, array(':id' => $id));
	message($row, '', 'ajax');
} elseif ($operation == 'remove') {
	$id = intval($_GPC['id']);
	if (!empty($id)) {
		$where = ' AND `uniacid` = :uniacid AND `uid` = :uid';
		$sql = 'SELECT `isdefault` FROM ' . tablename('mc_member_address') . ' WHERE `id` = :id' . $where;
		$params = array(':id' => $id, ':uniacid' => $_W['uniacid'], ':uid' => $_W['member']['uid']);
		$mobordercontact = pdo_fetch($sql, $params);
	if (!empty($mobordercontact)) {
			pdo_delete('mc_member_address', array('id' => $id));
			// 如果删除的是默认联系方式，则设置是新的为默认联系方式
			if ($mobordercontact['isdefault'] > 0) {
				$sql = 'SELECT MAX(id) FROM ' . tablename('mc_member_address') . ' WHERE 1 ' . $where;
				unset($params[':id']);
				$maxId = pdo_fetchcolumn($sql, $params);
				if (!empty($maxId)) {
					pdo_update('mc_member_address', array('isdefault' => 1), array('id' => $maxId));
					die(json_encode(array("result" => 1, "maxid" => $maxId)));
   				}
			}
		}
	}
	die(json_encode(array("result" => 1, "maxid" => 0)));
} else {
	$sql = 'SELECT * FROM ' . tablename('mc_member_address') . ' WHERE `uniacid` = :uniacid AND `uid` = :uid';
	$params = array(':uniacid' => $_W['uniacid']);
	if (empty($_W['member']['uid'])) {
		$params[':uid'] = $_W['fans']['openid'];//这个语法查看会员信息
	} else {
		$params[':uid'] = $_W['member']['uid'];
	}
	$mobordercontacts = pdo_fetchall($sql, $params);
	$carttotal = fmMod_shopcart_total();

	//include $this->template($appstyle.$do.'/453');
	include $this->template($appstyle.$do.'/_old'.$ac.'/index');
}
