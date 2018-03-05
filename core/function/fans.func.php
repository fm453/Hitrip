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
 * @remark：粉丝处理函数
 */
defined('IN_IA') or exit('Access Denied');
fm_load()->fm_func('status');
fm_load()->fm_model('member');
fm_load()->fm_model('order');

//判断会员是否有消费记录
function fmFunc_fans_isBuyer($openid){
	$result = pdo_fetch("SELECT `id`,`ordersn` FROM " . tablename('fm453_shopping_order')." WHERE `from_user`= :from_user AND status > :status",array(':from_user'=>$openid,':status'=>0));
	if($result) {
		return TRUE;
	}else{
		return FALSE;
	}
}

//取粉丝的头像信息
function fmFunc_fans_getAvatar(){
	global $_GPC;
	global $_W;
	global $_FM;
	load()->model('mc');
	$openid=$_GPC['openid'];
	if (!empty($_W['member']['uid'])) {
		$member = mc_fetch(intval($_W['member']['uid']), array('avatar'));
		if (!empty($member)) {
			$avatar = $member['avatar'];
		}
	}
	if (empty($avatar)) {
		$fan = mc_fansinfo($_W['openid']);
		if (!empty($fan)) {
			$avatar = $fan['avatar'];
		}
	}
	if (empty($avatar)) {
		$userinfo = mc_oauth_userinfo();
		if (!is_error($userinfo) && !empty($userinfo) && is_array($userinfo) && !empty($userinfo['avatar'])) {
			$avatar = $userinfo['avatar'];
		}
	}
	if (empty($avatar) && !empty($_W['member']['uid'])) {
		$avatar = mc_require($_W['member']['uid'], array('avatar'));
	}
	return $avatar;
}

//根据粉丝openid从系统表中获取对应粉丝的信息
function fmFunc_fans_getInfo($openid)	{
	load()->model('mc');
	$fanid=mc_openid2uid($openid);
	$member = mc_fetch($fanid);
	$faninfo=mc_fansinfo($openid);
	$returns=array();
	$returns['member']=$member;
	$returns['faninfo']=$faninfo;
	return $returns;
}


//虚拟会员头像
function fmFunc_fans_virtualAvatar($openid){
	$oid=rand(1,1000);
	$http = "http://";
	$host = "public.hiluker.com";
	$dir = "/avatars/";
	$file = "virtual_member_avatar_".$oid.".jpg";
	$avatar = $http.$host.$dir.$file;
	return $avatar;
	/*
	$openids=array();
	$oid=0;

	if($oid) {
			unset($openids[$oid]);
		}
	$ids_num=0;
				//print_r($openids);
				//格式化要写入的订单所使用的虚拟会员序列
				if(is_array($openids)) {//如果序列非空
					for ($i = 1; $i < 1000; $i++) {
						$oid=rand(1,1000);
						if(in_array($oid,$openids)) {
							//id序号存在于数组内，说明上次未使用
							$oids[]=$oid;//将序号存入组
							unset($openids[$oid]);//从序列组中踢除该序号
							$ids_num=$ids_num+1;//标记已经成功加入了一个id
						}
						if($ids_num==$pnums){
							unset($openids);//当存入足够ids时，清空可用序列
							$openids=array('0'=>0);
						}
					}
				}
	//print_r($openids);
				//print_r($ids_num);
				//print_r($oids);
				//break;
				//按所使用会员序列遍历写入订单
				foreach($oids as $oid) {
					$openid=$oid.'_openid';
				}
	*/
}
