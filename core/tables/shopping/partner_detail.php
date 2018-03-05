<?php
$tables['partner_detail']=array(
	'name'=>'合作商户详情',
	'type'=>array("partner","system","all")
);
$tables['partner_detail']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_shopping_partner_detail')." (`id` int(11) NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='合作伙伴的详情';";

$tables['partner_detail']['columns']['id']         ="ALTER TABLE ".tablename('fm453_shopping_partner_detail')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['partner_detail']['columns']['psn']        ="ALTER TABLE ".tablename('fm453_shopping_partner_detail')." ADD "."`psn` varchar(20) NOT NULL COMMENT '伙伴编码'".";";
$tables['partner_detail']['columns']['param']      ="ALTER TABLE ".tablename('fm453_shopping_partner_detail')." ADD "."`param` varchar(255) NOT NULL COMMENT '参数'".";";
$tables['partner_detail']['columns']['value']      ="ALTER TABLE ".tablename('fm453_shopping_partner_detail')." ADD "."`value` text NOT NULL COMMENT '参数值'".";";
$tables['partner_detail']['columns']['status']     ="ALTER TABLE ".tablename('fm453_shopping_partner_detail')." ADD "."`status` tinyint(3) NOT NULL COMMENT '参数状态'".";";
$tables['partner_detail']['columns']['createtime'] ="ALTER TABLE ".tablename('fm453_shopping_partner_detail')." ADD "."`createtime` int(11) NOT NULL COMMENT '记录创建时间'".";";

$tables['partner_detail']['indexes']['id']="`id` (`id`)";