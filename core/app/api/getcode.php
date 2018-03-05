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
 * @remark 获取授权
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;

fm_load()->fm_func('share');//链接分享管理
fm_load()->fm_func('view'); 	//浏览量处理

$_W['page']['title'] = '嗨旅行商城初始授权设置';
$saveItem = array();
$suip=$_GPC['suip'];
	$saveItem['suip']=$suip;
$sudomain=$_GPC['sudomain'];
	$saveItem['sudomain']=$sudomain;
$suapi=$_GPC['suapi'];
$suseceret=$_GPC['suseceret'];
$sufm453code = $_GPC['sufm453code'];
$op=$_GPC['op'];
fmFunc_server_check();//与服务器建议通讯，进行往来查询

if($op=='modulecheck') {//该段，内用，发行版不包含
	$data=$_GPC;
	$data=unserializer($data);
	$postdata['ip']=$data['ip'];
	$postdata['sudomain']=$data['sudomain'];
	$postdata['siteurl']=$data['siteurl'];
	$postdata['suapi']=$data['suapi'];
	$postdata['suseceret']=$data['suseceret'];
	$postdata['sufm453code']=$data['sufm453code'];
	$postdata['uniacid']=$data['uniacid'];
	$postdata['uniaccount']=$data['uniaccount'];
	$postdata['uid']=$data['uid'];
	$postdata['username']=$data['username'];
	$postdata['loginuser']=$data['loginuser'];
	$postdata['timestamp']=$data['timestamp'];
	//写入操作日志START
	$dologs=array(
		'url'=>$data['siteurl'],
		'description'=>'服务器通讯日志',
		'addons'=>$postdata,
	);
	fmMod_log_record($platid,$uniacid,$uid,$fanid,$openid,'fm453_shopping_settings',$id,'getcode',$dologs);
	unset($dologs);
	//写入操作日志END
}
