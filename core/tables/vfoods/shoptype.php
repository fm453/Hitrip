<?php
$tables['shoptype']=array(
	'name'=>'店铺类型',
	'type'=>array("foods","vfoods","system","all")
);
$tables['shoptype']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_vfoods_shoptype')." (`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`))ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='店铺类型' ;";

$tables['shoptype']['columns']['id']           ="ALTER TABLE ".tablename('fm453_vfoods_shoptype')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['shoptype']['columns']['uniacid']      ="ALTER TABLE ".tablename('fm453_vfoods_shoptype')." ADD "."`uniacid` int(11) UNSIGNED NOT NULL COMMENT '所属公众账号'".";";
$tables['shoptype']['columns']['title']        ="ALTER TABLE ".tablename('fm453_vfoods_shoptype')." ADD "."`title` varchar(100) NOT NULL COMMENT ''".";";
$tables['shoptype']['columns']['description']  ="ALTER TABLE ".tablename('fm453_vfoods_shoptype')." ADD "."`description` varchar(1000) NOT NULL COMMENT ''".";";
$tables['shoptype']['columns']['displayorder'] ="ALTER TABLE ".tablename('fm453_vfoods_shoptype')." ADD "."`displayorder` int(5) UNSIGNED NOT NULL COMMENT ''".";";

$tables['shoptype']['indexes']['id']           ="`id` (`id`)";
$tables['shoptype']['indexes']['uniacid']      ="`uniacid` (`uniacid`)";
$tables['shoptype']['indexes']['title']        ="`title` (`title`)";

?>