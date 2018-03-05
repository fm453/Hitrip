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
 * @remark 广告位管理；
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
$shopname=$settings['brands']['shopname'];
$shopname = !empty($shopname) ? $shopname :FM_NAME_CN;
$_W['page']['title'] = $shopname.$routes[$do]['title'];
$direct_url=fm_wurl($do,$ac,$operation,'');
$pindex=max(1,intval($_GPC['page']));
$psize=(intval($_GPC['psize'])>5) ? intval($_GPC['psize']) : 5;//最少显示5条主数据

$uniacid=$_W['uniacid'];
$plattype=$settings['plattype'];
$platids = fmFunc_getPlatids();//获取平台模式关联的公众号商户ID参数
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

$showorder=" ORDER BY uniacid ASC, parentid ASC, displayorder DESC ";

if ($operation == 'display') {
		$children = array();
		$sql = "SELECT * FROM " . tablename('fm453_shopping_adboxes') .$condition.$showorder;
		$adboxes = pdo_fetchall($sql,$params);
		foreach ($adboxes as $index => $row) {
			if (!empty($row['parentid'])) {
				$children[$row['parentid']][] = $row;
				unset($adboxes[$index]);
			}
		}

	if (!empty($_GPC['displayorder'])) {
		foreach ($_GPC['displayorder'] as $id => $displayorder) {
			pdo_update('fm453_shopping_adboxes', array('displayorder' => $displayorder), array('id' => $id));
		}
			message('广告位排序更新成功！', fm_wurl($do,$ac,'', array()), 'success');
	}

	include $this->template($fm453style.$do.'/453');
}
elseif ($operation == 'modify') {
	$id = intval($_GPC['id']);
	if (!empty($id)) {
		$adboxes = pdo_fetch("SELECT * FROM " . tablename('fm453_shopping_adboxes') . " WHERE id = ".$id);
		$parentid = (intval($_GPC['parentid'])>0) ? intval($_GPC['parentid']) : intval($adboxes['parentid']);	//是否有上级分类
		$adboxes['time']=array(
			'starttime' => date('Y-m-d H:i:s',$adboxes['starttime']),
			'endtime'   => date('Y-m-d H:i:s',$adboxes['endtime'])
			);
	} else {
		message('请先选择一个广告位再操作！', 'referer', 'info');
	}

	if (!empty($parentid)) {
		$parent = pdo_fetch("SELECT id, name FROM " . tablename('fm453_shopping_adboxes') . " WHERE id = ".$parentid);
    }
	if (checksubmit('submit')) {
		if (empty($_GPC['catename'])) {
   			message('抱歉，请输入广告位名称！');
		}
		$time = $_GPC['adtime'];// 结构为: array('start'=>?, 'end'=>?)
		$starttime = empty($time['start']) ? strtotime('-1 month') : strtotime($time['start']);
		$endtime   = empty($time['end'])   ? TIMESTAMP : strtotime($time['end']) + 86399;
		$data = array(
					'uniacid' => $_W['uniacid'],
					'name' => $_GPC['catename'],
					'forpage' => $_GPC['forpage'],
					'enabled' => intval($_GPC['enabled']),
					'isrecommand' => intval($_GPC['isrecommand']),
					'displayorder' => intval($_GPC['displayorder']),
					'starttime' => $starttime,
					'endtime' => $endtime,
					'description' => $_GPC['description'],
					'parentid' => intval($parentid),
					'thumb' => $_GPC['thumb']
		);
		pdo_update('fm453_shopping_adboxes', $data,array('id'=>$id));
		message('更新广告位成功！', fm_wurl($do,$ac,'modify', array('id'=>$id,'parentid' => $parentid)), 'success');
	}
	//包含的模板文件
	include $this->template($fm453style.$do.'/453');
}
 elseif ($operation == 'copy') {
	$parentid = intval($_GPC['parentid']);
	$id = intval($_GPC['id']);
	if (!empty($id)) {
		$adboxes = pdo_fetch("SELECT * FROM " . tablename('fm453_shopping_adboxes') . " WHERE id = ".$id);
		$adboxes['time']=array(
			'starttime' => date('Y-m-d H:i:s',$adboxes['starttime']),
    		'endtime'   => date('Y-m-d H:i:s',$adboxes['endtime'])
		);
	} else {
		message('请先选择一个广告位再操作！', referer(), 'info');
	}
	if (!empty($parentid)) {
		$parent = pdo_fetch("SELECT id, name FROM " . tablename('fm453_shopping_adboxes') . " WHERE id = ".$parentid);
    }
	if (checksubmit('submit')) {
		if (empty($_GPC['catename'])) {
   			message('抱歉，请输入广告位名称！');
		}
		$time = $_GPC['adtime'];// 结构为: array('start'=>?, 'end'=>?)
		$starttime = empty($time['start']) ? strtotime('-1 month') : strtotime($time['start']);
		$endtime   = empty($time['end'])   ? TIMESTAMP : strtotime($time['end']) + 86399;
		$data = array(
					'uniacid' => $_W['uniacid'],
					'name' => $_GPC['catename'],
					'forpage' => $_GPC['forpage'],
					'enabled' => intval($_GPC['enabled']),
					'isrecommand' => intval($_GPC['isrecommand']),
					'displayorder' => intval($_GPC['displayorder']),
					'starttime' => $starttime,
					'endtime' => $endtime,
					'description' => $_GPC['description'],
					'parentid' => intval($parentid),
					'thumb' => $_GPC['thumb']
		);
		pdo_insert('fm453_shopping_adboxes', $data);
		$id = pdo_insertid();
		message('新增广告位成功！', fm_wurl($do,$ac,'modify', array('id'=>$id,'parentid' => $parentid)), 'success');
	}
	//包含的模板文件
	include $this->template($fm453style.$do.'/453');
}
elseif ($operation == 'add') {
	unset($adboxes);
	$parentid = intval($_GPC['parentid']);
	$adboxes = array(
		'uniacid' => $_W['uniacid'],
		'displayorder' => 0
	);

	if (!empty($parentid)) {
		$parent = pdo_fetch("SELECT id, name FROM " . tablename('fm453_shopping_adboxes') . " WHERE id = '$parentid'");
		if (empty($parent)) {
			message('抱歉，上级广告位不存在或是已经被删除！','referer', 'info');
   		}
    }
	if (checksubmit('submit')) {
		if (empty($_GPC['catename'])) {
   			message('抱歉，请输入广告位名称！');
		}
		$time = $_GPC['adtime'];// 结构为: array('start'=>?, 'end'=>?)
		$starttime = empty($time['start']) ? strtotime('-1 month') : strtotime($time['start']);
		$endtime   = empty($time['end'])   ? TIMESTAMP : strtotime($time['end']) + 86399;
		$data = array(
					'uniacid' => $_W['uniacid'],
					'name' => $_GPC['catename'],
					'forpage' => $_GPC['forpage'],
					'enabled' => intval($_GPC['enabled']),
					'isrecommand' => intval($_GPC['isrecommand']),
					'displayorder' => intval($_GPC['displayorder']),
					'starttime' => $starttime,
					'endtime' => $endtime,
					'description' => $_GPC['description'],
					'parentid' => $parentid,
					'thumb' => $_GPC['thumb']
		);

		pdo_insert('fm453_shopping_adboxes', $data);
		$id = pdo_insertid();
		message('添加广告位成功！', fm_wurl($do,$ac,'modify', array('id'=>$id,'parentid' => $parentid)), 'success');
			}
			//包含的模板文件
			include $this->template($fm453style.$do.'/453');
}
elseif($operation=='export'){
	return message('功能暂未开放！','referer','info');
}
elseif($operation=='import'){
	return message('功能暂未开放！','referer','info');
}