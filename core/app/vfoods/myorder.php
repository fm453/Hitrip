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
 * @remark 用户订单
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;
load()->func('tpl');
fm_load()->fm_func('share');//链接分享管理
fm_load()->fm_func('view'); 	//浏览量处理
fm_load()->fm_func('wechat');//微信定义管理

//加载风格模板及资源路径
$appstyle      =empty($settings['appstyle']) ? FM_APPSTYLE : $settings['appstyle'];
$appsrc        =MODULE_URL.'template/mobile/'.$appstyle.'453/';
$htmlsrc       =MODULE_URL.'template/mobile/'.$appstyle;
$fm453resource = FM_RESOURCE;
//入口判断
$do            = $_GPC['do'];
$ac            =$_GPC['ac'];
$op            = !empty($_GPC['op']) ? $_GPC['op'] : 'index';

//开始操作管理
$shopname      =$settings['brands']['shopname'];
$shopname      = !empty($shopname) ? $shopname :FM_NAME_CN;

$uniacid       =$_W['uniacid'];
$plattype      =$settings['plattype'];
$platids       =fmFunc_getPlatids();//获取平台模式关联的公众号商户ID参数
$platid        =$_W['uniacid'];
$oauthid       =$platids['oauthid'];
$fendianids    =$platids['fendianids'];
$supplydianids =$platids['supplydianids'];
$blackids      =$platids['blackids'];

//平台模式处理
require_once FM_PUBLIC.'plat.php';
$supplydians   =explode(',',$supplydianids);//字符串转数组
$supplydians   =array_filter($supplydians);//数组去空

//按平台模式前置筛选条件
$condition     =' WHERE ';
$params        =array();
require_once FM_PUBLIC.'forsearch.php';

$share_user    =$_GPC['share_user'];
$shareid       = intval($_GPC['shareid']);
$lastid        = intval($_GPC['lastid']);
$currentid     = intval($_W['member']['uid']);
$fromplatid    = intval($_GPC['fromplatid']);
$from_user     = $_W['openid'];
$url_condition ="";
$direct_url    = fm_murl($do,$ac,$op,array());

//自定义微信分享内容
$_share           = array();
$_share['title']  = $shopname.'|'.$_W['account']['name'];
$_share['link']   = fm_murl($do,$ac,$operation,array('isshare'=>1));
$_share['link']   = $_share['link'].$url_condition;
$_share['imgUrl'] = tomedia($settings['brands']['logo']);
$_share['desc']   = $settings['brands']['share_des'];

// $resultmember     = fmMod_member_query($currentid);
// $FM_member        =$resultmember['data'];

//会员自定义设置
$mine_settings    =$_FM['member']['settings'];

//初始化一些数据
$allgoods = array();//全部产品id\sn
$alltotal = array();
$allpager = array();
$allorder = array();
$allids = array();
$ids = array();
$allorderstatus = 0;
$carttotal = $alltotal['mycart'] = 0;
$alltotal['comment'] = 0;	//点评订单量

//开始整理搜索条件
$OnlyThisMp = ($_GPC['fromplats'] != $_W['uniacid']) ? FALSE : TRUE;
$nowtime = TIMESTAMP;
//分页设置
$pindex = max(1, intval($_GPC['page']));
$defaultPsize = 3;
$psize = (intval($_GPC['psize'])>0) ? intval($_GPC['psize']) : $defaultPsize;

//排序及截断
$showorder = " ORDER BY uniacid ASC , createtime DESC ";
$limit = " LIMIT ".($pindex - 1) * $psize . ',' . $psize;


//页面具体操作
if(empty($_W['fans']['from_user'])){
	checkauth();
}
//MUI侧边栏链接
$shoptype = pdo_fetchall("SELECT * FROM ".tablename('fm453_vfoods_shoptype')." WHERE uniacid = '{$_W['uniacid']}' ");	//全部店家
$appNavs=array();
require_once FM_APP.$do.DIRECTORY_SEPARATOR.'_aside.php';

$ordertype = $_GPC['ordertype'];

if($op == 'shanchu' || $op == 'delete'){
	$ccate2 = pdo_fetchall("SELECT * FROM ".tablename('fm453_vfoods_foods')." WHERE uniacid = '{$_W['uniacid']}' AND ccate = '{$_GPC['ccate']}' ");
$pcate2 = pdo_fetchall("SELECT * FROM ".tablename('fm453_vfoods_category')." WHERE uniacid = '{$_W['uniacid']}' AND (id = '{$_GPC['pcate']}' OR id = '{$ccate2[0]['pcate']}') ORDER BY psn ASC, displayorder DESC");
	pdo_update('fm453_vfoods_order', array('status' => -2), array('id' => $_GPC['id']));
	message('删除订单成功。', fm_murl($do,$ac, '', array('pcate'=>$_GPC['pcate'],'ccate'=>$_GPC['ccate'])), 'success');

}elseif($op == 'load'){
	$ccate2 = pdo_fetchall("SELECT * FROM ".tablename('fm453_vfoods_foods')." WHERE uniacid = '{$_W['uniacid']}' AND ccate = '{$_GPC['ccate']}' ");

	$pcate2 = pdo_fetchall("SELECT * FROM ".tablename('fm453_vfoods_category')." WHERE uniacid = '{$_W['uniacid']}' AND (id = '{$_GPC['pcate']}' OR id = '{$ccate2[0]['pcate']}') ORDER BY psn ASC, displayorder DESC");
	$pindex = max(1, intval($_GPC['page']));
	$psize = 15;

	switch($ordertype){
		case 'waimai':
			$orderStr = " AND `ordertype`=1";
			break;
		case 'tangshi':
			$orderStr = " AND `ordertype`=2";
			break;
		case 'ziqu':
			$orderStr = " AND `ordertype`=3";
			break;
		default:
			$orderStr = "";
			break;
	}

	$list = pdo_fetchall("SELECT * FROM ".tablename('fm453_vfoods_order')." WHERE uniacid = '{$_W['uniacid']}' AND from_user = '{$_W['fans']['from_user']}' AND status != -2".$orderStr." ORDER BY id DESC LIMIT ".($pindex - 1) * $psize.','.$psize, array(), 'id');

	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fm453_vfoods_order') . " WHERE uniacid = '{$_W['uniacid']}' AND from_user = '{$_W['fans']['from_user']}' AND status != -2 ");
	$alltotal['all'] = $total;

	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fm453_vfoods_order') . " WHERE uniacid = '{$_W['uniacid']}' AND from_user = '{$_W['fans']['from_user']}' AND status != -2 AND `ordertype`=1");
	$alltotal['waimai'] = $total;

	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fm453_vfoods_order') . " WHERE uniacid = '{$_W['uniacid']}' AND from_user = '{$_W['fans']['from_user']}' AND status != -2 AND `ordertype`=2");
	$alltotal['tangshi'] = $total;

	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fm453_vfoods_order') . " WHERE uniacid = '{$_W['uniacid']}' AND from_user = '{$_W['fans']['from_user']}' AND status != -2 AND `ordertype`=3");
	$alltotal['ziqu'] = $total;

	$pager = pagination($total, $pindex, $psize);
	if (!empty($list)) {
		foreach ($list as &$row) {
			$foodsid = pdo_fetchall("SELECT foodsid,total FROM ".tablename('fm453_vfoods_order_foods')." WHERE orderid = '{$row['id']}'", array(), 'foodsid');
			$foods = pdo_fetchall("SELECT id, pcate, title, thumb, preprice, oriprice, unit FROM ".tablename('fm453_vfoods_foods')."  WHERE id IN ('".implode("','", array_keys($foodsid))."')");
			$pcate3 = pdo_fetchall("SELECT * FROM ".tablename('fm453_vfoods_category')." WHERE uniacid = '{$_W['uniacid']}' AND id = '{$foods[0]['pcate']}' ORDER BY psn ASC, displayorder DESC");
			$row['pcate3'] = $pcate3;
			$row['foods'] = $foods;
			$row['total'] = $foodsid;
		}
	}

}elseif($op == 'detail'){
	$orderid = intval($_GPC['id']);	//传入了ID，是订单详情
	$detail = pdo_fetch("SELECT * FROM ".tablename('fm453_vfoods_order')." WHERE `id`=:id", array(':id'=>$orderid));
	$foodsid = pdo_fetchall("SELECT foodsid,total FROM ".tablename('fm453_vfoods_order_foods')." WHERE orderid = :id", array(':id'=>$orderid), 'foodsid');
	$foods = pdo_fetchall("SELECT id, pcate, title, thumb, preprice, oriprice, unit FROM ".tablename('fm453_vfoods_foods')."  WHERE id IN ('".implode("','", array_keys($foodsid))."')");
	//关联商家
	$pcate3 = pdo_fetch("SELECT * FROM ".tablename('fm453_vfoods_category')." WHERE uniacid = '{$_W['uniacid']}' AND id = '{$foods[0]['pcate']}'");
	$detail ['shop'] = $pcate3;
	$detail ['foods'] = $foods;
	$detail ['total'] = $foodsid;

	$item = $detail;

	//生成核销页面二维码
	$link_preview =  fm_murl('appwebvfoods','order','detail',array('id' => $orderid));
	$qrcode=fmFunc_qrcode_name_m($platid,'vfoods','order','detail',$id,0,0);
	fmFunc_qrcode_check($qrcode,$link_preview);
	$qrcode_preview=tomedia($qrcode);
}

if($op=="index"){
	//更新流量、链路统计
	if(!empty($shareid)){
		fmFunc_share_click($lastid,$shareid,$currentid,$share_user);
	}
	fmFunc_view();//记录访问
	// fmMod_member_check($_W['openid']);//检测会员
}
include fmFunc_template_m($do.'/453');
