<?php
$tables['preedit']=array(
	'name'=>'预编辑记录表',
	'type'=>array("goods","partner","user","article","needs","system","all")
);
$tables['preedit']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_shopping_preedit')." (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='预编辑记录表' ;";

$tables['preedit']['columns']['id']         ="ALTER TABLE ".tablename('fm453_shopping_preedit')." ADD "."`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT".";";
$tables['preedit']['columns']['uniacid']    ="ALTER TABLE ".tablename('fm453_shopping_preedit')." ADD "."`uniacid` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '所属公众帐号'".";";
$tables['preedit']['columns']['tablename']  ="ALTER TABLE ".tablename('fm453_shopping_preedit')." ADD "."`tablename` varchar(50) NOT NULL COMMENT '表名称'".";";
$tables['preedit']['columns']['rid']        ="ALTER TABLE ".tablename('fm453_shopping_preedit')." ADD "."`rid` int(11) NOT NULL COMMENT '表数据记录号'".";";
$tables['preedit']['columns']['setfor']     ="ALTER TABLE ".tablename('fm453_shopping_preedit')." ADD "."`setfor` varchar(50) NOT NULL COMMENT '对应字段'".";";
$tables['preedit']['columns']['content']    ="ALTER TABLE ".tablename('fm453_shopping_preedit')." ADD "."`content`text COMMENT '具体内容'".";";
$tables['preedit']['columns']['reply']      ="ALTER TABLE ".tablename('fm453_shopping_preedit')." ADD "."`reply`text COMMENT '管理人员回复'".";";
$tables['preedit']['columns']['statuscode'] ="ALTER TABLE ".tablename('fm453_shopping_preedit')." ADD "."`statuscode` int(11) NOT NULL COMMENT '状态码'".";";
$tables['preedit']['columns']['createtime'] ="ALTER TABLE ".tablename('fm453_shopping_preedit')." ADD "."`createtime` int(12) NOT NULL COMMENT '记录创建时间'".";";

$tables['preedit']['indexes']['id']        ="`id` (`id`)";
$tables['preedit']['indexes']['uniacid']   ="`uniacid` (`uniacid`)";
$tables['preedit']['indexes']['rid']       ="`rid` (`rid`)";
$tables['preedit']['indexes']['tablename'] ="`tablename` (`tablename`)";
$tables['preedit']['indexes']['setfor']    ="`setfor` (`setfor`)";
//结束
