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
 * @remark 自提管理；
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
checklogin();  //检测客户端登陆状态
load()->func('tpl');
load()->model('account');//加载公众号函数
fm_load()->fm_func('qrcode'); //二维码处理
fm_load()->fm_model('category'); //分类管理模块
//加载模块配置参数
$settings=fmMod_settings_all();
//加载风格模板及资源路径
$fm453style = empty($settings['shopstyle']) ? FM_SHOPSTYLE : $settings['shopstyle'];
$fm453resource = FM_RESOURCE;
//入口判断
$routes=fmFunc_route_web();
$routes_do=fmFunc_route_web_do();
$do= $_GPC['do'];
$ac=$_GPC['ac'];
$all_ac=fmFunc_route_web_ac($do);
//print_r($all_ac);
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$all_op=fmFunc_route_web_op_single($do,$ac);
//print_r($all_op);
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

//引入快递品牌列表
$expresses = array();
include_once FM_PUBLIC.'express.php';

if ($operation == 'display') {

	include $this->template($fm453style.$do.'/453');
}
