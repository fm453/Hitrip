<?php
$tables['address']=array(
	'name'=>'商城会员地址表',
	'type'=>array("member","system","all")
);
$tables['address']['sql']                  ="CREATE TABLE IF NOT EXISTS ".tablename('fm453_shopping_address')." (`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`)) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT='会员收货地址表';";

$tables['address']['columns']['id']        ="ALTER TABLE ".tablename('fm453_shopping_address')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['address']['columns']['uniacid']   ="ALTER TABLE ".tablename('fm453_shopping_address')." ADD "."`uniacid` int(11) UNSIGNED NOT NULL COMMENT '所属公共账号'".";";
$tables['address']['columns']['openid']    ="ALTER TABLE ".tablename('fm453_shopping_address')." ADD "."`openid` varchar(50) NOT NULL".";";
$tables['address']['columns']['realname']  ="ALTER TABLE ".tablename('fm453_shopping_address')." ADD "."`realname` varchar(20) NOT NULL".";";
$tables['address']['columns']['mobile']    ="ALTER TABLE ".tablename('fm453_shopping_address')." ADD "."`mobile` varchar(11) NOT NULL".";";
$tables['address']['columns']['zipcode']   ="ALTER TABLE ".tablename('fm453_shopping_address')." ADD "."`zipcode` varchar(6) NOT NULL COMMENT '邮政编码'".";";
$tables['address']['columns']['province']  ="ALTER TABLE ".tablename('fm453_shopping_address')." ADD "."`province` varchar(30) NOT NULL".";";
$tables['address']['columns']['city']      ="ALTER TABLE ".tablename('fm453_shopping_address')." ADD "."`city` varchar(30) NOT NULL".";";
$tables['address']['columns']['area']      ="ALTER TABLE ".tablename('fm453_shopping_address')." ADD "."`area` varchar(30) NOT NULL".";";
$tables['address']['columns']['address']   ="ALTER TABLE ".tablename('fm453_shopping_address')." ADD "."`address` varchar(300) NOT NULL".";";
$tables['address']['columns']['isdefault'] ="ALTER TABLE ".tablename('fm453_shopping_address')." ADD "."`isdefault` tinyint(3) UNSIGNED NOT NULL DEFAULT '0'".";";
$tables['address']['columns']['deleted']   ="ALTER TABLE ".tablename('fm453_shopping_address')." ADD "."`deleted` tinyint(3) UNSIGNED NOT NULL DEFAULT '0'".";";

$tables['address']['indexes']['id']="`id` (`id`)";
