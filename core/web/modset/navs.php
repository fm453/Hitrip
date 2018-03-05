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
 * @remark 自定义导航条设置页
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
$shopname = !empty($shopname) ? $shopname : FM_NAME_CN;
$_W['page']['title'] = $shopname.'后台导航条设置';

if($operation=='display') {
	if(empty($settings['navsnum'])){
		$navsnum = 1;
	}
	include $this->template('modset/navs');
}
elseif($operation=='modify') {
	if(!empty($settings['shouquan']['sufm453code']) && $settings['onoffs']['isnavmenu']==1){
		$navsnum = $settings['navsnum'];
		if(empty($navsnum)){
			$navsnum = 1;
		}
		$saveNav = array();
		for ($n=0; $n <$navsnum ; $n++) {
			$navmenusnum = $settings['navmenusnum'];
			if(empty($navmenusnum)){
				$navmenusnum = 5;
			}
			$saveItem = array();
			$mymenuname = $_GPC['mymenuname'];
			$saveItem['mymenuname']=$mymenuname;
			$mymenuvalue = $_GPC['mymenuvalue'];
			$saveItem['mymenuvalue']=$mymenuvalue;
			$mymenuicon = $_GPC['mymenuicon'];
			$saveItem['mymenuicon']=$mymenuicon;
			for ($i=0; $i <$navmenusnum ; $i++) {
				$iconValue=$_GPC['icon'.$i];
				$linkValue=$_GPC['value'.$i];
				$nameValue=$_GPC['name'.$i];

				$saveItem['icon'.$i]=$iconValue;
				$saveItem['value'.$i]=$linkValue;
				$saveItem['name'.$i]=$nameValue;
			}
			$saveNav['nav'.$n]=$saveItem;
		}
		$setfor='navmenus';
		$record = array();
		$record['value']=$saveNav;
		$record['status']='127';
		//$record=iserializer($record);
		$result=fmMod_setting_save($record,$setfor,$_W['uniacid']);
		if($result['result']) {
			//写入操作日志START
			$dologs=array(
				'url'=>$_W['siteurl'],
				'description'=>'自定义导航条设置',
				'addons'=>$saveNav
			);
			fmMod_log_record($platid,$_W['uniacid'],$_W['uid'],$fanid,$openid,'fm453_shopping_settings',$id,'modify',$dologs);
			unset($dologs);
			//写入操作日志END
			message('自定义导航条保存成功','referer','success');
		}else{
			message('自定义导航条保存失败，原因：'.$result['msg'],'referer','fail');
		}
	}else{
		message('您不是管理员，不具备添加自定义导航条的权限','referer','fail');
	}
}
elseif($operation=='delete'){
	//从数据库删除
	$result=fmMod_setting_delete($_W['uniacid'],'navmenus','');
	if($result['result']){
	//写入操作日志START
		$dologs=array(
			'url'=>$_W['siteurl'],
			'description'=>'清除自定义导航条',
			'addons'=>'',
		);
		fmMod_log_record($platid,$_W['uniacid'],$_W['uid'],$fanid,$openid,'fm453_shopping_settings',$id,'delete',$dologs);
		unset($dologs);
		//写入操作日志END
		message($shopname.'自定义导航条配置信息已经清空！','referer','success');
	}else{
		message('清空失败；提示：'.$result["msg"],'','fail');
	}
}