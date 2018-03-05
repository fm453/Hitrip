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
 * @remark 模块路径参数设置保存
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;
load()->func('tpl');
load()->func('file');
load()->model('account');//加载公众号函数

//加载风格模板及资源路径
$fm453style    = fmFunc_ui_shopstyle();
$fm453resource =FM_RESOURCE;

//入口判断
$routes        =fmFunc_route_web();
$routes_do     =fmFunc_route_web_do();
$do            =$_GPC['do'];
$ac            =$_GPC['ac'];
$all_ac        =fmFunc_route_web_ac($do);
$operation     = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$all_op        =fmFunc_route_web_op_single($do,$ac);
if(!in_array($ac,$all_ac) || !in_array($operation,$all_op)){
	die(message('非法访问，请通过有效路径进入！'));
}

fmMod_privilege_adm();//检查用户授权

//开始操作管理
$shopname            = !empty($settings['brands']['shopname']) ? $settings['brands']['shopname'] : FM_NAME_CN;
$_W['page']['title'] = $shopname.$routes[$do]['title'];

if($operation=='display') {
	unset($routes['modset']);
	foreach($routes  as $k=>&$v){
		unset($v['ac']['index']);
		unset($v['ac']['ajax']);
	}
	unset($v);
	include $this->template('modset/showroute');
}
elseif($operation=='modify') {
	$Data = $_GPC['data'];
	$nowroute = $_GPC['nowdo'];
	$acs = $routes[$nowroute]['ac'];
	unset($routes[$nowroute]['ac']);
	$dos = $routes[$nowroute];
	$hidden = array(0=>false,1=>true);
	$ishidden = intval($_GPC[$nowroute.'_hide']);
	$ops = array();

	$dos['hide'] = isset($dos['hide']) ? $dos['hide'] : array();
	$dos['show'] = isset($dos['show']) ? $dos['show'] : array();
	if($Data['do']){
		if($hidden[$ishidden]){
			$dos['hide'] = array_merge($dos['hide'],array($_W['uniacid']));
			array_unique($dos['hide']);
			$isinarray = array_search($_W['uniacid'],$dos['show']);
			if($isinarray || $isinarray==0){
				unset($dos['show'][$isinarray]);
			}
		}else{
			$dos['show'] = array_merge($dos['show'],array($_W['uniacid']));
			array_unique($dos['show']);
			$isinarray = array_search($_W['uniacid'],$dos['hide']);
			if($isinarray || $isinarray==0){
				unset($dos['hide'][$isinarray]);
			}
		}
	}
	$_dos = $dos;

	$_acs = isset($Data['ac']) ? $Data['ac'] : array();
	if(is_array($acs) && !empty($acs)){
		foreach($acs as $k=>&$v){
			$ops[$k]=$v['op'];
			unset($v['op']);
			$v['hide']=isset($v['hide']) ? $v['hide'] : array();
		}
	}
	if(is_array($_acs) && !empty($_acs)){
		foreach($_acs as $k=>$v){
			if($acs[$k]){
				if($hidden[$ishidden]){
					$acs[$k]['hide'] = array_merge($acs[$k]['hide'],array($_W['uniacid']));
				}else{
					$isinarray = array_search($_W['uniacid'],$acs[$k]['hide']);
					if($isinarray || $isinarray==0){
						unset($acs[$k]['hide'][$isinarray]);
					}
				}
			}
		}
	}
	unset($k);
	unset($v);
	$_acs = $acs;

	$result = fmFunc_route_fileWrite($_dos,$_acs,$ops,$nowroute);

	if($result) {
		//写入操作日志START
		$dologs=array(
			'url'=>$_W['siteurl'],
			'description'=>'路径设置',
			'addons'=>$data
		);
		fmMod_log_record($platid,$_W['uniacid'],$_W['uid'],$fanid,$openid,'',$id,'modify',$dologs);
		unset($dologs);
		//写入操作日志END
		exit(json_encode(array('code'=>0)));
	}else{
		exit(json_encode(array('code'=>1,'msg'=>'路径设置保存失败')));
	}
}
