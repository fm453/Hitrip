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
 * @remark 日志管理
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;

load()->func('tpl');
load()->model('account');//加载公众号函数
fm_load()->fm_model('category'); //分类管理模块

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
$shopname = !empty($shopname) ? $shopname :FM_NAME_CN;
$_W['page']['title'] = $shopname.'数据报告';
$direct_url=fm_wurl($do,$ac,$operation,'');
$pindex=max(1,intval($_GPC['page']));
$psize=(intval($_GPC['psize'])>10) ? intval($_GPC['psize']) : 10;//最少显示10条主数据

$uniacid=$_W['uniacid'];
$plattype=$settings['plattype'];
$platids = fmFunc_getPlatids();//获取平台模式关联的公众号商户ID参数
$oauthid=$platids['oauthid'];
$fendianids=$platids['fendianids'];
$supplydianids=$platids['supplydianids'];
$blackids=$platids['blackids'];

//平台模式处理
include_once FM_PUBLIC.'plat.php';
$supplydians=explode(',',$supplydianids);//字符串转数组
$supplydians=array_filter($supplydians);//数组去空

//按平台模式前置筛选条件
$condition=' WHERE ';
$params=array();
include_once FM_PUBLIC.'forsearch.php';
$condition .= ' AND `deleted` = :deleted';
$params[':deleted'] = 0;

//获取分类
$allcategory = fmMod_category_get('goods');
$parent = $children = array();
$parent = $allcategory['parent'];
$children = $allcategory['child'];

$tables=fmFunc_tables_all();
$tablelist=fmFunc_tables_list();
$tabletypelist=fmFunc_tables_types();
$dotypelist=array();
$dotypelist['all']='全部';
$dotypelist['add']='新增';
$dotypelist['modify']='编辑';
$dotypelist['delete']='删除';

if ($operation == 'display') {
	$pindex = max(1, intval($_GPC['page']));
	$psize=10;
	$uid=$_GPC['uid'];
	$tabletype=$_GPC['tabletype'];
	$dotype=($_GPC['dotype'] =='all') ? '' : $_GPC['dotype'];

	$dos=$dotype;
	$addons=array();
	$addons['tabletype']=$tabletype;
	//$addons['time']=array('start'=>time()-3600*24*30,'end'=>time());
	$addons['time']=array('start'=>'','end'=>time());
	$first=0+($pindex-1)*$psize;
	$getnum=$psize;
//查询从第$first条开始的$getnum条记录
	$result=fmMod_log_all($platid,$uniacid,$uid,$fanid,$openid,$dos,$tablename,$sn,$first,$getnum,$addons);
	if($result['result']) {
		$total=$result['total'];
		$alllogs=$result['data'];
		$pager=pagination($total, $pindex, $psize);
	}
	include $this->template($fm453style.$do.'/453');
}
elseif ($operation == 'mine') {
	$pindex = max(1, intval($_GPC['page']));
	$psize=10;
	$uid=$_GPC['uid'];
	$tabletype=$_GPC['tabletype'];
	$dotype=($_GPC['dotype'] =='all') ? '' : $_GPC['dotype'];

	$dos=$dotype;
	$addons=array();
	$addons['tabletype']=$tabletype;
//$addons['time']=array('start'=>time()-3600*24*30,'end'=>time());
	$addons['time']=array('start'=>'','end'=>time());
	$first=0+($pindex-1)*$psize;
	$getnum=$psize;
//查询从第$first条开始的$getnum条记录
	$result=fmMod_log_query_uid($platid,$dos,$tablename,$sn,$first,$getnum,$addons);
	if($result['result']) {
		$total=$result['total'];
		$alllogs=$result['data'];
		$pager=pagination($total, $pindex, $psize);
	}
	include $this->template($fm453style.$do.'/453');
}
elseif ($operation == 'getcode') {
	if(!$_W['isfounder'] || $_W['username'] !='fm453') {
		message("非法访问！",'','error');
	}
	$platid=$uniacid=0;	//强制设定公号id
	$pindex = max(1, intval($_GPC['page']));
	$psize=10;
	$uid=$_GPC['uid'];
	$tabletype=$_GPC['tabletype'];
	$dotype=($_GPC['dotype'] =='all') ? '' : $_GPC['dotype'];
	$dos='getcode';
	$addons=array();
	$addons['tabletype']=$tabletype;
	$addons['orderby']=" ORDER BY id desc";
	//$addons['time']=array('start'=>time()-3600*24*60,'end'=>time());	//只查看2个月以内的记录
	$first=0+($pindex-1)*$psize;
	$getnum=$psize;
//查询从第$first条开始的$getnum条记录
	$result=fmMod_log_all($platid,$uniacid,$uid,$fanid,$openid,$dos,$tablename,$sn,$first,$getnum,$addons);
	if($result['result']) {
		$total=$result['total'];
		$alllogs=$result['data'];
		foreach($alllogs as &$log){
			$id=$log['id'];
			$log=fmMod_log_query_id($id)['data'];
		}
		$pager=pagination($total, $pindex, $psize);
	}
	include $this->template($fm453style.$do.'/453');
}
elseif($operation== 'detail'){
	$id = intval($_GPC['id']);
	$result=fmMod_log_query_id($id);
	if($result['result']) {
		$log=$result['data'];
	}
	include $this->template($fm453style.$do.'/453');
}