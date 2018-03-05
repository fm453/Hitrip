<?php
$tables['goods_buylink']=array(
	'name'=>'产品购买跳转链接',
	'type'=>array("goods","system","all")
);
$tables['goods_buylink']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_shopping_goods_buylink')." (`id` int(11) NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`)) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;";

$tables['goods_buylink']['columns']['id']          ="ALTER TABLE ".tablename('fm453_shopping_goods_buylink')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['goods_buylink']['columns']['gid']         ="ALTER TABLE ".tablename('fm453_shopping_goods_buylink')." ADD "."`gid` int(10) DEFAULT NULL".";";
$tables['goods_buylink']['columns']['marketmodel'] ="ALTER TABLE ".tablename('fm453_shopping_goods_buylink')." ADD "."`marketmodel` varchar(50) DEFAULT NULL".";";
$tables['goods_buylink']['columns']['linkurl']     ="ALTER TABLE ".tablename('fm453_shopping_goods_buylink')." ADD "."`linkurl` text".";";
$tables['goods_buylink']['columns']['isused']      ="ALTER TABLE ".tablename('fm453_shopping_goods_buylink')." ADD "."`isused` int(11) DEFAULT '0'".";";

$tables['goods_buylink']['indexes']['id']="`id` (`id`)";