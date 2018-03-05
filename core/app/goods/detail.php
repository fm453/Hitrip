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
 * @remark 产品详情页
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;
load()->func('tpl');
fm_load()->fm_model('ad');
fm_load()->fm_model('category');
fm_load()->fm_model('goods');
fm_load()->fm_model('order');
fm_load()->fm_func('express');
fm_load()->fm_func('order');
fm_load()->fm_func('share');//链接分享管理
fm_load()->fm_model('shopcart'); //购物车模块
fm_load()->fm_func('market');//营销管理
fm_load()->fm_func('view'); 	//浏览量处理
fm_load()->fm_func('wechat');//微信定义管理

if(intval($_GPC['i'])<=0) {
	$_W['uniacid']=1;
	$_GPC['i']=1;
}
$id = intval($_GPC['id']);
$sn = $_GPC['sn'];
if($id<=0) {
	fm_error('未传入产品id值');
}

//是否需要授权登陆
$checklogin=$_GPC['checklogin'];
if($checklogin) {
	checkauth();
}

fm_checkopen($settings['onoffs']);

$is_wexin = fmFunc_wechat_is();
$userinfo=fmFunc_fans_oauth_getInfo();//网页授权获取头像、昵称等信息；

$appstyle = empty($settings['appstyle']) ? FM_APPSTYLE : $settings['appstyle'];
$appsrc =MODULE_URL.'template/mobile/'.$appstyle.'453/';
$htmlsrc =MODULE_URL.'template/mobile/'.$appstyle;
$fm453resource = FM_RESOURCE;

$do= $_GPC['do'];
$ac=$_GPC['ac'];
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'index';

$shopname=$settings['brands']['shopname'];
$shopname = !empty($shopname) ? $shopname :FM_NAME_CN;
$pagename = '产品详情';

$carttotal = fmMod_shopcart_total();

$share_user=$_GPC['share_user'];
$shareid = intval($_GPC['shareid']);
$lastid = intval($_GPC['lastid']);
$currentid = intval($_W['member']['uid']);
$mySysInfo = fmMod_member_query($currentid)['data']; //系统会员信息
 $myShopInfo = fmMod_member_query($currentid)['data'];	//商城会员信息

$fromplatid = intval($_GPC['fromplatid']);
$from_user = $_W['openid'];//当前用户
$url_condition="";

//COOKIE读写-待
$day_cookies = 15;
if((($_GPC['mid']!=$_COOKIE[$shareid]) && !empty($_GPC['mid']))){
	setcookie($shareid, $_GPC['mid'], time()+3600*24*$day_cookies);
}

//产品详情
$result_goods = fmMod_goods_detail_all_m($id,$sn);
$goods=$result_goods['data'];
$goodtpl = $goodstpl=$goods['goodtpl'];
$thumbs = $goods['thumbs'];
$sharethumb = tomedia(!empty($goods['sharethumb']) ? $goods['sharethumb'] : $settings['brands']['logo']);

$lastthumb = $thumbs[count($thumbs)-1];
$params = $goods['params'];
$options = $goods['options'];
$specs = $goods['specs'];
$buylinks = $goods['buylink'];
$marketmodels = $goods['marketmodel'];
$commissions = $goods['commissions'];
$hasoption = $goods['hasoption'];
$goodsstatus = $goods['status'];
$title = $goods['title'];
$marketprice = $goods['marketprice'];
$cankaoprice= $goods['cankaoprice'];
$originalprice = $goods['originalprice'];
$stock = $goods['stock'];
$maxbuy = $goods['maxbuy']; // 单次最多购买量

//开始根据规则进行前端个别场景处理
$pagename = $title;
$kefuphone = ($goods['kefuphone']) ? $goods['kefuphone'] :  $settings['brands']['phone'];
$fromspecial=$_GPC['special'];//是否来自于某个专题页面
$url_condition .= "&special=".$fromspecial;
$direct_url = fm_murl('goods','detail', 'index',array('id' => $id, 'sn'=>$sn));

$isavailable= ($goodsstatus>0) ? 1 : 0;//产品是否可用 1是0否
$isshareable=1;//产品是否可分享 1是0否（可分享时会添加特别当前匹配的营销规则链接）
$allowedtobebuy=1; //1允许购买，0不允许购买
$disableComment = true;    //是否开启评论
if($_W['uniacid']==96){
    $disableComment = false;
}

//时间规则
if ($goods['istime'] == 1) {
	if (time() < $goods['timestart']) {
		if($fromspecial !=='presale'){ //未从预售专题页（或带有预售专题页来路标识时，如直接输入网址）抵达时，使用常规的时效限制进行判断
			$timenotstarted=1;
			$allowedtobebuy=0;
		}else {//从预售专题页（或带有预售专题页来路标识时，如直接输入网址）抵达时，进行进一步判断
			if($goods['marketmodel']['ispresale'] !=1){
				fm_error('抱歉，该产品非预售产品！');
			}else{
				if(!empty($buylinks['presale']['hidden'])){
					fm_error('抱歉，您的访问网址有错(非法访问)！');
				}
			}
		}
	}
	if (time() > $goods['timeend']) {
		$timeended=1;
		$allowedtobebuy=0;
	}
}

//处理规格项(在启用商品规格的前提下)
if($hasoption ==1){
	if(count($specs)==1) {
		//仅有一种规格类时,标记出0库存或者0价格的规格项，并为其对应的前台样式加上hidden类
		$empty_options =array();
		foreach($options as $tmp_option){
			$empty_oids[$tmp_option['specs']] = ($tmp_option['stock']==0 || $tmp_option['marketprice']==0) ? 'hidden' : '';
		}
	}

	//启用规格项但获取规格为空的情况下，产品不可用
	if(empty($specs)){
		$isavailable=0;
	}
}

//取配送方式
$dispatch = fmFunc_express_dispatch_list();

//自定义微信分享内容
$_share = array();
$_share['title'] = $goods['title'];
$_share['link'] = fm_murl($do,$ac,'index',array('id' => $id, 'sn'=>$sn,'isshare'=>1));
$_share['link'] = $_share['link'].$url_condition;
$_share['imgUrl'] = $sharethumb;
$_share['desc'] = $goods['description'];

if($operation=="tobuy"){
	checkauth();	//购买下单前需要登陆
	//直接进入购买步骤
	if($isavailable !=1) {
		fm_error('Oh,当前产品已经无效,您可以再看看其他的哦！');
	}
	if($allowedtobebuy !=1) {
		fm_error('Oh,当前产品还不能购买,您可以再看看其他的哦！');
	}
	$pagename = "订单确认";
	$gtype = $goods['type'];	//1实物 2虚拟
	$sendtype = 0; // 是否自提或快递，对应dispatchtype ,0是先付款后发货；1是货到付款； 2是实物自提
	$isDirect = true; //是否是直接购买(默认是,false否；true是)
	$isNeedDispatch= ($goods['type']==1) ? TRUE : FALSE;	//实物类型需要发货，虚拟类型不用；默认不需要,
	$dipatchFee=0; //运费,为0时则是免运费
	$isCouponAble=true; //是否支持卡券,默认开启，能否使用还取决于全局的卡券权限
	$defaultaddress = fmMod_member_address($currentid,1);	//默认收货地址
	$freeDispatchPrice = isset($goods['freedispatchprice']) ? $goods['freedispatchprice'] : $settings['free_dispatch'];		//可免邮的最低总价
	$dispatchPrice = isset($goods['dispatchprice']) ? $goods['dispatchprice'] : $settings['free_dispatch_price'];		//不足免邮最低总价时需要补充的邮费

	$freeDispatchNum = isset($goods['freedispatchnum']) ? $goods['freedispatchnum'] : $maxbuy;	//免邮费的最低购买量（未设置时取单次最大购买量）
	//免邮费的起购判断条件优先顺序，产品价格限制》产品购买量限制 》系统全局价格限制 》全局购买量限制 ；当设置了包邮营销规则时，取消

	$ordertype = !empty($fromspecial) ? $fromspecial : (!empty($_GPC['ordertype']) ? $_GPC['ordertype'] : "biaozhun");
	$price = $goods['marketprice'];

	//准备购买,并进行各种逻辑判断
	fmMod_member_check($_W['openid']);//检测会员

	$optionid = ($hasoption) ? intval($_GPC['optionid']) : 0;
	// 商品规格
	if($hasoption && $optionid>0) {
		if(is_array($options)){
			foreach($options as $tmp_option){
				if($tmp_option['id']==$optionid) {
					$option = $tmp_option;
				}
			}
		}
	}
	if(empty($option)) {
		$option=$options[0];	//未传入有效规格ID时，默认选一个
	}

	$total = max(1,$_GPC['total']);	//购买总量
	$totalprice = $total*$goods['marketprice'];
	//获取用户已购买该产品的记录
	$areadybuy = 0;
	$result_orders_thisgoods = fmMod_order_get_bygoods($id);
	$orders_thisgoods = $result_orders_thisgoods['data'];
	$order_unabled_status = array(-1,1,2,3) ;//不能再次发起支付的订单状态
	if($orders_thisgoods) {
		$tmp_status = implode(',', $order_unabled_status);
		$sql = 'SELECT SUM(`og`.`total`) AS `orderTotal` FROM ' . tablename('fm453_shopping_order_goods') . ' AS `og` JOIN ' . tablename('fm453_shopping_order') .' AS `o` ON `og`.`orderid` = `o`.`id` WHERE `og`.`goodsid` = :goodsid AND `o`.`from_user` = :from_user AND `o`. `status` IN ('.$tmp_status.') ' ;
		$sqlparams = array(':goodsid' => $id, ':from_user' => $_W['openid']);
		$alreadybuy = pdo_fetchcolumn($sql, $sqlparams);	//已购买总量
	}

	//判断最大购买量(默认不进行判断)
	if($goods['usermaxbuy']>0) {
		if($goods['maxbuy']>0 && $total>$goods['maxbuy']) {
			//当启用了单次购买上限且用户此次购买超量时，自动修正为最大可购买上限
			$total=$goods['maxbuy'];
		}
		$maxbuynum = min($goods['usermaxbuy']-$alreadbuy,$maxbuy);
		if($total>=$maxbuynum) {
			$total =$maxbuynum;
			if($total<=0){
				fm_error('很抱歉，经过综合全网订单并整理后，现产品库存不足啦，请您看看其他的产品呢！您还可以反馈给商家补货哦！','库存不足');
			}
		}
	}

	//单产品重量
	$weight = $goods['weight'];		//产品规格对应重量
	// 重新核算订单商品价格及相关价格限制
	if($hasoption && $option) {
		$totalprice = $total*$option['marketprice'];
		$price = $option['marketprice'];
		$weight = $option['weight'];
	}

	//产品总重量
	$totalweight = $total*$weight;	//单位(g)

	//是否免邮(产品类型、营销专题、包邮设定、全局起邮金额设定)
	if($isNeedDispatch) {
		if($goods['marketing']['freedispatch']) {
			$isNeedDispatch=FALSE;
			$freeDispatchPrice=0;
			$dipatchFee = 0;
		}
	}
	$isNeedAddress=$isNeedDispatch;	//是否需要配送地址、需要配送

	//需要配送时，根据规则计算运费

	if($isNeedDispatch){
		if(is_array($dispatch)){
			foreach ($dispatch as &$d) {
				//首重价	$d['firstprice']		//首重	$d['firstweight']
				//续重价	$d['secondprice']		//续重	$d['secondweight']
				if($totalweight<=($d['firstweight']*1000)){
					//未超出首重
					$d['price'] = $d['firstprice'];
				}else{
					//计算超重部分，根据续重条件可共分多少段级
					$secondweight = $d['firstweight']*1000 - $totalweight;
					if($d['secondweight']==0) {
						//续重重量为0时，则直接引用续重价
						$d['price'] = $d['firstprice'] + $d['secondprice'];
					}else{
						$_temp_i =  MOD($secondweight,$d['secondweight']*1000);		//求余数
						$d['price'] = $d['firstprice'] + $d['secondprice']*$_temp_i;
					}
				}
			}
			unset($_temp_i);
			unset($d);
		}
	}

	//为了html文件可重复利用，格式化部分数据
	$allgoods=array($goods);
	$allgoods[0]['optionname']=$option['name'];
	$allgoods[0]['total']=$total;
	$allgoods[0]['marketprice']= $price;

	//表单验证
	//if(checksubmit()) {
	if($_GPC['submit']) {
		//联系方式
		$addressid = !empty($_GPC['address']) ? $_GPC['address'] : 0;	//address前台传来的部分是adressid而已
		if($addressid) {
			$address = pdo_fetch("SELECT * FROM " . tablename('mc_member_address') . " WHERE id = :id", array(':id' => intval($addressid)));
			if(!$address) {
				$address = $defaultaddress;
			}
		}
		if (empty($address)) {
				$tmp_msg = ($isNeedAddress) ? '抱歉，请您填写联系方式(姓名、电话及地址)(确定后点击“管理联系方式”链接可补充)！' :  '抱歉，请您填写联系姓名与电话！';
			exit(message($tmp_msg,referer(),'info'));
		}
		//将联系人姓名电话直接写进订单；
		$contactinfo=array(
			'username' =>$address['username'],
			'mobile' =>$address['mobile']
		);
		$contactinfo=serialize($contactinfo);//数组序列化

		//产品模型表单;
		include_once  FM_CORE.'goodstpl/appconfirm.php';   //require请求失败则程序不继续，用include
		//关于订单的更多信息(子公众号名称、用户终端\OS、产品模型关联的订单信息提前表单内容)
		$ucontainer = !empty($_GPC['container']) ? $_GPC['container'] : $_W['container'];
		$uos = !empty($_GPC['os']) ? $_GPC['os'] : $_W['os'];
		$aboutinfos=array(
			'mpaccountname' =>$_W['account']['name'],
			'ucontainer' =>$ucontainer,
			'uos' =>$uos,
			'goodstpl' =>$goodstpl,
			'infos' =>$goodstplinfos
		);

		$aboutinfos=serialize($aboutinfos);

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
		$orderpirce=$totalprice + $dispatchprice;
		if($isNeedDispatch) {
			if($orderpirce<$settings['free_dispatch']){
				$orderpirce +=$settings['free_dispatch_price'];
				$dispatchprice +=$settings['free_dispatch_price'];
			}
		}
		//生成订单数据
		$ordersn = date('ymdsih') . random(4, 1);
		$data = array(
			'uniacid' => $_W['uniacid'],
			'from_user' => $from_user,
			'fromuid' => $currentid,
			'ordersn' => $ordersn,
			'price' => $orderpirce,
			'dispatchprice' => $dispatchprice,
			'goodsprice' => $totalprice,
			'status' => 0,
			'sendtype' => intval($sendtype),
			'dispatch' => $dispatchid,
			'goodstype' => intval($goods['type']),
			'remark' => $_GPC['remark'],
			'addressid' => $address['id'],
			'contactinfo' => $contactinfo,
			'aboutinfos' => $aboutinfos,
			'sharedfrom' => $fromplatid,	//来源公众号
			'createtime' => TIMESTAMP,
			'shareid' => $shareid,
			'ordertype'=>$ordertype
		);

		pdo_insert('fm453_shopping_order', $data);
		$orderid = pdo_insertid();

		//记录订单商品
		$d = array(
			'uniacid' => $_W['uniacid'],
			'goodsid' => $id,
			'orderid' => $orderid,
			'ordersn' => $ordersn,
			'total' => $total,
			'price' => $price,
			'createtime' => TIMESTAMP,
			'optionid' => $optionid,
			'optionname' => $option['name']
		);

		//商品佣金处理(不需调用产品分类\全局佣金比的佣金设置，它只适用于后台快速录入使用)
		$d['commission'] = $totalprice * $goods['commissions']['1'] / 100;
		$d['commission2'] = $totalprice * $goods['commissions']['2'] / 100;
		$d['commission3'] = $totalprice * $goods['commissions']['3'] / 100;
		pdo_insert('fm453_shopping_order_goods', $d);

		// 变更商品库存
		if ($gitems['totalcnf']==0) {	//,0拍下减库存，1付款减库存，2永不减库存
			fmFunc_order_setStock($orderid,$minus=true,$gtype);
		}
		//写入日志
		$addons = array(
			'orderid'=>$orderid,
			'orderdata'=>$data
		);
		$dologs=array(
			'url'=>$_W['siteurl'],
			'description'=>'用户下单；',
			'addons'=>$addons,
			);
			fmMod_log_record($platid,$_W['uniacid'],$uid,$currentid,$_W['openid'],'fm453_shopping_order',$orderid,'create',$dologs);
			unset($dologs);
		//订单记录已完成

		//发送订单生成的相关通知
		$WeAccount = fmFunc_wechat_weAccount();
		require_once MODULE_ROOT.'/core/msgtpls/tpls/order.php';
		require_once MODULE_ROOT.'/core/msgtpls/msg/order.php';
		//发微信模板通知
		$postdata = $tplnotice_data['order']['new']['booker'];//下单人
		$result = fmMod_notice_tpl($postdata, $platid=NULL, $WeAccount);
		if(!$result) {
			$postdata = $notice_data['order']['new']['booker'];
			fmMod_notice($_W['openid'],$postdata, $platid=NULL, $WeAccount);
		}
		$postdata = $tplnotice_data['order']['new']['goodsadm'];//产品专员
		$result = fmMod_notice_tpl($postdata, $platid=NULL, $WeAccount);
		if(!$result) {
			$postdata = $notice_data['order']['new']['admin'];
			fmMod_notice($goods['goodsadm'],$postdata, $platid=NULL, $WeAccount);
		}
		$postdata = $tplnotice_data['order']['new']['admin'];//管理员
		$result = fmMod_notice_tpl($postdata, $platid=NULL, $WeAccount);
		if(!$result) {
			$postdata = $notice_data['order']['new']['admin'];
			fmMod_notice($settings['manageropenids'],$postdata, $platid=NULL, $WeAccount);
		}

		//跳转支付
		exit(message('提交订单成功,点击跳转到支付中心...', fm_murl('confirm', 'pay', 'index', array('id' => $orderid)), 'success'));
	}else{
		include $this->template($appstyle.$do.'/453');
	}
}
elseif($operation=="checkoption"){
	//利用传入的规格串oids进行规格类的反推；当对应规格项组合的库存为0时，将其进行标记
	$oids=$_GPC['oids'];
	$oids_selected=explode("_", $oids);
	$oids_selected=array_filter($oids_selected);
	//print_r($oids_selected);
	$len=intval($_GPC['len'])-1;
	$temp_len=count($oids_selected);
	$specid=intval($_GPC['specid']);
	$hasempty=0;
	if($len==$temp_len) {	// 对数组长度进行判断，仅当只剩下一组规格项未勾选时才进行下一下的判断
		$result=array(
			'hasempty'=>$hasempty
		);
		die(json_encode($result));
	}


	if(empty($options)) {//无0库存规格项时，直接结束并返回结果
		$result=array(
			'hasempty'=>$hasempty
		);
		die(json_encode($result));
	}

	//将全部0库存的规格id并入一个数组$e_oids，且以规格id为索引(方便后面的删除动作)
	$emptyoids=array();
	foreach($options as $key=>$option){
		$emptyoids[$key] = explode("_", $option['specs'] );
		//过滤掉与已勾选规格无关的组
		//print_r($emptyoids[$key]);
		$key_this_none=count($emptyoids[$key]);
		foreach($emptyoids[$key] as $ek=>$emptyoid){
			if(!in_array($emptyoid,$oids_selected)){
				$key_this_none +=-1;
			};
		}
		if($key_this_none==0) {
			unset($emptyoids[$key]);
		}else{
			$e_oids=$emptyoids[$key];
		}
		//print_r($emptyoids[$key]);
	}
	$emptyoids=array_filter($emptyoids);
	//print_r($emptyoids);
	//exit();
	if(empty($emptyoids)) {//无0库存规格项时，直接结束并返回结果
		$result=array(
			'hasempty'=>$hasempty
		);
		die(json_encode($result));
	}

	$e_oids=array_unique($e_oids);//去除数组中的重复值
	$temp_count=count($e_oids);
	//print_r($e_oids);
	//从0库存规格id数组中去掉已经勾选的规格id
	foreach($e_oids as $key=>$e_oid){
		if(in_array($e_oid,$oids_selected)) {
			unset($e_oids[$key]);
		}
	}
	//print_r($e_oids);
	$count=count($e_oids);
	if($temp_count==$count) {	//0库存规格项并没有减少时，说明当前勾选的规格项全部有效
		$result=array(
			'hasempty'=>$hasempty
		);
		die(json_encode($result));
	}
	//未勾选中的规格项
	//$unselected_oids = pdo_fetchall("select id from " . tablename('fm453_shopping_spec_item') . " where specid=:id order by id asc", array(':id' => $specid));
	//foreach($unselected_oids as $oid){
		//$oid_id=$oid['id'];
		//print_r($oid_id);
		//if(!empty($e_oids[$oid_id])) {
			//echo "";
		//}
	//}

	$e_oids=array_values($e_oids);//重设索引，便于下一步按个数递增分拆、引用
	//print_r($e_oids);
	//过滤后，将剩余的$e_oids返回前台（即 当前情况下无库存的规格）
	$result=array(
			'hasempty'=>1,
			'oidnum'=>$count,
			//'oids'=>($count>1) ? json_encode($e_oids) : $d
			'oids'=>$e_oids
		);
	//for($i=0; $i<$count; $i++) {
		//$result['oid'.$i]=$e_oids[$i];
	//}
	die(json_encode($result));
}
elseif($operation=='loadmore'){
	if($pindex=1) {
		include $this->template($appstyle.$do.'/'.$ac.'/'.$operation.'/page');
	}
}
elseif($operation=='load'){
	if($_GPC['refresh']==1) {
		include $this->template($appstyle.$do.'/'.$ac.'/'.$operation.'/page');
	}else{
		include $this->template($appstyle.$do.'/453');
	}
}
elseif($operation=='index'){
	//更新流量、链路统计
	fmFunc_view();
	if(!empty($shareid)){
		fmFunc_share_click($lastid,$shareid,$currentid,$share_user);
	}
	//更新浏览量
 	fmFunc_view('goods',$goodsid);
	fmMod_member_check($_W['openid']);//检测会员
//模板主框架（父页面）
	include $this->template($appstyle.$do.'/453');
}
