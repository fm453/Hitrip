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
 * @remark 电子名片
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;
load()->func('tpl');
fm_load()->fm_func('share');//链接分享管理
fm_load()->fm_func('view'); 	//浏览量处理
fm_load()->fm_func('wechat');//微信定义管理

//加载风格模板及资源路径
$appstyle = empty($settings['appstyle']) ? FM_APPSTYLE : $settings['appstyle'];
$appsrc =MODULE_URL.'template/mobile/'.$appstyle.'453/';
$htmlsrc =MODULE_URL.'template/mobile/'.$appstyle;
$fm453resource = FM_RESOURCE;
//入口判断
$do=$_GPC['do'];
$ac=$_GPC['ac'];
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'index';
if($operation=='login') {
	checkauth();
}

//默认跳转到会员本人电子名片详情页
$new_ac = 'detail';
header('location:'.fm_murl($do,$new_ac,$op,array()));
exit;
// //开始操作管理
// $shopname=$settings['brands']['shopname'];
// $shopname = !empty($shopname) ? $shopname :FM_NAME_CN;
// $pagename = '微名片';

// $uniacid=$_W['uniacid'];
// $plattype=$settings['plattype'];
// $platids=fmFunc_getPlatids();//获取平台模式关联的公众号商户ID参数
// $platid=$_W['uniacid'];
// $oauthid=$platids['oauthid'];
// $fendianids=$platids['fendianids'];
// $supplydianids=$platids['supplydianids'];
// $blackids=$platids['blackids'];

// //平台模式处理
// require_once FM_PUBLIC.'plat.php';
// $supplydians=explode(',',$supplydianids);//字符串转数组
// $supplydians=array_filter($supplydians);//数组去空

// //按平台模式前置筛选条件
// $condition=' WHERE ';
// $params=array();
// require_once FM_PUBLIC.'forsearch.php';

// $share_user=$_GPC['share_user'];
// $shareid = intval($_GPC['shareid']);
// $lastid = intval($_GPC['lastid']);
// $currentid = intval($_W['member']['uid']);
// $fromplatid = intval($_GPC['fromplatid']);
// $from_user = $_W['openid'];
// $url_condition="";
// $direct_url = fm_murl($do,$ac,$operation,array());

// //自定义微信分享内容
// $_share = array();
// $_share['title'] = $pagename.'|'.$_W['account']['name'];
// $_share['link'] = fm_murl($do,$ac,$operation,array('isshare'=>1));
// $_share['link'] = $_share['link'].$url_condition;
// $_share['imgUrl'] = tomedia($settings['brands']['logo']);
// $_share['desc'] = $_share['desc'] = $settings['brands']['share_des'];

// $resultmember = fmMod_member_query($currentid);
// $FM_member=$resultmember['data'];

// //会员自定义设置
// $mine_settings=$_FM['member']['settings'];
// $mine_settings['onoffs']['notify']=1;
// $mine_settings['onoffs']['nodisturb']=2;
// $mine_settings['onoffs']['tip_qiandao']=1;


// include $this->template($appstyle.$do.'/453');
