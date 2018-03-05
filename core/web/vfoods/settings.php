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
 * @remark 微餐饮配置；
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;

load()->func('tpl');
load()->model('account');//加载公众号函数
fm_load()->fm_model('category'); //分类管理模块

//加载风格模板及资源路径
$fm453style    = fmFunc_ui_shopstyle();
$fm453resource =FM_RESOURCE;

//入口判断
$routes        =fmFunc_route_web();
$routes_do     =fmFunc_route_web_do();
$do            = $_GPC['do'];
$ac            =$_GPC['ac'];
$all_ac        =fmFunc_route_web_ac($do);
$operation     = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$all_op=fmFunc_route_web_op_single($do,$ac);
if(!in_array($ac,$all_ac) || !in_array($operation,$all_op)){
	// die(message('非法访问，请通过有效路径进入！'));
}

fmMod_privilege_adm();//检查用户授权

//开始操作管理
$shopname            =$settings['brands']['shopname'];
$shopname            = !empty($shopname) ? $shopname :FM_NAME_CN;
$_W['page']['title'] = $shopname.$routes[$do]['title'];
$direct_url          =fm_wurl($do,$ac,$operation,'');
$pindex              =max(1,intval($_GPC['page']));
$psize               =(intval($_GPC['psize'])>10) ? intval($_GPC['psize']) : 10;//最少显示10条主数据

$uniacid             =$_W['uniacid'];
$plattype            =$settings['plattype'];
$platids             = fmFunc_getPlatids();//获取平台模式关联的公众号商户ID参数
$oauthid             =$platids['oauthid'];
$fendianids          =$platids['fendianids'];
$supplydianids       =$platids['supplydianids'];
$blackids            =$platids['blackids'];

//平台模式处理
include_once FM_PUBLIC.'plat.php';
$supplydians        =explode(',',$supplydianids);//字符串转数组
$supplydians        =array_filter($supplydians);//数组去空

//按平台模式前置筛选条件
$condition          =' WHERE ';
$params             =array();
include_once FM_PUBLIC.'forsearch.php';
$condition          .= ' AND `deleted` = :deleted';
$params[':deleted'] = 0;

fm_load()->fm_func('foods'); //获取微餐饮函数
fm_load()->fm_func('mail'); //获取邮件函数
$category = pdo_fetch("SELECT * FROM ".tablename('fm453_vfoods_sms')." WHERE uniacid = '{$_W['uniacid']}'");

$mc_groups=mc_groups($_W['uniacid']);//指定公号的会员分组

if($operation == "links"){
	$links = array();
	$links[] = array('title'=>'手机点餐主入口（选择下单模式）','url'=>fm_murl('vfoods','index','index',array()));
	$links[] = array('title'=>'微餐饮会员中心','url'=>fm_murl('member','index','index',array('state'=>'vfoods')));
	$links[] = array('title'=>'微餐饮订单列表','url'=>fm_murl('vfoods','myorder','index',array()));
	$links[] = array('title'=>'外卖模式点餐主入口','url'=>fm_murl('vfoods','waimai','index',array()));
	$links[] = array('title'=>'自取模式下单主入口','url'=>fm_murl('vfoods','take','index',array()));
	$links[] = array('title'=>'堂食模式下单主入口','url'=>fm_murl('vfoods','tangshi','index',array()));
	$links[] = array('title'=>'订单核销入口','url'=>fm_murl('appwebvfoods','order','index',array()));
	$links[] = array('title'=>'商家管理手机端入口','url'=>fm_murl('appwebvfoods','shop','index',array()));
}
elseif($operation == "emailsend"){
	if(empty($category['email']) || empty($category['emailpsw']) || empty($category['smtp'])){
		message('请先填写邮箱号、密码、SMTP服务器并提交。', fm_wurl($do,$ac,'',array()), 'error');}
	else{
		fmFunc_mail_send('微餐饮测试邮件','欢迎使用微餐饮，邮件接口已经可以使用。',$category['email'],$category['smtp'],$category['email'],$category['emailpsw']);
		message('若'.$category['email'].'能收到邮件，说明接口设置成功。', fm_wurl($do,$ac,'',array()), 'success');
	}
}
else if($operation == "smssend"){
	if(empty($category['smsnum']) || empty($category['smspsw']) || empty($category['smstest'])){
		message('请先填写短信接口账号、短信接口密码、测试手机号并提交。', fm_wurl($do,$ac,'',array()), 'error');}
	else{
		fmFunc_foods_sendSMS($category['smsnum'],$category['smspsw'],$category['smstest'],'欢迎使用聚风微餐饮，接口设置成功。');
		message('若'.$category['smstest'].'能收到短信，说明接口设置成功。', fm_wurl($do,$ac,'',array()), 'success');
	}
}
else if($operation == "transfer" && checksubmit('token')){
	$sql = "
        ";
	pdo_query($sql);
	message('数据迁移完成。', fm_wurl($do,$ac,'transfer',array()), 'success');
}
elseif($operation == "display"){
	$settings['vfoods']['basic']['managers'] = isset($settings['vfoods']['basic']['managers']) ? $settings['vfoods']['basic']['managers'] : $category['managers'];

	if(checksubmit()){
		$data = array(
			// 'smsnum' => trim($_GPC['smsnum']),
			// 'smspsw' => trim($_GPC['smspsw']),
			// 'smstest' => trim($_GPC['smstest']),
			'managers' => trim($_GPC['managers']),
			'isxianfu' => intval($_GPC['is_xianfu']),
			'is_sms_send' => intval($_GPC['is_sms_send']),
			'mobile' => trim($_GPC['mobile']),
		);
		$setfor='vfoods';
		$record = array();
		$record['value']=array('basic'=>$data);
		$record['status']='127';
		$record=iserializer($record);
		$result=fmMod_setting_save($record,$setfor,$_W['uniacid']);
		if($result['result']) {
			$dologs=array(
				'url'=>$_W['siteurl'],
				'description'=>'微餐饮基础设置',
				'addons'=>array('basic'=>$data)
			);
			fmMod_log_record($platid,$_W['uniacid'],$_W['uid'],$fanid,$openid,'fm453_shopping_settings',$id,'modify',$dologs);
			unset($dologs);
			message('微餐饮基础设置更新成功','referer','success');
		}
		else{
			message('微餐饮基础设置更新失败，原因：'.$result['msg'],'','error');
		}
	}
}
elseif($operation == "waimai"){
	$waimai_fanwei = '';
	if(is_array($settings['vfoods']['waimai']['fanwei'])){
		foreach($settings['vfoods']['waimai']['fanwei'] as $k=>$v){
			$waimai_fanwei .="\n".$v;
		}
		$waimai_fanwei = trim($waimai_fanwei);
	}
	$waimai_mc = '';

	if(is_array($settings['vfoods']['waimai']['mc'])){
		foreach($settings['vfoods']['waimai']['mc'] as $k=>$v){
			$waimai_mc .="\n".$v;
		}
		$waimai_mc = trim($waimai_mc);
	}

	if(checksubmit()){
		$fanwei = trim($_GPC['waimai_fanwei']);
		$isfanwei = intval($_GPC['is_waimai_fanwei']);

		$fanweis = explode("\n",$fanwei);
		foreach($fanweis as &$_fw){
			$_fw = trim($_fw);
		}
		$length = count($fanweis );	//回车数
		$fanwei = nl2br($fanwei);//回车换成换行

		$mc = trim($_GPC['waimai_mc']);
		$ismc = intval($_GPC['is_waimai_mc']);

		$mcs = explode("\n",$mc);
		foreach($mcs as &$_fw){
			$_fw = trim($_fw);
		}
		$length = count($mcs );	//回车数
		$mc = nl2br($mc);//回车换成换行

		$waimai=array(
			'isfanwei' => $isfanwei,
			'fanwei' => $fanweis,
			'ismc' => $ismc,
			'mc' => $mcs,
		);
		$setfor='vfoods';
		$record = array();
		$record['value']=array('waimai'=>$waimai);
		$record['status']='127';
		$record=iserializer($record);
		$result=fmMod_setting_save($record,$setfor,$_W['uniacid']);
		if($result['result']) {
			$dologs=array(
				'url'=>$_W['siteurl'],
				'description'=>'微餐饮外卖设置',
				'addons'=>array('waimai'=>$waimai)
			);
			fmMod_log_record($platid,$_W['uniacid'],$_W['uid'],$fanid,$openid,'fm453_shopping_settings',$id,'modify',$dologs);
			unset($dologs);
			message('微餐饮外卖设置成功','referer','success');
		}
		else{
			message('微餐饮外卖设置失败，原因：'.$result['msg'],'','error');
		}
	}
}
elseif($operation == "tangshi"){
	$tangshi_fanwei = '';
	if(is_array($settings['vfoods']['tangshi']['fanwei'])){
		foreach($settings['vfoods']['tangshi']['fanwei'] as $k=>$v){
			$tangshi_fanwei .="\n".$v;
		}
		$tangshi_fanwei = trim($tangshi_fanwei);
	}
	$tangshi_mc = '';
	if(is_array($settings['vfoods']['tangshi']['mc'])){
		foreach($settings['vfoods']['tangshi']['mc'] as $k=>$v){
			$tangshi_mc .="\n".$v;
		}
		$tangshi_mc = trim($tangshi_mc);
	}

	if(checksubmit()){
		$fanwei = trim($_GPC['tangshi_fanwei']);
		$isfanwei = intval($_GPC['is_tangshi_fanwei']);

		$fanweis = explode("\n",$fanwei);
		foreach($fanweis as &$_fw){
			$_fw = trim($_fw);
		}
		$length = count($fanweis );	//回车数
		$fanwei = nl2br($fanwei);//回车换成换行

		$mc = trim($_GPC['tangshi_mc']);
		$ismc = intval($_GPC['is_tangshi_mc']);

		$mcs = explode("\n",$mc);
		foreach($mcs as &$_fw){
			$_fw = trim($_fw);
		}
		$length = count($mcs );	//回车数
		$mc = nl2br($mc);//回车换成换行

		$tangshi=array(
			'isfanwei' => $isfanwei,
			'fanwei' => $fanweis,
			'ismc' => $ismc,
			'mc' => $mcs,
		);
		$setfor='vfoods';
		$record = array();
		$record['value']=array('tangshi'=>$tangshi);
		$record['status']='127';
		$record=iserializer($record);
		$result=fmMod_setting_save($record,$setfor,$_W['uniacid']);
		if($result['result']) {
			$dologs=array(
				'url'=>$_W['siteurl'],
				'description'=>'微餐饮堂食模式设置',
				'addons'=>array('tangshi'=>$tangshi)
			);
			fmMod_log_record($platid,$_W['uniacid'],$_W['uid'],$fanid,$openid,'fm453_shopping_settings',$id,'modify',$dologs);
			unset($dologs);
			message('微餐饮堂食模式设置成功','referer','success');
		}
		else{
			message('微餐饮堂食模式设置失败，原因：'.$result['msg'],'','error');
		}
	}
}
elseif($operation == "ziqu"){
	$ziqu_fanwei = '';
	if(is_array($settings['vfoods']['ziqu']['fanwei'])){
		foreach($settings['vfoods']['ziqu']['fanwei'] as $k=>$v){
			$ziqu_fanwei .="\n".$v;
		}
		$ziqu_fanwei = trim($ziqu_fanwei);
	}
	$ziqu_mc = '';
	if(is_array($settings['vfoods']['ziqu']['mc'])){
		foreach($settings['vfoods']['ziqu']['mc'] as $k=>$v){
			$ziqu_mc .="\n".$v;
		}
		$ziqu_mc = trim($ziqu_mc);
	}

	if(checksubmit()){
		$fanwei = trim($_GPC['ziqu_fanwei']);
		$isfanwei = intval($_GPC['is_ziqu_fanwei']);

		$fanweis = explode("\n",$fanwei);
		foreach($fanweis as &$_fw){
			$_fw = trim($_fw);
		}
		$length = count($fanweis );	//回车数
		$fanwei = nl2br($fanwei);//回车换成换行

		$mc = trim($_GPC['ziqu_mc']);
		$ismc = intval($_GPC['is_ziqu_mc']);

		$mcs = explode("\n",$mc);
		foreach($mcs as &$_fw){
			$_fw = trim($_fw);
		}
		$length = count($mcs );	//回车数
		$mc = nl2br($mc);//回车换成换行

		$ziqu=array(
			'isfanwei' => $isfanwei,
			'fanwei' => $fanweis,
			'ismc' => $ismc,
			'mc' => $mcs,
		);
		$setfor='vfoods';
		$record = array();
		$record['value']=array('ziqu'=>$ziqu);
		$record['status']='127';
		$record=iserializer($record);
		$result=fmMod_setting_save($record,$setfor,$_W['uniacid']);
		if($result['result']) {
			$dologs=array(
				'url'=>$_W['siteurl'],
				'description'=>'微餐饮自取模式设置',
				'addons'=>array('ziqu'=>$ziqu)
			);
			fmMod_log_record($platid,$_W['uniacid'],$_W['uid'],$fanid,$openid,'fm453_shopping_settings',$id,'modify',$dologs);
			unset($dologs);
			message('微餐饮自取模式设置成功','referer','success');
		}
		else{
			message('微餐饮堂食模式设置失败，原因：'.$result['msg'],'','error');
		}
	}
}
elseif($operation == "diancantypes"){
	$diancantypes = array();
	$diancantypes = $settings['vfoods']['dingcantypes'];
	if(!$diancantypes){
		$diancantypes = fmFunc_foods_dingcantypes($simple=false);
	}

	if(checksubmit()){
		$tangshi = $ziqu = $waimai = array();
		foreach(array('title','icon','des','displayorder') as $v){
			$tangshi[$v] = trim($_GPC['tangshi_'.$v]);
			$ziqu[$v] = trim($_GPC['ziqu_'.$v]);
			$waimai[$v] = trim($_GPC['waimai_'.$v]);
		}
		$tangshi['id'] = 'tangshi';
		$ziqu['id'] = 'take';
		$waimai['id'] = 'waimai';

		fm_load()->fm_func('array');
		$_displayorder = fmFunc_array_multisort($multi_array=array($tangshi,$waimai,$ziqu),$sort_key='displayorder',$sort=SORT_DESC);
		$displayorder = array();
		foreach($_displayorder as $k=>$v){
			$displayorder[] = $v['id'];
		}

		$types=array(
			'tangshi' => $tangshi,
			'take' => $ziqu,
			'waimai' => $waimai,
			'displayorder'=>$displayorder
		);
		$setfor='vfoods';
		$record = array();
		$record['value']=array('dingcantypes'=>$types);
		$record['status']='127';
		$record=iserializer($record);
		$result=fmMod_setting_save($record,$setfor,$_W['uniacid']);
		if($result['result']) {
			$dologs=array(
				'url'=>$_W['siteurl'],
				'description'=>'微餐饮订单类型设置',
				'addons'=>array('dingcantypes'=>$types)
			);
			fmMod_log_record($platid,$_W['uniacid'],$_W['uid'],$fanid,$openid,'fm453_shopping_settings',$id,'modify',$dologs);
			unset($dologs);
			message('微餐饮订单类型设置成功','referer','success');
		}
		else{
			message('微餐饮订单类型设置失败，原因：'.$result['msg'],'','error');
		}
	}
}
include $this->template($fm453style.$do.'/453');
