<?php
$tables['goods_param']=array(
	'name'=>'产品自定义参数',
	'type'=>array("goods","system","all")
);
$tables['goods_param']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_shopping_goods_param')." (`id` int(11) NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`)) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;";

$tables['goods_param']['columns']['id']           ="ALTER TABLE ".tablename('fm453_shopping_goods_param')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['goods_param']['columns']['goodsid']      ="ALTER TABLE ".tablename('fm453_shopping_goods_param')." ADD "."`goodsid` int(10) DEFAULT '0'".";";
$tables['goods_param']['columns']['title']        ="ALTER TABLE ".tablename('fm453_shopping_goods_param')." ADD "."`title` varchar(50) DEFAULT ''".";";
$tables['goods_param']['columns']['value']        ="ALTER TABLE ".tablename('fm453_shopping_goods_param')." ADD "."`value` text".";";
$tables['goods_param']['columns']['displayorder'] ="ALTER TABLE ".tablename('fm453_shopping_goods_param')." ADD "."`displayorder` int(11) DEFAULT '0'".";";

$tables['goods_param']['indexes']['id']="`id` (`id`)";