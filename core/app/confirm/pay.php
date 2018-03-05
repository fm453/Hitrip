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
 * @remark 支付准备
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;
fm_load()->fm_func('share');//链接分享管理
fm_load()->fm_model('shopcart'); //购物车模块
fm_load()->fm_func('market');//营销管理
fm_load()->fm_func('view'); 	//浏览量处理
fm_load()->fm_func('pay');	//支付后处理

//加载风格模板及资源路径
$appstyle = empty($settings['appstyle']) ? FM_APPSTYLE : $settings['appstyle'];
$appsrc =MODULE_URL.'template/mobile/'.$appstyle.'453/';
$htmlsrc =MODULE_URL.'template/mobile/'.$appstyle;
$fm453resource = FM_RESOURCE;
//入口判断
$do= $_GPC['do'];
$ac= $_GPC['ac'];
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'index';
//开始操作管理
$shopname=$settings['brands']['shopname'];
$shopname = !empty($shopname) ? $shopname :FM_NAME_CN;
$pagename = $shopname.'支付中心';

$uniacid=$_W['uniacid'];
$plattype=$settings['plattype'];
$platids = fmFunc_getPlatids();//获取平台模式关联的公众号商户ID参数
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

$orderid = intval($_GPC['id'])>0  ? intval($_GPC['id']) : (intval($_GPC['orderid'])>0 ? intval($_GPC['orderid']) : 0);
$id = $orderid;

$order = pdo_fetch("SELECT * FROM " . tablename('fm453_shopping_order') . " WHERE id = :id", array(':id' => $id));

$sn = $ordersn =$order['ordersn'];
$orderstatus=$order['status'];
$sendtype=$order['sendtype'];
$paytype=$order['paytype'];
$carttotal = fmMod_shopcart_total();
if ($order['status'] != '0') {
	message('抱歉，您的订单已经付款或是被关闭，现在将进入到您的订单列表！', fm_murl('order','myorder','index',array()), 'error');
}

if (checksubmit()) {
	if ($order['paytype'] == 1 && $_W['fans']['credit2'] < $order['price']) {
	     //判断为余额支付方式形式，用户余额需足够才能支付成功；
		message('抱歉，您账户的余额不够支付该订单，请充值！', create_url('mobile/module/charge', array('name' => 'member', 'uniacid' => $_W['uniacid'])), 'error');
		}
	 //订单产品价格为0时，直接显示支付成功；
	if ($order['price'] == '0') {
		$this->payResult(array('tid' => $ordersn, 'from' => 'return', 'type' => 'credit2'));
		exit();
	}
}
// 检索商品编号
$sql = 'SELECT `goodsid` FROM ' . tablename('fm453_shopping_order_goods') . " WHERE `orderid` = :orderid";
$goodsId = pdo_fetchall($sql, array(':orderid' => $orderid));    //同一订单的商品可能不只一个，这里全部调用
$totalIds=count($goodsId);      //计算订单包含的商品类型总数
$goodsTitle='';
$sql = 'SELECT `title` FROM ' . tablename('fm453_shopping_goods') . " WHERE `id` = :id";
foreach ($goodsId as $row) {
    $goodsTitle .= pdo_fetchcolumn($sql, array(':id' => $row['goodsid'])).';';
}
if($totalIds>1) {
	$goodsTitle =$shopname.'的多个商品';
}
	//定义支付参数
	unset($params);
	$params['tid'] = $ordersn;	//从本商城传入的订单号
	$params['user'] = $_W['openid'];	//支付的用户,统一使用OPENID
	$params['fee'] = $order['price'];	//要支付的金额(>0)
	$params['title'] = $goodsTitle;			//支付界面显示的产品标题
	$params['ordersn'] = $order['ordersn'];	//支付界面显示的订单号
	$params['virtual'] = $order['goodstype'] == 2 ? true : false;	//产品类型，虚拟还是实体

//自定义微信分享内容
$_share = array();
$_share['title'] = $settings['brands']['shopname'].$pagename.'|'.$_W['account']['name'];
$_share['link'] = $direct_url = fm_murl($do,$ac,$operation,array('id'=>$id,'isshare'=>1));
$_share['link'] = $_share['link'].$url_condition;
$_share['imgUrl'] = tomedia($settings['brands']['logo']);
$_share['desc'] ="我刚刚在 $shopname 下了订单，现正在支付";

include $this->template($appstyle.$do.'/453');
