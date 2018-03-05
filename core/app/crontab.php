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
 * @remark 定时任务
*/
defined('IN_IA') or exit('Access Denied');

ignore_user_abort(true);	//即使用户把浏览器关掉（断开连接），php也会在服务器上继续执行
set_time_limit(0);
date_default_timezone_set('PRC'); // 切换到中国的时间

global $_GPC;
global $_W;
global $_FM;

if (empty($_W['account']['endtime']) && !empty($_W['account']['endtime']) && $_W['account']['endtime'] < time()) {
	fm_error('公众号已到服务期限，暂停该项服务!','系统提醒','https://vcms.hiluker.com');
}
fm_load()->fm_func('share');//链接分享管理
fm_load()->fm_func('view'); 	//浏览量处理
fm_load()->fm_func('wechat');//微信定义管理

//加载风格模板及资源路径
$appstyle = empty($settings['appstyle']) ? FM_APPSTYLE : $settings['appstyle'];
$appsrc   =MODULE_URL.'template/mobile/'.$appstyle.'453/';
$htmlsrc  =MODULE_URL.'template/mobile/'.$appstyle;
$fm453resource = FM_RESOURCE;
//入口判断
$do        = 'crontab';
$ac        =$_GPC['ac'];
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'index';
//开始操作管理
$shopname      =$_W['uniaccount']['name'];

$uniacid       =$_W['uniacid'];
$plattype      =$settings['plattype'];
$platids       =fmFunc_getPlatids();//获取平台模式关联的公众号商户ID参数
$platid        =$_W['uniacid'];
$oauthid       =$platids['oauthid'];
$fendianids    =$platids['fendianids'];
$supplydianids =$platids['supplydianids'];
$blackids      =$platids['blackids'];

//平台模式处理
require_once FM_PUBLIC.'plat.php';
$supplydians   =explode(',',$supplydianids);//字符串转数组
$supplydians   =array_filter($supplydians);//数组去空

//按平台模式前置筛选条件
$condition     =' WHERE ';
$params        =array();
require_once FM_PUBLIC.'forsearch.php';

//利用判断页面是否已经被打开
$isopended = cache_load('crontab_is_doing'.$_W['uniacid']);
if($isopended){
	die(message("定时任务已经在进行中，当前页面不再继续！"));
}else{
	cache_write('crontab_is_doing'.$_W['uniacid'],1);
}
//执行记录计数
$_FM['settings']['crontab']['timelimit'] = (intval($_FM['settings']['crontab']['timelimit']) > 3600) ? intval($_FM['settings']['crontab']['timelimit']) : 3600;	//默认最少间隔60分钟
$done_count = cache_load('crontab_done_count_'.$_W['uniacid']);
if(!$done_count) {
	$done_count = 0;
}
//粉丝统计(更新数据按天读取)
$nowTime = $_W['timestamp'];
$today = date('Ymd', time());
$yesterday = date('Ymd', strtotime('-1 days'));
$fansUpdatedTime = cache_load('crontab_fans_updated_time'.$_W['uniacid']);
if($fansUpdatedTime){
	$fansUpdatedDate = date('Ymd', $fansUpdatedTime);
	$has_FansUpdated = ($fansUpdatedDate==$today) ? TRUE : FALSE;
	if(!$has_FansUpdated){
		uni_update_week_stat();
		cache_write('crontab_fans_updated_time'.$_W['uniacid'],$nowTime);
	}
}else{
	uni_update_week_stat();
	cache_write('crontab_fans_updated_time'.$_W['uniacid'],$nowTime);
}

$yesterday_stat = pdo_get('stat_fans', array('date' => $yesterday, 'uniacid' => $_W['uniacid']));
$today_stat = pdo_get('stat_fans', array('date' => date('Ymd'), 'uniacid' => $_W['uniacid']));
$today_add_num = intval($today_stat['new']);
$today_cancel_num = intval($today_stat['cancel']);
$today_jing_num = $today_add_num - $today_cancel_num;
$today_total_num = intval($today_jing_num) + intval($yesterday_stat['cumulate']);
if($today_total_num < 0) {
	$today_total_num = 0;
}

$weektitle = array(
'0'=>'周日',
'1'=>'周一',
'2'=>'周二',
'3'=>'周三',
'4'=>'周四',
'5'=>'周五',
'6'=>'周六',
);
$nowtime_w = date('w',TIMESTAMP);

/*————————设立问候语内容库并根据规则进行发送————————*/
//数据报告
/*—————————————————————————————————*/

if($_FM['settings']['crontab']['msg_report_admin_off'] !=1) {
    $_FM['settings']['crontab']['msg_timeline_report_admin'] = !empty($_FM['settings']['crontab']['msg_timeline_report_admin']) ? $_FM['settings']['crontab']['msg_timeline_report_admin'] : (8*60*60);	//该通知的间隔时间(cookie有效期)(默认8h)
	$cache_crontab_msg_report_admin = cache_load('crontab_msg_report_admin_'.$_W['uniacid']);
	$cache_crontab_msg_report_admin = json_decode($cache_crontab_msg_report_admin,true);
	$has_crontab_msg_report_admin_result = $cache_crontab_msg_report_admin['result'];
	$has_crontab_msg_report_admin_dotime = $cache_crontab_msg_report_admin['dotime'];

	if(!in_array(date('H',$has_crontab_msg_report_admin_dotime), array('7','8','9','15','16','17','22','23') )) {
	    //break;    //只在7～9点，15～17点，22～23点这些时段才发送
	}

	$notice_data['task']['crontab']['report']['admin'] =	"关键数据报告".'\r';
	$notice_data['task']['crontab']['report']['admin'] .=	date('Y年m月d日',time()).'  '.$weektitle[$nowtime_w].'\r';
	$notice_data['task']['crontab']['report']['admin'] .=	"今日粉丝："."累计关注".$today_total_num."  其中，新关注".$today_add_num."  取消关注".$today_cancel_num."  净增".$today_jing_num.'\r';

	$has_crontab_msg_report_admin = (($nowTime-$has_crontab_msg_report_admin_dotime)>=$_FM['settings']['crontab']['msg_timeline_report_admin']) ? TRUE : FALSE;	//如果当前时间与上次执行时差大于设置的间隔时间，说明可以发送报告了，返回TRUE
	if($has_crontab_msg_report_admin){
		$postData = $notice_data['task']['crontab']['report']['admin'];
		$result= fmMod_notice($_FM['settings']['manageropenids'],$postData);	//发送客服消息
		$hasResult=0;
		foreach($result as $r){
			if($r==1) {
				$hasResult +=1;
			}
		}
		$temp_cache=array('result'=>$hasResult,'dotime'=>$nowTime);
		$temp_cache=json_encode($temp_cache);
		cache_write('crontab_msg_report_admin_'.$_W['uniacid'],$temp_cache);
	}
}

//定时问候
/*—————————————————————————————————*/
if($_FM['settings']['crontab']['msg_hello_morning_admin_off'] !=1) {
	$notice_data['task']['crontab']['hello']['morning']['admin'] =	"亲，打卡上班啦！".'\n\r'.'今天是'.$weektitle[$nowtime_w];
	$_FM['settings']['crontab']['msg_timeline_hello_morning'] = !empty($_FM['settings']['crontab']['msg_timeline_hello_morning']) ? $_FM['settings']['crontab']['msg_timeline_hello_morning'] : (24*60*60);	//该通知的间隔时间(cookie有效期)(默认24h)
	$cache_crontab_msg_hello_admin = cache_load('crontab_msg_hello_admin_'.$_W['uniacid']);
	$cache_crontab_msg_hello_admin = json_decode($cache_crontab_msg_hello_admin, $assoc = true);
	$has_crontab_msg_hello_admin_result = $cache_crontab_msg_hello_admin['result'];
	$has_crontab_msg_hello_admin_dotime = $cache_crontab_msg_hello_admin['dotime'];
	$has_crontab_msg_hello_admin = (($nowTime-$has_crontab_msg_hello_admin_dotime)>=$_FM['settings']['crontab']['msg_timeline_hello_morning']) ? TRUE : FALSE;	//如果当前时间与上次执行时差大于设置的间隔时间，说明可以发送报告了，返回TRUE
	if($has_crontab_msg_hello_admin){
		$postData = $notice_data['task']['crontab']['hello']['morning']['admin'];
		$result= fmMod_notice($_FM['settings']['manageropenids'],$postData);	//发送客服消息
		$hasResult=0;
		foreach($result as $r){
			if($r==1) {
				$hasResult +=1;
			}
		}
		$temp_cache=array('result'=>$hasResult,'dotime'=>$nowTime);
		$temp_cache=json_encode($temp_cache);
		cache_write('crontab_msg_hello_admin_'.$_W['uniacid'],$temp_cache);
	}
}

if($_GPC['refresh']) {
    cache_write('crontab_done_count_'.$_W['uniacid'],$done_count+1);
    echo $done_count+1;
	exit();
}else{
	include $this->template('crontab');
}
