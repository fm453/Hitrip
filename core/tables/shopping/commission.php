<?php
$tables['commission']=array(
	'name'=>'分销佣金记录',
	'type'=>array("member","system","all")
);
$tables['commission']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_shopping_commission')." ( `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

$tables['commission']['columns']['id']         ="ALTER TABLE ".tablename('fm453_shopping_commission')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['commission']['columns']['uniacid']    ="ALTER TABLE ".tablename('fm453_shopping_commission')." ADD "."`uniacid` int(11) UNSIGNED NOT NULL COMMENT '所属公众帐号'".";";
$tables['commission']['columns']['mid']        ="ALTER TABLE ".tablename('fm453_shopping_commission')." ADD "."`mid` int(10) UNSIGNED NOT NULL COMMENT '粉丝ID'".";";
$tables['commission']['columns']['ogid']       ="ALTER TABLE ".tablename('fm453_shopping_commission')." ADD "."`ogid` int(10) UNSIGNED DEFAULT NULL COMMENT '订单商品ID'".";";
$tables['commission']['columns']['commission'] ="ALTER TABLE ".tablename('fm453_shopping_commission')." ADD "."`commission` decimal(10,2) UNSIGNED NOT NULL COMMENT '佣金'".";";
$tables['commission']['columns']['content']    ="ALTER TABLE ".tablename('fm453_shopping_commission')." ADD "."`content` text".";";
$tables['commission']['columns']['flag']       ="ALTER TABLE ".tablename('fm453_shopping_commission')." ADD "."`flag` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0为账户充值记录，1为提现记录'".";";
$tables['commission']['columns']['isout']      ="ALTER TABLE ".tablename('fm453_shopping_commission')." ADD "."`isout` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0为未导出，1为已导出'".";";
$tables['commission']['columns']['isshare']    ="ALTER TABLE ".tablename('fm453_shopping_commission')." ADD "."`isshare` int(11) DEFAULT NULL".";";
$tables['commission']['columns']['createtime'] ="ALTER TABLE ".tablename('fm453_shopping_commission')." ADD "."`createtime` int(10) NOT NULL".";";

$tables['commission']['indexes']['id']="`id` (`id`)";