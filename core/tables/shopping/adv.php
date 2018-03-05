<?php
$tables['adv']=array(
	'name'=>'商城首页幻灯片图库',
	'type'=>array("system","all")
);

$tables['adv']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_shopping_adv')." (`id` int(11) NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`)) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT='商城首页幻灯片图库';";

$tables['adv']['columns']['id']           ="ALTER TABLE ".tablename('fm453_shopping_adv')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['adv']['columns']['uniacid']      ="ALTER TABLE ".tablename('fm453_shopping_adv')." ADD "."`uniacid` int(11) DEFAULT '0' COMMENT '所属公众账号'".";";
$tables['adv']['columns']['advname']      ="ALTER TABLE ".tablename('fm453_shopping_adv')." ADD "."`advname` varchar(50) DEFAULT ''".";";
$tables['adv']['columns']['link']         ="ALTER TABLE ".tablename('fm453_shopping_adv')." ADD "."`link` varchar(255) NOT NULL DEFAULT ''".";";
$tables['adv']['columns']['thumb']        ="ALTER TABLE ".tablename('fm453_shopping_adv')." ADD "."`thumb` varchar(255) DEFAULT ''".";";
$tables['adv']['columns']['displayorder'] ="ALTER TABLE ".tablename('fm453_shopping_adv')." ADD "."`displayorder` int(11) DEFAULT '0'".";";
$tables['adv']['columns']['enabled']      ="ALTER TABLE ".tablename('fm453_shopping_adv')." ADD "."`enabled` int(11) DEFAULT '0'".";";
$tables['adv']['columns']['deleted']      ="ALTER TABLE ".tablename('fm453_shopping_adv')." ADD "."`deleted` tinyint(1) DEFAULT '0' COMMENT '是否删除，1是0否'".";";

$tables['adv']['indexes']['id']      ="`id` (`id`)";
$tables['adv']['indexes']['uniacid'] ="`uniacid` (`uniacid`)";
$tables['adv']['indexes']['enabled'] ="`enabled` (`enabled`)";
$tables['adv']['indexes']['deleted'] ="`deleted` (`deleted`)";
//结束
