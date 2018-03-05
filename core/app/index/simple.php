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
 * @remark 简版首页
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;
fm_load()->fm_model('ad');
fm_load()->fm_model('category');
fm_load()->fm_model('goods');
fm_load()->fm_func('share');//链接分享管理
fm_load()->fm_model('shopcart'); //购物车模块
fm_load()->fm_func('market');//营销管理
fm_load()->fm_func('view'); 	//浏览量处理
fm_load()->fm_func('wechat');//微信定义管理

fm_checkopen($settings['onoffs']);

$appstyle = empty($settings['appstyle']) ? FM_APPSTYLE : $settings['appstyle'];
$appsrc =MODULE_URL.'template/mobile/'.$appstyle.'453/';
$htmlsrc =MODULE_URL.'template/mobile/'.$appstyle;
$fm453resource = FM_RESOURCE;

$do= $_GPC['do'];
$ac=$_GPC['ac'];
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'index';

$shopname=$settings['brands']['shopname'];
$shopname = !empty($shopname) ? $shopname :FM_NAME_CN;
$pagename = $shopname.'首页';
$pagename .='|'.$_W['account']['name'];

$uniacid=$_W['uniacid'];
$plattype=$settings['plattype'];
$platids = fmFunc_getPlatids();
$platid=$_W['uniacid'];
$oauthid=$platids['oauthid'];
$fendianids=$platids['fendianids'];
$supplydianids=$platids['supplydianids'];
$blackids=$platids['blackids'];

require_once FM_PUBLIC.'plat.php';
$supplydians=explode(',',$supplydianids);
$supplydians=array_filter($supplydians);

$condition=' WHERE ';
$params=array();
require_once FM_PUBLIC.'forsearch.php';

$share_user=$_GPC['share_user'];
$shareid = intval($_GPC['shareid']);
$lastid = intval($_GPC['lastid']);
$currentid = intval($_W['member']['uid']);
$fromplatid = intval($_GPC['fromplatid']);
$from_user = $_W['openid'];
$url_condition="";
$direct_url = fm_murl($do,$ac,$operation,array());

$url_condition .= '&page='.$_GPC['page'];
$url_condition .= '&rpage='.$_GPC['rpage'];

$is_wexin = fmFunc_wechat_is();

$carttotal = fmMod_shopcart_total();

//幻灯片
$result_advs = fmMod_ad_adv_mine();
$advs = $result_advs['data'];
$lastadvno=count($advs)-1;
$lastadv=$advs[$lastadvno];
$firstadv=$advs[0];

//分页设置
$rpindex = max(1, intval($_GPC['rpage']));
$rpsize = ($settings['index']['temai_num']>0)  ? $settings['index']['temai_num'] : 3;
$pindex = max(1, intval($_GPC['page']));
$psize = (intval($_GPC['psize'])>0) ? intval($_GPC['psize']) : 2;

//排序及截断
$showorder = " ORDER BY uniacid ASC , isrecommand DESC , displayorder DESC , sales DESC ";
$limit = " LIMIT ".($pindex - 1) * $psize . ',' . $psize;
$rshoworder = " ORDER BY uniacid ASC , displayorder DESC , sales DESC ";
$rlimit = " LIMIT ".($rpindex - 1) * $rpsize . ',' . $rpsize;
if($operation=='loadmore'){
	$rlimit = " LIMIT ".($rpindex * $psize) . ',' . $psize;
}

//全部产品详情以产品id为索引存入数组
$allgoods=array();  //保存当前页面里的产品id\sn
$goodsdetails=array();
$goodslabels=array();
$alltotal=array();

//添加筛选条件
$condition .= " AND deleted = :deleted";
$params[':deleted']=0;
$condition .= " AND status = :status";
$params[':status']=1;
$condition .= " AND isrecommand= :isrecommand";
$params[':isrecommand']=1;

//各种数量
$alltotal['goods']['rec']=pdo_fetchcolumn("SELECT COUNT(id) FROM " . tablename('fm453_shopping_goods') .$condition, $params);
$params[':isnew']=1;
$alltotal['goods']['new']=pdo_fetchcolumn("SELECT COUNT(id) FROM " . tablename('fm453_shopping_goods') .$condition ." AND isnew = :isnew", $params);
unset($params[':isnew']);
$params[':istime']=1;
$alltotal['goods']['time']=pdo_fetchcolumn("SELECT COUNT(id) FROM " . tablename('fm453_shopping_goods') .$condition ." AND istime = :istime", $params);
unset($params[':istime']);
$params[':ishot']=1;
$alltotal['goods']['hot']=pdo_fetchcolumn("SELECT COUNT(id) FROM " . tablename('fm453_shopping_goods') .$condition ." AND ishot = :ishot", $params);
unset($params[':ishot']);
$alltotal['goods']['gift']=rand(0,9);
$alltotal['goods']['only']=rand(0,9);
$alltotal['goods']['temai']=rand(0,9);
$alltotal['goods']['kefu']='On';

//添加搜索条件（仅用于筛选首页推荐产品）
if (!empty($_GPC['keyword'])) {
	$condition .= " AND title LIKE :title";
	$params[':title'] = "%". trim($_GPC['keyword'])."%";
	$url_condition .= '&keyword='.$_GPC['keyword'];
}

//首页推荐产品(以id为索引)
$alltotal['goods']['nowrec']=pdo_fetchcolumn("SELECT COUNT(id) FROM " . tablename('fm453_shopping_goods') .$condition .$rshoworder , $params);
$maxpages = fm_page($alltotal['goods']['nowrec']-$rpsize, $psize);

$rec_goodss= pdo_fetchall("SELECT id,sn FROM " . tablename('fm453_shopping_goods') .$condition .$rshoworder .$rlimit, $params,'id');

foreach($rec_goodss as $r_g_k => $goods){
	$allgoods[$r_g_k]=$goods;
}
//取得产品信息
foreach($allgoods as $id =>$goods){
	$result_basic = fmMod_goods_detail_basic($goods['id']);
	$goodsdetails[$id]=$result_basic['data'];
	$result_labels=fmMod_goods_detail_labels($goods['id']);
	$goodslabels[$id]=$result_labels['data'];
}
unset($goods);

//自定义微信分享内容
$_share = array();
$_share['title'] = $pagename;
$_share['link'] = fm_murl($do,$ac,$operation,array('isshare'=>1));
$_share['link'] = $_share['link'].$url_condition;
$_share['imgUrl'] = tomedia($settings['brands']['logo']);
$_share['desc'] = $settings['brands']['share_des'];

if($operation=='ajax'){
	$return = array();
	$sn = $GPC['gsn'];
	$rturn['view'] = view('goods',$sn);

	return $return;
}
elseif($operation=='loadmore'){
	if($rpindex>1) {
		include $this->template($appstyle.$do.'/'.$ac.'/loadmore/page');
	}
}
elseif($operation=='load'){
	if($_GPC['refresh']==1) {
		include $this->template($appstyle.$do.'/'.$ac.'/load/page');
	}else{
		include $this->template($appstyle.$do.'/453');
	}
}
elseif($operation=='index'){
	//更新流量、链路统计
	if(!empty($shareid)){
		fmFunc_share_click($lastid,$shareid,$currentid,$share_user);
	}
	fmFunc_view();//记录访问
	fmMod_member_check($_W['openid']);//检测会员

	if($_GPC['needentry']==true) {
		//从启动入口进入时(默认)
		include $this->template($appstyle.$do.'/'.$ac.'/entry');
	}else{
		//模板主框架（父页面）
		include $this->template($appstyle.$do.'/453');
	}
}
