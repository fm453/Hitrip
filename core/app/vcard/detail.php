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
 * @remark 会员电子名片
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;
load()->func('tpl');
fm_load()->fm_model('article');
fm_load()->fm_func('file');
fm_load()->fm_func('share');//链接分享管理
fm_load()->fm_func('view'); 	//浏览量处理
fm_load()->fm_func('wechat');//微信定义管理

//加载风格模板及资源路径
$appstyle = empty($settings['appstyle']) ? FM_APPSTYLE : $settings['appstyle'];
$appsrc =MODULE_URL.'template/mobile/'.$appstyle.'453/';
$htmlsrc =MODULE_URL.'template/mobile/'.$appstyle;
$fm453resource = FM_RESOURCE;
// 设置页面视效
$ui_body_maxwidth = '860';	//BODY的最大宽度

//入口判断
$do= $_GPC['do'];
$ac=$_GPC['ac'];
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'index';
if($op =='login' || !$_W['uid']) {
	checkauth();
}
//开始操作管理
$shopname=$settings['brands']['shopname'];
$shopname = !empty($shopname) ? $shopname :FM_NAME_CN;
$pagename = '微名片';

$uniacid=$_W['uniacid'];
$plattype=$settings['plattype'];
$platids = fmFunc_getPlatids();
$platid=$_W['uniacid'];
$oauthid=$platids['oauthid'];
$fendianids=$platids['fendianids'];
$supplydianids=$platids['supplydianids'];
$blackids=$platids['blackids'];

//平台模式处理
require_once FM_PUBLIC.'plat.php';
$supplydians=explode(',',$supplydianids);//字符串转数组
$supplydians=array_filter($supplydians);//数组去空

//按平台模式前置筛选条件
$condition=' WHERE ';
$params=array();
require_once FM_PUBLIC.'forsearch.php';

$share_user=$_GPC['share_user'];
$shareid = intval($_GPC['shareid']);
$lastid = intval($_GPC['lastid']);
$currentid = intval($_W['member']['uid']);
$fromplatid = intval($_GPC['fromplatid']);
$from_user = $_W['openid'];
$url_condition="";
$direct_url = fm_murl($do,$ac,$operation,array());

//自定义微信分享内容
$_share = array();
$_share['title'] = $pagename.'|'.$_W['account']['name'];
$_share['link'] = fm_murl($do,$ac,$operation,array('isshare'=>1));
$_share['link'] = $_share['link'].$url_condition;
$_share['imgUrl'] = tomedia($settings['brands']['logo']);
$_share['desc'] = $_share['desc'] = $settings['brands']['share_des'];

$resultmember = fmMod_member_query($currentid);
$FM_member=$resultmember['data'];
if(!$FM_member['nickname']) {
	if($FM_member['realname']) {
		$FM_member['nickname']=$FM_member['realname'];
	}else{
		$FM_member['nickname']=$FM_member['realname']="未填写";
	}
}

$FM_vipcard_open=FALSE;
$FM_vipcard_get = FALSE;
//判断公众号是否开启会员卡功能
$card_setting = pdo_fetch("SELECT * FROM ".tablename('mc_card')." WHERE uniacid = '{$_W['uniacid']}'");
$card_status =  $card_setting['status'];
if($card_status) {
	$FM_vipcard_open=TRUE;
}
//查看会员是否开启会员卡功能
$membercard_setting  = pdo_get('mc_card_members', array('uniacid' => $_W['uniacid'], 'uid' => $_W['member']['uid']));
$membercard_status = $membercard_setting['status'];
$pricefield = (!empty($membercard_status) && $card_status == 1) ? "mprice" : "cprice";
if (!empty($card_status) && !empty($membercard_status)) {
	$FM_vipcard_get=TRUE;
} else {
	$FM_vipcard_get= false;
}

//会员自定义设置
$mine_settings=$_FM['member']['settings'];

$mine_settings['onoffs']['notify']= !isset($mine_settings['onoffs']['notify']) ? 1 : $mine_settings['onoffs']['notify'];
$mine_settings['onoffs']['noDisturb'] = !isset($mine_settings['onoffs']['noDisturb']) ? 2 : $mine_settings['onoffs']['noDisturb'];
$mine_settings['onoffs']['tip_qiandao'] = !isset($mine_settings['onoffs']['tip_qiandao']) ? 1 : $mine_settings['onoffs']['tip_qiandao'];


//开始取所查会员的信息
$uid = intval($_GPC['uid']);
$user = array();
if(!$uid) {
	$uid = $currentid;
	$user['info'] = $FM_member;
	$user['settings'] = $mine_settings;
	$user['agent'] = $FM_agent;
}else{
	$user_member = fmMod_member_query($uid);//会员身份信息
	$user_member = $user_member['data'];
	$user_agent = fmMod_member_agent($uid);	//代理身份信息
	$user_agent = $user_agent['data'];

	$user['info'] = $user_member;
	$user['settings'] = fmMod_member_settings($uid);	//粉丝个性设置
	$user['agent'] = $user_agent;
}

//初始化部分信息
//将会员设置表单中的会员信息直接转入会员设置
if(is_array($user['settings']['formdata'])) {
	foreach($user['settings']['formdata'] as $k=>$v){
		if($k != 'formdata') {
			$user['settings'][$k] = $v;
		}
	}
}

$user['settings']['isVerify'] = "名片审核中";
$user['agent']['detail'] = false;

//从文章库中查询关联的会员介绍文
$article_openid = $user['info']['openid'];
$article=pdo_fetch("SELECT * FROM " . tablename('fm453_site_article') . $condition ." AND a_tpl = 'member' AND goodadm = '".$article_openid."' ",$params);
if($article) {
	$article_id = $article['id'];
	if($article['status']==1){
		//文章为可用状态，视为该会员有效、通过了平台认证
		$user['settings']['isVerify'] = "平台名片已认证";
		$user['agent']['detail'] = $article['detail'];
	}else{
		$user['settings']['isVerify'] = "名片审核中";
	}
	$article_url=fm_murl('article','detail','index',array('id'=>$article_id));
}

//数据处理
$user['settings']['thumb'] = !empty($user['settings']['thumb']) ? $user['settings']['thumb'] : $user['info']['avatar'];
$user['settings']['company'] = !empty($user['settings']['company']) ? $user['settings']['company'] : $_W['account']['name'];
$user['settings']['sex'] = !empty($user['settings']['sex']) ? $user['settings']['sex'] : ($user['info']['gender']==1 ? 2 : 1);
$user['settings']['birthday'] = $user['info']['birthyear'].'-'. $user['info']['birthmonth'].'-'.$user['info']['birthday'];
$user['settings']['now_where'] = $user['settings']['now_province'].'-'.$user['settings']['now_city'].'-'.$user['settings']['now_county'];	//现居 省-市-区
$user['settings']['now_address'] = !empty($user['settings']['now_address']) ? $user['settings']['now_address'] : $user['info']['address'];	//现居地址
$user['settings']['email'] = !empty($user['settings']['email']) ? $user['settings']['email'] : 'null';
$user['settings']['phone'] = !empty($user['settings']['phone']) ? $user['settings']['phone'] : $user['info']['mobile'];
$user['settings']['work_phone'] = !empty($user['settings']['work_phone']) ? $user['settings']['work_phone'] : $user['settings']['phone'];
$user['settings']['home_phone'] = !empty($user['settings']['home_phone']) ? $user['settings']['home_phone'] : 'null';
$user['settings']['job'] = isset($user['settings']['job']) ? $user['settings']['job'] : '';

//通讯录二维码生成（换行符必须使用双引号外包；PHP的单引号为绝对字符串外包）
$content = "BEGIN:VCARD"."\r\n";
$content .= "VERSION:2.1"."\r\n";
// $content .= "N:;".$user['info']['realname'].";;;"."\r\n";	//姓名
$content .= "FN:".$user['info']['realname']."\r\n";		//姓名
$content .= "ORG:".$user['settings']['company']."\r\n";	//公司部门
$content .= "TITLE:".$user['settings']['isVerify']."\r\n";		//职位
// $content .= "PHOTO;JPEG:".$user['info']['avatar']."\r\n";	//头像	-网页浏览器无添加头像到通讯录的权限，隐去
$content .= "TEL;CELL,VOICE:".$user['info']['mobile']."\r\n";	//移动电话
if($user['settings']['home_phone']){
	$content .= "TEL;HOME;VOICE:".$user['settings']['home_phone']."\r\n";	//家庭电话
}
if($user['settings']['work_phone'] && $user['settings']['work_phone'] != $user['info']['mobile']){
	$content .= "TEL;WORD;VOICE:".$user['settings']['work_phone']."\r\n";	//工作电话
}
$content .= "URL;WORK:".fm_shorturl($_W['siteurl'])."\r\n";	//个人主页
$content .= "EMAIL;FREE;INTERNET:".$user['settings']['email']."\r\n";	//邮箱 -必须是正确的邮箱才能被识别
$content .= "ADR;WORK:;".$user['settings']['now_address'].";;;;"."\r\n";	//工作地址
// $content .= "LABEL;WORK;".$user['settings']['now_address']."\r\n";	//工作地址	-不能被识别，隐去
$content .= "BDAY:".$user['info']['birthyear']."-".$user['info']['birthmonth']."-".$user['info']['birthday']."\r\n";	//生日
$content .= "END:VCARD";

//二维码
// BEGIN:VCARD
// VERSION:2.1
// N:Gump;Forrest
// FN:Forrest Gump
// ORG:Bubba Gump Shrimp Co.
// TITLE:Shrimp Man
// PHOTO;GIF:http://www.example.com/dir_photos/my_photo.gif
// TEL;WORK;VOICE:(111) 555-1212
// TEL;HOME;VOICE:(404) 555-1212
// ADR;WORK:;;100 Waters Edge;Baytown;LA;30314;United States of America
// LABEL;WORK;ENCODING=QUOTED-PRINTABLE:100 Waters Edge=0D=0ABaytown, LA 30314=0D=0AUnited States of America
// ADR;HOME:;;42 Plantation St.;Baytown;LA;30314;United States of America
// LABEL;HOME;ENCODING=QUOTED-PRINTABLE:42 Plantation St.=0D=0ABaytown, LA 30314=0D=0AUnited States of America
// EMAIL;PREF;INTERNET:forrestgump@example.com
// REV:20080424T195243Z
// END:VCARD

$qrcode=fmFunc_qrcode_name_m($platid,$do,$ac,$operation,$uid,0,0);
fmFunc_qrcode_check($qrcode,$content,$forceRefresh=1);
$qrcode2contact = tomedia($qrcode);

// $qrcode2contact = fmFunc_file_image_base64Encode(IA_ROOT.'/attachment/'.$qrcode);	//图片转64位BASE码，实际不用，仅测试

$user['qrcode'] = isset($settings['qrcode_wx']) ? $settings['qrcode_wx'] : $qrcode2contact;

$user['detail']  = fmMod_article_memberContentFormat($user,1);

//注册通讯录资料的地址
// $reg_url = $_FM['settings']['member_regUrl'];	//系统设置的会员注册页地址
$reg_url = "http://vcms.hiluker.com/app/index.php?c=entry&i=96&m=fm453_shopping&do=needs&ac=detail&op=index&shareid=44321&id=4&isshare=1";

//重定义页分享标题
$_share['title'] = $user['info']['realname'].'|'.$pagename.'|'.$_W['account']['name'];

if($op=="index"){
	//更新流量、链路统计
	if(!empty($shareid)){
		fmFunc_share_click($lastid,$shareid,$currentid,$share_user);
	}
	fmFunc_view();//记录访问
	fmMod_member_check($_W['openid']);//检测会员
	//模板主框架（父页面）
	include fmFunc_template_m($do.'/453');
}
