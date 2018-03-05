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
 * @remark  模块部分功能开关
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;
load()->func('file');

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
$_W['page']['title'] = $shopname.'功能开关设置';

if($operation=='display') {
	if(!isset($settings['onoffs']['isopenning'])) {
		$settings['onoffs']['isopenning']=1;
	}
	$closed_reason=$settings['onoffs']['closed_reason'];
	$closed_reason_old=$settings['onoffs']['closed_reason_old'];
	if($settings['onoffs']['isopenning']==1) {
		unset($settings['onoffs']['closed_reason']);
	}
	$shop_closed_reason=$settings['onoffs']['shop_closed_reason'];
	$shop_closed_reason_old=$settings['onoffs']['shop_closed_reason_old'];
	if($settings['onoffs']['close_shop']==0) {
		unset($settings['onoffs']['shop_closed_reason']);
	}
	$site_closed_reason=$settings['onoffs']['site_closed_reason'];
	$site_closed_reason_old=$settings['onoffs']['site_closed_reason_old'];
	if($settings['onoffs']['close_site']==0) {
		unset($settings['onoffs']['site_closed_reason']);
	}
	include $this->template('modset/onoff');
}
elseif($operation=='modify'){
	if(checksubmit('save')) {
			$onoffs=array();
		$issafe = $_GPC['issafe'];
			$onoffs['issafe']=$issafe;
		$iswebkf = $_GPC['webkf'];
			$onoffs['webkf']=$iswebkf;
		$isappkf = $_GPC['appkf'];
			$onoffs['appkf']=$isappkf;
		$iscomment = $_GPC['comment'];
			$onoffs['comment']=$iscomment;

		$memberAutoContent = $_GPC['memberAutoContent'];
			$onoffs['memberAutoContent']=$memberAutoContent;

		$isappstyle = $_GPC['isappstyle'];
			$onoffs['isappstyle']=$isappstyle;
		$isfm453style = $_GPC['isfm453style'];
			$onoffs['isfm453style']=$isfm453style;
		$isnavmenu = $_GPC['isnavmenu'];
			$onoffs['isnavmenu']=$isnavmenu;
		$isnavs = $_GPC['isnavs'];
			$onoffs['isnavs']=$isnavs;

		$closed_reason = $_GPC['closed_reason'];
		$closed_reason_old = $_GPC['closed_reason_old'];
			$onoffs['closed_reason_old']=$closed_reason_old;
		if($_GPC['isopenning']==0) {
			if(!empty($_GPC['closed_reason'])) {
				$onoffs['isopenning']=0;
				$onoffs['closed_reason']=$closed_reason;
			}else{
				message('您选择了全局维护，请填写维护的原因；维护期间用户将看到您填写的内容','','error');
			}
		}else{
			$onoffs['isopenning']=1;
		}

		$shop_closed_reason = $_GPC['shop_closed_reason'];
		$shop_closed_reason_old = $_GPC['shop_closed_reason_old'];
			$onoffs['shop_closed_reason_old']=$shop_closed_reason_old;
		if($_GPC['close_shop']==1) {
			if(!empty($_GPC['shop_closed_reason'])) {
				$onoffs['close_shop']=1;
				$onoffs['shop_closed_reason']=$shop_closed_reason;
			}else{
				message('您选择了关店，请填写关店的原因；关店期间用户将看到您填写的内容','referer','error');
			}
		}else{
			$onoffs['close_shop']=0;
		}

		$site_closed_reason = $_GPC['site_closed_reason'];
		$site_closed_reason_old = $_GPC['site_closed_reason_old'];
			$onoffs['site_closed_reason_old']=$site_closed_reason_old;
		if($_GPC['close_site']==1) {
			if(!empty($_GPC['site_closed_reason'])) {
				$onoffs['close_site']=1;
				$onoffs['site_closed_reason']=$site_closed_reason;
			}else{
				message('您选择了关闭官网，请填写关闭的原因','referer','error');
			}
		}else{
			$onoffs['close_site']=0;
		}

		$setfor='onoffs';
		$record = array();
		$record['value']=$onoffs;
		$record['status']='127';
		$record=iserializer($record);
		$result=fmMod_setting_save($record, $setfor, $_W['uniacid']);

		if($result['result']) {
			//写入操作日志START
			$dologs=array(
				'url'=>$_W['siteurl'],
				'description'=>'模块开关设置',
				'addons'=>$onoffs,
			);
			fmMod_log_record($platid,$_W['uniacid'],$_W['uid'],$fanid,$openid,'fm453_shopping_settings',$id,'modify',$dologs);
			unset($dologs);
			//写入操作日志END
			message('开关功能设置成功','referer','success');
		}else{
			message('开关功能设置失败，原因：'.$result['msg'],'','error');
		}
	}
}
elseif($operation=='delete'){
	//从数据库删除
	$result=fmMod_setting_delete($_W['uniacid'],'onoffs','');
	if($result['result']){
	//写入操作日志START
		$dologs=array(
			'url'=>$_W['siteurl'],
			'description'=>'删除开关设置',
			'addons'=>'',
		);
		fmMod_log_record($platid,$_W['uniacid'],$_W['uid'],$fanid,$openid,'fm453_shopping_settings',$id,'delete',$dologs);
		unset($dologs);
		//写入操作日志END
		message($shopname.'开关配置信息已经清空！','referer','success');
	}else{
		message('清空失败；提示：'.$result["msg"],'','error');
	}
}