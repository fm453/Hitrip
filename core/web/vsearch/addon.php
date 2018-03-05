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
fm_load()->fm_func('pinyin');

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

$catestatus=fmFunc_status_get('category');
$datatype= fmFunc_data_types();

$opp = $_GPC['opp'];

if ($operation == 'pcate') {
	switch($opp) {
		case 'category':
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
	$postUrl = '/index.php?r=search/detail';

	switch($opp) {
		case 'category':
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

		case 'vars':
			$k = random(32);
			/*页面变量类型*/
$varTypes = [];
$varTypes['txt'] = ['title'=>'普通文字'];
$varTypes['textarea'] = ['title'=>'富文本'];
$varTypes['pic'] = ['title'=>'单张图片'];
$varTypes['album'] = ['title'=>'相册组图'];
$varTypes['date'] = ['title'=>'日期'];
$varTypes['datetime'] = ['title'=>'日期时间'];
$varTypes['daterange'] = ['title'=>'日期范围'];
$varTypes['link'] = ['title'=>'链接'];
$varTypes['icon'] = ['title'=>'图标'];
$varTypes['color'] = ['title'=>'颜色'];
$varTypes['lbs'] = ['title'=>'地理位置坐标'];
$varTypes['emoji'] = ['title'=>'表情'];
$varTypes['audio'] = ['title'=>'音频'];
$varTypes['video'] = ['title'=>'视频'];

			$v = array();
			$v['title'] = '';
			$v['key'] = '';
			$v['type'] = '';
			$v['status'] = 1;
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
