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
 * @remark 订单确认
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;
fm_load()->fm_model('category');
fm_load()->fm_model('goods');
fm_load()->fm_model('order');
load()->func('tpl');
fm_load()->fm_func('share');//链接分享管理
fm_load()->fm_model('shopcart'); //购物车模块
fm_load()->fm_func('market');//营销管理
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
$do=$_GPC['do'];;
$ac=$_GPC['ac'];
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'index';
//开始操作管理
$shopname=$settings['brands']['shopname'];
$shopname = !empty($shopname) ? $shopname : '嗨旅行商城';
$pagename = $shopname.'订单确认页';
$pagename .='|'.$_W['account']['name'];

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
$url_condition .= "&lastid=".$shareid;
$url_condition .= "&shareid=".$currentid;
$url_condition .= "&share_user=".$from_user;
$url_condition .= "&fromplatid=".$fromplatid;
$direct_url = fm_murl($do,$ac,$operation,array());

//初始化一些数据
$allgoods = array();//存入全部产品id\sn
$alltotal = array();
$allurl = array();
$isDirect = false; //是否是直接购买(默认是,false否；true是)
$isNeedDispatch=false;	//默认不需要邮费、发货
$dipatchFee=0; //运费,为0时则是免运费
$isCouponAble=false; //是否支持卡券
$totalprice = 0;	//订单总价

$is_wexin = fmFunc_wechat_is();
$userinfo=fmFunc_fans_oauth_getInfo();//网页授权获取头像、昵称等信息；
$carttotal = $alltotal['mycart'] = fmMod_shopcart_total();

//开始整理条件
$nowtime = TIMESTAMP;
$defaultaddress = fmMod_member_address($currentid,1);
$ordertypes = fmMod_order_types();
$goodstypes = fmMod_goods_types();
$_FM_pattypes = fmFunc_pay_types();
$returnUrl = $_W['siteurl']; //当前链接

$id = intval($_GPC['id']);
$sn = $_GPC['sn'];
$optionid = intval($_GPC['optionid']);
$total = max(1,intval($_GPC['total']));

$goodstype =!empty($goodstypes[$_GPC['goodstype']]) ? $_GPC['goodstype'] : 'goods';	//默认为商城产品类订单
$paytype = !empty($_FM_pattypes[$_GPC['paytype']]) ? $_GPC['paytype'] : 'credit2';	//默认支付全部支付方式,使用系统支付方法
$ordertype = !empty($ordertypes[$_GPC['ordertype']]) ? $_GPC['ordertype'] : 'biaozhun'; //默认为标准订单
$goodstitle = !empty($_GPC['goodstitle']) ? $_GPC['goodstitle'] : '快捷支付';
$isdirect = (!$_GPC['direct']) ? TRUE : FALSE;	//是否直接发起的订单（为空时不是;从购物车来时会带有direct标识)
//处理产品信息
if($goodstype=='artcle') {
	//header("Location: ".$url);
	//	exit;
	fm_error('功能还没有开放，请您稍后再来…');
}elseif($goodstype=="goods") {
		//取产品完整信息
	$result_goods=fmMod_goods_detail_all_m($id,$sn);
	$gitems=$result_goods['data'];
}

if($isdirect) {
		//直接从产品页面发起的订单,必须有产品唯一标识
		if($id<=0 && empty($sn)){
			fm_error('亲，您还没有选择要购买的产品或者选择失效了,现在将为您跳转到产品搜索页', '', fm_murl('serach','goods','index',array()));
		}elseif($id>0 && !empty($sn)) {
			//两个参数均有时，判断是否匹配
			$goods = pdo_fetch("SELECT id,goodtpl FROM ".tablename('fm453_shopping_goods')." WHERE id = :id && sn = :sn",array(":id"=>$id,":sn"=>$sn));
			$gid=$goods['id'];
			$goodtpl = $goodstpl = $goods['goodtpl'];

			if($goods) {
//				if($goodstpl=='car') {	//新的产品订单逻辑处理(暂时只处理测试的)
				if($goodstpl) {	//新的产品订单逻辑处理(暂时只处理测试的)
					$url = fm_murl('goods','detail','tobuy',array('id'=>$id,'sn'=>$sn,'optionid'=>$optionid,'total'=>$total));
					header("Location: ".$url);
					exit;
				}else{
					//TODO
				}
			}else{

			}
		}elseif($id>0 && empty($sn)) {
			//$url = fm_murl('goods','detail','tobuy',array('id'=>$id,'sn'=>$sn,'optionid'=>$optionid,'total'=>$total));
			//header("Location: ".$url);
			//exit;
		}elseif(!empty($sn) && $id <=0) {
			//$url = fm_murl('goods','detail','tobuy',array('id'=>$gid,'sn'=>$sn,'optionid'=>$optionid,'total'=>$total));
			//header("Location: ".$url);
			//exit;
		}
		//exit;

if ($id>0) {
	if ($gitems['istime'] == 1) {
		if (time() > $gitems['timeend']) {
			$backUrl = fm_murl('goods','detail','', array('id' => $id));
			message('抱歉，商品限购时间已到，无法购买了；下次要趁早哟！', $backUrl, "error");
		}
	}
	if ($gitems['totalcnf']!=2) {		//添加对减库存方式及库存总量的判断，修改库存不足提示页的返回地址为原产品  ,0拍下减库存，1付款减库存，2永不减库存
		if ($gitems['total']!=-1) {		//-1为无限库存
			if ($gitems['total'] - $total < 0) {
				message('抱歉，[' . $gitems['title'] . ']库存不足！', $backUrl, 'error');
			}
		}
	}
	if (!empty($optionid)) {
		$option = pdo_fetch("select title,marketprice,weight,stock from " . tablename("fm453_shopping_goods_option") . " where id=:id limit 1", array(":id" => $optionid));
		if ($option) {
			$gitems['optionid'] = $optionid;
			$gitems['optionname'] = $option['title'];
			$gitems['marketprice'] = $option['marketprice'];
			$gitems['weight'] = $option['weight'];
		}
	}
	$gitems['stock'] = $gitems['total'];
	$gitems['total'] = $total;
	$gitems['totalprice'] = $total * $gitems['marketprice'];
	$allgoods[] = $gitems;
	$totalprice += $gitems['totalprice'];
	if ($gitems['type'] == 1) { //检测商品类型（实物类型的需要发货）
		if ($gitems['isfreedispatch'] != 1) { //检测商品营销模型类型（免邮费时，不需要发货）
			$isneeddispatch=1;
			$needdispatch =true;
		}
	}

	$direct = true;//修改连接为直接连接
	// 检查用户最多购买数量
	$sql = 'SELECT SUM(`og`.`total`) AS `orderTotal` FROM ' . tablename('fm453_shopping_order_goods') . ' AS `og` JOIN ' . tablename('fm453_shopping_order') .' AS `o` ON `og`.`orderid` = `o`.`id` WHERE `og`.`goodsid` = :goodsid AND `o`.`from_user` = :from_user';
	$params = array(':goodsid' => $id, ':from_user' => $_W['openid']);
	$orderTotal = pdo_fetchcolumn($sql, $params);
	if ( (($orderTotal + $gitems['total']) > $gitems['usermaxbuy']) && (!empty($gitems['usermaxbuy']))) {
		message('您已经超过购买数量限制了', fm_murl('goods', 'detail','index',array('id' => $id)), 'error');
	}
	$returnUrl = urlencode($_W['siteurl']);
}

}elseif (!$direct) {
	//如果不是直接购买（从购物车购买）
	$url = fm_murl('confirm','fromcart','index',array('ids'=>$ids));//新的产品订单逻辑处理(暂时只处理酒+X套餐的)
	header("Location: ".$url);
	exit;
}
//配送方式
if ($isneeddispatch >0) {
	$dispatch = pdo_fetchall("select id,dispatchname,dispatchtype,firstprice,firstweight,secondprice,secondweight from " . tablename("fm453_shopping_dispatch") . " WHERE uniacid = {$_W['uniacid']} order by displayorder desc");
	foreach ($dispatch as &$d) {
		$weight = 0;
		foreach ($allgoods as $g) {
			$weight += $g['weight'] * $g['total'];
		}
		$price = 0;
		if ($weight <= $d['firstweight']) {
			$price = $d['firstprice'];
		}else{
			$price = $d['firstprice'];
			$secondweight = $weight - $d['firstweight'];
			if ($secondweight % $d['secondweight'] == 0) {	//整除取余数
				$price += (int)($secondweight / $d['secondweight']) * $d['secondprice'];
			} else {
				$price += (int)($secondweight / $d['secondweight'] + 1) * $d['secondprice'];
			}
		}
		$d['price'] = $price;
	}
	unset($d);
}

if (checksubmit('submit')) {
	// 是否自提或快递，对应dispatchtype ,0是先付款后发货；1是货到付款； 2是实物自提
	$sendtype = 0;
	// 是否实物商品
	//$type = 1;
	//$type = pdo_fetchall("select type from" . tablename('fm453_shopping_goods') . " WHERE id = :id", array(':id' => $goodsid));
	$address = pdo_fetch("SELECT * FROM " . tablename('mc_member_address') . " WHERE id = :id", array(':id' => intval($_GPC['address'])));
	//新增手机订单联系方式调用
	$MobOrderContact = $address;
	if (empty($MobOrderContact)) {
		message('抱歉，请您填写联系姓名与电话！');
	}
	//将联系人姓名电话直接写进订单；
	$contactinfo=array(
		'username' =>$MobOrderContact['username'],
		'mobile' =>$MobOrderContact['mobile']
	);
	$contactinfo=serialize($contactinfo);//将数组序列化以便存入数据库；再后台订单读取信息时要执行反序列化操作,可使用PHP自带的unserialize或者系统内的iunserializer(系统自带的方法不适用，请注意)。
	//来源用户的Uid；
	//$fromuid=$_W['member']['uid'];
	//产品模型表单;
	include_once  FM_CORE.'goodstpl/appconfirm.php';   //require请求失败则程序不继续，用include
	//关于订单的更多信息(子公众号名称、用户终端\OS、产品模型关联的订单信息提前表单内容)
	$aboutinfos=array(
		'mpaccountname' =>$_W['account']['name'],
		'ucontainer' =>$_W['container'],
		'uos' =>$_W['os'],
		'goodstpl' =>$goodstpl,
		'infos' =>$goodstplinfos
	);
	$aboutinfos=serialize($aboutinfos);
	// 商品价格
	$goodsprice = 0;
	foreach ($allgoods as $row) {
		$goodsprice += $row['totalprice'];
	}
	// 运费
	$dispatchid = intval($_GPC['dispatch']);
	$dispatchprice = 0;
	if(is_array($dispatch)){
		foreach ($dispatch as $d) {
			if ($d['id'] == $dispatchid) {
				$dispatchprice = $d['price'];
				$sendtype = $d['dispatchtype'];
			}
		}
	}
	//订单总价不足商城最低配送金额时，自动加上预设的运费
	$orderpirce=$goodsprice + $dispatchprice;
	if($orderpirce<$settings['free_dispatch']){
		$orderpirce +=$settings['free_dispatch_price'];
		$dispatchprice +=$settings['free_dispatch_price'];
	}

	$data = array(
		'uniacid' => $_W['uniacid'],
		'from_user' => $from_user,
		'fromuid' => $currentid,
		'ordersn' => date('ymdsih') . random(4, 1),
		'price' => $orderpirce,
		'dispatchprice' => $dispatchprice,
		'goodsprice' => $goodsprice,
		'status' => 0,
		'sendtype' => intval($sendtype),
		'dispatch' => $dispatchid,
		'goodstype' => intval($item['type']),
		'remark' => $_GPC['remark'],
		'addressid' => $MobOrderContact['id'],
		'contactinfo' => $contactinfo,
		'aboutinfos' => $aboutinfos,
		'sharedfrom' => $fromplatid,	//来源公众号
		'createtime' => TIMESTAMP,
		'shareid' => $shareid
	);
	pdo_insert('fm453_shopping_order', $data);
	$orderid = pdo_insertid();

	//插入订单商品
	foreach ($allgoods as $row) {
		if (empty($row)) {
			continue;
		}
		$d = array(
			'uniacid' => $_W['uniacid'],
			'goodsid' => $row['id'],
			'orderid' => $orderid,
			'total' => $row['total'],
			'price' => $row['marketprice'],
			'createtime' => TIMESTAMP,
			'optionid' => $row['optionid']
		);
		$o = pdo_fetch("select title from " . tablename('fm453_shopping_goods_option') . " where id=:id limit 1", array(":id" => $row['optionid']));
		if (!empty($o)) {
				$d['optionname'] = $o['title'];
		}
		//获取商品id
		$ccate = $row['ccate'];
		$commission = pdo_fetchcolumn( " SELECT commission FROM ".tablename('fm453_shopping_goods')."  WHERE id=".$row['id']);
		$commission2 = pdo_fetchcolumn( " SELECT commission2 FROM ".tablename('fm453_shopping_goods')."  WHERE id=".$row['id']);
		$commission3 = pdo_fetchcolumn( " SELECT commission3 FROM ".tablename('fm453_shopping_goods')."  WHERE id=".$row['id']);
		if($commission == false || $commission == null || $commission <0){
			$commission = $_FM['settings']['fenxiao']['globalCommission'];
		}
		if($commission2 == false || $commission2 == null || $commission2 <0){
			$commission2 = $_FM['settings']['fenxiao']['globalCommission2'];
		}
		if($commission3 == false || $commission3 == null || $commission3 <0){
			$commission3 = $_FM['settings']['fenxiao']['globalCommission3'];
		}

		$commissionTotal = $row['marketprice']  * $commission /100;	//一级佣金
		$d['commission'] = $commissionTotal;
		$commissionTotal2 = $row['marketprice']  * $commission2 /100;	//二级佣金
		$d['commission2'] = $commissionTotal2;
		$commissionTotal3 = $row['marketprice']  * $commission3 /100;	//三级佣金
		$d['commission3'] = $commissionTotal3;

		pdo_insert('fm453_shopping_order_goods', $d);
	}

	// 清空购物车
	if (!$direct) {
		pdo_delete("fm453_shopping_cart", array("uniacid" => $_W['uniacid'], "from_user" => $_W['openid']));
	}
	// 变更商品库存
	if ($gitems['totalcnf']==0) {	//,0拍下减库存，1付款减库存，2永不减库存；当前是拍下减库存的方式时，在支付前减
		fmFunc_order_setStock($orderid,$minus=true,$gtype);
	}
		message('提交订单成功,现在跳转到支付中心...', fm_murl('confirm', 'pay', '', array('orderid' => $orderid)), 'success');
}

$carttotal = fmMod_shopcart_total();//重新获取一次购物车数量
$profile = fans_search($_W['openid'], array('resideprovince', 'residecity', 'residedist', 'address', 'realname', 'mobile'));//从粉丝表中获取收货地址

	//自定义页面默认的分享内容
$_share = array();
$_share['title']=$shopname;
$_share['link']=fm_murl('confirm','order','index',array('id'=>$id,'sn'=>$sn,'optionid'=>$optionid,'total'=>$total));
$_share['link']=$_share['link'].$url_condition;
$_share['imgUrl']=tomedia($settings['brands']['logo']);
$_share['desc']="我正在".$shopname."(".$_W['uniacid']['name'].")"."下订单，把页面链接给你";

if(!empty($shareid)){
	fmFunc_share_click($lastid,$shareid,$currentid,$share_user);
}
//include $this->template($appstyle.'confirm');
include $this->template($appstyle.$do.'/old_'.$ac.'/index');
