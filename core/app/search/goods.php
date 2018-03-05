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
 * @remark 产品搜索页
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;
fm_load()->fm_model('category');
fm_load()->fm_model('goods');
fm_load()->fm_model('shopcart');
fm_load()->fm_model('ad');
fm_load()->fm_func('share');//链接分享管理
fm_load()->fm_func('view'); 	//浏览量处理
fm_load()->fm_func('wechat');//微信定义管理

//是否关店歇业
fm_checkopen($settings['onoffs']);

//加载风格模板及资源路径
$appstyle = empty($settings['appstyle']) ? FM_APPSTYLE : $settings['appstyle'];
$appsrc =MODULE_URL.'template/mobile/'.$appstyle.'453/';
$htmlsrc =MODULE_URL.'template/mobile/'.$appstyle;
$fm453resource = FM_RESOURCE;
//入口判断
$do= $_GPC['do'];
$ac=$_GPC['ac'];
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'index';
//开始操作管理
$shopname=$settings['brands']['shopname'];
$shopname = !empty($shopname) ? $shopname :FM_NAME_CN;
$pagename = '搜索页';

$uniacid=$_W['uniacid'];
$plattype=$settings['plattype'];
$platids= fmFunc_getPlatids();
$platid=$_W['uniacid'];
$oauthid=$platids['oauthid'];
$fendianids=$platids['fendianids'];
$supplydianids=$platids['supplydianids'];
$blackids=$platids['blackids'];

//平台模式处理
require_once FM_PUBLIC.'plat.php';
$supplydians=explode(',',$supplydianids);//字符串转数组
$supplydians=array_filter($supplydians);//数组去空

//按平台模式前置筛选条件
$condition=' WHERE ';
$params=array();
require_once FM_PUBLIC.'forsearch.php';

$shareid = intval($_GPC['shareid']);
$lastid = intval($_GPC['lastid']);
$currentid = intval($_W['member']['uid']);
$fromplatid = intval($_GPC['fromplatid']);

$url_condition="";
$direct_url = fm_murl($do,$ac,'index',array());

//初始化一些数据
$allcategory = array();
$allchidren = array();
$allgoods = array();//存入全部产品id\sn
$alltotal = array();
$allpager = array();
$allurl = array();

$is_wexin = fmFunc_wechat_is();
//$userinfo = fmFunc_fans_oauth_getInfo();//网页授权获取头像、昵称等信息；
$alltotal['mycart'] = fmMod_shopcart_total();
$carttotal = $alltotal['mycart'];

//开始整理搜索条件
$OnlyThisMp = ($_GPC['fromplats'] != $_W['uniacid']) ? FALSE : TRUE;
$allcategory = fmMod_category_get('goods');
$allchidren = $allcategory['child'];

$nowtime = TIMESTAMP;
//分页设置
$pindex = max(1, intval($_GPC['page']));
$settings['search']['goods_list_num']=($settings['search']['goods_list_num']>0) ? $settings['search']['goods_list_num'] :3 ;
$psize = (intval($_GPC['psize'])>0) ? intval($_GPC['psize']) : $settings['search']['goods_list_num'];

//排序及截断(推荐>手动设置的排序数字>拼音首字母)
$showorder = " ORDER BY ";
$limit = " LIMIT ".($pindex - 1) * $psize . ',' . $psize;

//产品模型
$goodstpl=$_GPC['goodstpl'];
if($goodstpl){
	$condition .=" AND goodtpl LIKE :goodtpl";
	$params[':goodtpl']="%". trim($goodstpl)."%";
	$url_condition .="&goodstpl=".$goodstpl;
}

//关键词
$keyword = $_GPC['keyword'];
if($keyword){
	$condition .=" AND title LIKE :keyword";
	$params[':keyword']="%". trim($keyword)."%";
	$url_condition .="&keyword=".$keyword;
}

//整理分类数据
$ccate=$_GPC['ccate'];
$pcate=$_GPC['pcate'];

if($ccate) {
	$pcate = pdo_fetchcolumn("SELECT parentid FROM " . tablename('fm453_shopping_category') . " WHERE id = :id", array(':id' => intval($_GPC['ccate'])));
}
$url_condition .="&pcate=".$pcate;
$url_condition .="&ccate=".$ccate;

$children = array();
$category = array();

//指定父分类时，仅显示子分类
if ($pcate){
	$pcid = intval($pcate);
	$temp_url_condition = str_replace("&pcate=".$pcate, '', $url_condition);
	$temp_url_condition = str_replace("&ccate=".$ccate, '', $url_condition);
	$allcateurl=fm_murl($do, $ac, 'index', array()).$temp_url_condition;//当前分类“全部产品”链接
	$category = $allchidren[$pcate];
	$children[$pcate] = $category;
	$haschild="display:none";
	$ischild="";
	$condition .= " AND pcate = :pcate";
	$params[':pcate']=$pcid;
}else{
	$category = $allcategory['parent'];
	$children = $allchidren;
	$temp_url_condition = str_replace("&pcate=".$pcate, '', $url_condition);
	$temp_url_condition = str_replace("&ccate=".$ccate, '', $temp_url_condition);
	$allcateurl=fm_murl($do, $ac, 'index', array()).$temp_url_condition;//“全部分类”链接
	$haschild="";
	$ischild="display:none";
}

if($ccate>0) {
	$ccid = intval($ccate);
	$condition .= " AND ccate = :ccate";
	$params[':ccate']=$ccid;
	$pagename = $allcategory['child'][$pcate][$ccate]['name'].$pagename;
	$haschild="display:none;";
	$ischild="";
}

/*————————分类排序的记录与处理——————————*/
$sorturl_default = fm_murl($do, $ac, 'index', array("keyword" => $keywords, "pcate" => $pcate, "ccate" => $ccate,"page"=>$page, 'goodstpl' =>$goodstpl));
$sorturl = $sorturl_default;

if (!empty($_GPC['isnew'])) {
	$condition .= " AND isnew = :isnew";
	$params[':isnew'] = 1;
	$sorturl = $sorturl_default;
	$sorturl.="&isnew=1";
	$url_condition .="&isnew=1";
	$footbar_pagename = '新品推荐';
}
if (!empty($_GPC['ishot'])) {
	$condition .= " AND ishot = :ishot";
	$params[':ishot'] = 1;
	$sorturl = $sorturl_default;
	$sorturl.="&ishot=1";
	$url_condition .="&ishot=1";
	$footbar_pagename = '热门推荐';
}
if (!empty($_GPC['isdiscount'])) {
	$condition .= " AND isdiscount = :isdiscount";
	$params[':isdiscount'] = 1;
	$sorturl = $sorturl_default;
	$sorturl.="&isdiscount=1";
	$url_condition .="&isdiscount=1";
	$footbar_pagename = '折扣商品';
}
if (!empty($_GPC['istime'])) {
	$condition .= " AND ( istime = :istime and timestart <= :timestart and timeend >= :timeend )";
	$params[':istime'] = 1;
	$params[':timestart'] = $nowtime;
	$params[':timeend'] = $nowtime;
	$sorturl = $sorturl_default;
	$sorturl.="&istime=1";
	$url_condition .="&istime=1";
	$footbar_pagename = '限时促销';
}
if (!empty($_GPC['isrecommand'])) {
	$condition .= " AND isrecommand = :isrecommand";
	$params[':isrecommand'] = 1;
	$sorturl = $sorturl_default;
	$sorturl.="&isrecommand=1";
	$url_condition .="&isrecommand=1";
	$footbar_pagename = '商家推荐';
}

//添加筛选条件
$condition .= " AND deleted = :deleted";
$params[':deleted']=0;
$condition .= " AND status = :status";
$params[':status']=1;

//进一步排序//ASC正序（由小到大），DESC倒序
	$sortfield = 'isrecommand DESC';	//被推荐的优先
	//排序计算方法，适用ajax处理
	$orderbys = array(
		'updatetime'=>'createtime',
		'view'=>'viewcount',
		'sales'=>'sales',
		'price'=>'marketprice'
	);
	foreach($orderbys as $orderby =>$column){
		$orderkey = "hasorderby".$orderby;
		$sortkey = "by".$orderby;
		$hasorder = $_GPC[$orderkey];
		$url_condition .="&".$orderkey."=".$hasorder;
		$sortby= empty($_GPC[$sortkey]) ? "desc" : strtolower($_GPC[$sortkey]);
		$url_condition .="&".$sortkey."=".$sortby;
		if($hasorder){
			$allissort['by'.$orderby]='active';
			$sortfield .= ", ".$column." " . $sortby;
		}
	}

	$showorder .=  $sortfield." ,  displayorder DESC , convert(title USING gbk) COLLATE gbk_chinese_ci";
	$refreshOrder = ($_GPC['changeorder']==1) ? TRUE : FALSE;
	    $url_condition .="&changeorder=".$_GPC['changeorder'];

	//根据以上条件筛选后得出产品清单
	$list = pdo_fetchall("SELECT id,sn FROM " . tablename('fm453_shopping_goods') . $condition.$showorder.$limit,$params,'id');

	//得出产品总数并做分页计算
	$alltotal['search'] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fm453_shopping_goods') . $condition,$params);
	$total = $alltotal['search'];
	$maxpages = fm_page($total , $psize);
	$allpager['search'] = pagination($total, $pindex, $psize);
	$pager = $allpager['search'];

	foreach ($list as $gid => $r) {
		$gid=$r['id'];
		$allgoods[$gid]=$r;
	}
	if($allgoods) {
	foreach ($allgoods as $gid => $goods) {
		$gid=$goods['id'];
		$gsn=$goods['sn'];
		//获取产品基本信息
		$result_detail=fmMod_goods_detail_all_m($gid);
		$r = $result_detail['data'];
		$goodslabels[$gid] = $r['labels'];

		if ($r['istime'] == 1) {
			$arr = fmFunc_time_tran($r['timeend']);
			$r['timelaststr'] = $arr[0];
			$r['timelast'] = $arr[1];
		}
		//产品自定义标签
		$labels = $r['labels'];
		$r['label_pic_alt']=$labels[0]['title'];
		$r['label_pic_src']=$labels[0]['value'];
		$r['label_span_title']=$labels[1]['title'];
		$r['label_span_value']=$labels[1]['value'];

		$goodsdetails[$gid] = $r;
	}
}
	unset($goods);

	//动态调用Banner图
	$result_banners = fmMod_ad_banner_mine();
	$banners = $result_banners['data'];
	//幻灯片
	$result_advs = fmMod_ad_adv_mine();
	$advs = $result_advs['data'];

	if (empty($pcate)) {
		$lastadvno=count($advs)-1;
		$lastadv=$advs[$lastadvno];
	}else{
		unset($advs);
		if(count($banners)==1) {
			$banner=$banners[0];
			unset($banners);
		}else{
			$advs = $banners;
		}
	}
	$appbanner = (!$advs && !$banners) ? FALSE : TRUE;

//自定义页面默认的分享内容
$_share = array();
$_share['title'] = $pagename.'|'.$shopname.'|'.$_W['account']['name'];
$_share['link']= fm_murl('search', 'goods', 'index', array('keyword'=>$keyword,'page'=>$_GPC['page'],'isrecommand'=>$_GPC['isrecommand'],'isnew'=>$_GPC['isnew'],'ishot'=>$_GPC['ishot'],'istime'=>$_GPC['istime'],'pcate' =>$pcate, 'ccate' =>$ccate, 'a_tpl' =>$a_tpl,'isshare'=>1));
$_share['link']=$_share['link'].$url_condition;
$_share['imgUrl']=tomedia($settings['brands']['logo']);
$_share['desc']=$settings['brands']['share_des'];

if($ccate){
    $_share['title'] = ($category[$ccate]['name']) ? ($category[$ccate]['name'].'|'.$_share['title']) : ($_share['title']);
    $_share['imgUrl'] = ($category[$ccate]['thumb']) ? tomedia($category[$ccate]['thumb']) :
        $_share['imgUrl'];
    $_share['desc'] = ($category[$ccate]['desc']) ? $category[$ccate]['desc'] : $_share['desc'];
}elseif($pcate){
    $_share['title'] = ($allcategory['parent'][$pcate]['name']) ? ($allcategory['parent'][$pcate]['name'].'|'.$_share['title']) : ($_share['title']);
    $_share['imgUrl'] = ($allcategory['parent'][$pcate]['thumb']) ? tomedia($allcategory['parent'][$pcate]['thumb']) :
        $_share['imgUrl'];
    $_share['desc'] = ($allcategory['parent'][$pcate]['desc']) ? $allcategory['parent'][$pcate]['desc'] : $_share['desc'];
}
/* ————————不同请求类型的处理————————————————  */
if($operation=='index') {
	fmFunc_view();
	if(!empty($shareid)){
		fmFunc_share_click($lastid,$shareid,$currentid,$share_user);
	}
	$loadurl = fm_murl($do,$ac,'load',array()).$url_condition;
	include $this->template($appstyle.$do.'/453');
}
elseif($operation=='load') {
	if($_GPC['refresh']==1) {
		include $this->template($appstyle.$do.'/'.$ac.'/'.$operation.'/page');
	}else{
		include $this->template($appstyle.$do.'/453');
	}
}
elseif($operation=='loadmore'){
	include $this->template($appstyle.$do.'/'.$ac.'/'.$operation.'/page');
}
elseif($operation=='addsearch'){
	include $this->template($appstyle.$do.'/'.$ac.'/'.$operation.'/page');
}
