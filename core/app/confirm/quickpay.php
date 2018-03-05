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
 * @remark 快捷支付
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;
fm_load()->fm_func('share');//链接分享管理
fm_load()->fm_model('shopcart'); //购物车模块
fm_load()->fm_func('market');//营销管理
fm_load()->fm_func('view'); 	//浏览量处理
fm_load()->fm_func('pay');	//支付后处理

$is_wexin = fmFunc_wechat_is();
$appstyle = empty($settings['appstyle']) ? FM_APPSTYLE : $settings['appstyle'];
$appsrc =MODULE_URL.'template/mobile/'.$appstyle.'453/';
$htmlsrc =MODULE_URL.'template/mobile/'.$appstyle;
$fm453resource = FM_RESOURCE;

$do= $_GPC['do'];
$ac=$_GPC['ac'];
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'index';

$shopname=$settings['brands']['shopname'];
$shopname = !empty($shopname) ? $shopname :FM_NAME_CN;
$pagename = '快捷支付';

$share_user=$_GPC['share_user'];
$shareid = intval($_GPC['shareid']);
$lastid = intval($_GPC['lastid']);
$currentid = intval($_W['member']['uid']);
$mySysInfo = fmMod_member_query($currentid); //系统会员信息
$myShopInfo = fmMod_member_query($currentid);	//商城会员信息
$fromplatid = intval($_GPC['fromplatid']);
$from_user = $_W['openid'];
$url_condition="";
$direct_url = fm_murl($do,$ac,$operation,array());
/**________________准备支付数据
@goodstype: 传入产品类型,
@goodstitle: 传入产品标题,
@id: 传入产品id,
@sn: 传入产品SN,
@fee: 传入支付金额,
@total:1,传入购买数量
@returnurl: 伟入返回地址,
@paytype: 传入支付类型
**/
$id = intval($_GPC['id']);
$sn = $_GPC['sn'];
$fee = intval($_GPC['fee']);

if ($fee < 0) {
	exit(json_encode(array('status' => 0, 'msg' => '支付金额错误')));
}
$total = max(1,intval($_GPC['total']));
$goodstype =!empty($_GPC['goodstype']) ? $_GPC['goodstype'] : 'quickpay';
if(!empty($_GPC['goodstype'])) {
	if(empty($sn) && empty($id)) {
		exit(json_encode(array('status' => 0, 'msg' => '未传入要支付的内容')));
	}
}
$paytype = (in_array($_GPC['paytype'],array('yue','jifen','kaquan'))) ? $_GPC['paytype'] : 'yue';
$_FM_pattypes = fmFunc_pay_types();
$paydo = $_FM_pattypes[$paytype];	//wechat,credit1,credit	//将自定义的支付方式与系统支付方式对应

$ordertype = 'quickpay';
$goodstitle = !empty($_GPC['goodstitle']) ? $_GPC['goodstitle'] : '快捷支付';
$returnurl = $_GPC['returnurl'];

if($operation=='getparams'){
	$return = array();
	//格式化支付参数
	$return['paytype']=$paydo;
	//$return['paytype']='wechat';

	switch($goodstype) {
	    case 'goods':
	   $ordersnPre = '';
	   break;

	   case 'needs':
	   $ordersnPre = 'N';
	   break;

	   case 'article':
	   $ordersnPre = 'A';
	   break;

	   default:
	   $ordersnPre = 'X';
	   break;
	}

	$ordersn = $ordersnPre.date('Ymdhis') . rand(10, 99);
	$params = array(
		'tid' => $ordersn,
		'ordersn' => $ordersn,
		'title' => $goodstitle,
		'fee' => $fee,
		'articleid' => $id,
		'total' => 1,
		'user' => $_W['openid'],
		'goodstype'=>$goodstype,
		'module' => 'fm453_shopping'
	);
	$return['params'] = base64_encode(json_encode($params));

	//为节约数据空间，积分支付的不生成支付日志
	if($paydo=='credit1'){
		//使用的是积分支付，返回status代码为2
		checkauth();
		load()->model('mc');
		$uid = mc_openid2uid($_W['openid']);
		$hascredit1 = $_W['member']['credit1'];

		$WeAccount = fmFunc_wechat_weAccount();
		require_once MODULE_ROOT.'/core/msgtpls/msg/article.php';
		if($hascredit1>=$params['fee']){
			mc_credit_update($uid, 'credit1', -$params['fee'], array($_W['member']['uid'], '为文章打赏,使用'.$params['fee'].'积分'));

			$msgdata=$notice_data['dashang']['jifen']['result']['complete']['admin'];
			fmMod_notice($settings['manageropenids'],$msgdata,'',$WeAccount);
			$msgdata=$notice_data['dashang']['jifen']['result']['complete']['payer'];
			fmMod_notice($_W['openid'],$msgdata,'',$WeAccount);

			exit(json_encode(array('status' => 2,'result'=>0)));
		}else{
			mc_credit_update($uid, 'credit1', -$hascredit1, array($_W['member']['uid'], '为文章打赏,消耗'.$hascredit1."积分"));

			$msgdata=$notice_data['dashang']['jifen']['result']['part']['admin'];
			fmMod_notice($settings['manageropenids'],$msgdata,'',$WeAccount);
			$msgdata=$notice_data['dashang']['jifen']['result']['part']['payer'];
			fmMod_notice($_W['openid'],$msgdata,'',$WeAccount);

			exit(json_encode(array('status' => 2,'result'=>-1)));
		}
	}
	//生成支付日志
	$log = pdo_get('core_paylog', array('uniacid' => $_W['uniacid'], 'module' => $params['module'], 'tid' => $params['tid']));
	//在pay方法中，要检测是否已经生成了paylog订单记录，如果没有需要插入一条订单数据
	if (empty($log)) {
		$log = array(
			'uniacid' => $_W['uniacid'],
			'acid' => $_W['acid'],
			'openid' => $_W['openid'],
			'module' => $params['module'], //模块名称，请保证$this可用
			'tid' => $params['tid'],
			'fee' => $params['fee'],
			'card_fee' => $params['fee'],
			'status' => '0',//尚未支付的状态
			'is_usecard' => '0',
		);
		pdo_insert('core_paylog', $log);
	}

	//生成订单数据
		//将联系人姓名电话直接写进订单；
		$contactinfo=array(
			'username' =>$FM_member['username'],
			'mobile' =>$$FM_member['mobile']
		);
		$contactinfo=serialize($contactinfo);//将数组序列化以便存入数据库；
		$aboutinfos=array(
			'mpaccountname' =>$_W['account']['name'],
			'ucontainer' =>$_W['container'],
			'uos' =>$_W['os'],
		);
		$aboutinfos=serialize($aboutinfos);

		$data = array(
			'uniacid' => $_W['uniacid'],
			'from_user' => $from_user,
			'fromuid' => $currentid,
			'ordersn' => $params['tid'],
			'price' => $params['fee'],
			'dispatchprice' => 0,
			'goodsprice' => 0,
			'status' => 0,
			'sendtype' => 0,
			'dispatch' => 0,
			'goodstype' => $params['goodstype'],
			'remark' => '',
			'addressid' => '',
			'contactinfo' => $contactinfo,
			'aboutinfos' => $aboutinfos,
			'sharedfrom' => $fromplatid,	//来源公众号
			'createtime' => TIMESTAMP,
			'shareid' => $shareid
		);
		pdo_insert('fm453_shopping_order', $data);
		$orderid = pdo_insertid();

		//写入订单产品
		$d = array(
			'uniacid' => $_W['uniacid'],
			'articleid' => $params['articleid'],
			'orderid' => $orderid,
			'total' => $params['total'],
			'price' => $params['fee'],
			'createtime' => TIMESTAMP,
		);
		$commission=$commission2=$commission3=0;
		$commissionTotal = $params['fee']  * $commission /100;
		$d['commission'] = $commissionTotal;
		$commissionTotal2 = $params['fee']  * $commission2 /100;
		$d['commission2'] = $commissionTotal2;
		$commissionTotal3 = $params['fee']  * $commission3 /100;
		$d['commission3'] = $commissionTotal3;
		pdo_insert('fm453_shopping_order_goods', $d);

	$result =$return;
	//使用的是系统支付，返回status代码为1
	exit(json_encode(array('status' => 1, 'result' => $result)));
}
//_________________
else if ($operation=='index'){
	$FM_member= fmMod_member_query($currentid)['data'];
	if($_GPC['params']){
		$params = fm_object2array(json_decode(base64_decode($_GPC['params'])));
		//根据情况，清除不必要的支付日志（暂不处理）

		echo $this->pay($params);
	}
}
