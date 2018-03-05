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

$_FM['settings']['onoffs']['debug'] = 1;	//开启调试模式,应用于临时的页面开发管理查看
$_FM['settings']['onoffs']['console'] = 1; //前台将输出console.log记录

$sn=intval($_GPC['sn']);	//传入的为表单订单SN（order_sn）
$sn=1487663751;

//初始化聊天室各项参数-仅测试期间使用
//fmMod_chat_init();

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
$fromuser = $_W['openid'];
$url_condition="";
$nowTime = $_W['timestamp'];

/**————开始操作管理————**/
//初始权限判断
$_FM['settings']['force_follow']=$settings['force_follow']=FALSE;
$_FM['settings']['force_login']=$settings['force_login']=FALSE;
if($settings['force_follow']) {
	checkauth();	//要求微信网页授权或用户登陆
	checkfollow($_FM['member']['info']);
}elseif($settings['force_login']) {
	checkauth();	//要求微信网页授权或用户登陆
}
checkauth();	//要求微信网页授权或用户登陆

$expired = intval($_GPC['expired']);	//过期时间

//初始化设置
$shopname=$settings['brands']['shopname'];
$shopname = !empty($shopname) ? $shopname :FM_NAME_CN;
$pagename = '在线交流';
$pagename .='|'.$_W['account']['name'];
$expire = 48*60*60; //默认邀请有效期为48小时
$isforbidden = false;

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
	$chatOrder = fmMod_chat_query_order($sn);
}

$sn =  $chatOrder['s_sn'];
$f_sn = $chatOrder['f_sn'];
$o_sn = $chatOrder['sn'];
/**

$chatSns=cache_load('chat_sn_'.$_W['uniacid']);
$chatSns=iunserializer($chatSns);
if(!$chatSns)
{
	$chatSns=fmMod_chat_self_sns('all');
}
$chartParam = cache_load('chat_param_'.$f_sn.'_'.$_W['uniacid']);

$chatOrder = iunserializer($chatOrder);
if(!$chatOrder)
{
	$chatOrder = fmMod_chat_query_order($sn);
}

$chartForm = fmMod_chat_query_Form($sn);
$chatSelf = fmMod_chat_query_self($sn);

**/
//进行各种自定义
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
$_share['link'] = fm_murl($do,$ac,$operation,array('expired'=>($timestamp+$expire,'isshare'=>1)));
$_share['link'] = $_share['link'].$url_condition;
$_share['imgUrl'] = tomedia($settings['brands']['logo']);
$_share['desc'] = '我发现一个很不错的交流平台，正在这里聊得火热,现在邀请你一起来！';

//具体事务处理
$isforbidden = false;	//是否禁止访问
$inChat = true;	//粉丝是否仍在群中，不在则停止向其传输数据


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
}elseif($operation=='get' ){
	//校验用户状态，不通过时则禁止发送消息
	$error=array();
	$return = array();
	if(!$_W['openid']){
		//未获取到openid时
		$error['code'] = 45300001;
		$error['content'] = '抱歉,您暂时不能发出消息!<br>原因：您需要登陆';
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

	//获取数据
	$callNew = $_GET['callNew'];
	$return = array();
	$messages=array();
	$message=array();

	$message['id']=1;	//记录号
	$message['type']=0;	//消息类型（-1,系统消息；-2表情；-3快捷表态；-4 红包；0,默认，文本消息；1图片；3音频； 4视频；）
	$message['mid']=8883;	//对应会员
	$message['avatar'] = tomedia($_FM['member']['info']['avatar']);	//会员的头像
	$message['username'] = '新来的用户叫张三';	//会员昵称
	$message['content'] = '大家好，我是张三，我为李四代言';	//消息内容
	$message['createtime']= 1487663751;	//消息发出的时间
	$messages[0] = $message;

	$message['id']=2;	//记录号
	$message['type']= 0;	//消息类型（-1,系统消息；-2表情；-3快捷表态；-4 红包；0,默认，文本消息；1图片；3音频； 4视频；）
	$message['mid']= 1;	//对应会员，-1为公众号本身
	$message['avatar'] = tomedia($avatar);	//会员头像
	$message['username'] = '很长很长很长很长很长很长很长很长很长很长的用户名';	//公众号名称
	$message['content'] = '这里是别人回复的消息，很长。。。很长。。。很长。。。很长。。。很长。。。很长。。。很长。。。很长。。。很长。。。';	//消息内容
	$message['createtime']= 1487663751;	//消息发出的时间
	$messages[1] = $message;

	$message['id']=3;	//记录号
	$message['type']= 0;	//消息类型（-1,系统消息；-2表情；-3快捷表态；-4 红包；0,默认，文本消息；1图片；3音频； 4视频；）
	$message['mid']= -1;	//对应会员，-1为公众号本身
	$message['avatar'] = tomedia('headimg_'.$_W['acid'].'.jpg');	//公众号的头像
	$message['username'] = $_W['uniaccount']['name'];	//公众号名称
	$message['content'] = '方少邀请隔壁老王加入群聊';	//消息内容
	$message['createtime']= 1487663751;	//消息发出的时间
	$message['time2string']= date('m月d日 H:i:s',$message['createtime']);	//时间转文字格式化
	$messages[2] = $message;

	$message['id']=4;	//记录号
	$message['type']=0;	//消息类型（-1,系统消息；-2表情；-3快捷表态；-4 红包；0,默认，文本消息；1图片；3音频； 4视频；）
	$message['mid']=8883;	//对应会员
	$message['avatar'] = tomedia($_FM['member']['info']['avatar']);	//会员的头像
	$message['username'] = '新来的用户叫张三';	//会员昵称
	$message['content'] = '这里是我的消息，很长。。。很长。。。很长。。。很长。。。很长。。。很长。。。很长。。。很长。。。很长。。。';	//消息内容
	$message['createtime']= 1487663751;	//消息发出的时间
	$messages[3] = $message;

	$message['id']=5;	//记录号
	$message['type']=0;	//消息类型（-1,系统消息；-2表情；-3快捷表态；-4 红包；0,默认，文本消息；1图片；3音频； 4视频；）
	$message['mid']=3;	//对应会员
	$message['avatar'] = tomedia($_FM['member']['info']['avatar']);	//会员的头像
	$message['username'] = '新来的用户叫张三';	//会员昵称
	$message['content'] = '发一条短消息';	//消息内容
	$message['createtime']= 1487663751;	//消息发出的时间
	$messages[4] = $message;

	//打包成数据，输出到前端
	$return['messages'] = $messages;
	$return['result'] = 1;	//有数据

	$return['result'] = 0;	//暂停传输数据

	$return['callNew'] = ($callNew) ? 1 : 0;
	$return['callNew'] = 1;
	$return = json_encode($return);
	die($return);

}elseif($operation=='send' ){
	//校验用户状态，不通过时则禁止发送消息
	$error=array();
	$return = array();
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
		$message = $result['data'];
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
