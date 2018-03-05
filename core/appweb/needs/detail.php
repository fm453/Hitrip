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
 * @remark 核销端-有求必应
 */
defined('IN_IA') or exit('Access Denied');
global $_W, $_GPC,$_FM;
load()->func('tpl');
fm_load()->fm_model('needs');
if(intval($_GPC['i'])<=0) {
	$_W['uniacid']=1;
	$_GPC['i']=1;
}
$id=intval($_GPC['id']);
if($id<=0) {
	message('没有指定表单ID，请核对您访问的链接！');
}
$sn=$_GPC['sn'];
if(!$sn) {
	message('没有指定数据编号SN，请核对您访问的链接！');
}
fm_load()->fm_func('share');//链接分享管理
fm_load()->fm_func('server'); //授权服务器
fm_load()->fm_func('fans'); //粉丝处理函数库
fm_load()->fm_func('tables'); //数据表函数
fm_load()->fm_func('qrcode'); //二维码处理
fm_load()->fm_model('log'); //日志模块
fm_load()->fm_func('msg');//消息通知前置函数
fm_load()->fm_model('notice');//消息通知模块
fm_load()->fm_model('member');//会员管理模块
fm_load()->fm_model('shopcart'); //购物车模块
fm_load()->fm_func('ui');//页面视图
fm_load()->fm_func('tpl');//页面代码块
fm_load()->fm_func('template');//页面模板调用
fm_load()->fm_func('data');//统一数据处理方法
fm_load()->fm_func('market');//营销管理
fm_load()->fm_func('view'); 	//浏览量处理
fm_load()->fm_func('wechat');//微信定义管理
fm_load()->fm_func('mobile'); 	//手机号处理
fm_load()->fm_func('bankcard'); 	//银行卡处理
fm_load()->fm_func('pay');	//支付后处理
fm_load()->fm_func('api');	//云数据接口管理

//加载模块配置参数
$settings = fmMod_settings_all();//全局加载配置
$settings['appstyle']= ($settings['appstyle']=='mui/') ? 'default/' : $settings['appstyle'];
$_FM['settings']=$settings;
//加载风格模板及资源路径
$appstyle = empty($settings['appstyle']) ? FM_APPSTYLE : $settings['appstyle'];
$appsrc =MODULE_URL.'template/mobile/'.$appstyle.'453/';
$htmlsrc =MODULE_URL.'template/mobile/'.$appstyle;
$fm453resource = FM_RESOURCE;

//入口判断
$do= 'needs';
$ac=$_GPC['ac'];
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'index';
//开始操作管理
$shopname=$settings['brands']['shopname'];
$shopname = !empty($shopname) ? $shopname :FM_NAME_CN;
$pagename = '核销中心-有求必应';

$uniacid=$_W['uniacid'];
$plattype=$settings['plattype'];
$platids= fmFunc_getPlatids();//获取平台模式关联的公众号商户ID参数
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

$shareid = intval($_GPC['shareid']);
$lastid = intval($_GPC['lastid']);
$currentid = intval($_W['member']['uid']);
$fromplatid = intval($_GPC['fromplatid']);
$from_user = $_W['openid'];
$url_condition="";
$direct_url = fm_murl($do,$ac,$operation,array('id'=>$id,'sn'=>$sn));

$FM_member = fmMod_member_query($currentid);
$FM_member = $FM_member['data'];

//会员自定义设置
$mine_settings=$_FM['member']['settings'];
$mine_settings['onoffs']['notify']=1;
$mine_settings['onoffs']['nodisturb']=2;
$mine_settings['onoffs']['tip_qiandao']=1;

//开始处理表单信息
$templates=fmMod_needs_tpl();
//开始根据规则进行前端个别场景处理
$needs=pdo_fetch("SELECT * FROM ".tablename('fm453_shopping_needs')." WHERE id=:id ",array(':id'=>$id));
//前台权限判断
$_FM['appweb']['managers']=array();
$FM_admins = $_FM['settings']['manageropenids'];
if($needs['notice_openid']) {
	$FM_admins .= ','.$needs['notice_openid'];
}
$FM_admins = explode(',',$FM_admins);
$FM_admins = array_unique($FM_admins);
$_FM['appweb']['managers']=$FM_admins;

$openid = isset($_W['openid']) ? true : false;
if(!in_array($_W['openid'],$_FM['appweb']['managers']) && $openid) {
	message('非法访问!','','error');  //去掉了无登陆状态下的核销访问限制（临时）
}
$needs['content']= htmlspecialchars_decode($needs['content']);
$template=$needs['template'];

$fields=array();
require MODULE_ROOT.'/template/mobile/'.$appstyle.$do.'/detail/'.$template.'/fields.php';

$needs_form=pdo_fetchall("SELECT * FROM ".tablename('fm453_shopping_needs_data')." WHERE nid=:nid AND sn=:sn",array(':nid'=>$id,':sn'=>$sn),'title');
	if(!empty($needs_form)){
		foreach($needs_form as $key=>$form){
			$bookerid = $needs_data['setfor']=intval($form['setfor']);
			//$needs_data[$key]=$form['value'];
			$needs_data[$key] = is_serialized($form['value']) ? iunserializer($form['value']) : $form['value'];
		}
	}
$booker = fmMod_member_query($bookerid)['data'];	//预约人
$needs_data['name']= !empty($needs_data['name']) ? $needs_data['name'] : $booker['nickname'];
$needs_data['mobile']= !empty($needs_data['mobile']) ? $needs_data['mobile'] : $booker['mobile'];
$needs_data['wxhao']= !empty($needs_data['wxhao']) ? $needs_data['wxhao'] : $booker['wxhao'];

$stars=$needs_data['stars'];

if($operation=='index') {
	fmMod_needs_order_check($id,$sn,$needs_data,$openid=$booker['openid']);
	include_once MODULE_ROOT.'/template/mobile/'.$appstyle.$do.'/detail/'.$template.'/extra.php';
	$qrcodeurl=$_W['siteroot']. 'attachment/qrcode_'.$_W['acid'].'.jpg';//从公众号的系统配置中提取二维码图片
	include $this->template($appstyle.'appweb/'.$do.'/453');

}elseif ($operation == 'post') {
	$template=$_GPC['template'];
	$timestamp = $_W['timestamp'];
	$data=array();
	$key='reply';
	$data[$key]['value']=$_GPC[$key];
	$data[$key]['status']=64;
	//提交信息
	if($needs_data['reply']){
		pdo_update('fm453_shopping_needs_data',$data[$key],array('sn'=>$sn,'title'=>$key,'nid'=>$id));
	}else{
		$data[$key]['nid']=$id;
		$data[$key]['setfor']=$bookerid;
		$data[$key]['title']=$key;
		$data[$key]['sn']=$sn;
		$data[$key]['createtime']=$_W['timestamp'];
		pdo_insert('fm453_shopping_needs_data',$data[$key]);
	}

	$username = $FM_member['nickname'];
	$mobile = $settings['brands']['phone'];
	$content= $data['reply']['value'];

	$WeAccount = fmFunc_wechat_weAccount();
	require_once MODULE_ROOT.'/core/msgtpls/tpls/task.php';
	require_once MODULE_ROOT.'/core/msgtpls/msg/task.php';
	//发任务处理通知
	$result=array();
	$postdata = $tplnotice_data['task']['form']['reply']['admin'];
	$result=fmMod_notice_tpl($postdata, $platid=NULL, $WeAccount);
	if($result['errno']==-1) {
		$noticedata = $notice_data['form']['reply']['admin'];
		$result=fmMod_notice($settings['manageropenids'],$noticedata,$platid=NULL, $WeAccount);
	}

	$noticedata = $notice_data['form']['reply']['booker'];
	$result=fmMod_notice($booker['openid'],$noticedata,$platid=NULL, $WeAccount);
	if($result['errno']==-1) {
		$postdata = $tplnotice_data['task']['form']['reply']['user'];	//预约人
		fmMod_notice_tpl($postdata, $platid=NULL, $WeAccount);
	}
	return $return;	//后台PHP响应使用
	exit($return);		//前端JS响应
}
