<?php
$tables['needs_order']=array(
	'name'=>'有求必应需求单',
	'type'=>array("needs","system","all")
);
$tables['needs_order']['sql']="CREATE TABLE IF NOT EXISTS `ims_fm453_shopping_needs_order` (`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`)) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;";

$tables['needs_order']['columns']['id']         ="ALTER TABLE ".tablename('fm453_shopping_needs_order')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['needs_order']['columns']['uniacid']    ="ALTER TABLE ".tablename('fm453_shopping_needs_order')." ADD "."`uniacid` int(11) UNSIGNED NOT NULL COMMENT '所属公众账号'".";";
$tables['needs_order']['columns']['nid']        ="ALTER TABLE ".tablename('fm453_shopping_needs_order')." ADD "."`nid` int(11) UNSIGNED NOT NULL COMMENT '关联表单ID'".";";
$tables['needs_order']['columns']['from_user']  ="ALTER TABLE ".tablename('fm453_shopping_needs_order')." ADD "."`from_user` varchar(50) NOT NULL".";";
$tables['needs_order']['columns']['fromuid']    ="ALTER TABLE ".tablename('fm453_shopping_needs_order')." ADD "."`fromuid` int(10) NOT NULL COMMENT '粉丝的Uid'".";";
$tables['needs_order']['columns']['shareid']    ="ALTER TABLE ".tablename('fm453_shopping_needs_order')." ADD "."`shareid` int(10) UNSIGNED DEFAULT '0' COMMENT '推荐人ID'".";";
$tables['needs_order']['columns']['ordersn']    ="ALTER TABLE ".tablename('fm453_shopping_needs_order')." ADD "."`ordersn` varchar(32) NOT NULL COMMENT '订单编号sn'".";";
$tables['needs_order']['columns']['price']      ="ALTER TABLE ".tablename('fm453_shopping_needs_order')." ADD "."`price` varchar(10) NOT NULL".";";
$tables['needs_order']['columns']['status']     ="ALTER TABLE ".tablename('fm453_shopping_needs_order')." ADD "."`status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '-1取消状态，0普通状态，1为已付款，2为处理中，3为成功'".";";

$tables['needs_order']['columns']['paytype']    ="ALTER TABLE ".tablename('fm453_shopping_needs_order')." ADD "."`paytype` tinyint(1) UNSIGNED NOT NULL COMMENT '1为余额，2为在线，3为到付'".";";
$tables['needs_order']['columns']['transid']    ="ALTER TABLE ".tablename('fm453_shopping_needs_order')." ADD "."`transid` varchar(30) NOT NULL DEFAULT '0' COMMENT '微信支付单号'".";";
$tables['needs_order']['columns']['remark']     ="ALTER TABLE ".tablename('fm453_shopping_needs_order')." ADD "."`remark` varchar(1000) NOT NULL DEFAULT ''".";";
$tables['needs_order']['columns']['logs']       ="ALTER TABLE ".tablename('fm453_shopping_needs_order')." ADD "."`logs` text NOT NULL COMMENT '订单的操作日志，如谁于何时作何操作等。'".";";
$tables['needs_order']['columns']['createtime'] ="ALTER TABLE ".tablename('fm453_shopping_needs_order')." ADD "."`createtime` int(10) UNSIGNED NOT NULL".";";
$tables['needs_order']['columns']['remark_kf']  ="ALTER TABLE ".tablename('fm453_shopping_needs_order')." ADD "."`remark_kf` text COMMENT '客服操作备注'".";";
$tables['needs_order']['columns']['reply']      ="ALTER TABLE ".tablename('fm453_shopping_needs_order')." ADD "."`reply` text COMMENT '平台回复'".";";
$tables['needs_order']['columns']['deleted']    ="ALTER TABLE ".tablename('fm453_shopping_needs_order')." ADD "."`deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0为保留，-1为删除'".";";

$tables['needs_order']['indexes']['id']      ="`id` (`id`)";
$tables['needs_order']['indexes']['uniacid'] ="`uniacid` (`uniacid`)";
$tables['needs_order']['indexes']['fromuid'] ="`fromuid` (`fromuid`)";
$tables['needs_order']['indexes']['shareid'] ="`shareid` (`shareid`)";
$tables['needs_order']['indexes']['ordersn'] ="`ordersn` (`ordersn`)";
