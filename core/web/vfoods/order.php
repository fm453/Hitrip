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
 * @remark 微餐饮订单管理；
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;

load()->func('tpl');
load()->model('account');//加载公众号函数
fm_load()->fm_func('route'); //获取路径函数
fm_load()->fm_model('category'); //分类管理模块
fm_load()->fm_func('foods'); //微餐饮管理函数

//加载风格模板及资源路径
$fm453style    = fmFunc_ui_shopstyle();
$fm453resource =FM_RESOURCE;

//入口判断
$routes        =fmFunc_route_web();
$routes_do     =fmFunc_route_web_do();
$do            = $_GPC['do'];
$ac            =$_GPC['ac'];
$all_ac        =fmFunc_route_web_ac($do);
$operation     = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$all_op=fmFunc_route_web_op_single($do,$ac);
if(!in_array($ac,$all_ac) || !in_array($operation,$all_op)){
	die(message('非法访问，请通过有效路径进入！'));
}

fmMod_privilege_adm();//检查用户授权

//开始操作管理
$shopname            =$settings['brands']['shopname'];
$shopname            = !empty($shopname) ? $shopname :FM_NAME_CN;
$_W['page']['title'] = $shopname.$routes[$do]['title'];
$direct_url          =fm_wurl($do,$ac,$operation,'');
$pindex              =max(1,intval($_GPC['page']));
$psize               =(intval($_GPC['psize'])>10) ? intval($_GPC['psize']) : 10;//最少显示10条主数据

$uniacid             =$_W['uniacid'];
$plattype            =$settings['plattype'];
$platids             = fmFunc_getPlatids();//获取平台模式关联的公众号商户ID参数
$oauthid             =$platids['oauthid'];
$fendianids          =$platids['fendianids'];
$supplydianids       =$platids['supplydianids'];
$blackids            =$platids['blackids'];

//平台模式处理
include_once FM_PUBLIC.'plat.php';
$supplydians        =explode(',',$supplydianids);//字符串转数组
$supplydians        =array_filter($supplydians);//数组去空

//按平台模式前置筛选条件
$condition          =' WHERE ';
$params             =array();
include_once FM_PUBLIC.'forsearch.php';
$condition          .= ' AND `deleted` = :deleted';
$params[':deleted'] = 0;


if ($operation == 'display') {
	$pindex = max(1, intval($_GPC['page']));
	$psize = 5;
	$condition = '';
	if (!empty($_GPC['keyword'])) {
		$condition .= " AND title LIKE '%{$_GPC['keyword']}%'";
	}
	if (!empty($_GPC['cate_2'])) {
		$cid = intval($_GPC['cate_2']);
		$condition .= " AND ccate = '{$cid}'";
	} elseif (!empty($_GPC['cate_1'])) {
		$cid = intval($_GPC['cate_1']);
		$condition .= " AND pcate = '{$cid}'";
	}
	if (isset($_GPC['status'])) {
		$condition .= " AND status = '".intval($_GPC['status'])."'";
	}
	$list = pdo_fetchall("SELECT * FROM ".tablename('fm453_vfoods_order')." WHERE uniacid ='{$_W['uniacid']}' $condition ORDER BY id DESC LIMIT ".($pindex - 1) * $psize.','.$psize);
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fm453_vfoods_order') . " WHERE uniacid ='{$_W['uniacid']}'");
	$pager = pagination($total, $pindex, $psize);
	foreach($list as &$row){
		$pcate = pdo_fetch("SELECT * FROM ".tablename('fm453_vfoods_category')." WHERE uniacid ='{$_W['uniacid']}' AND id = '{$row['pcate']}'");
		$row['pcatename'] = $pcate['title'];
	}
} else if ($operation == 'detail') {
	$id = intval($_GPC['id']);
	$item0 = pdo_fetch("SELECT * FROM ".tablename('fm453_vfoods_order')." WHERE id = '{$id}'");
	$foodsid = pdo_fetchall("SELECT foodsid, total FROM ".tablename('fm453_vfoods_order_foods')." WHERE orderid = '{$item0['id']}'", array(), 'foodsid');
	$foods = pdo_fetchall("SELECT * FROM ".tablename('fm453_vfoods_foods')."  WHERE id IN ('".implode("','", array_keys($foodsid))."')");
	$pcate = pdo_fetch("SELECT * FROM ".tablename('fm453_vfoods_category')." WHERE uniacid ='{$_W['uniacid']}' AND id = '{$item0['pcate']}'");
	$item['foods'] = $foods;

	if (checksubmit('delete')) {
		pdo_delete('fm453_vfoods_order', array('id' => $_GPC['id']));
		pdo_delete('fm453_vfoods_order_foods', array('orderid' => $_GPC['id']));
		message('彻底删除订单成功！', $this->createWebUrl('order', array('op' => 'display')), 'success');
	}
	if (checksubmit('wancheng')) {
		pdo_update('fm453_vfoods_order', array('status' => 0), array('id' => $id));
		message('订单转为已完成！', referer(), 'success');
	}
	if (checksubmit('yixia')) {
		pdo_update('fm453_vfoods_order', array('status' => 2), array('id' => $id));
		message('订单转为已下单！', referer(), 'success');
	}
	if (checksubmit('jieshou')) {
		pdo_update('fm453_vfoods_order', array('status' => 3), array('id' => $id));
		$sms = pdo_fetch("SELECT * FROM ".tablename('fm453_vfoods_sms')." WHERE uniacid ='{$_W['uniacid']}'");
		$print = pdo_fetchall("SELECT * FROM ".tablename('fm453_vfoods_print')." WHERE cateid = '{$pcate['id']}' AND enabled = 1");
		if($sms['smsnum'] && $sms['smspsw']){
			$body = '您向店家--'.$pcate['name'].'--预定餐号为'.$item0['ordersn'].'总价为'.$item0['price'].'元的订单已被确认，请留意接听店家电话，如有疑问请电联'.$pcate['shouji'];
			$res = fmFunc_foods_sendSMS($sms['smsnum'],$sms['smspsw'],$item0['mobile'],$body);
		}
		foreach($print as $printrow){
			if($printrow['deviceno'] && $printrow['key'] && $printrow['printtime'] > 0){
				$deviceno = $printrow['deviceno'];
				$key =$printrow['key'];
				$printtime = $printrow['printtime'];
				define('FEIE_HOST','115.28.225.82');
				define('FEIE_PORT',80);
				$orderInfo  = '<CB>'.$pcate['name'].'</CB><BR>';
				$orderInfo .= '--------------------------------<BR>';
				$orderInfo .= '--订餐号：'.$item0['ordersn'].'<BR>';
				$orderInfo .= '联系电话：'.$item0['mobile'].'<BR>';
				if($item0['time']){
					$orderInfo .= '送餐时间：'.$item0['time'].'<BR>';
				}
				if($item0['address']){
					if(is_numeric($item0['address'])){
						$addresses = $settings['vfoods']['waimai']['fanwei'];
						$item0['address'] = trim($addresses[$item0['address']]);
					}
					$orderInfo .= '送餐地址：'.$item0['address'].'<BR>';
				}
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
				$msgJSON = fmFunc_foods_orderPrint($deviceno,$key,$printtime,$orderInfo);
			}
		}
		message('订单转为已确认！请按时安排。', referer(), 'success');
	}
	if (checksubmit('quxiao')) {
		$ordersn = pdo_fetchall("SELECT * FROM ".tablename('fm453_vfoods_order')." WHERE uniacid ='{$_W['uniacid']}' AND id = '{$_GPC['id']}' ");
		pdo_update('fm453_vfoods_order', array('status' => -1), array('id' => $id));
		$sms = pdo_fetch("SELECT * FROM ".tablename('fm453_vfoods_sms')." WHERE uniacid ='{$_W['uniacid']}'");
		if($pcate['email']){
			fmFunc_mail_send('订单取消提醒',"#友情提醒#\n" ."订餐人：" . $ordersn[0]['realname']. "（" . $ordersn[0]['mobile'] . "）" ."向贵店预定餐号：".$ordersn[0]['ordersn'] . "的订单已经取消！\n不需要对此订单进行派送。",$pcate['email'],$sms['smtp'],$sms['email'],$sms['emailpsw']);
			fmFunc_mail_send('订单取消提醒',"#友情提醒#\n" ."订餐人：" . $ordersn[0]['realname']. "（" . $ordersn[0]['mobile'] . "）" ."向贵店预定餐号：".$ordersn[0]['ordersn'] . "的订单已经取消！\n不需要对此订单进行派送。",$sms['email'],$sms['smtp'],$sms['email'],$sms['emailpsw']);
		}
		if($sms['smsnum'] && $sms['smspsw']){
			if($ordersn[0]['paytype'] == 1){
				$body = '您向店家--'.$pcate['name'].'--预定餐号为'.$ordersn[0]['ordersn'].'的订单已经取消！相关金额我们会尽快退还到您的账户，如有疑问请电联'.$pcate['shouji'];
			}
			else if($ordersn[0]['paytype'] == 2){
				$body = '您向店家--'.$pcate['name'].'--预定餐号为'.$ordersn[0]['ordersn'].'的订单已经取消！如有疑问请电联'.$pcate['shouji'];
			}
			$res = fmFunc_foods_sendSMS($sms['smsnum'],$sms['smspsw'],$ordersn[0]['mobile'],$body);
		}
		message('取消订单成功！', referer(), 'success');
	}
}
include $this->template($fm453style.$do.'/453');
