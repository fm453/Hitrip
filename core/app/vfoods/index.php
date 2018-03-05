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
 * @remark 微餐饮总入口，选择点餐模式
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;

if($_GPC['ordertype']){
	//传入了订单类型值时
	$new_ac = $_GPC['ordertype'];
	if($new_ac){
		header('location:'.fm_murl($do,$new_ac,$_GPC['op'],array()));
		exit();
	}
}

fm_load()->fm_func('foods');
fm_load()->fm_func('share');//链接分享管理
fm_load()->fm_func('view'); 	//浏览量处理
fm_load()->fm_func('wechat');//微信定义管理

//全局访问权限判断
if($settings['ismcverify']){
	if(!$_W['member']['uid']){
		checkauth();
	}else{
		$result = fmMod_member_agent($uid=$_W['member']['uid']);
		$verify = $result['data'];
		if($verify['status']!=1){
			$msgtitle = "访问权限提示";
			$msgbody = "对不起，您尚未通过本系统认证，暂无法使用相关功能。";
			$backurl=$hasnewurl=false;
			header('location:'.fm_murl('error','index','index',array('msg[title]'=>$msgtitle,'msg[body]'=>$msgbody,'backurl'=>$backurl,'hasnewurl'=>$hasnewurl)));
			exit();
		}
	}
}

//加载风格模板及资源路径
$appstyle      = empty($settings['appstyle']) ? FM_APPSTYLE : $settings['appstyle'];
$appsrc        =MODULE_URL.'template/mobile/'.$appstyle.'453/';
$htmlsrc       =MODULE_URL.'template/mobile/'.$appstyle;
$fm453resource = FM_RESOURCE;
//入口判断
$do            = $_GPC['do'];
$ac            = $_GPC['ac'];
$op            = !empty($_GPC['op']) ? $_GPC['op'] : 'index';
if(!in_array($op,array('index','load','loadmore','redirect'))){
	$op = 'index';
}
if($op=="redirect"){
	//默认跳转到多店铺首页
	$new_ac = !$_GPC['new_ac'] ? 'dianjia' : $_GPC['new_ac'];
	header('location:'.fm_murl($do,$new_ac,$op,array()));
	exit();
}
//开始操作管理
$shopname      =$settings['brands']['shopname'];
$shopname      = !empty($shopname) ? $shopname :FM_NAME_CN;

$uniacid       =$_W['uniacid'];
$plattype      =$settings['plattype'];
$platids       =fmFunc_getPlatids();//获取平台模式关联的公众号商户ID参数
$platid        =$_W['uniacid'];
$oauthid       =$platids['oauthid'];
$fendianids    =$platids['fendianids'];
$supplydianids =$platids['supplydianids'];
$blackids      =$platids['blackids'];

//平台模式处理
// require_once FM_PUBLIC.'plat.php';
// $supplydians   =explode(',',$supplydianids);//字符串转数组
// $supplydians   =array_filter($supplydians);//数组去空

//按平台模式前置筛选条件
// $condition     =' WHERE ';
// $params        =array();
// require_once FM_PUBLIC.'forsearch.php';

$share_user    =$_GPC['share_user'];
$shareid       = intval($_GPC['shareid']);
$lastid        = intval($_GPC['lastid']);
$currentid     = intval($_W['member']['uid']);
$fromplatid    = intval($_GPC['fromplatid']);
$from_user     = $_W['openid'];
$url_condition ="";
$direct_url    = fm_murl($do,$ac,$op,array());

//自定义微信分享内容
$_share           = array();
$_share['title']  = $shopname.'|'.$_W['account']['name'];
$_share['link']   = fm_murl($do,$ac,$operation,array('isshare'=>1));
$_share['link']   = $_share['link'].$url_condition;
$_share['imgUrl'] = tomedia($settings['brands']['logo']);
$_share['desc']   = $settings['brands']['share_des'];

// $resultmember     = fmMod_member_query($currentid);
// $FM_member        =$resultmember['data'];

// //会员自定义设置
// $mine_settings    =$_FM['member']['settings'];

//页面具体操作
//MUI侧边栏链接
$shoptype = pdo_fetchall("SELECT * FROM ".tablename('fm453_vfoods_shoptype')." WHERE uniacid = '{$_W['uniacid']}' ");	//全部店家
$appNavs=array();
// require_once FM_APP.$do.DIRECTORY_SEPARATOR.'_aside.php';

if($op=='login') {
	checkauth();
}

fm_load()->fm_model('ad');
//幻灯片
$result_advs = fmMod_ad_adv_mine();
$advs = $result_advs['data'];
$lastadvno=count($advs)-1;
$lastadv=$advs[$lastadvno];
$firstadv=$advs[0];
//unset($advs);

//购买模式
$buytypes = array();
$buytypes = fmFunc_foods_dingcantypes(false);
$_dingcantypes = $settings['vfoods']['dingcantypes'];	//设置的订餐模式外显数据
foreach($buytypes as $bk=>&$buytype){
	foreach($buytype as $k=>&$v){
		if(isset($_dingcantypes[$bk][$k])){
			$v = $_dingcantypes[$bk][$k];
		}
	}
}
unset($buytype);

if($op=="index"){
	//更新流量、链路统计
	if(!empty($shareid)){
		fmFunc_share_click($lastid,$shareid,$currentid,$share_user);
	}
	fmFunc_view();//记录访问
	//fmMod_member_check($_W['openid']);//检测会员
}
//模板主框架（父页面）
include fmFunc_template_m($do.'/453');
exit();
