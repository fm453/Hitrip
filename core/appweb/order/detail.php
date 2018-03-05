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
 * @remark 核销端-商城订单管理
 */
defined('IN_IA') or exit('Access Denied');
global $_W, $_GPC,$_FM;
load()->func('tpl');
fm_load()->fm_model('order');
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
$appstyle = empty($settings['appstyle']) ? FM_APPSTYLE : $settings['appstyle'];
$appsrc =MODULE_URL.'template/mobile/'.$appstyle.'453/';
$htmlsrc =MODULE_URL.'template/mobile/'.$appstyle;
$fm453resource = FM_RESOURCE;

//入口判断
$do= 'order';
$ac=$_GPC['ac'];
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'index';

//开始操作管理
$shopname=$settings['brands']['shopname'];
$shopname = !empty($shopname) ? $shopname :FM_NAME_CN;
$pagename = '核销中心-有求必应';

$uniacid=$_W['uniacid'];
$plattype=$settings['plattype'];
$platids= fmFunc_getPlatids();//获取平台模式关联的公众号商户ID参数
$platid=$_W['uniacid'];
$oauthid=$platids['oauthid'];
$fendianids=$platids['fendianids'];
$supplydianids=$platids['supplydianids'];
$blackids=$platids['blackids'];

$ordersn = intval($_GPC['ordersn']);
$id = intval($_GPC['id']);
if ($operation == 'index') {
	$item = pdo_fetch("SELECT * FROM " . tablename('fm453_shopping_order') . " WHERE uniacid = '{$_W['uniacid']}' and ordersn='{$ordersn}' limit 1");
	if($id>0) {
	$item = pdo_fetch("SELECT * FROM " . tablename('fm453_shopping_order') . " WHERE uniacid = '{$_W['uniacid']}' and id = '{$id}' limit 1");
	}
	if (empty($item)) {
		message('抱歉，您的订单不存或是已经被取消！', fm_murl('appweborder','list','index',array()), 'error');
		}
		$status=$item['status'];
	//下面是获取新添加的联系信息、订单更多信息
		$row=$item;
			//获取下单人的默认联系方式
			$adress_sql = 'SELECT * FROM ' . tablename('mc_member_address') . ' WHERE `uniacid` = :uniacid AND `uid` = :uid AND `isdefault`=1';
			$params = array(':uniacid' => $_W['uniacid']);
			$params[':uid'] = $item['fromuid'];
			$row['defaultaddress']=pdo_fetch($adress_sql,$params);
			$defaultaddress=$row['defaultaddress'];

		$contactinfo=unserialize($row['contactinfo']);
			$row['username'] =$contactinfo['username'];
			$row['mobile'] =$contactinfo['mobile'];
		$aboutinfos=unserialize($row['aboutinfos']);
			$row['goodstpl'] =$aboutinfos['goodstpl'];
			$goodtpl=$row['goodtpl'];
			$row['mpaccountname'] =$aboutinfos['mpaccountname'];
			$mpaccountname =$row['mpaccountname'];
			$row['ucontainer'] =$aboutinfos['ucontainer'];
			$row['uos'] =$aboutinfos['uos'];
			$goodstplinfos =unserialize($aboutinfos['infos']);
			include_once  FM_CORE.'goodstpl/forordermanage.php';
			$row['tips']=$tips;

			$item=$row;
	$goods = pdo_fetchall("SELECT g.id, g.title, g.thumb, g.unit, g.marketprice, o.total,o.optionid,o.price FROM " . tablename('fm453_shopping_order_goods'). " o left join " . tablename('fm453_shopping_goods') . " g on o.goodsid=g.id " . " WHERE o.orderid='{$item['id']}'");
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
	include $this->template($appstyle.'appweb/'.$do.'/'.$ac.'/'.'index');
}
