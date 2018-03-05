<?php
$tables['privilege']=array(
	'name'=>'权限配置表',
	'type'=>array("system","all")
);
$tables['privilege']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_shopping_privilege')." (`id` int(11) NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='权限配置表';";

$tables['privilege']['columns']['id']         ="ALTER TABLE ".tablename('fm453_shopping_privilege')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['privilege']['columns']['uid']        ="ALTER TABLE ".tablename('fm453_shopping_privilege')." ADD "."`uid` int(11) NOT NULL COMMENT '后台用户ID'".";";
$tables['privilege']['columns']['uniacid']    ="ALTER TABLE ".tablename('fm453_shopping_privilege')." ADD "."`uniacid` int(11) NOT NULL COMMENT '公号ID'".";";
$tables['privilege']['columns']['platid']     ="ALTER TABLE ".tablename('fm453_shopping_privilege')." ADD "."`platid` int(11) NOT NULL COMMENT '可管理的平台ID'".";";
$tables['privilege']['columns']['do']         ="ALTER TABLE ".tablename('fm453_shopping_privilege')." ADD "."`do` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '主类程序入口'".";";
$tables['privilege']['columns']['ac']         ="ALTER TABLE ".tablename('fm453_shopping_privilege')." ADD "."`ac` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '具体程序控制器入口'".";";
$tables['privilege']['columns']['op']         ="ALTER TABLE ".tablename('fm453_shopping_privilege')." ADD "."`op` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '程序操作页入口'".";";
$tables['privilege']['columns']['view']       ="ALTER TABLE ".tablename('fm453_shopping_privilege')." ADD "."`view` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '浏览'".";";
$tables['privilege']['columns']['modify']     ="ALTER TABLE ".tablename('fm453_shopping_privilege')." ADD "."`modify` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '编辑'".";";
$tables['privilege']['columns']['delete']     ="ALTER TABLE ".tablename('fm453_shopping_privilege')." ADD "."`delete` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '删除'".";";
$tables['privilege']['columns']['role']       ="ALTER TABLE ".tablename('fm453_shopping_privilege')." ADD "."`role` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '角色'".";";
$tables['privilege']['columns']['m']          ="ALTER TABLE ".tablename('fm453_shopping_privilege')." ADD "."`m` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'fm453_shopping' COMMENT '对应模块'".";";
$tables['privilege']['columns']['createtime'] ="ALTER TABLE ".tablename('fm453_shopping_privilege')." ADD "."`createtime` int(11) NOT NULL COMMENT '创建时间'".";";

$tables['privilege']['indexes']['id']      ="`id` (`id`)";
$tables['privilege']['indexes']['uid']     ="`uid` (`uid`)";
$tables['privilege']['indexes']['uniacid'] ="`uniacid` (`uniacid`)";
$tables['privilege']['indexes']['platid']  ="`platid` (`platid`)";
$tables['privilege']['indexes']['do']      ="`do` (`do`)";
$tables['privilege']['indexes']['ac']      ="`ac` (`ac`)";
$tables['privilege']['indexes']['op']      ="`op` (`op`)";
//privilege结束
