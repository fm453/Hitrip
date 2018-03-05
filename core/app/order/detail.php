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
 * @remark 订单详情
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;
load()->func('tpl');
fm_load()->fm_func('status');
fm_load()->fm_model('order');
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
$pagename = '订单详情';

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

$carttotal=fmMod_shopcart_total();

//自定义微信分享内容
$_share = array();
$_share['title'] = $settings['brands']['shopname'].$pagename.'|'.$_W['account']['name'];
$_share['link'] = fm_murl($do,$ac,$operation,array('isshare'=>1));
$_share['link'] = $_share['link'].$url_condition;
$_share['imgUrl'] = tomedia($settings['brands']['logo']);
$_share['desc'] = $_share['desc'] = $settings['brands']['share_des'];

//__________
$id = intval($_GPC['id']);

if (intval($id<=0)) {
		exit(message('抱歉，请先选择一个订单！', fm_murl('order','myorder','index',array()), 'error'));
}
$order=pdo_get('fm453_shopping_order',array('id'=>$id));	//获取订单的基础信息
if(!$order) {
	exit(message('抱歉，该订单信息不存在！', fm_murl('order','myorder','index',array()), 'error'));
}

if($operation=='index') {
	//请求删除订单
	/*
	*订单状态
	*1 已支付
	*2	已发货
	*3	已完成
	*-1 已取消
	*0 未支付
	*/
	if($_GPC['todo']=='delete') {
		//不允许删除已完成3\已支付2\已发货1等有效交易订单
		if(!in_array($order['status'],array(0))){
			exit(json_encode(0));
		}
		if($_W['openid']==$order['from_user']) {
			$result = pdo_update('fm453_shopping_order',array('status'=>'-1'),array('id'=>$id));
			exit(json_encode($result));
		}
	}
	elseif($_GPC['todo']=='recovery') {
		exit(json_encode(0));	//暂时不支持直接恢复订单
		if($_W['openid']==$order['from_user']) {
			$result = pdo_update('fm453_shopping_order',array('status'=>'0'),array('id'=>$id));
			exit(json_encode($result));
		}
	}
	elseif($_GPC['todo']=='complete') {
		if($_W['openid']==$order['from_user']) {
			$result = pdo_update('fm453_shopping_order',array('status'=>'3'),array('id'=>$id));
			exit(json_encode($result));
		}
	}

	$result_order = fmMod_order_detail('',$id);
	$order = $result_order['data'];
	//print_r($order['originaldata']);
	$buyer=$order['buyer'];	//购买者的信息
	//print_r($buyer);
	unset($order['buyer']);
	$allgoods=$order['allgoods'];	//订单内的产品信息
	//print_r($allgoods);
	unset($order['allgoods']);
	$contactinfo=json_decode($order['contactinfo']);	//订单联系信息
	unset($order['contactinfo']);
	$aboutinfos=json_decode($order['aboutinfos']);	//订单更多备注信息
	unset($order['aboutinfos']);
	$logs=$order['logs'];	//订单日志
	unset($order['logs']);
	$originaldata=$order['originaldata'];		//订单原始数据
	unset($order['originaldata']);
	$paylog=$order['paylog'];	//订单支付日志
	unset($order['paylog']);
	$goodstpl=$order['goodstpl'];

	$defaultaddress = fmMod_member_address($currentid,1);	//默认收货地址
	//下面是获取新添加的联系信息、订单更多信息
	$row['username'] =$contactinfo['username'];
	$row['mobile'] =$contactinfo['mobile'];
	$row['mpaccountname'] =$aboutinfos['mpaccountname'];
	$mpaccountname =$row['mpaccountname'];
	$row['ucontainer'] =$aboutinfos['ucontainer'];
	$row['uos'] =$aboutinfos['uos'];
	$goodstplinfos =unserialize($aboutinfos['infos']);
	include_once  FM_CORE.'goodstpl/forapporder.php';   //文件顺序不可更改——BYFM453
	$row['tips']=$tips;
	$dispatch = pdo_fetch("select id,dispatchname from " . tablename('fm453_shopping_dispatch') . " where id=:id limit 1", array(":id" => $item['dispatch']));

	//更新流量、链路统计
	fmFunc_view();
	if(!empty($shareid)){
		fmFunc_share_click($lastid,$shareid,$currentid,$share_user);
	}
	include $this->template($appstyle.$do.'/453');
}
elseif($operation=='repair') {

	include $this->template($appstyle.$do.'/453');
}
