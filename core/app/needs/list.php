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
 * @remark 有求必应表单列表页
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;
load()->func('tpl');
fm_load()->fm_model('needs');
fm_load()->fm_func('wechat');//微信定义管理
fm_load()->fm_func('share');//链接分享管理
fm_load()->fm_func('view'); 	//浏览量处理

$id=intval($_GPC['id']);
if($id<=0) {
	message('未指定预约表单，请联系管理员','','info');
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
$pagename = '我的预约列表';

$uniacid=$_W['uniacid'];
$plattype=$settings['plattype'];
$platids = fmFunc_getPlatids();//获取平台模式关联的公众号商户ID参数
$platid=$_W['uniacid'];
$oauthid=$platids['oauthid'];
$fendianids=$platids['fendianids'];
$supplydianids=$platids['supplydianids'];
$blackids=$platids['blackids'];

$share_user=$_GPC['share_user'];
$shareid = intval($_GPC['shareid']);
$lastid = intval($_GPC['lastid']);
$currentid = intval($_W['member']['uid']);
$fromplatid = intval($_GPC['fromplatid']);
$from_user = $_W['openid'];
$url_condition="";
$direct_url = fm_murl($do,$ac,$operation,array('id'=>$id));
$pindex=max(1,intval($_GPC['page']));
$psize=(intval($_GPC['psize'])>10) ? intval($_GPC['psize']) : 10;//最少显示10条主数据

//平台模式处理
require_once FM_PUBLIC.'plat.php';
$supplydians=explode(',',$supplydianids);//字符串转数组
$supplydians=array_filter($supplydians);//数组去空

//按平台模式前置筛选条件
$condition=' WHERE ';
$params=array();
require_once FM_PUBLIC.'forsearch.php';
$condition .= ' AND `deleted` = :deleted';
$params[':deleted'] = 0;

$showorder=" ORDER BY uniacid ASC,  displayorder DESC ,id DESC";
$limit=" LIMIT ".($pindex-1)*$psize.",".$psize;
$templates=fmMod_needs_tpl();
$cycles=array(
	'day'=>'天',
	'month'=>'周',
	'hour'=>'时',
	'minute'=>'分'
);

//会员信息
$_FM['member']['info']['avatar']= ($_FM['member']['info']['avatar']) ? $_FM['member']['info']['avatar'] : $appsrc.'images/user-photo.png';

//会员自定义设置
$mine_settings=$_FM['member']['settings'];
$mine_settings['onoffs']['notify']=1;
$mine_settings['onoffs']['nodisturb']=2;
$mine_settings['onoffs']['tip_qiandao']=1;

//开始处理表单信息
$templates=fmMod_needs_tpl();
$uploadurl=fm_murl('ajax','file','upload',array());//图片上传地址

//取指定预约类型的相关参数
$needs=pdo_fetch("SELECT * FROM ".tablename('fm453_shopping_needs')." WHERE id=:id ",array(':id'=>$id));
$settings['needs']=fmMod_needs_params($id);
$kefuphone = ($needs['kefuphone']) ? $needs['kefuphone'] : $settings['brands']['phone'];
$needs['content']= htmlspecialchars_decode($needs['content']);
$needs['status'] = intval($needs['status']);
//$pagename=$needs['title'];

//配置核销员
	$_FM['appweb']['managers']=array();
	$FM_admins = $_FM['settings']['manageropenids'];
	if($needs['notice_openid']) {
		$FM_admins .= ','.$needs['notice_openid'];
	}
	$FM_admins = explode(',',$FM_admins);
	$FM_admins = array_unique($FM_admins);
	$_FM['appweb']['managers']=$FM_admins;
	$isManager = false;
	if(in_array($_W['openid'],$_FM['appweb']['managers'])) {
	$isManager = true;
}

//取预约列表
$needs_order = array();
$condition='';
$template=$needs['template'];
$cols = array();
$fields = array();
require_once MODULE_ROOT.'/template/mobile/'.$appstyle.'needs'.'/detail/'.$template.'/fields.php';
$cols['default']=array(
	'setfor'=>'会员',
	'name'=>'姓名',
	'mobile'=>'手机'
);
$params=array();

if(!in_array($_W['openid'],$_FM['appweb']['managers'])) {
	$condition .="AND setfor = :setfor";
	$params[":setfor"] =  $currentid;
}

	$isbytime=intval($_GPC['isbytime']);
	if (!empty($_GPC['date'])) {
		$starttime = strtotime($_GPC['date']['start']);
		$endtime = strtotime($_GPC['date']['end']) + 86399;
	} else {
		$starttime = strtotime('-3 month');
		$endtime = time();
	}
	if($isbytime) {
		$condition .= " AND `createtime` >= :starttime AND `createtime` < :endtime";
		$params[':starttime'] = $starttime;
		$params[':endtime'] = $endtime;
	}
	$keyword = trim($_GPC['keyword']);
	if (!empty($keyword)) {
		if(!in_array($_W['openid'],$_FM['appweb']['managers'])) {
			$temp_sns = pdo_fetchall("SELECT sn FROM " . tablename('fm453_shopping_needs_data') . " WHERE nid = :nid AND `value` LIKE :value AND setfor = :setfor " , array(':nid'=>$id,':value'=>'%'.$keyword.'%',':setfor'=>$currentid));
		}else{
			$temp_sns = pdo_fetchall("SELECT sn FROM " . tablename('fm453_shopping_needs_data') . " WHERE nid = :nid AND `value` LIKE :value " , array(':nid'=>$id,':value'=>'%'.$keyword.'%'));
		}

		$_temp_sns ="(";
		foreach($temp_sns as $t_s){
			$_temp_sns .= $t_s['sn'].",";
		}
		$_temp_sns .="'')";
		$condition .= " AND (";
		$condition .= " sn IN ".$_temp_sns;
		$condition .= " OR ";
		$condition .= " `setfor` = :setfor";
		$condition .= " )";
		$params[':setfor'] = intval($keyword);
	}

	$status = empty($_GPC['status']) ? 0 : intval($_GPC['status']);
	$status = intval($status);
	if ($status != 0) {
		$condition .= " AND `status` = :status";
		$params[':status'] = $status;
	}

	$showorder=" ORDER BY createtime DESC ";
	$params[':nid']=$id;
	$needs_sn = pdo_fetchall("SELECT sn FROM " . tablename('fm453_shopping_needs_data') . " WHERE nid=:nid " . $condition . $showorder, $params,'sn');

	$needs_data = array();
	if($needs_sn) {
		foreach($needs_sn as $key=>$_sn){
			$params[':sn']=$key;
			$needs_form = pdo_fetchall("SELECT * FROM " . tablename('fm453_shopping_needs_data') . " WHERE nid=:nid  AND sn =:sn ", array(':nid'=>$id,':sn'=>$key),'title');
			$_data = array();
			if(!empty($needs_form)){
		      foreach($needs_form as $k => $form){
			     $_data[$k] = is_serialized($form['value']) ? iunserializer($form['value']) : $form['value'];
		      }
	       }
	       $needs_data[$key] = $_data;
		}
	}

	$list=array();
	$keys=array();
	$users=$setfors=array();

	if(is_array($needs_data) && !empty($needs_data)) {
		foreach($needs_data as $key => $dvalue){
				$keys[$key]=$key;
				$setfor=$dvalue['setfor'];
						$setfors[$setfor]=$setfor;
				$list[$key]=array();
				$list[$key]['setfor']=$setfor;
				$list[$key]['status']=$dvalue['status'];
				$list[$key]['createtime']=$dvalue['createtime'];
				$_temp = fmMod_member_query($setfor);
				$list[$key]['user_info']=$_temp['data'];
				$list[$key]['link'] = ($isManager) ? fm_murl('appwebneeds','detail','index',array('id'=>$id,'sn'=>$key)) : fm_murl('needs','detail','index',array('id'=>$id,'sn'=>$key));		//详情链接

                foreach($cols[$needs['template']] as $title => $text){
				    if(isset($dvalue[$title])) {
				        $list[$key][$title] = $dvalue[$title];
				    }
                }
		}
	}

	$total=count($list);
	$pager = pagination($total, $pindex, $psize);

//自定义微信分享内容
$_share = array();
$_share['title'] = $pagename.'|'.$_W['account']['name'];
$_share['link'] = fm_murl($do,$ac,$operation,array('id'=>$id,'isshare'=>1));
$_share['link'] = $_share['link'].$url_condition;
$_share['imgUrl'] = tomedia($shareimg);
$_share['desc'] = $needs['description'];

if($operation=='index') {
	if(!empty($shareid)){
		fmFunc_share_click($lastid,$shareid,$currentid,$share_user);
	}

	//包含的模板文件
	include $this->template($appstyle.$do.'/453');
}
elseif($operation=='post') {

}
