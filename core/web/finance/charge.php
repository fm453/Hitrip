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
 * @remark 财务充值管理；
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;

load()->func('tpl');
load()->model('account');//加载公众号函数
load()->model('mc');

//加载风格模板及资源路径

$fm453style = fmFunc_ui_shopstyle();
$fm453resource =FM_RESOURCE;

//入口判断
$routes=fmFunc_route_web();
$routes_do=fmFunc_route_web_do();
$do= $_GPC['do'];
$ac=$_GPC['ac'];
$all_ac=fmFunc_route_web_ac($do);
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$all_op=fmFunc_route_web_op_single($do,$ac);
if(!in_array($ac,$all_ac) || !in_array($operation,$all_op)){
	die(message('非法访问，请通过有效路径进入！'));
}

fmMod_privilege_adm();//检查用户授权

//开始操作管理
$shopname=$settings['brand']['shopname'];
$shopname = !empty($shopname) ? $shopname :FM_NAME_CN;
$_W['page']['title'] = $shopname.$routes[$do]['title'];
$direct_url=fm_wurl($do,$ac,$operation,'');
$pindex=max(1,intval($_GPC['page']));
$psize=(intval($_GPC['psize'])>5) ? intval($_GPC['psize']) : 5;//最少显示5条主数据

$uniacid=$_W['uniacid'];
$plattype=$settings['plattype'];
$platids=fmFunc_getPlatids();//获取平台模式关联的公众号商户ID参数
$platid=$_W['uniacid'];
$oauthid=$platids['oauthid'];
$fendianids=$platids['fendianids'];
$supplydianids=$platids['supplydianids'];
$blackids=$platids['blackids'];

$gtpltype = fmMod_goodstpl_type_get();//列出全部产品模型清单
$marketmodeltypes =fmFunc_market_types();
$countstatus=fmFunc_status_get('count');
$datatype= fmFunc_data_types();

//平台模式处理
include_once FM_PUBLIC.'plat.php';
$supplydians=explode(',',$supplydianids);//字符串转数组
$supplydians=array_filter($supplydians);//数组去空

//按平台模式前置筛选条件
$condition=' WHERE ';
$params=array();
include_once FM_PUBLIC.'forsearch.php';
//$condition .= ' AND `deleted` = :deleted';
//$params[':deleted'] = 0;

$limit=" LIMIT ".($pindex-1)*$psize.",".$psize;
	$credits = array(
		'all'=>'不限',
		'credit1' => '积分',
		'credit2' => '余额'
	);

if ($operation == 'display') {
	$uid=$_GPC['uid'];
 	$type = $_GPC['type'];
 	$status = $_GPC['status'];
	$starttime = strtotime($_GPC['starttime']);
	$endtime = strtotime($_GPC['endtime']);
	if(is_numeric($uid) && $uid>0) {
		$condition .=' AND  uid= :uid';
		$params[':uid']=$uid;
	}
	if($starttime && $endtime){
		if($starttime<$endtime) {
			$condition .=' AND createtime > :start';
			$condition .=' AND createtime < :end';
			$params[':start']=$starttime;
			$params[':end']=$endtime;
		}else{
			unset($starttime);
			unset($endtime);
		}
	}elseif(empty($starttime) && !empty($endtime)){
			$condition .=' AND createtime < :end';
			$params[':end']=$endtime;
	}elseif(!empty($starttime) && empty($endtime)){
			$condition .=' AND createtime > :start';
			$params[':start']=$starttime;
	}
	//不考虑前台类型及加减搜索的影响，计算一些总指标START
	if(!empty($credits)) {
		$data = array();
		foreach(array(
		'credit1' => '积分',
		'credit2' => '余额'
	) as $key => $li) {
			$data[$key]['add'] = round(pdo_fetchcolumn('SELECT SUM(num) FROM ' . tablename('mc_credits_record') .$condition.' AND  credittype = "'.$key.'"  AND num > 0',$params),2);
			$data[$key]['del'] = abs(round(pdo_fetchcolumn('SELECT SUM(num) FROM ' . tablename('mc_credits_record') . $condition.' AND  credittype = "'.$key.'" AND num < 0',$params),2));
			$data[$key]['end'] = $data[$key]['add'] - $data[$key]['del'];
		}
	}
//END
	if(!empty($type) && $type !='all') {
		$condition .=' AND  credittype= :credittype';
		$params[':credittype']=$type;
	}
	if(!empty($status) && $status !='code') {
		if($countstatus[$status]['value']==0) {
			$condition .=' AND  num > 0';
		}elseif($countstatus[$status]['value']==1) {
			$condition .=' AND  num < 0';
		}
	}
	$showorder =' ORDER BY createtime DESC ';
	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('mc_credits_record').$condition,$params);
	$pager = pagination($total, $pindex, $psize);
	$list = pdo_fetchall("SELECT * FROM ".tablename('mc_credits_record').$condition.$showorder.$limit,$params);
	foreach($list as &$item){
		$item['credittype']=$credits[$item['credittype']];
		$item['operator']=  fm_w_username($item['operator']);
	}
	unset($item);
	include $this->template($fm453style.$do.'/453');
 }
elseif($operation=='member'){
	$uid=$_GPC['uid'];
	if(!is_numeric($uid)){
		message('请选择会员！',fm_wurl('crm','member','display', array()), 'info');
	}
	//从粉丝表获取的数据
	$mapping_fans = pdo_fetch("SELECT * FROM " . tablename('mc_mapping_fans') . " WHERE `uid` = :uid", array(':uid' => $uid));
	$profile = fans_search($uid);
	if(!$profile){
		message('该会员资料不存在！',fm_wurl('crm','member','display', array()), 'success');
	}
	//获取会员近三个月的余额/积分日志
	$credits = array(
		'credit1' => '积分',
		'credit2' => '余额'
	);
	$starttime = strtotime('-3 month');
	$endtime = TIMESTAMP;
	$condition .=' AND  uid= :uid';
	$params[':uid']=$uid;
	$condition .=' AND createtime > :start';
	$condition .=' AND createtime < :end';
	$params[':start']=$starttime;
	$params[':end']=$endtime;
	$showorder =' ORDER BY createtime DESC ';
	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('mc_credits_record').$condition,$params);
	$pager = pagination($total, $pindex, $psize);
	$list = pdo_fetchall("SELECT * FROM ".tablename('mc_credits_record').$condition.$showorder.$limit,$params);
	foreach($list as &$item){
		$item['credittype']=$credits[$item['credittype']];
		$item['operator']=  fm_w_username($item['operator']);
	}

	if(checksubmit('charge_credit2')){
		$chargenum = $_GPC['credit2'];
		if(is_numeric($chargenum) && $chargenum !=0){
			if($_W[isfounder] ||$_W['username']==$settings['mainuser']){
				$result = mc_credit_update($uid, 'credit2', $chargenum, array($_W['uid'],$shopname.'系统充值'));
				if($result){
				$paylog=array(
					'type'=>'charge',
					'uniacid'=>$uniacid,
					'openid'=>$mapping_fans['openid'],
					'tid'=>date('Y-m-d H:i:s'),
					'fee'=>$chargenum,
					'module'=>'fm453_shopping',
					'tag'=>$shopname.'系统充值'.$chargenum.'元'
				);
				pdo_insert('core_paylog',$paylog);
				//写入操作日志START
			$dologs=array(
				'url'=>$_W['siteurl'],
				'description'=>'商城后台会员充值；',
				'addons'=>$paylog,
			);
			fmMod_log_record($platid,$_W['uniacid'],$_W['uid'],$fanid,$openid,'mc_members',$id,'update',$dologs);
			unset($dologs);
			//写入操作日志END
		//给管理员发个微信消息
			fmMod_notice($settings[manageropenids],array(
				'header'=>array('title'=>'事件通知','value'=>'会员充值'),
				'operator'=>array('title'=>'操作人','value'=>$_W['username'].'('.$_W['uid'].')'),
				'type'=>array('title'=>'充值类型','value'=>'系统余额(credit2)'),
				'num'=>array('title'=>'金额数量','value'=>$chargenum.'元'),
				'user'=>array('title'=>'用户','value'=>$username),
				'leftnum'=>array('title'=>'剩余','value'=>$profile['credit2']-$chargenum)
			));
			message('您已成功为'.$username.'增加了'.$chargenum.'元余额','referer','success');
		}
			}else {
				message('充值失败，您不具备此权限，请使用主工号操作','','false');
			}
		}
	}

	if(checksubmit('charge_credit1')){
		$chargenum = $_GPC['credit1'];
		if(is_numeric($chargenum) && $chargenum !=0){
			if($_W[isfounder] ||$_W['username']==$settings['mainuser']){
				$result = mc_credit_update($uid, 'credit1', $chargenum, array($_W['uid'],$shopname.'系统充值'));
				if($result){
				$paylog=array(
					'type'=>'charge',
					'uniacid'=>$uniacid,
					'openid'=>$mapping_fans['openid'],
					'tid'=>date('Y-m-d H:i:s'),
					'fee'=>$chargenum,
					'module'=>'fm453_shopping',
					'tag'=>$shopname.'系统充值'.$chargenum.'个积分'
				);
				pdo_insert('core_paylog',$paylog);
				//写入操作日志START
			$dologs=array(
				'url'=>$_W['siteurl'],
				'description'=>'商城后台会员充值；',
				'addons'=>$paylog,
			);
			fmMod_log_record($platid,$_W['uniacid'],$_W['uid'],$fanid,$openid,'mc_members',$id,'update',$dologs);
			unset($dologs);
			//写入操作日志END
		//给管理员发个微信消息
			fmMod_notice($settings[manageropenids],array(
				'header'=>array('title'=>'事件通知','value'=>'会员充值'),
				'operator'=>array('title'=>'操作人','value'=>$_W['username'].'('.$_W['uid'].')'),
				'type'=>array('title'=>'充值类型','value'=>'积分(credit1)'),
				'num'=>array('title'=>'积分数量','value'=>$chargenum.'积分'),
				'user'=>array('title'=>'用户','value'=>$username),
				'leftnum'=>array('title'=>'剩余','value'=>$profile['credit1']-$chargenum)
			));
			message('您已成功为'.$username.'增加了'.$chargenum.'个积分','referer','success');
		}
			}else {
				message('充值失败，您不具备此权限，请使用主工号操作','','false');
			}
		}
	}

	include $this->template($fm453style.$do.'/453');
}
elseif($operation=='detail'){
	$id=$_GPC['id'];
	if(empty($id)){
		message('请选择一条记录！',fm_wurl('finance','charge','display', array()), 'success');
	}
	$condition .= ' AND id = :id';
	$params[':id'] = $id;
	$item = pdo_fetch("SELECT * FROM ".tablename('mc_credits_record').$condition,$params);
	$item['credittype']=$credits[$item['credittype']];
	$item['operator']=  fm_w_username($item['operator']);

	include $this->template($fm453style.$do.'/453');
}
