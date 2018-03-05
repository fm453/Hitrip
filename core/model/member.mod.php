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
 * @remark	会员模型
 */
defined('IN_IA') or exit('Access Denied');

load()->model('mc');
fm_load()->fm_func("tables");

//_mc_login($member); //会员登陆
//mc_openid2uid($openid); 转换会员uid
function fmMod_member_orderby(){
	return $showorder=' ORDER BY createtime DESC, id DESC ';//强制设定排序规则
}

//从系统会员表获取会员系统(参数二选一，以openid为先)
function fmMod_member_query($uid=NULL,$openid=NULL) {
	global $_W,$_FM;
	header("Content-type:text/html;charset=utf-8");//加上网页字符集强制设置，避免中文传输乱码
	$return=array();
	$member=array();
	if(empty($openid)) {
		if(!is_numeric($uid)) {
			$return['result']=FALSE;
			$return['msg']='传入的会员uid非法（必须是正整数）';
			return $return;
		}
		$member['uid']=$uid;
		$mapping_fans = pdo_fetch("SELECT * FROM " . tablename('mc_mapping_fans') . " WHERE `uid` = :uid", array(':uid' => $uid));//反推对应粉丝信息，是否关注公众号、openid、fanid等
		$openid=$mapping_fans['openid'];
	}else{
		$mapping_fans = pdo_fetch("SELECT * FROM " . tablename('mc_mapping_fans') . " WHERE `openid` = :openid", array(':openid' => trim($openid)));
		$member['uid']=$uid=$mapping_fans['uid'];
	}
	if($mapping_fans) {
		$member['fanid']=$mapping_fans['fanid'];
		$member['uniacid']=$mapping_fans['uniacid'];
		$member['openid']=$mapping_fans['openid'];
		$member['nickname']=$mapping_fans['nickname'];
		$member['follow']=$mapping_fans['follow'];
		$member['groupid']=$mapping_fans['groupid'];
		$member['followtime']=$mapping_fans['followtime'];
		$member['unfollowtime']=$mapping_fans['unfollowtime'];
		$member['updatetime']=$mapping_fans['updatetime'];
		$member['unionid']=$mapping_fans['unionid'];
	}

	$profile = mc_fetch($uid);

	if($profile) {
		$member['mobile']=$profile['mobile'];
		$member['email']=$profile['email'];
		$member['groupid']=$profile['groupid'];
		$member['realname']=$profile['realname'];
		// if($profile['avatar']){
		//     $avatar = ((substr($profile['avatar'],9)=='http://wx' || substr($profile['avatar'],10)=='https://wx') && (substr($profile['avatar'],-2)=='/0' || substr($profile['avatar'],-4)=='/132')) ? $profile['avatar'] : $profile['avatar'].'/132';	//解决微擎1.6.7版无法正确显示粉丝头像的问题
		// }else{
		//     $avatar = '';
		// }

		// $member['avatar']=$avatar;
		$member['avatar']=$profile['avatar'];
		$member['vip']=$profile['vip'];
		$member['credit1']=$profile['credit1'];
		$member['credit2']=$profile['credit2'];
		$member['credit3']=$profile['credit3'];
		$member['credit4']=$profile['credit4'];
		$member['credit5']=$profile['credit5'];
		$member['birthyear']=$profile['birthyear'];
		$member['birthmonth']=$profile['birthmonth'];
		$member['birthday']=$profile['birthday'];
		$member['gender']=$profile['gender'];
		$member['alipay']=$profile['alipay'];
		$member['address']=$profile['address'];
		//$member['']=$profile[''];
	}
	if(!$member['nickname']) {
		if($member['realname']) {
			$member['nickname']=$member['realname'];
		}else{
			$member['nickname']=$member['realname']="未填写";
		}
	}
	$return['result']=TRUE;
	$return['data']=$member;
	return $return;
}

//检查当前用户是否注册到了商城会员表内,使用openid或uid为索引,如果没有，就创建一条
//返回商城会员表数据ID（代理身份ID）
function fmMod_member_check($OpenidOrUid=NULL) {
	global $_GPC;
	global $_W;
	global $_FM;
	$OpenidOrUid = !empty($OpenidOrUid) ? $OpenidOrUid : $_W['member']['uid'];
	$uid=mc_openid2uid($OpenidOrUid);
	$result=fmMod_member_query($uid);
	$profile=$result['data'];
	$data=array();
	$platid=$_W['uniacid'];
	$mid=pdo_fetchcolumn("SELECT id FROM ".tablename('fm453_shopping_member')." WHERE uid = :uid",array(':uid'=>$uid));
	if(!$mid) {
		$data['uid'] =  $uid;
		$data['uniacid']= $platid;
		$data['realname'] = $profile['realname'];
		$data['from_user'] = $profile['openid'];
		$data['mobile'] = $profile['mobile'];
		$data['alipay'] = $profile['alipay'];
		$data['status'] = 1;//会员状态（0为不可用）
		if($_FM['settings']['force_verify']){
			$data['status'] = 0;//会员状态（0为不可用）
		}
		$data['createtime']=TIMESTAMP;
		$result=pdo_insert('fm453_shopping_member',$data);
		if($result){
			$mid = pdo_insertid();
			return $mid;
		}
	}else{
		return $mid;
	}
}

//在系统会员表中检查用户是否有注册，无则创建
/*
@info 会员详情信息（成员id、手机、邮箱、名称等）
@sure 是否确认直接注册(可减去检测步骤)
*/
//返回系统会员表中的会员id
function fmMod_member_reg($info,$sure=NULL) {
	global $_GPC;
	global $_W;
	global $_FM;
	$uniacid=$_W['uniacid'];
	$openid = $_W['openid'];
	$mobile = isset($info['mobile']) ? $info['mobile'] : '';
	$email = isset($info['email']) ? $info['email'] : md5($openid).'@we7.cc';
	$user = array();
	if(!$sure){
		$sql = 'SELECT `uid` FROM ' . tablename('mc_members') . ' WHERE `uniacid`=:uniacid';
		$pars = array();
		$pars[':uniacid'] = $_W['uniacid'];
		if ($mobile) {
			$sql .= ' AND `mobile`=:mobile';
			$pars[':mobile'] = $mobile;
		} elseif ($email) {
			$sql .= ' AND `email`=:email';
			$pars[':email'] = $email;
		}
		$user = pdo_fetch($sql, $pars);
	}
	if(!$user){
		$default_groupid = pdo_fetchcolumn('SELECT groupid FROM ' .tablename('mc_groups') . ' WHERE uniacid = :uniacid AND isdefault = 1', array(':uniacid' => $uniacid));
		$data = array(
			'uniacid' => $uniacid,
			'mobile'=> $mobile,
			'email' => md5($openid).'@we7.cc',
			'avatar' => $info['avatar'],
			'gender' => $info['gender'],
			'realname' => $info['name'],
			'salt' => random(8),
			'groupid' => $default_groupid,
			'createtime' => TIMESTAMP,
		);
		$data['password'] = md5('123456' . $data['salt'] . $_W['config']['setting']['authkey']);	//用户的初始密码是123456
		pdo_insert('mc_members', $data);
		$uid = pdo_insertid();
	}else{
		$uid = $user['uid'];
	}

	//如果用户信息已存在，则更新它
	//注册到商城会员表
	fmMod_member_check($uid);

	//把剩余信息补充为商城会员设置(企业微信成员)
	$unsets = array('mobile','gender','email','avatar');
	foreach($unsets as $col){
	    unset($info[$col]);
	}

	fmMod_member_update($uid,$info);

	foreach($info as $col=>$val){
		$setfor = $col;
		$data = array();
		$data['title'] = $col;
		$data['value'] = $val;
		$data['status'] = 64;
		fmMod_member_settings_save($uid,$openid,$data,$setfor, $uniacid);
		unset($data);
	}
	return $uid;
}

//模糊查询会员，返回uid数组
function fmMod_member_uids($keyword) {
	global $_GPC;
	global $_W;
	global $_FM;
	header("Content-type:text/html;charset=utf-8");//加上网页字符集强制设置，避免中文传输乱码
	$return=array();
	if (empty($keyword)) {
		$return['result']=FALSE;
		$return['msg']='未传入要查询的关键字';
		return $return;
	}
	$keyword=trim($keyword);
	$params=array();
		$condition = "WHERE ";
		$condition .=" realname LIKE :realname";
		$params[':realname'] ='%'.$keyword.'%';
		$condition .= " OR ";
		$condition .=" nickname LIKE :nickname";
		$params[':nickname'] ='%'.$keyword.'%';
		$condition .= " OR ";
		$condition .=" mobile LIKE :mobile";
		$params[':mobile'] ='%'.$keyword.'%';
		$condition .= " OR ";
		$condition .=" uid LIKE :uid";
		$params[':uid'] ='%'.$keyword.'%';
	$showorder=' ORDER BY uid ASC, createtime DESC';
	$limit=' LIMIT 0,50';//为了控制效率，默认最多只取前50个匹配的数据
	$result=pdo_fetchall("SELECT uid FROM ".tablename('mc_members').$condition.$showorder.$limit,$params,'uid');
	if($result) {
		$return['result']=TRUE;
		$return['msg']='';
		$return['data']=$result;
	}else {
		$return['result']=FALSE;
		$return['msg']='无匹配的会员数据';
	}
	return $return;
}

//查询商城代理人资料
function fmMod_member_agent($uid){
	global $_GPC;
	global $_W;
	global $_FM;
	header("Content-type:text/html;charset=utf-8");//加上网页字符集强制设置，避免中文传输乱码
	$return=array();
	if(!is_numeric($uid)) {
		$return['result']=FALSE;
		$return['msg']='传入的会员uid非法（必须是正整数）';
		return $return;
	}
	$result = pdo_fetch("SELECT * FROM ".tablename('fm453_shopping_member')." WHERE uid = :uid",array(':uid'=>$uid));
	if($result) {
		$return['result']=TRUE;
		$return['data'] = $result;
		return $return;
	}else{
		$return['result']=FALSE;
		$return['msg']='未查询到该会员资料';
		return $return;
	}
}

//更新会员资料
function fmMod_member_update($uid,$data) {
	global $_GPC;
	global $_W;
	global $_FM;
	$return = array();
	if(!is_numeric($uid)) {
		$return['result']=FALSE;
		$return['msg']='传入的会员uid非法（必须是正整数）';
		return $return;
	}
	$fields=fmFunc_tables_fields('member');
	foreach($data as $key=>$d){
		if(!in_array($key,$fields)) {
			unset($data[$key]);
		}
	}
	unset($data['createtime']);
	unset($data['id']);
	unset($data['uniacid']);
	unset($data['from_user']);
	if(empty($data)) {
		$return['result']=FALSE;
		$return['msg']='数据未做任何有效更改';
		return $return;
	}

	$user = fmMod_member_agent($uid);

	if($data['flag'] == 1) {
		$data['flagtime'] = ($user['flagtime']) ? $user['flagtime'] : TIEMSTAMP;
	}
	$result=pdo_update('fm453_shopping_member',$data,array('uid'=>$uid));
	if($result) {
		$return['result']=TRUE;
		return $return;
	}else{
		$return['result']=FALSE;
		$return['msg']='数据更新失败';
		return $return;
	}
}

//从系统会员表获取会员OPENID
function fmMod_member_uid2openid($uid) {
	global $_GPC;
	global $_W;
	global $_FM;
	header("Content-type:text/html;charset=utf-8");//加上网页字符集强制设置，避免中文传输乱码
	$return=array();
	if(!is_numeric($uid)) {
		$return['result']=FALSE;
		$return['msg']='传入的会员uid非法（必须是正整数）';
		return $return;
	}
	$openid = pdo_fetchcolumn("SELECT openid FROM " . tablename('mc_mapping_fans') . " WHERE `uid` = :uid", array(':uid' => $uid));
	if($openid) {
		$return['result']=TRUE;
		$return['data']=$openid;
		return $return;
	}
	else{
		$return['result']=FALSE;
		$return['msg']='未查到该会员的OPENID';
		return $return;
	}
}

//会员收货地址
function fmMod_member_address($uid,$isdefault=null) {
	global $_GPC;
	global $_W;
	global $_FM;
	header("Content-type:text/html;charset=utf-8");//加上网页字符集强制设置，避免中文传输乱码
	$return=array();
	$uid = ($uid>0) ? $uid : $_W['member']['uid'];
	if(!is_numeric($uid)) {
		$return['result']=FALSE;
		$return['msg']='传入的会员uid非法（必须是正整数）';
		return $return;
	}

	$addresses = pdo_fetchall("SELECT * FROM " . tablename('mc_member_address') . " WHERE uid = :uid ", array(':uid' => $uid),'id');
	$default = pdo_fetch("SELECT * FROM " . tablename('mc_member_address') . " WHERE isdefault = 1 and uid = :uid limit 1", array(':uid' => $uid));
	if(!$default && !empty($addresses)) {
		$default=$addresses[0];
	}
	if($addresses) {
		$return['result']=TRUE;
		$return['data']['all']=$addresses;
		$return['data']['default']=$default;
		if($isdefault){
			return $default;
		}else{
			return $return;
		}
	}
	elseif($_W['member']['mobile']) {
		$data=array(
			'username'=>!empty($_W['member']['realname']) ? $_W['member']['realname'] : '匿名',
			'mobile'=>$_W['member']['mobile'],
		);
		$return['result']=TRUE;
		$return['data']['all']=array($data);
		$return['data']['default']=$data;
		if($isdefault){
			return $data;
		}else{
			return $return;
		}
	}
	else{
		$return['result']=FALSE;
		$return['msg']='未查到该会员的地址/联系信息';
		return $return;
	}
}

/*
	会员个性化设置; 单项保存
	@data 要保存的数据中，包含title,value,status
*/
function fmMod_member_settings_save($uid,$openid=NULL,$data,$setfor, $uniacid=NULL) {
	global $_W,$_FM,$_GPC;
	$return=array();
	$uid = ($uid>0) ? $uid : $_W['member']['uid'];
	if(!is_numeric($uid)) {
		$return['result']=FALSE;
		$return['msg']='传入的会员uid非法（必须是正整数）';
		return $return;
	}
	$uniacid=(intval($uniacid)>0) ? $uniacid : $_W['uniacid'];
	if (!is_numeric($uniacid)) {
		$return['result']=FALSE;
		$return['msg']='公号uniacid无效';
		return $return;
	}
	if(empty($setfor)){
		$return['result']=FALSE;
		$return['msg']='要设置的会员属性setfor未指定';
		return $return;
	}
	if(!empty($setfor)) {
		//传入了setfor，保存的数据取出时都归属于setfor组内
		$record = array();
		if (!is_array($data)) {
			$return['result']=FALSE;
			$return['msg']='传入的数据格式非法，必须是完整的array';
			return $return;
		}
		$record['uniacid']= $uniacid;
		$record['setfor']=$setfor;
		$record['uid']=$uid;
		$record['status']=$data['status'];
		$record['createtime']=time();
		$record['title']= !empty($data['title']) ? $data['title'] : $setfor;	//键值，对应会员信息表或其他相关表中的字段；为空时，同setfor值
		$record['value']=$data['value'];
		//$record['value']=htmlspecialchars($data['value']);//过滤字符
		$record['value']=iserializer($data['value']);//将数据序列化，以便入库

		$return['result']=TRUE;
		$return['msg']='';

			$sql='SELECT `id`FROM '.tablename('fm453_shopping_member_settings').' WHERE `setfor`=:setfor AND `uniacid` =:uniacid AND `title` =:title AND `uid` =:uid';
			$params=array();
			$params[':setfor']=$setfor;
			$params[':uniacid']=$uniacid;
			$params[':uid']=$uid;
			$params[':title']=$record['title'];
			$isexist = pdo_fetchcolumn($sql,$params);
			if ($isexist) {
				$result = pdo_update('fm453_shopping_member_settings', $record, array('id'=>$isexist));
				if(!$result) {
					$return['result']=FALSE;
					$return['msg'] .= $setfor." --".$key." 保存失败";
				}
			}else{
				$result = pdo_insert('fm453_shopping_member_settings', $record);
				if(!$result) {
					$return['result']=FALSE;
					$return['msg'] .= $setfor." --".$key." 保存失败";
				}
			}
		return $return;
	}
}

/*
	会员个性化设置取出（仅包含已审核项）
*/
function fmMod_member_settings($uid=NULL,$uniacid=NULL){
	global $_GPC;
	global $_W;
	global $_FM;
	$params =array();
	$return =array();
	$fields='`id`,`uniacid`,`uid`,`setfor`,`title`,`value`,`status`,`createtime`';	//要查询的字段范围
	$tablename='fm453_shopping_member_settings';
	//数字化传入的数值，防恶意查询
	$uid = ($uid>0) ? $uid : $_W['member']['uid'];
	if(!is_numeric($uid))
	{
		$return['result']=FALSE;
		$return['msg']='传入的会员uid非法（必须是正整数）';
		return $return;
	}
	$uniacid=(intval($uniacid)>0) ? $uniacid : $_W['uniacid'];
	if (!is_numeric($uniacid))
	{
		$return['result']=FALSE;
		$return['msg']='公号uniacid无效';
		return $return;
	}
	$status='127';
	$sql='SELECT '.$fields.'FROM'.tablename($tablename);
	$condition = ' WHERE ';
	$condition .= '`uniacid`=:uniacid';
	$params[':uniacid']=$uniacid;
	$condition .=' AND ';
	$condition .= '`status`=:status';
	$params[':status']=$status;
	$condition .=' AND ';
	$condition .= '`uid`=:uid';
	$params[':uid']=$uid;
	$sql =$sql.$condition;
	$settings= pdo_fetchall($sql,$params,'title');
	if(is_array($settings) && !empty($settings))
	{
		$result=array();
		foreach($settings as $key=>&$setting)
		{
			$setting['value'] = iunserializer($setting['value']);	//被序列化的数据需要先还原
			$setfor=$setting['setfor'];
			$title=$key;
			if($title !=$setfor)
			{
				$result[$setfor][$key]=$setting['value'];
			}else{
				$result[$key]=$setting['value'];
			}
		}
		$return['result']=TRUE;
		$return['msg'] ='';
		$return['data']=$result;
	}
	return $return['data'];
}

/*
	会员个性化设置取出（包含未审核项）
*/
function fmMod_member_settings_all($uid=NULL,$uniacid=NULL){
	global $_W,$_FM,$_GPC;
	$params =array();
	$return =array();
	$fields='`id`,`uniacid`,`uid`,`setfor`,`title`,`value`,`status`,`createtime`';	//要查询的字段范围
	$tablename='fm453_shopping_member_settings';
	//数字化传入的数值，防恶意查询
	$uid = ($uid>0) ? $uid : $_W['member']['uid'];
	if(!is_numeric($uid)) {
		$return['result']=FALSE;
		$return['msg']='传入的会员uid非法（必须是正整数）';
		return $return;
	}
	$uniacid=(intval($uniacid)>0) ? $uniacid : $_W['uniacid'];
	if (!is_numeric($uniacid)) {
		$return['result']=FALSE;
		$return['msg']='公号uniacid无效';
		return $return;
	}
	$sql='SELECT '.$fields.'FROM'.tablename($tablename);
	$condition = ' WHERE ';
	$condition .= '`uniacid`=:uniacid';
	$params[':uniacid']=$uniacid;
	$condition .=' AND ';
	$condition .= '`uid`=:uid';
	$params[':uid']=$uid;
	$sql =$sql.$condition;
	$settings= pdo_fetchall($sql,$params,'title');
	if(is_array($settings)){
		if(empty($settings)){
			$return['result']=FALSE;
			$return['msg'] ='未获取到数据';
			$return['data']='';
		}else{
			$result=array();
			foreach($settings as $key=>&$setting){
				$setting['value'] = (is_serialized($setting['value'])) ? iunserializer($setting['value']) : $setting['value'];	//如果被序列化，需要先还原
				$setfor=$setting['setfor'];
				$title=$key;
				if($title !=$setfor){
					$result[$setfor][$key]=$setting['value'];
				}else{
					$result[$key]=$setting['value'];
				}
			}
			$return['result']=TRUE;
			$return['msg'] ='';
			$return['data']=$result;
		}
	}else{
		$return['result']=FALSE;
		$return['msg'] ='数据格式非法';
		$return['data']='';
	}
	return $return['data'];
}

/*
	设置会员登陆
	@$uid 传入会员ID时直接登陆，不判断手机号
	@mobile 优先使用手机号判断；
	@email 未传入mobile时，使用email判断
	@password传入了，则判断是否正确；否则不判断
*/
function fmMod_member_login($uid,$mobile,$email=null,$password=null){
	global $_W;
	if($uid){
		$sql = 'SELECT `uid`,`salt`,`password` FROM ' . tablename('mc_members') . ' WHERE `uniacid`=:uniacid AND `uid`=:uid' ;
		$params = array();
		$pars[':uniacid'] = $_W['uniacid'];
		$pars[':uid'] = $uid;
		$user = pdo_fetch($sql, $pars);
		return _mc_login($user);
	}
	$username = !isset($mobile) ? $email : $mobile;
	if (empty($username)) {
		message('用户名不能为空', '', 'error');
	}

	$sql = 'SELECT `uid`,`salt`,`password` FROM ' . tablename('mc_members') . ' WHERE `uniacid`=:uniacid';
	$pars = array();
	$pars[':uniacid'] = $_W['uniacid'];
	if ($mobile) {
		$sql .= ' AND `mobile`=:mobile';
		$pars[':mobile'] = $mobile;
	} elseif ($email) {
		$sql .= ' AND `email`=:email';
		$pars[':email'] = $email;
	} else {
		return false;
	}

	$user = pdo_fetch($sql, $pars);
	if (empty($user)) {
		message('该帐号尚未注册', '', 'error');
	}
	if ($password) {
		$hash = md5($password . $user['salt'] . $_W['config']['setting']['authkey']);
		if ($user['password'] != $hash) {
			message('密码错误', '', 'error');
		}
	}
	return _mc_login($user);
}


?>
