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
 * @remark	购物车管理模型
 */

 //获取购物车内总量
function fmMod_shopcart_total($openid=null,$uid=null){
	global $_GPC;
	global $_W;
	global $_FM;
	$return=array();
	$openid = !($openid) ? $_W['openid'] : $openid;
	$uid = (intval($uid)<=0) ? $_W['member']['uid'] : intval($uid);
	if(!$openid && !$uid){
		return 0;
	}
	$sql = " SELECT total FROM ". tablename("fm453_shopping_cart");
	$params=array();
	$condition = " WHERE ";
	$condition .= " uniacid = :uniacid ";
	$params[':uniacid'] = $_W['uniacid'];
	$condition .= "AND";
	$condition .= " ( from_user = :openid OR uid = :uid )";
	$params[':openid'] = $openid;
	$params[':uid'] = $uid;
	$totals = pdo_fetchall($sql.$condition,$params);
	$carttotal=0;
	foreach($totals as $key=>$total){
		$carttotal +=intval($total['total']);
	}
	$carttotal =empty($carttotal) ? 0 : $carttotal;
	return $carttotal; //返回购物车数值
}

?>
