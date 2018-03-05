<?php
$tables['needs_param']=array(
	'name'=>'有求必应参数表',
	'type'=>array("system","needs","all")
);
$tables['needs_param']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_shopping_needs_param')." (`id` int(11) NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='有求必应参数表';";

$tables['needs_param']['columns']['id']         ="ALTER TABLE ".tablename('fm453_shopping_needs_param')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['needs_param']['columns']['nid']        ="ALTER TABLE ".tablename('fm453_shopping_needs_param')." ADD "."`nid` int(11) NOT NULL COMMENT '需求表单id'".";";
$tables['needs_param']['columns']['setfor']     ="ALTER TABLE ".tablename('fm453_shopping_needs_param')." ADD "."`setfor` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '要设置的项'".";";
$tables['needs_param']['columns']['title']      ="ALTER TABLE ".tablename('fm453_shopping_needs_param')." ADD "."`title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '具体设置项'".";";
$tables['needs_param']['columns']['value']      ="ALTER TABLE ".tablename('fm453_shopping_needs_param')." ADD "."`value` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '设置的值'".";";
$tables['needs_param']['columns']['status']     ="ALTER TABLE ".tablename('fm453_shopping_needs_param')." ADD "."`status` tinyint(3) NOT NULL COMMENT '状态，如是否启用'".";";
$tables['needs_param']['columns']['createtime'] ="ALTER TABLE ".tablename('fm453_shopping_needs_param')." ADD "."`createtime` int(11) NOT NULL COMMENT '创建时间'".";";

$tables['needs_param']['indexes']['id']     ="`id` (`id`)";
$tables['needs_param']['indexes']['nid']    ="`nid` (`nid`)";
$tables['needs_param']['indexes']['setfor'] ="`setfor` (`setfor`)";
$tables['needs_param']['indexes']['status'] ="`status` (`status`)";
