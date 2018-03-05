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
 * @remark 动态输出模板；
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;

load()->func('tpl');
load()->model('account');//加载公众号函数
fm_load()->fm_model('category'); //分类管理模块
fm_load()->fm_func('pinyin');

//加载风格模板及资源路径

$fm453style = fmFunc_ui_shopstyle();
$fm453resource =FM_RESOURCE;
$appstyle = fmFunc_ui_appstyle();

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

$catestatus=fmFunc_status_get('category');
$datatype= fmFunc_data_types();

//引入公用字段集
require FM_TEMPLATE.'mobile/'.$appstyle.$do.'/_fields.php';

$opp = $_GPC['opp'];

if ($operation == 'pcate') {
	switch($opp) {
		case 'areas':
			$k = $_W['timestamp']. random(32);
			$v = array();
			$v['title'] = '';
			$v['key'] = '';
			$v['status'] = 1;
			include $this->template($fm453style.$do.'/'.$ac.'/'.$operation.'/'.$opp);
		break;

		default:
		break;
	}
	return;
}
elseif($operation=='ccate'){
	$s_sn = intval($_GPC['sn']);
	$getData = array();
	$getData['platid'] = $oauthid;
	$getData['uniacid'] = $_W['uniacid'];
	$getData['shopid'] = 0;
	$getData['ac'] = 'model';
	$postUrl = '/index.php?r=realty/detail';

	switch($opp) {
		case 'areas':
			$postData = array();
			$postData['sn'] = $s_sn;
			$result = fmFunc_api_push($postUrl,$postData,$getData);
			if($result){
				$data = $result;
				$item = $data['params'];
			}

			$k = $_W['timestamp']. random(32);
			$v = array();
			$v['title'] = '';
			$v['key'] = '';
			$v['status'] = 1;
			$v['pcate'] = $item['areas']['pcates'][0]['key'];
			include $this->template($fm453style.$do.'/'.$ac.'/'.$operation.'/'.$opp);
		break;

		default:
		break;
	}
	return;
}
elseif($operation=='node'){
	switch($opp) {
		case 'areas':
			$k = random(32);
			$v = array();
			$v['title'] = '';
			$v['key'] = '';
			$v['displayorder'] = 0;
			$v['status'] = 1;
			include $this->template($fm453style.$do.'/'.$ac.'/'.$operation.'/'.$opp);
		break;

		case 'pages':
			$k = random(32);
			$v = array();
			$v['title'] = '';
			$v['sn'] = '';
			$v['status'] = 1;
			include $this->template($fm453style.$do.'/'.$ac.'/'.$operation.'/'.$opp);
		break;

		case 'shortnews':
			$k = random(32);
			$v = array();
			$v['title'] = '';
			$v['content'] = '';
			$v['status'] = 1;
			$v['displayorder'] = '';
			$v['addtime'] = TIMESTAMP;
			include $this->template($fm453style.$do.'/'.$ac.'/'.$operation.'/'.$opp);
		break;

		case 'events':
			$k = random(32);
			$v = array();
			$v['title'] = '';
			$v['status'] = 1;
			$v['displayorder'] = '';
			$v['addtime'] = TIMESTAMP;
			include $this->template($fm453style.$do.'/'.$ac.'/'.$operation.'/'.$opp);
		break;

		default:
		break;
	}
}
elseif($operation=='ajax'){
	switch($opp) {
		case 'pinyin':
			$chars = $_GPC['var'];
			$pinyin = fmFunc_pinyin_get($chars);
			die(json_encode($pinyin));
		break;

		default:
		break;
	}
}
