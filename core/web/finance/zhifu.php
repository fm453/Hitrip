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
 * @remark 佣金发放管理；
 */
defined('IN_IA') or exit('Access Denied');
global $_W, $_GPC,$_FM;
message('功能暂未开放');

load()->func('tpl');
load()->model('account');//加载公众号函数
fm_load()->fm_model('category'); //分类管理模块
fm_load()->fm_model('goods'); //商品管理模块
fm_load()->fm_model('goodstpl'); //商品模型管理模块

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

$cfg = $settings['fenxiao'];
$zhifucommission = $cfg['zhifuCommission'];
if(!$zhifucommission){
		message('请先在参数设置，设置佣金打款限额！', $this->createWebUrl('Commission'), 'success');
}
if(empty($_GPC['mobile'])){
		$mobile = 0;
}else{
		$mobile = $_GPC['mobile'];
}

if($operation=='display'){
		if($_GPC['submit'] == '搜索'){
				$list = pdo_fetchall("select * from ".tablename('fm453_shopping_member'). " where mobile = ".$mobile." and status = 1 and flag = 1 and (commission - zhifu) >= ".$zhifucommission." and uniacid = ".$_W['uniacid']);
				$total=count($list);
				include $this->template($fm453style .'zhifu');
				exit();
			}
			if(intval($_GPC['so']) == 1) {
				$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('fm453_shopping_member')." WHERE status = 1 and flag = 1 and (commission - zhifu) >= ".$zhifucommission." and uniacid = :uniacid ", array(':uniacid' => $_W['uniacid']));
				$pager = pagination($total, $pindex, $psize);
				$list = pdo_fetchall("SELECT * FROM ".tablename('fm453_shopping_member')."  WHERE uniacid = ".$_W['uniacid']." AND and status = 1 and flag = 1 and (commission - zhifu) >= ".$zhifucommission." ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . "," . $psize);
			} else {
				$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('fm453_shopping_member')." WHERE status = 1 and flag = 1 and (commission - zhifu) >= ".$zhifucommission." AND `uniacid` = :uniacid", array(':uniacid' => $_W['uniacid']));
				$pager = pagination($total, $pindex, $psize);
				$list = pdo_fetchall("SELECT * FROM ".tablename('fm453_shopping_member')." WHERE uniacid = ".$_W['uniacid']."  AND status = 1 AND flag = 1 ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . "," . $psize);
			}
		include $this->template($fm453style.$do.'/453');
}
elseif($operation=='detail'){
		if(empty($_GPC['from_user'])){
				message('请选择会员！', create_url('zhifu', array('do' => 'zhifu','op'=>'list', 'name' => 'fm453_shopping','uniacid'=>$_W['uniacid'])), 'success');
		}
		if(checksubmit()){
				$chargenum=intval($_GPC['chargenum']);
				if($chargenum){
						pdo_query("update ".tablename('fm453_shopping_member')." SET zhifu=zhifu+'".$chargenum."' WHERE from_user='".$_GPC['from_user']."' AND  uniacid = ".$_W['uniacid']."  ");
						$paylog=array(
							'type'=>'zhifu',
							'uniacid'=>$uniacid,
							'openid'=>$_GPC['from_user'],
							'tid'=>date('Y-m-d H:i:s'),
							'fee'=>$chargenum,
							'module'=>'fm453_shopping',
							'tag'=>' 嗨旅行商城后台分销佣金结算打款'.$chargenum.'元'
						);
						pdo_insert('core_paylog',$paylog);
				}
		}
		$from_user = $_GPC['from_user'];
		$profile = pdo_fetch('SELECT * FROM '.tablename('fm453_shopping_member')." WHERE  uniacid = :uniacid  AND from_user = :from_user" , array(':uniacid' => $_W['uniacid'],':from_user' => $from_user));
		if(!$profile){
				message('请选择会员！', create_url('zhifu', array('do' => 'zhifu','op'=>'list', 'name' => 'fm453_shopping','uniacid'=>$_W['uniacid'])), 'success');
		}
		$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('core_paylog')." WHERE  openid='".$_GPC['from_user']."' AND `uniacid` = ".$_W['uniacid']);
		$pager = pagination($total, $pindex, $psize);
		$list = pdo_fetchall("SELECT * FROM ".tablename('core_paylog')." WHERE openid='".$_GPC['from_user']."' AND uniacid=".$_W['uniacid']." ORDER BY plid DESC LIMIT " . ($pindex - 1) * $psize . "," . $psize);
		$mlist=pdo_fetchall("SELECT `name`,`title` FROM ".tablename('modules'));
		$mtype=array();
		foreach($mlist as $k=>$v){
				$mtype[$v['name']]=	$v['title'];
		}
include $this->template($fm453style.$do.'/453');
}

