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
 * @remark 分销排行榜
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;
fm_load()->fm_func('share');//链接分享管理
fm_load()->fm_func('view'); 	//浏览量处理
fm_load()->fm_func('wechat');//微信定义管理

$appstyle =$this->appstyle();
$fm453resource =FM_RESOURCE;
$appsrc =fmFunc_ui_appsrc();
$pagename = "";//定义页面标题
$shopname=$settings['brands']['shopname'];
$uniacid=$_W['uniacid'];
$op = $_GPC['op']?$_GPC['op']:'display';
$month = date('m', strtotime("-0 month"));
//上一个月第一天的时间戳
$premonth = strtotime(date('Y-m-1 00:00:00', strtotime("-0 month")));
$temptime = date('Y-m-1 00:00:00', strtotime("-0 month"));
//上一个月最后一天的时间戳
$premonthed = strtotime(date('Y-m-d 23:59:59', strtotime("$temptime +1 month -1 day")));

$pindex = max(1, intval($_GPC['page']));
$psize = 15;

$commission = pdo_fetchall("select sum(c.commission) as commission, m.realname, m.mobile from ".tablename('fm453_shopping_commission')." as c left join ".tablename('fm453_shopping_member')." as m on c.uniacid = m.uniacid and c.mid = m.id where c.flag = 0 and m.realname !='' and c.uniacid = ".$_W['uniacid']." and c.createtime >= ".$premonth." and c.createtime <= ".$premonthed." group by c.mid order by sum(c.commission) desc, c.createtime desc LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
$total = pdo_fetchcolumn("select count(distinct c.mid) from ".tablename('fm453_shopping_commission')." as c left join ".tablename('fm453_shopping_member')." as m on c.uniacid = m.uniacid and c.mid = m.id where c.flag = 0 and c.uniacid = ".$_W['uniacid']." and m.realname !='' and c.createtime >= ".$premonth." and c.createtime <= ".$premonthed);
$pager = pagination($total, $pindex, $psize);

//include $this->template($appstyle.'phb');
include $this->template($appstyle.$do.'/453');
