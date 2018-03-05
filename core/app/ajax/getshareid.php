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

/*ewei
 * @site www.hiluker.com
 * @remark 取分享人信息
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;
$from_user = $_W['openid'];
$profile = pdo_fetch('SELECT * FROM '.tablename('fm453_shopping_member')." WHERE uniacid = :uniacid  AND from_user = :from_user" , array(':uniacid' => $_W['uniacid'],':from_user' => $from_user));
$cookieid = 'fm453_shopping_'.$profile['uid'].'_'.$_W['uniacid'];
if(empty($profile['shareid'])){
		if(!empty($_COOKIE[$cookieid])){
				if($profile['id']!=$_COOKIE[$cookieid]){
						pdo_update('fm453_shopping_member', array('shareid'=>$_COOKIE[$cookieid]), array('from_user' => $from_user,':uniacid' => $_W['uniacid']));
						return $_COOKIE[$cookieid];
				}
		}
		return 0;
}else{
		return $profile['shareid'];
}
