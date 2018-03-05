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
 * @remark 模块分销机制设置保存
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
$_W['page']['title'] = $shopname.'分销机制设置';

if($operation=='display') {
	include $this->template('modset/fenxiao');
}
elseif($operation=='modify') {
	if(checksubmit('save')) {
		$fenxiao=array();
		$zhifuCommission = $_GPC['zhifuCommission'];
			$fenxiao['zhifuCommission']=$zhifuCommission;
		$globalCommission = $_GPC['globalCommission'];
			$fenxiao['globalCommission']=$globalCommission;
		$globalCommission2 = $_GPC['globalCommission2'];
			$fenxiao['globalCommission2']=$globalCommission2;
		$globalCommission3 = $_GPC['globalCommission3'];
			$fenxiao['globalCommission3']=$globalCommission3;
		$applytime = $_GPC['applytime'];
			$fenxiao['applytime']=$applytime;
		$tobeagent = $_GPC['tobeagent'];
			$fenxiao['tobeagent']=$tobeagent;
		$clickcredit = $_GPC['clickcredit'];
			$fenxiao['clickcredit']=$clickcredit;
		$expiretime = $_GPC['expiretime'];
			$fenxiao['expiretime']=$expiretime;
		$clicktime = $_GPC['clicktime'];
			$fenxiao['clicktime']=$clicktime;
		$help = $_GPC['help'];
			$fenxiao['help']=$help;
		$terms = $_GPC['terms'];
			$fenxiao['terms']=$terms;

		$setfor='fenxiao';
		$record = array();
		$record['value']=$fenxiao;
		$record['status']='127';
		$record=iserializer($record);
		$result=fmMod_setting_save($record,$setfor,$_W['uniacid']);
		if($result['result']) {
			//写入操作日志START
			$dologs=array(
				'url'=>$_W['siteurl'],
				'description'=>'分享机制设置',
				'addons'=>$fenxiao
			);
			fmMod_log_record($platid,$_W['uniacid'],$_W['uid'],$fanid,$openid,'fm453_shopping_settings',$id,'modify',$dologs);
			unset($dologs);
			//写入操作日志END
			message('分享机制保存成功','referer','success');
		}else{
			message('分享机制设置失败，原因：'.$result['msg'],'','error');
		}
	}
}