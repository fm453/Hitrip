<?php
$tables['express']=array(
	'name'=>'快递公司',
	'type'=>array("system","shopping","all")
);
$tables['express']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_shopping_express')." (`id` int(11) NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`)) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;";

$tables['express']['columns']['id']            ="ALTER TABLE ".tablename('fm453_shopping_express')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['express']['columns']['uniacid']       ="ALTER TABLE ".tablename('fm453_shopping_express')." ADD "."`uniacid` int(11) DEFAULT '0' COMMENT '所属公众账号'".";";
$tables['express']['columns']['brand']         ="ALTER TABLE ".tablename('fm453_shopping_express')." ADD "."`brand` varchar(50) DEFAULT ''".";";
$tables['express']['columns']['express_name']  ="ALTER TABLE ".tablename('fm453_shopping_express')." ADD "."`express_name` varchar(50) DEFAULT ''".";";
$tables['express']['columns']['displayorder']  ="ALTER TABLE ".tablename('fm453_shopping_express')." ADD "."`displayorder` int(11) DEFAULT '0'".";";
$tables['express']['columns']['express_price'] ="ALTER TABLE ".tablename('fm453_shopping_express')." ADD "."`express_price` varchar(10) DEFAULT ''".";";
$tables['express']['columns']['express_area']  ="ALTER TABLE ".tablename('fm453_shopping_express')." ADD "."`express_area` varchar(100) DEFAULT ''".";";
$tables['express']['columns']['express_url']   ="ALTER TABLE ".tablename('fm453_shopping_express')." ADD "."`express_url` varchar(255) DEFAULT ''".";";
$tables['express']['columns']['deleted']       ="ALTER TABLE ".tablename('fm453_shopping_express')." ADD "."`deleted` tinyint(3) NOT NULL COMMENT '是否删除，0否1是'".";";

$tables['express']['indexes']['id']="`id` (`id`)";