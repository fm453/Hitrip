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
 * @remark 系统后台测试
*/
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;

load()->func('file');

//入口判断
$routes=fmFunc_route_web();
$routes_do=fmFunc_route_web_do();
$do=$_GPC['do'];
$ac=$_GPC['ac'];
$all_ac=fmFunc_route_web_ac($do);
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$all_op=fmFunc_route_web_op_single($do,$ac);
if(!in_array($ac,$all_ac) || !in_array($operation,$all_op)){
  die(message('非法访问，请通过有效路径进入！'));
}

fmMod_privilege_adm();//检查用户授权

//开始操作管理
$shopname=$settings['brands']['shopname'];
$shopname = !empty($shopname) ? $shopname :FM_NAME_CN;
$_W['page']['title'] = $shopname.'系统后台测试';

$aim = $_GPC['aim'];
switch ($aim) {
  case 'dacms':
    $result = fmFunc_api_login();
    if($result['errorcode']>0) {
      message("接口报错，请联系QQ：1280880631（本警示仅站长可见！）。错误码：{$result['errorcode']}; 错误提示：{$result['msg']}","",'fail');
    }else{
      message('OK,接口测试通过！','referer','success');
    }
    break;
  case 'workweixin':
    fm_load()->fm_func('wechat.corp'); //企业微信函数集
    $WorkWeiXinAccount = array('corpid'=>$settings['api']['workweixin_corpid'],'corpsecret'=>$settings['api']['workweixin_corpsecret'],'agentid'=>$settings['api']['workweixin_agentid']);
    $workweixin = new  WorkWeiXinAccount($WorkWeiXinAccount);
    $token = $workweixin->getAccessToken();
    //var_dump($token);
    $WorkWeiXinAccount = $workweixin->getAccount();
    // var_dump($WorkWeiXinAccount);
    $url = 
    $workweixin->AppOAuthCode($url);
    break;
  
  default:
    itoast('未指定有效的测试目标','','info');
    break;
}