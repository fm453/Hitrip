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
 * @remark 品牌分类管理；
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;

load()->func('tpl');
load()->model('account');//加载公众号函数
fm_load()->fm_model('category'); //分类管理模块

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
$shopname=$settings['brand']['shopname'];
$shopname = !empty($shopname) ? $shopname :FM_NAME_CN;
$_W['page']['title'] = $shopname.$routes[$do]['title'];
$direct_url=fm_wurl($do,$ac,$operation,'');
$pindex=max(1,intval($_GPC['page']));
$psize=(intval($_GPC['psize'])>5) ? intval($_GPC['psize']) : 5;//最少显示5条主数据

$uniacid=$_W['uniacid'];
$platids=fmFunc_getPlatids();//获取平台模式关联的公众号商户ID
$oauthid=$platids['oauthid'];
$fendianids=$platids['fendianids'];
$supplydianids=$platids['supplydianids'];
$blackids=$platids['blackids'];

$nowtype='brand';
$catetype=array();
$catetype['all']=array(
	'name'=>'不限',
	'status'=>'127',
	);
$catetype['goods']=array(
	'name'=>'商品分类',
	'status'=>'127',
	);
	$catetype['brand']=array(
	'name'=>'品牌分类',
	'status'=>'127',
	);
	$catetype['partner']=array(
	'name'=>'商户分类',
	'status'=>'127',
	);
	$catetype['rule']=array(
	'name'=>'规则分类',
	'status'=>'127',
	);
	$catetype['article']=array(
	'name'=>'文章分类',
	'status'=>'127',
	);
$catestatus=fmFunc_status_get('category');

//数据获取
$condition='';
	if($platids=='-1'){
		$condition.= "uniacid >'{$platids}'";
	}else {
		if($supplydianids){
			$condition.= "uniacid in ({$supplydianids})";
		}else{
			$condition.= "uniacid ='{$uniacid}'";//总店没有供应商店铺
		}
	}

	$keyword=$_GPC['keyword'];
	if(!empty($keyword)){
		$condition.=" AND name LIKE '%{$keyword}%' ";
	}
	$type='all';
	$temp_type=$_GPC['type'];
	if(!empty($temp_type) & $temp_type!=$type){
		$type=$temp_type;
		$condition.=" AND setfor LIKE '%{$temp_type}%' ";
	}
//abs()函数，取绝对值
	$status='code';
	$temp_status=$_GPC['status'];
	if(!empty($temp_status) & $temp_status!=$status){
		$status=$temp_status;
		$temp_status=$catestatus[$temp_status]['value'];
		if($temp_status<0) {
			$temp_abs=abs($temp_status)-1;
			$condition.=" AND statuscode in ({$temp_status},{$temp_abs}) ";
		}else{
			$condition.=" AND statuscode in ({$temp_status}) ";
		}
	}
	$condition.=" AND deleted !=1";
	$condition.=" AND setfor = 'brand' ";

	$showorder=" ORDER BY uniacid ASC, psn ASC, displayorder DESC ";

	$children = array();
	$category = pdo_fetchall("SELECT * FROM " . tablename('fm453_shopping_category') . " WHERE ".$condition.$showorder);
	//更新旧数据，添加SN值
	//fmMod_category_old($category);

	$allcategory=array();
	foreach($category as $row){
		$allcategory[$row['sn']]=$row;
		if ($row['psn']>0) {
			$children[$row['psn']][] = $row;
		}
	}
	unset($category);

	$total=pdo_fetchcolumn("SELECT count(id) FROM ". tablename('fm453_shopping_category') . " WHERE ".$condition." AND psn=0" .$showorder);
	$pager = pagination($total, $pindex, $psize);
	$limit=" LIMIT ".($pindex-1)*$psize.",".$psize;

if ($operation == 'display') {
	$category = pdo_fetchall("SELECT * FROM " . tablename('fm453_shopping_category') . " WHERE ".$condition.$showorder.$limit);
	foreach ($category as $index => &$row) {
		$code='code'.$row['statuscode'];
		$row['statusname']=$catestatus[$code]['name'];
		$row['plataccount']=uni_account_default($row['uniacid']);
		if($row['uniacid']==0) {
			$row['plataccount']['name']="系统平台";
		}
		if(empty($row['plataccount'])) {
			$row['plataccount']['name']="已经删除";
		}
		if ($row['psn']>0) {
			//$child[$row['psn']] = $row;
			foreach($children[$row['psn']] as &$child){
				$child['statusname']=$catestatus[$code]['name'];
				$child['plataccount']=uni_account_default($child['uniacid']);
			}
			unset($category[$index]);
		}
		unset($index);
		unset($code);
	}
	unset($row);

	if (!empty($_GPC['displayorder']) ||!empty($_GPC['sn'])) {
		if (!empty($_GPC['displayorder'])) {
			foreach ($_GPC['displayorder'] as $id => $displayorder) {
				pdo_update('fm453_shopping_category', array('displayorder' => $displayorder), array('id' => $id));
			}
			$dologs=array(
				'url'=>$_W['siteurl'],
				'description'=>'后台更新商城分类排序；',
				'addons'=>$_GPC['displayorder'],
			);
			fmMod_log_record($platid,$_W['uniacid'],$_W['uid'],$fanid,$openid,'fm453_shopping_category',$id,'modify',$dologs);
			unset($dologs);
		}
		if (!empty($_GPC['sn'])) {
			foreach ($_GPC['sn'] as $id => $sn) {
				pdo_update('fm453_shopping_category', array('sn' => $sn), array('id' => $id));
			}
			$dologs=array(
				'url'=>$_W['siteurl'],
				'description'=>'后台更新商城分类编码；',
				'addons'=>$_GPC['sn'],
			);
			fmMod_log_record($platid,$_W['uniacid'],$_W['uid'],$fanid,$openid,'fm453_shopping_category',$id,'modify',$dologs);
			unset($dologs);
		}
		message('分类排序\编码更新成功！', 'referer', 'success');
	}
include $this->template($fm453style.$do.'/453');
}
elseif ($operation == 'modify') {
	unset($category);
	$psn = intval($_GPC['psn']);
	$id = intval($_GPC['id']);
	if (!empty($id)) {
		$category = pdo_fetch("SELECT * FROM " . tablename('fm453_shopping_category') . " WHERE id = '{$id}'");
	} else {
		message('请先选择一个分类再操作！', 'referer', 'info');
	}

	if (!empty($psn)) {
		$parent = pdo_fetch("SELECT id, name,setfor FROM " . tablename('fm453_shopping_category') . " WHERE sn = '$psn'");
		if (empty($parent)) {
			message('抱歉，上级分类不存在或是已经被删除！', 'referer', 'error');
		}else{
			$category['setfor']=$parent['setfor'];
		}
	}
	if (checksubmit('submit')) {
		if (empty($_GPC['catename'])) {
			message('抱歉，请输入分类名称！');
		}
		if (empty($_GPC['setfor'])) {
			message('抱歉，请输入分类类型！');
		}
		if (empty($_GPC['thumb'])) {
			message('抱歉，请选择或上传一张图片作为分类图片！');
		}
		$temp_uniacid= !empty($_GPC['uniacid']) ? $_GPC['uniacid'] : $_W['uniacid'];//修复编辑分类时前台未传输uniacid的情况
		$data = array(
			'uniacid' => $temp_uniacid,
			'name' => $_GPC['catename'],
			'enabled' => intval($_GPC['enabled']),
			'displayorder' => intval($_GPC['displayorder']),
			'isrecommand' => intval($_GPC['isrecommand']),
			'commission' => intval($_GPC['commission']),
			'description' => $_GPC['description'],
			'setfor' => $_GPC['setfor'],
			'psn' => intval($_GPC['psn']),
			'thumb' => $_GPC['thumb'],
			'deleted'=>0
			);
			pdo_update('fm453_shopping_category', $data, array('id' => $id));
			load()->func('file');
			file_delete($_GPC['thumb_old']);
			$dologs=array(
				'url'=>$_W['siteurl'],
				'description'=>'后台更新分类数据；',
				'addons'=>$data,
			);
			fmMod_log_record($platid,$_W['uniacid'],$_W['uid'],$fanid,$openid,'fm453_shopping_category',$id,'modify',$dologs);
			unset($dologs);
			message('更新分类成功！', fm_wurl($do,$ac,$operation, array('id' => $id,'page'=>$page)), 'success');
	}
		//包含的模板文件
		include $this->template($fm453style.$do.'/453');
}
elseif ($operation == 'add') {
	unset($category);
	$psn = intval($_GPC['psn']);
	$category = array(
		'uniacid' => $_W['uniacid'],
		'displayorder' => 0,
	);
	if (!empty($psn)) {
		$parent = pdo_fetch("SELECT id, name,setfor FROM " . tablename('fm453_shopping_category') . " WHERE sn = '$psn'");
		if (empty($parent)) {
			message('抱歉，上级分类不存在或是已经被删除！', 'referer', 'error');
		}else{
			$category['setfor']=$parent['setfor'];
		}
	}
	if (checksubmit('submit')) {
		if (empty($_GPC['catename'])) {
			message('抱歉，请输入分类名称！');
		}
		if (empty($_GPC['setfor'])) {
			message('抱歉，请输入分类类型！');
		}
		if (empty($_GPC['thumb'])) {
			message('抱歉，请选择或上传一张图片作为分类图片！');
		}
		$temp_uniacid= !empty($_GPC['uniacid']) ? $_GPC['uniacid'] : $_W['uniacid'];//修复编辑分类时前台未传输uniacid的情况
		$data = array(
			'uniacid' => $temp_uniacid,
			'name' => $_GPC['catename'],
			'enabled' => intval($_GPC['enabled']),
			'displayorder' => intval($_GPC['displayorder']),
			'isrecommand' => intval($_GPC['isrecommand']),
			'commission' => intval($_GPC['commission']),
			'description' => $_GPC['description'],
			'setfor' => $_GPC['setfor'],
			'psn' => intval($_GPC['psn']),
			'thumb' => $_GPC['thumb'],
			'deleted'=>0
			);
			$data['createtime']=TIMESTAMP;
			pdo_insert('fm453_shopping_category', $data);
			$id = pdo_insertid();
			pdo_update('fm453_shopping_category', array('sn'=>$id), array('id' => $id));
			$dologs=array(
				'url'=>$_W['siteurl'],
				'description'=>'后台插入新分类；',
			);
			fmMod_log_record($platid,$_W['uniacid'],$_W['uid'],$fanid,$openid,'fm453_shopping_category',$id,'export',$dologs);
			unset($dologs);
			message('添加分类成功！', fm_wurl($do,$ac,'modify', array('id'=>$id,'psn'=>$psn)), 'success');
		}
	//包含的模板文件
	include $this->template($fm453style.$do.'/453');
}
elseif ($operation == 'copy') {
	unset($category);
	$id = intval($_GPC['id']);
	if (!empty($id)) {
		$category = pdo_fetch("SELECT * FROM " . tablename('fm453_shopping_category') . " WHERE id = '{$id}'");
		if(!$category) {
			message('您要复制的分类无效，请重新选择！', 'referer', 'info');
		}
	} else {
		message('请先选择一个分类再操作！', 'referer', 'info');
	}
	unset($id);
	$category['uniacid'] =$_W['uniacid'];
	$psn=$category['psn'];
	if (!empty($psn)) {
		$parent = pdo_fetch("SELECT id, name,setfor FROM " . tablename('fm453_shopping_category') . " WHERE sn = '$psn'");
	}
	$category['displayorder'] =0;
	unset($category['displayorder']);

	if (checksubmit('submit')) {
		if (empty($_GPC['catename'])) {
			message('抱歉，请输入分类名称！');
		}
		if (empty($_GPC['setfor'])) {
			message('抱歉，请输入分类类型！');
		}
		if (empty($_GPC['thumb'])) {
			message('抱歉，请选择或上传一张图片作为分类图片！');
		}
		$temp_uniacid= !empty($_GPC['uniacid']) ? $_GPC['uniacid'] : $_W['uniacid'];//修复编辑分类时前台未传输uniacid的情况
		$data = array(
			'uniacid' => $temp_uniacid,
			'name' => $_GPC['catename'],
			'enabled' => intval($_GPC['enabled']),
			'displayorder' => intval($_GPC['displayorder']),
			'isrecommand' => intval($_GPC['isrecommand']),
			'commission' => intval($_GPC['commission']),
			'description' => $_GPC['description'],
			'setfor' => $_GPC['setfor'],
			'psn' => intval($_GPC['psn']),
			'thumb' => $_GPC['thumb'],
			'deleted'=>0
			);
			$data['createtime']=TIMESTAMP;
			//$data['sn']=(int) date('ymdHis',TIMESTAMP).mt_rand(1,99);//生成日期+2位随机数的字符串后，强制转换为int,理由上1秒内允许99个请求保存
			pdo_insert('fm453_shopping_category', $data);
			$id = pdo_insertid();
			pdo_update('fm453_shopping_category', array('sn'=>$id), array('id' => $id));
			$dologs=array(
				'url'=>$_W['siteurl'],
				'description'=>'后台插入新分类；',
			);
			fmMod_log_record($platid,$_W['uniacid'],$_W['uid'],$fanid,$openid,'fm453_shopping_category',$id,'modify',$dologs);
			unset($dologs);
			message('添加分类成功！', fm_wurl($do,$ac,'modify', array('id'=>$id,'psn'=>$psn)), 'success');
		}
	//包含的模板文件
	include $this->template($fm453style.$do.'/453');
}
elseif($operation=='export'){
	$haslimit=!empty($_GPC['haslimit']) ? $_GPC['haslimit'] : 'page';
	if($haslimit=='all') {
		$category = pdo_fetchall("SELECT * FROM " . tablename('fm453_shopping_category') . " WHERE ".$condition.$showorder);
	}elseif($haslimit=='page') {
		$category = pdo_fetchall("SELECT * FROM " . tablename('fm453_shopping_category') . " WHERE ".$condition.$showorder.$limit);
	}
	foreach ($category as $index => &$row) {
		$code='code'.$row['statuscode'];
		$row['statusname']=$catestatus[$code]['name'];
		$row['plataccount']=uni_account_default($row['uniacid']);
		if($row['uniacid']==0) {
			$row['plataccount']['name']="系统平台";
		}
		if(empty($row['plataccount'])) {
			$row['plataccount']['name']="已经删除";
		}
		if ($row['psn']>0) {
			foreach($children[$row['psn']] as &$child){
				$child['statusname']=$catestatus[$code]['name'];
				$child['plataccount']=uni_account_default($child['uniacid']);
			}
			unset($category[$index]);
		}
		unset($index);
		unset($code);
	}
	unset($row);

	//导出数据START
		if ($_GPC['export']) {
			/* 输入到CSV文件 */
			$html = "\xEF\xBB\xBF";
				/* 输出表头 */
			$filter = array(
				'sn' => '分类编号',
				'psn' => '上级分类编号',
				'name' => '分类名称',
				'statusname'=>'状态',
				'statuscode'=>'状态码',
				'displayorder'=>'显示顺序',
				'setfor'=>'适用范围',
				'setforname'=>'适用对象',
				'thumb'=>'缩略图',
				'isrecommand'=>'是否推荐',
				'description'=>'描述',
				'uniacid' => '平台ID',
				'platname' => '平台',
			);
			foreach ($filter as $key => $title) {
				$html .= $title . "\t,";
			}
				$html .= "\n";
				foreach ($category as $k => $v) {
					foreach ($filter as $key => $title) {
							if($key=="status") {
								$html .=$catestatus['code'.$v['statuscode']]['name']."\t, ";
							}elseif($key=="setforname") {
								$html .=$catetype[$v['setfor']]['name']."\t, ";
							}elseif($key=="platname") {
								$default=uni_account_default($v['uniacid']);
								$html .=$default['name']."\t, ";
							}else {
								$html .= $v[$key] . "\t, ";
							}
					}
					$html .= "\n";
				}
				/* 输出CSV文件 */
				header("Content-type:text/csv");
				header("Content-Disposition:attachment; filename=".$shopname."分类数据.csv");
				echo $html;

				$dologs=array(
				'url'=>$_W['siteurl'],
				'description'=>'后台导出分类数据；',
				);
				fmMod_log_record($platid,$_W['uniacid'],$_W['uid'],$fanid,$openid,'fm453_shopping_category',$id,'export',$dologs);
				unset($dologs);
				//给管理员发个微信消息
				fmMod_notice($settings[manageropenids],array(
				'header'=>array('title'=>'事件通知','value'=>'后台导出分类数据'),
				'user'=>array('title'=>'操作人','value'=>$_W['username'].'('.$_W['uid'].')'),
				'url'=>array('title'=>'执行网址','value'=>$_W['siteurl']),
				));
				exit();
				}
//导出数据END
}
elseif($operation=='import'){
	return message('功能暂未开放！','referer','info');
}