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
 * @remark	产品管理模型
 */
defined('IN_IA') or exit('Access Denied');
//定义产品类型
function fmMod_goods_types(){
	return $types=array(
		'goods'=>'商城产品',	//使用shopping_goods表
		'vshop'=>'积分产品',	//使用积分商城管理
		'article'=>'文章',	//使用site_articel表
		'notype'=>'无类型'		//未指定具体类型时，如自由支付、体验支付
	);
}

//新建商品-基础数据
function fmMod_goods_new_basic($data,$platid) {
	global $_GPC;
	global $_W;
	global $_FM;
	header("Content-type:text/html;charset=utf-8");//加上网页字符集强制设置，避免中文传输乱码
	$return=array();
	if(!is_array($data)){
		$return['result']=FALSE;
		$return['msg']='传入数据非法，需是数组';
		return $return;
	}
	//前置判断
	if (empty($data['title'])) {
		message('请输入商品名称！','referer','fail');
	}
	unset($data['id']);//从数组中踢除id项
	//数据拆解
	if(empty($data['category'])){
		$data['category'] = array();
	}
	$data['pcate']=intval($data['category']['parentid']);//父分类
	$data['ccate']=intval($data['category']['childid']);//子分类
	if(empty($data['thumbs'])){
		$data['thumbs'] = array();
	}
	if(is_array($data['thumbs'])){
		$data['thumb_url'] = serialize($data['thumbs']);
	}
	//清理非库内字段
	unset($data['category']);//不是数据库字段，不需入库
	unset($data['thumbs']);//不是数据库字段，不需入库
	$fields=fmFunc_tables_fields('goods');
	foreach($data as $field=>$d){
		if(!in_array($field,$fields)){
			unset($data[$field]);
		}
	}
	if(count($data)==0){
		$return['result']=FALSE;
		$return['msg']='传入数据无效';
		return $return;
	}
	//数据格式处理
	$data['uniacid']=intval($platid);//产品所属公众平台
	$data['displayorder']=intval($data['displayorder']);//排序，DESC
	$data['type']=intval($data['type']);//商品类型 1实物 2虚拟
	$data['isrecommand']=intval($data['isrecommand']);//是否推荐
	$data['ishot']=intval($data['ishot']);//是否热销
	$data['isnew']=intval($data['isnew']);//是否新品
	$data['isdiscount']=intval($data['isdiscount']);//是否折扣
	$data['istime']=intval($data['istime']);//是否限时
	$data['timestart']=intval($data['timestart']);//开始时间
	$data['timeend']=intval($data['timeend']);//结束时间
	$data['weight']=intval($data['weight']);//重量
	$data['total']=intval($data['total']);//虚拟库存
	$data['stock']=intval($data['stock']);//真实库存
	$data['totalcnf']=intval($data['totalcnf']);//减库存的方式，0拍下减 1付款减 2永不减
	$data['status']=intval($data['status']);//在售状态 1上架0下架
	$data['maxbuy']=intval($data['maxbuy']);//单次购买的最大数量
	$data['usermaxbuy']=intval($data['usermaxbuy']);//单个用户的最大购买数量
	$data['hasoption']=intval($data['hasoption']);//开启多规格
	$data['sales']=intval($data['sales']);//虚拟销量
	$data['realsales']=intval($data['realsales']);//真实销量
	$data['commission']=intval($data['commission']);//1级佣金比例
	$data['commission2']=intval($data['commission2']);//2级佣金比例
	$data['commission3']=intval($data['commission3']);//3级佣金比例
	$data['isagent']=intval($data['isagent']);//是否允许代理

	$data['content']=htmlspecialchars_decode($data['content']);//商品详情
	$data['agentcontent']=htmlspecialchars_decode($data['agentcontent']);//代理商用的商品详情
	$data['agenttip']=htmlspecialchars_decode($data['agenttip']);//给代理商的通知

	$data['marketprice']=sprintf('%.2f', $data['marketprice']);//销售价
	$data['cankaoprice']=sprintf('%.2f', $data['cankaoprice']);//市场参考价
	$data['costprice']=sprintf('%.2f', $data['costprice']);//成本价
	$data['originalprice']=sprintf('%.2f', $data['originalprice']);//原价
	$data['agentsaleprice']=sprintf('%.2f', $data['agentsaleprice']);//代理销售价
	$data['agentprice']=sprintf('%.2f', $data['agentprice']);//代理结算价
	//数据有效性处理
	$data['lastsales']=0;//上次填写的虚拟销量
	if($data['realsales']<0){
		$data['realsales']= 0;//真实销量
	}
	$data['sn']=TIMESTAMP;//商品SN序号（全库惟一）
	if ($data['total'] == -1) {
		$data['totalcnf'] = 2;//对外库存无限时，减库存方式改为永不减
	}
	if($data['marketprice']<=0){
		$data['marketprice']=0;
		$data['status'] = 0;//销售价为0时，产品状态改为下架
	}
	if($data['istime']==1){
		if($data['timestart']=0 && $data['timeend']=0){
			$data['istime']=0;//未设置开始及结束时间时，取消限时，下架产品
			$data['status'] = 0;
		}elseif($data['timestart']>0 && $data['timeend']>0){
			if($data['timestart']>=$data['timeend']){
				$data['istime']=0;//开始时间晚于结束时间，取消限时，下架产品
				$data['status'] = 0;
			}
		}
	}
	if(empty($data['unit'])) {
		$data['unit']='份';//默认单位为“份”
	}
	//特别字段处理
	$data['statuscode']=intval($data['statuscode']);//状态码 （用于后期权限流程判断）
	if($platid !=$_W['uniacid']){
		$data['status'] = 0;//当前公众号非平台主体时，产品不直接上架； 需经平台进行审核
		$data['statuscode'] = 0;//产品权限状态调为创建待审
	}
	$data['createtime']=TIMESTAMP;//创建时间不允许更改
	$data['updatetime']=$data['createtime'];//记录更新时间

	$result=pdo_insert('fm453_shopping_goods', $data);
	if($result){
		$return['result']=TRUE;
		$return['data']=pdo_insertid();
		return $return;
	}else{
		$return['result']=FALSE;
		$return['msg']='插入数据失败';
		return $return;
	}
}

//管理员编辑更新商品基础数据
function fmMod_goods_modify_basic($id,$data) {
	global $_GPC;
	global $_W;
	global $_FM;
	header("Content-type:text/html;charset=utf-8");//加上网页字符集强制设置，避免中文传输乱码
	$return=array();
	$id=intval($id);
	if($id<=0){
		$return['result']=FALSE;
		$return['msg']='未传入商品ID';
		return $return;
	}
	$fields=fmFunc_tables_fields('goods');
	$fields = implode(',',$fields);
	$goods=pdo_fetch('SELECT '.$fields.' FROM '.tablename('fm453_shopping_goods').' WHERE id = :id',array(':id'=>$id));
	if(empty($goods)){
		$return['result']=FALSE;
		$return['msg']='未获得商品数据';
		return $return;
	}
	$fields=explode(',',$fields);
	if(!is_array($data)){
		$return['result']=FALSE;
		$return['msg']='传入数据非法，需是数组';
		return $return;
	}
	//数据拆解
	if(empty($data['category'])){
		$data['category'] = array();
	}
	$data['pcate']=intval($data['category']['parentid']);//父分类
	$data['ccate']=intval($data['category']['childid']);//子分类

	if(empty($data['thumbs'])){
		$data['thumbs'] = array();
	}
	if(is_array($data['thumbs'])){
		$data['thumb_url'] = serialize($data['thumbs']);
	}
	//清理非库内字段
	unset($data['category']);//不是数据库字段，不需入库
	unset($data['thumbs']);//不是数据库字段，不需入库
	foreach($data as $field=>$d){
		if(!in_array($field,$fields)){
			unset($data[$field]);
		}
	}
	if(count($data)==0){
		$return['result']=FALSE;
		$return['msg']='传入数据无效';
		return $return;
	}

	//数据格式处理
	$data['displayorder']=intval($data['displayorder']);//排序，DESC
	$data['type']=intval($data['type']);//商品类型 1实物 2虚拟
	$data['isrecommand']=intval($data['isrecommand']);//是否推荐
	$data['ishot']=intval($data['ishot']);//是否热销
	$data['isnew']=intval($data['isnew']);//是否新品
	$data['isdiscount']=intval($data['isdiscount']);//是否折扣
	$data['istime']=intval($data['istime']);//是否限时
	$data['timestart']=intval($data['timestart']);//开始时间
	$data['timeend']=intval($data['timeend']);//结束时间
	$data['weight']=intval($data['weight']);//重量
	$data['total']=intval($data['total']);//虚拟库存
	$data['stock']=intval($data['stock']);//真实库存
	$data['totalcnf']=intval($data['totalcnf']);//减库存的方式，0拍下减 1付款减 2永不减
	$data['status']=intval($data['status']);//在售状态 1上架0下架
	$data['maxbuy']=intval($data['maxbuy']);//单次购买的最大数量
	$data['usermaxbuy']=intval($data['usermaxbuy']);//单个用户的最大购买数量
	$data['hasoption']=intval($data['hasoption']);//开启多规格
	$data['sales']=intval($data['sales']);//虚拟销量
	$data['realsales']=intval($data['realsales']);//真实销量
	$data['commission']=intval($data['commission']);//1级佣金比例
	$data['commission2']=intval($data['commission2']);//2级佣金比例
	$data['commission3']=intval($data['commission3']);//3级佣金比例
	$data['isagent']=intval($data['isagent']);//是否允许代理

	$data['content']=htmlspecialchars_decode($data['content']);//商品详情
	$data['agentcontent']=htmlspecialchars_decode($data['agentcontent']);//代理商用的商品详情
	$data['agenttip']=htmlspecialchars_decode($data['agenttip']);//给代理商的通知

	$data['marketprice']=sprintf('%.2f', $data['marketprice']);//销售价
	$data['cankaoprice']=sprintf('%.2f', $data['cankaoprice']);//市场参考价
	$data['costprice']=sprintf('%.2f', $data['costprice']);//成本价
	$data['originalprice']=sprintf('%.2f', $data['originalprice']);//原价
	$data['agentsaleprice']=sprintf('%.2f', $data['agentsaleprice']);//代理销售价
	$data['agentprice']=sprintf('%.2f', $data['agentprice']);//代理结算价
	//数据有效性处理
	$data['lastsales']=$goods['sales'];//上次填写的虚拟销量
	if($data['realsales']<0){
		$data['realsales']= intval($goods['realsales']);//真实销量，如输入为负，则继承修改前的真实销量
	}
	unset($data['id']);//从数组中踢除id项
	unset($data['sn']);//序号不可被直接修改
	unset($data['uniacid']);//不允许直接修改产品所属公众平台
	if ($data['total'] == -1) {
		$data['totalcnf'] = 2;//对外库存无限时，减库存方式改为永不减
	}
	if($data['marketprice']<=0){
		$data['marketprice']=0;
		$data['status'] = 0;//销售价为0时，产品状态改为下架
	}
	if($data['istime']==1){
		if($data['timestart']==0 && $data['timeend']==0){
			$data['istime'] = 0;//未设置开始及结束时间时，取消限时，下架产品
			$data['status'] = 0;
		}elseif($data['timestart']>0 && $data['timeend']>0){
			if($data['timestart']>$data['timeend']){
				$data['istime']=0;//开始时间晚于结束时间，取消限时，下架产品
				$data['status'] = 0;
			}
		}
	}
	if(empty($data['unit'])) {
		$data['unit']='份';//默认单位为“份”
	}
	if(empty($data['title'])){
		$data['title']=$goods['title'];
	}
	//特别字段处理
	$data['statuscode']=intval($data['statuscode']);//状态码 （用于后期权限流程判断）
	$data['createtime']=$goods['createtime'];//创建时间不允许更改
	$data['updatetime']=TIMESTAMP;//记录更新时间
	$result=pdo_update('fm453_shopping_goods', $data,array('id'=>$id));
	if($result){
		$return['result']=TRUE;
		return $return;
	}else{
		$return['result']=FALSE;
		$return['msg']='插入数据失败';
		return $return;
	}
}

//更新商品的自定义参数
function fmMod_goods_modify_params($goodsid,$data) {
	global $_GPC;
	global $_W;
	global $_FM;
	header("Content-type:text/html;charset=utf-8");//加上网页字符集强制设置，避免中文传输乱码
	$return=array();
	$id=intval($goodsid);
	if($id<=0){
		$return['result']=FALSE;
		$return['msg']='未传入商品ID';
		return $return;
	}
	if(!is_array($data)){
		$return['result']=FALSE;
		$return['msg']='传入数据非法，需是数组';
		return $return;
	}
	$fields='`sn`';
	$gsn=pdo_fetch('SELECT '.$fields.' FROM '.tablename('fm453_shopping_goods').' WHERE id = :id',array(':id'=>$id));
	if(empty($gsn)){
		$return['result']=FALSE;
		$return['msg']='未获得商品数据';
		return $return;
	}
	//数据拆解
	$ids=$data['ids'];
	$titles=$data['titles'];
	$values=$data['values'];
	$displayorders=$data['displayorders'];
	if(!is_array($ids)){
		$return['result']=FALSE;
		$return['msg']='传入的自定义参数不合要求，需为数组结构';
		return $return;
	}
	$len = count($ids);
	$done_ids = array();
	$temp_done=array();
	for ($k = 0; $k < $len; $k++) {
		$done_id = "";
		$get_exist_id = $ids[$k];//可能是空值（新添加的），也可能是数字（原有的）
		$a = array(
			"title" => $titles[$k],
			"value" => $values[$k],
			"displayorder" => intval($k),
			"goodsid" => $id,
		);
		if (!is_numeric($get_exist_id)) {//检查是否为数字或数字字符串
			pdo_insert("fm453_shopping_goods_param", $a);
			$done_id = pdo_insertid();
		} else {
			pdo_update("fm453_shopping_goods_param", $a, array('id' => $get_exist_id));
			$done_id = $get_exist_id;
		}
		$done_ids[] = $done_id;
		$a['id']=$done_id;
		$temp_done[]=$a;
	}
	$result['data']['done']=$temp_done;
	unset($a);
	$sql = "DELETE FROM " . tablename('fm453_shopping_goods_param');
	$params =array();
	$condition =' WHERE ';
	$condition .='goodsid = :gid';
	$params[':gid'] =$id;
	if (count($done_ids) > 0) {
		$condition .=' AND ';
		$condition .='id NOT IN ( ' . implode(',', $done_ids) . ')';
	}
	$result['data']['deleted']=pdo_query($sql.$condition,$params);
	$return['result']=TRUE;//为了不影响商品其他数据的保存，此处标记操作成功并返回
	return $return;
}
//更新商品的自定义标签
function fmMod_goods_modify_labels($goodsid,$data){
	global $_GPC;
	global $_W;
	global $_FM;
	header("Content-type:text/html;charset=utf-8");//加上网页字符集强制设置，避免中文传输乱码
	$return=array();
	$id=intval($goodsid);
	if($id<=0){
		$return['result']=FALSE;
		$return['msg']='未传入商品ID';
		return $return;
	}
	if(!is_array($data)){
		$return['result']=FALSE;
		$return['msg']='传入数据非法，需是数组';
		return $return;
	}
	$fields='`sn`';
	$gsn=pdo_fetch('SELECT '.$fields.' FROM '.tablename('fm453_shopping_goods').' WHERE id = :id',array(':id'=>$id));
	if(empty($gsn)){
		$return['result']=FALSE;
		$return['msg']='未获得商品数据';
		return $return;
	}
	//数据拆解
	$ids=$data['ids'];
	$titles=$data['titles'];
	$values=$data['values'];
	$displayorders=$data['displayorders'];
	if(!is_array($ids)){
		$return['result']=FALSE;
		$return['msg']='传入的自定义参数不合要求，需为数组结构';
		return $return;
	}
	$len = count($ids);
	$done_ids = array();
	$temp_done=array();
	for ($k = 0; $k < $len; $k++) {
		$done_id = "";
		$get_exist_id = $ids[$k];//可能是空值（新添加的），也可能是数字（原有的）
		$a = array(
			"title" => $titles[$k],
			"value" => $values[$k],
			"displayorder" => intval($k),
			"goodsid" => $id,
		);
		if (!is_numeric($get_exist_id)) {//检查是否为数字或数字字符串
			pdo_insert("fm453_shopping_goods_label", $a);
			$done_id = pdo_insertid();
		} else {
			pdo_update("fm453_shopping_goods_label", $a, array('id' => $get_exist_id));
			$done_id = $get_exist_id;
		}
		$done_ids[] = $done_id;
		$a['id']=$done_id;
		$temp_done[]=$a;
	}
	$result['data']['done']=$temp_done;
	unset($a);
	$sql = "DELETE FROM " . tablename('fm453_shopping_goods_label');
	$params =array();
	$condition =' WHERE ';
	$condition .='goodsid = :gid';
	$params[':gid'] =$id;
	if (count($done_ids) > 0) {
		$condition .=' AND ';
		$condition .='id NOT IN ( ' . implode(',', $done_ids) . ')';
	}
	$result['data']['deleted']=pdo_query($sql.$condition,$params);
	$return['result']=TRUE;//为了不影响商品其他数据的保存，此处标记操作成功并返回
	return $return;
}
//处理商品规格子项
function fmMod_goods_modify_specitems($specid,$goodsid,$data){
	global $_GPC;
	global $_W;
	global $_FM;
	header("Content-type:text/html;charset=utf-8");//加上网页字符集强制设置，避免中文传输乱码
	$return=array();
	$id=intval($goodsid);
	$platid=$_W['uniacid'];
	$specid=intval($specid);
	if($id<=0){
		$return['result']=FALSE;
		$return['msg']='未传入商品ID';
		return $return;
	}
	if(!is_array($data)){
		$return['result']=FALSE;
		$return['msg']='传入商品规格子项数据非法，需是数组';
		return $return;
	}
	$fields='`sn`,`uniacid`';
	$goods=pdo_fetch('SELECT '.$fields.' FROM '.tablename('fm453_shopping_goods').' WHERE id = :id',array(':id'=>$id));
	if(empty($goods)){
		$return['result']=FALSE;
		$return['msg']='未获得商品数据';
		return $return;
	}
	if($goods['uniacid']!=$platid){
		$return['result']=FALSE;
		$return['msg']='该产品非当前平台所有，不允许修改规格项';
		return $return;
	}
	//数据拆解
	$ids=$data['ids'];
	$titles=$data['titles'];
	$shows=$data['shows'];
	$thumbs=$data['thumbs'];
	$startdates=$data['startdates'];
	$enddates=$data['enddates'];
	$istimes=$data['istimes'];
	if(!is_array($ids)){
		$return['result']=FALSE;
		$return['msg']='传入的specitems ids参数不合要求，需为数组结构';
		return $return;
	}
	$len = count($ids);
	$done_ids = array();
	$temp_done=array();
	for ($k = 0; $k < $len; $k++) {
		$done_id = "";
		$get_exist_id = $ids[$k];//可能是空值（新添加的），也可能是数字（原有的）
		if ($istimes[$k] !=1){
			$istimes[$k] =0;//是否启用时效控制，1是0否
		}
		$a = array(
			"uniacid" => $platid,
			"specid" => $specid,
			"displayorder" => $k,
			"title" => $titles[$k],
			"show" => $shows[$k],
			"thumb"=>$thumbs[$k],
			"startdate"=>strtotime($startdates[$k]),
			"enddate"=>strtotime($enddates[$k]),
			"istime"=>intval($istimes[$k])
		);
		if (is_numeric($get_exist_id)) {
			$return['data']['update'][]=pdo_update("fm453_shopping_spec_item", $a, array("id" => $get_exist_id));
			$done_id = $get_exist_id;
		} else {
			$return['data']['insert'][]=pdo_insert("fm453_shopping_spec_item", $a);
			$done_id = pdo_insertid();
		}
		$done_ids[] = $done_id;
		//临时记录，用于保存子规格项;也作为日志记录调用的附加说明
		$a['get_id'] = $get_exist_id;
		$a['id']= $done_id;
		$temp_done[] = $a;
	}

	$return['data']['done_ids']=$done_ids;
	//回调子规格项的ID汇总
	$return['data']['done']=$temp_done;//回调更新入库的子规格项
	unset($a);
	//删除其他的
	$sql = "DELETE FROM " . tablename('fm453_shopping_spec_item');
	$params =array();
	$condition =' WHERE ';
	$condition .='uniacid = :uniacid';
	$params[':uniacid'] =$platid;
	$condition .=' AND ';
	$condition .='specid = :specid';
	$params[':specid'] =$specid;
	if(count($done_ids)>0){
		$condition .=' AND ';
		$condition .='id NOT IN ( ' . implode(',', $done_ids) . ')';
	}

	$return['data']['deleted']=pdo_query($sql.$condition,$params);
	$return['result']=TRUE;	//为了不影响商品其他数据的保存，此处标记操作成功并返回
	return $return;
}
//处理商品规格项
function fmMod_goods_modify_specs($goodsid,$data,$item_data){
	global $_GPC;
	global $_W;
	global $_FM;
	header("Content-type:text/html;charset=utf-8");//加上网页字符集强制设置，避免中文传输乱码
	$return=array();
	$id=intval($goodsid);
	$platid=$_W['uniacid'];
	$specid=intval($specid);
	if($id<=0){
		$return['result']=FALSE;
		$return['msg']='未传入商品ID';
		return $return;
	}
	if(!is_array($data)){
		$return['result']=FALSE;
		$return['msg']='传入商品规格项spec id 数据非法，需是数组';
		return $return;
	}
	$fields='`sn`,`uniacid`';
	$goods=pdo_fetch('SELECT '.$fields.' FROM '.tablename('fm453_shopping_goods').' WHERE id = :id',array(':id'=>$id));
	if(empty($goods)){
		$return['result']=FALSE;
		$return['msg']='未获得商品数据';
		return $return;
	}
	if($goods['uniacid']!=$platid){
		$return['result']=FALSE;
		$return['msg']='该产品非当前平台所有，不允许修改规格项';
		return $return;
	}
	//数据拆解
	$ids=$data['ids'];
	$titles=$data['titles'];
	if(!is_array($ids)){
		$return['result']=FALSE;
		$return['msg']='传入的ids参数不合要求，需为数组结构';
		return $return;
	}
	$len = count($ids);
	$done_ids = array();
	$temp_done=array();
	$spec_items = array();
	$get_specitems_ids = array();
	for ($k = 0; $k < $len; $k++) {
		$done_id = "";
		$get_exist_id = $ids[$k];//可能是随机字符串（random(32)新添加的），也可能是数字（原有的）
		$a = array(
			"uniacid" => $platid,
			"goodsid" => $id,
			"displayorder" => $k,
			"title" => $titles[$get_exist_id]
		);
		if (is_numeric($get_exist_id)) {
			$return['data']['update'][]=pdo_update("fm453_shopping_spec", $a, array("id" => $get_exist_id));
			$done_id = $get_exist_id;
		} else {
			$return['data']['insert'][]=pdo_insert("fm453_shopping_spec", $a);
			$done_id = pdo_insertid();
		}
		//处理子项
		$spec_item_data=array();
		$temp_keys=array('id','title','show','thumb','startdate','enddate','istime');
		foreach($temp_keys as $key){
			$spec_item_data[$key.'s']=$item_data[$key][$get_exist_id];
		}
		unset($post_key);
		unset($temp_keys);
		$spec_items_return=fmMod_goods_modify_specitems($done_id,$id,$spec_item_data);//传入$done_id作为目标specid
		//更新规格项ID
		$itemids=$spec_items_return['data']['done_ids'];
		pdo_update("fm453_shopping_spec", array("content" => serialize($itemids)), array("id" => $done_id));
		$done_ids[] = $done_id;
		$a['id']= $done_id;
		$temp_done[] = $a;
		$return['data']['spec_items'][$done_id]=$spec_items_return['data'];
	}
	$return['data']['done']=$temp_done;
	$return['data']['done_ids']=$done_ids;
	unset($a);
	//删除其他的
	$sql = "DELETE FROM " . tablename('fm453_shopping_spec');
	$params =array();
	$condition =' WHERE ';
	$condition .='uniacid = :uniacid';
	$params[':uniacid'] =$platid;
	$condition .=' AND ';
	$condition .='goodsid = :goodsid';
	$params[':goodsid'] =$id;
	if(count($done_ids)>0){
		$condition .=' AND ';
		$condition .='id NOT IN ( ' . implode(',', $done_ids) . ')';
	}
	$return['data']['deleted']=pdo_query($sql.$condition,$params);
	$return['result']=TRUE;	//为了不影响商品其他数据的保存，此处标记操作成功并返回
	return $return;
}
//获取指定某个商品的基本信息
function fmMod_goods_detail_basic($goodsid){
	global $_GPC;
	global $_W;
	global $_FM;
	header("Content-type:text/html;charset=utf-8");//加上网页字符集强制设置，避免中文传输乱码
	$return=array();
	$nowtime = TIMESTAMP;
	$id = intval($goodsid);
	$fields=fmFunc_tables_fields('goods');
	$fields = implode(',',$fields);
	if ($id>0) {
		$sql="SELECT ".$fields." FROM " . tablename('fm453_shopping_goods') . " WHERE id = :id";
		$params=array();
		$params[':id']=$id;
			$result = pdo_fetch($sql, $params);
			if ($result){
				$return['result']=TRUE;
				//格式化产品缩略图，注入$result['thumbs']返回
				$result['thumb'] = tomedia($result['thumb']);
				$thumb_urls=unserialize($result['thumb_url']);
				$thumbs = array();
				if(is_array($thumb_urls)){
					foreach($thumb_urls as $p){
						//$thumbs[] = is_array($p) ? $p['attachment'] : tomedia($p);
						if(is_array($p)) {
							$tmp_ps[] = array();
							foreach($p['attachment']  as $p_a){
								$tmp_ps[]=tomedia($p_a);
							}
							$thumbs[] = $tmp_ps;
						}else{
							$thumbs[] = tomedia($p);
						}
					}
				}else{
					$thumbs=array($result['thumb']);
				}
				$result['thumbs']=$thumbs;
				$result['sharethumb'] = !empty($result['sharethumb']) ? tomedia($result['sharethumb']) : $result['thumb'];
				$result['xsthumb'] = !empty($result['xsthumb']) ? tomedia($result['xsthumb']) : $result['thumb'];
				//处理商品SN序号（该值必须全库惟一）
				if(empty($result['sn'])){
					$result['sn']=$result['id'];
				}
				$result['unit'] = !empty($result['unit']) ? $result['unit'] : '份';//产品默认计量单位
				$result['maxbuy'] = min($result['usermaxbuy'] ,$result['maxbuy']);	//单次购买问题不能超过单人最大购买总量
				//检查产品状态
				if($result['istime']){
					$result['status'] = ($result['status']>0 && $result['timend']<$nowtime) ? $result['status'] = 0 : $result['status'];
				}
				//产品模型
				$result['goodstpl']=$result['goodtpl'];
				//产品管理员
				$result['goodsadm']=$result['goodadm'];
				//返回结果
				$return['data']=$result;
				return $return;
			}else{
				$return['result']=FALSE;
				$return['msg']='商品不存在或是已经删除!';
				return $return;
			}
	}else{
		$return['result']=FALSE;
		$return['msg']='查询的商品ID非法!';
		return $return;
	}
}

//获取指定某个商品的产品模型参数
function fmMod_goods_detail_tplparams($sn,$goodstpl){
	global $_GPC;
	global $_W;
	global $_FM;
	header("Content-type:text/html;charset=utf-8");//加上网页字符集强制设置，避免中文传输乱码
	$return=array();
	$params=array();
	$sn=intval($sn);
	if($goodssn<=0 || empty($goodstpl)) {
		$return['result']=FALSE;
		$return['msg']='查询的商品序号sn及模型goodstpl非法!';
		return $return;
	}
	$fields='*';
	$sql ="SELECT ".$fields." FROM " . tablename('fm453_shopping_goods_tplparam');
	$condition =" WHERE ";
	$condition .='gsn = :gsn';
	$condition .=" AND ";
	$condition .='goodstpl =:goodstpl';
	$showorder = ' ORDER BY displayorder  DESC';
	$params['gsn']=$sn;
	$params['goodstpl']=$goodstpl;
	$result=pdo_fetchall($sql.$condition.$showorder, $params,'tplparam');
	if ($result){
		$return['result']=TRUE;
		$return['data']=$result;
		return $return;
	}else{
		$return['result']=FALSE;
		$return['msg']='未查询到相关产品模型参数!';
		return $return;
	}
}

//获取指定某个商品的自定义参数
function fmMod_goods_detail_params($goodsid){
	global $_GPC;
	global $_W;
	global $_FM;
	header("Content-type:text/html;charset=utf-8");//加上网页字符集强制设置，避免中文传输乱码
	$return=array();
	$params=array();
	$goodsid=intval($goodsid);
	if($goodsid<=0) {
		$return['result']=FALSE;
		$return['msg']='查询的商品id非法!';
		return $return;
	}
	$fields='*';
	$sql ="SELECT ".$fields." FROM " . tablename('fm453_shopping_goods_param');
	$condition =" WHERE ";
	$condition .='goodsid = :goodsid';
	$params['goodsid']=$goodsid;
	$showorder = ' ORDER BY displayorder  ASC';
	$result=pdo_fetchall($sql.$condition.$showorder, $params);
	if ($result){
		$return['result']=TRUE;
		$return['data']=$result;
		return $return;
	}else{
		$return['result']=FALSE;
		$return['msg']='未查询到相关产品参数!';
		return $return;
	}
}

//获取指定某个商品的自定义标签
function fmMod_goods_detail_labels($goodsid){
	global $_GPC;
	global $_W;
	global $_FM;
	header("Content-type:text/html;charset=utf-8");//加上网页字符集强制设置，避免中文传输乱码
	$return=array();
	$params=array();
	$goodsid=intval($goodsid);
	if($goodsid<=0) {
		$return['result']=FALSE;
		$return['msg']='查询的商品id非法!';
		return $return;
	}
	$fields='*';
	$sql ="SELECT ".$fields." FROM " . tablename('fm453_shopping_goods_label');
	$condition =" WHERE ";
	$condition .='goodsid = :goodsid';
	$params['goodsid']=$goodsid;
	$showorder = ' ORDER BY displayorder  ASC';
	$result=pdo_fetchall($sql.$condition.$showorder, $params);
	if ($result){
		$return['result']=TRUE;
		$return['data']=$result;
		return $return;
	}else{
		$return['result']=FALSE;
		$return['msg']='未查询到相关产品参数!';
		return $return;
	}
}

//获取指定某个商品的营销模型应用清单及相关购买链接
function fmMod_goods_detail_marketing($goodsid){
	global $_GPC;
	global $_W;
	global $_FM;
	header("Content-type:text/html;charset=utf-8");//加上网页字符集强制设置，避免中文传输乱码
	$return=array();
	$params=array();
	$goodsid=intval($goodsid);
	if($goodsid<=0) {
		$return['result']=FALSE;
		$return['msg']='查询的商品id非法!';
		return $return;
	}
	$fields='*';
	$sql ="SELECT ".$fields." FROM " . tablename('fm453_shopping_goods_marketmodel');
	$condition =" WHERE ";
	$condition .='gid = :goodsid';
	$params['goodsid']=$goodsid;
	$result=pdo_fetchall($sql.$condition, $params);
	if ($result){
		$return['result']=TRUE;
		$return['data']['marketmodel']=$result;
		//调用产品营销模型关联购买链接
		$buylinks = pdo_fetchall("select * from " . tablename('fm453_shopping_goods_buylink') . " where gid=:id", array(':id' => $goodsid));
		$item['buylink']=array();
		if(is_array($buylinks)){
			foreach($buylinks as $buylink){
				$item['buylink'][$buylink['marketmodel']]=$buylink;
			}
		}
		$return['data']['buylink']=$item['buylink'];
		return $return;
	}else{
		$return['result']=FALSE;
		$return['msg']='未查询到相关产品营销规则!';
		return $return;
	}
}

//获取指定某个商品的关联的规格项与子项(后端)
function fmMod_goods_detail_specs_w($goodsid){
	global $_GPC;
	global $_W;
	global $_FM;
	header("Content-type:text/html;charset=utf-8");//加上网页字符集强制设置，避免中文传输乱码
	$return=array();
	$params=array();
	$goodsid=intval($goodsid);
	if($goodsid<=0) {
		$return['result']=FALSE;
		$return['msg']='查询的商品id非法!';
		return $return;
	}
	$fields='*';
	$sql ="SELECT ".$fields." FROM " . tablename('fm453_shopping_spec');
	$condition =" WHERE ";
	$condition .='goodsid = :goodsid';
	$params['goodsid']=$goodsid;
	$showorder = ' ORDER BY  displayorder  ASC';
	$result=pdo_fetchall($sql.$condition.$showorder, $params,'id');
	if ($result){
		$return['result']=TRUE;
		$allspecs=$result;
		//规格项
		foreach ($allspecs as &$s) {
			$s['items'] = pdo_fetchall("select * from " . tablename('fm453_shopping_spec_item') . " where specid=:specid order by displayorder asc", array(":specid" => $s['id']));
		}
		$return['data']=$allspecs;
		return $return;
	}else{
		$return['result']=FALSE;
		$return['msg']='未查询到相关产品规格项!';
		return $return;
	}
}

//获取指定某个商品的关联的规格项与子项(前端)
function fmMod_goods_detail_specs_m($goodsid){
	global $_GPC;
	global $_W;
	global $_FM;
	header("Content-type:text/html;charset=utf-8");//加上网页字符集强制设置，避免中文传输乱码
	$return=array();
	$params=array();
	$goodsid=intval($goodsid);
	if($goodsid<=0) {
		$return['result']=FALSE;
		$return['msg']='查询的商品id非法!';
		return $return;
	}
	$fields='*';
	$sql ="SELECT ".$fields." FROM " . tablename('fm453_shopping_spec');
	$condition =" WHERE ";
	$condition .='goodsid = :goodsid';
	$params['goodsid']=$goodsid;
	$showorder = ' ORDER BY  displayorder  ASC';
	$result=pdo_fetchall($sql.$condition.$showorder, $params,'id');
	if ($result){
		$allspecs=$result;
		//规格项
		foreach ($allspecs as $s_k => &$s) {
			$s['title'] = empty($s['title']) ? '规格项('.$s_k.')' : $s['title'];
			$availabletime = TIMESTAMP;
			$showorder = "order by displayorder asc";
			$field = "*";
			$sql = " SELECT ".$field." FROM ".tablename('fm453_shopping_spec_item');
			$params = array();
			$condition =" WHERE ";
			$condition .=" specid = :specid ";
			$params[':specid'] = $s['id'];
			$s['items'] = pdo_fetchall($sql.$condition,$params);
			if(is_array($s['items'])) {
				foreach($s['items'] as $key=> $sitem){
					$sitem['time']= empty($sitem['time']) ? '子规格'.$key : $sitem['time'];
					//时效控制
					if($sitem['isitme']==1) {
						if($sitem['startdate']<$availabletime || $sitem['enddate']>$availabletime) {
							unset($s['items'][$key]);
						}
					}
				}
			}
			if(empty($s['items'])) {
				unset($allspecs[$s_k]);
			}
		}
		if($allspecs) {
			$return['result']=TRUE;
			$return['data']=$allspecs;
			return $return;
		}else{
			$return['result']=FALSE;
			$return['msg']='没有有效的规格项!';
			return $return;
		}
	}else{
		$return['result']=FALSE;
		$return['msg']='未查询到相关产品规格项!';
		return $return;
	}
}

//获取指定某个规格ID获取对应的规格项
function fmMod_goods_specs($optionid){
	global $_GPC;
	global $_W;
	global $_FM;
	header("Content-type:text/html;charset=utf-8");//加上网页字符集强制设置，避免中文传输乱码
	$return=array();
	$params=array();
	$id=intval($optionid);
	if($id<=0) {
		$return['result']=FALSE;
		$return['msg']='查询的商品规格optionid非法!';
		return $return;
	}
	$fields='specs';
	$sql ="SELECT ".$fields." FROM " . tablename('fm453_shopping_goods_option');
	$condition =" WHERE ";
	$condition .='id = :id';
	$params['id']=$id;
	$option=pdo_fetch($sql.$condition, $params);
	$specs=array();
	if ($option){
		$return['result']=TRUE;
		$specitemids = explode("_", $option['specs']);
		foreach($specitemids as $tkey => $specitemid){
			$specitem = pdo_fetch("select * from " . tablename('fm453_shopping_spec_item') . " where id=:id", array(':id' => $specitemid));//具体规格项的全部规格值
			$spectitle = pdo_fetchcolumn("select title from " . tablename('fm453_shopping_spec') . " where id=:id", array(':id' => $specitem['specid']));//根据规格项的id获取规格名称
			$specs[]=array(
				'title' => $spectitle,
				'item' => $specitem
			);
		}
		$return['data']=$specs;
		return $return;
	}else{
		$return['result']=FALSE;
		$return['msg']='未查询到相关产品规格项!';
		return $return;
	}
}
//商品规格具体参数
function fmMod_goods_optionparams(){
	$optionparams=array();
	$optionparams['stock']=array('title'=>'库存','css'=>'info');
	$optionparams['marketprice']=array('title'=>'销售价','css'=>'success');
	$optionparams['cankaoprice']=array('title'=>'参考价','css'=>'info');
	$optionparams['agentsaleprice']=array('title'=>'代理销售价','css'=>'success');
	$optionparams['agentprice']=array('title'=>'代理结算价','css'=>'info');
	$optionparams['costprice']=array('title'=>'成本价','css'=>'danger');
	$optionparams['weight']=array('title'=>'重量(g)','css'=>'warning');
	return $optionparams;
}

//获取指定某个商品的关联的具体规格(后端)
function fmMod_goods_detail_options_w($goodsid){
	global $_GPC;
	global $_W;
	global $_FM;
	header("Content-type:text/html;charset=utf-8");//加上网页字符集强制设置，避免中文传输乱码
	$return=array();
	$params=array();
	$goodsid=intval($goodsid);
	if($goodsid<=0) {
		$return['result']=FALSE;
		$return['msg']='查询的商品id非法!';
		return $return;
	}
	$fields='*';
	$sql ="SELECT ".$fields." FROM " . tablename('fm453_shopping_goods_option');
	$condition =" WHERE ";
	$condition .='goodsid = :goodsid';
	$params['goodsid']=$goodsid;
	$showorder = ' ORDER BY  id  ASC';
	$result=pdo_fetchall($sql.$condition.$showorder, $params);
	if ($result){
		$return['result']=TRUE;
		$options=$result;
		//对$options做个数据量判断，以防后续操作出错
		if (count($options) <= 0) {
			return;
		}

		$get_allspecs=fmMod_goods_detail_specs_w($goodsid);
		//规格参数
		$optionparams=fmMod_goods_optionparams();
		if($get_allspecs['result']) {
			$allspecs=$get_allspecs['data'];
		}
		$html = "";
		//规格项
		$specs = array();
		$specitemids = explode("_", $options[0]['specs'] );//将具体规格对应规格项分拆成数组，该数组以规格项id为键值
		//避免因数据库出现spec表的部分错误导出读不到有效的spec，此处对$allspecs做个判断；
		if(is_array($allspecs)){
			foreach($specitemids as $itemid){
				foreach($allspecs as $ss){
					$items = $ss['items'];
					foreach($items as $it){
						if($it['id']==$itemid){
							$specs[] = $ss;
							break;
						}
					}
				}
			}
		}else{
			//如果未获取到$allspecs，就将各spec项都留空，这样前端仍能继续编辑商品;仅当规格是由一或两组规格项构成时有效
			if(count($specitemids) ==1 || count($specitemids) ==2) {
				$fm_i = count($specitemids);
				$fm_o = count($options);
				$fm_j = intval($fm_o / $fm_i);
				for($i=0;$i<$fm_i;$i++){
					for($j=0;$j<$fm_j;$j++){
						$specs[$i][]=array();
					}
				}
			}
		}
			$len = count($specs);//有多少个规格项
			$newlen = 1; //多少种子规格组合，起始数为1；加乘方式计算
		//表头
			$html .= '<table class="table table-bordered table-responsive">';
			$html .= '<thead>';
			$html .= '<tr class="active">';
			//左侧，规格项组合列
			$h = array(); //显示表格二维数组
			$rowspans = array(); //每个列的rowspan
			for ($i = 0; $i < $len; $i++) {
				//表头
				$html .= "<th style='width:50px;'>" . $specs[$i]['title'] . "</th>";
				//计算多种组合
				$itemlen = count($specs[$i]['items']);
				if ($itemlen <= 0) {
					$itemlen = 1;
				}
				$newlen *= $itemlen;
				//初始化 二维数组
				$h = array();
				for ($j = 0; $j < $newlen; $j++) {
					$h[$i][$j] = array();
				}
				//计算rowspan
				$l = count($specs[$i]['items']);
				$rowspans[$i] = 1;
				for ($j = $i + 1; $j < $len; $j++) {
					$rowspans[$i]*= count($specs[$j]['items']);
				}
			}
			//右侧，批量填值功能
			foreach($optionparams as $op_key => $optionparam){
				$html .= '
				<th class="'.$optionparam['css'].'" style="width:80px;">
			<div class="">
			<div style="padding-bottom:10px;text-align:center;font-size:14px;">'.$optionparam['title'].'</div>
			<div class="input-group">
			<input type="text" class="form-control option_'.$op_key.'_all"  VALUE=""/>
			<span class="input-group-addon">
			<a href="javascript:;" class="fa fa-hand-o-down" title="批量设置" onclick="setCol(\'option_'.$op_key.'\');"></a>
			</span>
			</div>
			</div>
			</th>
				';
			}
			$html .= '</tr></thead>';
			//表身
			for ($m = 0; $m < $len; $m++) {
				$k = 0;
				$kid = 0;
				$n = 0;
				for ($j = 0; $j < $newlen; $j++) {
					$rowspan = $rowspans[$m];
					if ($j % $rowspan == 0) {
						$h[$m][$j] = array("html" => "<td rowspan='" . $rowspan . "'>" . $specs[$m]['items'][$kid]['title'] . "</td>", "id" => $specs[$m]['items'][$kid]['id']);
					} else {
    				$h[$m][$j] = array("html" => "", "id" => $specs[$m]['items'][$kid]['id']);
					}
					$n++;
					if ($n == $rowspan) {
						$kid++;
						if ($kid > count($specs[$m]['items']) - 1) {
							$kid = 0;
						}
						$n = 0;
					}
				}
			}

			$hh = "";
			for ($i = 0; $i < $newlen; $i++) {
				$hh.="<tr>";
				$ids = array();
				for ($j = 0; $j < $len; $j++) {
					$hh.=$h[$j][$i]['html'];
					$ids[] = $h[$j][$i]['id'];
				}
				$ids = implode("_", $ids);
				$val = array("id" => "","title"=>"", "stock" => "", "costprice" => "","agentprice" => "", "cankaoprice" => "","agentsaleprice" => "", "marketprice" => "", "weight" => "");
				foreach ($options as $o) {
					if ($ids === $o['specs']) {
						$val = array(
							"id" => $o['id'],
							"title" =>$o['title'],
							"stock" => $o['stock'],
							"costprice" => $o['costprice'],
							"agentprice" =>$o['agentprice'],
							"cankaoprice" => $o['cankaoprice'],
							"agentsaleprice" => $o['agentsaleprice'],
							"marketprice" => $o['marketprice'],
							"weight" => $o['weight']
						);
						break;
					}
				}
				$hh .= '<td class="hidden">';
				$hh .= '<input name="option_id_' . $ids . '[]"  type="hidden" class="form-control option_id option_id_' . $ids . '" value="' . $val['id'] . '"/>';
				$hh .= '<input name="option_ids[]"  type="hidden" class="form-control option_ids option_ids_' . $ids . '" value="' . $ids . '"/>';
				$hh .= '<input name="option_title_' . $ids . '[]"  type="hidden" class="form-control option_title option_title_' . $ids . '" value="' . $val['title'] . '"/>';
				$hh .= '</td>';
			foreach($optionparams as $op_key => $optionparam){
				$hh .= '<td class="'.$optionparam['css'].'">';
				$hh .= '<input name="option_'.$op_key.'_' . $ids . '[]"  type="text" class="form-control option_'.$op_key.' option_'.$op_key.'_' . $ids . '" value="' . $val[$op_key] . '"/>';
				$hh .='</td>';
			}
				$hh .= '</tr>';

			}
			$html .= $hh;
			$html .= "</table>";
			//表格结束
		$return['data']=$html;
		return $return;
	}else{
		$return['result']=FALSE;
		$return['msg']='未查询到相关产品具体规格!';
		return $return;
	}
}

//获取指定某个商品的具体规格（前端）
function fmMod_goods_detail_options_m($goodsid){
	global $_GPC;
	global $_W;
	global $_FM;
	header("Content-type:text/html;charset=utf-8");//加上网页字符集强制设置，避免中文传输乱码
	$return=array();
	$params=array();
	$goodsid=intval($goodsid);
	if($goodsid<=0) {
		$return['result']=FALSE;
		$return['msg']='查询的商品id非法!';
		return $return;
	}
	$fields='*';
	$sql ="SELECT ".$fields." FROM " . tablename('fm453_shopping_goods_option');
	$condition =" WHERE ";
	$condition .='goodsid = :goodsid';
	$params['goodsid']=$goodsid;
	$showorder = ' ORDER BY  id  ASC';
	$result=pdo_fetchall($sql.$condition.$showorder, $params);
	if ($result){
		$return['result']=TRUE;
		$return['data']=$result;
		return $return;
	}else{
		$return['result']=FALSE;
		$return['msg']='未查询到相关产品具体规格!';
		return $return;
	}
}

//获取指定某个商品的全部信息
function fmMod_goods_detail_all($goodsid){
	global $_GPC;
	global $_W;
	global $_FM;
	header("Content-type:text/html;charset=utf-8");//加上网页字符集强制设置，避免中文传输乱码
	$return=array();
	$goodsdetail=array();
	$id = intval($goodsid);
	if ($id<=0){
		$return['result']=FALSE;
		$return['msg']='查询的商品ID非法!';
		return $return;
	}
	//基础信息
	$result_basic = fmMod_goods_detail_basic($goodsid);
	$goodsdetail=$result_basic['data'];
	$sn=$goodsdetail['sn'];
	$goodstpl=$goodsdetail['goodtpl'];
	//产品模型参数
	$result_tplparams=fmMod_goods_detail_tplparams($sn,$goodstpl);
	$goodsdetail['tplparams']=$result_tplparams['data'];
	//自定义参数
	$result_params=fmMod_goods_detail_params($goodsid);
	$goodsdetail['params']=$result_params['data'];
	//标签
	$result_labels=fmMod_goods_detail_labels($goodsid);
	$goodsdetail['labels']=$result_labels['data'];
	//关联营销模型应用清单及购买跳转链接
	$result_marketing=fmMod_goods_detail_marketing($goodsid);
	$goodsdetail['marketmodel']=$result_params['data']['marketmodel'];
	$goodsdetail['buylink']=$result_params['data']['buylink'];
	//规格项与子项
	$result_specs=fmMod_goods_detail_specs_w($goodsid);
	$goodsdetail['allspecs']=$result_specs['data'];
	//具体规格
	$result_options=fmMod_goods_detail_options_w($goodsid);
	$goodsdetail['options']=$result_options['data'];

	$return['result']=TRUE;
	$return['data']=$goodsdetail;
	return $return;
}

//获取指定某个商品的全部信息(前端展示)
function fmMod_goods_detail_all_m($goodsid,$gsn=NULL){
	global $_GPC;
	global $_W;
	global $_FM;
	header("Content-type:text/html;charset=utf-8");//加上网页字符集强制设置，避免中文传输乱码
	$return=array();
	$id = intval($goodsid);
	$sn = intval($gsn);
	if ($id<=0){
		$return['result']=FALSE;
		$return['msg']='查询的商品ID非法!';
		return $return;
	}
	//基础信息
	$result_basic = fmMod_goods_detail_basic($goodsid);
	if (!$result_basic['result']) {
		fm_error('抱歉，商品不存在或是已经被删除！');
	}
	$goodsdetail=$result_basic['data'];
	if($sn>0 && $sn !=$goodsdetail['sn']) {
		fm_error('抱歉，传入的商品id与系统编号不匹配！请联系网站方面解决');
	}
	$goodstpl=$goodsdetail['goodtpl'];
	$hasoption = $goodsdetail['hasoption'];
	//产品模型参数
	$result_tplparams=fmMod_goods_detail_tplparams($sn,$goodstpl);
	$goodsdetail['tplparams']=$result_tplparams['data'];
	//自定义参数
	$result_params=fmMod_goods_detail_params($goodsid);
	$goodsdetail['params']=$result_params['data'];
	//标签
	$result_labels=fmMod_goods_detail_labels($goodsid);
	$goodsdetail['labels']=$result_labels['data'];
	//关联营销模型应用清单及购买跳转链接
	$result_marketing=fmMod_goods_detail_marketing($goodsid);
	$goodsdetail['marketmodel']=$result_params['data']['marketmodel'];
	$goodsdetail['buylink']=$result_params['data']['buylink'];
	//规格项与子项
	$result_specs=fmMod_goods_detail_specs_m($goodsid);
	$goodsdetail['allspecs']=$result_specs['data'];
	//具体规格
	$result_options=fmMod_goods_detail_options_m($goodsid);
	$goodsdetail['options']=$result_options['data'];
	//进行一些处理
	$allspecs=$goodsdetail['allspecs'];
	$options=$goodsdetail['options'];
	//规格项specs处理
	$specs = array();
	//排序好的specs
//找出数据库存储的排列顺序
	if (count($options) > 0) {
		$specitemids = explode("_", $options[0]['specs'] );
		foreach($specitemids as $itemid){
			foreach($allspecs as $ss){
				$items = $ss['items'];
				foreach($items as $it){
					if(!empty($it['id'])) {
						if($it['id']==$itemid){
							$specs[] = $ss;
							break;
						}
					}
				}
			}
		}
	}
	$goodsdetail['specs']=$specs;
	$goodsdetail['isavailabe'] = (empty($specs) && $hasoption==1) ? 0 : 1;
	//整理佣金比
	$goodsdetail['commissions']=array(
		'1'=>$goodsdetail['commission'],
		'2'=>$goodsdetail['commission2'],
		'3'=>$goodsdetail['commission3']
	);

	$return['result']=TRUE;
	$return['data']=$goodsdetail;
	return $return;
}

//批量修改商品的通知对象（产品专员、库管、供应商对接人……）
function fmMod_goods_openids($platid,$openids,$field,$type){
	global $_GPC;
	global $_W;
	global $_FM;
	header("Content-type:text/html;charset=utf-8");//加上网页字符集强制设置，避免中文传输乱码
	$return=array();
	if(!$_W['isfounder']){
		$return['result']= FALSE;//本操作只允许站长执行
		$return['msg']='本操作仅允许站长执行！';
		return $return;
	}
	$return['data']=array();
	$fields=array('goodadm','stockadm','supplyadm');
	$types=array('add','del');
	$platid=intval($platid);
	$uniacid=$_W['uniacid'];
	$sql = "SELECT id,goodadm FROM " . tablename('fm453_shopping_goods');
	$params=array();
	$condition =' WHERE ';
	if($platid != $uniacid){
		$condition .=' uniacid IN ('. $platid .','. $uniacid .') ';
	}else{
		$condition .= 'uniacid :=uniacid';
		$params[':uniacid']=$uniacid;
	}
	$allgoods = pdo_fetchall($sql.$condition,$params);
	//开始处理
	foreach($allgoods as $good) {
		$id=$good['id'];
	//产品专员
		if($field=='goodadm') {
			$goodadms=$good['goodadm'];
			if($type=='add') {
				if(!empty($goodadms)) {
					$tmpgoodadms=$goodadms.','.$openids;
				}else {
					$tmpgoodadms=$openids;
				}
				$oldgoodadms=explode(',',$tmpgoodadms);//字符串转数组
				$newgoodadms = array_filter($oldgoodadms);//数组去空值
				$newgoodadms=array_unique($newgoodadms);//数组去重
				//数组拼接成文本，方便存储
				$newgoodadms =implode(',',$newgoodadms);
				$return['data'][$id]=pdo_update("fm453_shopping_goods", array("goodadm" => $newgoodadms), array("id" => $id));
			}elseif($type=='del') {
				if(!empty($goodadms)) {
					if(!empty($openids)) {
					//字符串转数组
						$delgoodadms=explode(',',$openids);
						$delgoodadms = array_filter($delgoodadms);//数组去空值
						$delgoodadms=array_unique($delgoodadms);//去重

						$goodadms=explode(',',$goodadms);
						$newgoodadms = array();
						//从产品专员列表中清除匹配待删除的openid
						foreach($goodadms as $gdm){
							if(!in_array($gdm,$delgoodadms)) {
								$newgoodadms[]=$gdm;
							}
						}
						//数组拼接成文本，方便存储
						$newgoodadms =implode(',',$newgoodadms);
						$return['data'][$id]=pdo_update("fm453_shopping_goods", array("goodadm" => $newgoodadms), array("id" => $id));
					}
				}
			}
		}
	}
	$return['result']=TRUE;
	return $return;
}

//____________产品营销模型_________________________//
//营销模型列表
function fmMod_goods_marketModelTypes(){
	return array(
		'presale'=>'预售',
		'limitnum'=>'限量',
		'limittime'=>'限时',
		'freedispatch'=>'包邮',
		'minus'=>'满减',
	);
}

//根据营销模型专题获取产品ID
function fmMod_goods_getId_special($special) {
	global $_GPC;
	global $_W;
	global $_FM;
	header("Content-type:text/html;charset=utf-8");//加上网页字符集强制设置，避免中文传输乱码
	$return=array();
	if (empty($special)){
		$return['result']=FALSE;
		$return['msg']='没有明确查询的营销专题!';
		return $return;
	}
	if($special=='gift'){
		$fields = "`gid`";
		$sql = " SELECT ".$fields." FROM ".tablename('fm453_shopping_goods_marketmodel');
		$condition = " WHERE ";
		$params=array();
		$condition .= " uniacid = :uniacid ";
		$params[':uniacid']=$_W['uniacid'];
		$condition .= " AND ";
		$condition .= " isgiftable = :isgiftable ";
		$params[':isgiftable']=1;
		$showorder = " ORDER BY gid DESC";
		$ids=pdo_fetchall($sql.$condition.$showorder,$params);
		if(count($ids)>0){
			$return['result']=TRUE;
			$return['data']=$ids;
			return $return;
		}else{
			$return['result']=FALSE;
			$return['msg']='该营销专题内尚无产品!';
			return $return;
		}
	}
}
