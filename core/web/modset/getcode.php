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
 * @remark 模块授权配置（清空覆盖方式）
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
$_W['page']['title'] = $shopname.'授权配置';

if($operation=='display') {
	$shouquan=$settings['shouquan'];

	$shopversion=FM_VERSION;
	if (!empty($shouquan['sufm453code'])){
				$isread='readonly="true"';
			}else{
				$shouquan['sufm453code'] = '';
				$isread='';
			}

	$suip=gethostbyname($_SERVER['HTTP_HOST']);
	$sudomain=$_SERVER['HTTP_HOST'];

	include $this->template('modset/getcode');
}
elseif($operation=='modify'){
	$saveItem = array();
		$suip=$_GPC['suip'];
	$saveItem['suip']=$suip;
		$sudomain=$_GPC['sudomain'];
	$saveItem['sudomain']=$sudomain;
		$suapi=$_GPC['suapi'];
		$susecret=$_GPC['susecret'];
		$sufm453code = $_GPC['sufm453code'];
	$serverinfos=fmFunc_server_check();//与服务器通讯，进行往来查询

	if(checksubmit(bindme)) {
		if($_W['isfounder']){
			$saveItem['suapi']=$suapi;
			$saveItem['susecret']=$susecret;
			$saveItem['sufm453code']=$sufm453code;
			$suinfo =md5($suip.$sudomain.$sufm453code);
			$saveItem['suinfo']=$suinfo;
			if(!empty($sufm453code)) {
				if($sufm453code !== $serverinfos['authcode']){
					message("很抱歉，授权码不对，请联系确认填写正确！如果需要购买，请联系QQ：1280880631（本警示仅站长可见！）","",'fail');
				}
				else
				if($suapi !== $serverinfos['api'] || $susecret !== $serverinfos['secret']){
					message("很抱歉，授权码API与密钥，请联系确认填写正确！如果需要购买，请联系QQ：1280880631（本警示仅站长可见！）","",'fail');
				}
				else
				if($suip !== $serverinfos['ip'] || $sudomain !== $serverinfos['domain']){
					message("该站点无注册购买记录，如果需要购买，请联系QQ：1280880631（本警示仅站长可见！）","",'fail');
				}

				$setfor='shouquan';
				$record = array();
				$record['title']='模块授权配置';
				$record['value']=$saveItem;
				$record['status']='127';
				$result=fmMod_setting_save_sys($record, $setfor);
				if($result) {
					$this->module['config']['shouquan']=$saveItem;
					$this->saveSettings($this->module['config']);
					//写入操作日志START
					$dologs=array(
						'url'=>$_W['siteurl'],
						'description'=>'绑定授权设置',
						'addons'=>$saveItem,
					);
					fmMod_log_record($platid,$_W['uniacid'],$_W['uid'],$fanid,$openid,'fm453_shopping_settings',$id,'modify',$dologs);
					unset($dologs);
					//写入操作日志END
					// 在附件目录中添加一个记事本文件并写入授权信息
					file_write('fm453/verify/'.$sudomain.'.log', $suinfo);
					message('恭喜，授权保存成功！','referer','success');
				}
			}else{
				message('亲，请填入客服给您的授权信息！','referer','error');
			}
		}else {
			message('亲，这种事请让管理员来处理吧！','referer','error');
		}
	}
	if(checksubmit(rebindme) ) {
		if($_W['isfounder']){
			if(!empty($settings['shouquan']['sufm453code'])) {
				$saveItem['sufm453code']='';
				$saveItem['suapi']='';
				$saveItem['susecret']='';
				$sulog='由'.$_W['role'].'-'.$_W['username'].'(ID:'.$_W['uid'].')于'.date("Y-m-d H:i:s",TIMESTAMP).'操作解除授权';
				$suinfo ='IP:'.$suip.'，域名'.$sudomain.'，状态：'.$sulog;
				$saveItem['suinfo']=$suinfo;

				$setfor='shouquan';
				$record = array();
				$record['title']='模块授权配置';
				$record['value']=$saveItem;
				$record['status']='127';
				$result=fmMod_setting_save_sys($record, $setfor);
				//  在附件文件夹下生成fm453文件夹，并在其中建立一个以当前域名命名的日志文件，将授权信息写入文件中；
				if($result['result']) {

					//写入操作日志START
					$dologs=array(
						'url'=>$_W['siteurl'],
						'description'=>'解除模块授权',
						'addons'=>$saveItem,
					);
					fmMod_log_record($platid,$_W['uniacid'],$_W['uid'],$fanid,$openid,'fm453_shopping_settings',$id,'modify',$dologs);
					unset($dologs);
					//写入操作日志END
					// 在附件目录中添加一个记事本文件并写入授权信息
					file_write('fm453/verify/'.$sudomain.'.log', $suinfo);
					message('现在，已经为您解绑了！','referer','success');
				}else{
					message('解绑失败！{$result["msg"]}','referer','faile');
				}
			}else {
				message('亲，您还没有购买授权呢！','referer','error');
			}
		}else {
			message('亲，这种事请让管理员来处理吧！','referer','error');
		}
	}
}
if($operation=='delete') {
	//从数据库删除授权
	$result=fmMod_setting_delete_sys('shouquan','');
	if($result['result']){
//写入操作日志START
		$dologs=array(
			'url'=>$_W['siteurl'],
			'description'=>'删除授权信息',
			'addons'=>'',
		);
		fmMod_log_record($platid,$_W['uniacid'],$_W['uid'],$fanid,$openid,'fm453_shopping_settings',$id,'delete',$dologs);
		unset($dologs);
		//写入操作日志END
		message('授权信息已经清空！',fm_wurl($do,$ac,'',array()),'info');
	}else{
		message('清空失败；提示：{$result["msg"]}','','info');
	}
}