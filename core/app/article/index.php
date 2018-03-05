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
 * @remark 文章分流引导
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;
if(intval($_GPC['i'])<=0) {
	$_W['uniacid']=1;
	$_GPC['i']=1;
}

fm_load()->fm_func('share');//链接分享管理
fm_load()->fm_func('view'); 	//浏览量处理

$do= $_GPC['do'];
$ac=$_GPC['ac'];
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'index';

switch($operation)
{
	case 'member';	//如果对会员资料的操作
	//如果是精确查询且有相应结果，刚直接跳转到对应页(从会员搜索页过来)
	$from_openid = trim(htmlspecialchars($_GPC['uid_openid']));
	$mid = intval($_GPC['mid']);
	if($mid)
	{
		$articleOpenid = fmMod_member_uid2openid($mid);
		$from_openid = $articleOpenid['data'];
	}
	if($from_openid){
		$article = pdo_fetch("SELECT * FROM " . tablename('fm453_site_article') . "WHERE uniacid = :uniacid  AND a_tpl = :a_tpl AND goodadm = :goodadm ",array(":uniacid"=>$_W['uniacid'],":a_tpl"=>'member',':goodadm'=>$from_openid));
		$article_id =  $article['id'];
		if($article_id) {
			$url=fm_murl('article','detail','index',array('id'=>$article_id));
			header("Location: $url");
			exit;
		}else{
			message('该会员的资料尚未录入');
		}
	}

	break;	//跳离循环

	case 'partner';	//如果对商户资料的操作
	break;	//跳离循环
}
?>
