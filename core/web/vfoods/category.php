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
 * @remark 微餐饮分类管理；
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;

load()->func('tpl');
load()->model('account');//加载公众号函数
fm_load()->fm_func('route'); //获取路径函数
fm_load()->fm_model('category'); //分类管理模块

//加载风格模板及资源路径
$fm453style    = fmFunc_ui_shopstyle();
$fm453resource =FM_RESOURCE;

//入口判断
$routes        =fmFunc_route_web();
$routes_do     =fmFunc_route_web_do();
$do            = $_GPC['do'];
$ac            =$_GPC['ac'];
$all_ac        =fmFunc_route_web_ac($do);
$operation     = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$all_op=fmFunc_route_web_op_single($do,$ac);
if(!in_array($ac,$all_ac) || !in_array($operation,$all_op)){
	die(message('非法访问，请通过有效路径进入！'));
}

fmMod_privilege_adm();//检查用户授权

//开始操作管理
$shopname            =$settings['brands']['shopname'];
$shopname            = !empty($shopname) ? $shopname :FM_NAME_CN;
$_W['page']['title'] = $shopname.$routes[$do]['title'];
$direct_url          =fm_wurl($do,$ac,$operation,'');
$pindex              =max(1,intval($_GPC['page']));
$psize               =(intval($_GPC['psize'])>10) ? intval($_GPC['psize']) : 10;//最少显示10条主数据

$uniacid             =$_W['uniacid'];
$plattype            =$settings['plattype'];
$platids             = fmFunc_getPlatids();//获取平台模式关联的公众号商户ID参数
$oauthid             =$platids['oauthid'];
$fendianids          =$platids['fendianids'];
$supplydianids       =$platids['supplydianids'];
$blackids            =$platids['blackids'];

//平台模式处理
include_once FM_PUBLIC.'plat.php';
$supplydians        =explode(',',$supplydianids);//字符串转数组
$supplydians        =array_filter($supplydians);//数组去空

//按平台模式前置筛选条件
$condition          =' WHERE ';
$params             =array();
include_once FM_PUBLIC.'forsearch.php';
$condition          .= ' AND `deleted` = :deleted';
$params[':deleted'] = 0;

$map_api_qq = isset($settings['api']['map_qq_js']) ? $settings['api']['map_qq_js'] : "7ZVBZ-VX76Q-EFL5A-GR2ZD-BGNSF-6ZB7R";

if ($operation == 'display') {
	$children = array();
	$category = pdo_fetchall("SELECT * FROM ".tablename('fm453_vfoods_category')." WHERE uniacid = '{$_W['uniacid']}' ORDER BY psn ASC, displayorder DESC");
	foreach ($category as $index => $row) {
		if (!empty($row['psn'])){
			$children[$row['psn']][] = $row;
			unset($category[$index]);
		}
	}
	include $this->template($fm453style.$do.'/453');
}

elseif ($operation == 'post') {
	$psn = intval($_GPC['psn']);
	$id = intval($_GPC['id']);
	load()->model('mc');
	$groups = mc_groups();
	$shoptype = pdo_fetchall("SELECT * FROM ".tablename('fm453_vfoods_shoptype')." WHERE uniacid = '{$_W['uniacid']}'");
	if(!empty($id)) {
		$category = pdo_fetch("SELECT * FROM ".tablename('fm453_vfoods_category')." WHERE id = '$id'");
	} else {
		$category = array(
			'displayorder' => 0,
		);
	}
	$ptime1 = $category['time1'];
	$ptime2 = $category['time2'];
	$ptime3 = $category['time3'];
	$ptime4 = $category['time4'];
	if (!empty($psn)) {
		$parent = pdo_fetch("SELECT id, title FROM ".tablename('fm453_vfoods_category')." WHERE id = '$psn'");
		if (empty($parent)) {
			message('抱歉，餐厅不存在或是已经被删除！', fm_wurl($do,$ac,'post',array()), 'error');
		}
	}
	$loc_x = isset($category['loc_x']) ? $category['loc_x'] : "18.283036";
	$loc_y = isset($category['loc_y']) ? $category['loc_y'] : "109.569998";
	if (checksubmit('submit')) {
		if (empty($_GPC['catename'])) {
			message('抱歉，请输入名称！');
		}
		$data = array(
			'uniacid' => $_W['uniacid'],
			'title' => $_GPC['catename'],
			'displayorder' => intval($_GPC['displayorder']),
			'iswaimai' => intval($_GPC['iswaimai']),
			'isziqu' => intval($_GPC['isziqu']),
			'istangshi' => intval($_GPC['istangshi']),
			'isdefault' => intval($_GPC['isdefault']),
			'isnodetail' => intval($_GPC['isnodetail']),
			'psn' => intval($psn),
			'shouji' => $_GPC['shouji'],
			'sendprice' => $_GPC['sendprice'],
			'total' => $_GPC['total'],
			'typeid' => $_GPC['typeid'],
			'enabled' => $_GPC['enabled'],
			'description' => $_GPC['description'],
			'email' => $_GPC['email'],
			'time1' => $_GPC['time1'],
			'time2' => $_GPC['time2'],
			'time3' => $_GPC['time3'],
			'time4' => $_GPC['time4'],
			'thumb' => $_GPC['thumb'],
			'address' => $_GPC['address'],
			'loc_x' => $_GPC['loc_x'],
			'loc_y' => $_GPC['loc_y'],
			'mbgroup' => $_GPC['mbgroup'],
			'managers' => trim($_GPC['managers']),

		);
		 if (!empty($_FILES['thumb']['tmp_name'])) {

            file_delete($_GPC['thumb_old']);

            $upload = file_upload($_FILES['thumb']);

            if (is_error($upload)) {

                message($upload['message'], '', 'error');

            }

            $data['thumb'] = $upload['path'];

        }
		if (!empty($id)) {
			unset($data['psn']);
			pdo_update('fm453_vfoods_category', $data, array('id' => $id));
		} else {
			pdo_insert('fm453_vfoods_category', $data);
			$id = pdo_insertid();
		}
		// message('更新成功！', fm_wurl($do,$ac, 'post',array('id' => $id)), 'success');
		message('更新成功！', fm_wurl($do,$ac, 'display',array('id' => $id)), 'success');
	}
	include $this->template($fm453style.$do.'/453');
}
else if ($operation == 'delete') {
	$id = intval($_GPC['id']);
	$category = pdo_fetch("SELECT id, psn FROM ".tablename('fm453_vfoods_category')." WHERE id = '$id'");
	if (empty($category)) {
		message('抱歉，餐厅或菜系不存在或是已经被删除！', fm_wurl($do,$ac,'display', array()), 'error');
	}
	pdo_delete('fm453_vfoods_category', array('id' => $id, 'psn' => $id), 'OR');
	message('删除成功！',fm_wurl($do,$ac, 'display', array()), 'success');
}