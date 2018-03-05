<?php
$tables['product']=array(
	'name'=>'积分商城虚拟产品',
	'type'=>array("goods","system","all")
);
$tables['product']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_shopping_product')." (`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

$tables['product']['columns']['id']           ="ALTER TABLE ".tablename('fm453_shopping_product')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['product']['columns']['goodsid']      ="ALTER TABLE ".tablename('fm453_shopping_product')." ADD "."`goodsid` int(11) NOT NULL".";";
$tables['product']['columns']['productsn']    ="ALTER TABLE ".tablename('fm453_shopping_product')." ADD "."`productsn` varchar(50) NOT NULL".";";
$tables['product']['columns']['title']        ="ALTER TABLE ".tablename('fm453_shopping_product')." ADD "."`title` varchar(1000) NOT NULL".";";
$tables['product']['columns']['marketprice']  ="ALTER TABLE ".tablename('fm453_shopping_product')." ADD "."`marketprice` decimal(10,0) UNSIGNED NOT NULL".";";
$tables['product']['columns']['productprice'] ="ALTER TABLE ".tablename('fm453_shopping_product')." ADD "."`productprice` decimal(10,0) UNSIGNED NOT NULL".";";
$tables['product']['columns']['total']        ="ALTER TABLE ".tablename('fm453_shopping_product')." ADD "."`total` int(11) NOT NULL".";";
$tables['product']['columns']['status']       ="ALTER TABLE ".tablename('fm453_shopping_product')." ADD "."`status` tinyint(3) UNSIGNED NOT NULL DEFAULT '1'".";";
$tables['product']['columns']['spec']         ="ALTER TABLE ".tablename('fm453_shopping_product')." ADD "."`spec` varchar(5000) NOT NULL".";";

$tables['product']['indexes']['id']="`id` (`id`)";