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
 * @remark 购物车管理
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;
load()->func('tpl');
fm_load()->fm_func('share');//链接分享管理
fm_load()->fm_func('view'); 	//浏览量处理
fm_load()->fm_func('wechat');//微信定义管理
fm_load()->fm_model('shopcart'); //购物车模块

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
$pagename = '购物车';

$uniacid=$_W['uniacid'];
$plattype=$settings['plattype'];
$platids = fmFunc_getPlatids();
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

$share_user=$_GPC['share_user'];
$shareid = intval($_GPC['shareid']);
$lastid = intval($_GPC['lastid']);
$currentid = intval($_W['member']['uid']);
$fromplatid = intval($_GPC['fromplatid']);
$from_user = $_W['openid'];
$url_condition="";
$direct_url = fm_murl($do,$ac,$operation,array());

//自定义微信分享内容
$_share = array();
$_share['title'] = $pagename .'|'.$_W['account']['name'];
$_share['link'] = fm_murl($do,$ac,$operation,array('isshare'=>1));
$_share['link'] = $_share['link'].$url_condition;
$_share['imgUrl'] = tomedia($settings['brands']['logo']);
$_share['desc'] = $_share['desc'] = $settings['brands']['share_des'];

$op = $_GPC['op'];

if ($op == 'add') {
	$goodsid = intval($_GPC['id']);
	$total = intval($_GPC['total']);
	$total = empty($total) ? 1 : $total;
	$optionid = intval($_GPC['optionid']);
    $goods = pdo_fetch("SELECT id, type, total,marketprice,maxbuy FROM " . tablename('fm453_shopping_goods') . " WHERE id = :id", array(':id' => $goodsid));
	if (empty($goods)) {
		$result['message'] = '抱歉，该商品不存在或是已经被删除！';
		message($result, '', 'ajax');
	}
	$marketprice = $goods['marketprice'];
	if (!empty($optionid)) {
		$option = pdo_fetch("select marketprice from " . tablename('fm453_shopping_goods_option') . " where id=:id limit 1", array(":id" => $optionid));
		if (!empty($option)) {
			$marketprice = $option['marketprice'];
		}
	}
	$row = pdo_fetch("SELECT id, total FROM " . tablename('fm453_shopping_cart') . " WHERE from_user = :from_user AND uniacid = '{$_W['uniacid']}' AND goodsid = :goodsid  and optionid=:optionid", array(':from_user' => $_W['fans']['from_user'], ':goodsid' => $goodsid,':optionid'=>$optionid));
		if ($row == false) {
			//不存在
			$data = array(
				'uniacid' => $_W['uniacid'],
				'goodsid' => $goodsid,
				'goodstype' => $goods['type'],
				'marketprice' => $marketprice,
				'from_user' => $_W['fans']['from_user'],
				'total' => $total,
				'optionid' => $optionid
			);
			pdo_insert('fm453_shopping_cart', $data);
		} else {
			//累加最多限制购买数量
			$t = $total + $row['total'];
			if (!empty($goods['maxbuy'])) {
					if ($t > $goods['maxbuy']) {
						$t = $goods['maxbuy'];
					}
			}
			//存在
			$data = array(
				'marketprice' => $marketprice,
				'total' => $t,
				'optionid' => $optionid
			);
			pdo_update('fm453_shopping_cart', $data, array('id' => $row['id']));
		}
		//返回数据
		$carttotal = fmMod_shopcart_total();
		$result = array(
			'result' => 1,
			'total' => $carttotal
		);
		die(json_encode($result));
} else if ($op == 'clear') {
	   pdo_delete('fm453_shopping_cart', array('from_user' => $_W['fans']['from_user'], 'uniacid' => $_W['uniacid']));
	   die(json_encode(array("result" => 1)));
} else if ($op == 'remove') {
		$id = intval($_GPC['id']);
		pdo_delete('fm453_shopping_cart', array('from_user' => $_W['fans']['from_user'], 'uniacid' => $_W['uniacid'], 'id' => $id));
		die(json_encode(array("result" => 1, "cartid" => $id)));
} else if ($op == 'update') {
		$id = intval($_GPC['id']);
		$num = intval($_GPC['num']);
		$sql = "update " . tablename('fm453_shopping_cart') . " set total=$num where id=:id";
		pdo_query($sql, array(":id" => $id));
		die(json_encode(array("result" => 1)));
	} else {
		$list = pdo_fetchall("SELECT * FROM " . tablename('fm453_shopping_cart') . " WHERE  uniacid = '{$_W['uniacid']}' AND from_user = '{$_W['fans']['from_user']}'");
		$totalprice = 0;
		if (!empty($list)) {
			foreach ($list as &$item) {
				$goods = pdo_fetch("SELECT  title, thumb, marketprice, unit, total,maxbuy FROM " . tablename('fm453_shopping_goods') . " WHERE id=:id limit 1", array(":id" => $item['goodsid']));
				//规格属性
				$option = pdo_fetch("select title,marketprice,stock from " . tablename("fm453_shopping_goods_option") . " where id=:id limit 1", array(":id" => $item['optionid']));
				if ($option) {
					$goods['title'] = $goods['title'];
					$goods['optionname'] = $option['title'];
					$goods['marketprice'] = $option['marketprice'];
					$goods['total'] = $option['stock'];
				}
				$item['goods'] = $goods;
				$item['totalprice'] = (floatval($goods['marketprice']) * intval($item['total']));
				$totalprice += $item['totalprice'];
			}
			unset($item);
		}

	include $this->template($appstyle.$do.'/453');
}
