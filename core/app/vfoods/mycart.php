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
 * @remark 购物车
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;
load()->func('tpl');

if(!$_W['openid']){
	checkauth();
}

fm_load()->fm_func('share');//链接分享管理
fm_load()->fm_func('view'); 	//浏览量处理
fm_load()->fm_func('wechat');//微信定义管理

//加载风格模板及资源路径
$appstyle      =empty($settings['appstyle']) ? FM_APPSTYLE : $settings['appstyle'];
$appsrc        =MODULE_URL.'template/mobile/'.$appstyle.'453/';
$htmlsrc       =MODULE_URL.'template/mobile/'.$appstyle;
$fm453resource = FM_RESOURCE;
//入口判断
$do            =$_GPC['do'];
$ac            =$_GPC['ac'];
$op            =!empty($_GPC['op']) ? $_GPC['op'] : 'index';

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

//自定义微信分享内容
$_share           = array();
$_share['title']  = $shopname.'|'.$_W['account']['name'];
$_share['link']   = fm_murl($do,$ac,$operation,array('isshare'=>1));
$_share['link']   = $_share['link'].$url_condition;
$_share['imgUrl'] = tomedia($settings['brands']['logo']);
$_share['desc']   = $settings['brands']['share_des'];

// $resultmember     = fmMod_member_query($currentid);
// $FM_member        =$resultmember['data'];

//会员自定义设置
$mine_settings    =$_FM['member']['settings'];

//页面具体操作
$cart = pdo_fetchall("SELECT * FROM ".tablename('fm453_vfoods_cart')." WHERE uniacid = '{$_W['uniacid']}' AND from_user = '{$_W['fans']['from_user']}'", array(), 'sn');
if (empty($cart)) {
	message("您还没有选购任何菜品，点击确定后进入点餐页面。",fm_murl($do,'index','index',array()),'error');
}
//SESSION判断订单类型
$_ordertype = isset($_SESSION['ordertype']) ? $_SESSION['ordertype'] : 'waimai';		//将订单类型写入SESSION,无设定时默认为外卖
switch($_ordertype) {
	case 'waimai':
		$ordertype = 1;
		break;
	case 'tangshi':
		$ordertype = 2;
		break;
	case 'ziqu':
	case 'take':
		$ordertype = 3;
		break;
	default:
		$ordertype = 1;
		break;
}

//MUI侧边栏链接
// $shoptype = pdo_fetchall("SELECT * FROM ".tablename('fm453_vfoods_shoptype')." WHERE uniacid = '{$_W['uniacid']}' ");	//全部店家
// $appNavs=array();
// require_once FM_APP.$do.DIRECTORY_SEPARATOR.'_aside.php';

$ccate2 = pdo_fetchall("SELECT * FROM ".tablename('fm453_vfoods_foods')." WHERE uniacid = '{$_W['uniacid']}' AND ccate = '{$_GPC['ccate']}' ");
$pcate2 = pdo_fetchall("SELECT * FROM ".tablename('fm453_vfoods_category')." WHERE uniacid = '{$_W['uniacid']}' AND (id = '{$_GPC['pcate']}' OR id = '{$ccate2[0]['pcate']}') ORDER BY psn ASC, displayorder DESC");

if ($_W['ispost']) {
	$cart = pdo_fetchall("SELECT * FROM ".tablename('fm453_vfoods_cart')." WHERE uniacid = '{$_W['uniacid']}' AND from_user = '{$_W['fans']['from_user']}'", array(), 'sn');

	//地址修改
	//外卖区域
	$_address = trim($_GPC['address']);
	$address = $_address;
	if($settings['vfoods']['waimai']['isfanwei'] && $ordertype==1){
	  $addresses = $settings['vfoods']['waimai']['fanwei'];
	  $_address = intval($_address);
	  $address = $addresses[$_address];
	}

	if (!empty($cart)) {
		$foods = pdo_fetchall("SELECT * FROM ".tablename('fm453_vfoods_foods')." WHERE (pcate = '{$_GPC['pcate']}' OR pcate = '{$pcate2[0]['id']}') AND id IN ('".implode("','", array_keys($cart))."')");
		if (!empty($foods)) {
			foreach ($foods as $row) {
				if (empty($cart[$row['id']]['total'])) {
					continue;
				}
				if($row['preprice']){
					$price += (floatval($row['preprice']) * intval($cart[$row['id']]['total']));
				}else{
					$price += (floatval($row['oriprice']) * intval($cart[$row['id']]['total']));
				}
			}
		}

		//变更菜品热度
		if (!empty($foods)) {
			foreach ($foods as $row) {
				if (empty($cart[$row['id']]['total'])) {
					continue;
				}
				pdo_query("UPDATE ".tablename('fm453_vfoods_foods')." SET hits = :hits WHERE id = :id", array(':hits' => $row['hits'] + $cart[$row['id']]['total'], ':id' => $row['id']));
			}
		}
		//支付方式：付现
		if ($_GPC['paytype'] == 2) {
			$data = array(
				'uniacid' => $_W['uniacid'],
				'from_user' => $_W['fans']['from_user'],
				'mobile' => $_GPC['mobile'],
				'pcate' => $pcate2[0]['id'],
				'username' => trim($_GPC['nickname']),
				'address' => $address,
				'desknum' => trim($_GPC['desknum']),
				'guests' => intval($_GPC['guests']),
				'ordersn' => 'VFOOD'.date('ymd') . random(5, 1),
				'price' => $price,
				'status' => 2,
				'paytype' => intval($_GPC['paytype']),
				'ordertype' => $ordertype,
				'other' => $_GPC['other'],
				'time' => $_GPC['time'],
				'createtime' => TIMESTAMP,
			);
			pdo_insert('fm453_vfoods_order', $data);
			$orderid = pdo_insertid();
			//插入订单菜品
			foreach ($foods as $row) {
				if (empty($row)) {
					continue;
				}
				pdo_insert('fm453_vfoods_order_foods', array(
					'uniacid' => $_W['uniacid'],
					'foodsid' => $row['id'],
					'orderid' => $orderid,
					'total' => $cart[$row['id']]['total'],
					'createtime' => TIMESTAMP,
				));
			}
			//清空我的菜单
			pdo_delete('fm453_vfoods_cart', array('uniacid' => $_W['uniacid'], 'from_user' => $_W['fans']['from_user']));

			//更新粉丝数据
			$fansdata = array();
			if($data['username']){
				$fansdata['nickname'] = $data['username'];
			}
			if($data['mobile']){
				$fansdata['mobile'] = $data['mobile'];
			}
			if($data['address']){
				$fansdata['address'] = $data['address'];
			}
			if($fansdata){
				fans_update($_W['fans']['from_user'], $fansdata);
			}

			if($pcate2[0]['email']){
				$body = "<h3>{$pcate2[0]['title']}，您有一条订单</h3> <br />";
				if (!empty($foods)) {
					foreach ($foods as $row) {
						if($row['preprice']){
							$rowprice = $row['preprice'];}else{$rowprice = $row['oriprice'];
						}
						$body .= "{$row['title']}X{$cart[$row['id']]['total']}{$row['unit']}，".$cart[$row['id']]['total']*$rowprice."元<br />";
					}
				}
				$body .= "<br />总价格：{$price}元<br />";
				$body .= "<h3>【{$pcate2[0]['title']}】订单详情</h3> <br />";
				$body .= "订餐号：{$data['ordersn']}<br />";
				$body .= "用餐人：{$data['username']}<br />";
				$body .= "联系电话：{$data['mobile']} <br />";
				$body .= "用餐时间：{$data['time']}<br />";
				$body .= "就餐桌号：{$data['desknum']}<br />";
				$body .= "用餐地址：{$data['address']} <br />";
				$body .= "支付方式：餐到付款<br />";
				$body .= "订单备注：{$data['other']} <br />";

				load()->func('communication');
				if($settings['noticeemail']){
					ihttp_email($settings['noticeemail'], '{FM_NAME_CN}订单提醒', $body);
				}
				if($pcate['email']){
					ihttp_email($pcate['email'], '{FM_NAME_CN}订单提醒', $body);
				}
				if($settings['vfoods']['basic']['noticeemail']){
					ihttp_email($settings['vfoods']['basic'], FM_NAME_CN.'订单提醒', $body);
				}
			}
			header('Location:'.fm_murl($do, 'pay', 'index', array('orderid' => $orderid,'pcate'=>$_GPC['pcate'],'ccate'=>$_GPC['ccate'])));
			exit();
			// message('提交订单成功，现在跳转至查询订单页面。', fm_murl($do, 'myorder', 'index', array('pcate'=>$_GPC['pcate'],'ccate'=>$_GPC['ccate'])), 'success');
		}else if ($_GPC['paytype'] == 1){
			//支付方式：在线支付
			$data = array(
				'uniacid' => $_W['uniacid'],
				'from_user' => $_W['fans']['from_user'],
				'mobile' => $_GPC['mobile'],
				'pcate' => $pcate2[0]['id'],
				'username' => trim($_GPC['nickname']),
				'address' => $address,
				'desknum' => trim($_GPC['desknum']),
				'guests' => intval($_GPC['guests']),
				'ordersn' => 'VFOOD'.date('ymd') . random(5, 1),
				'price' => $price,
				'status' => 1,
				'paytype' => intval($_GPC['paytype']),
				'ordertype' => $ordertype,
				'other' => $_GPC['other'],
				'time' => $_GPC['time'],
				'createtime' => TIMESTAMP,
			);
			pdo_insert('fm453_vfoods_order', $data);
			$orderid = pdo_insertid();
			//插入订单菜品
			foreach ($foods as $row) {
				if (empty($row)) {
					continue;
				}
				pdo_insert('fm453_vfoods_order_foods', array(
					'uniacid' => $_W['uniacid'],
					'foodsid' => $row['id'],
					'orderid' => $orderid,
					'total' => $cart[$row['id']]['total'],
					'createtime' => TIMESTAMP,
				));
			}
			//清空我的菜单
			pdo_delete('fm453_vfoods_cart', array('uniacid' => $_W['uniacid'], 'from_user' => $_W['fans']['from_user']));

			//更新粉丝数据
			$fansdata = array();
			if($data['username']){
				$fansdata['nickname'] = $data['username'];
			}
			if($data['mobile']){
				$fansdata['mobile'] = $data['mobile'];
			}
			if($data['address']){
				$fansdata['address'] = $data['address'];
			}
			if($fansdata){
				fans_update($_W['fans']['from_user'], $fansdata);
			}

			header('Location:'.fm_murl($do, 'pay', 'index', array('orderid' => $orderid,'pcate'=>$_GPC['pcate'],'ccate'=>$_GPC['ccate'])));
			exit();
			// message('提交订单成功，现在跳转至付款页面...', fm_murl($do, 'pay', 'index', array('orderid' => $orderid,'pcate'=>$_GPC['pcate'],'ccate'=>$_GPC['ccate'])), 'success');
		}
	}
}


if (!empty($cart)) {
	$foods = pdo_fetchall("SELECT * FROM ".tablename('fm453_vfoods_foods')." WHERE (pcate = '{$_GPC['pcate']}' OR pcate = '{$pcate2[0]['id']}') AND id IN ('".implode("','", array_keys($cart))."')");
}
$pcatefoods = pdo_fetchall("SELECT * FROM ".tablename('fm453_vfoods_foods')." WHERE uniacid = '{$_W['uniacid']}' AND pcate = '{$pcate2[0]['id']}' ");
$pricetotal =0;
foreach ($pcatefoods as $row1) {
	$pcatecart = pdo_fetch("SELECT * FROM ".tablename('fm453_vfoods_cart')." WHERE from_user = :from_user AND uniacid = '{$_W['uniacid']}' AND sn = '{$row1['id']}'", array(':from_user' => $_W['fans']['from_user']));
	$pcatetotal += $pcatecart['total'];
	$eachprice = pdo_fetch("SELECT * FROM ".tablename('fm453_vfoods_foods')." WHERE uniacid = '{$_W['uniacid']}' AND id = '{$pcatecart['sn']}'");
	if($eachprice['preprice']){$pricetotal += $eachprice['preprice']*$pcatecart['total'];}
	else{$pricetotal += $eachprice['oriprice']*$pcatecart['total'];}
}
$between = $pcate2[0]['sendprice']-$pricetotal;
$profile = fans_search($_W['fans']['from_user'], array('nickname','realname', 'resideprovince', 'residecity', 'residedist', 'address', 'mobile'));

if($op=="index"){
	//更新流量、链路统计
	if(!empty($shareid)){
		fmFunc_share_click($lastid,$shareid,$currentid,$share_user);
	}
	fmFunc_view();//记录访问
	// fmMod_member_check($_W['openid']);//检测会员
}
include fmFunc_template_m($do.'/453');
