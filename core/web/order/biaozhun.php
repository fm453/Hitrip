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
 * @remark 标准订单；
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;

load()->func('tpl');

//加载风格模板及资源路径

$fm453style = fmFunc_ui_shopstyle();
$fm453resource =FM_RESOURCE;

//入口判断
$routes=fmFunc_route_web();
$routes_do=fmFunc_route_web_do();
$do= $_GPC['do'];
$ac=$_GPC['ac'];
$all_ac=fmFunc_route_web_ac($do);
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$all_op=fmFunc_route_web_op_single($do,$ac);
if(!in_array($ac,$all_ac) || !in_array($operation,$all_op)){
	die(message('非法访问，请通过有效路径进入！'));
}

fmMod_privilege_adm();//检查用户授权

//开始操作管理
$shopname=$settings['brands']['shopname'];
$shopname = !empty($shopname) ? $shopname :FM_NAME_CN;
$_W['page']['title'] = $shopname.$routes[$do]['title'];
$direct_url=fm_wurl($do,$ac,$operation,'');
$pindex=max(1,intval($_GPC['page']));
$psize=(intval($_GPC['psize'])>10) ? intval($_GPC['psize']) : 10;//最少显示10条主数据

$uniacid=$_W['uniacid'];
$plattype=$settings['plattype'];
$platids = fmFunc_getPlatids();//获取平台模式关联的公众号商户ID参数
$oauthid=$platids['oauthid'];
$fendianids=$platids['fendianids'];
$supplydianids=$platids['supplydianids'];
$blackids=$platids['blackids'];

$gtpltype = fmMod_goodstpl_type_get();//列出全部产品模型清单
$marketmodeltypes =fmFunc_market_types();

//平台模式处理
include_once FM_PUBLIC.'plat.php';
$supplydians=explode(',',$supplydianids);//字符串转数组
$supplydians=array_filter($supplydians);//数组去空

//按平台模式前置筛选条件
$condition=' WHERE ';
$params=array();
include_once FM_PUBLIC.'forsearch.php';
$condition .= ' AND `deleted` = :deleted';
$params[':deleted'] = 0;

//获取分类
$allcategory = fmMod_category_get('goods');
$parent = $children = array();
$parent = $allcategory['parent'];
$children = $allcategory['child'];

if ($operation == 'display') {
	$pindex = max(1, intval($_GPC['page']));
	$psize = 5;
	$status = $_GPC['status'];
	$deleted = $_GPC['deleted'];
	$sendtype = !isset($_GPC['sendtype']) ? 0 : $_GPC['sendtype'];
	$condition = " o.uniacid = :uniacid";
	$paras = array();
	$paras[':uniacid'] = $_W['uniacid'];
	if (!empty($_GPC['time'])) {
		$starttime = strtotime($_GPC['time']['start']);
		$endtime = strtotime($_GPC['time']['end']) + 86399;
		$condition .= " AND o.createtime >= :starttime AND o.createtime <= :endtime ";
		$paras[':starttime'] = $starttime;
		$paras[':endtime'] = $endtime;
	}
		if (empty($starttime) || empty($endtime)) {
			$starttime = -28800;//1970-01-01 00:00:00
			$endtime = TIMESTAMP;
		}
	$searchtime=array(
	'start' => date('Y-m-d',$starttime),
	'end' => date('Y-m-d',$endtime),
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
		$condition .= " AND o.sendtype = '" . intval($sendtype) . "' AND o.status != '3'";
	}
	if ($deleted == 1) {
		$condition .= " AND o.deleted = '". intval($deleted)."'";
	}else{
		$deleted = 0;
		$condition .= " AND o.deleted = '". intval($deleted)."'";
	}
	$sql = 'SELECT COUNT(*) FROM ' . tablename('fm453_shopping_order') . ' AS `o` LEFT JOIN ' . tablename('mc_member_address'). ' AS `a` ON `o`.`addressid` = `a`.`id` WHERE ' . $condition ;
	//仅显示删除状态为0(即未删除)的订单

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
			'-1' => array('css' => 'default', 'name' => '已取消'),
			'0' => array('css' => 'danger', 'name' => '待付款'),
			'1' => array('css' => 'info', 'name' => '待发货'),
			'2' => array('css' => 'warning', 'name' => '待收货'),
			'3' => array('css' => 'success', 'name' => '已完成')
		);
		foreach ($list as &$value) {
			$s = $value['status'];
			$ordersn = $value['ordersn'];
			$value['uid']=$value['fromuid'];
			//下面是获取新添加的联系信息、订单更多信息
			$contactinfo=unserialize($value['contactinfo']);
				$value['username'] =$contactinfo['username'];
				$value['mobile'] =$contactinfo['mobile'];
				$value['province']=$contactinfo['province'];
				$value['city']=$contactinfo['city'];
				$value['district']=$contactinfo['district'];
				$value['address']=$contactinfo['address'];
			$aboutinfos=unserialize($value['aboutinfos']);
				$value['goodtpl'] =$aboutinfos['goodtpl'];
				$goodtpl=$value['goodtpl'];
				$value['mpaccountname'] =$aboutinfos['mpaccountname'];
				$mpaccountname =$value['mpaccountname'];
				$value['ucontainer'] =$aboutinfos['ucontainer'];
				$value['uos'] =$aboutinfos['uos'];
			$goodtplinfos =unserialize($aboutinfos['infos']);
			include  FM_PUBLIC.'goodtpl/forweborder.php';   //不能用include_once，否则数据显示不全导致混乱；文件顺序不可更改——BYFM453
				$value['tips']=$tips;
                    //echo $tips;
            //获取新添加信息 结束
          //下面获取订单分销信息
          $orderid = $value['id'];
          //print_r($orderid);
			$commission = pdo_fetchall("select total,commission, commission2, commission3 from".tablename('fm453_shopping_order_goods')." where orderid =:orderid ",array(':orderid'=>$orderid));
          $value['commission'] = 0;
			 $value['commission2'] = 0;
			 $value['commission3'] = 0;
          foreach ($commission as $key=>$l){
				$value['commission'] += $commission[$i]['commission']* $commission[$i]['total'];
				$value['commission2'] += $commission[$i]['commission2']* $commission[$i]['total'];
				$value['commission3'] += $commission[$i]['commission3']* $commission[$i]['total'];
				}
				unset($key);
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
			//重命名支付状态
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
					$value['transid'] = "支付记录号".$value['transid'];
				}
			} else {
				$value['transid'] = '非微信支付订单';
			}
			//重命名微支付流水号
		}
        //导出订单功能
		if ($_GPC['export'] != '') {
			/* 输入到CSV文件 */
			$html = "\xEF\xBB\xBF";
				/* 输出表头 */
			$filter = array(
				'ordersn' => '系统订单编号',
				'username' => '联系人姓名',
				'mobile' => '联系电话',
				'uid' => '下单人会员ID',
				'paytype' => '支付方式',
				'dispatch' => '配送方式',
				'dispatchprice' => '运费',
				'price' => '总价',
				'status' => '状态',
				'tips' => '订单关联信息',
				'createtime' => '下单时间',
				'zipcode' => '邮政编码',
				'address' => '联系地址',
				'transid' => '微信支付订单号',
				'commission' => '1级分销佣金',
				'commission2' => '2级分销佣金',
				'commission3' => '3级分销佣金',
				'goodtpl' => '产品模型',
				'mpaccountname' => '来源公号',
				'shareid'=> '推荐人会员ID',
				'shareby'=> '推荐人名称',
				'ucontainer'=> '下单人设备',
				'uos'=> '下单人设备OS',

			);
			foreach ($filter as $key => $title) {
				$html .= $title . "\t,";
			}
				$html .= "\n";
				foreach ($list as $k => $v) {
					foreach ($filter as $key => $title) {
						if ($key == 'createtime') {
							$html .= date('Y-m-d H:i:s', $v[$key]) . "\t, ";
						} elseif ($key == 'address') {
							$html .= $v['province'] . '-' . $v['city'] . '-' . $v['district'] . '  ' . $v['address'] . "\t, ";
						}  elseif ($key == 'ordersn') {
							$html .= "  ".$v['ordersn'] . "\t, ";
						} else {
							$html .= $v[$key] . "\t, ";
						}
					}
					$html .= "\n";
				}
				/* 输出XLS文件 */
				header("Content-type:text/xls");
				header("Content-Disposition:attachment; filename=嗨旅行商城订单数据_".$searchtime['start']."-".$searchtime['end'].".xls");
				echo $html;
				exit();
				}
                //导出订单功能结束
			}

} elseif ($operation == 'detail') {
	$gid=$_GPC['fromgid'];
	$ordersn = intval($_GPC['ordersn']);
	//按订单编号（ordersn）筛选订单
	$item = pdo_fetch("SELECT * FROM " . tablename('fm453_shopping_order') . " WHERE `ordersn` LIKE '%{$_GPC['ordersn']}%'");
	//订单列表数据
	$paytype = array (
			'0' => array('css' => 'default', 'name' => '未支付'),
			'1' => array('css' => 'danger','name' => '余额支付'),
			'2' => array('css' => 'info', 'name' => '在线支付'),
			'3' => array('css' => 'warning', 'name' => '货到付款')
		);
		$orderstatus = array (
			'-1' => array('css' => 'default', 'name' => '已取消'),
			'0' => array('css' => 'danger', 'name' => '待付款'),
			'1' => array('css' => 'info', 'name' => '待发货'),
			'2' => array('css' => 'warning', 'name' => '待收货'),
			'3' => array('css' => 'success', 'name' => '已完成')
		);
		//定义支付方式与订单状态
	$id = $item['id'];	//获取订单ID号
	//下面是获取新添加的联系信息、订单更多信息
	           $value=$item;
			$contactinfo=unserialize($value['contactinfo']);
			     $value['username'] =$contactinfo['username'];
                 $value['mobile'] =$contactinfo['mobile'];
             $aboutinfos=unserialize($value['aboutinfos']);
                   $value['goodtpl'] =$aboutinfos['goodtpl'];
                   $goodtpl=$value['goodtpl'];
                   $value['mpaccountname'] =$aboutinfos['mpaccountname'];
                   $mpaccountname =$value['mpaccountname'];
                    $value['ucontainer'] =$aboutinfos['ucontainer'];
                    $value['uos'] =$aboutinfos['uos'];
              $goodtplinfos =unserialize($aboutinfos['infos']);
                    include_once  FM_PUBLIC.'goodtpl/forweborder.php';   //require请求失败则程序不继续；开发完成改用include；文件顺序不可更改——BYFM453
                    $value['tips']=$tips;
                    //echo $tips;
            //获取新添加信息 结束
     //下面获取订单分销信息
          $orderid = $value['id'];
          $commission = pdo_fetchall("select total,commission, commission2, commission3 from ".tablename('fm453_shopping_order_goods')." where orderid = ".$orderid);
          $value['commission'] = 0;
			 $value['commission2'] = 0;
			 $value['commission3'] = 0;
          foreach ($commission as $key=>$l){
				$value['commission'] += $commission[$i]['commission']* $commission[$i]['total'];
				$value['commission2'] += $commission[$i]['commission2']* $commission[$i]['total'];
				$value['commission3'] += $commission[$i]['commission3']* $commission[$i]['total'];
				}
			$members = pdo_fetchall("select * from ".tablename('fm453_shopping_member')." where uniacid = ".$_W['uniacid']." and status = 1");
			$member = array();
			foreach($members as $m){
				$member[$m['id']] = $m['realname'];
			}
					$value['shareby'] =$m[$value['shareid']];
					if($value['shareid']==0) {
						$value['shareby'] ='总店';
            	}
          //获取分销信息 结束
	$webpayid = pdo_fetch("SELECT * FROM " . tablename('core_paylog') . " WHERE tid = :ordersn", array(':ordersn' => $ordersn));
	//查询支付表里的记录号plid
	$order_from_user = $item['from_user'];
	$orderfans = pdo_fetch("SELECT * FROM " . tablename('mc_mapping_fans') . " WHERE uniacid = :uniacid AND openid LIKE '%{$order_from_user}%'", array(':uniacid' =>$_W['uniacid']));	//获取系统的粉丝表数据; 添加了判断当前主公众号的查询，解决重复openid的问题
	$fanid = $orderfans['fanid'];	//获取订单关联粉丝ID号。
	$ordermembers = pdo_fetch("SELECT * FROM " . tablename('mc_member_address') . " WHERE id = :addressid", array(':addressid' => $item['addressid']));
	$fansuid = $ordermembers['uid'];	//获取订单关联粉丝UID号; 经测试，实际上mc_mapping_fans表里的uid也是粉丝UID号，跟这里的值是一样的$orderfans['uid']。
	if (empty($item)) {
		message("抱歉，订单不存在!", referer(), "error");
	}
		//发货方式及快递方式
		$dispatch = pdo_fetch("SELECT * FROM " . tablename('fm453_shopping_dispatch') . " WHERE id = :id", array(':id' => $item['dispatch']));
		if (!empty($dispatch) && !empty($dispatch['express'])) {
			$express = pdo_fetch("select * from " . tablename('fm453_shopping_express') . " WHERE id=:id limit 1", array(":id" => $dispatch['express']));
		}
		//根据地址id获取用户收货地址信息
		$value['user'] = pdo_fetch("SELECT * FROM " . tablename('mc_member_address') . " WHERE id = {$item['addressid']}");
		$users=$value['user'];
		//print_r($value['user'] );
		//下面开始获取产品相关的明细信息
		$ordergoods = pdo_fetchall("SELECT g.*, o.total,g.type,o.optionname,o.optionid,o.price as orderprice FROM " . tablename('fm453_shopping_order_goods') . " o left join " . tablename('fm453_shopping_goods') . " g on o.goodsid=g.id " . " WHERE o.orderid='{$id}'");
			//print_r($ordergoods);
		// 各项产品明细//by fm453 151111 细化产品信息获取
		foreach ($ordergoods as &$singlegoods) {
			//print_r($singlegoods['optionid']);
			//规格及规格项，当订单中关联产品存在规格id值时才操作
			if(!empty($singlegoods['optionid'])) {
			$options = pdo_fetch("select specs from " . tablename('fm453_shopping_goods_option') . " where id=:id order by id asc", array(':id' => $singlegoods['optionid']));
			//print_r($options);
			//开始整理具体规格值
			//找出数据库存储的排列顺序
			if (count($options) > 0) {
				$specitemids = explode("_", $options['specs'] );//从产品规格id字符串中分拆出各个规格的id,用以在规格表fm453_shopping_spec_item中查找对应项
				//print_r($specitemids);
				foreach($specitemids as $tkey => $specitemid){
					$specitem = pdo_fetch("select * from " . tablename('fm453_shopping_spec_item') . " where id=:id", array(':id' => $specitemid));//具体规格项的全部规格值
					$spectitle = pdo_fetchcolumn("select title from " . tablename('fm453_shopping_spec') . " where id=:id", array(':id' => $specitem['specid']));//根据规格项的id获取规格名称
					$singlegoodsspecs[]=array(
					'title' => $spectitle,
					'item' => $specitem
					);
				}
				$singlegoods['specs']=$singlegoodsspecs;
				//print_r($singlegoods['specs']);
				unset($singlegoodsspecs);
				}
			}
			//调用产品自定义标签
		$labels = pdo_fetchall("select * from " . tablename('fm453_shopping_goods_label') . " where goodsid=:id order by displayorder asc", array(':id' => $singlegoods['id']));
		$singlegoods['label_pic_alt']=$labels[0]['title'];
		$singlegoods['label_pic_src']=$labels[0]['value'];
		$singlegoods['label_span_title']=$labels[1]['title'];
		$singlegoods['label_span_value']=$labels[1]['value'];

		//调用产品营销模型信息
		$marketmodels = pdo_fetch("select * from " . tablename('fm453_shopping_goods_marketmodel') . " where gid=:id", array(':id' => $r['id']));
		$item['marketmodel']=$marketmodels;
		}
		//print_r($ordergoods);
		$value['goods'] = $ordergoods;
		//print_r($value['goods'] );
		$item=$value;
//$value为订单全部相关数据；$item只是从订单表fm453_shopping_order中获取的数据。现在将它们汇总显示
	if (checksubmit('confirmsend')) {
		if (!empty($_GPC['isexpress']) && empty($_GPC['expresssn'])) {
			message('请输入快递单号！');
		}
		//$item = pdo_fetch("SELECT transid FROM " . tablename('fm453_shopping_order') . " WHERE ordersn LIKE '%$ordersn%'",  array('id' => $id)); //
		if (!empty($item['transid'])) {
			$this->changeWechatSend($id, 1);
		}
		pdo_update('fm453_shopping_order',
			array(
				'status' => 2,
				'express' => $_GPC['express'],
				'expresscom' => $_GPC['expresscom'],
				'expresssn' => $_GPC['expresssn'],
			),
			 array('id' => $id)
		);
		$logs ='确认发货，快递单号：'.$_GPC['expresssn'];
			$logs .='---by  ' . $_W['username'];
			$logs .='(UID:'. $_W['uid'] .')操作时间:' . date('y年m月d日 h:i:s', $timestamp =  TIMESTAMP);
			$logs .='；||';
			$logs .=$value['logs'];
			pdo_update('fm453_shopping_order', array('logs' => $logs ), array('id' => $id));
		message('发货操作成功！', referer(), 'success');
	}
	if (checksubmit('cancelsend')) {
		$item = pdo_fetch("SELECT transid FROM " . tablename('fm453_shopping_order') . " WHERE ordersn LIKE '%$ordersn%'",  array(':ordersn' => $ordersn));
		if (!empty($item['transid'])) {
			$this->changeWechatSend($id, 0, $_GPC['cancelreson']);
		}
		pdo_update('fm453_shopping_order',
			array(
				'status' => 1,
				'remark_kf' => $_GPC['remark_kf'],
			),
			array('id' => $id)
		);
		$logs ='取消发货';
			$logs .='---by  ' . $_W['username'];
			$logs .='(UID:'. $_W['uid'] .')操作时间:' . date('y年m月d日 h:i:s', $timestamp =  TIMESTAMP);
			$logs .='；||';
			$logs .=$value['logs'];
			pdo_update('fm453_shopping_order', array('logs' => $logs ), array('id' => $id));
		message('取消发货操作成功！', referer(), 'success');
		}
		if (checksubmit('kfbeizhu')) {
			$remark_kf =$_GPC['remark_kf'];
			$remark_kf .='---by  ' . $_W['username'];
			$remark_kf .='(UID:'. $_W['uid'] .')操作时间:' . date('y年m月d日 h:i:s', $timestamp =  TIMESTAMP);
			$remark_kf .='；';
			pdo_update('fm453_shopping_order', array('remark_kf' => $remark_kf ), array('id' => $id));
			$logs ='添加客服备注:'.$_GPC['remark_kf'];
			$logs .='---by  ' . $_W['username'];
			$logs .='(UID:'. $_W['uid'] .')操作时间:' . date('y年m月d日 h:i:s', $timestamp =  TIMESTAMP);
			$logs .='；||';
			$logs .=$value['logs'];
			pdo_update('fm453_shopping_order', array('logs' => $logs ), array('id' => $id));
			message('客服备注成功！', referer(), 'success');
		}
		if (checksubmit('changeprice') ) {
			//未付款的订单才可改价
			if($value['status'] ==0) {
				//改价前，先软清支付记录（在原tid前加上删除标记）
				$ischanged=pdo_update('core_paylog', array('tid' => 'DEL'.$ordersn), array('plid' => $webpayid['plid']));//修改支付记录表中的金额记录；
				if($ischanged) {
					pdo_update('fm453_shopping_order', array('price' => $_GPC['newprice']), array('id' => $id));//修改订单中的价格
					$logs ='改价：:'.$_GPC['newprice'];
					$logs .='---by  ' . $_W['username'];
					$logs .='(UID:'. $_W['uid'] .')操作时间:' . date('y年m月d日 h:i:s', $timestamp =  TIMESTAMP);
					$logs .='；||';
					$logs .=$value['logs'];
					pdo_update('fm453_shopping_order', array('logs' => $logs ), array('id' => $id));
					message('改价成功，请通知客人完成支付！', referer(), 'success');
				}else{
					message('订单不是待支付状态；错误状态码为'.$value['status'].'！', referer(), 'error');
				}
			}
		}
		if (checksubmit('finish')) {
			pdo_update('fm453_shopping_order', array('status' => 3, 'remark_kf' => $_GPC['remark_kf']), array('id' => $id));
			$logs ='确认完成订单';
			$logs .='---by  ' . $_W['username'];
			$logs .='(UID:'. $_W['uid'] .')操作时间:' . date('y年m月d日 h:i:s', $timestamp =  TIMESTAMP);
			$logs .='；||';
			$logs .=$value['logs'];
			pdo_update('fm453_shopping_order', array('logs' => $logs ), array('id' => $id));
			message('订单操作成功！', referer(), 'success');
		}
		if (checksubmit('cancel')) {
			pdo_update('fm453_shopping_order', array('status' => 1, 'remark_kf' => $_GPC['remark_kf']), array('id' => $id));
			$logs ='取消订单';
			$logs .='---by  ' . $_W['username'];
			$logs .='(UID:'. $_W['uid'] .')操作时间:' . date('y年m月d日 h:i:s', $timestamp =  TIMESTAMP);
			$logs .='；||';
			$logs .=$value['logs'];
			pdo_update('fm453_shopping_order', array('logs' => $logs ), array('id' => $id));
			message('取消完成订单操作成功！', referer(), 'success');
		}
		if (checksubmit('cancelpay')) {
			pdo_update('fm453_shopping_order', array('status' => 0, 'remark_kf' => $_GPC['remark_kf']), array('id' => $id));
			//设置库存
			$this->setOrderStock($id, false);
			//减少积分
			$this->setOrderCredit($id, false);
			$logs ='取消订单付款';
			$logs .='---by  ' . $_W['username'];
			$logs .='(UID:'. $_W['uid'] .')操作时间:' . date('y年m月d日 h:i:s', $timestamp =  TIMESTAMP);
			$logs .='；||';
			$logs .=$value['logs'];
			pdo_update('fm453_shopping_order', array('logs' => $logs ), array('id' => $id));
			message('取消订单付款操作成功！', referer(), 'success');
		}
		if (checksubmit('confrimpay')) {
			pdo_update('fm453_shopping_order', array('status' => 1, 'paytype' => 2, 'remark_kf' => $_GPC['remark_kf']), array('id' => $id));
			//设置库存
			$this->setOrderStock($id);
			//增加积分
    		$this->setOrderCredit($id);
    		$logs ='后台手动确认订单付款';
			$logs .='---by  ' . $_W['username'];
			$logs .='(UID:'. $_W['uid'] .')操作时间:' . date('y年m月d日 h:i:s', $timestamp =  TIMESTAMP);
			$logs .='；||';
			$logs .=$value['logs'];
			pdo_update('fm453_shopping_order', array('logs' => $logs ), array('id' => $id));
			message('确认订单付款操作成功！', referer(), 'success');
		}
		if (checksubmit('close')) {
			$item = pdo_fetch("SELECT transid FROM " . tablename('fm453_shopping_order') . " WHERE id = :id", array(':id' => $id));
			if (!empty($item['transid'])) {
				$this->changeWechatSend($id, 0, $_GPC['reson']);
			}
			pdo_update('fm453_shopping_order', array('status' => -1, 'remark_kf' => $_GPC['remark_kf']), array('id' => $id));
			$logs ='关闭订单';
			$logs .='---by  ' . $_W['username'];
			$logs .='(UID:'. $_W['uid'] .')操作时间:' . date('y年m月d日 h:i:s', $timestamp =  TIMESTAMP);
			$logs .='；||';
			$logs .=$value['logs'];
			pdo_update('fm453_shopping_order', array('logs' => $logs ), array('id' => $id));
			message('订单关闭操作成功！', referer(), 'success');
		}
		if (checksubmit('open')) {
			$logs ='开启订单';
			$logs .='---by  ' . $_W['username'];
			$logs .='(UID:'. $_W['uid'] .')操作时间:' . date('y年m月d日 h:i:s', $timestamp =  TIMESTAMP);
			$logs .='；||';
			$logs .=$value['logs'];
			pdo_update('fm453_shopping_order', array('logs' => $logs ), array('id' => $id));
			pdo_update('fm453_shopping_order', array('status' => 0, 'remark_kf' => $_GPC['remark_kf']), array('id' => $id));
			message('开启订单操作成功！', referer(), 'success');
		}
		//订单详情页删除订单按钮
		if (checksubmit('deleteorder')) {
				pdo_update('fm453_shopping_order', array('deleted' => '1'), array('id' => $item['id']));	//deleted为1时，删除；为0时保留

				message('订单删除操作成功！',$this->createWebUrl('order', array('op' => 'display')), 'success');
		}
		//订单详情页恢复订单按钮
		if (checksubmit('recoveryorder')) {
			pdo_update('fm453_shopping_order', array('deleted' => '0'), array('id' => $item['id']));
			message('订单恢复操作成功！', referer(), 'success');
		}
		// 订单取消
		if (checksubmit('cancelorder')) {
			if ($item['status'] == 1) {
				load()->model('mc');
				$memberId = mc_openid2uid($item['from_user']);
				mc_credit_update($memberId, 'credit2', $item['price'], array($_W['uid'], '嗨旅行微商城取消订单，系统退回款项到会员余额'));
			}
			$logs ='后台取消订单';
			$logs .='---by  ' . $_W['username'];
			$logs .='(UID:'. $_W['uid'] .')操作时间:' . date('y年m月d日 h:i:s', $timestamp =  TIMESTAMP);
			$logs .='；||';
			$logs .=$value['logs'];
			pdo_update('fm453_shopping_order', array('logs' => $logs ), array('id' => $id));
			pdo_update('fm453_shopping_order', array('status' => '-1'), array('id' => $item['id']));
			message('订单取消操作成功！', referer(), 'success');
		}
} elseif ($operation == 'delete') {
			/*订单页删除,现在未用到*/
	$ordersn = intval($_GPC['ordersn']);
	if (pdo_update('fm453_shopping_order', array('deleted' => '1'), array('ordersn' => $ordersn))) {
		message("订单删除操作成功！", $this->createWebUrl('order', array('op' => 'display')), 'success');
	} else {
		message('订单不存在或已被删除', $this->createWebUrl('order', array('op' => 'display')), 'error');
	}
}

include $this->template($fm453style.'order');