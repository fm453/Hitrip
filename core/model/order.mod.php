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
 * @remark	订单管理模型
 */
defined('IN_IA') or exit('Access Denied');
load()->model('mc');
fm_load()->fm_model('goods');
fm_load()->fm_model('goodstpl');

//定义订单类型
function fmMod_order_types(){
	return $types = array(
		'biaozhun'=>'标准订单',
		'quickpay'=>'快捷支付',	//无实际对应的产品时
		'forfriend'=>'代付',
	);
}

//将订单编号sn转为id
function fmMod_order_sn2id($ordersn){
	global $_GPC;
	global $_W;
	global $_FM;
	$return=array();
	$id=pdo_fetchcolumn("SELECT id FROM " . tablename('fm453_shopping_order') . " WHERE ordersn = :ordersn",array(':ordersn'=>$ordersn));
	return $id;
}

//根据订单SN获取订单基础信息
function fmMod_order_basic($ordersn) {
	global $_GPC;
	global $_W;
	global $_FM;
	header("Content-type:text/html;charset=utf-8");//加上网页字符集强制设置，避免中文传输乱码
	$return=array();
	if(empty($ordersn)){
		$return['result']=FALSE;
		$return['msg']='未传入订单编号';
		return $return;
	}
	$fields=fmFunc_tables_fields('order');
	$fields=implode(',',$fields);
	$result = pdo_fetch("SELECT ".$fields." FROM " . tablename('fm453_shopping_order') . " WHERE ordersn = :ordersn",array('ordersn'=>$ordersn));

	if($result){
		$allorderstatus=fmFunc_status_get('order');
		$paytype=fmFunc_status_get('paytype');
		$value = $result;
		$s = intval($value['status']);
		$original_data=array();	//保留部分原始数据，以便输出前台使用
			$original_data['status']=intval($value['status']);
		//获取下单人信息
		$hasmember=fmMod_member_query(intval($value['fromuid']));
		if($hasmember['result']) {
			$member = $hasmember['data'];
			//print_r($member);
			$value['buyer']=$member;
			$value['mem_name'] =empty($member['realname']) ? $member['nickname'] : $member['realname'];
			$value['mem_mobile'] =$member['mobile'];
			$value['mem_address'] =$member['address'];
		}
		//获取关联联系信息
		$contactinfo=unserialize($value['contactinfo']);
			$value['username'] =$contactinfo['username'];
			$value['mobile'] =$contactinfo['mobile'];
			$value['province']=$contactinfo['province'];
			$value['city']=$contactinfo['city'];
			$value['district']=$contactinfo['district'];
			$value['address']=$contactinfo['address'];
				$original_data['adress']=$contactinfo['address'];
			$value['address'] = $value['province'] .'-'.$value['city'] .'-'.$value['district'] .'-'.$value['address'];

		//关联模型表单信息
		$aboutinfos=unserialize($value['aboutinfos']);
			$value['goodstpl'] =$aboutinfos['goodstpl'];
			$value['mpaccountname'] =$aboutinfos['mpaccountname'];
			$value['ucontainer'] =$aboutinfos['ucontainer'];
			$value['uos'] =$aboutinfos['uos'];
		$goodstpl = $aboutinfos['goodstpl'];
		$goodstplinfos =unserialize($aboutinfos['infos']);
		require  FM_CORE.'goodstpl/forweborder.php';   //不能用 _once，否则数据显示不全导致混乱；
		$value['tips']=$tips;

		//订单状态样式处理
		$value['statuscss'] = $allorderstatus['code'.$value['status']]['css'];
		$value['status'] = $allorderstatus['code'.$value['status']]['name'];
		$value['paytypecss'] = $paytype['code'.$value['paytype']]['css'];
			$original_data['paytype']=$value['paytype'];
			$original_data['transid']=$value['transid'];
		if (intval($value['paytype']) == 2) {
			//2，在线支付
			if (!empty($value['transid'])) {
				$value['paytype'] = '微信支付';
			} else {
				$value['paytype'] = '非微信支付';
			}
		} else {
			$value['paytype'] = $paytype['code'.$value['paytype']]['name'];
		}
		if ($s < 1) {
			//1,待支付，0已取消
			$value['paytypecss'] = $paytype['code']['css'];
			if($s==0) {
				$value['paytype'] = $paytype['code0']['name'];
			}elseif($s==-1) {
				$value['paytype'] = '订单已取消';
			}
		}

		//是否删除
		$original_data['deleted']=$value['deleted'];
		if ($value['deleted'] == 0){
			$value['deleted'] = '否';
		} else {
			$value['deleted'] = '是';
		}
		//微支付流水号
		if (!empty($value['transid'])){
			if ($value['transid'] == 0){
				$value['transid'] = '订单已经取消';
			} else {
				$value['transid'] = "支付记录号".$value['transid'];
			}
		} else {
			$value['transid'] = '非微信支付订单';
		}

		$value['originaldata']=$original_data;
		$return['result']=TRUE;
		$return['data']=$value;
		return $return;
	}else{
		$return['result']=FALSE;
		$return['msg']='获取订单数据失败';
		return $return;
	}
}

//订单物流信息
function fmMod_order_wuliu($dispatchid) {
	global $_GPC;
	global $_W;
	global $_FM;
	header("Content-type:text/html;charset=utf-8");//加上网页字符集强制设置，避免中文传输乱码
	$return=array();
	$dispatchid=intval($dispatchid);
	if(empty($dispatchid)){
		$return['result']=FALSE;
		$return['msg']='未传入物流方式记录dispatchid';
		return $return;
	}
	$dispatch = pdo_fetchcolumn("SELECT * FROM " . tablename('fm453_shopping_dispatch') . " WHERE id = :id", array(':id' => $dispatchid));
	if (!empty($dispatch) && !empty($dispatch['express'])) {
		$express = pdo_fetch("select * from " . tablename('fm453_shopping_express') . " WHERE id=:id limit 1", array(":id" => $dispatch['express']));
	}
	$wuliu=array();
	$wuliu['dispatch']=$dispatch;
	$wuliu['express']=$express;
	$return['result']=TRUE;
	$return['data']=$wuliu;
	return $return;
}

//处理订单分销数据
function fmMod_order_share($orderid) {
	global $_GPC;
	global $_W;
	global $_FM;
	header("Content-type:text/html;charset=utf-8");//加上网页字符集强制设置，避免中文传输乱码
	$return=array();
	if(empty($orderid)){
		$return['result']=FALSE;
		$return['msg']='未传入订单ID';
		return $return;
	}
	$orderid=intval($orderid);
	$value = array();

	$fields = array('shareid','fromuid','sharedfrom');
	$fields=implode(',',$fields);
	$value = pdo_fetch("SELECT ".$fields." FROM " . tablename('fm453_shopping_order') . " WHERE id = :orderid",array('orderid'=>$orderid));

	$commissions = pdo_fetchall("select total,commission, commission2, commission3 from".tablename('fm453_shopping_order_goods')." where orderid =:orderid ",array(':orderid'=>$orderid));
	$value['commission'] = 0;
	$value['commission2'] = 0;
	$value['commission3'] = 0;
	foreach ($commissions as $i=>$c){
		$value['commission'] += $commissions[$i]['commission']* $commissions[$i]['total'];
		$value['commission2'] += $commissions[$i]['commission2']* $commissions[$i]['total'];
		$value['commission3'] += $commissions[$i]['commission3']* $commissions[$i]['total'];
	}
	unset($i);
	unset($c);

	if(intval($value['shareid'])>0){
		$hasmember=fmMod_member_query(intval($value['shareid']));
		if($hasmember['result']) {
			$member = $hasmember['data'];
			$value['sharer'] =$member;
			$value['shareby'] =$member['realname'];
		}
	}elseif($value['shareid']==0) {
	    $value['sharer'] = array();
		$value['shareby'] ='总店';
	}
	$return['result']=TRUE;
	$return['data']=$value;
	return $return;
}

//订单支付记录
function fmMod_order_paylog($ordersn) {
	global $_GPC;
	global $_W;
	global $_FM;
	header("Content-type:text/html;charset=utf-8");//加上网页字符集强制设置，避免中文传输乱码
	$return=array();
	if(empty($ordersn)){
		$return['result']=FALSE;
		$return['msg']='未传入订单编号';
		return $return;
	}
	$paylog = pdo_fetchall("SELECT * FROM " . tablename('core_paylog') . " WHERE ( tid LIKE :tid OR tid = :tidDel ) ORDER BY plid DESC", array(':tid' => $ordersn,'tidDel'=>'DEL'.$ordersn));
	$return['result']=TRUE;
	$return['data']=$paylog;
	return $return;
}

//订单中的产品信息
function fmMod_order_goods($orderid) {
	global $_GPC;
	global $_W;
	global $_FM;
	header("Content-type:text/html;charset=utf-8");//加上网页字符集强制设置，避免中文传输乱码
	$return=array();
	if(empty($orderid)){
		$return['result']=FALSE;
		$return['msg']='未传入订单ID';
		return $return;
	}
	$orderid=intval($orderid);
	$ordergoods_s = pdo_fetchall("SELECT * FROM " . tablename('fm453_shopping_order_goods'). " WHERE orderid = :orderid",array(":orderid"=>$orderid));
	$goodsids=array();
	foreach($ordergoods_s as $ordergoods){
		$goodsids[$ordergoods['goodsid']]=$ordergoods['goodsid'];
	}
	$goodsdetail=array();
	$goodslabels=array();
	foreach($goodsids as $goodsid){
		//产品基础信息
		$result_basic = fmMod_goods_detail_basic($goodsid);
		$goodsdetail[$goodsid]=$result_basic['data'];
		//产品标签
		$result_labels=fmMod_goods_detail_labels($goodsid);
		$goodslabels[$goodsid]=$result_labels['data'];
	}
	$allgoods=array();
	$i=0;
	foreach($ordergoods_s as $key =>$ordergoods){
		$tmp = $goodsdetail[$ordergoods['goodsid']];//预取入详情信息
		//记录一些原始数据
		$tmp['basic']=$ordergoods;
		$tmp['basic']['totalprice']=$ordergoods['price']*$ordergoods['total'];
		$tmp['detail']=$goodsdetail[$ordergoods['goodsid']];
		$tmp['labels']=$goodslabels[$ordergoods['goodsid']];
		$labels=$goodslabels[$ordergoods['goodsid']];
		//格式化处理一些数据，方便调用
		$tmp['buytotal']=$ordergoods['total'];//预订数量
		$tmp['totalprice']=$ordergoods['price']*$ordergoods['total'];//单品总价
		$tmp['optionname']=$ordergoods['optionname'];//产品规格名
		$tmp['price']=$ordergoods['price'];//产品购买价格
		$tmp['label_pic_alt']=$labels[1]['title'];
		$tmp['label_pic_src']=$labels[1]['value'];
		$tmp['label_span_title']=$labels[0]['title'];
		$tmp['label_span_value']=$labels[0]['value'];
		$hasspecs=fmMod_goods_specs($ordergoods['optionid']);
		$tmp['specs']=$hasspecs['data'];
		$allgoods[$i]=$tmp;
		$i += 1;
	}

	$return['result']=TRUE;
	$return['data']=$allgoods;
	return $return;
}

//订单信息(摘要，用于列表显示、导出等)
function fmMod_order_info($ordersn,$orderid=NULL) {
	global $_GPC;
	global $_W;
	global $_FM;
	header("Content-type:text/html;charset=utf-8");//加上网页字符集强制设置，避免中文传输乱码
	$return=array();
	$orderid=intval($orderid);
	if($orderid>0 && !empty($ordersn)){
		$checkid=fmMod_order_sn2id($ordersn);
		if($checkid !=$orderid) {
			$return['result']=FALSE;
			$return['msg']='所查数据有误，产品ID与SN不符';
			return $return;
		}
	}elseif($orderid>0 && empty($ordersn)){
		$ordersn = pdo_fetchcolumn("SELECT ordersn FROM " . tablename('fm453_shopping_order') . " WHERE id = :orderid",array('orderid'=>$orderid));
	}elseif($orderid<=0 && empty($ordersn)){
		$return['result']=FALSE;
			$return['msg']='所查数据有误，没有传入有效的ID或SN';
			return $return;
	}
	$orderinfo=array();
	$result=fmMod_order_basic($ordersn);
	if($result['result']) {
		foreach($result['data'] as $bk=>$b){
			$orderinfo[$bk]=$b;
		}
	}
	$dispatchid=$orderinfo['dispatchid'];
	$result=fmMod_order_wuliu($dispatchid);
	if($result['result']) {
		$orderinfo['wuliu']=$result['data'];
	}
	$result=fmMod_order_share($orderid);
	if($result['result']) {
		foreach($result['data'] as $sk=>$s){
			$orderinfo[$sk]=$s;
		}
	}
	$return['result']=TRUE;
	$return['data']=$orderinfo;
	return $return;
}

//订单详情信息(全部)
function fmMod_order_detail($ordersn,$orderid=NULL) {
	global $_GPC;
	global $_W;
	global $_FM;
	header("Content-type:text/html;charset=utf-8");//加上网页字符集强制设置，避免中文传输乱码
	$return=array();
	$orderid=intval($orderid);
	if($orderid>0 && !empty($ordersn))
	{
		$checkid=fmMod_order_sn2id($ordersn);
		if($checkid !=$orderid)
		{
			$return['result']=FALSE;
			$return['msg']='所查数据有误，产品ID与SN不符';
			return $return;
		}
	}elseif($orderid>0 && empty($ordersn))
	{
		$ordersn = pdo_fetchcolumn("SELECT ordersn FROM " . tablename('fm453_shopping_order') . " WHERE id = :orderid",array('orderid'=>$orderid));
	}elseif($orderid<=0 && empty($ordersn))
	{
		$return['result']=FALSE;
			$return['msg']='所查数据有误，没有传入有效的ID或SN';
			return $return;
	}
	$orderinfo=array();
	$result=fmMod_order_basic($ordersn);
	if($result['result'])
	{
		foreach($result['data'] as $bk=>$b)
		{
			$orderinfo[$bk]=$b;
		}
	}
	$orderid=$orderinfo['id'];
	$dispatchid=$orderinfo['dispatchid'];
	$result=fmMod_order_wuliu($dispatchid);
	if($result['result']) {
		$orderinfo['wuliu']=$result['data'];
	}
	$result=fmMod_order_share($orderid);
	if($result['result']) {
		foreach($result['data'] as $sk=>$s){
			$orderinfo[$sk]=$s;
		}
	}
	$result=fmMod_order_paylog($ordersn);
	if($result['result']) {
		$orderinfo['paylog']=$result['data'];
	}
	$result=fmMod_order_goods($orderid);
	if($result['result']) {
		$orderinfo['allgoods']=$result['data'];
	}
	$return['result']=TRUE;
	$return['data']=$orderinfo;
	return $return;
}

//根据产品获取订单id列表
function fmMod_order_get_bygoods($goodsid){
	global $_GPC;
	global $_W;
	global $_FM;
	header("Content-type:text/html;charset=utf-8");//加上网页字符集强制设置，避免中文传输乱码
	$return=array();
	$id=intval($goodsid);
	if(empty($id)){
		$return['result']=FALSE;
		$return['msg']='未传入产品ID';
		return $return;
	}
	$orders = pdo_fetchall("SELECT `orderid` FROM " . tablename('fm453_shopping_order_goods')." WHERE `goodsid`= :goodsid ",array(':goodsid'=>$id));
	$orderids=array();
	foreach($orders as $key => $order){
		$orderids[] =$order['orderid'];
	}
	$orderids=array_unique($orderids);
	if(count($orderids)>0) {
		$return['result']=TRUE;
		$return['data']=$orderids;
		return $return;
	}else{
		$return['result']=FALSE;
		$return['msg']='该产品无订单记录';
		return $return;
	}
}

//根据粉丝openid获取订单id列表
function fmMod_order_get_bymember($fromuser,$status=NULL){
	global $_GPC;
	global $_W;
	global $_FM;
	header("Content-type:text/html;charset=utf-8");//加上网页字符集强制设置，避免中文传输乱码
	$return=array();
	if(empty($fromuser)){
		$return['result']=FALSE;
		$return['msg']='未传入会员fromuser';
		return $return;
	}
	$params = array();
	$condition = " WHERE ";
	$condition .="`from_user`= :from_user";
	$params[':from_user'] = $fromuser;
	if($status) {
		//指定订单状态
		$allstatus = fmFunc_status_get('order');
		$status = $allstatus[$status]['value'];
		$condition = " AND ";
		$condition .=" status = :status";
		$params[':status'] = $status;
	}
	$orders = pdo_fetchall("SELECT `id`,`ordersn` FROM " . tablename('fm453_shopping_order'). $condition,$params);
	$orderids=array();
	foreach($orders as $key => $order){
		$orderids[] =$order['id'];
	}
	$orderids=array_unique($orderids);
	if(count($orderids)>0) {
		$return['result']=TRUE;
		$return['data']=$orderids;
		return $return;
	}else{
		$return['result']=FALSE;
		$return['msg']='该会员无订单记录';
		return $return;
	}
}

//根据产品模型获取订单id列表
function fmMod_order_get_bygoodtpl($goodtpl){
	global $_GPC;
	global $_W;
	global $_FM;
	header("Content-type:text/html;charset=utf-8");//加上网页字符集强制设置，避免中文传输乱码
	$return=array();
	$gtpltype=fmMod_goodstpl_type_get();
	$goodtpls=array();
	foreach($gtpltype as $key => $gtpl){
		$goodtpls[]=$key;
	}
	if(!in_array($goodtpl,$goodtpls)){
		message('请先选择产品模型',fm_wurl('goods','tpl','',array()),error);
	}
	$allgoods = pdo_fetchall("SELECT `id`,`sn` FROM " . tablename('fm453_shopping_goods')." WHERE `goodtpl`= :goodtpl ",array(':goodtpl'=>$goodtpl));
	$goodsids=array();
	foreach($allgoods as $key => $goods){
		$goodsids[] =$goods['id'];
	}
	$goodsids=array_unique($goodsids);
	if(count($goodsids)<=0){
		message('该产品模型还没有相关的产品',fm_wurl('goods','tpl','',array()),error);
	}
	$goodsids="(". implode(',',$goodsids). ")";
	$orders = pdo_fetchall("SELECT `orderid` FROM " . tablename('fm453_shopping_order_goods')." WHERE `goodsid` in ".$goodsids);
	$orderids=array();
	foreach($orders as $key => $order){
		$orderids[] =$order['orderid'];
	}
	$orderids=array_unique($orderids);
	if(count($orderids)>0) {
		$return['result']=TRUE;
		$return['data']=$orderids;
		return $return;
	}else{
		$return['result']=FALSE;
		$return['msg']='该类产品无订单记录';
		return $return;
	}
}

//修复粉丝openid、uid与订单列表uid的对应
function fmMod_order_repair(){
	global $_GPC;
	global $_W;
	global $_FM;
	header("Content-type:text/html;charset=utf-8");//加上网页字符集强制设置，避免中文传输乱码
	$orders = pdo_fetchall("SELECT id,from_user,fromuid FROM " . tablename('fm453_shopping_order'));
	foreach($orders as $order){
		$uid=mc_openid2uid($order['from_user']);
		if($order['fromuid']<=0) {
			$result=pdo_update('fm453_shopping_order',array('fromuid'=>$uid),array('id'=>$order['id']));
		}
	}
	return;
}
