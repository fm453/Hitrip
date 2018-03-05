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
 * @remark 微擎模块主入口文件
 */
defined('IN_IA') or exit('Access Denied');
$_FM_NAME = 'fm453_imms';
define('FM_NAME',$_FM_NAME);
require IA_ROOT."/addons/". FM_NAME ."/version.php";
require IA_ROOT."/addons/". FM_NAME ."/core/defines.php";
require IA_ROOT."/addons/". FM_NAME ."/core/fmloader.php";
fm_load()->fm_func('fm'); //内置专用函数库
fm_load()->fm_func('url'); //链接路由处理
fm_load()->fm_model('setting'); //内置模块配置模块
fm_load()->fm_model('log'); //日志模块
fm_load()->fm_model('notice');//消息通知模块
fm_load()->fm_func('msg');//消息通知前置函数
fm_load()->fm_func('fans'); //粉丝处理函数库
fm_load()->fm_model('member');//会员管理模块
fm_load()->fm_func('qrcode'); //二维码处理
fm_load()->fm_func('data');//统一数据处理方法
fm_load()->fm_func('wechat');//微信定义管理
fm_load()->fm_func('mobile'); 	//手机号处理
fm_load()->fm_func('bankcard'); 	//银行卡处理
fm_load()->fm_func('share');//链接分享管理
fm_load()->fm_func('ui');//页面视图
fm_load()->fm_func('tpl');//页面代码块
fm_load()->fm_func('template');//页面模板调用
fm_load()->fm_func('api');	//云数据接口管理

class Fm453_immsModuleSite extends WeModuleSite {
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

		if(intval($_GPC['i'])<=0 && !$_W['uniacid']) {
			$_W['uniacid']=1;
			$_GPC['i']=1;
		}

		//路由准备
		$isWeb = stripos($name, 'doWeb') === 0;
		$_FM['isWeb'] = $isWeb;
		$isMobile = stripos($name, 'doMobile') === 0;
		$_FM['isMobile'] = $isMobile;

		$settings = array();
		//进行路由处理
		if($isWeb || $isMobile) {
			if ($isWeb) {
				checklogin();
				if(!$_W['uniacid']){
					message("当前登陆状态已失效，请返回系统重新选择管理公众号");
				}

				fm_load()->fm_func('server'); //授权服务器
				fm_load()->fm_func('tables'); //数据表函数
				fm_load()->fm_model('shopcart'); //购物车模块
				fm_load()->fm_func('market');//营销管理

				$settings = fmMod_settings_all();//全局加载配置
				$_FM['settings']=$settings;

				//取与DAIMS通讯的AccessToken
				global $ApiAccessToken;
				$ApiAccessToken = fmFunc_api_AccessToken();

				require FM_VAR ."web.php";	//全局可变变量
				require FM_VAR ."adminlinks.php";	//后台普通管理员快捷链接
				require FM_VAR ."menulinks.php";	//后台通用顶部菜单栏链接

				fm_load()->fm_func('route'); //获取路径函数
				fm_load()->fm_model('privilege'); //权限控制模块

				$app_name = 'web';
				$model_name = strtolower(substr($name, 5));
				$file= FM_WEB.$model_name.DIRECTORY_SEPARATOR.$action.'.php';
			}else if ($isMobile){
				$settings = fmMod_settings_all($useCache=true);//全局加载配置
				$settings['force_wxAuth']= !isset($settings['force_wxAuth']) ? FALSE : $settings['force_wxAuth'];
				$settings['force_login']= !isset($settings['force_login']) ? TRUE : $settings['force_login'];
				$settings['force_follow']= !isset($settings['force_follow']) ? FALSE : $settings['force_follow'];
				$settings['force_mcInfo']= !isset($settings['force_mcInfo']) ? TRUE : $settings['force_mcInfo'];
				$settings['force_agentInfo']= !isset($settings['force_agentInfo']) ? FALSE : $settings['force_agentInfo'];
				$settings['force_verify'] = !isset($settings['force_verify']) ? FALSE : $settings['force_verify'];

				$isAppWeb = stripos($name, 'doMobileAppweb') === 0;
				if(strtolower($controller)=='appweb'){
					$isAppWeb = true;
				}

				if ($isAppWeb){
					//核销端必需登陆、必须关注
					$app_name = 'appweb';
					$model_name = strtolower(substr($name, 14));
					if(empty($model_name)){
						$model_name = $action;
						$action = $_GPC['op'];
					}
					$file=  FM_CORE.'appweb'.DIRECTORY_SEPARATOR.$model_name.DIRECTORY_SEPARATOR.$action.'.php';

					//核销端强制要求登陆
					$settings['force_login']=TRUE;
					//核销端强制要求关注
					$settings['force_follow']=TRUE;
				}else{
					$app_name = 'app';
					$model_name = strtolower(substr($name, 8));
					$file=  FM_APP.$model_name.DIRECTORY_SEPARATOR.$action.'.php';

					//优先处理级别的入口
					switch ($controller) {
						case 'crontab':
							//定时任务入口
							$settings['force_wxAuth']=FALSE;
							$settings['force_login']=FALSE;
							$settings['force_follow']=FALSE;
							$settings['force_mcInfo']=FALSE;	//强制取微擎系统会员信息
							$settings['force_agentInfo']=FALSE;	//强制取本模块内置会员信息
							$settings['force_verify']=FALSE;	//强制要求会员通过认证
							$file=  FM_CORE.'app'.DIRECTORY_SEPARATOR.'crontab.php';
							break;

						case 'api':
						case 'ajax':
							$settings['force_wxAuth']=FALSE;
							$settings['force_login']=FALSE;
							$settings['force_follow']=FALSE;
							$settings['force_mcInfo']=FALSE;	//强制取微擎系统会员信息
							$settings['force_agentInfo']=FALSE;	//强制取本模块内置会员信息
							$settings['force_verify']=FALSE;	//强制要求会员通过认证
							break;

						case 'followus':
							//关注引导入口，禁止判断关注
							$settings['force_wxAuth']=FALSE;
							$settings['force_login']=FALSE;
							$settings['force_follow']=FALSE;
							$settings['force_mcInfo']=FALSE;	//强制取微擎系统会员信息
							$settings['force_agentInfo']=FALSE;	//强制取本模块内置会员信息
							$settings['force_verify']=FALSE;	//强制要求会员通过认证
							$file=  FM_CORE.'app'.DIRECTORY_SEPARATOR.'index.php';
							break;

						//以下正常处理
						/*
							全局判断逻辑：网页授权》登陆》关注
							@网页授权：只要有授权公众号即可发起（非认证服务号会报错）
							@登陆：要求判断是否注册会员
							@关注：查询粉丝关注状态
						*/
						case 'demo':
							//演示入口，站点自行设置
							$file=  FM_CORE.'app'.DIRECTORY_SEPARATOR.'demo'.DIRECTORY_SEPARATOR.'index.php';
							break;

						case 'feedback':
						case 'error':
						case 'help':
							//反馈与报错页，默认不作关注登陆判断
							$settings['force_wxAuth']=FALSE;
							$settings['force_login']=FALSE;
							$settings['force_follow']=FALSE;
							$settings['force_verify']=FALSE;	//强制要求会员通过认证
							break;

						case 'chat':
							//在线聊天，必须强制网页授权以获取信息；登陆与关注跟随进一步设置
							$settings['force_wxAuth']=TRUE;
							break;

						case 'realty':
							//微房产
							$settings['force_wxAuth']=FALSE;
							$settings['force_login']=FALSE;
							$settings['force_follow']=FALSE;
							$settings['force_mcInfo']=FALSE;	//强制取微擎系统会员信息
							$settings['force_agentInfo']=FALSE;	//强制取本模块内置会员信息
							$settings['force_verify']=FALSE;	//强制要求会员通过认证
							break;


						default:
							break;
					}

					//是否强制网页授权
					if($settings['force_wxAuth']){
						fmFunc_fans_getAvatar();	//取或设置粉丝头像
					}elseif($settings['force_login'] || $settings['force_verify']){
						checkauth();	//是否强制登陆
					}

					//强制关注判断(非关注的是否跳转引导关注,默认否)
					if($settings['force_follow']) {
						fmFunc_wechat_checkfollow($openid=$_W['openid'],$redirct=null);
					}

				}

				$mpLevel = $_W['uniaccount']['level'];	//公众号级别，普通订阅号1，普通服务号2，认证订阅号3，认证服务号4
				$auth_uniacid = $_FM['settings']['plat']['oauthid'];	//借调公众号ID

				//系统会员表
				$agent_id = NULL;
				if($_W['openid']){
					$agent_id = fmMod_member_check($_W['openid']);	//商城会员表中的会员id();没有时自动注册一个
				}
				$member_id = $_W['member']['uid'];    //WE7会员表的会员id；开启系统自动时此处的uid必然有效

				$_member = $settings['force_mcInfo'] ? fmMod_member_query($member_id) : array('data'=>NULL);//会员身份信息
				$FM_member = $_member['data'];
				unset($_member);
				$_agent = $settings['force_agentInfo'] ? fmMod_member_agent($member_id) : array('data'=>NULL);	//代理身份信息
				$FM_agent = $_agent['data'];
				unset($_agent);

				if($settings['force_verify'] && $settings['ismcverify'] && !$FM_agent['status']){
					fm_error($msgbody=$settings['whymcverify'],$msgtitle='验证提示',$backurl=NULL);
				}

				$_FM['member']['info'] = $FM_member;
				$_FM['member']['agent']=$FM_agent;
				unset($FM_member);
				unset($FM_agent);
			}

			$_FM['settings'] = $settings;

			if (!file_exists($file)){
				if(FM_DEBUG){
					trigger_error('您访问的'.$model_name.DIRECTORY_SEPARATOR.$action.'链接不存在，请通知网站方面检查修复……', E_USER_ERROR);
				}else{
					$url = fm_murl('error','index','index',array('msg[title]'=>'网址有误','msg[body]'=>'您访问的'.$model_name.DIRECTORY_SEPARATOR.$action.'链接不存在，请通知网站方面检查修复……'));
					header("Location: ".$url);
					exit();
				}
			}
			require_once $file;	// 文件引用放在最后，以继承上面的数据与逻辑
		}else{
			if(FM_DEBUG){
				trigger_error('您访问的'.$name.'链接 不存在,请联系管理员…', E_USER_ERROR);
			}else{
				message('访问的'.$name.'链接 不存在,请联系管理员','','info');
			}
			return false;
		}
	}

	//接收支付结果回调通知
	public function payResult($params) {
		fm_load()->fm_func('pay');	//支付后处理
		return FM_CHECK_PAY_RESULT($params);//必须要有return
	}

//至此，主体程序结束
}

?>
