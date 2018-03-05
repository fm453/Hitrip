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
 * @remark 模块配置
 */
defined('IN_IA') or exit('Access Denied');
$_FM_NAME = array_reverse(explode(DIRECTORY_SEPARATOR,dirname(__FILE__)));
$_FM_NAME = $_FM_NAME[0];
define('FM_NAME',$_FM_NAME);
require IA_ROOT."/addons/". FM_NAME ."/version.php";
require IA_ROOT."/addons/". FM_NAME ."/core/defines.php";
require IA_ROOT."/addons/". FM_NAME ."/core/fmloader.php";
fm_load()->fm_func('fm'); //内置专用函数库
fm_load()->fm_func('route'); //获取路径函数
fm_load()->fm_func('url'); //链接路由处理
fm_load()->fm_func('server'); //授权服务器
fm_load()->fm_func('fans'); //内置专用函数库
fm_load()->fm_func('wechat');//微信定义管理
fm_load()->fm_func('api');	//云数据接口管理
fm_load()->fm_model('log'); //日志模块
fm_load()->fm_model('setting'); //内置模块配置模块

class Fm453_immsModule extends WeModule {
	public function fieldsFormDisplay($rid = 0) {
		global $_GPC;
		global $_W;
		global $_FM;
		if ($rid) {
			$reply = pdo_fetch("SELECT * FROM " . tablename('fm453_shopping_reply') . " WHERE `uniacid`=:uniacid and `rid` = :rid limit 1", array(':uniacid' => $_W['uniacid'], ':rid' => $rid));
			$sql = 'SELECT `id`,`sn`,`title`,`description`,`thumb`,`xsthumb` FROM ' . tablename('fm453_shopping_goods') . ' WHERE `uniacid`=:uniacid AND `sn`=:sn';
			$goods = pdo_fetch($sql, array(':uniacid' => $_W['uniacid'], ':sn' => $reply['gsn']));
		}
		$setting = $_W['account']['modules'][$this->_saveing_params['mid']]['config'];
		include $this->template('modset/form');
	}

	public function fieldsFormValidate($rid = 0) {
		global $_GPC;
		$sn = intval($_GPC['gsn']);
		$id = intval($_GPC['gid']);
		if ($sn) {
			$sql = 'SELECT * FROM' .tablename('fm453_shopping_goods')." WHERE `sn`=:sn";
			$params = array();
			$params[':sn'] = $sn;
			$goods = pdo_fetch($sql, $params);
			if (!empty($goods)) {
				return '';
			}
		}elseif ($id) {
			$sql = 'SELECT * FROM' .tablename('fm453_shopping_goods')." WHERE `id`=:id";
			$params = array();
			$params[':id'] = $id;
			$goods = pdo_fetch($sql, $params);
			if (!empty($goods)) {
				return '';
			}
		}
		return '';
		//return '没有选择合适的产品或对象';
	}

	public function fieldsFormSubmit($rid) {
		global $_GPC;
		global $_W;
		$sn = intval($_GPC['gsn']);
		$record = array();
		$record['gsn'] = $sn;
		$record['rid'] = $rid;
		$record['uniacid'] = $_W['uniacid'];
		$record['statuscode'] = 1;
		$reply = pdo_fetch("SELECT * FROM " . tablename('fm453_shopping_reply') . " WHERE rid = :rid limit 1", array(':rid' => $rid));
		if ($reply) {
			pdo_update('fm453_shopping_reply', $record, array('id' => $reply['id']));
		} else {
			$record['createtime'] = TIMESTAMP;
			pdo_insert('fm453_shopping_reply', $record);
		}
		return true;
	}

	public function ruleDeleted($rid) {
		$record = array();
		$record['statuscode'] = 2;
		$record['deleted'] = 1;
		pdo_update('fm453_shopping_reply', $record, array('id' => $reply['id']));
	}

	public function settingsDisplay($settings) {
		global $_GPC;
		global $_W;
		load()->func('tpl');
		load()->model('account');//加载公众号函数
		//在打开模块设置时，更新一次模块信息
		$sql="SELECT mid FROM ".tablename('modules')." WHERE `name`= ".FM_NAME;
		$mid =pdo_fetch($sql);
		$shopversion =FM_VERSION;
		$moduleinfo =array();
		$moduleinfo['isrulefields']=1;
		$moduleinfo['version']=$shopversion;	//更新版本
		$moduleinfo['type']='business';
		pdo_update('modules',$moduleinfo,array('mid'=>$mid));//模块信息直接写入系统的模块数据表

		$shopname=($this->module['title']) ? $this->module['title'] : FM_NAME_CN;
		$fm453style =$this->module['config']['shopstyle'];
		if (empty($fm453style)) {
			$fm453style='web/default/';
		}

		$fm453industry =$this->module['config']['industry'];
		if (empty($fm453industry)) {
			$fm453industry='ebiz';
		}
		if($fm453industry=='ebiz') {
			$fm453industry_name='所在行业：IT科技/互联网/电子商务';
		}elseif($fm453industry=='hotel') {
			$fm453industry_name='所在行业：酒店住宿';
		}elseif($fm453industry=='ota') {
			$fm453industry_name='所在行业：旅行社';
		}

		//初始化商城的平台模式
		$plattype=$settings['plattype'];//default,fendian,zongdian,multi,pingtai,os
		if($plattype=='os'){
			$platids='-1';
		}elseif($plattype=='default'){//无上级，无下级
			$platids=array(
				'oauthid'=>$oauthid,
				'fendianids'=>false,
				'supplydianids'=>false,
				'blackids'=>false,
			);
		}else{
			$oauthid= !empty($settings['plat']['oauthid']) ? $settings['plat']['oauthid'] : $_W['uniacid'];
			$fendianids=!empty($settings['plat']['fendianids']) ? $settings['plat']['fendianids'] : $_W['uniacid'];
			$temp_fendianids=explode(",",$fendianids);
			$supplydianids= !empty($settings['plat']['supplydianids']) ? $settings['plat']['supplydianids'] : $_W['uniacid'];
			$temp_supplydianids=explode(",",$supplydianids);
			$blackids= !empty($settings['plat']['blackids']) ? $settings['plat']['blackids'] : 0;
			$temp_blackids=explode(",",$blackids);
			if($plattype=='fendian'){//只有一个上级店铺，无下级
				foreach($temp_supplydianids as $index=>$supplydianid) {
					if(in_array($supplydianid, $temp_blackids) || $supplydianid==$_W['uniacid']) {
						unset($temp_supplydianids[$index]);
					}
				}
				$supplydianids=implode(",",$temp_supplydianids);
				//print_r($supplydianids);
				if(empty($supplydianids)) {
					$this->module['config']['plattype']='default';
					$platids=array(
						'oauthid'=>$oauthid,
						'fendianids'=>false,
						'supplydianids'=>false,
						'blackids'=>false,
					);
				}else{
					$supplydianids=$supplydianids;
					$platids=array(
						'oauthid'=>$oauthid,
						'fendianids'=>false,
						'supplydianids'=>$supplydianids,
						'blackids'=>false,
					);
				}
			}elseif($plattype=='zongdian'){//无上级，有下级
				foreach($temp_fendianids as $index=>$fendianid) {
					if(in_array($fendianid, $temp_blackids)) {
						unset($temp_fendianids[$index]);
					}
				}
				$fendianids=implode(",",$temp_fendianids);
				$platids=array(
					'oauthid'=>$oauthid,
					'fendianids'=>$fendianids,
					'supplydianids'=>false,
					'blackids'=>$blackids,
				);
			}elseif($plattype=='multi'){//多个上级，无下级
				foreach($temp_supplydianids as $index=>$supplydianid) {
					if(in_array($supplydianid, $temp_blackids)) {
						unset($temp_supplydianids[$index]);
					}
				}
				$supplydianids=implode(",",$temp_supplydianids);
				$platids=array(
					'oauthid'=>$oauthid,
					'fendianids'=>false,
					'supplydianids'=>$supplydianids,
					'blackids'=>$blackids,
				);
			}elseif($plattype=='pingtai'){//多个上级，多下级
				foreach($temp_supplydianids as $index=>$supplydianid) {
					if(in_array($supplydianid, $temp_blackids)) {
						unset($temp_supplydianids[$index]);
					}
				}
				$supplydianids=implode(",",$temp_supplydianids);
				foreach($temp_fendianids as $index=>$fendianid) {
					if(in_array($fendianid, $temp_blackids)) {
						unset($temp_fendianids[$index]);
					}
				}
				$fendianids=implode(",",$temp_fendianids);
				$platids=array(
					'oauthid'=>$oauthid,
					'fendianids'=>$fendianids,
					'supplydianids'=>$supplydianids,
					'blackids'=>$blackids,
				);
			}
		}

		//服务器授权通讯开始
		$suip=gethostbyname($_SERVER['HTTP_HOST']);
		$sudomain=$_SERVER['HTTP_HOST'];
		if (!empty($settings['shouquan']['sufm453code'])){
			$isread='readonly="true"';
		}else{
			$isread='';
		}
		$serverinfos = fmFunc_server_check();
		//开始检查
		$hostname="vcms.hiluker.com";
		$hostport="443";
		load()->func('communication');
		load()->model('user');
		$loginuser = user_single(array('uid' => $_W['uid']));
		$postdata=array();
		$postdata['op']='modulecheck';
		$postdata['ip']=$suip;
		$postdata['sudomain']=$sudomain;
		$postdata['siteurl']=$_W['siteurl'];
		$postdata['suapi']=$settings['shouquan']['suapi'];
		$postdata['susecret']=$settings['shouquan']['susecret'];
		$postdata['sufm453code']=$settings['shouquan']['sufm453code'];
		$postdata['uniacid']=$_W['uniacid'];
		$postdata['uniaccount']=$_W['uniaccount'];
		$postdata['uid']=$_W['uid'];
		$postdata['username']=$_W['username'];
		$postdata['loginuser']=$loginuser;
		$postdata['timestamp']=$_W['timestamp'];
		$postUrl='https://vcms.hiluker.com/app/index.php?i=1&c=entry&do=api&ac=getcode&op=modulecheck&m=fm453_shopping';
		$postdata = iserializer($postdata);
		$result=ihttp_post($postUrl,$postdata);
		unset($postdata);
		//检查结束
		if($suip != $serverinfos['ip']){
			message("很抱歉，您的站点尚未购买本系统，请联系正版微擎(http://mai.we7.cc)购买！（本警示仅站长可见！）","",'fail');
		}
		$murl=url('profile/module/setting',array('m'=>FM_NAME));
		if($murl !== $serverinfos['url']){
			message("站点非法访问，请购买正版程序！如果需要购买，请联系QQ：1280880631（本警示仅站长可见！）","",'fail');
		}
		//服务器授权通讯结束
		include $this->template('modset/setting');
	}

	public function welcomeDisplay($menus) {
		//$menus 变量中存放模块注册过的菜单
		//这里可以自定义模块后台首页
		global $_GPC;
		global $_W;
		checklogin();  //检测客户端登陆状态
		//加载风格模板及资源路径
		//入口清单

		$routes=fmFunc_route_web();
		$routes_do=fmFunc_route_web_do();
		foreach($routes as $k_do => &$route){//列出do
			unset($route['ac']['ajax']);
			unset($route['ac']['index']);
			foreach($route['ac'] as $k_ac => &$item){//列出ac
				unset($item['op']['display']);
			}
		}

		unset($item);
		unset($route);

		//检查数据库
		fm_load()->fm_func('tables');
		fmFunc_tables_check();

		//开始操作管理
		//处理模块menus
		foreach($menus['cover'] as $key=>$menu){
			//print_r($key.'-');
		}

		//$do=array_rand($routes,1);//随机取一个$do
		$do = 'modset';	//定义当前活动的$do
		$settings = fmMod_settings_all();//全局加载配置
		$_FM['settings']=$settings;

		$shopname=isset($settings['brands']['shopname']) ? $settings['brands']['shopname'] : FM_NAME_CN;
		$_W['page']['title'] = $shopname.'|'.$routes[$do]['title'];

		$fm453style = "web/default/";
		$fm453resource =FM_RESOURCE;

		require_once FM_VAR ."web.php";	//全局可变变量
		require_once FM_VAR ."fastlinks.php";	//列举一些前端常用链接
		require_once FM_VAR ."adminlinks.php";	//后台普通管理员快捷链接
		// @require_once(fm_load()->fm_vars('menulinks'));
		require_once FM_VAR ."menulinks.php";	//后台通用顶部菜单栏链接

		include $this->template('index');
	}
}

?>
