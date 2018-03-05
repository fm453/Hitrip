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
 * @remark 品牌设置
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;

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
$_W['page']['title'] = $shopname.'品牌信息设置';

if($operation=='display') {
	$settings['brands']['qrcode'] = ($settings['brands']['qrcode']) ? $settings['brands']['qrcode'] : "qrcode_".$_W['acid'].".jpg";
	include $this->template('modset/brands');
}
elseif($operation=='modify') {
	$shopname = $_GPC['shopname'];
	$fm453resource =FM_RESOURCE;
	$theweb = $_GPC['officialweb'];
	if($theweb){
		if (substr($theweb, 0, 5) == 'http:') {
			$officialweb=$theweb;
		}elseif(substr($theweb, 0, 6) == 'https:') {
			$officialweb=$theweb;
		}else {
			$officialweb='http://'.$theweb;
		}
	}else{
		$officialweb="#";
	}
	$logo =  $_GPC['logo'];
	$qrcode =  $_GPC['qrcode'];
	$qrcode_wxapp =  $_GPC['qrcode_wxapp'];
	$phone = $_GPC['phone'];
	$address =  $_GPC['address'];
	$icp =  $_GPC['icp'];
	if(!empty($_GPC['copyright'])) {
		$serverinfos=fmFunc_server_check();
		if($settings['shouquan']['sufm453code'] == $serverinfos['authcode']){
			$copyright =   $_GPC['copyright'];
		}else{
			$copyright =  "hitrip@vcms.hiluker.com";
		}
	}else{
			$copyright =  "hitrip@vcms.hiluker.com";
		}
	$share_des =  $_GPC['share_des'];
	$description=  htmlspecialchars_decode($_GPC['description']);
	$brands=array(
		'shopname' => $shopname,
		'officialweb' => $officialweb,
		'logo' => $logo,
		'qrcode'=>$qrcode,
		'qrcode_wxapp'=>$qrcode_wxapp,
		'phone' => $phone,
		'address' => $address,
		'icp' => $icp,
		'copyright' => $copyright,
		'share_des'=>$share_des,
		'description'=>  htmlspecialchars_decode($description)
	);
	$setfor='brands';
	$record = array();
	$record['value']=$brands;
	$record['status']='127';
	$record=iserializer($record);
	$result=fmMod_setting_save($record,$setfor,$_W['uniacid']);
	if($result['result']) {
		$dologs=array(
			'url'=>$_W['siteurl'],
			'description'=>'模块开关设置',
			'addons'=>$brands
		);
		fmMod_log_record($platid,$_W['uniacid'],$_W['uid'],$fanid,$openid,'fm453_shopping_settings',$id,'modify',$dologs);
		unset($dologs);
		message('品牌信息设置成功','referer','success');
	}
	else{
		message('品牌信息设置失败，原因：'.$result['msg'],'','error');
	}
}
elseif($operation=='delete'){
	//从数据库删除
	$result=fmMod_setting_delete($_W['uniacid'],'brands','');
	if($result['result']){
	//写入操作日志START
		$dologs=array(
			'url'=>$_W['siteurl'],
			'description'=>'删除品牌信息',
			'addons'=>'',
		);
		fmMod_log_record($platid,$_W['uniacid'],$_W['uid'],$fanid,$openid,'fm453_shopping_settings',$id,'delete',$dologs);
		unset($dologs);
		//写入操作日志END
		message($shopname.'品牌配置信息已经清空！','referer','success');
	}else{
		message('清空失败；提示：'.$result["msg"],'','fail');
	}
}