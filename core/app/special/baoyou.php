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
 * @remark 专题页-包邮专区
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;
fm_load()->fm_model('ad');
fm_load()->fm_model('category');
fm_load()->fm_model('goods');
fm_load()->fm_model('article');
fm_load()->fm_func('share');//链接分享管理
fm_load()->fm_func('view'); 	//浏览量处理
fm_load()->fm_func('wechat');//微信定义管理
//检查是否关店
fm_checkopen($settings['onoffs']);
checkauth();	//专题活动需要用户登陆
$appstyle = empty($settings['appstyle']) ? FM_APPSTYLE : $settings['appstyle'];
$appsrc =MODULE_URL.'template/mobile/'.$appstyle.'453/';
$htmlsrc =MODULE_URL.'template/mobile/'.$appstyle;
$fm453resource = FM_RESOURCE;

$do= $_GPC['do'];
$ac=$_GPC['ac'];
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'index';

$shopname=$settings['brands']['shopname'];
$shopname = !empty($shopname) ? $shopname :FM_NAME_CN;
$pagename = '新用户专享礼包';
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
$_GPC['fromplats']=$_W['uniacid'];	//暂时只允许调用本公众号自有的专题产品
require_once FM_PUBLIC.'forsearch.php';

$share_user=$_GPC['share_user'];
$shareid = intval($_GPC['shareid']);
$lastid = intval($_GPC['lastid']);
$currentid = intval($_W['member']['uid']);
$fromplatid = intval($_GPC['fromplatid']);
$from_user = $_W['openid'];
$url_condition="";



$url_condition .= "&fromplatid=".$fromplatid;
$direct_url = fm_murl($do,$ac,$operation,array());

$url_condition .= '&page='.$_GPC['page'];
$url_condition .= '&rpage='.$_GPC['rpage'];

$is_wexin = fmFunc_wechat_is();
$userinfo=fmFunc_fans_oauth_getInfo();//网页授权获取头像、昵称等信息；
$carttotal = fmMod_shopcart_total();

//幻灯片
$result_advs = fmMod_ad_adv_mine();
$advs = $result_advs['data'];
$lastadvno=count($advs)-1;
$lastadv=$advs[$lastadvno];
$firstadv=$advs[0];

//广告
$result_ads = fmMod_ads_mine('special');
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

//分页设置
$rpindex = max(1, intval($_GPC['rpage']));
//$rpsize = ($settings['temai_num']>0)  ? $settings['temai_num'] : 3;
$rpsize =  8;
$pindex = max(1, intval($_GPC['page']));
$psize = (intval($_GPC['psize'])>0) ? intval($_GPC['psize']) : 2;

//排序及截断
$showorder = " ORDER BY  displayorder DESC , createtime DESC , uniacid ASC";
$limit = " LIMIT ".($pindex - 1) * $psize . ',' . $psize;
$rshoworder = " ORDER BY displayorder DESC , createtime DESC , uniacid ASC";
$rlimit = " LIMIT ".($rpindex - 1) * $rpsize . ',' . $rpsize;
if($operation=='loadmore'){
	$rlimit = " LIMIT ".($rpindex * $psize) . ',' . $psize;
}

//取得模型下的产品IDS
$ids=fmMod_goods_getId_special('gift')['data'];

unset($goods);
//自定义微信分享内容
$_share = array();
$_share['title'] = $settings['brands']['shopname'];
$_share['link'] = fm_murl($do,$ac,$operation,array('isshare'=>1));
$_share['link'] = $_share['link'].$url_condition;
$_share['imgUrl'] = tomedia($settings['brands']['logo']);
$_share['desc'] = $_share['desc'] = $settings['brands']['share_des'];
if(!empty($shareid)){
	fmFunc_share_click($lastid,$shareid,$currentid,$share_user);
}
include $this->template($appstyle.$do.'/453');
