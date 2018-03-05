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
 * @remark 微餐饮打印机管理；
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;

load()->func('tpl');
load()->model('account');//加载公众号函数
fm_load()->fm_func('route'); //获取路径函数

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

fm_load()->fm_func('foods'); //获取微餐饮函数

if ($operation == 'display') {
	$print = pdo_fetchall("SELECT * FROM ".tablename('fm453_vfoods_print')." WHERE uniacid ='{$_W['uniacid']}'");
	for($i=0;$i<count($print);$i++){
		$print[$i][0] = pdo_fetch("SELECT title FROM ".tablename('fm453_vfoods_category')." WHERE id = '{$print[$i]['cateid']}'");
	}
}
elseif ($operation == 'status') {
	fm_load()->fm_class('FeiEYun.Print'); //获取飞鹅云打印机类
	$id = intval($_GPC['id']);
	if(!empty($id)) {
		$print = pdo_fetch("SELECT * FROM ".tablename('fm453_vfoods_print')." WHERE id = '$id'");
		$print['title'] = pdo_fetchcolumn("SELECT title FROM ".tablename('fm453_vfoods_category')." WHERE id = '{$print['cateid']}'");
		$deviceno = $print['deviceno'];	//设备编码
		$deviceKey = $print['key'];	//设备密钥
		if(!empty($_GPC['time'])) {
			$result = fmFunc_foods_queryOrderNumbersByTime($deviceno,$_GPC['time']);
			$print['printedNumber'] = $result['printedNumber'];
			$print['waitingNumber'] = $result['waitingNumber'];
		}
		$print['status'] = fmFunc_foods_queryPrinterStatus($deviceno);
	}
}
elseif ($operation == 'post') {
	$id = intval($_GPC['id']);
	$category = pdo_fetchall("SELECT id,title FROM ".tablename('fm453_vfoods_category')." WHERE uniacid ='{$_W['uniacid']}' AND psn = 0");
	if(!empty($id)) {
		$print = pdo_fetch("SELECT * FROM ".tablename('fm453_vfoods_print')." WHERE id = '$id'");
	}
	if (checksubmit('submit')) {
		if (empty($_GPC['deviceno']) || empty($_GPC['key']) || empty($_GPC['printtime'])) {
			message('抱歉，请正确输入打印机机器号、打印机key、打印联数！');
		}
		$data = array(
			'uniacid' => $_W['uniacid'],
			'cateid' => $_GPC['cateid'],
			'deviceno' => $_GPC['deviceno'],
			'key' => $_GPC['key'],
			'printtime' => $_GPC['printtime'],
			'qr' => $_GPC['qr'],
			'enabled' => $_GPC['enabled'],

		);
		if (!empty($id)) {
			pdo_update('fm453_vfoods_print', $data, array('id' => $id));
		} else {
			pdo_insert('fm453_vfoods_print', $data);
			$id = pdo_insertid();
		}
		message('更新打印机成功！', fm_wurl($do,$ac,'', array('id'=>$id)), 'success');
	}
} elseif ($operation == 'delete') {
	$id = intval($_GPC['id']);
	$print = pdo_fetch("SELECT * FROM ".tablename('fm453_vfoods_print')." WHERE id = '$id'");
	if (empty($print)) {
		message('抱歉，打印机不存在或是已经被删除！', fm_wurl($do,$ac,'', array()), 'error');
	}
	pdo_delete('fm453_vfoods_print', array('id' => $id));
	message('打印机删除成功！', fm_wurl($do,$ac,'', array()), 'success');
}
include $this->template($fm453style.$do.'/453');