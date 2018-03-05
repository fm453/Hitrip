<?php
$tables['settings']=array(
	'name'=>'商城设置记录表',
	'type'=>array("system","all")
);
$tables['settings']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_shopping_settings')." (`id` int(11) NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商城配置记录';";

$tables['settings']['columns']['id']         ="ALTER TABLE ".tablename('fm453_shopping_settings')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['settings']['columns']['uniacid']    ="ALTER TABLE ".tablename('fm453_shopping_settings')." ADD "."`uniacid` int(11) NOT NULL COMMENT '公众号id,为0时则为全局设置'".";";
$tables['settings']['columns']['setfor']     ="ALTER TABLE ".tablename('fm453_shopping_settings')." ADD "."`setfor` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '要设置的项'".";";
$tables['settings']['columns']['title']      ="ALTER TABLE ".tablename('fm453_shopping_settings')." ADD "."`title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '具体设置项'".";";
$tables['settings']['columns']['value']      ="ALTER TABLE ".tablename('fm453_shopping_settings')." ADD "."`value` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '设置的值'".";";
$tables['settings']['columns']['status']     ="ALTER TABLE ".tablename('fm453_shopping_settings')." ADD "."`status` tinyint(3) NOT NULL COMMENT '状态，如是否启用'".";";
$tables['settings']['columns']['m']          ="ALTER TABLE ".tablename('fm453_shopping_settings')." ADD "."`m` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'fm453_shopping' COMMENT '对应模块'".";";
$tables['settings']['columns']['createtime'] ="ALTER TABLE ".tablename('fm453_shopping_settings')." ADD "."`createtime` int(11) NOT NULL COMMENT '创建时间'".";";

$tables['settings']['indexes']['id']      ="`id` (`id`)";
$tables['settings']['indexes']['uniacid'] ="`uniacid` (`uniacid`)";
$tables['settings']['indexes']['setfor']  ="`setfor` (`setfor`)";
$tables['settings']['indexes']['status']  ="`status` (`status`)";
//settings结束
