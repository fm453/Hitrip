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
 * @remark 检测相关数据表是否完整并进行修复
 */
defined('IN_IA') or exit('Access Denied');
global $_W, $_GPC,$_FM;

load()->func('tpl');
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
$_W['page']['title'] = $shopname.'数据表检测';
$direct_url=fm_wurl($do,$ac,$opertaion,array());
$uniacid=$_W['uniacid'];
$platids = fmFunc_getPlatids();//获取平台模式关联的公众号商户ID
$oauthid=$platids['oauthid'];
$fendianids=$platids['fendianids'];
$supplydianids=$platids['supplydianids'];
$blackids=$platids['blackids'];

$tables=fmFunc_tables_all();

if($operation=='display') {
	$groups = fmFunc_tables_group();
	$needcreate = '';
	$checkresult = '';
	$tablelist=array();
	$result = array();
	$tablepre =!empty($settings['tablepre']) ? $settings['tablepre'] : $_W['config']['db']['master']['tablepre'];

	$isfixed = true;
	foreach($groups as $group){
		$result[$group] = fmFunc_tables_check($group);
		$checkresult .= $result[$group]['msg'].'<br>';
		$needcreate .= $result[$group]['data'].'<br>';
		if(!$result[$group]['result']){
			$isfixed = false;
		}
		foreach($result[$group]['tablelist'] as $key => $table){
			$tablelist[$table['name']]=$table;
		}
	}
	if($isfixed){
		$checkresult .='<i class="fa fa-check-square-o" style="color:blue;"></i>恭喜，全部数据表、字段及索引，均已修复！';
	}

	//除商城主体外的其他数据表列表尚待列入
	$tables=array();//附加用到的几个系统的表
	$tables['mc_members']='系统会员表，商城共用此表记录会员信息';
	$tables['mc_member_address']='系统会员地址表，商城共用此表记录会员收货地址信息';
	foreach($tables as $key=>$table){
		$tablelist[$key] =array(
			'name'=>$key,
			'remark'=>$table
		);
	}
	include $this->template('modset/checkdb');
}elseif($operation=='export'){
	$withData= ($_GPC['withdata']==1) ? TRUE : FALSE;
	fmFunc_tables_export($withData);
	//写入操作日志START
		$dologs=array(
			'url'=>$_W['siteurl'],
			'description'=>'导出数据',
			'addons'=>'',
		);
		fmMod_log_record($platid,$_W['uniacid'],$_W['uid'],$fanid,$openid,'',$id,'export',$dologs);
		unset($dologs);
		//写入操作日志END
}
