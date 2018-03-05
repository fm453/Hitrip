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
 * @remark 模块会员/代理管理；
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
$platids= fmFunc_getPlatids();//获取平台模式关联的公众号商户ID参数
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
		$condition.=' realname LIKE :realname ';
		$params[':realname']='%'.$keyword.'%';
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

	$flag = (is_numeric($_GPC['flag']) && in_array($_GPC['flag'],array(0,1))) ? $_GPC['flag'] : '-1';
	if($flag > -1) {

	}
	$shareid = is_numeric($_GPC['shareid']) ? $_GPC['shareid'] : '';
	if($shareid) {
		$condition.=" AND shareid = :shareid ";
		$params[':shareid'] = $shareid;
	}
	$list = pdo_fetchall("SELECT * FROM ".tablename('fm453_shopping_member').$condition.$showorder.$limit,$params);
	foreach($list as $k => &$v){
		$uid = $v['uid'];
		$result = fmMod_member_query($uid);
		$profile[$uid]=$result['data'];
		$cols = array('avatar','fanid','nickname','realname','follow','mobile','vip');
		if($result['result']){
			foreach($cols as $col){
				$v[$col] = $profile[$uid][$col];
			}
		}
	}
	unset($k);
	unset($v);

	$total = pdo_fetchcolumn("select count(id) from". tablename('fm453_shopping_member').$condition,$params);
	$pager = pagination($total, $pindex, $psize);
	$commissions = pdo_fetchall("select mid, sum(commission) as commission from ".tablename('fm453_shopping_commission')." where uniacid = ".$_W['uniacid']." and flag = 0 group by mid");
	// 还需结佣
	$commission = array();
	foreach($commissions as $c){
		$commission[$c['mid']] = $c['commission'];
	}

	//分销规则
	//$settings['fenxiao'];
	include $this->template($fm453style.$do.'/453');
}
//未审核的代理
elseif($operation=='nocheck'){
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$list = pdo_fetchall("select sale.*,mc_members.credit1 from ".tablename('fm453_shopping_member'). " sale left join ".tablename('mc_members'). " mc_members on sale.uid=mc_members.uid where sale.flag = 0 and sale.uniacid = ".$_W['uniacid']." ORDER BY sale.id DESC limit ".($pindex - 1) * $psize . ',' . $psize);
	$total = pdo_fetchcolumn("select count(id) from". tablename('fm453_shopping_member'). "where flag = 0 and uniacid =".$_W['uniacid']);;
	$pager = pagination($total, $pindex, $psize);

	include $this->template($fm453style.$do.'/453');
}
//查找粉丝
elseif($operation=='sort'){
	$sort = array(
			'realname'=>$_GPC['realname'],
			'mobile'=>$_GPC['mobile']
	);
	if($_GPC['opp']=='nocheck'){
			$status = 0;
		} else {
			$status = 1;
		}
	// 符合条件的粉丝
	$list = pdo_fetchall("select * from". tablename('fm453_shopping_member')."where flag = ".$status." and uniacid =".$_W['uniacid'].".and realname like '%".$sort['realname']. "%' and mobile like '%".$sort['mobile']. "%' ORDER BY id DESC");
	$commissions = pdo_fetchall("select mid, sum(commission) as commission from ".tablename('fm453_shopping_commission')." where uniacid = ".$_W['uniacid']." and flag = 0 group by mid");
	// 还需结佣
	$commission = array();
	foreach($commissions as $c){
			$commission[$c['mid']] = $c['commission'];
	}
	if($_GPC['opp']=='nocheck'){
			include $this->template($fm453style.'fansmanage/fansmanagered');
			exit;
	}
}
// 删除粉丝
elseif($operation=='delete'){
		$temp = pdo_delete('fm453_shopping_member', array('id'=>$_GPC['id']));
		if(empty($temp)){
				if($_GPC['opp']=='nocheck'){
					message('删除失败，请重新删除！', $this->createWebUrl('fansmanager', array('op'=>'nocheck')), 'error');
				} else {
					message('删除失败，请重新删除！', $this->createWebUrl('fansmanager'), 'error');
				}
		}else{
			if($_GPC['opp']=='nocheck'){
					message('删除成功！', $this->createWebUrl('fansmanager', array('op'=>'nocheck')), 'success');
				} else {
					message('删除成功！', $this->createWebUrl('fansmanager'), 'success');
				}
		}
}
// 粉丝详情
elseif($operation=='modify'){
    $uid = intval($_GPC['uid']);
	$id = intval($_GPC['id']);
	if($id){
	    $user = pdo_fetch("select * from ".tablename('fm453_shopping_member'). " where id = ".$id);
	    $uid = $user['uid'];
	}elseif($uid){
	    $user = pdo_fetch("select * from ".tablename('fm453_shopping_member'). " where uid = ".$uid);
	$id = $user['id'];

	}
	$result = fmMod_member_query($uid);

	$profile=$result['data'];
	$openid=$profile['openid'];
	$MineAticle_addons=pdo_fetch("select * from ".tablename('fm453_site_article'). " where goodadm = :goodadm AND a_tpl =:a_tpl",array(':goodadm'=>$openid,':a_tpl'=>'member'));
	if($MineAticle_addons) {
		$article_id=$MineAticle_addons['sn'];
		$MineAticle=pdo_fetch("select * from ".tablename('site_article'). " where id = :id",array(':id'=>$article_id));
		$user['content'] = !empty($user['content']) ? $user['content'] : $MineAticle['content'];
		$user['keyword'] = !empty($user['keyword']) ? $user['keyword'] : $MineAticle_addons['keywords'];
	}

	if(checksubmit()) {
		$check_fields=fmFunc_tables_fields('member','shopping');
		$data=array();
		foreach($check_fields as $field){
			$data[$field]= ($_GPC[$field]) ? $_GPC[$field] : '';
		}
		unset($data['uid']);
		unset($data['uniacid']);
		//unset($data['realname']);
		//unset($data['mobile']);
		$result=fmMod_member_update($uid,$data);
		$sys_mc_check_fields=array('realname','mobile','alipay');
		$data=array();
		foreach($check_fields as $field){
			$data[$field]= ($_GPC[$field]) ? $_GPC[$field] : $data[$field];
		}
		unset($data['uid']);
		unset($data['uniacid']);
		mc_update($uid,$data);
		if($result['result']){
			//写入操作日志START
			$dologs=array(
				'url'=>$_W['siteurl'],
				'description'=>FM_NAME_CN.'后台编辑商城会员资料；',
				'addons'=>$data,
			);
			fmMod_log_record($platid,$_W['uniacid'],$_W['uid'],$fanid,$openid,'fm453_shopping_members',$uid,'update',$dologs);
			unset($dologs);
			//写入操作日志END
			//给管理员发个微信消息
			fmMod_notice($settings['manageropenids'],array(
				'header'=>array('title'=>'事件通知','value'=>'后台编辑'.FM_NAME_CN.'会员资料'),
				'operator'=>array('title'=>'操作人','value'=>$_W['username'].'('.$_W['uid'].')'),
				'user'=>array('title'=>'用户','value'=>$profile['openid'])
			));
			message('会员资料编辑成功','referer','success');
		}
	}
	include $this->template($fm453style.$do.'/453');
}

	// 设置粉丝权限，类型
elseif($operation=='status'){
			$status = array(
				'status'=>$_GPC['status'],
				'flag'=>$_GPC['flag'],
				'content'=>trim($_GPC['content'])
			);
			if($_GPC['opp']=='nocheck'&&$_GPC['flag']==1){

				$status ['flagtime']=TIMESTAMP;
			}
			$temp = pdo_update('fm453_shopping_member', $status, array('id'=>$_GPC['id']));
			if(empty($temp)){
				if($_GPC['opp']=='nocheck'){
					message('设置用户权限失败，请重新设置！', $this->createWebUrl('fansmanager', array('op'=>'detail', 'opp'=>'nocheck', 'id'=>$_GPC['id'])), 'error');
				} else {
					message('设置用户权限失败，请重新设置！', $this->createWebUrl('fansmanager', array('op'=>'detail', 'id'=>$_GPC['id'])), 'error');
				}
			}else{
				if($_GPC['opp']=='nocheck'){
					message('设置用户权限成功！', $this->createWebUrl('fansmanager', array('op'=>'nocheck')), 'success');
				} else {
					message('设置用户权限成功！', $this->createWebUrl('fansmanager'), 'success');
				}
			}
		}

// 充值
elseif($operation=='recharge'){
			$id = $_GPC['id'];
			if($_GPC['opp']=='recharged'){
				if(!is_numeric($_GPC['commission'])){
					message('佣金需输入正确的数字！', '', 'error');
				}
				$recharged = array(
					'uniacid'=>$_W['uniacid'],
					'mid'=>$id,
					'flag'=>1,
					'content'=>trim($_GPC['content']),
					'commission'=>$_GPC['commission'],
					'createtime'=>time()
				);
				$temp = pdo_insert('fm453_shopping_commission', $recharged);
				// 已结佣金
				$commission = pdo_fetchcolumn("select commission from ".tablename('fm453_shopping_member'). " where id = ".$id);
				if(empty($temp)){
					message('充值失败，请重新充值！', $this->createWebUrl('fansmanager', array('op'=>'recharge', 'id'=>$_GPC['id'])), 'error');
				}else{
					pdo_update('fm453_shopping_member', array('commission'=>$commission+$_GPC['commission']), array('id'=>$id));
					message('充值成功！', $this->createWebUrl('fansmanager', array('op'=>'recharge', 'id'=>$_GPC['id'])), 'success');
				}
			}
			$user = pdo_fetch("select * from ".tablename('fm453_shopping_member'). " where id = ".$id);
			$commission = pdo_fetchcolumn("select sum(commission) from ".tablename('fm453_shopping_commission')." where mid = ".$id." and flag = 0 and uniacid = ".$_W['uniacid']);
			$commission = empty($commission)?0:$commission;
			// 可结佣金
			$commission = $commission - $user['commission'];
			// 充值记录
			$commissions = pdo_fetchall("select * from ".tablename('fm453_shopping_commission')." where mid = ".$id." and uniacid = ".$_W['uniacid']." and flag = 1");
			include $this->template($fm453style.'fansmanage/fansmanager_recharge');
			exit;
}