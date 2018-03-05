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
 * @remark 代理人粉丝列表
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
$uniacid=$_W['uniacid'];
$op = $_GPC['op']?$_GPC['op']:'display';
$profile = pdo_fetch('SELECT * FROM '.tablename('fm453_shopping_member')." WHERE  uniacid = :uniacid  AND from_user = :from_user" , array(':uniacid' => $_W['uniacid'],':from_user' => $from_user));
		//	$count1_1 = pdo_fetchcolumn("select count(id) from ".tablename('fm453_shopping_member')." where shareid = ".$profile['id']);
$count1 = pdo_fetchcolumn("select count(*) from ("."select from_user from ".tablename('fm453_shopping_order')." where  shareid = ".$profile['id'].'  group by from_user'.") x");
$count1_2 = pdo_fetchcolumn("select count(mber.id) from ".tablename('fm453_shopping_member')." mber where mber.shareid = ".$profile['id']." and mber.from_user not in ("."select orders.from_user from ".tablename('fm453_shopping_order')." orders where  orders.shareid = ".$profile['id']." group by from_user)");
$count1=$count1+$count1_2;
if($count1>0)	{
		$countall = pdo_fetch("select id from ".tablename('fm453_shopping_member')." where shareid = ".$profile['id']);
		$count2=0;
		$count3=0;
		foreach ($countall as &$citem){
				$tcount2 = pdo_fetchcolumn("select count(id) from ".tablename('fm453_shopping_member')." where shareid = ".$citem);
				$count2=$count2+$tcount2;
				$count2all = pdo_fetch("select id from ".tablename('fm453_shopping_member')." where shareid = ".$citem);
				foreach ($count2all as &$citem2){
						$tcount3 = pdo_fetchcolumn("select count(*) from ("."select from_user from ".tablename('fm453_shopping_order')." where  shareid = ".$citem2.' and shareid!='.$citem.' and shareid!='.$profile['id'].' group by from_user'.") y"  );
						$count3=$count3+$tcount3;
				}
		}
}else{
	$count1=0;
	$count2=0;
	$count3=0;
}

include $this->template($appstyle.$do.'/453');
