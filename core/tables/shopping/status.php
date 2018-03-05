<?php
$tables['status']=array(
	'name'=>'商城数据表各种状态规范说明',
	'type'=>array("system","all")
);
$tables['status']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_shopping_status')." (`id` int(11) NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`)) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT='状态说明表(仅供开发时参考用)';";

$tables['status']['columns']['id']         ="ALTER TABLE ".tablename('fm453_shopping_status')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['status']['columns']['sn']         ="ALTER TABLE ".tablename('fm453_shopping_status')." ADD "."`sn` tinyint(3) NOT NULL".";";
$tables['status']['columns']['showname']   ="ALTER TABLE ".tablename('fm453_shopping_status')." ADD "."`showname` varchar(50) NOT NULL COMMENT '状态名'".";";
$tables['status']['columns']['des']        ="ALTER TABLE ".tablename('fm453_shopping_status')." ADD "."`des` varchar(255) NOT NULL".";";
$tables['status']['columns']['statuscode'] ="ALTER TABLE ".tablename('fm453_shopping_status')." ADD "."`statuscode` tinyint(3) NOT NULL".";";
$tables['status']['columns']['setfor']     ="ALTER TABLE ".tablename('fm453_shopping_status')." ADD "."`setfor` varchar(50) NOT NULL".";";
$tables['status']['columns']['workflow']   ="ALTER TABLE ".tablename('fm453_shopping_status')." ADD "."`workflow` int(11) NOT NULL COMMENT '工作顺序'".";";

$tables['status']['indexes']['id']="`id` (`id`)";
