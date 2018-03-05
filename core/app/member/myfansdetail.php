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
 * @remark 代理人粉丝（下线）的详情
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;
load()->func('tpl');
fm_load()->fm_func('share');//链接分享管理
fm_load()->fm_func('view'); 	//浏览量处理
fm_load()->fm_func('wechat');//微信定义管理

$appstyle =$this->appstyle();
$fm453resource =FM_RESOURCE;
$appsrc =fmFunc_ui_appsrc();
$shopname=$settings['brands']['shopname'];
$pagename = "";//定义页面标题
checkAuth();
$level=$_GPC['level'];
$uniacid=$_W['uniacid'];
$op = $_GPC['op']?$_GPC['op']:'index';

$profile = pdo_fetch('SELECT * FROM '.tablename('fm453_shopping_member')." WHERE  uniacid = :uniacid  AND from_user = :from_user" , array(':uniacid' => $_W['uniacid'],':from_user' => $from_user));
if($level=='1'){
		$fansall=array();
		$fansall[0] = pdo_fetchall("select mc_members.* from ".tablename('mc_members')." mc_members where mc_members.uid in ("."select orders.uid from ".tablename('fm453_shopping_order')." orders where  orders.shareid = ".$profile['id'].'  group by orders.from_user'.")");
		$fansall[1] =  pdo_fetchall("select mc_members.* from ".tablename('mc_members')." mc_members where mc_members.uid in ("."select mber.uid from ".tablename('fm453_shopping_member')." mber where mber.shareid = ".$profile['id']." and mber.from_user not in ("."select orders.from_user from ".tablename('fm453_shopping_order')." orders where  orders.shareid = ".$profile['id']." group by orders.from_user)".")");
}
if($level=='2'){
		$fansall=array();
		$countall = pdo_fetch("select id from ".tablename('fm453_shopping_member')." where shareid = ".$profile['id']);
		$rowindex =0;
		foreach ($countall as &$citem){
				$fansall[$rowindex] = pdo_fetchall("select mc_members.* from ".tablename('mc_members')." mc_members where mc_members.uid in ("."select mber.uid from ".tablename('fm453_shopping_member')." mber where mber.shareid = ".$citem.")");
		}
}
if($level=='3'){
		$countall = pdo_fetch("select id from ".tablename('fm453_shopping_member')." where shareid = ".$profile['id']);
		$fansall=array();
		$rowindex =0;
		foreach ($countall as &$citem){
				$count2all = pdo_fetch("select id from ".tablename('fm453_shopping_member')." where shareid = ".$citem);
				$str="";
				foreach ($count2all as &$citem2){
					$str=$str.$citem2.',';
				}
				$mids='('.$str.'0'.')';
				$fansall[$rowindex] = pdo_fetchall("select mc_members.* from ".tablename('mc_members')." mc_members where mc_members.uid in ("."select orders.uid from ".tablename('fm453_shopping_order')." orders where  orders.shareid = ".$citem2.' and orders.shareid!='.$citem.' and orders.shareid!='.$profile['id'].' group by orders.from_user'.")" );
				$rowindex=$rowindex+1;
		}
}

include $this->template($appstyle.$do.'/453');
