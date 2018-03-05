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
 * @remark 佣金打款支付
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
$pagename = "";//定义页面标题
$shopname=$settings['brands']['shopname'];
checkAuth();
$uniacid=$_W['uniacid'];
$pindex = max(1, intval($_GPC['page']));
$psize = 30;
$cfg = $this->module['config'];
$zhifucommission = $cfg['zhifuCommission'];
/*
if(empty($from_user)){
		message('请选择会员！', $this->mturl('zhifu',array('mid'=>$id)), 'success');
}
*/
$profile = pdo_fetch('SELECT * FROM '.tablename('fm453_shopping_member')." WHERE  uniacid = :uniacid  AND from_user = :from_user" , array(':uniacid' => $_W['uniacid'],':from_user' => $from_user));
/*
if(!$profile){
		message('请选择会员！', $this->mturl('zhifu',array('mid'=>$id)), 'success');
}
*/
$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('core_paylog')." WHERE  openid='".$from_user."' AND `uniacid` = ".$_W['uniacid']);
$pager = pagination($total, $pindex, $psize);
$list = pdo_fetchall("SELECT * FROM ".tablename('core_paylog')." WHERE openid='".$from_user."' AND uniacid=".$_W['uniacid']." ORDER BY plid DESC LIMIT " . ($pindex - 1) * $psize . "," . $psize);
//include $this->template($appstyle.'dakuan');
include $this->template($appstyle.$do.'/453');
