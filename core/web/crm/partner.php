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
 * @remark 合作商户管理；
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;

load()->func('tpl');
load()->model('account');//加载公众号函数
load()->model('mc');
fm_load()->fm_model('partner');
fm_load()->fm_model('category');

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
$psize=(intval($_GPC['psize'])>5) ? intval($_GPC['psize']) : 5;//最少显示5条主数据

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
$catestatus=fmFunc_status_get('category');
$datatype= fmFunc_data_types();

//平台模式处理
include_once FM_PUBLIC.'plat.php';
$supplydians=explode(',',$supplydianids);//字符串转数组
$supplydians=array_filter($supplydians);//数组去空

//按平台模式前置筛选条件
$condition=' WHERE ';
$params=array();
include_once FM_PUBLIC.'forsearch.php';
//$condition .= ' AND `deleted` = :deleted';
//$params[':deleted'] = 0;

//获取分类
$allcategory = fmMod_category_get('partner');
$parent = $child = array();
$parent = $allcategory['parent'];
$child = $allcategory['child'];

$map_api_qq = isset($settings['api']['map_qq_js']) ? $settings['api']['map_qq_js'] : "7ZVBZ-VX76Q-EFL5A-GR2ZD-BGNSF-6ZB7R";

if($operation=='display'){
	$keyword=trim($_GPC['keyword']);
	$partid=trim($_GPC['partid']);

	if($partid){
		$condition.=" AND id = :id ";
		$params[':id']=$partid;
	}else{
		if(!empty($keyword)){
			$condition.=' AND ';
			$condition.=' name LIKE :name ';
			$params[':name']='%'.$keyword.'%';
		}
		$mobile=intval($_GPC['mobile']);
		if(!empty($mobile)){
			$condition.=" AND (";
			$condition.=" mobile1 LIKE :mobile1 ";
			$params[':mobile1']='%'.$mobile.'%';
			$condition.=" OR ";
			$condition.=" mobile2 LIKE :mobile2 ";
			$params[':mobile2']='%'.$mobile.'%';
			$condition.=" OR ";
			$condition.=" tel1 LIKE :tel1 ";
			$params[':tel1']='%'.$mobile.'%';
			$condition.=" OR ";
			$condition.=" tel2 LIKE :tel2 ";
			$params[':tel2']='%'.$mobile.'%';
			$condition.=" )";
		}
	}
	$showorder=" ORDER BY uniacid ASC, displayorder DESC, createtime DESC ";
	$table = 'fm453_shopping_partner';
	$total = pdo_fetchcolumn("SELECT COUNT(id) FROM ".tablename($table).$condition, $params);
	$pager = pagination($total, $pindex, $psize);
	$list = pdo_fetchall("SELECT * FROM ".tablename($table).$condition.$showorder.$limit, $params);

	include $this->template($fm453style.$do.'/453');
}
elseif($operation=='modify'){
	$id=$_GPC['id'];
	$_partner = fmMod_partner_query($id);
	$partner = $_partner['data'];
	$loc_x = $partner['locx'] ? $partner['locx'] : '18.259471';
	$loc_y = $partner['locy'] ? $partner['locy'] : '109.494081';
	if(checksubmit()) {
		$check_fields=fmFunc_tables_fields($name='partner',$dtype='shopping');
		$data=array();
		foreach($check_fields as $field){
			$data[$field]= ($_GPC[$field]) ? $_GPC[$field] : '';
		}

		$data['psn'] = $partner['psn'] ? $partner['psn'] : $data['createtime'];
		$data['displayorder'] = intval($data['displayorder']);
		$data['deleted'] = intval($data['deleted']);
		$category = $_GPC['category'];
		$data['pcate'] = $category['parentid'];
		$data['ccate'] = $category['childid'];
		$data['description'] = htmlspecialchars_decode($data['description']);
		unset($data['id']);
		unset($data['createtime']);

		$table = "fm453_shopping_partner";
		$result = pdo_update($table,$data,array('id'=>$id));
		if($result){
			//写入操作日志START
			$dologs=array(
				'url'=>$_W['siteurl'],
				'description'=>'商城后台商户资料编辑；',
				'addons'=>$data,
			);
			fmMod_log_record($platid,$_W['uniacid'],$_W['uid'],$fanid,$openid,$table,$uid,'modify',$dologs);
			unset($dologs);
			//写入操作日志END
			//给管理员发个微信消息
			fmMod_notice($settings['manageropenids'],array(
				'header'=>array('title'=>'事件通知','value'=>'后台更新商户资料'),
				'operator'=>array('title'=>'操作人','value'=>$_W['username'].'('.$_W['uid'].')'),
				'partner'=>array('title'=>'商户','value'=>$data['name']."(".$data['psn'].")")
			));
			message('商户信息更新成功',fm_wurl($do,$ac,'modify',array('id'=>$id)),'success');
		}
	}

	include $this->template($fm453style.$do.'/453');
}
elseif($operation=='add'){
	$partner = array();
	$partner['uniacid'] = $_W['uniacid'];
	$loc_x = '18.259471';
	$loc_y = '109.494081';
	if(checksubmit()) {
		$check_fields=fmFunc_tables_fields($name='partner',$dtype='shopping');
		$data=array();
		foreach($check_fields as $field){
			$data[$field]= ($_GPC[$field]) ? $_GPC[$field] : '';
		}
		unset($data['id']);
		$data['displayorder'] = intval($data['displayorder']);
		$data['psn'] = TIMESTAMP;
		$data['createtime'] = TIMESTAMP;
		$table = "fm453_shopping_partner";
		// var_dump($data);
		$result = pdo_insert($table,$data);
		if($result){
			$id = pdo_insertid();
			//写入操作日志START
			$dologs=array(
				'url'=>$_W['siteurl'],
				'description'=>'商城后台添加商户；',
				'addons'=>$data,
			);
			fmMod_log_record($platid,$_W['uniacid'],$_W['uid'],$fanid,$openid,$table,$uid,'add',$dologs);
			unset($dologs);
			//写入操作日志END
			//给管理员发个微信消息
			fmMod_notice($settings['manageropenids'],array(
				'header'=>array('title'=>'事件通知','value'=>'后台新增商户'),
				'operator'=>array('title'=>'操作人','value'=>$_W['username'].'('.$_W['uid'].')'),
				'partner'=>array('title'=>'商户','value'=>$data['name']."(".$data['psn'].")")
			));
			message('商户新增成功',fm_wurl($do,$ac,'',array('id'=>$id)),'success');
		}
	}
	include $this->template($fm453style.$do.'/453');
}