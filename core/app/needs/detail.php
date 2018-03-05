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
 * @remark 有求必应表单详情页
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;
load()->func('tpl');
fm_load()->fm_model('needs');
fm_load()->fm_func('wechat');
fm_load()->fm_func('share');//链接分享管理
fm_load()->fm_func('view'); 	//浏览量处理

$id=intval($_GPC['id']);
$sn=$_GPC['sn'];

//是否需要授权登陆
$checklogin=$_GPC['checklogin'];
if($checklogin) {
	checkauth();
}

$is_wexin = fmFunc_wechat_is();
if(!$is_weixin) {
	checkauth();
}

//加载风格模板及资源路径
$appstyle = empty($settings['appstyle']) ? FM_APPSTYLE : $settings['appstyle'];
$appsrc =MODULE_URL.'template/mobile/'.$appstyle.'453/';
$htmlsrc =MODULE_URL.'template/mobile/'.$appstyle;
$fm453resource = FM_RESOURCE;
//入口判断
$do= $_GPC['do'];
$ac=$_GPC['ac'];
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'index';
//开始操作管理
$shopname=$settings['brands']['shopname'];
$shopname = !empty($shopname) ? $shopname :FM_NAME_CN;
$pagename = '有求必应';

$uniacid=$_W['uniacid'];
$plattype=$settings['plattype'];
$platids = fmFunc_getPlatids();//获取平台模式关联的公众号商户ID参数
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
$direct_url = fm_murl($do,$ac,$operation,array('id'=>$id));
$_FM['member']['info']['avatar']= ($_FM['member']['info']['avatar']) ? $_FM['member']['info']['avatar'] : $appsrc.'images/user-photo.png';

//会员自定义设置
$mine_settings=$_FM['member']['settings'];
$mine_settings['onoffs']['notify']=1;
$mine_settings['onoffs']['nodisturb']=2;
$mine_settings['onoffs']['tip_qiandao']=1;

//定义当前非核销模式
$isNotHexiao = true;

//支付处理
$pricetype=array(
	//'jifen'=>array('title'=>'积分','icon'=>'fa fa-money'),
	'yue'=>array('title'=>'元','icon'=>'fa fa-cny'),
	//'kaquan'=>array('title'=>'余额','icon'=>'fa fa-ticket')
);
$defaultprice=array();
$defaultprice['jifen']=array('50','100','500');
$defaultprice['yue']=array('1','2','5','9','15','20','30','50','99');
$defaultprice['kaquan']=array('1','10','15');

//开始处理表单信息
$templates=fmMod_needs_tpl();
$uploadurl=fm_murl('ajax','file','upload',array());//图片上传地址
//开始根据规则进行前端个别场景处理
if($id==0 && !empty($sn)){
	$id = pdo_fetchcolumn("SELECT nid FROM ".tablename('fm453_shopping_needs_data')." WHERE sn=:sn ",array(':sn'=>$sn));
}

$needs = pdo_fetch("SELECT * FROM ".tablename('fm453_shopping_needs')." WHERE id=:id ",array(':id'=>$id));
//取表单参数设置
$settings['needs'] = fmMod_needs_params($id);
// var_dump($settings['needs']);
$kefuphone = ($settings['needs']['kefuphone']) ? $settings['needs']['kefuphone'] : $settings['brands']['phone'];

$needs['content']= htmlspecialchars_decode($needs['content']);
$needs['status'] = intval($needs['status']);
$pagename=$needs['title'];
$needs_data=array();

if($sn){
	$needs_form=pdo_fetchall("SELECT * FROM ".tablename('fm453_shopping_needs_data')." WHERE nid=:nid AND sn=:sn",array(':nid'=>$id,':sn'=>$sn),'title');
	if(!empty($needs_form)){
		foreach($needs_form as $key=>$form){
			$bookerid = $needs_data['setfor'] = intval($form['setfor']);
			$needs_data[$key] = is_serialized($form['value']) ? iunserializer($form['value']) : $form['value'];
		}
	}
}

$booker = fmMod_member_query($bookerid);	//预约人信息
$booker = $booker['data'];
$needs_data['name']= !empty($needs_data['name']) ? $needs_data['name'] : $_FM['member']['info']['nickname'];
$needs_data['mobile']= !empty($needs_data['mobile']) ? $needs_data['mobile'] : $_FM['member']['info']['mobile'];
$needs_data['sex']= !empty($needs_data['sex']) ? $needs_data['sex'] : $_FM['member']['info']['gender'];
$needs_data['wxhao']= !empty($needs_data['wxhao']) ? $needs_data['wxhao'] : $_FM['member']['info']['wxhao'];

$stars=$needs_data['stars'];
$shareimg= !empty($needs['thumb']) ? $needs['thumb'] : (!empty($_FM['member']['info']['avatar']) ? $_FM['member']['info']['avatar'] : $settings['brands']['logo']);
$needs['thumb'] = tomedia($needs['thumb']);
$needs['banner'] = !empty($needs['banner']) ? tomedia($needs['banner']) : $needs['thumb'];
$template = $needs['template'];
$settings['brands']['qrcode'] = tomedia("qrcode_".$_W['acid'].".jpg");

$available = ($needs['status']==1) ? true : false;

$fields=array();
require MODULE_ROOT.'/template/mobile/'.$appstyle.$do.'/detail/'.$template.'/fields.php';

//畅言评论初始化
$_FM['settings']['api']['changyan_appId'] = ($_FM['settings']['api']['changyan_appId']) ? $_FM['settings']['api']['changyan_appId'] : 'cysQrSTqK';
$_FM['settings']['api']['changyan_conf'] = ($_FM['settings']['api']['changyan_conf']) ? $_FM['settings']['api']['changyan_conf'] : 'prod_9e663cdf33cf715d33172b6cc0d0bb7f';

//前台权限判断
	$_FM['appweb']['managers']=array();
	$FM_admins = $_FM['settings']['manageropenids'];
	if($needs['notice_openid']) {
		$FM_admins .= ','.$needs['notice_openid'];
	}
	$FM_admins = explode(',',$FM_admins);
	$FM_admins = array_unique($FM_admins);
	$_FM['appweb']['managers']=$FM_admins;

//自定义微信分享内容
$_share = array();
$_share['title'] = $pagename.'|'.$_W['account']['name'];
$_share['link'] = fm_murl($do,$ac,$operation,array('id'=>$id,'isshare'=>1));
$_share['link'] = $_share['link'].$url_condition;
$_share['imgUrl'] = tomedia($shareimg);
$_share['desc'] = $needs['description'];

if($operation=='index') {
	if($sn){
		fmMod_needs_order_check($id,$sn,$needs_data,$openid=$booker['openid']);
	}
	$qrcodeurl=$_W['siteroot']. 'attachment/qrcode_'.$_W['acid'].'.jpg';//从公众号的系统配置中提取二维码图片
	//更新流量、链路统计
	fmFunc_view();
    //阅读数
	$nowViewNum=$needs['viewcount'];

	pdo_update('fm453_shopping_needs',array('viewcount'=>$nowViewNum+1),array('id'=>$id));

	if(!empty($shareid)){
		fmFunc_share_click($lastid,$shareid,$currentid,$share_user);
	}
	include MODULE_ROOT.'/template/mobile/'.$appstyle.$do.'/detail/'.$template.'/extra.php';//该文件顺序不可再上调，固定放在模板输出之上就可以了
	//包含的模板文件
	include $this->template($appstyle.$do.'/453');

}elseif($operation=='post') {
	$return =1;

	$settings['manageropenids']=$FM_admins;    //重组通知接收的管理员openid
	if(!empty($sn)){
		if(!in_array($_W['openid'],$_FM['appweb']['managers']) && $bookerid !=$currentid) {
			echo "您没有修改该数据的权限！";
			exit();
		}
		if($needs['status']==0) {
			echo "当前表单还未开启信息录入，请联系管理员或继续等待！";
			exit();
		}
	}

	$fields['default']=array(
		0=>'setfor',
		1=>'name',
		2=>'mobile',
		3=>'wxhao',
		4=>'sex',
		5=>'stars'
	);
	$timestamp = $_W['timestamp'];
	$newOrModify="new";
	if($sn) {
		$newOrModify="modify";
	}
	$data=array();
	$orderData=array();

	foreach($fields[$template] as $i=>$key){
		$data[$key]['value'] = $_GPC[$key];
		if(is_array($_GPC[$key])) {
		  $data[$key]['value'] = iserializer($_GPC[$key]);
		}
		$orderData[$key]=$data[$key]['value'];
		$data[$key]['status']=0;
		//提交信息
		if($sn) {
			$hasThisKey=pdo_fetch("SELECT id FROM ".tablename('fm453_shopping_needs_data')." WHERE `nid`= :nid AND `sn` = :sn AND `setfor` = :setfor AND `title` = :title ORDER BY createtime DESC",array(':nid'=>$id,':sn'=>$sn,':setfor'=>$bookerid,':title'=>$key));
			if($hasThisKey) {
				pdo_update('fm453_shopping_needs_data',$data[$key],array('sn'=>$sn,'title'=>$key,'nid'=>$id,'setfor'=>$bookerid));
			}else{
				$data[$key]['nid']=$id;
				$data[$key]['setfor']=$bookerid;
				$data[$key]['title']=$key;
				$data[$key]['sn']=$sn;
				$data[$key]['createtime']=$sn;
				pdo_insert('fm453_shopping_needs_data',$data[$key]);
			}
		}else{
			$data[$key]['nid']=$id;
			$data[$key]['setfor']=$currentid;
			$data[$key]['title']=$key;
			$data[$key]['sn']=$timestamp;
			$data[$key]['createtime']=$timestamp;
			pdo_insert('fm453_shopping_needs_data',$data[$key]);
		}

		//保存到会员个性设置(只对会员信息表单模型有效)
		if(in_array($_W['openid'],$_FM['appweb']['managers']) && $template=='MemberInfo'){
			$setfor = 'formdata';
			$key = $key;
			$value = $data[$key]['value'];

			$data=array();
			$data['status'] = 127;
			$data['title'] = $key;
			$data['value'] = $value;

			$result = array();
			$result = fmMod_member_settings_save($bookerid,$booker['openid'],$data,$setfor, $_W['uniacid']);
		}
	}
	if(!$sn) {
		$sn = $timestamp;
		$orderData['from_user']=$_W['openid'];
		$orderData['fromuid']=$currentid;
		$orderData['shareid']=$shareid;
	}else{
		$orderData['from_user']=$booker['openid'];
		$orderData['fromuid']=$booker['uid'];
	}
	$orderData['nid']=$id;
	$orderData['unacid']=$platid;
	$orderData['createtime']=$timestamp;
	fmMod_needs_order_check($id,$sn,$orderData,$openid=$booker['openid']);

	//至此，sn唯一确认
	$username = trim($_GPC['name']);
	$mobile = trim($_GPC['mobile']);
	$WeAccount = fmFunc_wechat_weAccount();
	require_once MODULE_ROOT.'/core/msgtpls/tpls/task.php';
	//发任务处理通知
	$result=array();
	$postdata = $tplnotice_data['task']['form'][$newOrModify]['admin'];
	$result=fmMod_notice_tpl($postdata, $platid=NULL, $WeAccount);
	if($result['errno']==-1) {
		require_once MODULE_ROOT.'/core/msgtpls/msg/task.php';
		$noticedata = $notice_data['form'][$newOrModify]['admin'];
		$result=fmMod_notice($settings['manageropenids'],$noticedata,$platid=NULL, $WeAccount);
	}

	$postdata = $tplnotice_data['task']['form'][$newOrModify]['user'];	//预约人
	fmMod_notice_tpl($postdata, $platid=NULL, $WeAccount);

	return $return;	//后台PHP响应使用
	exit($return);		//前端JS响应

}elseif($operation=='dianzan'){
	$return = array();
	$return['errorcode'] = 0;

    //判断是否有点赞记录
	$tablename = 'fm453_shopping_needs_data';
	$fields = '(id)';
	$sql = "SELECT ".$fields." FROM ".tablename($tablename);
	$condition = 'WHERE';
	$params = array();
	$condition .= ' `nid`= :nid';
	$params[':nid'] = $nid;
	if($sn){
	   $condition .= ' AND ';
	   $condition .= ' `sn` = :sn';
	   $params[':sn'] = $sn;
	}
	$condition .= ' AND ';
   $condition .= ' `setfor` = :setfor';
   $params[':setfor'] = $_W['openid'];

	$condition .= ' AND ';
   $condition .= ' `title` = :title';
   $params[':title'] = '%dianzan+%';

	if($disableDianzan) {
		$return['errorcode'] = $_DianzanError;
		switch($_DianzanError) {
			case -1:
				$return['msg'] = '您需要登陆之后才能点赞';
			break;
			case 1:
				$return['msg'] = '您已经点过赞了；当前内容不允许重复点赞';
			break;
			case 2:
				$return['msg'] = '您为当前内容的点赞次数已达上限了';
			break;
			default:
				$return['errorcode'] = -2;
				$return['msg'] = '本内容的点赞功能已关闭';
			break;
		}
		die(json_encode($return['errorcode']));
	}

	//点赞数
	$nowDzNum=$needs['dianzancount'];
	pdo_update('fm453_shopping_needs',array('dianzancount'=>$nowDzNum+1),array('id'=>$id));

	//发微信模板通知
	//重组通知接收的管理员openid
	$_FM['appweb']['managers']=array();
	$FM_admins = $_FM['settings']['manageropenids'];
	if($needs['notice_openid']) {
		$FM_admins .= ','.$needs['notice_openid'];
	}
	$FM_admins = explode(',',$FM_admins);
	$FM_admins = array_unique($FM_admins);
	$_FM['appweb']['managers']=$FM_admins;
	$settings['manageropenids']=$FM_admins;
	//开始发送
	require_once MODULE_ROOT.'/core/msgtpls/tpls/task.php';
	$postdata = $tplnotice_data['task']['form']['dianzan']['admin'];
	$result= fmMod_notice_tpl($postdata);
	if(!$result) {
		require MODULE_ROOT.'/core/msgtpls/msg/task.php';
		$postdata = $notice_data['form']['dianzan']['admin'];
		$result= fmMod_notice($settings['manageropenids'],$postdata);
	}

	$postdata = $tplnotice_data['task']['form']['dianzan']['user'];	//预约人
	fmMod_notice_tpl($postdata);

	//输出点赞处理结果
	die(json_encode($return['errorcode']));

}
elseif($operation=='join') {
    //接受邀请加入 TBD

    include MODULE_ROOT.'/template/mobile/'.$appstyle.$do.'/detail/'.$template.'/extra.php';//该文件顺序不可再下调，固定放在开始位置
	if($sn){
		fmMod_needs_order_check($id,$sn,$needs_data,$openid=$booker['openid']);
	}

	$data=array();
	$orderData=array();
	//判断是否已加入
	$tablename = 'fm453_shopping_needs_data';
	$fields = '(id)';
	$sql = "SELECT ".$fields." FROM ".tablename($tablename);
	$condition = 'WHERE';
	$params = array();
	$condition .= ' `nid`= :nid';
	$params[':nid'] = $nid;
	if($sn){
	   $condition .= ' AND ';
	   $condition .= ' `sn` = :sn';
	   $params[':sn'] = $sn;
	}
	$condition .= ' AND ';
   $condition .= ' `setfor` = :setfor';
   $params[':setfor'] = $_W['openid'];

	$condition .= ' AND ';
   $condition .= ' `title` = :title';
   $params[':title'] = 'ingroup';

   $showorder = ' ORDER BY createtime DESC';

	$hasJoined = pdo_fetch($sql.$condition.$showorder);
	if($hasJoined) {
		pdo_update('fm453_shopping_needs_data',$data[$key],array('sn'=>$sn,'title'=>$key,'nid'=>$id,'setfor'=>$bookerid));
	}else{
		$data['nid']=$id;
		$data['setfor']=$bookerid;
		$data['title']=$key;
		$data['sn']= ($sn) ? $sn : '';
		$data['createtime']=$_W['createtime'];
		pdo_insert('fm453_shopping_needs_data',$data[$key]);
	}

	$username = $needs_data['name'];
	$mobile = $needs_data['mobile'];

	//包含的模板文件
	include $this->template($appstyle.$do.'/453');
}
elseif($operation=='invite') {
    //邀请他人加入 TBD

	//包含的模板文件
	include $this->template($appstyle.$do.'/453');
}
