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
 * @remark 自助升级
 */
defined('IN_IA') or exit('Access Denied');
global $_W, $_GPC,$_FM;

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
$_W['page']['title'] = $shopname.'自助升级帮助文档';

if($operation=='display') {
	$tablepre =!empty($settings['tablepre']) ? $settings['tablepre'] :"ims_";
	fmMod_setting_entry_check();//检测并修复入口（前台关键词触发入口、后台业务功能菜单）
	$now_version = fmMod_setting_version();

	include $this->template('modset/upgrade');

}elseif($operation=='modify') {

	if (checksubmit('addentry_code_1')) {
		include_once MODULE_ROOT.'/resource/uphelpsql/addentry_code_1.php';
		pdo_run($sql);
		message('添加分类入口成功！', url('home/welcome/ext',array('m'=>'fm453_shopping')), 'success');
	}

	if (checksubmit('addentry_code_2')) {
		include_once MODULE_ROOT.'/resource/uphelpsql/addentry_code_2.php';
		pdo_run($sql);
		message('添加专题入口成功！', url('home/welcome/ext',array('m'=>'fm453_shopping')), 'success');
	}

	if (checksubmit('addentry_code_3')) {
		include_once MODULE_ROOT.'/resource/uphelpsql/addentry_code_3.php';
		pdo_run($sql);
		message('添加订单管理入口成功！', url('home/welcome/ext',array('m'=>'fm453_shopping')), 'success');
	}

	if (checksubmit('emptyentry_code')) {
		$sql="DELETE FROM ".tablename('modules_bindings')." WHERE `module` LIKE '%fm453_shopping%' AND `entry` LIKE '%cover%'  ;
	";
		pdo_run($sql);
		message('清空入口成功！', url('home/welcome/ext',array('m'=>'fm453_shopping')), 'success');
	}

	if (checksubmit('addentry_code_last')) {
	 //include_once MODULE_ROOT.'/resource/uphelpsql/addentry_code_last.php';
		//pdo_run($sql);
		message('修复业务功能菜单成功！', url('home/welcome/ext',array('m'=>'fm453_shopping')), 'success');
	}

	if (checksubmit('add_gdmopenids')) {
		fm_load()->fm_model('goods'); //内置模块配置模块
		$result=fmMod_goods_openids($platid=0,$openids=$_GPC['gdmopenids'],$filed='goodadm',$type='add');
		message('批量添加产品专员成功！', referer(), 'success');
	}
	if (checksubmit('del_gdmopenids')) {
		fm_load()->fm_model('goods'); //内置模块配置模块
		$result=fmMod_goods_openids($platid=0,$openids=$_GPC['gdmopenids'],$filed='goodadm',$type='del');
		message('批量删除产品专员成功！', referer(), 'success');
	}

	if (checksubmit('clear_module_save')) {
		$data=array();
		$result=$this->saveSettings($data);
		//写入操作日志START
		$dologs=array(
			'url'=>$_W['siteurl'],
			'description'=>'清空模块内置配置',
			'addons'=>$data
		);
		fmMod_log_record($platid,$_W['uniacid'],$_W['uid'],$fanid,$openid,'',$id,'modify',$dologs);
		unset($dologs);
		//写入操作日志END
		message('模块设置数据已经清除，现在可重新设置保存！', referer(), 'success');
	}

	if (checksubmit('sudo')) {
		include_once  'core/web/sudo.php';
	}

	if(checksubmit('diy_version')){
		$version = $_GPC['new_version'];
		fmMod_setting_version($version);
		//写入操作日志START
		$dologs=array(
			'url'=>$_W['siteurl'],
			'description'=>'自定义模块版本',
			'addons'=>$version
		);
		fmMod_log_record($platid,$_W['uniacid'],$_W['uid'],$fanid,$openid,'',$id,'modify',$dologs);
		unset($dologs);
		//写入操作日志END
		message('模块版本已重新定义，请检查！', referer(), 'success');
	}

}
