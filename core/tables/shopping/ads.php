<?php
$tables['ads']=array(
	'name'=>'商城广告',
	'type'=>array("system","all")
);

$tables['ads']['sql']                     ="CREATE TABLE IF NOT EXISTS ".tablename('fm453_shopping_ads')." (`id` int(11) NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`)) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT='广告表';";

$tables['ads']['columns']['id']           ="ALTER TABLE ".tablename('fm453_shopping_ads')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['ads']['columns']['uniacid']      ="ALTER TABLE ".tablename('fm453_shopping_ads')." ADD "."`uniacid` int(11) DEFAULT '0' COMMENT '所属公众账号'".";";
$tables['ads']['columns']['pcate']        ="ALTER TABLE ".tablename('fm453_shopping_ads')." ADD "."`pcate` int(10) NOT NULL COMMENT '上级分类'".";";
$tables['ads']['columns']['ccate']        ="ALTER TABLE ".tablename('fm453_shopping_ads')." ADD "."`ccate` int(10) DEFAULT NULL COMMENT '当前分类'".";";
$tables['ads']['columns']['adname']       ="ALTER TABLE ".tablename('fm453_shopping_ads')." ADD `adname` varchar(50) DEFAULT NULL".";";
$tables['ads']['columns']['link']         ="ALTER TABLE ".tablename('fm453_shopping_ads')." ADD "."`link` varchar(255) NOT NULL DEFAULT ''".";";
$tables['ads']['columns']['thumb']        ="ALTER TABLE ".tablename('fm453_shopping_ads')." ADD "."`thumb` varchar(255) DEFAULT ''".";";
$tables['ads']['columns']['displayorder'] ="ALTER TABLE ".tablename('fm453_shopping_ads')." ADD "."`displayorder` int(11) DEFAULT '0'".";";
$tables['ads']['columns']['enabled']      ="ALTER TABLE ".tablename('fm453_shopping_ads')." ADD "."`enabled` int(11) DEFAULT '0'".";";
$tables['ads']['columns']['viewcount']    ="ALTER TABLE ".tablename('fm453_shopping_ads')." ADD "."`viewcount` int(11) NOT NULL DEFAULT '0' COMMENT '浏览量'".";";
$tables['ads']['columns']['clickcount']   ="ALTER TABLE ".tablename('fm453_shopping_ads')." ADD "."`clickcount` int(11) NOT NULL DEFAULT '0' COMMENT '点击量'".";";
$tables['ads']['columns']['deleted']      ="ALTER TABLE ".tablename('fm453_shopping_ads')." ADD "."`deleted` tinyint(1) DEFAULT '0' COMMENT '是否删除，1是0否'".";";

$tables['ads']['indexes']['id']      ="`id` (`id`)";
$tables['ads']['indexes']['uniacid'] ="`uniacid` (`uniacid`)";
$tables['ads']['indexes']['enabled'] ="`enabled` (`enabled`)";
$tables['ads']['indexes']['deleted'] ="`deleted` (`deleted`)";
//结束
