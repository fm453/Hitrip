<?php
	$tables['needs_data']=array(
		'name'=>'有求必应数据表',
		'type'=>array("system","needs","all")
	);
	$tables['needs_data']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_shopping_needs_data')." (`id` int(11) NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='有求必应数据表';";

	$tables['needs_data']['columns']['id']="ALTER TABLE ".tablename('fm453_shopping_needs_data')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
	$tables['needs_data']['columns']['nid']="ALTER TABLE ".tablename('fm453_shopping_needs_data')." ADD "."`nid` int(11) NOT NULL COMMENT '需求表单id'".";";
	$tables['needs_data']['columns']['sn']="ALTER TABLE ".tablename('fm453_shopping_needs_data')." ADD "."`sn` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '序列号SN,拥有同一SN的为同一批次保存的数据'".";";
	$tables['needs_data']['columns']['setfor']="ALTER TABLE ".tablename('fm453_shopping_needs_data')." ADD "."`setfor` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '要设置的项'".";";
	$tables['needs_data']['columns']['title']="ALTER TABLE ".tablename('fm453_shopping_needs_data')." ADD "."`title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '具体设置项'".";";
	$tables['needs_data']['columns']['value']="ALTER TABLE ".tablename('fm453_shopping_needs_data')." ADD "."`value` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '设置的值'".";";
	$tables['needs_data']['columns']['status']="ALTER TABLE ".tablename('fm453_shopping_needs_data')." ADD "."`status` tinyint(3) NOT NULL COMMENT '状态，是否有效'".";";
	$tables['needs_data']['columns']['is_contact']="ALTER TABLE ".tablename('fm453_shopping_needs_data')." ADD "."`is_contact` tinyint(3) NOT NULL COMMENT '是否联系了填表人'".";";
	$tables['needs_data']['columns']['reply']="ALTER TABLE ".tablename('fm453_shopping_needs_data')." ADD "."`reply` text CHARACTER SET utf8 COLLATE utf8_general_ci  COMMENT '工作人员回复'".";";
$tables['needs_data']['columns']['createtime']="ALTER TABLE ".tablename('fm453_shopping_needs_data')." ADD "."`createtime` int(11) NOT NULL COMMENT '创建时间'".";";

	$tables['needs_data']['indexes']['id']="`id` (`id`)";
	$tables['needs_data']['indexes']['nid']="`nid` (`nid`)";
	$tables['needs_data']['indexes']['sn']="`sn` (`sn`)";
	$tables['needs_data']['indexes']['setfor']="`setfor` (`setfor`)";
	$tables['needs_data']['indexes']['status']="`status` (`status`)";
