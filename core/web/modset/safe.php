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
 * @remark 模块安全参数设置保存（更新方式，非清空后覆盖）
 */
 defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;
load()->func('tpl');
load()->func('file');
load()->model('account');//加载公众号函数

//加载风格模板及资源路径
$fm453style = fmFunc_ui_shopstyle();
$fm453resource =FM_RESOURCE;

//入口判断
$routes=fmFunc_route_web();
$routes_do=fmFunc_route_web_do();
$do=$_GPC['do'];
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
$shopname = !empty($shopname) ? $shopname : FM_NAME_CN;
$_W['page']['title'] = $shopname.'安全设置';

if($operation=='display') {
	include $this->template('modset/safe');
}
elseif($operation=='modify') {
	$Data = $_GPC['data'];
	$data = $Data;

	$ismcverify = intval($_GPC['ismcverify']);
	$data['ismcverify'] = $ismcverify;
	$whymcverify = !trim($_GPC['whymcverify']) ? $shopname."开启了访问限制，您需要通过审核后才能访问。如需了解更多，请联系客服或管理员" : trim($_GPC['whymcverify']);
	$data['whymcverify'] = $whymcverify;

	$record = array();
	$record['value']=$data;
	$record['status']='127';
	$record=iserializer($record);
	$result=fmMod_setting_save($record,'',$_W['uniacid']);
	if($result['result']) {
		//写入操作日志START
		$dologs=array(
			'url'=>$_W['siteurl'],
			'description'=>'安全性设置',
			'addons'=>$data
		);
		fmMod_log_record($platid,$_W['uniacid'],$_W['uid'],$fanid,$openid,'fm453_shopping_settings',$id,'modify',$dologs);
		unset($dologs);
		//写入操作日志END
		message('安全设置保存成功','referer','success');
	}else{
		message('安全设置保存失败,原因：'.$result['msg'],'referer','error');
	}
}