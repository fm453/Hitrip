<?php
$tables['rule']=array(
	'name'=>'商城规则表，如计算公式、促销条件等',
	'type'=>array("goods","system","all")
);
$tables['rule']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_shopping_rule')." (`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

$tables['rule']['columns']['id']         ="ALTER TABLE ".tablename('fm453_shopping_rule')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['rule']['columns']['uniacid']    ="ALTER TABLE ".tablename('fm453_shopping_rule')." ADD "."`uniacid` int(11) NOT NULL COMMENT '所属公众账号'".";";
$tables['rule']['columns']['status']     ="ALTER TABLE ".tablename('fm453_shopping_rule')." ADD "."`status` varchar(255) NOT NULL DEFAULT ''".";";
$tables['rule']['columns']['rule']       ="ALTER TABLE ".tablename('fm453_shopping_rule')." ADD "."`rule` text".";";
$tables['rule']['columns']['terms']      ="ALTER TABLE ".tablename('fm453_shopping_rule')." ADD "."`terms` text".";";
$tables['rule']['columns']['gzurl']      ="ALTER TABLE ".tablename('fm453_shopping_rule')." ADD "."`gzurl` varchar(255) NOT NULL".";";
$tables['rule']['columns']['teamfy']     ="ALTER TABLE ".tablename('fm453_shopping_rule')." ADD "."`teamfy` int(10) UNSIGNED NOT NULL DEFAULT '0'".";";
$tables['rule']['columns']['createtime'] ="ALTER TABLE ".tablename('fm453_shopping_rule')." ADD "."`createtime` int(11) NOT NULL".";";

$tables['rule']['indexes']['id']="`id` (`id`)";
