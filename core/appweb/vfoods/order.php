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
 * @remark 核销端-微餐饮订单管理
*/
defined('IN_IA') or exit('Access Denied');
global $_W, $_GPC,$_FM;
load()->func('tpl');
if(intval($_GPC['i'])<=0) {
	$_W['uniacid']=1;
	$_GPC['i']=1;
}
fm_load()->fm_func('share');//链接分享管理
fm_load()->fm_func('server'); //授权服务器
fm_load()->fm_func('fans'); //粉丝处理函数库
fm_load()->fm_func('tables'); //数据表函数
fm_load()->fm_func('qrcode'); //二维码处理
fm_load()->fm_model('log'); //日志模块
fm_load()->fm_func('msg');//消息通知前置函数
fm_load()->fm_model('notice');//消息通知模块
fm_load()->fm_model('member');//会员管理模块
fm_load()->fm_model('shopcart'); //购物车模块
fm_load()->fm_func('ui');//页面视图
fm_load()->fm_func('tpl');//页面代码块
fm_load()->fm_func('template');//页面模板调用
fm_load()->fm_func('data');//统一数据处理方法
fm_load()->fm_func('market');//营销管理
fm_load()->fm_func('view'); 	//浏览量处理
fm_load()->fm_func('wechat');//微信定义管理
fm_load()->fm_func('mobile'); 	//手机号处理
fm_load()->fm_func('bankcard'); 	//银行卡处理
fm_load()->fm_func('pay');	//支付后处理
fm_load()->fm_func('api');	//云数据接口管理

//加载模块配置参数
$settings = fmMod_settings_all();//全局加载配置
$settings['appstyle']= ($settings['appstyle']=='mui/') ? 'default/' : $settings['appstyle'];
$_FM['settings']=$settings;
//加载风格模板及资源路径
$appstyle      = empty($settings['appstyle']) ? FM_APPSTYLE : $settings['appstyle'];
$appsrc        =MODULE_URL.'template/mobile/'.$appstyle.'453/';
$htmlsrc       =MODULE_URL.'template/mobile/'.$appstyle;
$fm453resource = FM_RESOURCE;
//入口判断
$do            = $_GPC['do'];
$_do            = 'vfoods';
$ac            = $_GPC['ac'];
$op            = !empty($_GPC['op']) ? $_GPC['op'] : 'index';

//开始操作管理
$shopname      =$settings['brands']['shopname'];
$shopname      = !empty($shopname) ? $shopname :FM_NAME_CN;

$uniacid       =$_W['uniacid'];
$plattype      =$settings['plattype'];
$platids       =fmFunc_getPlatids();//获取平台模式关联的公众号商户ID参数
$platid        =$_W['uniacid'];
$oauthid       =$platids['oauthid'];
$fendianids    =$platids['fendianids'];
$supplydianids =$platids['supplydianids'];
$blackids      =$platids['blackids'];

//平台模式处理
require_once FM_PUBLIC.'plat.php';
$supplydians   =explode(',',$supplydianids);//字符串转数组
$supplydians   =array_filter($supplydians);//数组去空

//按平台模式前置筛选条件
$condition     =' WHERE ';
$params        =array();
require_once FM_PUBLIC.'forsearch.php';

$share_user    =$_GPC['share_user'];
$shareid       = intval($_GPC['shareid']);
$lastid        = intval($_GPC['lastid']);
$currentid     = intval($_W['member']['uid']);
$fromplatid    = intval($_GPC['fromplatid']);
$from_user     = $_W['openid'];
$url_condition ="";
$direct_url    = fm_murl($do,$ac,$op,array());

//自定义微信分享内容
$_share           = array();
$_share['title']  = $pagename.'|'.$_W['account']['name'];
$_share['link']   = fm_murl($do,$ac,$operation,array('isshare'=>1));
$_share['link']   = $_share['link'].$url_condition;
$_share['imgUrl'] = tomedia($settings['brands']['logo']);
$_share['desc']   = $settings['brands']['share_des'];

// $resultmember     = fmMod_member_query($currentid);
// $FM_member        =$resultmember['data'];

//会员自定义设置
$mine_settings    =$_FM['member']['settings'];

$orderid = intval($_GPC['id']);
if($orderid){
	$pcateid = pdo_fetchcolumn("SELECT `pcate` FROM ".tablename('fm453_vfoods_order')." WHERE id = '{$orderid}'");
	$pcate = $pcateid;
	$pcate2 = array();
	$pcate2[0] = pdo_fetchall("SELECT * FROM ".tablename('fm453_vfoods_category')." WHERE uniacid = :uniacid AND id =:pcateid  ORDER BY psn ASC, displayorder DESC",array(':uniacid'=>$_W['uniacid'],':pcateid'=>$pcateid));
}else{
	$ccate2 = pdo_fetchall("SELECT * FROM ".tablename('fm453_vfoods_foods')." WHERE uniacid = '{$_W['uniacid']}' AND ccate = '{$_GPC['ccate']}' ");	//菜品类
	$pcate2 = pdo_fetchall("SELECT * FROM ".tablename('fm453_vfoods_category')." WHERE uniacid = '{$_W['uniacid']}' AND (id = '{$_GPC['pcate']}' OR id = '{$ccate2[0]['pcate']}') ORDER BY psn ASC, displayorder DESC");	//餐厅
}

$group = pdo_fetch("SELECT * FROM ".tablename('mc_members')." WHERE uniacid = '{$_W['uniacid']}' AND uid = '{$currentid}'");
$groupid = $group['groupid'];

$sms = pdo_fetch("SELECT * FROM ".tablename('fm453_vfoods_sms')." WHERE uniacid = '{$_W['uniacid']}'");	//基础设定
$settings['manageropenids'] = $settings['manageropenids'].','.$settings['vfoods']['basic']['managers'].','.$pcate2['managers'];
$_managers = explode(',',$settings['manageropenids']);
$_managers = array_unique($_managers);

if(!in_array($from_user,$_managers)){
	message('您没有核销权限！', '','error');
}elseif($groupid != $pcate2[0]['mbgroup']){
	//message('您所在会员组没有对此店的操作权限！', '','error');
}

if($op == 'delete'){
	pdo_delete('fm453_vfoods_order', array('id' => $orderid));
	pdo_delete('fm453_vfoods_order_foods', array('orderid' => $orderid ));
	message('彻底删除订单成功！', fm_murl($do,$ac,'index', array('pcate'=>$_GPC['pcate'],'ccate'=>$_GPC['ccate'])), 'success');
}
else if($op == 'wancheng'){
	pdo_update('fm453_vfoods_order', array('status' => 0), array('id' => $orderid ));
	message('订单转为已完成！', fm_murl($do,$ac,'index', array('pcate'=>$_GPC['pcate'],'ccate'=>$_GPC['ccate'])), 'success');
}
else if($op == 'yixia'){
	pdo_update('fm453_vfoods_order', array('status' => 2), array('id' => $orderid));
	message('订单转为已下单！', fm_murl($do,$ac,'index', array('pcate'=>$_GPC['pcate'],'ccate'=>$_GPC['ccate'])), 'success');
}
else if($op == 'jieshou'){
	pdo_update('fm453_vfoods_order', array('status' => 3), array('id' => $orderid));
	$item0 = pdo_fetch("SELECT * FROM ".tablename('fm453_vfoods_order')." WHERE id = '{$orderid}'");
	$foodsid = pdo_fetchall("SELECT foodsid, total FROM ".tablename('fm453_vfoods_order_foods')." WHERE orderid = '{$item0['id']}'", array(), 'foodsid');
	$foods = pdo_fetchall("SELECT * FROM ".tablename('fm453_vfoods_foods')."  WHERE id IN ('".implode("','", array_keys($foodsid))."')");

	$print = pdo_fetchall("SELECT * FROM ".tablename('fm453_vfoods_print')." WHERE cateid = '{$pcate2[0]['id']}' AND enabled = 1");
	if($sms['smsnum'] && $sms['smspsw']){
		$body = '您向--'.$pcate2[0]['name'].'--预定单号为'.$item0['ordersn'].'总价为'.$item0['price'].'元的订单已被确认，请留意接听电话或及时联系我们，如有疑问请电联'.$pcate2[0]['shouji'];
		$res = fmFunc_foods_sendSMS($sms['smsnum'],$sms['smspsw'],$item0['mobile'],$body);
	}
	foreach($print as $printrow){
		if($printrow['deviceno'] && $printrow['key'] && $printrow['printtime'] > 0){
			fm_load()->fm_class('HttpClient');
			$deviceno = $printrow['deviceno'];
			$key =$printrow['key'];
			$printtime = $printrow['printtime'];
	        define('FEIE_HOST','115.28.225.82');
	        define('FEIE_PORT',80);
			$orderInfo  = '<CB>'.$pcate2[0]['name'].'</CB><BR>';
			$orderInfo .= '--------------------------------<BR>';
			$orderInfo .= '--订餐号：'.$item0['ordersn'].'<BR>';
			$orderInfo .= '联系电话：'.$item0['mobile'].'<BR>';
			$orderInfo .= '送餐时间：'.$item0['time'].'<BR>';
			$orderInfo .= '送餐地址：'.$item0['address'].'<BR>';
			if($item0['paytype'] == 1){
				$orderInfo .= '支付方式：在线支付<BR>';
			}
			else if($item0['paytype'] == 2){
				$orderInfo .= '支付方式：餐到付款<BR>';
			}
			if($item0['other']){
				$orderInfo .= '----备注：'.$item0['other'].'<BR>';
			}
			$orderInfo .= '--------------------------------<BR>';
			foreach ($foods as $row) {
				if($row['preprice']){
					$rowprice = $row['preprice'];
				}else{
					$rowprice = $row['oriprice'];
				}
				$orderInfo .= $row['title'].'　X '.$foodsid[$row['id']]['total'].$row['unit'].'    '.$foodsid[$row['id']]['total']*$rowprice.'元<BR>';
			}
			$orderInfo .= '合计：'.$item0['price'].'元<BR>';
			if($printrow['qr']){
				$orderInfo .= '----------请扫描二维码----------';
				$orderInfo .= '<QR>'.$printrow['qr'].'</QR>';
				$orderInfo .= '<BR>';
			}
			$msgJSON = fmFunc_foods_sendSelfFormatOrderInfo($deviceno,$key,$printtime,$orderInfo);
		}
	}
	message('订单转为已确认！请按时派送。', fm_murl($do,$ac,'index', array('pcate'=>$_GPC['pcate'],'ccate'=>$_GPC['ccate'])), 'success');
}
else if($op == 'quxiao'){
	$ordersn = pdo_fetchall("SELECT * FROM ".tablename('fm453_vfoods_order')." WHERE uniacid = '{$_W['uniacid']}' AND id = '{$orderid}' ");
	pdo_update('fm453_vfoods_order', array('status' => -1), array('id' => $orderid));
	if($pcate2[0]['email']){
		fmFunc_mail_sendmail('订单取消提醒',"#友情提醒#\n" ."订餐人：" . $ordersn[0]['realname']. "（" . $ordersn[0]['mobile'] . "）" ."向餐厅预定餐号：".$ordersn[0]['ordersn'] . "的订单已经取消！\n不需要对此订单进行派送。",$pcate2[0]['email'],$sms['smtp'],$sms['email'],$sms['emailpsw']);
		fmFunc_mail_sendmail('订单取消提醒',"#友情提醒#\n" ."订餐人：" . $ordersn[0]['realname']. "（" . $ordersn[0]['mobile'] . "）" ."向餐厅预定餐号：".$ordersn[0]['ordersn'] . "的订单已经取消！\n不需要对此订单进行派送。",$sms['email'],$sms['smtp'],$sms['email'],$sms['emailpsw']);
	}
	if($sms['smsnum'] && $sms['smspsw']){
		if($ordersn[0]['paytype'] == 1){
			$body = '您向餐厅--'.$pcate2[0]['name'].'--预定单号为'.$ordersn[0]['ordersn'].'的订单已经取消！相关金额我们会尽快退还到您的账户，如有疑问请电联'.$pcate2[0]['shouji'];
		}
		else if($ordersn[0]['paytype'] == 2){
			$body = '您向店家--'.$pcate2[0]['name'].'--预定餐号为'.$ordersn[0]['ordersn'].'的订单已经取消！如有疑问请电联'.$pcate2[0]['shouji'];
		}
		$res = fmFunc_foods_sendSMS($sms['smsnum'],$sms['smspsw'],$ordersn[0]['mobile'],$body);
	}
	message('取消订单成功。', fm_murl($do,$ac,'index', array('pcate'=>$_GPC['pcate'],'ccate'=>$_GPC['ccate'])), 'success');
}
elseif($op=='detail') {
	$list = pdo_fetchall("SELECT * FROM ".tablename('fm453_vfoods_order')." WHERE uniacid = :uniacid AND id = :id", array(":uniacid"=>$_W['uniacid'],":id"=>$orderid), 'id');
	$total = 1;
	$pager = pagination($total, $pindex, $psize);
	if (!empty($list)) {
		foreach ($list as &$row) {
			$foodsid = pdo_fetchall("SELECT foodsid,total FROM ".tablename('fm453_vfoods_order_foods')." WHERE orderid = '{$row['id']}'", array(), 'foodsid');
			$foods = pdo_fetchall("SELECT id, pcate, title, thumb, preprice, oriprice, unit FROM ".tablename('fm453_vfoods_foods')."  WHERE id IN ('".implode("','", array_keys($foodsid))."')");
			$pcate3 = pdo_fetchall("SELECT * FROM ".tablename('fm453_vfoods_category')." WHERE uniacid = '{$_W['uniacid']}' AND id = '{$foods[0]['pcate']}' ORDER BY psn ASC, displayorder DESC");
			$row['pcate3'] = $pcate3;
			$row['foods'] = $foods;
			$row['total'] = $foodsid;
		}
	}
}
else{
	$pindex = max(1, intval($_GPC['page']));
	$psize = 5;
	$list = pdo_fetchall("SELECT * FROM ".tablename('fm453_vfoods_order')." WHERE uniacid = :uniacid AND pcate = :pcate AND status != -2 ORDER BY id DESC LIMIT ".($pindex - 1) * $psize.','.$psize, array(":uniacid"=>$_W['uniacid'],':pcate'=>$pcate2[0]['id']), 'id');
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fm453_vfoods_order') . " WHERE uniacid =:uniacid  AND pcate =:pcate AND status != -2 ",array(":uniacid"=>$_W['uniacid'],':pcate'=>$pcate2[0]['id']));
	$pager = pagination($total, $pindex, $psize);
	if (!empty($list)) {
		foreach ($list as &$row) {
			$foodsid = pdo_fetchall("SELECT foodsid,total FROM ".tablename('fm453_vfoods_order_foods')." WHERE orderid = '{$row['id']}'", array(), 'foodsid');
			$foods = pdo_fetchall("SELECT id, pcate, title, thumb, preprice, oriprice, unit FROM ".tablename('fm453_vfoods_foods')."  WHERE id IN ('".implode("','", array_keys($foodsid))."')");
			$pcate3 = pdo_fetchall("SELECT * FROM ".tablename('fm453_vfoods_category')." WHERE uniacid = '{$_W['uniacid']}' AND id = '{$foods[0]['pcate']}' ORDER BY psn ASC, displayorder DESC");
			$row['pcate3'] = $pcate3;
			$row['foods'] = $foods;
			$row['total'] = $foodsid;
		}
	}
}

include fmFunc_template_m('appweb/'.$_do.'/453');
