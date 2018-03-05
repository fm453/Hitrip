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
 * @remark 积分营销管理；
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;
return(message('功能暂未开放'));

load()->func('tpl');
load()->model('account');//加载公众号函数

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
$pindex=max(1,intval($_GPC['page']));
$psize=(intval($_GPC['psize'])>5) ? intval($_GPC['psize']) : 15;//最少显示5条主数据

$uniacid=$_W['uniacid'];
$plattype=$settings['plattype'];
$platids = fmFunc_getPlatids();//获取平台模式关联的公众号商户ID参数
$platid=$_W['uniacid'];
$oauthid=$platids['oauthid'];
$fendianids=$platids['fendianids'];
$supplydianids=$platids['supplydianids'];
$blackids=$platids['blackids'];

$gtpltype = fmMod_goodstpl_type_get();//列出全部产品模型清单
$marketmodeltypes =fmFunc_market_types();
$paylogstatus=fmFunc_status_get('paylog');
$datatype= fmFunc_data_types();

//平台模式处理
include_once FM_PUBLIC.'plat.php';
$supplydians=explode(',',$supplydianids);//字符串转数组
$supplydians=array_filter($supplydians);//数组去空
//print_r($supplydians);

//按平台模式前置筛选条件
$condition=' WHERE ';
$params=array();
include_once FM_PUBLIC.'forsearch.php';
//$condition .= ' AND `deleted` = :deleted';
//$params[':deleted'] = 0;

$limit=" LIMIT ".($pindex-1)*$psize.",".$psize;
$paytype=array();
$paytype['all']=array('name'=>'全部');
$paytype['alipay']=array('name'=>'支付宝');
$paytype['wechat']=array('name'=>'微信');
$paytype['credit']=array('name'=>'余额');
$paytype['charge']=array('name'=>'充值');
$mlist = pdo_fetchall("SELECT `name`,`title` FROM ".tablename('modules'),array(),'name');
 	//post筛选数据
	$keyword=trim($_GPC['keyword']);
	if(!empty($keyword)){
		$condition.=' AND openid LIKE :openid ';
		$params[':openid']='%'.$keyword.'%';
	}
	$bypaytype=$_GPC['type'];
	if(!empty($bypaytype) && $bypaytype !='all'){
		$condition.=' AND type = :type ';
		$params[':type']= $bypaytype;
	}
	$bystatus=$paylogstatus[$_GPC['status']]['value'];
	if(!empty($bystatus) && $bystatus !='all'){
		$condition.=' AND status = :status ';
		$params[':status']= $bystatus;
	}
	$onlyfm453=intval($_GPC['module']);
	if($onlyfm453==1){
		$condition.=' AND ';
		$condition.=' module LIKE :module ';
		$params[':module']='%fm453%';
	}
 	$showorder=" ORDER BY uniacid ASC, createtime DESC ";

if ($operation == 'display') {
		$condition = '';
	$list = pdo_fetchall("SELECT * FROM ".tablename('fm453_shopping_credit_award').$condition .$showorder,$params);
	include $this->template($fm453style.$do.'/453');
}
elseif ($operation == 'modify') { // 增加或者更新兑换商品
		$id = intval($_GPC['id']);
		if (!empty($id)) {
				$item = pdo_fetch("SELECT * FROM ".tablename('fm453_shopping_credit_award')." WHERE id = :id" , array(':id' => $id));
				if (empty($item)) {
						message('抱歉，兑换商品不存在或是已经删除！', '', 'error');
				}
			}
		if (checksubmit('submit')) {
				if (empty($_GPC['title'])) {
					message('请输入兑换商品名称！');
				}
				if (empty($_GPC['credit_cost'])) {
					message('请输入兑换商品需要消耗的积分数量！');
				}
				if (empty($_GPC['price'])) {
					message('请输入商品实际价值！');
				}
				$credit_cost = intval($_GPC['credit_cost']);
				$price = intval($_GPC['price']);
				$amount = intval($_GPC['amount']);
				$data = array(
					'uniacid' => $_W['uniacid'],
					'title' => $_GPC['title'],
					'logo' => $_GPC['logo'],
					'deadline' => $_GPC['deadline'],
					'amount' => $amount,
					'credit_cost' => $credit_cost,
					'price' => $price,
					'content' => $_GPC['content'],
					'createtime' => TIMESTAMP,
				);
				if (!empty($id)) {
					pdo_update('fm453_shopping_credit_award', $data, array('id' => $id));
				} else {
					pdo_insert('fm453_shopping_credit_award', $data);
				}
				message('积分商品更新成功！','referer', 'success');
		}
	include $this->template($fm453style.$do.'/453');
}else if ($operation == 'delete') { //删除商品
	$award_id = intval($_GPC['award_id']);
	$row = pdo_fetch("SELECT award_id FROM ".tablename('fm453_shopping_credit_award')." WHERE award_id = :award_id", array(':award_id' => $award_id));
	if (empty($row)) {
		message('抱歉，商品'.$award_id.'不存在或是已经被删除！');
	}
	pdo_delete('fm453_shopping_credit_award', array('award_id' => $award_id));
	message('删除成功！', referer(), 'success');
}
