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
 * @remark 文章管理；
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;
message('功能暂未开放！','referer','info');

load()->func('tpl');
load()->model('account');//加载公众号函数
fm_load()->fm_model('category'); //分类管理模块
fm_load()->fm_model('article'); //文章管理模块

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
$gtplstatus=fmFunc_status_get('goodstplparams');

//平台模式处理
include_once FM_PUBLIC.'plat.php';
$supplydians=explode(',',$supplydianids);//字符串转数组
$supplydians=array_filter($supplydians);//数组去空
//print_r($supplydians);

//按平台模式前置筛选条件
$condition=' WHERE ';
$params=array();
include_once FM_PUBLIC.'forsearch.php';
$condition .= ' AND `deleted` = :deleted';
$params[':deleted'] = 0;

//获取分类
$sql = 'SELECT * FROM ' . tablename('fm453_shopping_category') . $condition.' ORDER BY `psn`, `displayorder` DESC, `createtime` ASC';
$category = pdo_fetchall($sql, $params, 'sn');
if (!empty($category)) {
	$parent = $children = array();
	foreach ($category as $cid => $cate) {
		if (!empty($cate['psn'])) {
			$children[$cate['psn']][] = $cate;
		} else {
			$parent[$cate['sn']] = $cate;
		}
	}
}
//POST筛选数据
	$isfront=$_GPC['isfront'];
	if(!empty($isfront)) {
		$condition.=" AND isfront =:isfront ";
		$params[':isfront']=$isfront;
	}

	$isform=$_GPC['isform'];
	if(!empty($isform)) {
		$condition.=" AND isform= :isform ";
		$params[':isform']=$isform;
	}

	$keyword=trim($_GPC['keyword']);//去前后空格
	if(!empty($keyword)){
		$condition.=" AND param LIKE :param ";
		$params[':param']='%'.$keyword.'%';
	}
	$temp_type=trim($_GPC['type']);
	if(!empty($temp_type)){
		$type=$temp_type;
		$condition.=" AND goodstpl LIKE :goodstpl ";
		$params[':goodstpl']='%'.$temp_type.'%';
	}
//abs()函数，取绝对值
	$status='code';
	$temp_status=$_GPC['status'];
	if(!empty($temp_status) & $temp_status!=$status){
		$status=$temp_status;
		$temp_status=$gtplstatus[$temp_status]['value'];
		if($temp_status<0) {
			$temp_abs=abs($temp_status)-1;
		}
		$condition.=" AND statuscode in ({$temp_status},{$temp_abs}) ";
	}

	//print_r($condition);
	$total=pdo_fetchcolumn("SELECT count(id) FROM ". tablename('fm453_shopping_goodstpl') .$condition,$params);
	$pager = pagination($total, $pindex, $psize);
	//获取表字段
	$tablefields_goodstpl=fmFunc_tables_fields('goodstpl');
	//排序与截断
	$showorder=" ORDER BY uniacid ASC,  displayorder DESC ";
	$limit=" LIMIT ".($pindex-1)*$psize.",".$psize;
if ($operation == 'display') {
	unset($goodstpl);
	$fields=implode(',',$tablefields_goodstpl);
	$goodstpl = pdo_fetchall("SELECT ".$fields." FROM " . tablename('fm453_shopping_goodstpl') . $condition.$showorder.$limit,$params);
	foreach ($goodstpl as $index => &$row) {
		$code='code'.$row['statuscode'];
		$row['statusname']=$gtplstatus[$code]['name'];
		$row['plataccount']=uni_account_default($row['uniacid']);
		if($row['uniacid']==0) {
			$row['plataccount']['name']="系统平台";
		}
		if(empty($row['plataccount'])) {
			$row['plataccount']['name']="已经删除";
		}
		unset($index);
		unset($code);
	}
	unset($row);
	if (!empty($_GPC['displayorder']) ||!empty($_GPC['sn'])) {
		if (!empty($_GPC['displayorder'])) {
			foreach ($_GPC['displayorder'] as $id => $displayorder) {
				pdo_update('fm453_shopping_goodstpl', array('displayorder' => $displayorder), array('id' => $id));
			}
			$dologs=array(
				'url'=>$_W['siteurl'],
				'description'=>'后台更新商城产品模型自定义参数排序；',
				'addons'=>$_GPC['displayorder'],
			);
			fmMod_log_record($platid,$_W['uniacid'],$_W['uid'],$fanid,$openid,'fm453_shopping_goodstpl',$id,'update',$dologs);
			unset($dologs);

		}
		if (!empty($_GPC['sn'])) {
			foreach ($_GPC['sn'] as $id => $sn) {
				pdo_update('fm453_shopping_goodstpl', array('sn' => $sn), array('id' => $id));
			}
			$dologs=array(
				'url'=>$_W['siteurl'],
				'description'=>'后台更新商城产品模型自定义参数；',
				'addons'=>$_GPC['sn'],
			);
			fmMod_log_record($platid,$_W['uniacid'],$_W['uid'],$fanid,$openid,'fm453_shopping_goodstpl',$id,'update',$dologs);
			unset($dologs);
		}
		message('排序\编码更新成功！', fm_wurl($do,$ac,$operation,array()), 'success');
	}

//导出数据START
		if ($_GPC['export'] != '') {
			/* 输入到CSV文件 */
			$html = "\xEF\xBB\xBF";
				/* 输出表头 */
			$filter = array(
				'sn' => '记录编号',
				'param' => '参数项',
				'datatype' => '数据类型',
				'value' => '参数项标识',
				'statusname'=>'状态',
				'statuscode'=>'状态码',
				'goodstpl'=>'产品模型标识',
				'goodstplname'=>'产品模型',
				'displayorder'=>'显示顺序',
				'isfront'=>'是否供前台使用',
				'isform'=>'是否表单收集项',
				'isneeded'=>'是否必须项',
				'uniacid' => '平台ID',
				'platname' => '平台'
			);
			foreach ($filter as $key => $title) {
				$html .= $title . "\t,";
			}
				$html .= "\n";
				foreach ($goodstpl as $k => $v) {
					foreach ($filter as $key => $title) {
							if($key=="status") {
								$html .=$gtplstatus['code'.$v['statuscode']]['name']."\t, ";
							}elseif($key=="goodstplname") {
								$html .=$gtpltype[$v['goodstpl']]['name']."\t, ";
							}elseif($key=="platname") {
								$html .=uni_account_default($v['uniacid'])['name']."\t, ";
							}else {
								$html .= $v[$key] . "\t, ";
							}
					}
					$html .= "\n";
				}
				/* 输出CSV文件 */
				header("Content-type:text/csv");
				header("Content-Disposition:attachment; filename=嗨商城产品模型数据.csv");
				echo $html;
				$dologs=array(
				'url'=>$_W['siteurl'],
				'description'=>'后台导出产品模型数据；',
				);
				$this->recordlog($_W['uniacid'],$_W['uniacid'],$_W['uid'],$fanid,$openid,'fm453_shopping_goodstpl',$id,'export',$dologs);
				unset($dologs);
				exit();
				}
//导出数据END
	include $this->template($fm453style.$do.'/453');

} elseif ($operation == 'modify') {
	unset($goodstpl);
	$page=intval($_GPC['pindex']);
	$id = intval($_GPC['id']);
	if (!empty($id)) {
		$goodstpl = pdo_fetch("SELECT * FROM " . tablename('fm453_shopping_goodstpl') . " WHERE id = '{$id}'");
	} else {
		$goodstpl = array(
			'displayorder' => 0,
		);
	}

	if (checksubmit('submit')) {
		if (empty($_GPC['param'])) {
			message('抱歉，请输入参数项名称！');
		}
		if (empty($_GPC['goodstpl'])) {
			message('抱歉，请选择产品模型！');
		}
		if (empty($_GPC['datatype'])) {
			message('抱歉，请选择数据类型！');
		}
		$data = array(
					'uniacid' => $_GPC['uniacid'],
					'param' => $_GPC['param'],
					'displayorder' => intval($_GPC['displayorder']),
					'isneeded' => intval($_GPC['isneeded']),
					'datatype' => $_GPC['datatype'],
					'goodstpl' => $_GPC['goodstpl'],
					'value' => $_GPC['value'],
					'isfront' => $_GPC['isfront'],
					'isform' => $_GPC['isform'],
					'statuscode'=>64,
					'createtime'=>TIMESTAMP
				);
		if (!empty($id)) {
				pdo_update('fm453_shopping_goodstpl', $data, array('id' => $id));
				$dologs=array(
						'url'=>$_W['siteurl'],
						'description'=>'后台更新产品模型参数数据；',
						'addons'=>$data,
				);
				fmMod_log_record($_W['uniacid'],$_W['uniacid'],$_W['uid'],$fanid,$openid,'fm453_shopping_goodstpl',$id,'update',$dologs);
				unset($dologs);
				message('更新产品模型参数成功！', fm_wurl('goods','tpl','modify',array('id'=>$id)), 'success');
			} else {
					pdo_insert('fm453_shopping_goodstpl', $data);
					$id = pdo_insertid();
					pdo_update('fm453_shopping_goodstpl', array('sn'=>$id), array('id' => $id));
					$dologs=array(
						'url'=>$_W['siteurl'],
						'description'=>'后台新增产品模型参数数据；',
					);
					fmMod_log_record($_W['uniacid'],$_W['uniacid'],$_W['uid'],$fanid,$openid,'fm453_shopping_goodstpl',$id,'add',$dologs);
					unset($dologs);
					message('新增产品模型参数成功！', fm_wurl('goods','tpl','modify',array('id'=>$id)), 'success');
				}
			}
	//包含的模板文件
	include $this->template($fm453style.$do.'/453');

} elseif ($operation == 'delete') {
	$page=intval($_GPC['pindex']);
	$id = intval($_GPC['id']);
	$goodstpl = pdo_fetch("SELECT id FROM " . tablename('fm453_shopping_goodstpl') . " WHERE id = '$id'");
	if (empty($goodstpl)) {
		message('抱歉，该模型参数不存在或是已经被删除！', $this->createWebUrl('goodstpls', array('op' => 'display','page'=>$page)), 'error');
	}
	pdo_update("fm453_shopping_goodstpl", array("statuscode" => '-1'), array('id' => $id));//软删除
			$dologs=array(
				'url'=>$_W['siteurl'],
				'description'=>'后台删除产品模型参数；',
				);
				fmMod_log_record($_W['uniacid'],$_W['uniacid'],$_W['uid'],$fanid,$openid,'fm453_shopping_goodstpl',$id,'delete',$dologs);
				unset($dologs);
	message('模型参数删除成功！', 'referer', 'success');
}
