<?php
/**
 * 嗨商城系统
 *
 * @author Fm453
 * @url http://bbs.we7.cc/thread-9945-1-1.html
 * @guide WeEngine Team & ewei
 * @site www.hiluker.com
 * @remark 微聊天室
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;

fm_load()->fm_model('chat');
fm_load()->fm_func('share');//链接分享管理
fm_load()->fm_func('view'); 	//浏览量处理

//加载风格模板及资源路径
$appstyle = empty($settings['appstyle']) ? FM_APPSTYLE : $settings['appstyle'];
$appsrc =MODULE_URL.'template/mobile/'.$appstyle.'453/';
$fm453resource = FM_RESOURCE;

//入口判断
$do= $_GPC['do'];
$ac=$_GPC['ac'];
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'index';
//来路计算
$share_user=$_GPC['share_user'];
$shareid = intval($_GPC['shareid']);//分享来源ID
$lastid = intval($_GPC['lastid']);//分享来源ID的上一个来源ID
$currentid = intval($_W['member']['uid']);//当前用户的会员ID（从mc_members表中读取）
$fromplatid = intval($_GPC['fromplatid']);	//来源平台（用于跨平台支付等情形）
$siteid = $_W['setting']['site']['key'];	//来源站点
$fromuser = $_W['openid'];
$url_condition="";

$_FM['settings']['onoffs']['debug'] = 1;	//开启调试模式,应用于临时的页面开发管理查看
$_FM['settings']['onoffs']['console'] = 1; //前台将输出console.log记录

$sn=intval($_GPC['sn']);	//传入的为表单订单SN（order_sn）

if($operation=='all' ){
	$s_sn = pdo_fetchcolumn("SELECT sn FROM ".tablename('hi_chat_basic_self')." WHERE uniacid = :uniacid AND siteid = :siteid ",array(":uniacid"=>$_W['uniacid'],":siteid"=>$siteid));
	if(!$s_sn) {
		//初始化聊天室各项参数,自动生成一个群聊室
		$chats = fmMod_chat_init();
		$s_sn = $chats['order']['sn'];
	}
	$f_sn = pdo_fetchcolumn("SELECT sn FROM ".tablename('hi_chat_basic_form')." WHERE s_sn = :s_sn AND uniacid = :uniacid AND siteid = :siteid ",array(":uniacid"=>$_W['uniacid'],":siteid"=>$siteid,":s_sn"=>$s_sn));
	$o_sn = pdo_fetchcolumn("SELECT sn FROM ".tablename('hi_chat_basic_order')." WHERE s_sn = :s_sn AND f_sn = :f_sn AND uniacid = :uniacid AND siteid = :siteid ",array(":uniacid"=>$_W['uniacid'],":siteid"=>$siteid,":s_sn"=>$s_sn,":f_sn"=>$f_sn));
	$sn = $o_sn;
}

//读入数据
$chats=array();//全系统的聊天室的系统配置
$chatSelf=array();	//全系统的聊天室的基础配置信息
$chatParams=array();	//全系统的聊天室的配置参数
$chatForms=array();	//该公众号生成的当前群聊天室(具体场景)信息
$chatAddons=array();	//该聊天室的各用户配置信息
$chatDatas=array();	//聊天室的消息数据，对应chatOrder

$chatOrder = cache_load('chat_order_'.$sn.'_'.$_W['uniacid']);
$chatOrder = iunserializer($chatOrder);
if(!$chatOrder){
	$chatOrder = fmMod_chat_order_query($sn);
}

$s_sn =  $chatOrder['s_sn'];
$f_sn = $chatOrder['f_sn'];
$o_sn = $sn;

/**————开始操作管理————**/
$_FM['settings']['force_follow']=$settings['force_follow']=FALSE;
$_FM['settings']['force_login']=$settings['force_login']=FALSE;
//根据参数进行输出控制

//初始权限判断
if($settings['force_follow']) {
	checkauth();	//要求微信网页授权或用户登陆
	checkfollow($_FM['member']['info']);
}elseif($settings['force_login']) {
	checkauth();	//要求微信网页授权或用户登陆
}

//初始化设置
$shopname=$settings['brands']['shopname'];
$shopname = !empty($shopname) ? $shopname :FM_NAME_CN;
$pagename = '在线交流';
$pagename .='|'.$_W['account']['name'];
$expire = 48*60*60; //默认邀请有效期为48小时
$isforbidden = false;
$nowTime = $_W['timestamp'];


//进行各种自定义
$expired = intval($_GPC['expired']);	//过期时间

$chats['id']=1;	//群聊默认为第一条数据
$chats['title']= "交流群";
$allTotal['online']= 58;
$allTotal['num_noread']= '···';
$allTotal['msg_new']= 9;

$placeholderImg = tomedia(FM_RESOURCE.'images/default/background-logo.png');
$avatar = tomedia(FM_RESOURCE.'images/favicon.png');
$textbg_l = tomedia(FM_RESOURCE.'images/chat/chat_record_l_text.png');
$textbg_r = tomedia(FM_RESOURCE.'images/chat/chat_record_r_text.png');
$username = $_FM['member']['info']['username'];
$pagename = $chats['title']."(".$allTotal['online'].")";
$settings['onoffs']['confirmClose'] = true;	//关闭页面是否需要确认

//自定义微信分享内容(必须在渲染HTML文件之前)
$_share = array();
$_share['title'] = $pagename;
$_share['link'] = fm_murl($do,$ac,$operation,array('expired'=>($timestamp+$expire),'isshare'=>1));
$_share['link'] = $_share['link'].$url_condition;
$_share['imgUrl'] = tomedia($settings['brands']['logo']);
$_share['desc'] = '我发现一个很不错的交流平台，正在这里聊得火热,现在邀请你一起来！';

//具体事务处理
$isforbidden = false;	//是否禁止访问
$inChat = true;	//粉丝是否仍在群中，不在则停止向其传输数据

	//校验用户状态，不通过时则禁止发送消息
	$error=array();
	$return = array();
	$return['result'] = 1;
	if(!$inChat){
		//请出群聊,停止传输数据
		$error['code'] = 45300000;
		$error['content'] = '抱歉,您被管理员请出了该群!<br>';
		$error['title'] = '系统提示';
		$return['error']=$error;
		$return['result'] = -1;
	}

	if($return['result']<=0) {
		$return = json_encode($return);
		die($return);
	}

if($operation=='index' ){
	$isforbidden = true;	//禁止访问
	if($isforbidden){
		fm_error('关闭原因：该群已解散','该群已禁止访问!');
	}
	include $this->template($appstyle.$do.'/453');
}elseif($operation=='all' ){
	if($isforbidden){
		fm_error('关闭原因：该群已解散','该群已禁止访问!');
	}
	include $this->template($appstyle.$do.'/453');
}elseif($operation=='modify' ){
	//校验用户状态，不通过时则禁止发送消息
	$error=array();
	$return = array();
	$return['result'] = 1;
	if(!$_W['openid']){
		//未获取到openid时
		$error['code'] = 45300001;
		$error['content'] = '抱歉,您需要通过微信或会员账号登陆后才能进行设置';
		$error['title'] = '系统提示';
		$return['error']=$error;
		$return['result'] = 0;
	}elseif(!$inChat){
		//请出群聊,停止传输数据
		$error['code'] = 45300000;
		$error['content'] = '抱歉,您被管理员请出了该群!<br>';
		$error['title'] = '系统提示';
		$return['error']=$error;
		$return['result'] = -1;
	}

	if($return['result']<=0) {
		$return = json_encode($return);
		die($return);
	}
	//自定义设置公用部分
	$data['mid'] = $currentid;
	$data['status'] = 1;
	$data['statuscode'] = 0;
	$data['isGlobal'] = intval($_POST['global']);	//是否应用到全局(1是0否)
	$data['isGlobal'] = (in_array($_W['openid'],$managerOpenids)) ? $data['isGlobal'] : 0;

	//自定义设置数据部分
	$temp['setfor'] = htmlspecialchars($_POST['setfor']);
	$temp['title'] = htmlspecialchars($_POST['title']);
	$temp['key'] = htmlspecialchars($_POST['key']);
	$temp['value'] = htmlspecialchars($_POST['value']);
	//数据格式化
	$data[$temp['setfor']] = array();
	if(!empty($temp['title']) && !empty($temp['key'])) {
		$data[$temp['setfor']][$temp['title']][$temp['key']] = $temp['value'];
	}elseif(empty($temp['title']) && empty($temp['key'])) {
		$data[$temp['setfor']] = $temp['value'];
	}elseif(!empty($temp['title']) && empty($temp['key'])) {
		$data[$temp['setfor']][$temp['title']] = $temp['value'];
	}elseif(empty($temp['title']) && !empty($temp['key'])) {
		$data[$temp['setfor']][$temp['key']] = $temp['value'];
	}

	//保存
	fmMod_chat_addon_modify($o_sn,$data);
}elseif($operation=='get' ){

	//获取数据
	$o_sn = $_GET['nowOrderSn'];
	$callNew = intval($_GET['callNew']);

	$return = array();
	$messages=array();
	$message=array();

	switch($callNew) {
		case 1:
			$default_timelenth = 5;
			$starttime = time()-2;
			$timelenth = 2;
			$num =-1;
			$result = fmMod_chat_data_get($o_sn,$starttime,$endtime,$timelenth,$num,$excluded=false);	//取新数据时不加载会员自己的记录
			if(!$result['result']) {
				$return['result'] = 0;	//暂停传输数据
				$return['msg'] = $result['msg'];
				$return = json_encode($return);
				die($return);
			}
			$messages = $result['data'];
			if(is_array($messages)) {
				$return['result'] = 1;	//有数据
				foreach($messages as &$message){
					$message['value'] = json_decode($message['value']);
					foreach($message['value'] as $k =>$v){
						$message[$k] = $v;
					}
				}
			}else{
				$return['result'] = 0;	//暂停传输数据
			}
		break;

		case 0:
			$latestRecTime = $_GET['latestRecTime'];	//最近一次下拉刷新结果中记录的时间点
			$default_timelenth = 5;
			$endtime = $latestRecTime;
			$timelenth = $default_timelenth;
			$num =-1;
			$result = fmMod_chat_data_get($o_sn,$starttime,$endtime,$timelenth,$num,$excluded=false);	//取旧数据时可加载会员自己的记录
			if(!$result['result']) {
				$return['result'] = 0;	//暂停传输数据
				$return['msg'] = $result['msg'];
				$return['msg'] = $latestRecTime;
				$return = json_encode($return);
				die($return);
			}
			$messages = $result['data'];
			if(is_array($messages)) {
				$return['result'] = 1;	//有数据
				foreach($messages as &$message){
					$message['value'] = json_decode($message['value']);
					foreach($message['value'] as $k =>$v){
						$message[$k] = $v;
					}
				}
			}else{
				$return['result'] = 0;	//暂停传输数据
			}
		break;

		default:
			$return['result'] = 0;	//暂停传输数据
		break;
	}


	//打包成数据，输出到前端
	$return['messages'] = $messages;
	$return['callNew'] = $callNew;
	$return = json_encode($return);
	die($return);

}elseif($operation=='send' ){
	//校验用户状态，不通过时则禁止发送消息
	$error=array();
	$return = array();
	$return['result'] = 1;
	if(!$_W['openid']){
		//未获取到openid时
		$error['code'] = 45300001;
		$error['content'] = '抱歉,您暂时不能发出消息!<br>原因：您需要登陆';
		$error['title'] = '系统提示';
		$return['error']=$error;
		$return['result'] = 0;
	}

	if($return['result']<=0) {
		$return = json_encode($return);
		die($return);
	}

	//处理客户端发送消息
	$data=array();
	$content = $_POST['content'];
	$token = $_POST['token'];

	$return = array();
	$messages=array();
	$message=array();

	$message['type']=0;	//消息类型（-1,系统消息；-2表情；-3快捷表态；-4 红包；0,默认，文本消息；1图片；3音频； 4视频；）
	$message['status']=1;	//消息状态
	$message['fid']=$_W['fans']['fanid'];	//对应粉丝
	$message['mid']=$currentid;	//对应会员
	$message['avatar'] = tomedia($_FM['member']['info']['avatar']);	//会员的头像
	$message['username'] = $_FM['member']['info']['nickname'];	//会员昵称
	$message['openid']=$_W['openid'];	//对应粉丝
	$message['title'] = '';	//消息标题
	$message['content'] = $content;	//消息内容

	//消息入库
	$result = fmMod_chat_data_new($message,$o_sn);
	if($result['result']){
		$m = $message;
		$message = $result['data'];
		foreach($m as $k=>$v){
			$message[$k]=$v;
		}
		//打包成数据，输出到前端
		$messages[0] = $message;
		$return['messages'] = $messages;
		$return['result'] = 1;	//发送成功，返回消息记录的id
	}else{
		$return['result'] = 0;	//暂停传输数据
	}

	$return = json_encode($return);
	die($return);
}
