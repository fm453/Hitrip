<?php
$tables['goods_label']=array(
	'name'=>'产品标签',
	'type'=>array("goods","system","all")
);
$tables['goods_label']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_shopping_goods_label')." (`id` int(11) NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`)) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;";

$tables['goods_label']['columns']['id']           ="ALTER TABLE ".tablename('fm453_shopping_goods_label')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['goods_label']['columns']['goodsid']      ="ALTER TABLE ".tablename('fm453_shopping_goods_label')." ADD "."`goodsid` int(11) DEFAULT NULL".";";
$tables['goods_label']['columns']['title']        ="ALTER TABLE ".tablename('fm453_shopping_goods_label')." ADD "."`title` varchar(50) DEFAULT NULL".";";
$tables['goods_label']['columns']['value']        ="ALTER TABLE ".tablename('fm453_shopping_goods_label')." ADD "."`value` text".";";
$tables['goods_label']['columns']['displayorder'] ="ALTER TABLE ".tablename('fm453_shopping_goods_label')." ADD "."`displayorder` int(11) DEFAULT '0'".";";

$tables['goods_label']['indexes']['id']="`id` (`id`)";
