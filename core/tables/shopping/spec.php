<?php
$tables['spec']=array(
	'name'=>'产品规格名称',
	'type'=>array("goods","system","all")
);
$tables['spec']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_shopping_spec')." (`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`)) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;";

$tables['spec']['columns']['id']           ="ALTER TABLE ".tablename('fm453_shopping_spec')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['spec']['columns']['uniacid']      ="ALTER TABLE ".tablename('fm453_shopping_spec')." ADD "."`uniacid` int(11) UNSIGNED NOT NULL COMMENT '所属公众账号'".";";
$tables['spec']['columns']['title']        ="ALTER TABLE ".tablename('fm453_shopping_spec')." ADD "."`title` varchar(50) NOT NULL".";";
$tables['spec']['columns']['description']  ="ALTER TABLE ".tablename('fm453_shopping_spec')." ADD "."`description` varchar(1000) NOT NULL".";";
$tables['spec']['columns']['displaytype']  ="ALTER TABLE ".tablename('fm453_shopping_spec')." ADD "."`displaytype` tinyint(3) UNSIGNED NOT NULL".";";
$tables['spec']['columns']['content']      ="ALTER TABLE ".tablename('fm453_shopping_spec')." ADD "."`content` text NOT NULL".";";
$tables['spec']['columns']['goodsid']      ="ALTER TABLE ".tablename('fm453_shopping_spec')." ADD "."`goodsid` int(11) DEFAULT '0'".";";
$tables['spec']['columns']['displayorder'] ="ALTER TABLE ".tablename('fm453_shopping_spec')." ADD "."`displayorder` int(11) DEFAULT '0'".";";

$tables['spec']['indexes']['id']="`id` (`id`)";