<?php
$tables['rules']=array(
	'name'=>'商城分销规则说明(前端介绍用)',
	'type'=>array("system","all")
);
$tables['rules']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_shopping_rules')." (`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`)) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT='商城规则说明(对外介绍用)';";

$tables['rules']['columns']['id']            ="ALTER TABLE ".tablename('fm453_shopping_rules')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['rules']['columns']['uniacid']       ="ALTER TABLE ".tablename('fm453_shopping_rules')." ADD "."`uniacid` int(11) NOT NULL COMMENT '所属公众账号'".";";
$tables['rules']['columns']['rule']          ="ALTER TABLE ".tablename('fm453_shopping_rules')." ADD "."`rule` text".";";
$tables['rules']['columns']['terms']         ="ALTER TABLE ".tablename('fm453_shopping_rules')." ADD "."`terms` text".";";
$tables['rules']['columns']['createtime']    ="ALTER TABLE ".tablename('fm453_shopping_rules')." ADD "."`createtime` int(10) NOT NULL".";";
$tables['rules']['columns']['commtime']      ="ALTER TABLE ".tablename('fm453_shopping_rules')." ADD "."`commtime` int(5) NOT NULL DEFAULT '15' COMMENT '默认15天'".";";
$tables['rules']['columns']['promotertimes'] ="ALTER TABLE ".tablename('fm453_shopping_rules')." ADD "."`promotertimes` int(10) NOT NULL DEFAULT '1' COMMENT '默认成交一次才能成为推广员'".";";
$tables['rules']['columns']['ischeck']       ="ALTER TABLE ".tablename('fm453_shopping_rules')." ADD "."`ischeck` tinyint(1) DEFAULT '1' COMMENT '0为未审核，1为审核'".";";
$tables['rules']['columns']['clickcredit']   ="ALTER TABLE ".tablename('fm453_shopping_rules')." ADD "."`clickcredit` int(10) NOT NULL DEFAULT '0' COMMENT '点击获取积分'".";";

$tables['rules']['indexes']['id']="`id` (`id`)";