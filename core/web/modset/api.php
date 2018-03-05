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
 * @remark 模块API设置保存（更新方式，非清空覆盖）
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;

load()->func('file');
fm_load()->fm_func('wechat.corp'); //企业微信定义管理

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
$_W['page']['title'] = $shopname.'API接口设置';

if($operation=='display') {
	//畅言接口
	$api_changyan=array();
	$api_changyan['callback_url']     = $_W['siteroot'].'app/'. str_replace('./','',url('entry//api/',array('i'=>$_W['uniacid'],'m'=>FM_NAME,'ac'=>'changyan','op'=>'callback')));//回调地址
	$api_changyan['oss_userinfo_url'] = $_W['siteroot'].'app/'. str_replace('./','',url('entry//api/',array('i'=>$_W['uniacid'],'m'=>FM_NAME,'ac'=>'changyan','op'=>'index')));//OSS获取用户信息接口URL
	$api_changyan['oss_logout_url']   = $_W['siteroot'].'app/'. str_replace('./','',url('entry//api/',array('i'=>$_W['uniacid'],'m'=>FM_NAME,'ac'=>'changyan','op'=>'logout')));//OSS登出地址
	$api_changyan['oss_login_url']    = $_W['siteroot'].'app/'. str_replace('./','',url('entry//api/',array('i'=>$_W['uniacid'],'m'=>FM_NAME,'ac'=>'changyan','op'=>'login')));//OSS登陆地址

	//取DACMS接口测试数据
	$settings['api']['dacms_username'] = isset($settings['api']['dacms_username']) ? $settings['api']['dacms_username'] : $settings['shouquan']['suapi'];
	$settings['api']['dacms_password'] = isset($settings['api']['dacms_password']) ? $settings['api']['dacms_password'] : $settings['shouquan']['susecret'];
	$getData            = array();
	$getData['platid']  = $oauthid;
	$getData['uniacid'] = $_W['uniacid'];
	$getData['shopid']  = 0;
	$getData['test']    = true;
	$postUrl            = '/index.php?r=api/test';
	$postData           = array();
	$api_data_test      = fmFunc_api_push($postUrl,$postData,$getData);

	//企业微信接口
	$api_workweixin=array();
	$api_workweixin['callback_url']=$_W['siteroot'].'app/'. str_replace('./','',url('entry//api/',array('i'=>$_W['uniacid'],'m'=>FM_NAME,'ac'=>'workweixin','op'=>'callback')));//回调地址
	$api_workweixin['app_home']=$_W['siteroot'].'app/'. str_replace('./','',url('entry//workweixin/',array('i'=>$_W['uniacid'],'m'=>FM_NAME,'ac'=>'index')));//工作台应用主页
	$api_workweixin['app_msg_api']=$_W['siteroot'].'app/'. str_replace('./','',url('entry//workweixin/',array('i'=>$_W['uniacid'],'m'=>FM_NAME,'ac'=>'msg')));//消息服务器URL
	include $this->template('modset/api');
}
elseif($operation=='modify'){
	if(checksubmit('save')) {
		$api=array();
		$api['changyan_appId']= trim($_GPC['changyan_appId']);
		$api['changyan_appKey']=trim($_GPC['changyan_appKey']);
		$api['changyan_conf']=trim($_GPC['changyan_conf']);

		$api['dacms_username']= trim($_GPC['dacms_username']);
		$api['dacms_password']=trim($_GPC['dacms_password']);

		$api['workweixin_corpid']= trim($_GPC['workweixin_corpid']);
		$api['workweixin_corpsecret']=trim($_GPC['workweixin_corpsecret']);
		$api['workweixin_agentid']= trim($_GPC['workweixin_agentid']);
		$api['workweixin_app_Token']=trim($_GPC['workweixin_app_Token']);
		$api['workweixin_app_EncodingAESKey']=trim($_GPC['workweixin_app_EncodingAESKey']);

		$api['baidu_map_ak']= trim($_GPC['baidu_map_ak']);

		$api['map_qq_js']= trim($_GPC['map_qq_js']);

		$setfor='api';
		$record = array();
		$record['value']=$api;
		$record['status']='127';
		$record=iserializer($record);
		$result=fmMod_setting_save($record, $setfor, $_W['uniacid']);

		if($result['result']) {
			fmFunc_api_login();
			$dologs=array(
				'url'=>$_W['siteurl'],
				'description'=>'模块API设置',
				'addons'=>$api,
			);
			fmMod_log_record($platid,$_W['uniacid'],$_W['uid'],$fanid,$openid,'fm453_shopping_settings',$id,'modify',$dologs);
			unset($dologs);
			message('API设置成功','referer','success');
		}else{
			message('API设置失败，原因：'.$result['msg'],'','error');
		}
	}
}
elseif($operation=='delete'){
	//从数据库删除
	$result=fmMod_setting_delete($_W['uniacid'],'api','');
	if($result['result']){
	//写入操作日志START
		$dologs=array(
			'url'=>$_W['siteurl'],
			'description'=>'删除API设置',
			'addons'=>'',
		);
		fmMod_log_record($platid,$_W['uniacid'],$_W['uid'],$fanid,$openid,'fm453_shopping_settings',$id,'delete',$dologs);
		unset($dologs);
		//写入操作日志END
		message($shopname.'API配置信息已经清空！','referer','success');
	}else{
		message('清空失败；提示：'.$result["msg"],'','fail');
	}
}