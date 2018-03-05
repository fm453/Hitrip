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
 * @remark 意见收集处理；
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC, $_W,$_FM;
message('功能暂未开放');
load()->func('tpl');
load()->model('account');//加载公众号函数
fm_load()->fm_model('needs');

//加载风格模板及资源路径

$fm453style = fmFunc_ui_shopstyle();
$fm453resource = fmFunc_ui_resource();

//入口判断
$routes=fmFunc_route_web();
$routes_do=fmFunc_route_web_do();
$do= $_GPC['do'];
$ac=$_GPC['ac'];
$all_ac=fmFunc_route_web_ac($do);
$all_op=fmFunc_route_web_op_single($do,$ac);
$operation = empty($_GPC['op']) ? 'display' : $_GPC['op'];
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
$psize=(intval($_GPC['psize'])>10) ? intval($_GPC['psize']) : 10;//最少显示10条主数据

$uniacid=$_W['uniacid'];
$platids = fmFunc_getPlatids();//获取平台模式关联的公众号商户ID
$oauthid=$platids['oauthid'];
$fendianids=$platids['fendianids'];
$supplydianids=$platids['supplydianids'];
$blackids=$platids['blackids'];

//平台模式处理
include_once FM_PUBLIC.'plat.php';
$supplydians=explode(',',$supplydianids);//字符串转数组
$supplydians=array_filter($supplydians);//数组去空

//按平台模式前置筛选条件
$condition=' WHERE ';
$params=array();
include_once FM_PUBLIC.'forsearch.php';
$condition .= ' AND `deleted` = :deleted';
$params[':deleted'] = 0;

$showorder=" ORDER BY uniacid ASC,  displayorder DESC ,id DESC";
$limit=" LIMIT ".($pindex-1)*$psize.",".$psize;

$templates=array(
	'default'=>'默认',
	'QianZhiYaoZhuang'=>'千植药妆',
	'FeiMaoHuiShou'=>'肥猫回收'
);
$cycles=array(
	'day'=>'天',
	'month'=>'周',
	'hour'=>'时',
	'minute'=>'分'
);

if ($operation == 'display') {
	$isbytime=intval($_GPC['isbytime']);
	if (!empty($_GPC['date'])) {
		$starttime = strtotime($_GPC['date']['start']);
		$endtime = strtotime($_GPC['date']['end']) + 86399;
	} else {
		$starttime = strtotime('-3 month');
		$endtime = time();
	}
	if($isbytime) {
		$condition .= " AND `createtime` >= :starttime AND `createtime` < :endtime";
		$params[':starttime'] = $starttime;
		$params[':endtime'] = $endtime;
	}
	$keyword = trim($_GPC['keyword']);
	if (!empty($keyword)) {
		$condition .= " AND `title`LIKE :title";
		$params[':title'] = $keyword;
	}
		$status = empty($_GPC['status']) ? 0 : intval($_GPC['status']);
		$status = intval($status);
		if ($status != 0) {
			$condition .= " AND `status` = :status";
			$params[':status'] = $status;
		}
		$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('fm453_shopping_needs') . $condition, $params);
		$list = pdo_fetchall("SELECT * FROM " . tablename('fm453_shopping_needs') . $condition . $showorder . $limit, $params);
		$pager = pagination($total, $pindex, $psize);

		foreach ($list as $row) {

		}
	include $this->template($fm453style.$do.'/453');
}
elseif($operation=='new'){
	$fields=fmFunc_tables_fields('needs',$dtype='shopping');
	$data=array();
	foreach($fields as $i=>$key){
		$data[$key]=$_GPC[$key];
	}
	$data['createtime']=$_W['timestamp'];
	$data['uniacid']=$_W['uniacid'];
	$data['starttime']=$_W['timestamp'];
	$data['endtime']=$_W['timestamp'];
	unset($data['id']);
	if(checksubmit()) {
		pdo_insert('fm453_shopping_needs',$data);
		$id=pdo_insertid();
		message('表单添加成功',fm_wurl($do,$ac,'detail',array('id'=>$id)),'success');
	}
	include $this->template($fm453style.$do.'/453');
}
elseif($operation=='detail'){
	$id=intval($_GPC['id']);
	if($id<=0) {
		message('需要先选择一个表单',fm_wurl($do,$ac,'display',array()),'info');
	}
	//表单预览页面二维码
			$link_preview =  fm_murl('needs','detail','index',array('id' => $id));
			$qrcode=fmFunc_qrcode_name_m($platid,'needs','detail','index',$id,0,0);
			fmFunc_qrcode_check($qrcode,$link_preview);
			$qrcode_preview=tomedia($qrcode);

	$needs=pdo_fetch("SELECT * FROM ".tablename('fm453_shopping_needs')." WHERE id=:id ",array(':id'=>$id));
	$needs_params=pdo_fetchall("SELECT * FROM ".tablename('fm453_shopping_needs_param')." WHERE nid=:nid ",array(':nid'=>$id),'setfor');
	$settings['needs']=array();
	if(is_array($needs_params) && !empty($needs_params)) {
		foreach($needs_params as $key=>$value){
			if($value['status']==1) {
				$settings['needs'][$key]=$value['value'];
			}
		}
	}
	$needs['content'] = htmlspecialchars_decode($needs['content']);
	if(checksubmit()) {
		$fields=fmFunc_tables_fields('needs',$dtype='shopping');
		$data=array();
		foreach($fields as $i=>$key){
			$data[$key]=$_GPC[$key];
		}
		unset($data['createtime']);
		unset($data['uniacid']);
		unset($data['id']);
		$result=fmMod_needs_w_modify($data,$id);
		message('表单更新成功',fm_wurl($do,$ac,'detail',array('id'=>$id)),'success');
	}
	include $this->template($fm453style.$do.'/453');
}
elseif($operation=='data'){
	$id=intval($_GPC['id']);
	if($id<=0) {
		message('需要先选择一个表单',fm_wurl($do,$ac,'display',array()),'info');
	}
	$cols=array();
	$cols['default']=array(
	'setfor'=>'会员',
	'name'=>'姓名',
	'mobile'=>'手机'
	);
	$cols['QianZhiYaoZhuang']=array(
	'question'=>'诉求',
	'wxhao'=>'微信号'
	);
	$cols['FeiMaoHuiShou']=array(
	'starttime'=>'上门时间',
	'cityarea'=>'区域',
	'street'=>'街道',
	'address'=>'详细地址',
	'house'=>'房号',
	'remark'=>'备注信息'
	);
	$needs=pdo_fetch("SELECT * FROM ".tablename('fm453_shopping_needs')." WHERE id=:id ",array(':id'=>$id));
	$condition='';
	$params=array();
	$isbytime=intval($_GPC['isbytime']);
	if (!empty($_GPC['date'])) {
		$starttime = strtotime($_GPC['date']['start']);
		$endtime = strtotime($_GPC['date']['end']) + 86399;
	} else {
		$starttime = strtotime('-3 month');
		$endtime = time();
	}
	if($isbytime) {
		$condition .= " AND `createtime` >= :starttime AND `createtime` < :endtime";
		$params[':starttime'] = $starttime;
		$params[':endtime'] = $endtime;
	}
	$keyword = trim($_GPC['keyword']);
	if (!empty($keyword)) {
		//$temp_sns = pdo_fetchall("SELECT sn FROM " . tablename('fm453_shopping_needs_data') . " WHERE nid=:nid AND `value` LIKE :value " , array(':nid'=>$id,':value'=>$keyword));
		//$temp_sns = pdo_fetchall("SELECT sn FROM " . tablename('fm453_shopping_needs_data') . " WHERE nid=:nid " , array(':nid'=>$id));
		//$temp_sns = array_unique($temp_sns);
		//$temp_sns = implode(',',$temp_sns);
		//$condition .= " AND sn IN (".$temp_sns.")";
		$condition .= " AND `setfor` = :setfor";
		$params[':setfor'] = intval($keyword);
	}

	$status = empty($_GPC['status']) ? 0 : intval($_GPC['status']);
	$status = intval($status);
	if ($status != 0) {
		$condition .= " AND `status` = :status";
		$params[':status'] = $status;
	}

	$showorder=" ORDER BY createtime DESC ";
	$params[':nid']=$id;
	$needs_sn = pdo_fetchall("SELECT sn FROM " . tablename('fm453_shopping_needs_data') . " WHERE nid=:nid " . $condition . $showorder, $params,'sn');

	$needs_data = array();
	if($needs_sn) {
		foreach($needs_sn as $key=>$_sn){
			$params[':sn']=$key;
			$needs_data[$key] = pdo_fetchall("SELECT * FROM " . tablename('fm453_shopping_needs_data') . " WHERE nid=:nid " . $condition ." AND sn =:sn ". $showorder . $limit, $params);
		}
	}

	$list=array();
	$keys=array();
	$users=$setfors=array();
	if(is_array($needs_data) && !empty($needs_data)) {
		foreach($needs_data as $key=>$dvalue){
				$keys[$key]=$key;
				$setfor=$dvalue[0]['setfor'];
						$setfors[$setfor]=$setfor;
				$list[$key]=array();
				$list[$key]['setfor']=$setfor;
				$list[$key]['status']=$dvalue[0]['status'];
				$list[$key]['createtime']=$dvalue[0]['createtime'];
				$list[$key]['user_info']=fmMod_member_query($setfor)['data'];
				foreach($dvalue as $i=>$value){
					$title=$value['title'];
					if($cols[$needs['template']][$title] || $title=='name' || $title=='mobile') {
						$list[$key][$title]=$value['value'];
					}elseif($cols['default'][$title]){
						unset($dvalue[$i]);
					}
				}
		}
	}

	$total=count($list);
	$pager = pagination($total, $pindex, $psize);
	include $this->template($fm453style.$do.'/453');
}
elseif($operation=='reply'){
	$id=intval($_GPC['id']);
	if($id<=0) {
		message('需要先选择一个表单',fm_wurl($do,$ac,'display',array()),'info');
	};

	$sn=intval($_GPC['sn']);
	$needs=pdo_fetch("SELECT * FROM ".tablename('fm453_shopping_needs')." WHERE id=:id ",array(':id'=>$id));
	if($sn<=0) {
		message('需要先选择一条表单数据',fm_wurl($do,$ac,'detail',array('id'=>$id)),'info');
	}
		$needs_data=pdo_fetchall("SELECT * FROM ".tablename('fm453_shopping_needs_data')." WHERE nid=:nid  AND sn=:sn",array(':nid'=>$id,':sn'=>$sn),'title');
	if(!empty($needs_data)){
		foreach($needs_data as $key=>$form){
			//$needs_data[$key]=$form['value'];
		}
	}
	$setfor=$needs_data['name']['setfor'];
	$user_info=fmMod_member_query($setfor)['data'];

	if(checksubmit()) {
		$fields=fmFunc_tables_fields('needs_data',$dtype='shopping');
		$data=array();
		foreach($fields as $i=>$key){
			$data[$key]=$_GPC[$key];
		}

		message('回复处理成功',fm_wurl($do,$ac,'data',array('id'=>$id,'sn'=>$sn)),'success');
	}
	include $this->template($fm453style.$do.'/453');
}
else {
	message('请求方式不存在');
}