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
 * @remark 微餐饮菜品管理；
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
	// die(message('非法访问，请通过有效路径进入！'));
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



$category = pdo_fetchall("SELECT * FROM ".tablename('fm453_vfoods_category')." WHERE uniacid ='{$_W['uniacid']}' ORDER BY psn ASC, displayorder DESC", array(), 'id');
if (!empty($category)) {
	$children = array();
	foreach ($category as $cid => $cate) {
		if (!empty($cate['psn'])) {
			$children[$cate['psn']][$cate['id']] = array($cate['id'], $cate['title']);
		}
	}
}

$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'post') {
	$id = intval($_GPC['id']);
	if (!empty($id)) {
		$item = pdo_fetch("SELECT * FROM ".tablename('fm453_vfoods_foods')." WHERE id = :id" , array(':id' => $id));

		if (empty($item)) {
			message('抱歉，菜品不存在或是已经删除！', '', 'error');
		}
	}

	if (checksubmit('submit')) {
		if (empty($_GPC['title'])) {
			message('请输入菜品名称！');
		}
		if (empty($_GPC['pcate'])) {
			message('请选择店铺及菜系！');
		}
		$data = array(
			'uniacid' => intval($_W['uniacid']),
			'title' => $_GPC['title'],
			'pcate' => intval($_GPC['pcate']),
			'ccate' => intval($_GPC['ccate']),
			'status' => intval($_GPC['status']),
			'ishot' => intval($_GPC['ishot']),
			'preprice' => $_GPC['preprice'],
			'oriprice' => $_GPC['oriprice'],
			'hits' => intval($_GPC['hits']),
			'stock' => intval($_GPC['stock']),
			'overbook' => intval($_GPC['overbook']),
			'unit' => $_GPC['unit'],
			'thumb' => $_GPC['thumb'],
			'createtime' => TIMESTAMP,
		);
		if (!empty($_FILES['thumb']['tmp_name'])) {
			file_delete($_GPC['thumb_old']);
			$upload = file_upload($_FILES['thumb']);
			if (is_error($upload)) {
				message($upload['message'], '', 'error');
			}
			$data['thumb'] = $upload['path'];
		}
		if (empty($id)) {
			pdo_insert('fm453_vfoods_foods', $data);
		} else {
			unset($data['createtime']);
			pdo_update('fm453_vfoods_foods', $data, array('id' => $id));
		}
		message('菜品更新成功！', fm_wurl($do,$ac,'', array()), 'success');
	}
} else if ($operation == 'display') {
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$condition = '';
	if (!empty($_GPC['keyword'])) {
		$condition .= " AND title LIKE '%{$_GPC['keyword']}%'";
	}

	if (!empty($_GPC['cate_2'])) {
		$cid = intval($_GPC['cate_2']);
		$condition .= " AND ccate = '{$cid}'";
	} elseif (!empty($_GPC['cate_1'])) {
		$cid = intval($_GPC['cate_1']);
		$condition .= " AND pcate = '{$cid}'";
	}

	if (isset($_GPC['status'])) {
		$condition .= " AND status = '".intval($_GPC['status'])."'";
	}

	$list = pdo_fetchall("SELECT * FROM ".tablename('fm453_vfoods_foods')." WHERE uniacid ='{$_W['uniacid']}' $condition ORDER BY status DESC, ishot DESC, hits DESC, id DESC LIMIT ".($pindex - 1) * $psize.','.$psize);
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fm453_vfoods_foods') . " WHERE uniacid ='{$_W['uniacid']}'");
	$pager = pagination($total, $pindex, $psize);
} elseif ($operation == 'delete') {
	$id = intval($_GPC['id']);
	$row = pdo_fetch("SELECT id, thumb FROM ".tablename('fm453_vfoods_foods')." WHERE id = :id", array(':id' => $id));
	if (empty($row)) {
		message('抱歉，菜品不存在或是已经被删除！');
	}
	if (!empty($row['thumb'])) {
		file_delete($row['thumb']);
	}
	pdo_delete('fm453_vfoods_foods', array('id' => $id));
	message('删除成功！', referer(), 'success');
}
include $this->template($fm453style.$do.'/453');