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
 * @remark 佣金管理；
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;

load()->func('tpl');
load()->model('account');//加载公众号函数
load()->model('mc');

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
$psize=(intval($_GPC['psize'])>5) ? intval($_GPC['psize']) : 5;//最少显示5条主数据

$uniacid=$_W['uniacid'];
$plattype=$settings['plattype'];
$platids = fmFunc_getPlatids();//获取平台模式关联的公众号商户ID参数
$platid=$_W['uniacid'];
$oauthid=$platids['oauthid'];
$fendianids=$platids['fendianids'];
$supplydianids=$platids['supplydianids'];
$blackids=$platids['blackids'];

$gtpltype = fmMod_goodstpl_type_get();//列出全部产品模型清单
$marketmodeltypes =fmFunc_market_types();
$countstatus=fmFunc_status_get('count');
$datatype= fmFunc_data_types();

//平台模式处理
include_once FM_PUBLIC.'plat.php';
$supplydians=explode(',',$supplydianids);//字符串转数组
$supplydians=array_filter($supplydians);//数组去空

//按平台模式前置筛选条件
$condition=' WHERE ';
$params=array();
include_once FM_PUBLIC.'forsearch.php';
//$condition .= ' AND `deleted` = :deleted';
//$params[':deleted'] = 0;

$limit=" LIMIT ".($pindex-1)*$psize.",".$psize;
	$credits = array(
		'all'=>'不限',
		'credit1' => '积分',
		'credit2' => '余额'
	);

$members = pdo_fetchall("select id, realname, mobile from ".tablename('fm453_shopping_member')." where uniacid = ".$_W['uniacid']." and status = 1");
$member = array();
foreach($members as $m){
	$member['realname'][$m['id']] = $m['realname'];
	$member['mobile'][$m['id']] = $m['mobile'];
}
// 正在申请
if($operation=='display'){
	if($_GPC['opp']=='check'){
		$shareid = $_GPC['shareid'];
		// 申请人的信息
		$user = pdo_fetch("select realname, mobile from ".tablename('fm453_shopping_member')." where id = ".$_GPC['shareid']);
		// 申请订单信息
		$info = pdo_fetch("select og.id, og.total, og.price, og.status, og.commission, og.commission2,og.commission3, og.applytime, og.content, g.title from ".tablename('fm453_shopping_order_goods')." as og left join ".tablename('fm453_shopping_goods')." as g on og.goodsid = g.id and og.uniacid = g.uniacid where og.id = ".$_GPC['id']);

	}

	if($_GPC['opp']=='checked'){
			$checked = array(
					'status'=>$_GPC['status'],
					'checktime'=>time(),
					'content'=>trim($_GPC['content'])
				);
				$temp = pdo_update('fm453_shopping_order_goods', $checked, array('id'=>$_GPC['id']));
				if(empty($temp)){
					message('审核失败，请重新审核！', $this->createWebUrl('commission', array('opp'=>'check', 'shareid'=>$_GPC['shareid'], 'id'=>$_GPC['id'])), 'error');
				}else{
					message('审核成功！', $this->createWebUrl('commission'), 'success');
				}
			}

	if($_GPC['opp']=='sort'){
				$sort = array(
					'realname'=>$_GPC['realname'],
					'mobile'=>$_GPC['mobile']
				);
				//$shareid = pdo_fetchall("select id from ".tablename('fm453_shopping_member')." where uniacid = ".$_W['uniacid']." and realname like '%".$sort['realname']."%' and mobile like '%".$sort['mobile']."%'");
				$shareid = "select id from ".tablename('fm453_shopping_member')." where uniacid = ".$_W['uniacid']." and realname like '%".$sort['realname']."%' and mobile like '%".$sort['mobile']."%'";
				$list = pdo_fetchall("select o.shareid, o.status, g.id, g.applytime from ".tablename('fm453_shopping_order')." as o left join ".tablename('fm453_shopping_order_goods'). " as g on o.id = g.orderid and o.uniacid = g.uniacid where o.uniacid = ".$_W['uniacid']." and g.status = 1 and o.shareid in (".$shareid.") ORDER BY o.id desc");
				$total = sizeof($list);
			}else{
				$pindex = max(1, intval($_GPC['page']));
				$psize = 20;
				$list = pdo_fetchall("select o.shareid, o.status, g.id, g.applytime from ".tablename('fm453_shopping_order'). " as o left join ".tablename('fm453_shopping_order_goods'). " as g on o.id = g.orderid and o.uniacid = g.uniacid where o.uniacid = ".$_W['uniacid']." and g.status = 1 ORDER BY o.id DESC limit ".($pindex - 1) * $psize . ',' . $psize);
				$total = pdo_fetchcolumn("select count(o.id) from ".tablename('fm453_shopping_order')." as o left join ".tablename('fm453_shopping_order_goods'). " as g on o.id = g.orderid and o.uniacid = g.uniacid where o.uniacid = ".$_W['uniacid']." and g.status = 1");
				$pager = pagination($total, $pindex, $psize);
			}
			include $this->template($fm453style.$do.'/453');
}

// 审核
if($operation=='check'){
			if($_GPC['opp']=='jieyong'){
				$shareid = $_GPC['shareid'];
				// 申请人的信息
				$user = pdo_fetch("select id, realname, mobile,shareid from ".tablename('fm453_shopping_member')." where id = ".$_GPC['shareid']);
				// 申请订单信息
				$info = pdo_fetch("select og.id, og.total, og.price, og.status, og.commission, og.commission2,og.commission3, og.applytime, og.content, g.title from ".tablename('fm453_shopping_order_goods')." as og left join ".tablename('fm453_shopping_goods')." as g on og.goodsid = g.id and og.uniacid = g.uniacid where og.id = ".$_GPC['id']);
				// 佣金记录
				$commissions = pdo_fetchall("select * from ".tablename('fm453_shopping_commission')." where ogid = ".$_GPC['id'].' and mid='.$_GPC['shareid']);
				$commission = pdo_fetchcolumn("select sum(commission) from ".tablename('fm453_shopping_commission')." where isshare!=1 and ogid = ".$_GPC['id'].' and mid='.$_GPC['shareid']);
				$commission = empty($commission)?0:$commission;
				if(!empty($user['shareid'])){
					$commission2 = pdo_fetchcolumn("select sum(commission) from ".tablename('fm453_shopping_commission')." where isshare=1 and ogid = ".$_GPC['id'].' and mid='.$user['shareid']);
					$commission2 = empty($commission2)?0:$commission2;
					$user2 = pdo_fetch("select id, realname, mobile,shareid from ".tablename('fm453_shopping_member')." where id = ".$user['shareid']);
					if(!empty($user2['shareid'])){
						$commission3 = pdo_fetchcolumn("select sum(commission) from ".tablename('fm453_shopping_commission')." where isshare=1 and ogid = ".$_GPC['id'].' and mid='.$user2['shareid']);
						$commission3 = empty($commission3)?0:$commission3;
						$user3 = pdo_fetch("select id, realname, mobile,shareid from ".tablename('fm453_shopping_member')." where id = ".$user2['shareid']);
					}else{
						$commission3 =0;
					}
				}else{
					$commission2 =0;
				}
				//include $this->template($fm453style.'applyed_detail');
				exit;
			}

	if($_GPC['opp']=='jieyonged'){
				if($_GPC['status']==2){
					if(!is_numeric($_GPC['commission'])||!is_numeric($_GPC['commission2'])||!is_numeric($_GPC['commission3'])){
						message('佣金项请输入合法数字！', '', 'error');
					}
					$shareid = $_GPC['shareid'];
					$ogid = $_GPC['id'];
					$commission = array(
						'uniacid'=>$_W['uniacid'],
						'mid'=>$shareid,
						'ogid'=>$ogid,
						'commission'=>$_GPC['commission'],
						'content'=>trim($_GPC['content']),
						'isshare'=>0,
						'createtime'=>time()
					);
					if($_GPC['commission']>0){
						$temp = pdo_insert('fm453_shopping_commission', $commission);
					}
					$user = pdo_fetch("select id,shareid from ".tablename('fm453_shopping_member')." where id = ".$_GPC['shareid']);
					if(!empty($user['shareid'])){
						$user2 = pdo_fetch("select id from ".tablename('fm453_shopping_member')." where flag=1 and id = ".$user['shareid']);
						if(!empty($user2)){
							if(!empty($_GPC['commission2'])){
								$commission2 = array(
									'uniacid'=>$_W['uniacid'],
									'mid'=>$user['shareid'],
									'ogid'=>$ogid,
									'commission'=>$_GPC['commission2'],
									'content'=>trim($_GPC['content']),
									'isshare'=>1,
									'createtime'=>time()
								);
								if($_GPC['commission2']>0){
									pdo_insert('fm453_shopping_commission', $commission2);
								}
							}
						}
					}
					if(!empty($user2['id'])){
						$nuser2 = pdo_fetch("select shareid from ".tablename('fm453_shopping_member')." where id = ".$user2['id']);
					}
					if(!empty($nuser2['shareid'])){
						$nuser3 = pdo_fetch("select id from ".tablename('fm453_shopping_member')." where flag=1 and id = ".$nuser2['shareid']);
						if(!empty($nuser3)){
							if(!empty($_GPC['commission3'])){
								$commission3 = array(
									'uniacid'=>$_W['uniacid'],
									'mid'=>$nuser2['shareid'],
									'ogid'=>$ogid,
									'commission'=>$_GPC['commission3'],
									'content'=>trim($_GPC['content']),
									'isshare'=>1,
									'createtime'=>time()
								);
								if($_GPC['commission3']>0){
									pdo_insert('fm453_shopping_commission', $commission3);
								}
							}
						}
					}
					if($_GPC['commission']>0&&!empty($shareid)){
						$recharged = array(
							'uniacid'=>$_W['uniacid'],
							'mid'=>$shareid,
							'flag'=>1,
							'content'=>trim($_GPC['content']),
							'commission'=>$_GPC['commission'],
							'createtime'=>time()
						);
						$temp = pdo_insert('fm453_shopping_commission', $recharged);
						if(empty($temp)){
							message('充值失败，请重新充值！', $this->createWebUrl('commission', array('op'=>'applyed', 'opp'=>'jieyong', 'shareid'=>$_GPC['shareid'], 'id'=>$_GPC['id'])), 'error');
						}else{
							$commission = pdo_fetchcolumn("select commission from ".tablename('fm453_shopping_member'). " where id = ".$shareid);
							pdo_update('fm453_shopping_member', array('commission'=>$commission+$_GPC['commission']), array('id'=>$shareid));
						}
					}
					if($_GPC['commission2']>0&&!empty($user['shareid'])){
						$recharged = array(
							'uniacid'=>$_W['uniacid'],
							'mid'=>$user['shareid'],
							'flag'=>1,
							'content'=>trim($_GPC['content']),
							'commission'=>$_GPC['commission2'],
							'createtime'=>time()
						);
						$temp = pdo_insert('fm453_shopping_commission', $recharged);
						if(empty($temp)){
							message('充值失败，请重新充值！', $this->createWebUrl('commission', array('op'=>'applyed', 'opp'=>'jieyong', 'shareid'=>$_GPC['shareid'], 'id'=>$_GPC['id'])), 'error');
						}else{
							$commission = pdo_fetchcolumn("select commission from ".tablename('fm453_shopping_member'). " where id = ".$user['shareid']);
							pdo_update('fm453_shopping_member', array('commission'=>$commission+$_GPC['commission2']), array('id'=>$user['shareid']));
						}
					}
					if($_GPC['commission3']>0&&!empty($nuser2['shareid'])){
						$recharged = array(
							'uniacid'=>$_W['uniacid'],
							'mid'=>$nuser2['shareid'],
							'flag'=>1,
							'content'=>trim($_GPC['content']),
							'commission'=>$_GPC['commission3'],
							'createtime'=>time()
						);
						$temp = pdo_insert('fm453_shopping_commission', $recharged);
						if(empty($temp)){
							message('充值失败，请重新充值！', $this->createWebUrl('commission', array('op'=>'applyed', 'opp'=>'jieyong', 'shareid'=>$_GPC['shareid'], 'id'=>$_GPC['id'])), 'error');
						}else{
							$commission = pdo_fetchcolumn("select commission from ".tablename('fm453_shopping_member'). " where id = ".$nuser2['shareid']);
							pdo_update('fm453_shopping_member', array('commission'=>$commission+$_GPC['commission3']), array('id'=>$nuser2['shareid']));
						}
					}
					message('充值成功！', $this->createWebUrl('commission', array('op'=>'applyed', 'opp'=>'jieyong', 'shareid'=>$_GPC['shareid'], 'id'=>$_GPC['id'])), 'success');
					//if(empty($temp)){
					//		message('充值失败，请重新充值！', $this->createWebUrl('commission', array('op'=>'applyed', 'opp'=>'jieyong', 'shareid'=>$_GPC['shareid'], 'id'=>$_GPC['id'])), 'error');
					//	}else{
					//		message('审核成功！', $this->createWebUrl('commission', array('op'=>'applyed', 'opp'=>'jieyong', 'shareid'=>$_GPC['shareid'], 'id'=>$_GPC['id'])), 'success');
					//	}
				}else{
					$checked = array(
						'status'=>$_GPC['status'],
						'content'=>trim($_GPC['content'])
					);
					$temp = pdo_update('fm453_shopping_order_goods', $checked, array('id'=>$_GPC['id']));
					if(empty($temp)){
						message('提交失败，请重新提交！', $this->createWebUrl('commission', array('op'=>'applyed', 'opp'=>'jieyong', 'shareid'=>$_GPC['shareid'], 'id'=>$_GPC['id'])), 'error');
					}else{
						message('提交成功！', $this->createWebUrl('commission', array('op'=>'applyed')), 'success');
					}
				}
			}
			if($_GPC['opp']=='sort'){
				$sort = array(
					'realname'=>$_GPC['realname'],
					'mobile'=>$_GPC['mobile']
				);
				//$shareid = pdo_fetchall("select id from ".tablename('fm453_shopping_member')." where uniacid = ".$_W['uniacid']." and realname like '%".$sort['realname']."%' and mobile like '%".$sort['mobile']."%'");
				$shareid = "select id from ".tablename('fm453_shopping_member')." where uniacid = ".$_W['uniacid']." and realname like '%".$sort['realname']."%' and mobile like '%".$sort['mobile']."%'";
				$list = pdo_fetchall("select o.shareid, o.status, g.id, g.checktime from ".tablename('fm453_shopping_order'). " as o left join ".tablename('fm453_shopping_order_goods'). " as g on o.id = g.orderid and o.uniacid = g.uniacid where o.uniacid = ".$_W['uniacid']." and g.status = 2 and o.shareid in (".$shareid.") ORDER BY o.id desc");
				$total = sizeof($list);
			}else{
				$pindex = max(1, intval($_GPC['page']));
				$psize = 20;
				$list = pdo_fetchall("select o.shareid, o.status, g.id, g.checktime from ".tablename('fm453_shopping_order')." as o left join ".tablename('fm453_shopping_order_goods')." as g on o.id = g.orderid and o.uniacid = g.uniacid where o.uniacid = ".$_W['uniacid']." and g.status = 2 ORDER BY g.checktime DESC limit ".($pindex - 1) * $psize . ',' . $psize);
				$total = pdo_fetchcolumn("select count(o.id) from ".tablename('fm453_shopping_order'). " as o left join ".tablename('fm453_shopping_order_goods'). " as g on o.id = g.orderid and o.uniacid = g.uniacid where o.uniacid = ".$_W['uniacid']." and g.status = 2");
				$pager = pagination($total, $pindex, $psize);
			}
			include $this->template($fm453style.'applyed');
			exit;
		}

// 审核无效
if($operation=='invalid'){
			if($_GPC['opp']=='delete'){
				$delete = array(
					'status'=>-2
				);
				$temp = pdo_update('fm453_shopping_order_goods', $delete, array('id'=>$_GPC['id']));
				if(empty($temp)){
					message('删除失败，请重新删除！', $this->createWebUrl('commission', array('op'=>'invalid')), 'error');
				}else{
					message('删除成功！', $this->createWebUrl('commission', array('op'=>'invalid')), 'success');
				}
			}
			if($_GPC['opp']=='detail'){
				$shareid = $_GPC['shareid'];
				// 申请人的信息
				$user = pdo_fetch("select realname, mobile from ".tablename('fm453_shopping_member')." where id = ".$_GPC['shareid']);
				// 申请订单信息
				$info = pdo_fetch("select og.id, og.total, og.price, og.status, og.checktime, og.content, g.title from ".tablename('fm453_shopping_order_goods')." as og left join ".tablename('fm453_shopping_goods')." as g on og.goodsid = g.id and og.uniacid = g.uniacid where og.id = ".$_GPC['id']);
				include $this->template($fm453style.'invalid_detail');
				exit;
			}
			if($_GPC['opp']=='invalided'){
				$invalided = array(
					'status'=>$_GPC['status'],
					'content'=>trim($_GPC['content'])
				);
				$temp = pdo_update('fm453_shopping_order_goods', $invalided, array('id'=>$_GPC['id']));
				if(empty($temp)){
					message('提交失败，请重新提交！', $this->createWebUrl('commission', array('op'=>'invalid', 'opp'=>'detail', 'shareid'=>$_GPC['shareid'], 'id'=>$_GPC['id'])), 'error');
				}else{
					message('提交成功！', $this->createWebUrl('commission', array('op'=>'invalid')), 'success');
				}
			}
			if($_GPC['opp']=='sort'){
				$sort = array(
					'realname'=>$_GPC['realname'],
					'mobile'=>$_GPC['mobile']
				);
				//$shareid = pdo_fetchall("select id from ".tablename('fm453_shopping_member')." where uniacid = ".$_W['uniacid']." and realname like '%".$sort['realname']."%' and mobile like '%".$sort['mobile']."%'");
				$shareid = "select id from ".tablename('fm453_shopping_member')." where uniacid = ".$_W['uniacid']." and realname like '%".$sort['realname']."%' and mobile like '%".$sort['mobile']."%'";
				$list = pdo_fetchall("select o.shareid, o.status, g.id, g.checktime from ".tablename('fm453_shopping_order'). " as o left join ".tablename('fm453_shopping_order_goods'). " as g on o.id = g.orderid and o.uniacid = g.uniacid where o.uniacid = ".$_W['uniacid']." and g.status = -1 and o.shareid in (".$shareid.") ORDER BY o.id desc");
				$total = sizeof($list);
			}else{
				$pindex = max(1, intval($_GPC['page']));
				$psize = 20;
				$list = pdo_fetchall("select o.shareid, o.status, g.id, g.checktime from ".tablename('fm453_shopping_order'). " as o left join ".tablename('fm453_shopping_order_goods'). " as g on o.id = g.orderid and o.uniacid = g.uniacid where o.uniacid = ".$_W['uniacid']." and g.status = -1 ORDER BY o.id DESC limit ".($pindex - 1) * $psize . ',' . $psize);
				$pager = pagination($total, $pindex, $psize);
				$total = pdo_fetchcolumn("select count(o.id) from ".tablename('fm453_shopping_order'). " as o left join ".tablename('fm453_shopping_order_goods'). " as g on o.id = g.orderid and o.uniacid = g.uniacid where o.uniacid = ".$_W['uniacid']." and g.status = -1");
			}
			include $this->template($fm453style.'invalid');
		}