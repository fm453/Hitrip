<?php
$tables['member_settings']=array(
	'name'=>'会员个性设置表',
	'type'=>array("system","member","all")
);
$tables['member_settings']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_shopping_member_settings')." (`id` int(11) NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员个性设置';";

$tables['member_settings']['columns']['id']         ="ALTER TABLE ".tablename('fm453_shopping_member_settings')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['member_settings']['columns']['uniacid']    ="ALTER TABLE ".tablename('fm453_shopping_member_settings')." ADD "."`uniacid` int(11) NOT NULL COMMENT '公众号id,为0时则为全局设置'".";";
$tables['member_settings']['columns']['uid']        ="ALTER TABLE ".tablename('fm453_shopping_member_settings')." ADD "."`uid` int(11) NOT NULL COMMENT '会员id'".";";
$tables['member_settings']['columns']['setfor']     ="ALTER TABLE ".tablename('fm453_shopping_member_settings')." ADD "."`setfor` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '要设置的项'".";";
$tables['member_settings']['columns']['title']      ="ALTER TABLE ".tablename('fm453_shopping_member_settings')." ADD "."`title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '具体设置项'".";";
$tables['member_settings']['columns']['value']      ="ALTER TABLE ".tablename('fm453_shopping_member_settings')." ADD "."`value` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '设置的值'".";";
$tables['member_settings']['columns']['status']     ="ALTER TABLE ".tablename('fm453_shopping_member_settings')." ADD "."`status` tinyint(3) NOT NULL COMMENT '状态，如是否启用'".";";
$tables['member_settings']['columns']['createtime'] ="ALTER TABLE ".tablename('fm453_shopping_member_settings')." ADD "."`createtime` int(11) NOT NULL COMMENT '创建时间'".";";

$tables['member_settings']['indexes']['id']      ="`id` (`id`)";
$tables['member_settings']['indexes']['uid']     ="`uid` (`uid`)";
$tables['member_settings']['indexes']['uniacid'] ="`uniacid` (`uniacid`)";
$tables['member_settings']['indexes']['setfor']  ="`setfor` (`setfor`)";
$tables['member_settings']['indexes']['status']  ="`status` (`status`)";
//settings结束
