<?php
/**
 * 嗨商城系统
 *
 * @author Fm453
 * @url http://bbs.we7.cc/thread-9945-1-1.html
 * @guide WeEngine Team & ewei
 * @site www.hiluker.com
 * @remark 微聊天室-列表
 */
defined('IN_IA') or exit('Access Denied');
fm_load()->fm_model('chat');
fm_load()->fm_func('share');//链接分享管理
fm_load()->fm_func('view'); 	//浏览量处理
global $_GPC;
global $_W;
global $_FM;

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
$timestamp = $_W['timestamp'];
/**————开始操作管理————**/
//初始权限判断
$_FM['settings']['force_follow']=$settings['force_follow']=FALSE;
$_FM['settings']['force_login']=$settings['force_login']=FALSE;
$expired = intval($_GPC['expired']);	//过期时间

//初始化设置
$shopname=$settings['brands']['shopname'];
$shopname = !empty($shopname) ? $shopname :FM_NAME_CN;
$pagename = '在线交流';
$pagename .='|'.$_W['account']['name'];
$expire = 48*60*60; //默认邀请有效期为48小时

//进行各种自定义
$chats=array();
$chatSelf=array();
$chatParams=array();
$chatForms=array();
$chatAddons=array();
$chatDatas=array();
$chatSns=cache_load('chat_sn_'.$_W['uniacid']);
$chatSns=iunserializer($chatSns);
if(!$chatSns)
{
	$chatSns=fmMod_chat_self_sns('all');
}

//最新数据

//自定义微信分享内容(必须在渲染HTML文件之前)
$_share = array();
$_share['title'] = $pagename;
$_share['link'] = fm_murl($do,$ac,$operation,array('isshare'=>1));
$_share['link'] = $_share['link'].$url_condition;
$_share['imgUrl'] = tomedia($settings['brands']['logo']);
$_share['desc'] = '我发现一个很不错的交流平台，正在这里聊得火热,现在分享链接给你！';

if($operation=='index' )
{
	include $this->template($appstyle.$ac);
}elseif($operation=='all' )
{
	include $this->template($appstyle.$do.'/453');
}
