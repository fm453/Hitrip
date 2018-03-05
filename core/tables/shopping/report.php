<?php
$tables['report']=array(
	'name'=>'统计分析表单记录表',
	'type'=>array("system","all")
);
$tables['report']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_shopping_report')." (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='统计分析表单记录表' ;";

$tables['report']['columns']['id']          ="ALTER TABLE ".tablename('fm453_shopping_report')." ADD "."`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT".";";
$tables['report']['columns']['uniacid']     ="ALTER TABLE ".tablename('fm453_shopping_report')." ADD "."`uniacid` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '所属公众帐号'".";";
$tables['report']['columns']['title']       ="ALTER TABLE ".tablename('fm453_shopping_report')." ADD "."`title` varchar(50) NOT NULL COMMENT '分析表名称'".";";
$tables['report']['columns']['setfor']      ="ALTER TABLE ".tablename('fm453_shopping_report')." ADD "."`setfor` varchar(50) NOT NULL COMMENT '分析表类型(分析对象)'".";";
$tables['report']['columns']['pagestyle']   ="ALTER TABLE ".tablename('fm453_shopping_report')." ADD "."`pagestyle`varchar(50) COMMENT '页面风格'".";";
$tables['report']['columns']['description'] ="ALTER TABLE ".tablename('fm453_shopping_report')." ADD "."`description`text COMMENT ' 对报表进行概述说明'".";";
$tables['report']['columns']['statuscode']  ="ALTER TABLE ".tablename('fm453_shopping_report')." ADD "."`statuscode` int(11) NOT NULL COMMENT '状态码'".";";
$tables['report']['columns']['createtime']  ="ALTER TABLE ".tablename('fm453_shopping_report')." ADD "."`createtime` int(12) NOT NULL COMMENT '记录创建时间'".";";

$tables['report']['indexes']['id']      ="`id` (`id`)";
$tables['report']['indexes']['uniacid'] ="`uniacid` (`uniacid`)";
$tables['report']['indexes']['setfor']  ="`setfor` (`setfor`)";
//结束
