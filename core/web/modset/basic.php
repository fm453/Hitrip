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
 * @remark 模块基础设置；
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;

load()->func('file');
load()->model('account');//加载公众号函数

//加载风格模板及资源路径
$fm453style = fmFunc_ui_shopstyle();
$fm453resource =FM_RESOURCE;

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
$shopname = !empty($shopname) ? $shopname : FM_NAME_CN;
$_W['page']['title'] = $shopname.'基础设置';

if($operation=='display') {
	$result=fmMod_setting_query_setfor($_W['uniacid'],'platids','127');
	if($result['result']) {
		$settings['platids']=$result['data'];
	}
	unset($result);
	$result=fmMod_setting_query_setfor($_W['uniacid'],'plat','127');
	if($result['result']) {
		$settings['plat']=$result['data'];
	}
	unset($result);
	//从系统获取公众号ID
	$where = '';
	$params = array();
	if(empty($_W['isfounder'])) {
		$where = " WHERE `uniacid` IN (SELECT `uniacid` FROM " . tablename('uni_account_users') . " WHERE `uid`=:uid)";
		$params[':uid'] = $_W['uid'];
	}
	$sql = "SELECT * FROM " . tablename('uni_account') . $where;
	$uniaccounts = pdo_fetchall($sql, $params);
	$accounts = array();
	$accountids="";
	if(!empty($uniaccounts)) {
		foreach($uniaccounts as $key=>$uniaccount) {
			$del_account=pdo_fetch('SELECT `acid`,`isdeleted` FROM '.tablename('account').' WHERE `uniacid`= :uniacid',array(':uniacid'=>$uniaccount['uniacid']));
			if($del_account['isdeleted']==1) {
				unset($uniaccounts[$key]);
			}else{
				$accountids .=$uniaccount['uniacid'].',';
				$accountlist = uni_accounts($uniaccount['uniacid']);
				if(!empty($accountlist)) {
					foreach($accountlist as $account) {
						if(!empty($account['key'])
							&& !empty($account['secret'])
							&& in_array($account['level'], array(4))) {
								$accounts[$account['acid']] = $account['name'];
							}
							$accountheadimg[$uniaccount['uniacid']]=$account['acid'];
						}
					}else{
						$accountheadimg[$uniaccount['uniacid']]=$uniaccount['uniacid'];
					}
			}
		}
		$accountids =explode(',',$accountids);
		$accountids =array_filter($accountids);
		foreach($accountids as $accountid){
			$accountid_temp=@uni_account_default($accountid);
			$accountname[$accountid]=$accountid_temp['name'];
		}
	}

	$row=array();
	$row['fendian']=array();
	if($settings['plat']['fendianids']){
		$fendianids=explode(",",$settings['plat']['fendianids']);
		foreach($fendianids as $fendianid){
			$row['fendian'][$fendianid]['uniacid']=$fendianid;
			if($fendianid==0) {
				$row['fendian'][$fendianid]['name']="系统平台";
				$row['fendian'][$fendianid]['headimg']=MODULE_URL."icon.jpg";
			}else{
				$temp_plataccount=@uni_account_default($fendianid);
				if(empty($temp_plataccount)) {
					$row['fendian'][$fendianid]['name']="已经删除";
				}else{
					$row['fendian'][$fendianid]['name']=$temp_plataccount['name'];
					$row['fendian'][$fendianid]['headimg']=$_W['attachurl']."headimg_".$accountheadimg[$fendianid].".jpg";
				}
				unset($temp_plataccount);
			}
		}
	}

	$row['supplydian']=array();
	if($settings['plat']['supplydianids']){
		$supplydianids=explode(",",$settings['plat']['supplydianids']);
		foreach($supplydianids as $supplydianid){
			$row['supplydian'][$supplydianid]['uniacid']=$supplydianid;
			if($supplydianid==0) {
				$row['supplydian'][$supplydianid]['name']="系统平台";
				$row['supplydian'][$supplydianid]['headimg']=MODULE_URL."icon.jpg";
			}else{
				$temp_plataccount=@uni_account_default($supplydianid);
				if(empty($temp_plataccount)) {
					$row['supplydian'][$supplydianid]['name']="已经删除";
				}else{
					$row['supplydian'][$supplydianid]['name']=$temp_plataccount['name'];
					$row['supplydian'][$supplydianid]['headimg']=$_W['attachurl']."headimg_".$accountheadimg[$supplydianid].".jpg";
				}
				unset($temp_plataccount);
			}
		}
	}

	$row['black']=array();
	if($settings['plat']['blackids']){
		$blackids=explode(",",$settings['plat']['blackids']);
		foreach($blackids as $blackid){
			$row['black'][$blackid]['uniacid']=$blackid;
			if($blackid==0) {
				$row['black'][$blackid]['name']="系统平台";
				$row['black'][$blackid]['headimg']=MODULE_URL."icon.jpg";
			}else{
				$temp_plataccount=@uni_account_default($blackid);
				if(empty($temp_plataccount)) {
					$row['black'][$blackid]['name']="已经删除";
				}else{
					$row['black'][$blackid]['name']=$temp_plataccount['name'];
					$row['black'][$blackid]['headimg']=$_W['attachurl']."headimg_".$accountheadimg[$blackid].".jpg";
				}
				unset($temp_plataccount);
			}
		}
	}

	//店铺借权设置
	$oauth = pdo_fetchcolumn('SELECT `oauth` FROM '.tablename('uni_settings').' WHERE `uniacid` = :uniacid LIMIT 1',array(':uniacid' => $_W['uniacid']));
	$oauth = iunserializer($oauth) ? iunserializer($oauth) : array();
	if($oauth['account']==0){
		$oauth['account']=1;
	}
	$settings['plat']['oauthid']=!empty($settings['plat']['oauthid']) ? $settings['plat']['oauthid'] : $oauth['account'];
	$oauthid=$settings['plat']['oauthid'];
	$auth['uniacid']=$oauthid;
	if($oauthid==0) {
		$auth['name']="系统平台";
		$auth['headimg']=MODULE_URL."icon.jpg";
	}else{
		$temp_plataccount=@uni_account_default($oauthid);
		if(empty($temp_plataccount)) {
			$auth['name']="已经删除";
		}else{
			$auth['name']=$temp_plataccount['name'];
			$auth['headimg']=$_W['attachurl']."headimg_".$oauthid.".jpg";
		}
		unset($temp_plataccount);
	}

	$settings['force_wxAuth']= !isset($settings['force_wxAuth']) ? FALSE : $settings['force_wxAuth'];
	$settings['force_login']= !isset($settings['force_login']) ? TRUE : $settings['force_login'];
	$settings['force_follow']= !isset($settings['force_follow']) ? FALSE : $settings['force_follow'];
	$settings['force_mcInfo']= !isset($settings['force_mcInfo']) ? TRUE : $settings['force_mcInfo'];
	$settings['force_agentInfo']= !isset($settings['force_agentInfo']) ? FALSE : $settings['force_agentInfo'];

	include $this->template('modset/basic');
}
elseif($operation=='modify'){
	if($_GPC['test']=='manageropenids') {
		require FM_CORE.'msgtpls/tpls/task.php';
		//发微信模板通知
		$postdata = $tplnotice_data['task']['test']['admin'];
		$result= fmMod_notice_tpl($postdata);
		if(!$result || !$result[0]['errorno']) {
			require FM_CORE.'msgtpls/msg/task.php';
			$postdata = $notice_data['test']['manageropenids']['admin'];
			$result= fmMod_notice($settings['manageropenids'],$postdata);
		}
		if($result) {
			message('测试通知已经下发,请各管理员打开微信查看','referer','success');
		}
	}
	if(checksubmit('save')) {
		$data=array();	//要传输保存的数据
		if($_W['isfounder']){
			$plattype=$_GPC['plattype'];
			if($plattype==='os'){
				$serverinfos=fmFunc_server_check();
				if($_W['uid'] !=1 || $_W['username'] !=='fm453' || $settings['shouquan']['sufm453code'] != $serverinfos['authcode']){
					message('商城平台模式仅为作者本人开发测试时使用，设置失败，已经重置为默认单店版;请重新设置','referer','error');
				}
			}
		}else{
			$plattype='default';
		}
		$data['plattype']=$plattype;

		$plat=array();
		$fendianids=$_GPC['fendianids'].','.$_W['uniacid'];
		$temp_fendianids=explode(",",$fendianids);
		$temp_fendianids=array_unique($temp_fendianids);
		foreach($temp_fendianids as $index=>$fendianid) {
			if(intval($fendianid)<1) {
				unset($temp_fendianids[$index]);
			}
		}
		unset($index);
		$fendianids=implode(",",$temp_fendianids);
		$plat['fendianids']=$fendianids;

		$supplydianids=$_GPC['supplydianids'].','.$_W['uniacid'];
		$temp_supplydianids=explode(",",$supplydianids);
		$temp_supplydianids=array_unique($temp_supplydianids);
		foreach($temp_supplydianids as $index=>$supplydianid) {
			if(intval($supplydianid)<1) {
				unset($temp_supplydianids[$index]);
			}
		}
		unset($index);
		$supplydianids=implode(",",$temp_supplydianids);
		$plat['supplydianids']=$supplydianids;

		$blackids=$_GPC['blackids'].','.$_W['uniacid'];
		$temp_blackids=explode(",",$blackids);
		$temp_blackids=array_unique($temp_blackids);
		foreach($temp_blackids as $index=>$blackid) {
			if(intval($blackid)<1) {
				unset($temp_blackids[$index]);
			}
		}
		unset($index);
		$blackids=implode(",",$temp_blackids);
		$plat['blackids']=$blackids;

		$oauthid=intval($_GPC['oauthid']);
		if($oauthid>0) {
			$plat['oauthid']=$oauthid;
		}else{
			$plat['oauthid']=$_W['uniacid'];
		}

		$data['plat']=$plat;

		$noticeemail = $_GPC['noticeemail'];
			$data['noticeemail']=$noticeemail;
		$mobile = $_GPC['mobile'];
			$data['mobile']=$mobile;
		$tablepre = $_GPC['tablepre'];
		if(empty($tablepre)) {
			$tablepre=$_W['config']['db']['tablepre'];
		}
			$data['tablepre']=$tablepre;
		$free_dispatch =$_GPC['free_dispatch'];
			$data['free_dispatch']=$free_dispatch;//最低免邮金额；
		$free_dispatch_price =$_GPC['free_dispatch_price'];
			$data['free_dispatch_price']=$free_dispatch_price;//订单总价低于最低免邮金额时，需要支付的邮费/配送费；
		$manageropenids =$_GPC['manageropenids'];      //提交的信息需用英文逗号分隔，用时需要拆成数组
			$data['manageropenids']=$manageropenids;
		$kefuqq = $_GPC['kefuqq'];
			$data['kefuqq']=$kefuqq;
		$webkf_meiqia = $_GPC['webkf_meiqia'];
			$data['webkf_meiqia']=$webkf_meiqia;
		$appkf_meiqia = $_GPC['appkf_meiqia'];
			$data['appkf_meiqia']=$appkf_meiqia;
		$superadmin = $_GPC['superadmin'];
			$data['superadmin']=$superadmin;
		$navmenusnum=$_GPC['navmenusnum'];
			$data['navmenusnum']=$navmenusnum;
		$industry = $_GPC['industry'];
		if(!empty($industry)) {
			$data['industry']=$industry;
		}else{
			$data['industry']="ota";
		}
		$data['index']['temai_num']=$_GPC['temai_num'];
		$data['index']['url']=trim($_GPC['index_url']);
		$data['follow_url']=trim($_GPC['follow_url']);
		$data['member_regUrl']=trim($_GPC['member_regUrl']);
		$data['shoptype']=$_GPC['shoptype'];
		$data['force_wxAuth']=intval($_GPC['force_wxAuth']);
		$data['force_login']=intval($_GPC['force_login']);
		$data['force_follow']=intval($_GPC['force_follow']);
		$data['force_mcInfo']=intval($_GPC['force_mcInfo']);
		$data['force_agentInfo']=intval($_GPC['force_agentInfo']);

		$o_appstyle = $_GPC['appstyle'];
		$n_appstyle = $_GPC['appstyle_new'];
		$o_shopstyle = $_GPC['fm453style'];
		$n_shopstyle = $_GPC['fm453style_new'];

		if(!empty($n_appstyle) & $n_appstyle != 'default/' & $o_appstyle=='other') {
			if($settings['onoffs']['isappstyle'] ==1) {
				$data['appstyle']=$n_appstyle;
			}else{
				message('您尚未开启嗨旅行商城的自定义前台风格模板功能，请到模块的开关设置处进行设置','','error');
			}
		}else {
			$data['appstyle']='default/';
		}
		if(!empty($n_shopstyle) & $n_shopstyle != 'web/default/' & $o_shopstyle=='other') {
			if($settings['onoffs']['isfm453style'] ==1) {
				if($_W['isfounder']) {
					$data['shopstyle']=$n_shopstyle;
				}else{
					message('您不是站点管理员，无法进行商城后台风格的设置','','error');
				}
			}else{
				message('您尚未开启嗨旅行商城的自定义后台风格模板功能，请到模块的开关设置处进行设置','','error');
			}
		}else{
			$data['shopstyle']='web/default/';
		}

		$record = array();
		$record['value']=$data;
		$record['status']='127';
		$record=iserializer($record);
		$result=fmMod_setting_save($record,'',$_W['uniacid']);
		if($result['result']) {
			$dologs=array(
				'url'=>$_W['siteurl'],
				'description'=>'模块基础设置',
				'addons'=>$data
			);
			fmMod_log_record($platid,$_W['uniacid'],$_W['uid'],$fanid,$openid,'fm453_shopping_settings',$id,'modify',$dologs);
			unset($dologs);
			message('模块设置保存成功','referer','success');
		}else{
			message('模块设置保存失败,原因：'.$result['msg'],'referer','error');
		}
	}
}
