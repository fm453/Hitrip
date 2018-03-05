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
 * @remark 电子名片列表页
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;
fm_load()->fm_model('category');
fm_load()->fm_model('ad');
fm_load()->fm_model('article');

if($settings['force_follow']){
	checkfollow($FM_member);
}

fm_load()->fm_func('share');//链接分享管理
fm_load()->fm_func('view'); 	//浏览量处理
fm_load()->fm_func('wechat');//微信定义管理

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
$pagename = '电子名片列表';

$uniacid=$_W['uniacid'];
$plattype=$settings['plattype'];
$platids= fmFunc_getPlatids();//获取平台模式关联的公众号商户ID参数
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
$from_user = $_W['openid'];
$url_condition="";
$direct_url = fm_murl($so,$ac,'index',array());

//初始化一些数据
$allcategory = array();
$allchidren = array();
$allarticle = array();//存入全部文章id\sn
$alldetail = array();//存入全部文章详情
$alltotal = array();
$allpager = array();
$allurl = array();

$is_wexin = fmFunc_wechat_is();
$userinfo = fmFunc_fans_oauth_getInfo();//网页授权获取头像、昵称等信息；

//开始整理搜索条件
$OnlyThisMp = ($_GPC['fromplats'] != $_W['uniacid']) ? FALSE : TRUE;
$allcategory = fmMod_category_get('article');

$allchidren = $allcategory['child'];

$nowtime = TIMESTAMP;
//分页设置
$pindex = max(1, intval($_GPC['page']));
$settings['search']['article_list_num']=($settings['search']['article_list_num']>0) ? $settings['search']['article_list_num'] :12;

$psize = (intval($_GPC['psize'])>0) ? intval($_GPC['psize']) : $settings['search']['article_list_num'];
//排序及截断(推荐>手动设置的排序数字>拼音首字母)
$showorder = " ORDER BY ";
$limit = " LIMIT ".($pindex - 1) * $psize . ',' . $psize;
if($operation=='loadmore')
{
    $limit = " LIMIT ".(($pindex - 1) * $psize + $settings['search']['article_list_num']) . ',' . $psize;
}

//文章模型(需要排除掉会员介绍内容模型--TODO)
$a_tpl=$_GPC['a_tpl'];
if($a_tpl){
	$condition .=" AND a_tpl LIKE :a_tpl";
	$params[':a_tpl']="%". trim($a_tpl)."%";
	$url_condition .="&a_tpl=".$a_tpl;
}

//关键词
$keyword = $_GPC['keyword'];
if($keyword){
	$condition .=" AND (keywords LIKE :keyword or title LIKE :title)";
	$params[':keyword']="%". trim($keyword)."%";
	$params[':title']="%". trim($keyword)."%";
	$url_condition .="&keyword=".$keyword;
}

//整理分类数据
$ccate=$_GPC['ccate'];
$pcate=$_GPC['pcate'];
if($ccate) {
	$pcate = pdo_fetchcolumn("SELECT psn FROM " . tablename('fm453_shopping_category') . " WHERE sn = :sn", array(':sn' => intval($_GPC['ccate'])));
}
$url_condition .="&pcate=".$pcate;
$url_condition .="&ccate=".$ccate;
$children = array();
//指定父分类时，仅显示子分类
if ($pcate>0) {
	$allcateurl=fm_murl('article', 'list', 'index', array("pcate" =>$pcate)).$url_condition;//当前分类“全部文章”链接
	$category = $allchidren[$pcate];
	$children[$pcate] = $category;
	$haschild="display:none";
	$ischild="";
	$pcid = intval($pcate);
	$condition .= " AND pcate = :pcate";
	$params[':pcate']=$pcid;
}else {
	$category = $allcategory['parent'];
	$children = $allchidren;
	$allcateurl=fm_murl('article', 'list', 'index', array());//“全部分类”链接
	$haschild="";
	$ischild="display:none";
}
if($ccate>0) {
	$ccid = intval($ccate);
	$condition .= " AND ccate = :ccate";
	$params[':ccate']=$ccid;
    $allcateurl=fm_murl('article', 'list', 'index', array("pcate" =>$pcate));//当前分类“全部文章”链接
	$pagename = $allcategory['child'][$pcate][$ccate]['name'].$pagename;
	$haschild="display:none;";
	$ischild="";
}

/*————————分类排序的记录与处理——————————*/
$sorturl_default = fm_murl('article', 'list', 'index', array("a_tpl"=>$a_tpl,"keyword" => $keyword, "pcate" => $pcate, "ccate" => $ccate,"page"=>$pindex));
$sorturl = $sorturl_default;

if (!empty($_GPC['isnew'])) {
	$condition .= " AND isnew = :isnew";
	$params[':isnew'] = 1;
	$sorturl = $sorturl_default;
	$sorturl.="&isnew=1";
	$url_condition .="&isnew=1";
	$footbar_pagename = '最新推荐';
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
	$footbar_pagename = '折扣活动';
}
if (!empty($_GPC['istime'])) {
	$condition .= " AND ( istime = :istime and timestart <= :timestart and timeend >= :timeend )";
	$params[':istime'] = 1;
	$params[':timestart'] = $nowtime;
	$params[':timeend'] = $nowtime;
	$sorturl = $sorturl_default;
	$sorturl.="&istime=1";
	$url_condition .="&istime=1";
	$footbar_pagename = '限时推荐';
}
if (!empty($_GPC['isrecommand'])) {
	$condition .= " AND isrecommand = :isrecommand";
	$params[':isrecommand'] = 1;
	$sorturl = $sorturl_default;
	$sorturl.="&isrecommand=1";
	$url_condition .="&isrecommand=1";
	$footbar_pagename = '平台推荐';
}

//添加筛选条件
$condition .= " AND deleted = :deleted";
$params[':deleted']=0;
$condition .= " AND status = :status";
$params[':status']=1;

//进一步排序
	$sortfield = 'isrecommand DESC';	//被推荐的优先
	//排序计算方法，适用ajax处理
	$hasorderbytime= $_GPC['isupdatetime'];
    	$url_condition .="&isupdatetime=".$hasorderbytime;
	$hasorderbyview= $_GPC['isview'];
		$url_condition .="&isview=".$hasorderbyview;
	$hasorderbydianzan= $_GPC['isdianzan'];
	    $url_condition .="&isdianzan=".$hasorderbydianzan;
	$hasorderbysales= $_GPC['issales'];
		$url_condition .="&issales=".$hasorderbysales;
	//ASC正序（由小到大），DESC倒序
	$sortbytime = empty($_GPC['byupdatetime']) ? "desc" : strtolower($_GPC['byupdatetime']);	//更新时间
		$url_condition .="&byupdatetime=".$_GPC['byupdatetime'];
	$sortbysales = empty($_GPC['bysales']) ? "desc" : strtolower($_GPC['bysales']);	//销量
	    $url_condition .="&bysales=".$_GPC['bysales'];
	$sortbydianzan = empty($_GPC['bydianzan']) ? "desc" : strtolower($_GPC['bydianzan']);	//点赞量
	    $url_condition .="&bydianzan=".$_GPC['bydianzan'];
	$sortbyview = empty($_GPC['byview']) ? "desc" : strtolower($_GPC['byview']);	//浏览量
	    $url_condition .="&byview=".$_GPC['byview'];

	if($hasorderbytime=='true'){
		$allissort[0]='active';
		$sortfield .= ", updatetime " . $sortbytime;
	}

	if($hasorderbyview=='true'){
		$allissort[1]='active';
		$sortfield .= ", viewcount " . $sortbyview;
	}
	if($hasorderbydianzan=='true'){
		$allissort[2]='active';
		$sortfield .= ", dianzan " . $sortbydianzan;
	}
	if($hasorderbysales=='true'){
		$allissort[3]='active';
		$sortfield .= ", sales " . $sortbysales;
	}
	$showorder .=  $sortfield." ,  displayorder DESC , convert(title USING gbk) COLLATE gbk_chinese_ci";
	$refreshOrder = ($_GPC['changeorder']==1) ? TRUE : FALSE;
	    $url_condition .="&changeorder=".$_GPC['changeorder'];

	//根据以上条件筛选后得出文章清单
	$list = pdo_fetchall("SELECT id,sn FROM " . tablename('fm453_site_article') . $condition.$showorder.$limit,$params,'id');
	//得出文章总数并做分页计算
	$alltotal['search'] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fm453_site_article') . $condition,$params);
	$total = $alltotal['search'];
	$maxpages = fm_page($total , $psize);
	$allpager['search'] = pagination($total, $pindex, $psize);
	$pager = $allpager['search'];
	$allgoods=array();
	foreach ($list as $gid => $r) {
		$gid=$r['id'];
		$allgoods[$gid]=$r;
	}
	if($allgoods)
	{
		foreach ($allgoods as $gid => $goods)
		{
			$gid=$goods['id'];
			$gsn=$goods['sn'];
			//获取文章基本信息
			$result_detail=fmMod_article_detail_m($gid);
			$r = $result_detail['data'];
			if ($r['istime'] == 1)
			{
				$arr = fmFunc_time_tran($r['timeend']);
				$r['timelaststr'] = $arr[0];
				$r['timelast'] = $arr[1];
			}
			$alldetail[$gid] = $r;
		}
	}
	$allarticle=$allgoods;
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
$_share['link']= fm_murl('article', 'list', 'index', array('keyword'=>$keyword,'page'=>$_GPC['page'],'isrecommand'=>$_GPC['isrecommand'],'isnew'=>$_GPC['isnew'],'ishot'=>$_GPC['ishot'],'istime'=>$_GPC['istime'],'pcate' =>$pcate, 'ccate' =>$ccate, 'a_tpl' =>$a_tpl,'isshare'=>1));
$_share['link']=$_share['link'].$url_condition;
$_share['imgUrl']=tomedia($settings['brands']['logo']);
$_share['desc']=$settings['brands']['share_des'];

if($ccate){
    $_share['title'] = ($category[$ccate]['name']) ? ($category[$ccate]['name'].'|'.$_share['title']) : ($_share['title']);
    $_share['imgUrl'] = ($category[$ccate]['thumb']) ? tomedia($category[$ccate]['thumb']) :
        $_share['imgUrl'];
    $_share['desc'] = ($category[$ccate]['desc']) ? $category[$ccate]['desc'] : $_share['desc'];
}
elseif($pcate){
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
	$loadurl = fm_murl($do,$ac,'load',array('keyword'=>$keyword,'page'=>$_GPC['page'],'isrecommand'=>$_GPC['isrecommand'],'isnew'=>$_GPC['isnew'],'ishot'=>$_GPC['ishot'],'istime'=>$_GPC['istime'], 'a_tpl' =>$a_tpl)).$url_condition;
	include $this->template($appstyle.$do.'/453');
}
elseif($operation=='rec'){
	/*
	*	默认推荐内容
	*/
	$pagename="推荐内容";
	include $this->template($appstyle.$do.'/453');
}
elseif($operation=='subcate'){
	/*
	*	二级分类
	*/
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
