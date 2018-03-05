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
 * @remark 会员搜索页
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;
fm_load()->fm_model('category');
fm_load()->fm_model('goods');
fm_load()->fm_model('ad');
fm_load()->fm_model('article');
fm_load()->fm_func('share');//链接分享管理
fm_load()->fm_func('view'); 	//浏览量处理
fm_load()->fm_func('wechat');//微信定义管理

//是否关店歇业
fm_checkopen($settings['onoffs']);
//检查是否关注
fmFunc_wechat_checkfollow($_W['openid']);
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
$from_user = $_W['openid'];
$url_condition="";
$direct_url = fm_murl($do,$ac,'index',array());

//初始化一些数据
$allcategory = array();
$allchidren = array();
$allmember = array();//存入全部会员id\sn
$alltotal = array();
$allpager = array();
$allurl = array();

$is_wexin = fmFunc_wechat_is();
//$userinfo = fmFunc_fans_oauth_getInfo();//网页授权获取头像、昵称等信息；

//开始整理搜索条件
$OnlyThisMp = ($_GPC['fromplats'] != $_W['uniacid']) ? FALSE : TRUE;
$allcategory = fmMod_category_get();
$allchidren = $allcategory['child'];
$nowtime = TIMESTAMP;
//分页设置
$pindex = max(1, intval($_GPC['page']));
$settings['search']['member_list_num']=($settings['search']['member_list_num']>0) ? $settings['search']['member_list_num'] :20 ;
$psize = (intval($_GPC['psize'])>0) ? intval($_GPC['psize']) : $settings['search']['member_list_num'];

//排序及截断(推荐>手动设置的排序数字>拼音首字母)
$showorder = " ORDER BY ";
$showorder .=  " displayorder DESC , convert(realname USING gbk) COLLATE gbk_chinese_ci";
$limit = " LIMIT ".($pindex - 1) * $psize . ',' . $psize;

//关键词
	$keyword=trim($_GPC['keyword']);
		if(!empty($keyword)){
		$condition.=' AND ';
		$condition.='(';
		$condition.=' keyword LIKE :keyword ';
		$params[':keyword']='%'.$keyword.'%';
		$condition.=' OR ';
		$condition.=' realname LIKE :realname ';
		$params[':realname']='%'.$keyword.'%';
		$condition.=' OR ';
		$condition.=" mobile LIKE :mobile ";
		$params[':mobile']='%'.$keyword.'%';
		$condition.=')';
		}

	$total = pdo_fetchcolumn("SELECT COUNT(uid) FROM ".tablename('fm453_shopping_member').$condition, $params);
	$pager = pagination($total, $pindex, $psize);
	$list = pdo_fetchall("SELECT * FROM ".tablename('fm453_shopping_member').$condition.$showorder.$limit, $params);
	$members=array();
	foreach($list as $row){
		$uid=$row['uid'];
		$members[$uid] = fmMod_member_query($uid);
		$members[$uid] = $members[$uid]['data'];
	}

	$maxpages = fm_page($total , $psize);
	$allpager['search'] = pagination($total, $pindex, $psize);
	$pager = $allpager['search'];

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
		}elseif(count($banners)>1){
			//$advs = $banners;
			//$lastadvno=count($advs)-1;
		    //$lastadv=$advs[$lastadvno];
		}else{
			$advs = $banners;
		}
	}
	$appbanner = (!$advs && !$banners) ? FALSE : TRUE;

//自定义页面默认的分享内容
$_share = array();
$_share['title']=$shopname.$pagename.'|'.$_W['account']['name'];;
$_share['link']= fm_murl($do, $ac, 'index', array('keyword'=>$keyword,'isshare'=>1));
$_share['link']=$_share['link'].$url_condition;
$_share['imgUrl']=tomedia($settings['brands']['logo']);
$_share['desc']=$settings['brands']['share_des'];

/* ————————不同请求类型的处理————————————————  */
if($operation=='index') {
	fmFunc_view();
	if(!empty($shareid)){
		fmFunc_share_click($lastid,$shareid,$currentid,$share_user);
	}
	$loadurl = fm_murl($do,$ac,'load',array('keyword'=>$keyword));
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
	if($pindex>1) {
		include $this->template($appstyle.$do.'/'.$ac.'/'.$operation.'/page');
	}else{
		include $this->template($appstyle.$do.'/'.$ac.'/'.$operation.'/page');
	}
}
elseif($operation=='addsearch'){
	if($_GPC['has_search']==1) {
		include $this->template($appstyle.$do.'/453');
	}else{
		include $this->template($appstyle.$do.'/'.$ac.'/'.$operation.'/page');
	}
}
