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
 * @remark 会员订单中心
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;
fm_load()->fm_model('order');
fm_load()->fm_func('share');//链接分享管理
fm_load()->fm_model('shopcart'); //购物车模块
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
$do=$_GPC['do'];
$ac=$_GPC['ac'];
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'index';
//开始操作管理
$shopname=$settings['brands']['shopname'];
$shopname = !empty($shopname) ? $shopname :FM_NAME_CN;
$pagename = '订单中心';

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
$_share['title'] = $shopname.$pagename.'|'.$_W['account']['name'];
$_share['link'] = fm_murl($do,$ac,$operation,array('isshare'=>1));
$_share['link'] = $_share['link'].$url_condition;
$_share['imgUrl'] = tomedia($settings['brands']['logo']);
$_share['desc'] = $_share['desc'] = $settings['brands']['share_des'];

//初始化一些数据
$allgoods = array();//存入全部产品id\sn
$alltotal = array();
$allpager = array();
$allorder = array();
$allids = array();
$ids = array();
$allorderstatus = fmFunc_status_get('order');
$carttotal = $alltotal['mycart'] = fmMod_shopcart_total();
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

$sql = " SELECT id,ordersn FROM ". tablename('fm453_shopping_order');
$condition .=" AND deleted = :deleted ";
$params[':deleted']=0;
$condition .=" AND fromuid = :fromuid ";
$params[':fromuid']=$currentid;
$condition .=" AND status = :status ";
unset($allorderstatus['code']);
foreach($allorderstatus as $key=>$orderstatus){
	$params[':status']=$orderstatus['value'];
	$allorder[$key] = pdo_fetchall($sql.$condition.$showorder,$params,'id');	//使用订单id进行排序
}

foreach($allorder as $key=>$a_o){
	if(is_array($a_o)) {
		foreach($a_o as $o_i_d => $o_IdSn){
			$allid[$o_i_d]=$o_IdSn;
			if($key=='code-1') {
				$orders['cancelled'][$o_i_d]=$o_IdSn;	//已取消订单
			}elseif($key=='code0') {
				$orders['nopay'][$o_i_d]=$o_IdSn;	//待支付
				$orders['all'][$o_i_d]=$o_IdSn;
			}elseif($key=='code1') {
				$orders['payed'][$o_i_d]=$o_IdSn;	//已支付
				$orders['all'][$o_i_d]=$o_IdSn;
			}elseif($key=='code2') {
				$orders['payed'][$o_i_d]=$o_IdSn;	//已发货
				$alltotal['all'][$o_i_d]=$o_IdSn;
			}elseif($key=='code3') {
				$orders['success'][$o_i_d]=$o_IdSn;	//已完成
				$orders['all'][$o_i_d]=$o_IdSn;
			}
		}
	}
}
$alltotal['nopay']=count($allorder['code0']);
$alltotal['payed']=count($allorder['code1']) + count($allorder['code2']);
$alltotal['success']=count($allorder['code3']);
$alltotal['cancelled']=count($allorder['code-1']);
$alltotal['all']=count($orders['all']);

if($operation=='nopay') {
	$pagename = "未支付订单";
	$subpindex = max(1, intval($_GPC['subpindex']));
	$subpsize = (intval($_GPC['subpsize']) > 0) ? intval($_GPC['subpsize']) : $defaultPsize;
	$maxpages = fm_page($alltotal['nopay'] , $subpsize);
	if(is_array($orders['nopay'])) {
		foreach($orders['nopay'] as $order_nopay){
			$ids[]=$order_nopay;
		}
	}
	$arraystart = ($subpindex-1)*$subpsize +$defaultPsize;
	if($subpindex==1) {
		$arraystart = 0;
	}

	$list=array();
	for($i=0;$i<$subpsize;$i++){
		$tmp_orderid=$ids[$arraystart+$i]['id'];
		$tmp_ordersn=$ids[$arraystart+$i]['ordersn'];
		$result_orderinfo= fmMod_order_detail($tmp_ordersn,$tmp_orderid);
		if($result_orderinfo['result']){
			$list[]=$result_orderinfo['data'];
		}
	}
	if($subpindex>1) {
		include $this->template($appstyle.$do.'/'.$ac.'/'.$operation.'/loadmore');
	}else{
		include $this->template($appstyle.$do.'/453');
	}
}
elseif($operation=='payed') {
	$pagename = "已支付订单";
	$subpindex = max(1, intval($_GPC['subpindex']));
	$subpsize = (intval($_GPC['subpsize']) > 0) ? intval($_GPC['subpsize']) : $defaultPsize;
	$maxpages = fm_page($alltotal['payed'] , $subpsize);
	if(is_array($orders['payed'])) {
		foreach($orders['payed'] as $order_nopay){
			$ids[]=$order_nopay;
		}
	}
	$arraystart = ($subpindex-1)*$subpsize +$defaultPsize;
	if($subpindex==1) {
		$arraystart = 0;
	}

	$list=array();
	for($i=0;$i<$subpsize;$i++){
		$tmp_orderid=$ids[$arraystart+$i]['id'];
		$tmp_ordersn=$ids[$arraystart+$i]['ordersn'];
		$result_orderinfo= fmMod_order_detail($tmp_ordersn,$tmp_orderid);
		if($result_orderinfo['result']){
			$list[]=$result_orderinfo['data'];
		}
	}
	if($subpindex>1) {
		include $this->template($appstyle.$do.'/'.$ac.'/'.$operation.'/loadmore');
	}else{
		include $this->template($appstyle.$do.'/453');
	}
}
elseif($operation=='success') {
	$pagename = "已完成订单";
	$subpindex = max(1, intval($_GPC['subpindex']));
	$subpsize = (intval($_GPC['subpsize']) > 0) ? intval($_GPC['subpsize']) : $defaultPsize;
	$maxpages = fm_page($alltotal['success'] , $subpsize);
	if(is_array($orders['success'])) {
		foreach($orders['success'] as $order_nopay){
			$ids[]=$order_nopay;
		}
	}
	$arraystart = ($subpindex-1)*$subpsize +$defaultPsize;
	if($subpindex==1) {
		$arraystart = 0;
	}

	$list=array();
	for($i=0;$i<$subpsize;$i++){
		$tmp_orderid=$ids[$arraystart+$i]['id'];
		$tmp_ordersn=$ids[$arraystart+$i]['ordersn'];
		$result_orderinfo= fmMod_order_detail($tmp_ordersn,$tmp_orderid);
		if($result_orderinfo['result']){
			$list[]=$result_orderinfo['data'];
		}
	}
	if($subpindex>1) {
		include $this->template($appstyle.$do.'/'.$ac.'/'.$operation.'/loadmore');
	}else{
		include $this->template($appstyle.$do.'/453');
	}
}
 elseif($operation=='all') {
	$pagename = "全部订单";
	$subpindex = max(1, intval($_GPC['subpindex']));
	$subpsize = (intval($_GPC['subpsize']) > 0) ? intval($_GPC['subpsize']) : $defaultPsize;
	$maxpages = fm_page($alltotal['all'] , $subpsize);

	if(is_array($orders['all'])) {
		foreach($orders['all'] as $order_nopay){
			$ids[]=$order_nopay;
		}
	}

	$arraystart = ($subpindex-1)*$subpsize +$defaultPsize;
	if($subpindex==1) {
		$arraystart = 0;
	}

	$list=array();
	for($i=0;$i<$subpsize;$i++){
		$tmp_orderid=$ids[$arraystart+$i]['id'];
		$tmp_ordersn=$ids[$arraystart+$i]['ordersn'];
		$result_orderinfo= fmMod_order_detail($tmp_ordersn,$tmp_orderid);
		if($result_orderinfo['result']){
			$list[$tmp_orderid]=$result_orderinfo['data'];
		}
	}
	krsort($list);	//根据索引键倒序排列
	if($subpindex>1) {
		include $this->template($appstyle.$do.'/'.$ac.'/'.$operation.'/loadmore');
	}else{
		include $this->template($appstyle.$do.'/453');
	}
}
elseif($operation=='cancelled') {
	$pagename = "已取消订单";
	$subpindex = max(1, intval($_GPC['subpindex']));
	$subpsize = (intval($_GPC['subpsize']) > 0) ? intval($_GPC['subpsize']) : $defaultPsize;
	$maxpages = fm_page($alltotal['cancelled'] , $subpsize);
	if(is_array($orders['cancelled'])) {
		foreach($orders['cancelled'] as $order_nopay){
			$ids[]=$order_nopay;
		}
	}
	$arraystart = ($subpindex-1)*$subpsize +$defaultPsize;
	if($subpindex==1) {
		$arraystart = 0;
	}

	$list=array();
	for($i=0;$i<$subpsize;$i++){
		$tmp_orderid=$ids[$arraystart+$i]['id'];
		$tmp_ordersn=$ids[$arraystart+$i]['ordersn'];
		$result_orderinfo= fmMod_order_detail($tmp_ordersn,$tmp_orderid);
		if($result_orderinfo['result']){
			$list[]=$result_orderinfo['data'];
		}
	}
	if($subpindex>1) {
		include $this->template($appstyle.$do.'/'.$ac.'/'.$operation.'/loadmore');
	}else{
		include $this->template($appstyle.$do.'/453');
	}
}
 elseif($operation=='index') {
	//更新流量、链路统计
	fmFunc_view();
	if(!empty($shareid)){
		fmFunc_share_click($lastid,$shareid,$currentid,$share_user);
	}

	include $this->template($appstyle.$do.'/453');
}