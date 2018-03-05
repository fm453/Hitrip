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
 * @remark 微站首页
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;
fm_load()->fm_model('ad');
fm_load()->fm_model('category');
fm_load()->fm_model('goods');
fm_load()->fm_model('shopcart');
fm_load()->fm_model('article');
fm_load()->fm_func('share');//链接分享管理
fm_load()->fm_func('view'); 	//浏览量处理
fm_load()->fm_func('wechat');//微信定义管理

$appstyle = empty($settings['appstyle']) ? FM_APPSTYLE : $settings['appstyle'];
$appsrc =MODULE_URL.'template/mobile/'.$appstyle.'453/';
$htmlsrc =MODULE_URL.'template/mobile/'.$appstyle;
$fm453resource = FM_RESOURCE;

$do=$_GPC['do'];
$ac=$_GPC['ac'];
$op= !empty($_GPC['op']) ? $_GPC['op'] : 'index';
$operation = $op;

$shopname=$settings['brands']['shopname'];
$shopname = !empty($shopname) ? $shopname :FM_NAME_CN;
$pagename = '首页';
$pagename .='|'.$_W['account']['name'];

$uniacid=$_W['uniacid'];
$plattype=$settings['plattype'];
$platids= fmFunc_getPlatids();
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
$direct_url = fm_murl($do,$ac,'index',array());

$url_condition .= '&page='.$_GPC['page'];
$url_condition .= '&rpage='.$_GPC['rpage'];

$is_wexin = fmFunc_wechat_is();
$carttotal = fmMod_shopcart_total();

//微站风格
//$settings['index']['site_style'];
//背景图
$style['index']['bg_img'] = $appsrc."images/sitebg.png";
if(file_exists(FM_PATH.'template/mobile/'.$appstyle.'453/'."images/sitebg-".$_W['uniacid'].".png")) {
	$style['index']['bg_img'] = $appsrc."images/sitebg-".$_W['uniacid'].".png";
}

load()->model('app');
$appNavs = app_navs();//调用微站导航位置,用于9宫格

//幻灯片
$result_advs = fmMod_ad_adv_mine();
$advs = $result_advs['data'];
$lastadvno=count($advs)-1;
$lastadv=$advs[$lastadvno];
$firstadv=$advs[0];

//广告
$result_ads = fmMod_ads_mine();
$ads = $result_ads['data'];
$adboxes = $result_ads['adboxes'];
// 根据模板需要，改造广告位的广告结构
//改造第二个广告位的默认节点(取其中最后两个有设置了图片的广告)（必须保证有3个以上的广告才有效）

if(count($ads[1])>=3) {
	$li_count = 2; //各节点要求多少个广告
	$node_count = round(count($ads[1])/$li_count,0);
	$node_count_1=$node_count;
	//for($i=count($ads[1])-1;$i>=0;$i--){
			$i = count($ads[1])-1;
			for ($n=0; $n<$node_count; $n++) {
				for ($k=0;$k<$li_count;$k++){
					$m = $li_count*$n+$k;
					if($m>$i) {
						$m = $k;	//最后一个节点广告不足时，从头再取补齐
					}
					$adboxes[1]['fornode'][$n][] = $ads[1][$m];
				}
			}
	//}
	$adboxes[1]['first'] = $adboxes[1]['fornode'][$node_count-1];
	$adboxes[1]['last'] = $adboxes[1]['fornode'][0];
}
else{
	$adboxes[1] = FALSE;
}
//改造第三个广告位（必须显示4个）
if(count($ads[2])>=4) {
	$node_count = 1; //节点数
	$node_count_2=$node_count;
	$li_count = 4; //节点要求多少个广告
	$i = count($ads[2])-1;
	for ($n=0; $n<$li_count; $n++) {
		$m =$n;
		$adboxes[2]['fornode'][0][] = $ads[2][$m];
	}
}else{
	$adboxes[2] = FALSE;
}

//文章栏目
$allcategory = fmMod_category_get('article');

//分页设置
$rpindex = max(1, intval($_GPC['rpage']));
$rpsize = ($settings['index_article_psize']>0)  ? $settings['index_article_psize'] : 20;
$pindex = max(1, intval($_GPC['page']));
$psize = (intval($_GPC['psize'])>0) ? intval($_GPC['psize']) : 10;

//排序及截断
$showorder = " ORDER BY  displayorder DESC , uniacid ASC";
$limit = " LIMIT ".($pindex - 1) * $psize . ',' . $psize;
$rshoworder = " ORDER BY displayorder DESC ,  uniacid ASC";
$rlimit = " LIMIT ".($rpindex - 1) * $rpsize . ',' . $rpsize;
if($op=='loadmore'){
	$rlimit = " LIMIT ".($rpindex * $psize) . ',' . $psize;
}

//全部文章详情以文章id为索引存入数组
$allarticle=array();  //保存当前页面里的文章id\sn
$alldetails=array();
$alllabels=array();
$alltotal=array();
$allnotice=array();

//各种数量
$alltotal['article']['rec']=pdo_fetchcolumn("SELECT COUNT(id) FROM " . tablename('fm453_site_article') .$condition, $params);
$params[':ishot']=1;
$alltotal['article']['hot']=pdo_fetchcolumn("SELECT COUNT(id) FROM " . tablename('fm453_site_article') .$condition ." AND ishot = :ishot", $params);
unset($params[':ishot']);

//添加搜索条件（仅用于筛选首页推荐）
if (!empty($_GPC['keyword'])) {
	$condition .= " AND title LIKE :title";
	$params[':title'] = "%". trim($_GPC['keyword'])."%";
	$url_condition .= '&keyword='.$_GPC['keyword'];
}
//添加筛选条件
$condition .= " AND deleted = :deleted";
$params[':deleted']=0;
//$condition .= " AND statuscode >= :status";
//$params[':status']=64;

//首页公告(以id为索引)
$notices = pdo_fetchall("SELECT id FROM " . tablename('fm453_site_article') .$condition." AND a_tpl = 'notice' AND timeend > {$_W['timestamp']} LIMIT 0,3", $params,'id');
if(is_array($notices)) {
	foreach($notices as $r_g_k => $goods){
		$allnotice[$r_g_k]=$goods;
	}
}
//首页推荐条件
$condition .= " AND isrecommand= :isrecommand";
$params[':isrecommand']=1;

//首页推荐文章(以id为索引)
$alltotal['article']['nowrec']=pdo_fetchcolumn("SELECT COUNT(id) FROM " . tablename('fm453_site_article') .$condition .$rshoworder , $params);
$maxpages = fm_page($alltotal['article']['nowrec']-$rpsize, $psize);
$rec_articles= pdo_fetchall("SELECT id FROM " . tablename('fm453_site_article') .$condition. " AND a_tpl !='notice' ".$rshoworder .$rlimit, $params,'id');
if(is_array($rec_articles)) {
	foreach($rec_articles as $r_g_k => $goods){
		$allarticle[$r_g_k]=$goods;
	}
}
//取得文章信息
foreach($allarticle as $id =>$goods){
	$result_detail = fmMod_article_detail_m($goods['id'],'');
	$alldetail[$id]=$result_detail['data'];
}

//取得公告信息
foreach($allnotice as $id =>$goods){
	$result_detail = fmMod_article_detail_m($goods['id'],'');
	$allnotice[$id]=$result_detail['data'];
}

unset($goods);
//自定义微信分享内容
$_share = array();
$_share['title'] = $settings['brands']['shopname'];
$_share['link'] = fm_murl($do,$ac,$op,array('isshare'=>1));
$_share['link'] = $_share['link'].$url_condition;
$_share['imgUrl'] = tomedia($settings['brands']['logo']);
$_share['desc'] = $_share['desc'] = $settings['brands']['share_des'];

if($op=='loadmore'){
	if($rpindex>1) {
		//include $this->template($appstyle.$do.'/'.$ac.'/loadmore/page');
		include fmFunc_template_m($do.'/'.$ac.'/loadmore/page');
	}
}elseif($op=='load'){
	if($_GPC['refresh']==1) {
		//include $this->template($appstyle.$do.'/'.$ac.'/load/page');
		include fmFunc_template_m($do.'/'.$ac.'/load/page');
	}else{
		//include $this->template($appstyle.$do.'/453');
		include fmFunc_template_m($do.'/453');
	}
}elseif($op=='index'){
	//更新流量、链路统计
	if(!empty($shareid)){
		fmFunc_share_click($lastid,$shareid,$currentid,$share_user);
	}
	fmFunc_view();//记录访问
	fmMod_member_check($_W['openid']);//检测会员
	//模板主框架（父页面）
	include fmFunc_template_m($do.'/453');
	//include $this->template($appstyle.$do.'/453');
}
