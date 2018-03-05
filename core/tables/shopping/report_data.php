<?php
$tables['report_data']=array(
	'name'=>'统计分析数据表',
	'type'=>array("system","all")
);
$tables['report_data']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_shopping_report_data')." (`id` int(11) NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='统计分析数据表';";

$tables['report_data']['columns']['id']         ="ALTER TABLE ".tablename('fm453_shopping_report_data')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['report_data']['columns']['rid']        ="ALTER TABLE ".tablename('fm453_shopping_report_data')." ADD "."`rid` int(11) NOT NULL COMMENT '统计表单id'".";";
$tables['report_data']['columns']['sn']         ="ALTER TABLE ".tablename('fm453_shopping_report_data')." ADD "."`sn` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '序列号SN,拥有同一SN的为同一批次保存的数据'".";";
$tables['report_data']['columns']['setfor']     ="ALTER TABLE ".tablename('fm453_shopping_report_data')." ADD "."`setfor` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '要设置的项标识'".";";
$tables['report_data']['columns']['title']      ="ALTER TABLE ".tablename('fm453_shopping_report_data')." ADD "."`title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '具体设置项名称'".";";
$tables['report_data']['columns']['value']      ="ALTER TABLE ".tablename('fm453_shopping_report_data')." ADD "."`value` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '设置的值'".";";
$tables['report_data']['columns']['permission'] ="ALTER TABLE ".tablename('fm453_shopping_report_data')." ADD "."`permission` text CHARACTER SET utf8 COLLATE utf8_general_ci  COMMENT '对允许查阅的权限设置明细'".";";
$tables['report_data']['columns']['createtime'] ="ALTER TABLE ".tablename('fm453_shopping_report_data')." ADD "."`createtime` int(11) NOT NULL COMMENT '创建时间'".";";

$tables['report_data']['indexes']['id']     ="`id` (`id`)";
$tables['report_data']['indexes']['rid']    ="`rid` (`rid`)";
$tables['report_data']['indexes']['sn']     ="`sn` (`sn`)";
$tables['report_data']['indexes']['setfor'] ="`setfor` (`setfor`)";
$tables['report_data']['indexes']['status'] ="`status` (`status`)";
