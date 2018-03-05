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
 * @remark 核销端-订单列表
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
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'index';

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

if($op=='index') {
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;
	$status = $_GPC['status'];
	$deleted = $_GPC['deleted'];
	$sendtype = !isset($_GPC['sendtype']) ? 0 : $_GPC['sendtype'];
	$condition = " o.uniacid = :uniacid";
	$paras = array();
	$paras[':uniacid'] = $_W['uniacid'];
	if (!empty($_GPC['timestart'])) {
			$starttime = strtotime($_GPC['timestart']);
	}else {
		$starttime = -28800;//1970-01-01 00:00:00
	}
	if (!empty($_GPC['timeend'])) {
$endtime = strtotime($_GPC['timeend']) + 86399;
	}else {
		$endtime = TIMESTAMP;
	}
	$condition .= " AND o.createtime >= :starttime AND o.createtime <= :endtime ";
	$paras[':starttime'] = $starttime;
	$paras[':endtime'] = $endtime;
	$searchtime=array(
	'starttime' => date('Y-m-d',$starttime),
	'endtime' => date('Y-m-d',$endtime),
	);
	if (!empty($_GPC['paytype'])) {
		$condition .= " AND o.paytype = '{$_GPC['paytype']}'";
	} elseif ($_GPC['paytype'] === '0') {
		$condition .= " AND o.paytype = '{$_GPC['paytype']}'";
	}
	if (!empty($_GPC['keyword'])) {
		$condition .= " AND o.ordersn LIKE '%{$_GPC['keyword']}%'";
	}
	if (!empty($_GPC['member'])) {
		$condition .= " AND (a.username LIKE '%{$_GPC['member']}%' or a.mobile LIKE '%{$_GPC['member']}%' or a.uid LIKE '%{$_GPC['member']}%')";
	}
	//从订单备注remark中搜索信息
	if (!empty($_GPC['remark'])) {
		$condition .= " AND (o.remark LIKE '%{$_GPC['remark']}%')";
	}
	//从订单关联微支付流水号transid中搜索信息
	if (!empty($_GPC['transid'])) {
		$condition .= " AND (o.transid LIKE '%{$_GPC['transid']}%')";
	}
	//从订单客服操作备注remark_kf中搜索信息
	if (!empty($_GPC['remark_kf'])) {
		$condition .= " AND (o.remark_kf LIKE '%{$_GPC['remark_kf']}%')";
	}
	if ($status != '') {
		$condition .= " AND o.status = '" . intval($status) . "'";
	}
	if (!empty($sendtype)) {
		$condition .= " AND o.sendtype = '" . intval($sendtype) . "' AND status != '3'";
	}
	if ($deleted == 1) {
		$condition .= " AND o.deleted = '". intval($deleted)."'";
	}else{
		$deleted = 0;
		$condition .= " AND o.deleted = '". intval($deleted)."'";
	}
	$sql = 'SELECT COUNT(*) FROM ' . tablename('fm453_shopping_order') . ' AS `o` LEFT JOIN ' . tablename('mc_member_address'). ' AS `a` ON `o`.`addressid` = `a`.`id` WHERE ' . $condition ;
	$total = pdo_fetchcolumn($sql, $paras);

	if ($total > 0) {
		if ($_GPC['export'] != 'export') {
			$limit = ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
		} else {
			$limit = '';
			$condition = " o.uniacid = :uniacid";
			$paras = array(':uniacid' => $_W['uniacid']);
		}
		$sql = 'SELECT `o`.*, `a`.* FROM ' . tablename('fm453_shopping_order') . ' AS `o` LEFT JOIN' . tablename('mc_member_address') . ' AS `a` ON `o`.`addressid` = `a`.`id` WHERE ' . $condition . ' ORDER BY `o`.`createtime` DESC , `o`.`status` DESC ' . $limit;
    	//结果排序，按创建时间、订单状态，降序
		$list = pdo_fetchall($sql,$paras);
		//订单管理页结果列表
		$pager = pagination($total, $pindex, $psize);
		//订单结果列表分页
		$paytype = array (
			'0' => array('css' => 'default', 'name' => '未支付'),
			'1' => array('css' => 'danger','name' => '余额支付'),
			'2' => array('css' => 'info', 'name' => '在线支付'),
			'3' => array('css' => 'warning', 'name' => '货到付款')
		);
		$orderstatus = array (
			'-1' => array('css' => 'muted', 'name' => '已取消'),
			'0' => array('css' => 'danger', 'name' => '待付款'),
			'1' => array('css' => 'info', 'name' => '待发货'),
			'2' => array('css' => 'warning', 'name' => '待收货'),
			'3' => array('css' => 'success', 'name' => '已完成')
		);
		foreach ($list as &$value) {
			$s = $value['status'];
			$ordersn = $value['ordersn'];
			//下面是获取新添加的联系信息、订单更多信息
			$contactinfo=unserialize($value['contactinfo']);
			     $value['username'] =$contactinfo['username'];
                 $value['mobile'] =$contactinfo['mobile'];
             $aboutinfos=unserialize($value['aboutinfos']);
                   $value['goodtpl'] =$aboutinfos['goodtpl'];
                   $goodstpl=$value['goodstpl'];
                   $value['mpaccountname'] =$aboutinfos['mpaccountname'];
                   $mpaccountname =$value['mpaccountname'];
                    $value['ucontainer'] =$aboutinfos['ucontainer'];
                    $value['uos'] =$aboutinfos['uos'];
              $goodstplinfos =unserialize($aboutinfos['infos']);
                    include  FM_CORE.'goodstpl/forordermanage.php';
                    $value['tips']=$tips;
            //获取新添加信息 结束
          //下面获取订单分销信息
          $orderid = $value['id'];
			$commission = pdo_fetchall("select total,commission, commission2, commission3 from".tablename('fm453_shopping_order_goods')." where orderid =:orderid ",array(':orderid'=>$orderid));
          $value['commission'] = 0;
			 $value['commission2'] = 0;
			 $value['commission3'] = 0;
          foreach ($commission as $key=>$l){
				$value['commission'] += $commission[$i]['commission']* $commission[$i]['total'];
				$value['commission2'] += $commission[$i]['commission2']* $commission[$i]['total'];
				$value['commission3'] += $commission[$i]['commission3']* $commission[$i]['total'];
				}
			$members = pdo_fetchall("select id, realname from ".tablename('fm453_shopping_member')." where uniacid = ".$_W['uniacid']." and status = 1");
			$member = array();
			foreach($members as $m){
				$member[$m['id']] = $m['realname'];
			}
					$value['shareby'] =$m[$value['shareid']];
					if($value['shareid']==0) {
						$value['shareby'] ='总店';
            	}
          //获取分销信息 结束
			$value['statuscss'] = $orderstatus[$value['status']]['css'];
			$value['status'] = $orderstatus[$value['status']]['name'];
			$value['dispatch'] = pdo_fetchcolumn("SELECT `dispatchname` FROM " . tablename('fm453_shopping_dispatch') . " WHERE id = :id", array(':id' => $value['dispatch']));
			if ($s < 1) {
				$value['css'] = $paytype[$s]['css'];
				$value['paytype'] = $paytype[$s]['name'];
				continue;
			}
			$value['css'] = $paytype[$value['paytype']]['css'];
			if ($value['paytype'] == 2) {
				if (!empty($value['transid'])) {
					$value['paytype'] = '微信支付';
				} else {
					$value['paytype'] = '非微信支付';
				}
			} else {
				$value['paytype'] = $paytype[$value['paytype']]['name'];
			}
			//重命名支付方式
			if ($value['deleted'] == 0){
				$value['deleted'] = '否';
			} else {
				$value['deleted'] = '是';
			}
			//重命名订单删除状态
			if (!empty($value['transid'])){
				if ($value['transid'] == 0){
					$value['transid'] = '订单已经取消';
				} else {
					$value['transid'] = $value['transid'];
				}
			} else {
				$value['transid'] = '非微信支付订单';
			}
			//重命名微支付流水号
		}
	}
	unset($value);
	include $this->template($appstyle.'appweb/'.$do.'/'.$ac.'/'.'index');
}
