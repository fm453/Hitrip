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
 * @remark 未支付订单
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;
load()->func('tpl');
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
$do=$_GPC['do'];
$ac=$_GPC['ac'];
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'index';
//开始操作管理
$shopname=$settings['brands']['shopname'];
$shopname = !empty($shopname) ? $shopname :FM_NAME_CN;
$pagename = '未支付订单';

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
$_share['title'] = $settings['brands']['shopname'];
$_share['link'] = fm_murl($do,$ac,$operation,array('isshare'=>1));
$_share['link'] = $_share['link'].$url_condition;
$_share['imgUrl'] = tomedia($settings['brands']['logo']);
$_share['desc'] = $_share['desc'] = $settings['brands']['share_des'];

$carttotal = fmMod_shopcart_total();

if ($operation == 'index') {
	$orderid = intval($_GPC['orderid']);
	$order = pdo_fetch("SELECT * FROM " . tablename('fm453_shopping_order') . " WHERE id = :id AND from_user = :from_user", array(':id' => $orderid, ':from_user' => $_W['fans']['from_user']));
	if (empty($order)) {
		message('抱歉，您的订单不存或是已经被取消！', $this->createMobileUrl('myorder'), 'error');
		}
	pdo_update('fm453_shopping_order', array('status' => 3), array('id' => $orderid, 'from_user' => $_W['fans']['from_user']));
	message('确认收货完成！', $this->createMobileUrl('myorder'), 'success');

} else if ($operation == 'detail') {
	$orderid = intval($_GPC['orderid']);
	$item = pdo_fetch("SELECT * FROM " . tablename('fm453_shopping_order') . " WHERE uniacid = '{$_W['uniacid']}' AND from_user = '{$_W['fans']['from_user']}' and id='{$orderid}' limit 1");
	if (empty($item)) {
		message('抱歉，您的订单不存或是已经被取消！', $this->createMobileUrl('myorder'), 'error');
	}
	$status=$item['status'];
	$defaultaddress = pdo_fetch("SELECT * FROM " . tablename('mc_member_address') . " WHERE isdefault = 1 and uid = :uid limit 1", array(':uid' => $_W['member']['uid']));//从会员地址表中获取收货地址
	//下面是获取新添加的联系信息、订单更多信息
   $row=$item;
		$contactinfo=unserialize($row['contactinfo']);
		     $row['username'] =$contactinfo['username'];
             $row['mobile'] =$contactinfo['mobile'];
         $aboutinfos=unserialize($row['aboutinfos']);
               $row['goodtpl'] =$aboutinfos['goodtpl'];
                $goodtpl=$row['goodtpl'];
               $row['mpaccountname'] =$aboutinfos['mpaccountname'];
               $mpaccountname =$row['mpaccountname'];
                $row['ucontainer'] =$aboutinfos['ucontainer'];
                $row['uos'] =$aboutinfos['uos'];
                $goodtplinfos =unserialize($aboutinfos['infos']);
                include_once  FM_PUBLIC.'goodtpl/forapporder.php';   //require请求失败则程序不继续；开发完成改用include；文件顺序不可更改——BYFM453
                $row['tips']=$tips;
            //获取新添加信息 结束
	//$goodsid = pdo_fetch("SELECT goodsid,total FROM " . tablename('fm453_shopping_order_goods') . " WHERE orderid = '{$orderid}'", array(), 'goodsid');//将goodsid作为结果索引
	$goods = pdo_fetchall("SELECT g.id, g.title, g.thumb, g.unit, g.marketprice, o.total,o.optionid,o.price FROM " . tablename('fm453_shopping_order_goods'). " o left join " . tablename('fm453_shopping_goods') . " g on o.goodsid=g.id " . " WHERE o.orderid='{$orderid}'");
	foreach ($goods as &$g) {
		//属性
			$option = pdo_fetch("select title,marketprice,weight,stock from " . tablename("fm453_shopping_goods_option") . " where id=:id limit 1", array(":id" => $g['optionid']));
		if ($option) {
			$g['title'] = "[" . $option['title'] . "]" . $g['title'];
				$g['marketprice'] = $option['marketprice'];
			}
				if ($status == 1 ||$status == 2) {//当订单为待收货、已完成等状态时，从订单数据表中读取产品规格对应的价格作为产品价格
				$g['marketprice'] = $g['price'];
			}
		}
		unset($g);
		$dispatch = pdo_fetch("select id,dispatchname from " . tablename('fm453_shopping_dispatch') . " where id=:id limit 1", array(":id" => $item['dispatch']));
		include $this->template($appstyle.'order_detail');

} elseif() {
	$pindex = max(1, intval($_GPC['page']));
	$psize = 2;
	$status = intval($_GPC['status']);
	$where = " uniacid = '{$_W['uniacid']}' AND from_user = '{$_W['fans']['from_user']}' AND deleted = '0'";
	if ($status == 2) {
		$where.=" and ( status=1 or status=2 )";
	} else {
		$where.=" and status=$status";
	}
	$list = pdo_fetchall("SELECT * FROM " . tablename('fm453_shopping_order') . " WHERE". $where ." ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(), 'id');
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fm453_shopping_order') . " WHERE uniacid = '{$_W['uniacid']}' AND from_user = '{$_W['fans']['from_user']}'");
	$pager = pagination($total, $pindex, $psize);
	if (!empty($list)) {
		foreach ($list as &$row) {
			$goodsid = pdo_fetchall("SELECT goodsid,total FROM " . tablename('fm453_shopping_order_goods') . " WHERE orderid = '{$row['id']}'", array(), 'goodsid');
			$row['goodtpl'] =$aboutinfos['goodtpl'];
			$goods = pdo_fetchall("SELECT g.id, g.title, g.thumb, g.unit, g.marketprice,g.description,o.total,o.optionid,o.price FROM " . tablename('fm453_shopping_order_goods') . " o left join " . tablename('fm453_shopping_goods') . " g on o.goodsid=g.id " . " WHERE o.orderid='{$row['id']}'");
			foreach ($goods as &$item) {
				//属性
				$option = pdo_fetch("select title,marketprice,weight,stock from " . tablename("fm453_shopping_goods_option") . " where id=:id limit 1", array(":id" => $item['optionid']));
				if ($option) {
					$item['title'] =$item['title']. "[" . $option['title'] . "]" ;//从产品规格中读取规格名加入产品标题
					$item['marketprice'] = $option['marketprice'];//从产品规格中读取对应规格项的价格作为产品价格
				}
				if ($status == 2 ||$status == 3) {//当订单为待收货、已完成等状态时(在订单表中状态值为1或2)，从订单数据表中读取产品规格对应的价格作为产品价格
					$item['marketprice'] = $item['price'];
				}
			}
			unset($item);
			$row['goods'] = $goods;
			$row['total'] = $goodsid;
			$row['dispatch'] = pdo_fetch("select id,dispatchname from " . tablename('fm453_shopping_dispatch') . " where id=:id limit 1", array(":id" => $row['dispatch']));
		}
	}
	load()->model('mc');
	$fans = mc_fetch($_W['member']['uid']);
	include $this->template($appstyle.$do.'/453');
}
