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
 * @remark 按产品模型关联订单；
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;
load()->func('tpl');
load()->model('account');//加载公众号函数
load()->model('mc');
fm_load()->fm_func('status');
fm_load()->fm_model('order'); //订单管理模块
fm_load()->fm_model('category'); //分类管理模块
fm_load()->fm_model('goodstpl'); //商品模型管理模块

//加载风格模板及资源路径

$fm453style = fmFunc_ui_shopstyle();
$fm453resource =FM_RESOURCE;

//入口判断
$routes=fmFunc_route_web();
$routes_do=fmFunc_route_web_do();
$do= $_GPC['do'];
$ac=$_GPC['ac'];
$all_ac=fmFunc_route_web_ac($do);
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$all_op=fmFunc_route_web_op_single($do,$ac);
if(!in_array($ac,$all_ac) || !in_array($operation,$all_op)){
	die(message('非法访问，请通过有效路径进入！'));
}

fmMod_privilege_adm();//检查用户授权

//开始操作管理
$shopname=$settings['brands']['shopname'];
$shopname = !empty($shopname) ? $shopname :FM_NAME_CN;
$_W['page']['title'] = $shopname.$routes[$do]['title'];
$direct_url=fm_wurl($do,$ac,$operation,'');
$urladdons = array();
$pindex=max(1,intval($_GPC['page']));
$urladdons['page'] = $pindex;
$psize=(intval($_GPC['psize'])>5) ? intval($_GPC['psize']) : 5;//最少显示5条主数据

$uniacid=$_W['uniacid'];
$plattype=$settings['plattype'];
$platids = fmFunc_getPlatids();//获取平台模式关联的公众号商户ID参数
$platid=$_W['uniacid'];
$oauthid=$platids['oauthid'];
$fendianids=$platids['fendianids'];
$supplydianids=$platids['supplydianids'];
$blackids=$platids['blackids'];

$gtpltype=fmMod_goodstpl_type_get();//列出全部产品模型清单
$marketmodeltypes =fmFunc_market_types();
$allorderstatus=fmFunc_status_get('order');
$paytype=fmFunc_status_get('paytype');
$datatype= fmFunc_data_types();

//平台模式处理
require_once FM_PUBLIC.'plat.php';
$supplydians=explode(',',$supplydianids);//字符串转数组
$supplydians=array_filter($supplydians);//数组去空

//按平台模式前置筛选条件
$condition=' WHERE ';
$params=array();
include_once FM_PUBLIC.'forsearch.php';

$limit=" LIMIT ".($pindex-1)*$psize.",".$psize;
$showorder=" ORDER BY createtime DESC ";
$total=array();

//关联产品模型
$goodtpl= $_GPC['goodtpl'];
$hasorders = fmMod_order_get_bygoodtpl($goodtpl);
if($hasorders['result']){
	$orderids = $hasorders['data'];
	$orderids='('. implode(',',$orderids) .')';
	$condition .= " AND id IN ".$orderids;
	$urladdons['goodtpl']=$goodtpl;
}else{
	message('该类型产品暂无销售记录,请重新选择产品模型',fm_wurl('goods','list','',array()),'info');
}
$condition .= ' AND `deleted` = :deleted';
$params[':deleted'] = 0;
//时间匹配
if (!empty($_GPC['time'])) {
	$starttime = strtotime($_GPC['time']['start']);
	$endtime = strtotime($_GPC['time']['end']) + 86399;
	$condition .= " AND createtime >= :starttime AND createtime <= :endtime ";
	$params[':starttime'] = $starttime;
	$params[':endtime'] = $endtime;
}
if (empty($starttime) || empty($endtime)) {
	$starttime = -28800;//1970-01-01 00:00:00
	$endtime = TIMESTAMP;
}
$searchtime=array(
	'start' => date('Y-m-d',$starttime),
	'end' => date('Y-m-d',$endtime),
	);
$urladdons['time[start]']=date('Y-m-d',$starttime);
$urladdons['time[end]']=date('Y-m-d',$endtime);
if (!empty($_GPC['paytype']) && $_GPC['paytype'] !='code') {
	$condition .= " AND paytype = :paytype";
	$params[':paytype'] = $paytype[$_GPC['paytype']]['value'];
	$urladdons['paytype']=$_GPC['paytype'];
}
//订单号模糊查询
if (!empty($_GPC['keyword'])) {
	$condition .= " AND ordersn LIKE :ordersn";
	$params[':ordersn']='%'.$_GPC['keyword'].'%';
	$urladdons['keyword']=$_GPC['keyword'];
}
//会员匹配
if (!empty($_GPC['member'])) {
	$uid_result=fmMod_member_uids($_GPC['member']);
	if($uid_result['result']) {
		$temp_uids=$uid_result['data'];
		$uids=array();
		foreach($temp_uids as $key=> $t_uid){
			$uids[]=$t_uid['uid'];
		}
		$uids='('. implode(',',$uids) .')';
		$condition .= " AND fromuid IN ".$uids;
		$urladdons['member']=$_GPC['member'];
	}else{
		unset($_GPC['member']);
	}
}
	//从订单备注remark中搜索信息
if (!empty($_GPC['remark'])) {
	$condition .= " AND remark LIKE :remark";
	$params[':remark']='%'.$_GPC['remark'].'%';
	$urladdons['remark']=$_GPC['remark'];
}
	//从订单关联微支付流水号transid中搜索信息
if (!empty($_GPC['transid'])) {
	$condition .= " AND transid LIKE :transid";
	$params[':transid']='%'.$_GPC['transid'].'%';
	$urladdons['transid']=$_GPC['transid'];
}
	//从订单客服操作备注remark_kf中搜索信息
if (!empty($_GPC['remark_kf'])) {
	$condition .= " AND remark_kf LIKE :remark_kf";
	$params[':remark_kf']='%'.$_GPC['remark_kf'].'%';
	$urladdons['remark_kf']=$_GPC['remark_kf'];
}
//发货状态
$sendtype = !isset($_GPC['sendtype']) ? 0 : $_GPC['sendtype'];
$urladdons['sendtype']=$sendtype;
if (!empty($sendtype)) {
	$condition .= " AND sendtype = :sendtype AND status != :status";
	$params[':sendtype']=intval($sendtype);
	$params[':status']=3;
}
//查询各状态订单总数
$all_total=0;
foreach($allorderstatus as $key =>$temp_status){
	if($key !='code') {
		$params[':status']=$temp_status['value'];
		$total[$key] = pdo_fetchcolumn("select count(id) from ". tablename('fm453_shopping_order').$condition." AND status = :status",$params);
		$all_total +=$total[$key];
	}
	unset($params[':status']);
}
$total['code']=$all_total;
//继续正常查询
if (!empty($_GPC['status']) && $_GPC['status'] !='code') {
	$condition .= " AND status = :status";
	$params[':status']=intval($allorderstatus[$_GPC['status']]['value']);
	$urladdons['status']=$_GPC['status'];
}
$mlist = pdo_fetchall("SELECT `name`,`title` FROM ".tablename('modules'),array(),'name');//模块名称

if($operation=='display'){//此处接收来自产品列表中的产品ID参数并据此查询得出产品列表
	$list = pdo_fetchall("SELECT id,ordersn FROM " . tablename('fm453_shopping_order').$condition.$showorder.$limit,$params);
	$now_total = pdo_fetchcolumn("select count(id) from". tablename('fm453_shopping_order').$condition,$params);
	$pager = pagination($now_total, $pindex, $psize);
	foreach ($list as &$value) {
		$result = fmMod_order_info($value['ordersn'],$value['id']);
		$value = $result['data'];
	}
	//获取产品相关信息
	$ordergood = pdo_fetch("SELECT * FROM " . tablename('fm453_shopping_goods') . "  WHERE  id= :id",array(':id'=>$gid));
	include $this->template($fm453style.$do.'/453');
}
elseif ($operation == 'export') {//此处接收来自产品列表中的产品ID参数并据此查询得出产品列表
	$haslimit=!empty($_GPC['haslimit']) ? $_GPC['haslimit'] : 'page';
	if($haslimit=='all') {
		$outdata = pdo_fetchall("SELECT ordersn FROM " . tablename('fm453_shopping_order') . $condition.$showorder,$params);
	}elseif($haslimit=='page') {
		$outdata = pdo_fetchall("SELECT ordersn FROM " . tablename('fm453_shopping_order') . $condition.$showorder.$limit,$params);
	}

	//导出数据START
	if ($_GPC['export']) {
		/* 输入到CSV文件 */
		$html = "\xEF\xBB\xBF";//UTF8标记
		$html .= "\n";
		/* 输出表头 */
		$filter = array(
				'ordersn' => '系统订单编号',
				'username' => '联系人姓名',
				'mobile' => '联系电话',
				'address' => '订单联系地址',
				'paytype' => '支付方式',
				'transid' => '微信支付订单号',
				'dispatch' => '配送方式',
				'dispatchprice' => '运费',
				'price' => '总价',
				'status' => '状态',
				'tips' => '订单关联信息',
				'createtime' => '下单时间',
				'fromuid' => '下单人会员ID',
				'mem_name' => '下单人姓名',
				'mem_mobile' => '下单人联系电话',
				'mem_address' => '下单人联系地址',
				'goodtpl' => '产品模型',
				'commission' => '1级分销佣金',
				'commission2' => '2级分销佣金',
				'commission3' => '3级分销佣金',
				'shareid'=> '推荐人会员ID',
				'shareby'=> '推荐人名称',
				'ucontainer'=> '下单人设备',
				'uos'=> '设备OS',
				'mpaccountname' => '来源公号',
				'uniacid' => '公号',
				'platname' => '平台',
		);
		foreach ($filter as $key => $title) {
			$html .= $title . "\t";
		}
		$html .= "\n";//换行
		foreach ($outdata as $k => &$v) {
			$result = fmMod_order_info($v['ordersn']);
			$v = $result['data'];
			foreach ($filter as $key => $title) {
				if($key=="platname") {
					$default= uni_account_default($v['uniacid']);
					$html .= $default['name']."\t";
				}elseif($key=="ordersn") {
					$html .= "'".$v['ordersn']."\t";
				}elseif($key=="price") {
					$html .= " ".$v['price']."元"."\t";
				}elseif($key=="dispatchprice") {
					$html .= " ".$v['dispatchprice']."元"."\t";
				}elseif($key=="dispatch") {
					$html .= $v['wuliu']['dispatch']['dispatchname']."\t";
				}elseif($key=="createtime") {
					$html .= date('Y年m月d日 H:i:s',$v['createtime'])."\t";
				}else {
					$html .= $v[$key] . "\t";
				}
			}
			$html .= "\n";
		}
		/* 输出CSV文件 */
		header("Content-type:text/xls");
		header("Content-Type:application/force-download");
		header("Content-Type:application/vnd.ms-execl");
		header("Content-Type:application/octet-stream");
		header("Content-Type:application/download");
		header("Content-Transfer-Encoding:binary");
		header("Content-Disposition:attachment; filename=".$shopname."订单列表-关联产品".$gid.".xls");
		echo $html;

		$dologs=array(
			'url'=>$_W['siteurl'],
			'description'=>'后台导出订单数据',
		);
		fmMod_log_record($platid,$_W['uniacid'],$_W['uid'],$fanid,$openid,'fm453_shopping_order',$id,'export',$dologs);
		unset($dologs);
		//给管理员发个微信消息
		fmMod_notice($settings[manageropenids],array(
			'header'=>array('title'=>'事件通知','value'=>'后台导出订单数据'),
			'user'=>array('title'=>'操作人','value'=>$_W['username'].'('.$_W['uid'].')'),
			'url'=>array('title'=>'执行网址','value'=>$_W['siteurl']),
		));
		exit();
	}
//导出数据END
}