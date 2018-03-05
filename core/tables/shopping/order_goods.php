<?php
$tables['order_goods']=array(
	'name'=>'商城订单产品记录',
	'type'=>array("goods","order","system","all")
);
$tables['order_goods']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_shopping_order_goods')." (`id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;";

$tables['order_goods']['columns']['id']          ="ALTER TABLE ".tablename('fm453_shopping_order_goods')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['order_goods']['columns']['uniacid']     ="ALTER TABLE ".tablename('fm453_shopping_order_goods')." ADD "."`uniacid` int(11) UNSIGNED NOT NULL COMMENT '所属公众账号'".";";
$tables['order_goods']['columns']['orderid']     ="ALTER TABLE ".tablename('fm453_shopping_order_goods')." ADD "."`orderid` int(10) UNSIGNED NOT NULL".";";
$tables['order_goods']['columns']['ordersn']     ="ALTER TABLE ".tablename('fm453_shopping_order_goods')." ADD "."`ordersn` varchar(32) NOT NULL COMMENT '关联订单编号sn'".";";
$tables['order_goods']['columns']['goodsid']     ="ALTER TABLE ".tablename('fm453_shopping_order_goods')." ADD "."`goodsid` int(10) UNSIGNED NOT NULL".";";
$tables['order_goods']['columns']['articleid']   ="ALTER TABLE ".tablename('fm453_shopping_order_goods')." ADD "."`articleid` int(10) UNSIGNED NOT NULL".";";
$tables['order_goods']['columns']['quickid']     ="ALTER TABLE ".tablename('fm453_shopping_order_goods')." ADD "."`quickid` int(10) UNSIGNED NOT NULL".";";
$tables['order_goods']['columns']['commission']  ="ALTER TABLE ".tablename('fm453_shopping_order_goods')." ADD "."`commission` decimal(10,2) UNSIGNED DEFAULT '0.00' COMMENT '该订单的推荐佣金'".";";
$tables['order_goods']['columns']['commission2'] ="ALTER TABLE ".tablename('fm453_shopping_order_goods')." ADD "."`commission2` decimal(10,2) UNSIGNED DEFAULT '0.00'".";";
$tables['order_goods']['columns']['commission3'] ="ALTER TABLE ".tablename('fm453_shopping_order_goods')." ADD "."`commission3` decimal(10,2) UNSIGNED DEFAULT '0.00'".";";
$tables['order_goods']['columns']['applytime']   ="ALTER TABLE ".tablename('fm453_shopping_order_goods')." ADD "."`applytime` int(10) UNSIGNED DEFAULT NULL COMMENT '申请时间'".";";
$tables['order_goods']['columns']['checktime']   ="ALTER TABLE ".tablename('fm453_shopping_order_goods')." ADD "."`checktime` int(10) UNSIGNED DEFAULT NULL COMMENT '审核时间'".";";
$tables['order_goods']['columns']['status']      ="ALTER TABLE ".tablename('fm453_shopping_order_goods')." ADD "."`status` tinyint(3) DEFAULT '0' COMMENT '申请状态，-2为标志删除，-1为审核无效，0为未申请，1为正在申请，2为审核通过'".";";
$tables['order_goods']['columns']['content']     ="ALTER TABLE ".tablename('fm453_shopping_order_goods')." ADD "."`content` text CHARACTER SET utf8".";";
$tables['order_goods']['columns']['price']       ="ALTER TABLE ".tablename('fm453_shopping_order_goods')." ADD "."`price` decimal(10,2) DEFAULT '0.00'".";";
$tables['order_goods']['columns']['total']       ="ALTER TABLE ".tablename('fm453_shopping_order_goods')." ADD "."`total` int(10) UNSIGNED NOT NULL DEFAULT '1'".";";
$tables['order_goods']['columns']['optionid']    ="ALTER TABLE ".tablename('fm453_shopping_order_goods')." ADD "."`optionid` int(10) DEFAULT '0'".";";
$tables['order_goods']['columns']['createtime']  ="ALTER TABLE ".tablename('fm453_shopping_order_goods')." ADD "."`createtime` int(10) UNSIGNED NOT NULL".";";
$tables['order_goods']['columns']['optionname']  ="ALTER TABLE ".tablename('fm453_shopping_order_goods')." ADD "."`optionname` text".";";
$tables['order_goods']['columns']['aboutinfos']  ="ALTER TABLE ".tablename('fm453_shopping_order_goods')." ADD "."`aboutinfos` text NOT NULL COMMENT '关于订单的更多信息，根据产品属性做不同记录（关于入住日期、人数、行程等之类的）'".";";

$tables['order_goods']['indexes']['id']        ="`id` (`id`)";
$tables['order_goods']['indexes']['uniaciid']  ="`uniacid` (`uniacid`)";
$tables['order_goods']['indexes']['orderid']   ="`orderid` (`orderid`)";
$tables['order_goods']['indexes']['goodsid']   ="`goodsid` (`goodsid`)";
$tables['order_goods']['indexes']['articleid'] ="`articleid` (`articleid`)";
$tables['order_goods']['indexes']['quickid']   ="`quickid` (`quickid`)";
$tables['order_goods']['indexes']['optionid']  ="`optionid` (`optionid`)";