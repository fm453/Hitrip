<?php
$tables['spec_item']=array(
	'name'=>'产品规格具体项',
	'type'=>array("goods","system","all")
);
$tables['spec_item']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_shopping_spec_item')." (`id` int(11) NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`)) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;";

$tables['spec_item']['columns']['id']           ="ALTER TABLE ".tablename('fm453_shopping_spec_item')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['spec_item']['columns']['uniacid']      ="ALTER TABLE ".tablename('fm453_shopping_spec_item')." ADD "."`uniacid` int(11) DEFAULT '0' COMMENT '所属公众账号'".";";
$tables['spec_item']['columns']['specid']       ="ALTER TABLE ".tablename('fm453_shopping_spec_item')." ADD "."`specid` int(11) DEFAULT '0'".";";
$tables['spec_item']['columns']['title']        ="ALTER TABLE ".tablename('fm453_shopping_spec_item')." ADD "."`title` varchar(255) DEFAULT ''".";";
$tables['spec_item']['columns']['thumb']        ="ALTER TABLE ".tablename('fm453_shopping_spec_item')." ADD "."`thumb` varchar(255) DEFAULT ''".";";
$tables['spec_item']['columns']['istime']       ="ALTER TABLE ".tablename('fm453_shopping_spec_item')." ADD "."`istime` int(11) NOT NULL DEFAULT '0' COMMENT '是否启用有效时间限制，1为启用，0为不启用。默认不启用。'".";";
$tables['spec_item']['columns']['startdate']    ="ALTER TABLE ".tablename('fm453_shopping_spec_item')." ADD "."`startdate` int(11) DEFAULT NULL COMMENT '产品规格项的开始日期'".";";
$tables['spec_item']['columns']['enddate']      ="ALTER TABLE ".tablename('fm453_shopping_spec_item')." ADD "."`enddate` int(11) DEFAULT NULL COMMENT '产品规格项的结束日期'".";";
$tables['spec_item']['columns']['show']         ="ALTER TABLE ".tablename('fm453_shopping_spec_item')." ADD "."`show` int(11) DEFAULT '0'".";";
$tables['spec_item']['columns']['displayorder'] ="ALTER TABLE ".tablename('fm453_shopping_spec_item')." ADD "."`displayorder` int(11) DEFAULT '0'".";";

$tables['spec_item']['indexes']['id']="`id` (`id`)";