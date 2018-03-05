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
 * @remark 畅言评论管理；
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;

load()->func('tpl');
load()->model('mc');
load()->model('account');//加载公众号函数
fm_load()->fm_func('route'); //获取路径函数
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
$root=$_W['siteroot'];
$uniacid=$_W['uniacid'];
$acid=$_W['acid'];

$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';//display,汇总页面；detail，评论的详情页
if($operation=="display"){
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;
	$limit = ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
	$deleted = $_GPC['deleted'];
	$from_who=$_GPC['from_who'];
	$starttime = strtotime($_GPC['createtime']['start']);
	$endtime = strtotime($_GPC['createtime']['end'])+86399 ;

	$createtime = array();
	$createtime['start']="1970-01-01 00:00:00";
	$createtime['end']=date("Y-m-d",TIMESTAMP);
	$ispayed=$_GPC['ispayed'];
	$condition=" WHERE ";
	$condition.="`uniacid` ={$uniacid}";
	if(!empty($from_who)){
		$condition.=" AND `openid` LIKE '%{$from_who}%' OR `uid` ='{$from_who}' OR `nickname` LIKE '%{$from_who}%' ";
	}
	if (!empty($_GPC['createtime'])) {
		$condition .= " AND `createtime` >= {$starttime} AND `createtime` <= {$endtime} ";
	}

	if (!empty($_GPC['orderid'])) {
		$condition .= " AND `orderid` = ".intval($_GPC['orderid']);
	}

	if ($deleted == 1) {
		$condition .= " AND `deleted` = ". intval($deleted)."";
	}else{
		$deleted = 0;
		$condition .= " AND `deleted` = ". intval($deleted)."";
	}
	$displayorder=" ORDER BY createtime DESC";
	$table = 'fm453_shopping_comment';
	$sql='SELECT COUNT(*) FROM ' . tablename($table) . $condition;
	$total = pdo_fetchcolumn($sql);
	if ($total > 0) {
		if ($_GPC['export'] != 'export') {
			$sql ='SELECT * FROM ' . tablename($table) . $condition .$displayorder .$limit;
		} else {
			$sql ='SELECT * FROM ' . tablename($table) . $condition .$displayorder;
		}
		$list = pdo_fetchall($sql);
		$pager = pagination($total, $pindex, $psize);
		$ispaytype = array (
			'1' => array('css' => 'success', 'name' => '是'),
			'0' => array('css' => 'danger','name' => '否'),
		);
		// foreach($list as $lkey=>&$comments){
		// 	//
		// }
        //导出功能
		if ($_GPC['export'] != '') {
			/* 输入到xls文件 */
			$html = "\xEF\xBB\xBF";
				/* 输出表头 */
			$filter = array(
				'id' => '记录id',
				'username' => '联系人姓名',
				'mobile' => '联系电话',
				'address' => '联系地址',
				'from_openid' => '粉丝openid',
				'from_uid' => '会员ID',
				'starttime' => '入住日期',
				'endtime' => '离店日期',
				'rnumber' => '房号',
				'createtime' => '提交时间',

			);
			foreach ($filter as $key => $title) {
				$html .= $title . "\t,";
			}
			$html .= "\n";
			foreach ($list as $k => $v) {
				foreach ($filter as $key => $title) {
					if ($key == 'createtime') {
						$html .= date('Y-m-d H:i:s', $v[$key]) . "\t, ";
						}elseif($key == 'starttime') {
						$html .= date('Y-m-d', $v[$key]) . "\t, ";
						}elseif($key == 'endtime') {
						$html .= date('Y-m-d', $v[$key]) . "\t, ";
					} else {
						$html .= $v[$key] . "\t, ";
					}
				}
				$html .= "\n";
			}
			/* 输出CSV文件 */
			header("Content-type:text/csv");
			header("Content-Disposition:attachment; filename=搜狐畅言评论数据_".date('ymdHis',TIMESTAMP).".xls");
			echo $html;
			exit();
		}
                //导出订单功能结束
	}

}elseif($operation=="detail") {
	$id=$_GPC['id'];
	$sql ="SELECT * FROM " . tablename('fm453_duokefu_comment') . " WHERE `id`='{$id}'";
	$item=pdo_fetch($sql);
	$commentfan = pdo_fetch("SELECT * FROM " . tablename('mc_mapping_fans') . " WHERE uniacid = :uniacid AND openid LIKE '%{$item['from_openid']}%'", array(':uniacid' =>$_W['uniacid']));	//获取系统的粉丝表数据;
	$fanid = $commentfan['fanid'];	//获取关联粉丝ID号
	if (checksubmit('confirmrefuse')) {
			pdo_update('fm453_duokefu_comment', array('ischecked' => 0, 'why_failure' => $_GPC['why_failure']), array('id' => $id));
			$logs="";
			$logs .="评论审核不通过";
			$logs .="（执行方式：后台管理）";
			$logs .="理由：".$_GPC['why_failure'];
			$logs .='---by  ' . $_W['username'];
			$logs .='(UID:'. $_W['uid'] .')操作时间:' . date('y年m月d日 H:i:s', $timestamp =  TIMESTAMP);
			$logs .="\r\n";
			$logs .="<br>";
			$logs .=$item['log'];
			pdo_update('fm453_duokefu_comment', array('log' => $logs), array('id' => $id));
			message('评论取消审核执行完成！', referer(), 'success');
		}
	if (checksubmit('confirmreply')) {
			pdo_update('fm453_duokefu_comment', array('reply' => $_GPC['reply']), array('id' => $id));
			$logs="";
			$logs .="评论回复";
			$logs .="（执行方式：后台管理）";
			$logs .="内容：".$_GPC['reply'];
			$logs .='---by  ' . $_W['username'];
			$logs .='(UID:'. $_W['uid'] .')操作时间:' . date('y年m月d日 H:i:s', $timestamp =  TIMESTAMP);
			$logs .="\r\n";
			$logs .="<br>";
			$logs .=$item['log'];
			pdo_update('fm453_duokefu_comment', array('log' => $logs), array('id' => $id));
			message('评论回复成功！', referer(), 'success');
		}
		if (checksubmit('confirmpay')) {
			pdo_update('fm453_duokefu_comment', array('pay_no'=>$_GPC['pay_no'],'pay_money' => $_GPC['pay_money']), array('id' => $id));
			$logs="";
			$logs .="确认发放奖励";
			$logs .="（执行方式：后台管理）";
			$logs .="支付流水号：".$_GPC['pay_no']."；";
			$logs .="奖励金额：".$_GPC['pay_money']."元；";
			$logs .='---by  ' . $_W['username'];
			$logs .='(UID:'. $_W['uid'] .')操作时间:' . date('y年m月d日 H:i:s', $timestamp =  TIMESTAMP);
			$logs .="\r\n";
			$logs .="<br>";
			$logs .=$item['log'];
			pdo_update('fm453_duokefu_comment', array('log' => $logs), array('id' => $id));
			message('奖励发放通知成功！', referer(), 'success');
		}

}elseif($operation=="pass") {
	$id=$_GPC['id'];
	$sql ="SELECT * FROM " . tablename('fm453_duokefu_comment') . " WHERE `id`='{$id}'";
	$comment=pdo_fetch($sql);
	pdo_query("update " . tablename('fm453_duokefu_comment') . " set ischecked=1 where id=:id and uniacid='{$_W['uniacid']}' ", array(":id" => $id));
			$logs="";
			$logs .="评论审核通过";
			$logs .="（执行方式：后台管理）";
			$logs .='---by  ' . $_W['username'];
			$logs .='(UID:'. $_W['uid'] .')操作时间:' . date('y年m月d日 H:i:s', $timestamp =  TIMESTAMP);
			$logs .="\r\n";
			$logs .="<br>";
			$logs .=$comment['log'];
			pdo_update('fm453_duokefu_comment', array('log' => $logs), array('id' => $id));
	message('审核操作执行成功！',$this->createWebUrl('comment', array('ac'=>'dianping','op' => 'detail', 'id' => $id)),"success");
	exit();

}elseif($operation=="public") {
	$id=$_GPC['id'];
	$sql ="SELECT * FROM " . tablename('fm453_duokefu_comment') . " WHERE `id`='{$id}'";
	$comment=pdo_fetch($sql);
	if($comment['ischecked']==1) {
		pdo_query("update " . tablename('fm453_duokefu_comment') . " set ispublic=1 where id=:id and uniacid='{$_W['uniacid']}' ", array(":id" => $id));
			$logs="";
			$logs .="评论上墙公开";
			$logs .="（执行方式：后台管理）";
			$logs .='---by  ' . $_W['username'];
			$logs .='(UID:'. $_W['uid'] .')操作时间:' . date('y年m月d日 H:i:s', $timestamp =  TIMESTAMP);
			$logs .="\r\n";
			$logs .="<br>";
			$logs .=$comment['log'];
			pdo_update('fm453_duokefu_comment', array('log' => $logs), array('id' => $id));
		message('评论上墙开放操作执行成功！',$this->createWebUrl('comment', array('ac'=>'dianping','op' => 'detail', 'id' => $id)),"success");
		exit();
	}else {
		message('评论未通过审核，不可上墙公开！', referer(), 'false');
	}

}elseif($operation=="delete") {
	$id=$_GPC['id'];
	$sql ="SELECT * FROM " . tablename('fm453_duokefu_comment') . " WHERE `id`='{$id}'";
	$comment=pdo_fetch($sql);
	pdo_query("update " . tablename('fm453_duokefu_comment') . " set deleted=1 where id=:id and uniacid='{$_W['uniacid']}' ", array(":id" => $id));
			$logs="";
			$logs .="删除评论";
			$logs .="（执行方式：后台管理）";
			$logs .='---by  ' . $_W['username'];
			$logs .='(UID:'. $_W['uid'] .')操作时间:' . date('y年m月d日 H:i:s', $timestamp =  TIMESTAMP);
			$logs .="\r\n";
			$logs .="<br>";
			$logs .=$comment['log'];
			pdo_update('fm453_duokefu_comment', array('log' => $logs), array('id' => $id));
	message('评论删除操作执行成功！',$this->createWebUrl('comment', array('ac'=>'dianping','op' => 'display')),"success");
	exit();
}
include $this->template($fm453style.$do.'/453');
