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
 * @remark 文章详情
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;
load()->model('mc');
fm_load()->fm_model('ad');
fm_load()->fm_model('article');
fm_load()->fm_func('stat');
fm_load()->fm_func('share');//链接分享管理
fm_load()->fm_func('view'); 	//浏览量处理

$id = intval($_GPC['id']);
$sn = $_GPC['sn'];
if(empty($sn) && empty($id)) {
	fm_error('未传入文章编号');
}

$_Is_forceLogin = TRUE;
$_Is_forceFollow = TRUE;
$_Is_forceInWeixin = TRUE;
$_Is_forceWXAuth = TRUE;
$_Is_fromShare = FALSE;

//检查是否通过点击分享进来的链接
if(fmFunc_share_check($_W['siteurl']))
{
	//该情况下，取消强制登陆、强制关注、
	$_Is_forceLogin = FALSE;
	$_Is_forceFollow = FALSE;
	$_Is_fromShare = TRUE;
};

//是否需要授权登陆(逻辑：只要网址中checklogin参数非空/0值，即视为需要先登陆；该参数权限最重)
$checklogin = $_GPC['checklogin'];
if($checklogin && !$_W['member']['uid'])
{
	checkauth();
}

//文章详情
$result_article = fmMod_article_detail_m($id,$sn);
$article=$result_article['data'];

//是否强制在微信中打开
if(!$article['forceInWeixin']) {
	$_Is_forceInWeixin = FALSE;
}

if(!$is_wexin && $_Is_forceInWeixin)
{
	exit("请在微信客户端中打开浏览");
}

//是否要求登陆
if($article['a_tpl']=='default' || $article['a_tpl']=='zhaopin' || $article['a_tpl']=='business' || $article['a_tpl']=='notice')
{
	$_Is_forceLogin = FALSE;
}

//是否强制开启微信网页授权
if(!$article['forceWxAuth'])
{
	$_Is_forceWXAuth = FALSE;
}
if($_Is_forceWXAuth)
{
	// $userinfo = fmFunc_fans_getAvatar();//网页授权获取头像、昵称等信息；
	$userinfo = mc_oauth_userinfo();
}

//是否要求关注
/*
	强制关注逻辑判断：系统整体设置>内容/产品模型设置>文章/产品自身设置>特定情境判断
*/
//default等模型的内容不设强制关注(页面本身设置了强制关注的另计)
if($article['a_tpl']=='default' || $article['a_tpl']=='zhaopin' || $article['a_tpl']=='business' || $article['a_tpl']=='notice')
{
	$_Is_forceFollow = FALSE;
}
if(!$article['forceFollow']) {
	$_Is_forceFollow = FALSE;
}
if($_Is_forceFollow)
{
	fmFunc_wechat_checkfollow($_W['openid']);
}


$appstyle = empty($settings['appstyle']) ? FM_APPSTYLE : $settings['appstyle'];
$appsrc =MODULE_URL.'template/mobile/'.$appstyle.'453/';
$htmlsrc =MODULE_URL.'template/mobile/'.$appstyle;
$fm453resource = FM_RESOURCE;

$do=$_GPC['do'];
$ac=$_GPC['ac'];
$uniacid=$_W['uniacid'];
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'index';

$shopname=$settings['brands']['shopname'];
$shopname = !empty($shopname) ? $shopname :FM_NAME_CN;
$pagename = '文章详情';

$share_user=$_GPC['share_user'];
$shareid = intval($_GPC['shareid']);
$lastid = intval($_GPC['lastid']);
$currentid = intval($_W['member']['uid']);
$mySysInfo = fmMod_member_query($currentid); //系统会员信息
$myShopInfo = fmMod_member_query($currentid);	//商城会员信息
$fromplatid = intval($_GPC['fromplatid']);
$from_user = $_W['openid'];
$url_condition="";

//缓存操作-引导页
$is_NoGuide= cache_load($do.'_'.$ac.'_guide'.$_FM['member']['info']['uid'][$id]);
if(!$is_NoGuide) {
	cache_write($do.'_'.$ac.'_guide'.$_FM['member']['info']['uid'][$id],TRUE);
}
$GuideToUrl = $_W['siteurl'];

//COOKIE读写-待
$day_cookies = 15;
if((($_GPC['mid']!=$_COOKIE[$shareid]) && !empty($_GPC['mid']))){
	setcookie($shareid, $_GPC['mid'], time()+3600*24*$day_cookies);
}

//开始根据规则进行前端个别场景处理
$showthumb=$article['thumb'];
$article_tpl=$article['a_tpl'];
$pagename = $article['title'];
$settings['brands']['qrcode'] = tomedia("qrcode_".$_W['acid'].".jpg");

$kefuphone = ($article['kefuphone']) ? $article['kefuphone'] :  $settings['brands']['phone'];
$fromspecial=$_GPC['special'];//是否来自于某个专题页面
$url_condition .= "&special=".$fromspecial;
$direct_url = fm_murl('article','detail', 'index',array('id' => $id, 'sn'=>$sn));

$returnurl = $direct_url;
//支付处理
$pricetype=array(
	'jifen'=>array('title'=>'积分','icon'=>'fa fa-money'),
	'yue'=>array('title'=>'钱包','icon'=>'fa fa-cny'),
	'kaquan'=>array('title'=>'余额','icon'=>'fa fa-ticket')
);
$defaultprice=array();
$defaultprice['jifen']=array('50','100','500');
$defaultprice['yue']=array('1','5','9');
$defaultprice['kaquan']=array('1','10','15');

//根据会员id判断是否已登陆,无id则是未登陆
if(intval($_W['member']['uid'])==0){
	$_NoLogin = TRUE;
}


$uid = $currentid;
$hascredit1 = $_W['member']['credit1'];
if($hascredit1<=0){
	unset($pricetype['jifen']);
}

$isavailable= ($articlestatus>0) ? 1 : 0;//产品是否可用 1是0否
$isshareable=1;//产品是否可分享 1是0否（可分享时会添加特别当前匹配的营销规则链接）
$allowedtobebuy=1; //1允许购买，0不允许购买
if ($article['istime'] == 1)
{
	if (time() < $article['timestart'])
	{
		if($fromspecial !=='presale')
		{ //未从预售专题页（或带有预售专题页来路标识时，如直接输入网址）抵达时，使用常规的时效限制进行判断
			$timenotstarted=1;
			$allowedtobebuy=0;
		}
	}

	if (time() > $article['timeend'])
	{
		$timeended=1;
		$allowedtobebuy=0;
	}
}

$isPayable=$article['isdashang'];	//是否支持支付
if($settings['plat']['oauthid']==1)
{
	$isPayable=1;
}
$disableDashang=TRUE;	//是否关闭打赏功能
if($article['isdashang']==1)
{
	$disableDashang=FALSE;
}
$DsBtnTitle=!isset($settings['article']['DsBtnTitle']) ? '赏' : $settings['article']['DsBtnTitle'];

$disableThumb=TRUE;	//是否不显示缩略图（默认不显示）
$disableThumb = in_array(strtolower($_FM['settings']['ui']['article_member_detail_thumb']),array('true','false')) ? $_FM['settings']['ui']['article_member_detail_thumb'] : FALSE;
if($disableThumb){
	if(in_array($article['a_tpl'],array('member','business','notice')) && !$article['thumbNull']) {
		$disableThumb=FALSE;
	}
}
$thumbInTop=$article['params']['thumbInTop'];	//缩略图是显示在顶部
$thumbInContent=$article['params']['thumbInContent'];//还是显示在正文
if(!$thumbInTop && !$thumbInContent){
	$thumbInTop=TRUE;//如果显示缩略图，默认会显示在顶部
}

$disableComment=FALSE;	//是否关闭评论功能
if($article['iscomment']==0){
	$disableComment=TRUE;
}

$disableDianzan=FALSE;	//是否允许点赞
if($article['isdianzan']==1){
	$disableComment=TRUE;
}

$disableTips=TRUE;	//是否开启tips使用提示

$AvailableForAuthor=FALSE;	//是否允许会员认领其介绍（仅会员模型）
if($article['a_tpl']=='member' && !$article['goodadm']){
	$AvailableForAuthor=TRUE;
}

$disableVcard=FALSE;	//是否开启底部微名片
if($article['isvcard']==1 || !isset($article['isvcard'])){
	$disableVcard=FALSE;
}
if($_FM['settings']['common']['section_vcard_status']==2){
	$disableVcard=TRUE;
}
if(!$disableVcard){
	$vcard = htmlspecialchars_decode($_FM['settings']['common']['section_vcard_content']);
}
$vcard = htmlspecialchars_decode($vcard);

//查询点赞记录
$hasDianzan = fmMod_article_dzQuery($uid=$_FM['member']['info']['uid'],$_W['openid'],$aid=$id,$multi=true,$order=null);
$isDianzanRepeatable = isset($article['dianzanRepeatable']) ? $article['dianzanRepeatable'] : true;	//是否允许重复点赞，默认是
$dianzanRepeatNum = ($article['dianzanRepeatNum']>0) ? $article['dianzanRepeatNum'] : 0;	//允许重复点赞的数量，0为不限
$disableDianzan = FALSE;	//默认不禁用点赞
$hideDianzan = FALSE;	//默认显示点赞
$dianzanLogin = isset($article['dianzanlogin']) ? $article['dianzanlogin'] : true;	//点赞是否需要登陆
if($_W['uniacid']==96){
	$dianzanLogin = TRUE;
}

if($_NoLogin && $dianzanLogin) {
	//需要登陆才能点赞时
	$disableDianzan = TRUE;
	$_DianzanError = -1;
}
if($hasDianzan && !$isDianzanRepeatable){
	//存在点赞记录且不允许重复点赞时,禁用点赞
	$disableDianzan = TRUE;
	$_DianzanError = 1;
}else{
	//已点赞数量 大于等于 允许点赞数时，禁用点赞
	$dianzanNum = count($hasDianzan);
	if($dianzanRepeatNum >0 && $dianzanNum >= $dianzanRepeatNum) {
		$disableDianzan = TRUE;
		$_DianzanError = 2;
	}
}


//畅言评论初始化
$_FM['settings']['api']['changyan_appId'] = ($_FM['settings']['api']['changyan_appId']) ? $_FM['settings']['api']['changyan_appId'] : 'cysQrSTqK';
$_FM['settings']['api']['changyan_conf'] = ($_FM['settings']['api']['changyan_conf']) ? $_FM['settings']['api']['changyan_conf'] : 'prod_9e663cdf33cf715d33172b6cc0d0bb7f';

//对文内电话号码进行正则修饰
$isFormatable = true;
if($isFormatable){
    $article['content'] = fmFunc_tel_addlink($article['content']);
}

//是否允许阅读全文,默认是；否时对正文内容进行替换
$readable = true;
if(!$readable) {
	$article['content'] = $article['description'];
	$article['content'] .="亲，您需要关注我们才能查看完整内容！"."\r\n";
	$article['content'] .="<a class='btn btn-info btn-block mui-btn mui-btn-info mui-btn-blok' href='".fm_murl('index','index','index',array())."'> 点此前往关注引导页！</a>";
}

//取会员注册链接地址,默认选择有求必应表单插件的会员模型
$memberReg = $_FM['settings']['member_regUrl'];

$needsMember=pdo_fetchcolumn("SELECT id FROM ".tablename("fm453_shopping_needs")." WHERE `uniacid`= :uniacid AND `template` = :template AND `status` = :status AND `deleted` = :deleted ORDER BY createtime DESC",array(":uniacid"=>$uniacid,":template"=>"MemberInfo",":status"=>1,":deleted"=>0));
if(!$memberReg) {
	if($needsMember) {
		$needsMemberForm=fm_murl('needs','detail','index',array("id"=>$needsMember));
		$memberReg=$needsMemberForm;
	}else{
		$memberReg=FALSE;
	}
}

//取会员手机号
$publicPhone= isset($article['is_public_mobile']) ? $article['is_public_mobile'] : TRUE;//是否允许公开手机号,默认公开
$kefuphone=$_FM['settings']['brands']['phone'];
if($article['kefuphone'] && $publicPhone) {
	$authorphone=$article['kefuphone'];
}

//取会员UID
$article['member_id'] = mc_openid2uid($article['goodadm']);
$vcard_link = fm_murl('vcard','detail','index',array('uid'=>$article['member_id']));	//微名片链接

//自定义微信分享内容
$_share = array();
$_share['title'] = $article['title'];
$_share['link'] = fm_murl($do,$ac,'index',array('id'=>$id,'sn'=>$sn,'isshare'=>1));
$_share['link'] = $_share['link'].$url_condition;
$_share['imgUrl'] = tomedia($showthumb);
$_share['desc'] = $article['description'];

if($operation=='loadmore'){
	if($pindex=1) {
		include $this->template($appstyle.$do.'/'.$ac.'/'.$operation.'/page');
	}
}
elseif($operation=='load'){
	if($_GPC['refresh']==1) {
		include $this->template($appstyle.$do.'/'.$ac.'/'.$operation.'/page');
	}else{
		include $this->template($appstyle.$do.'/453');
	}
}
elseif($operation=='contact'){
	//联系作者
}elseif($operation=='aftershare'){

	$hasShare = fmMod_article_fxQuery($uid=$_FM['member']['info']['uid'],$_W['openid'],$aid=$id,$multi=false,$order=null);
	if(!$hasShare) {
		//没有分享记录，执行一次记录
		$data=array();
		$data['artid']=$id;
		$data['do']='share';
		$data['openid']=$_W['openid'];
		$data['uid']=$_FM['member']['info']['uid'];
		$data['arttitle']=$article['title'];
		$data['avatar']=$_FM['member']['info']['avatar'];
		$data['viewtime']=$_W['timestamp'];
		$result = pdo_insert('fm453_site_article_viewlog',$data);
	}

	//分享次数累加
	$nowFxNum=$article['sharecount'];
	pdo_update('fm453_site_article',array('sharecount'=>$nowFxNum+1),array('id'=>$id));
	die(json_encode($nowFxNum+1));
}
elseif($operation=='dianzan'){
	$return = array();
	$return['errorcode'] = 0;

	if($disableDianzan) {
		$return['errorcode'] = $_DianzanError;
		switch($_DianzanError) {
			case -1:
				$return['msg'] = '您需要登陆之后才能点赞';
			break;
			case 1:
				$return['msg'] = '您已经点过赞了；当前内容不允许重复点赞';
			break;
			case 2:
				$return['msg'] = '您为当前内容的点赞次数已达上限了';
			break;
			default:
				$return['msg'] = '本内容的点赞功能已关闭';
			break;
		}
		die(json_encode($return['errorcode']));
	}

	$hasDianzan = fmMod_article_dzQuery($uid=$_FM['member']['info']['uid'],$_W['openid'],$aid=$id,$multi=false,$order=null);
	if(!$hasDianzan) {
		//没有点赞记录，执行一次记录
		$data=array();
		$data['artid']=$id;
		$data['do']='dianzan';
		$data['openid']=$_W['openid'];
		$data['uid']=$_FM['member']['info']['uid'];
		$data['arttitle']=$article['title'];
		$data['avatar']=$_FM['member']['info']['avatar'];
		$data['viewtime']=$_W['timestamp'];
		$result = pdo_insert('fm453_site_article_viewlog',$data);
	}

	//点赞数
	$nowDzNum=$article['dianzan'];
	pdo_update('fm453_site_article',array('dianzan'=>$nowDzNum+1),array('id'=>$id));

	//发微信模板通知
	require_once MODULE_ROOT.'/core/msgtpls/tpls/article.php';
	$postdata = $tplnotice_data['dianzan']['admin'];
	$result= fmMod_notice_tpl($postdata);
	if(!$result) {
		require MODULE_ROOT.'/core/msgtpls/msg/article.php';
		$postdata = $notice_data['dianzan']['admin'];
		$result= fmMod_notice($settings['manageropenids'],$postdata);
	}
	if($article['goodadm']) {
		$postdata = $tplnotice_data['dianzan']['goodadm'];
		$result= fmMod_notice_tpl($postdata);
		if(!$result) {
			require MODULE_ROOT.'/core/msgtpls/msg/article.php';
			$postdata = $notice_data['dianzan']['goodadm'];
			$result= fmMod_notice($article['goodadm'],$postdata);
		}
	}
	$postdata = $tplnotice_data['dianzan']['reader'];
	$result= fmMod_notice_tpl($postdata);
	if(!$result) {
		require MODULE_ROOT.'/core/msgtpls/msg/article.php';
		$postdata = $notice_data['dianzan']['reader'];
		$result= fmMod_notice($_W['openid'],$postdata);
	}

	//输出点赞处理结果
	die(json_encode($return['errorcode']));

}elseif($operation=='index'){
	//更新流量、链路统计
	fmFunc_view();
	if(!empty($shareid)){
		fmFunc_share_click($lastid,$shareid,$currentid,$share_user);
	}
	//更新浏览量
 	fmFunc_stat_view('article',$id);
	fmMod_member_check($_W['openid']);//检测会员

	//检测当前会员的介绍资料是否存在
	$myArticle_id=pdo_fetchcolumn("SELECT id FROM " . tablename('fm453_site_article') . " WHERE uniacid = :uniacid  AND a_tpl = :a_tpl AND goodadm LIKE :goodadm ORDER BY id ASC",array(":uniacid"=>$_W['uniacid'],":a_tpl"=>'member',':goodadm'=>$from_user));
	if($myArticle_id) {
		$myArticle_url = fm_murl('article','detail','index',array('id'=>$myArticle_id));
		cache_write("articleId_".$_W['openid'],$myArticle_id);
	}

	// 模板主框架（父页面）
	$allowedtobebuy=1; //1允许购买，0不允许购买
	$isavailable = 1;
	//商城处于关闭状态时，文章作单页面展示，不显示底导航
	if(!$settings['onoffs']['isopenning']){
		$isNav = 0;
	}
	//查询点赞记录
	$DianzanList = fmMod_article_dzQuery($uid=null,'',$aid=$id,$multi=true,$order=null);
	foreach($DianzanList as &$dl){
		$_temp_openid = $dl['openid'];
		//为空头像的记录添加虚拟头像
		if(empty($dl['avatar'])){
			$dl['avatar'] = fmFunc_fans_virtualAvatar($_temp_openid);
		}
	}
	unset($dl);

	include $this->template($appstyle.$do.'/453');
	//放在页面渲染完成后再执行的动作
	//是否自动生成会员介绍文章
	if($settings['onoff']['memberAutoContent'])
	{
		if($_FM['member']['info']['follow']==1 && !$myArticle_id)
			{
				fmMod_article_memberAutoContent($currentid,$_W['openid']);
			}
	}
}
