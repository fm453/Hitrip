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
 * @remark 商品列表；
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;
load()->func('tpl');
load()->model('account');//加载公众号函数
fm_load()->fm_model('category'); //分类管理模块

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

//POST筛选数据
if (!empty($_GPC['keyword'])) {
	$condition .= ' AND `title` LIKE :title';
	$params[':title'] = '%' . trim($_GPC['keyword']) . '%';
}

if (!empty($_GPC['goodstpl'])) {
	if($_GPC['goodstpl']=='all') {

	}else{
		$condition .= ' AND `goodtpl` LIKE :goodstpl';
		$params[':goodstpl'] = '%' . trim($_GPC['goodstpl']) . '%';
	}
}

if (isset($_GPC['status'])) {
	if($_GPC['status']=='all') {

	}else{
		$condition .= ' AND `status` = :status';
		$params[':status'] = intval($_GPC['status']);
	}
}

if (isset($_GPC['shuxing'])) {
	if($_GPC['shuxing']=='all') {

	}else{
		$condition .= ' AND `'.$_GPC['shuxing'].'` = 1';
	}
}

if (!empty($_GPC['category']['childid'])) {
	$condition .= ' AND `ccate` = :ccate';
	$params[':ccate'] = intval($_GPC['category']['childid']);
}

if (!empty($_GPC['category']['parentid'])) {
	$condition .= ' AND `pcate` = :pcate';
	$params[':pcate'] = intval($_GPC['category']['parentid']);
}

if (isset($_GPC['type'])) {
	if($_GPC['type']=='all') {

	}else{
		$condition .= ' AND `type` = :type';
		$params[':type'] = intval($_GPC['type']);
	}
}

//按当前条件所得的产品总量并且分页
$sql = 'SELECT COUNT(id) FROM ' . tablename('fm453_shopping_goods') . $condition;
$total = pdo_fetchcolumn($sql, $params);
$pager = pagination($total, $pindex, $psize);
//排序及截断
$showorder=" ORDER BY uniacid ASC, displayorder DESC, createtime DESC, id DESC ";
$limit=" LIMIT ".($pindex-1)*$psize.",".$psize;

if ($operation == 'display') {
	if (!empty($total)) {
		$sql = 'SELECT * FROM ' . tablename('fm453_shopping_goods') . $condition. $showorder. $limit;
		$list = pdo_fetchall($sql, $params);
	}else{
		message('还没有商品可以展示，现在跳转至新增产品链接！', fm_wurl($do,'detail','display',array()), 'info');
	}

	if(is_array($list)) {
		foreach ($list as $index => &$row) {
			$code='code'.$row['statuscode'];
			$row['statusname']=$catestatus[$code]['name'];
			$row['plataccount']=uni_account_default($row['uniacid']);
			if(empty($row['sn']) || $row['sn']==0) {
				$row['sn']=$row['id'];
				pdo_update('fm453_shopping_goods', array('sn' => $row['id']), array('id' => $row['id']));
			}//对SN进行赋值
			if($row['uniacid']==0) {
				$row['plataccount']['name']="系统平台";
			}
			if(empty($row['plataccount'])) {
				$row['plataccount']['name']="已经删除";
			}
			$row['stock'] = !empty($row['stock']) ? $row['stock'] : $row['total'];	//针对老数据，更新真实库存 160905
			$row['totalStock'] =  $row['stock']+$row['realsales'];
			$row['total'] = ($row['total'] == -1) ? '无限' : $row['total'];
			unset($index);
			unset($code);
		}
	}
	unset($row);

	//更新排序与编码（编码排序暂不开放）
	if (!empty($_GPC['displayorder']) ||!empty($_GPC['sn'])) {
		if (!empty($_GPC['displayorder'])) {
			foreach ($_GPC['displayorder'] as $id => $displayorder) {
				pdo_update('fm453_shopping_goods', array('displayorder' => $displayorder), array('id' => $id));
			}
			$dologs=array(
				'url'=>$_W['siteurl'],
				'description'=>'后台更新商城产品排序；',
				'addons'=>$_GPC['displayorder'],
			);
			fmMod_log_record($_W['uniacid'],$_W['uniacid'],$_W['uid'],$fanid,$openid,'fm453_shopping_goods',$id,'update',$dologs);
			//给管理员发个微信消息
			require MODULE_ROOT.'/core/msgtpls/tpls/goods.php';
		//发微信模板通知
		$postdata = $tplnotice_data['list']['displayorder']['update']['admin'];
		$result= fmMod_notice_tpl($postdata);
		if(!$result) {
			require MODULE_ROOT.'/core/msgtpls/msg/goods.php';
			$postdata = $notice_data['list']['displayorder']['update']['admin'];
			$result= fmMod_notice($settings['manageropenids'],$postdata);
		}
			unset($dologs);
		}
		if (!empty($_GPC['sn'])) {
			foreach ($_GPC['sn'] as $id => $sn) {
				pdo_update('fm453_shopping_goods', array('sn' => $sn), array('id' => $id));
			}
			$dologs=array(
				'url'=>$_W['siteurl'],
				'description'=>'后台更新商城产品编码；',
				'addons'=>$_GPC['sn'],
			);
			fmMod_log_record($_W['uniacid'],$_W['uniacid'],$_W['uid'],$fanid,$openid,'fm453_shopping_goods',$id,'update',$dologs);
			//发微信模板通知
		$postdata = $tplnotice_data['list']['sn']['update']['admin'];
		$result= fmMod_notice_tpl($postdata);
		if(!$result) {
			require MODULE_ROOT.'/core/msgtpls/msg/goods.php';
			$postdata = $notice_data['list']['sn']['update']['admin'];
			$result= fmMod_notice($settings['manageropenids'],$postdata);
		}
			unset($dologs);
		}
		message('分类排序\编码更新成功！',fm_wurl($do,$ac,$op,''), 'success');
	}
	include $this->template($fm453style.$do.'/453');
}
elseif($operation=='export') {
	$haslimit=!empty($_GPC['haslimit']) ? $_GPC['haslimit'] : 'page';
	if($haslimit=='all') {
		$outdata = pdo_fetchall("SELECT * FROM " . tablename('fm453_shopping_goods') . $condition.$showorder,$params);
	}elseif($haslimit=='page') {
		$outdata = pdo_fetchall("SELECT * FROM " . tablename('fm453_shopping_goods') . $condition.$showorder.$limit,$params);
	}
	//导出数据START
	if ($_GPC['export']) {
		/* 输入到CSV文件 */
		$html = "\xEF\xBB\xBF";//UTF8标记
		$html .= "\n";
		/* 输出表头 */
		$filter = array(
			'sn' => '分类编号',
			'displayorder' => '排序',
			'title' => '商品标题',
			'goodtpl' => '产品模型',
			'datemodel' => '假期活动类型',
			'category' => '产品分类',
			'type' => '产品类型',
			'isrecommand' => '首页推荐',
			'ishot' => '热门',
			'isnew' => '新品',
			'isdiscount' => '折扣',
			'istime' => '限时',
			'timestart' => '开始时间',
			'timeend' => '结束时间',
			'description' => '产品描述',
			'createtime' => '创建时间',
			'isagent' => '是否代理',
			'goodssn' => '产品编码',
			'unit' => '单位',
			'weight' => '重量',
			'total' => '库存',
			'stock' => '真实库存',
			'totalcnf' => '减库存的方式',
			'marketprice' => '销售价',
			'agentsaleprice' => '代理销售价',
			'costprice' => '成本价',
			'agentprice' => '代理结算价',
			'originalprice' => '原价',
			'cankaoprice' => '市场参考价',
			'productsn' => '产品序号',
			'credit' => '奖励积分',
			'maxbuy' => '单次购买上限',
			'usermaxbuy' => '用户购买上限',
			'hasoption' => '启用多规格',
			'sales' => '销量',
			'realsales' => '真实销量',
			'goodadm' =>'管理专员',
			'commission' => '佣金比例',
			'uniacid' => '公号',
			'platname' => '平台',
		);
		foreach ($filter as $key => $title) {
			$html .= $title . "\t";
		}
		$html .= "\n";
		foreach ($outdata as $k => $v) {
			foreach ($filter as $key => $title) {
				if($key=="platname") {
					$default= uni_account_default($v['uniacid']);
					$html .= $default['name']."\t";
				}elseif($key=="sn") {
					$html .= "'".$v['sn']."\t";
				}elseif($key=="createtime") {
					$html .= date('Y年m月d日 H:i:s',$v['createtime'])."\t";
				}elseif($key=="timestart") {
					$html .= date('Y年m月d日 H:i:s',$v['timestart'])."\t";
				}elseif($key=="timeend") {
					$html .= date('Y年m月d日 H:i:s',$v['timeend'])."\t";
				}elseif($key=="category") {
					$html .= $category[$v['pcate']]['name']."-".$category[$v['ccate']]['name']."\t";
				}elseif($key=="commission") {
					$html .= '1级'.$v['commission']."%；". '2级'.$v['commission2']."%；". '3级'.$v['commission2']."%；"."\t";
				}else {
					$html .= $v[$key] . "\t";
				}
			}
			$html .= "\n";
		}
		/* 输出CSV文件 */
		header("Content-type:text/xls");
		header("Content-Type:application/force-download");
		header("Content-Type:application/vnd.ms-execl");
		header("Content-Type:application/octet-stream");
		header("Content-Type:application/download");
		header("Content-Transfer-Encoding:binary");
		header("Content-Disposition:attachment; filename=".$shopname."商品列表.xls");
		echo $html;

		$dologs=array(
			'url'=>$_W['siteurl'],
			'description'=>'后台导出商品数据',
		);
		fmMod_log_record($platid,$_W['uniacid'],$_W['uid'],$fanid,$openid,'fm453_shopping_goods',$id,'export',$dologs);
		unset($dologs);
		//给管理员发个微信消息
		fmMod_notice($settings[manageropenids],array(
			'header'=>array('title'=>'事件通知','value'=>'后台导出商品数据'),
			'user'=>array('title'=>'操作人','value'=>$_W['username'].'('.$_W['uid'].')'),
			'url'=>array('title'=>'执行网址','value'=>$_W['siteurl']),
		));
		exit();
	}
//导出数据END
}
elseif($operation=='import'){
	return message('功能暂未开放！','referer','info');
}