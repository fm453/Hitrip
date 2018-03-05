<?php
$tables['goods_option']=array(
	'name'=>'产品规格具体项的值',
	'type'=>array("goods","system","all")
);
$tables['goods_option']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_shopping_goods_option')." (`id` int(11) NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`)) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;";

$tables['goods_option']['columns']['id']             ="ALTER TABLE ".tablename('fm453_shopping_goods_option')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['goods_option']['columns']['goodsid']        ="ALTER TABLE ".tablename('fm453_shopping_goods_option')." ADD "."`goodsid` int(10) DEFAULT '0'".";";
$tables['goods_option']['columns']['title']          ="ALTER TABLE ".tablename('fm453_shopping_goods_option')." ADD "."`title` varchar(50) DEFAULT ''".";";
$tables['goods_option']['columns']['thumb']          ="ALTER TABLE ".tablename('fm453_shopping_goods_option')." ADD "."`thumb` varchar(60) DEFAULT ''".";";
$tables['goods_option']['columns']['cankaoprice']    ="ALTER TABLE ".tablename('fm453_shopping_goods_option')." ADD "."`cankaoprice` decimal(10,2) DEFAULT '0.00'".";";
$tables['goods_option']['columns']['marketprice']    ="ALTER TABLE ".tablename('fm453_shopping_goods_option')." ADD "."`marketprice` decimal(10,2) DEFAULT '0.00'".";";
$tables['goods_option']['columns']['costprice']      ="ALTER TABLE ".tablename('fm453_shopping_goods_option')." ADD "."`costprice` decimal(10,2) DEFAULT '0.00'".";";
$tables['goods_option']['columns']['agentprice']     ="ALTER TABLE ".tablename('fm453_shopping_goods_option')." ADD "."`agentprice` decimal(10,2) NOT NULL DEFAULT '0.00'".";";
$tables['goods_option']['columns']['agentsaleprice'] ="ALTER TABLE ".tablename('fm453_shopping_goods_option')." ADD "."`agentsaleprice` decimal(10,2) NOT NULL DEFAULT '0.00'".";";
$tables['goods_option']['columns']['stock']          ="ALTER TABLE ".tablename('fm453_shopping_goods_option')." ADD "."`stock` int(11) DEFAULT '0'".";";
$tables['goods_option']['columns']['weight']         ="ALTER TABLE ".tablename('fm453_shopping_goods_option')." ADD "."`weight` decimal(10,2) DEFAULT '0.00'".";";
$tables['goods_option']['columns']['displayorder']   ="ALTER TABLE ".tablename('fm453_shopping_goods_option')." ADD "."`displayorder` int(11) DEFAULT '0'".";";
$tables['goods_option']['columns']['specs']          ="ALTER TABLE ".tablename('fm453_shopping_goods_option')." ADD "."`specs` text".";";

$tables['goods_option']['indexes']['id']="`id` (`id`)";
