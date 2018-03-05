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
 * @remark 系统会员管理；
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
$shopname=$settings['brand']['title'];
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
$mlist = pdo_fetchall("SELECT `name`,`title` FROM ".tablename('modules'),array(),'name');
if($operation=='display'){
	$keyword=trim($_GPC['keyword']);
	if($_GPC['openid_uid']){
	    $openid_uid=trim($_GPC['openid_uid']);
	    $uid=mc_openid2uid($openid_uid);
    }
	if($uid>0){
		$condition.=" AND uid = :uid ";
		$params[':uid']=$uid;
	}else{
		if(!empty($keyword)){
		$condition.=' AND ';
		$condition.='(';
		$condition.=' nickname LIKE :nickname ';
		$params[':nickname']='%'.$keyword.'%';
		$condition.=' OR ';
		$condition.=' realname LIKE :realname ';
		$params[':realname']='%'.$keyword.'%';
		$condition.=')';
		}
		$mobile=intval($_GPC['mobile']);
		if(!empty($mobile)){
		$condition.=" AND mobile LIKE :mobile ";
		$params[':mobile']='%'.$mobile.'%';
		}
		$shareid = is_numeric($_GPC['shareid']) ? $_GPC['shareid'] : '';
		if($shareid) {
			$condition.=" AND shareid = :shareid ";
			$params[':shareid'] = $shareid;
		}
	}
	$showorder=" ORDER BY uniacid ASC, uid DESC ";

	$total = pdo_fetchcolumn("SELECT COUNT(uid) FROM ".tablename('mc_members').$condition, $params);
	$pager = pagination($total, $pindex, $psize);
	$list = pdo_fetchall("SELECT * FROM ".tablename('mc_members').$condition.$showorder.$limit, $params);

	include $this->template($fm453style.$do.'/453');
}
elseif($operation=='modify'){
	$uid=$_GPC['uid'];
	if(empty($uid)){
		message('请选择会员！',fm_wurl($do,$ac,'display', array()), 'success');
	}
	$uniacid=$_W['uniacid'];
	$mc_fields=mc_fields();//系统会员表字段
	$mc_groups=mc_groups($profile['uniacid']);//指定公号的会员分组
	$mc_credit =mc_credit_fetch($uid);
	$genders=array(
		'0'=>'保密',
		'1'=>'男',
		'2'=>'女'
	);

	$result = fmMod_member_query($uid);
	$profile=$result['data'];
	if(!$profile){
		message('该会员资料不存在！',fm_wurl($do,$ac,'display', array()), 'success');
	}
	//在商城的会员表中查找或插入该会员的分销代理身份
	$mid=fmMod_member_check($uid);

	$profile['followtime']=date('Y年m月d日 H:i:s 星期w',$profile['followtime']);
	$profile['updatetime']=date('Y年m月d日 H:i:s 星期w',$profile['updatetime']);
	$profile['unfollowtime']=($profile['unfollowtime']) ? date('Y年m月d日 H:i:s 星期w',$profile['unfollowtime']) : '' ;
	$profile['credit_total']=$profile['credit1']+$profile['credit2'];
		$openid=$profile['openid'];
	$MineAticle_addons=pdo_fetch("select * from ".tablename('fm453_site_article'). " where goodadm = :goodadm AND a_tpl =:a_tpl",array(':goodadm'=>$openid,':a_tpl'=>'member'));
	if($MineAticle_addons) {
		$article_id=$MineAticle_addons['sn'];
		$MineAticle=pdo_fetch("select * from ".tablename('site_article'). " where id = :id",array(':id'=>$article_id));
		$user['content'] = !empty($user['content']) ? $user['content'] : $MineAticle['content'];
		$user['keyword'] = !empty($user['keyword']) ? $user['keyword'] : $MineAticle_addons['keywords'];
	}

	$check_fields=fmFunc_tables_fields('member','shopping');
	if(checksubmit()) {
		$check_fields=array('realname','mobile','email','gender','groupid','alipay');
		$data=array();
		foreach($check_fields as $field){
			$data[$field]= ($_GPC[$field]) ? $_GPC[$field] : $data[$field];
		}
		unset($data['uid']);
		unset($data['uniacid']);
		$result=mc_update($uid,$data);
		if($result){
			//写入操作日志START
			$dologs=array(
				'url'=>$_W['siteurl'],
				'description'=>FM_NAME_CN.'后台编辑系统用户资料；',
				'addons'=>$data,
			);
			fmMod_log_record($platid,$_W['uniacid'],$_W['uid'],$fanid,$openid,'mc_members',$uid,'update',$dologs);
			unset($dologs);
			//写入操作日志END
			//给管理员发个微信消息
			require_once MODULE_ROOT.'/core/msgtpls/tpls/task.php';
			//发微信模板通知
			$postdata = $tplnotice_data['task']['member']['manage']['admin'];
			$result= fmMod_notice_tpl($postdata);
			if(!$result) {
				require MODULE_ROOT.'/core/msgtpls/msg/article.php';
				$postdata = $notice_data['member']['manage']['admin'];
				$result= fmMod_notice($settings['manageropenids'],$postdata);
			}
			message('用户资料编辑成功','referer','success');
		}
	}

	include $this->template($fm453style.$do.'/453');
}
elseif($operation=='query' ){
	$target = trim($_GPC['target']);

	$keyword=trim($_GPC['keyword']);
	if(!$keyword){
		exit('请输入搜索关键词!');
	}
	if($_GPC['openid_uid']){
	    $openid_uid=trim($_GPC['openid_uid']);
	    $uid=mc_openid2uid($openid_uid);
    }
	if($uid>0){
		$condition.=" AND uid = :uid ";
		$params[':uid']=$uid;
	}else{
		if(!empty($keyword)){
		$condition.=' AND ';
		$condition.='(';
		$condition.=' nickname LIKE :nickname ';
		$params[':nickname']='%'.$keyword.'%';
		$condition.=' OR ';
		$condition.=' realname LIKE :realname ';
		$params[':realname']='%'.$keyword.'%';
		$condition.=')';
		}
		$mobile=intval($_GPC['mobile']);
		if(!empty($mobile)){
		$condition.=" AND mobile LIKE :mobile ";
		$params[':mobile']='%'.$mobile.'%';
		}
	}
	$showorder=" ORDER BY uniacid ASC, uid DESC ";

	$total = pdo_fetchcolumn("SELECT COUNT(uid) FROM ".tablename('mc_members').$condition, $params);
	$pager = pagination($total, $pindex, $psize);
	$list = pdo_fetchall("SELECT * FROM ".tablename('mc_members').$condition.$showorder.$limit, $params);
	foreach($list as &$item){
		$mapping_fans = pdo_fetch("SELECT * FROM " . tablename('mc_mapping_fans') . " WHERE `uid` = :uid", array(':uid' => $item['uid']));
		if($mapping_fans){
		     foreach($mapping_fans as $key=>$fan){
				$item[$key] = !isset($item[$key]) ? $fan : $item[$key];
			}
		}
	}
	include $this->template($fm453style.$do.'/'.$ac.'/'.$operation);
}
