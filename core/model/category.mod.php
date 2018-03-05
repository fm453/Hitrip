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
 * @remark	分类管理模型
 */
defined('IN_IA') or exit('Access Denied');
//新建分类
function fmMod_category_new() {
	global $_GPC;
	global $_W;
	global $_FM;
	header("Content-type:text/html;charset=utf-8");//加上网页字符集强制设置，避免中文传输乱码
	$return=array();
	$data = array(
		'uniacid' => intval($_W['uniacid']),
		'sn' => TIMESTAMP,
		'displayorder' => intval($_GPC['displayorder']),
		'title' => $_GPC['goodsname'],
		'goodtpl' => $_GPC['goodtpl'],
		'datemodel' => $_GPC['datemodel'],
		'pcate' => intval($_GPC['category']['parentid']),
		'ccate' => intval($_GPC['category']['childid']),
		'thumb'=>$_GPC['thumb'],
		'type' => intval($_GPC['type']),
		'isrecommand' => intval($_GPC['isrecommand']),
		'ishot' => intval($_GPC['ishot']),
		'isnew' => intval($_GPC['isnew']),
		'isdiscount' => intval($_GPC['isdiscount']),
		'istime' => intval($_GPC['istime']),
		'timestart' => strtotime($_GPC['timestart']),
		'timeend' => strtotime($_GPC['timeend']),
		'description' => $_GPC['description'],
		'content' => htmlspecialchars_decode($_GPC['content']),
		'isagent' => intval($_GPC['isagent']),
		'agentcontent' => htmlspecialchars_decode($_GPC['agentcontent']),
		'goodssn' => $_GPC['goodssn'],
		'unit' => $_GPC['unit'],
		'createtime' => TIMESTAMP,
		'total' => intval($_GPC['total']),
		'totalcnf' => intval($_GPC['totalcnf']),
		'marketprice' => $_GPC['marketprice'],
		'agentsaleprice' => $_GPC['agentsaleprice'],
		'weight' => $_GPC['weight'],
		'costprice' => $_GPC['costprice'],
		'agentprice' => $_GPC['agentprice'],
		'originalprice' => $_GPC['originalprice'],
		'productprice' => $_GPC['productprice'],
		'productsn' => $_GPC['productsn'],
		'credit' => sprintf('%.2f', $_GPC['credit']),
		'maxbuy' => intval($_GPC['maxbuy']),
		'usermaxbuy' => intval($_GPC['usermaxbuy']),
		'hasoption' => intval($_GPC['hasoption']),
		'sales' => intval($_GPC['sales']),
		'realsales' => intval($realsales),
		'lastsales' => intval($lastsales),
		'status' => intval($_GPC['status']),
		'agenttip' => $_GPC['agenttip'],
		'goodadm' =>$_GPC['goodadm'],
		'xsthumb' => $_GPC['xsthumb'],
		'sharethumb' => $_GPC['sharethumb'],
		'commission' => intval($_GPC['commission']),
		'commission2' => intval($_GPC['commission2']),
		'commission3' => intval($_GPC['commission3']),
	);
	if ($data['total'] == -1) {
		//$data['total'] = 0;
		$data['totalcnf'] = 2;
	}
	if ($data['totalcnf'] != 2) {
		//$data['total'] = $data['total']+$data['realsales']
	}
	if(is_array($_GPC['thumbs'])){
		$data['thumb_url'] = serialize($_GPC['thumbs']);
	}
	$result=pdo_insert('fm453_shopping_category', $data);
	return $result;
}

//获取分类列表
function fmMod_category_list() {
	global $_GPC;
	global $_W;
	global $_FM;
	header("Content-type:text/html;charset=utf-8");//加上网页字符集强制设置，避免中文传输乱码
	$return=array();
	$data = array();
}

//更新旧数据
function fmMod_category_old($category) {
	global $_GPC;
	global $_W;
	global $_FM;
	header("Content-type:text/html;charset=utf-8");//加上网页字符集强制设置，避免中文传输乱码
	//没有sn值的即可视为旧数据，需要更新
	foreach ($category as $index => $row) {
		//旧版本数据升级填充START
		if(empty($row['sn'])){
			$row['sn']=$row['id'];
		}
		if(empty($row['psn'])){
			$row['psn']=$row['parentid'];
		}
		if(empty($row['setfor'])){
			$row['setfor']='goods';
		}
		if(empty($row['createtime'])){
			$row['createtime']=TIMESTAMP;
		}
		if($row['enabled']==0) {
			if($row['isrecommand']==1) {
				$row['statuscode']=3;//首页推荐但不显示
			}else{
				$row['statuscode']=0;//还原到刚创建的状态，待审核，不显示
			}
		}elseif($row['enabled']==1) {
			if($row['isrecommand']==1) {
				$row['statuscode']=64;//首页推荐,公众号内全部可见
			}else{
				$row['statuscode']=1;//仅显示，不作任何推荐、附加
			}
		}
		pdo_update('fm453_shopping_category',array('statuscode'=>$row['statuscode'],'sn'=>$row['sn'],'psn'=>$row['psn'],'setfor'=>$row['setfor'],'createtime'=>$row['createtime']),array('id'=>$row['id']));
	//旧版本数据升级填充END
	}
}
//按对象获取可用分类
/*
@$children的各子项，应是无指定键值的数组
@返回array('parent'=>$category,'child'=>$children);
*/
function fmMod_category_get($setfor=NULL) {
	global $_W,$_FM;
	header("Content-type:text/html;charset=utf-8");//加上网页字符集强制设置，避免中文传输乱码
	$return=array();
	$data = array();
	$setfor = !empty($setfor) ? $setfor : 'goods';
	if($setfor=='goods'){
		$fields = "*";
		$sql = "SELECT ".$fields." FROM " . tablename('fm453_shopping_category');
		$condition =  " WHERE ";
		$params = array();
		$condition .=  " uniacid = :uniacid ";
		$params[':uniacid'] = $_W['uniacid'];
		$condition .=  " AND enabled = :enabled";
		$params[':enabled'] = 1;
		$condition .=  " AND setfor = :setfor";
		$params[':setfor'] = $setfor;
		$showorder = " ORDER BY parentid ASC , displayorder DESC";
		$children = array();
		$category = pdo_fetchall($sql.$condition.$showorder, $params, 'id');
		if(!is_array($category)) {
			$return['result'] = 'FALSE';
			$return['msg']='未获取到分类数据';
			return $return;
		}
		foreach ($category as $index => $row) {
			if (!empty($row['parentid'])) {
				//$children[$row['parentid']][$row['id']] = $row;
				$children[$row['parentid']][] = $row;
				unset($category[$index]);
			}
			if ($row['psn']>0) {
				//$children[$row['psn']][$row['sn']] = $row;
				$children[$row['psn']][] = $row;
				unset($category[$index]);
			}
		}
		$allcategory=array('parent'=>$category,'child'=>$children);
		return $allcategory;
	}
	else{
		$fields = "*";
		$sql = "SELECT ".$fields." FROM " . tablename('fm453_shopping_category');
		$condition =  " WHERE ";
		$params = array();
		$condition .=  " uniacid = :uniacid ";
		$params[':uniacid'] = $_W['uniacid'];
		$condition .=  " AND enabled = :enabled";
		$params[':enabled'] = 1;
		$condition .=  " AND setfor = :setfor";
		$params[':setfor'] = $setfor;
		$showorder = " ORDER BY psn ASC , displayorder DESC";
		$children = array();
		$category = pdo_fetchall($sql.$condition.$showorder, $params, 'sn');
		if(!is_array($category)) {
			$return['result'] = 'FALSE';
			$return['msg']='未获取到分类数据';
			return $return;
		}
		foreach ($category as $index => $row) {
			if ($row['psn']>0) {
				//$children[$row['psn']][$row['sn']] = $row;
				$children[$row['psn']][] = $row;
				unset($category[$index]);
			}
		}
		$allcategory=array('parent'=>$category,'child'=>$children);
		return $allcategory;
	}
}
