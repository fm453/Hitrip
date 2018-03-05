<?php
$tables['adboxes']=array(
	'name'=>'商城广告位资源',
	'type'=>array("system","all")
);
$tables['adboxes']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_shopping_adboxes')." (`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`)) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT='广告位资源表';";

$tables['adboxes']['columns']['id']           ="ALTER TABLE ".tablename('fm453_shopping_adboxes')." ADD "."`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT".";";
$tables['adboxes']['columns']['uniacid']      ="ALTER TABLE ".tablename('fm453_shopping_adboxes')." ADD "."`uniacid` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '所属帐号'".";";
$tables['adboxes']['columns']['starttime']    ="ALTER TABLE ".tablename('fm453_shopping_adboxes')." ADD "."`starttime` varchar(50) NOT NULL COMMENT '广告位上线时间'".";";
$tables['adboxes']['columns']['endtime']      ="ALTER TABLE ".tablename('fm453_shopping_adboxes')." ADD "."`endtime` varchar(50) NOT NULL COMMENT '广告位下线时间'".";";
$tables['adboxes']['columns']['name']         ="ALTER TABLE ".tablename('fm453_shopping_adboxes')." ADD "."`name` varchar(50) NOT NULL COMMENT '广告位名称'".";";
$tables['adboxes']['columns']['forpage']      ="ALTER TABLE ".tablename('fm453_shopping_adboxes')." ADD "."`forpage` varchar(50) NOT NULL COMMENT '广告位标识，用于除首页外调用时的筛选'".";";
$tables['adboxes']['columns']['thumb']        ="ALTER TABLE ".tablename('fm453_shopping_adboxes')." ADD "."`thumb` varchar(255) NOT NULL COMMENT '广告位图片'".";";
$tables['adboxes']['columns']['parentid']     ="ALTER TABLE ".tablename('fm453_shopping_adboxes')." ADD "."`parentid` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级'".";";
$tables['adboxes']['columns']['isrecommand']  ="ALTER TABLE ".tablename('fm453_shopping_adboxes')." ADD "."`isrecommand` int(10) DEFAULT '0'".";";
$tables['adboxes']['columns']['description']  ="ALTER TABLE ".tablename('fm453_shopping_adboxes')." ADD "."`description` varchar(500) NOT NULL COMMENT '广告位介绍'".";";
$tables['adboxes']['columns']['displayorder'] ="ALTER TABLE ".tablename('fm453_shopping_adboxes')." ADD "."`displayorder` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '排序'".";";
$tables['adboxes']['columns']['enabled']      ="ALTER TABLE ".tablename('fm453_shopping_adboxes')." ADD "."`enabled` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '是否开启，1是0否'".";";
$tables['adboxes']['columns']['deleted']      ="ALTER TABLE ".tablename('fm453_shopping_adboxes')." ADD "."`deleted` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否删除，1是0否'".";";

$tables['adboxes']['indexes']['id']          ="`id` (`id`)";
$tables['adboxes']['indexes']['uniacid']     ="`uniacid` (`uniacid`)";
$tables['adboxes']['indexes']['parentid']    ="`parentid` (`parentid`)";
$tables['adboxes']['indexes']['isrecommand'] ="`isrecommand` (`isrecommand`)";
$tables['adboxes']['indexes']['enabled']     ="`enabled` (`enabled`)";
$tables['adboxes']['indexes']['deleted']     ="`deleted` (`deleted`)";
$tables['adboxes']['indexes']['forpage']     ="`forpage` (`forpage`)";
//结束
