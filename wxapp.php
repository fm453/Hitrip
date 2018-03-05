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
 * @remark 小程序入口文件
 */
defined('IN_IA') or exit('Access Denied');
$_FM_NAME = array_reverse(explode(DIRECTORY_SEPARATOR,dirname(__FILE__)));
$_FM_NAME = $_FM_NAME[0];
define('FM_NAME',$_FM_NAME);
require IA_ROOT."/addons/". FM_NAME ."/version.php";
require IA_ROOT."/addons/". FM_NAME ."/core/defines.php";
require IA_ROOT."/addons/". FM_NAME ."/core/fmloader.php";

fm_load()->fm_func('fm'); //内置专用函数库
fm_load()->fm_func('url'); //链接路由处理
fm_load()->fm_func('qrcode'); //二维码处理
fm_load()->fm_model('setting'); //内置模块配置模块
fm_load()->fm_model('log'); //日志模块
fm_load()->fm_func('ui');//页面视图
fm_load()->fm_func('mobile'); 	//手机号处理
fm_load()->fm_func('bankcard'); 	//银行卡处理
fm_load()->fm_func('pay');	//支付处理
fm_load()->fm_func('wxapp');	//小程序处理
fm_load()->fm_func('api');	//云数据接口管理

class Fm453_immsModuleWxapp extends WeModuleWxapp {
	//分解do***的应用入口方式
	public function __call($name, $arguments){
		global $_GPC;
		global $_W;
		global $_FM;
		$controller = 'index';
		$action ='index';

		if (empty($_GPC['do'])) {
			$_GPC['do']= 'index';
		}else{
			$controller =$_GPC['do'];
		}
		if (empty($_GPC['ac'])) {
			$_GPC['ac']= 'index';
		}else{
			$action =$_GPC['ac'];
		}

		$return = array();

		//路由准备
		$isWxapp = stripos($name, 'doPage') === 0;
		$_FM['isWxapp'] = $isWxapp;

		if(!$isWxapp) {
			$errno = -1;
			$return['message'] ="入口链接出错";
			$return['data'] = array();
			$return['error'] = $errno;
			return $return;
		}

		$settings = fmMod_settings_all();//全局加载配置
		$_FM['settings']=$settings;

		//取与DAIMS通讯的AccessToken
		global $ApiAccessToken;
		$ApiAccessToken = fmFunc_api_AccessToken();

		//进行路由处理
		if($isWxapp) {
				$isAdmin = stripos($name, 'doPageAdmin') === 0;	//核销端
				if(strtolower($controller)=='admin'){
					$isAdmin = true;
				}
				if ($isAdmin){
					//核销端必需登陆
					$model_name = strtolower(substr($name, 11));
					$app_name = 'appadmin';
					if(empty($model_name)){
						$model_name = $action;
						$action = $_GPC['op'];
					}

					$file=  FM_CORE.$app_name.'/'.$model_name.'/'.$action.'.php';

					//核销端强制要求登陆
					$settings['force_login']=TRUE;
				}else{
					$model_name = strtolower(substr($name, 6));
					$app_name = 'wxapp';
					$file=  FM_CORE.$app_name.'/'.$model_name.'/'.$action.'.php';
				}

			if (!file_exists($file)){
				if(FM_DEBUG){
					$errno = -1;
					$return['message'] ='您访问的'.$model_name.'/'.$action.'链接不存在，请通知商家技术人员检查修复……';
					$return['data'] = array();
					$return['error'] = $errno;
					die(json_encode($return));
				}else{
					return;
				}
			}
			require_once $file;	// 文件引用放在最后，以继续上面的数据与逻辑
		}else{
			if(FM_DEBUG){
				$errno = -1;
					$return['message'] ='您访问的'.$model_name.'/'.$action.'链接不存在，请联系管理员……';
					$return['data'] = array();
					$return['error'] = $errno;
					die(json_encode($return));

			}else{
				return error('-1','访问的'.$name.'链接 不存在,请联系管理员');
			}
		}
	}

	public function payResult($pay_result) {
		if ($pay_result['result'] == 'success') {
			//此处会处理一些支付成功的业务代码

		}
		return true;
	}

}
