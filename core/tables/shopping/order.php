<?php
$tables['order']=array(
	'name'=>'商城订单',
	'type'=>array("order","system","all")
);
$tables['order']['sql']="CREATE TABLE IF NOT EXISTS `ims_fm453_shopping_order` (`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`)) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;";

$tables['order']['columns']['id']            ="ALTER TABLE ".tablename('fm453_shopping_order')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['order']['columns']['uniacid']       ="ALTER TABLE ".tablename('fm453_shopping_order')." ADD "."`uniacid` int(11) UNSIGNED NOT NULL COMMENT '所属公众账号'".";";
$tables['order']['columns']['from_user']     ="ALTER TABLE ".tablename('fm453_shopping_order')." ADD "."`from_user` varchar(50) NOT NULL".";";
$tables['order']['columns']['fromuid']       ="ALTER TABLE ".tablename('fm453_shopping_order')." ADD "."`fromuid` int(10) NOT NULL COMMENT '粉丝的Uid'".";";
$tables['order']['columns']['shareid']       ="ALTER TABLE ".tablename('fm453_shopping_order')." ADD "."`shareid` int(10) UNSIGNED DEFAULT '0' COMMENT '推荐人ID'".";";
$tables['order']['columns']['ordersn']       ="ALTER TABLE ".tablename('fm453_shopping_order')." ADD "."`ordersn` varchar(20) NOT NULL COMMENT '订单编号sn'".";";
$tables['order']['columns']['price']         ="ALTER TABLE ".tablename('fm453_shopping_order')." ADD "."`price` varchar(10) NOT NULL".";";
$tables['order']['columns']['status']        ="ALTER TABLE ".tablename('fm453_shopping_order')." ADD "."`status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '-1取消状态，0普通状态，1为已付款，2为已发货，3为成功'".";";
$tables['order']['columns']['sendtype']      ="ALTER TABLE ".tablename('fm453_shopping_order')." ADD "."`sendtype` tinyint(1) UNSIGNED NOT NULL COMMENT '1为快递，2为自提'".";";
$tables['order']['columns']['paytype']       ="ALTER TABLE ".tablename('fm453_shopping_order')." ADD "."`paytype` tinyint(1) UNSIGNED NOT NULL COMMENT '1为余额，2为在线，3为到付'".";";
$tables['order']['columns']['transid']       ="ALTER TABLE ".tablename('fm453_shopping_order')." ADD "."`transid` varchar(30) NOT NULL DEFAULT '0' COMMENT '微信支付单号'".";";
$tables['order']['columns']['goodstype']     ="ALTER TABLE ".tablename('fm453_shopping_order')." ADD "."`goodstype` tinyint(1) UNSIGNED NOT NULL DEFAULT '1'".";";
$tables['order']['columns']['ordertype']     ="ALTER TABLE ".tablename('fm453_shopping_order')." ADD "."`ordertype` varchar(30) NOT NULL DEFAULT 'biaozhun' COMMENT '订单类型(标准、文章、团购、抽奖等)'".";";
$tables['order']['columns']['marketingid']   ="ALTER TABLE ".tablename('fm453_shopping_order')." ADD "."`marketingid` int(10) UNSIGNED NOT NULL".";";
$tables['order']['columns']['remark']        ="ALTER TABLE ".tablename('fm453_shopping_order')." ADD "."`remark` varchar(1000) NOT NULL DEFAULT ''".";";
$tables['order']['columns']['addressid']     ="ALTER TABLE ".tablename('fm453_shopping_order')." ADD "."`addressid` int(10) UNSIGNED NOT NULL".";";
$tables['order']['columns']['contactinfo']   ="ALTER TABLE ".tablename('fm453_shopping_order')." ADD "."`contactinfo` text NOT NULL COMMENT '订单关联的联系信息，包括联系人、联系电话、联系地址），默认从地址表中获取；独立保存'".";";
$tables['order']['columns']['aboutinfos']    ="ALTER TABLE ".tablename('fm453_shopping_order')." ADD "."`aboutinfos` text NOT NULL COMMENT '关于订单的更多信息，根据产品属性做不同记录（关于入住日期、人数、行程等之类的）'".";";
$tables['order']['columns']['logs']          ="ALTER TABLE ".tablename('fm453_shopping_order')." ADD "."`logs` text NOT NULL COMMENT '订单的操作日志，如谁于何时作何操作等。'".";";
$tables['order']['columns']['sharedfrom']    ="ALTER TABLE ".tablename('fm453_shopping_order')." ADD "."`sharedfrom` int(11) NOT NULL COMMENT '订单来源（关联系统统一公号）'".";";
$tables['order']['columns']['expresscom']    ="ALTER TABLE ".tablename('fm453_shopping_order')." ADD "."`expresscom` varchar(30) NOT NULL DEFAULT ''".";";
$tables['order']['columns']['expresssn']     ="ALTER TABLE ".tablename('fm453_shopping_order')." ADD "."`expresssn` varchar(50) NOT NULL DEFAULT ''".";";
$tables['order']['columns']['express']       ="ALTER TABLE ".tablename('fm453_shopping_order')." ADD "."`express` varchar(200) NOT NULL DEFAULT ''".";";
$tables['order']['columns']['goodsprice']    ="ALTER TABLE ".tablename('fm453_shopping_order')." ADD "."`goodsprice` decimal(10,2) DEFAULT '0.00'".";";
$tables['order']['columns']['dispatchprice'] ="ALTER TABLE ".tablename('fm453_shopping_order')." ADD "."`dispatchprice` decimal(10,2) DEFAULT '0.00'".";";
$tables['order']['columns']['dispatch']      ="ALTER TABLE ".tablename('fm453_shopping_order')." ADD "."`dispatch` int(10) DEFAULT '0'".";";
$tables['order']['columns']['paydetail']     ="ALTER TABLE ".tablename('fm453_shopping_order')." ADD "."`paydetail` varchar(255) NOT NULL COMMENT '支付详情'".";";
$tables['order']['columns']['createtime']    ="ALTER TABLE ".tablename('fm453_shopping_order')." ADD "."`createtime` int(10) UNSIGNED NOT NULL".";";
$tables['order']['columns']['remark_kf']     ="ALTER TABLE ".tablename('fm453_shopping_order')." ADD "."`remark_kf` text COMMENT '客服操作备注'".";";
$tables['order']['columns']['deleted']       ="ALTER TABLE ".tablename('fm453_shopping_order')." ADD "."`deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0为保留，-1为删除'".";";

$tables['order']['indexes']['id']          ="`id` (`id`)";
$tables['order']['indexes']['uniaciid']    ="`uniaciid` (`uniaciid`)";
$tables['order']['indexes']['marketingid'] ="`marketingid` (`marketingid`)";
$tables['order']['indexes']['fromuid']     ="`fromuid` (`fromuid`)";
$tables['order']['indexes']['shareid']     ="`shareid` (`shareid`)";
