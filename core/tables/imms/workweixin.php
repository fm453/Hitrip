<?php
$tables['workweixin']=array(
	'name'=>'企业微信账号',
	'type'=>array("system","all"),
	'disabled'=>true
);

$tables['workweixin']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_imms_workweixin')." (`id` int(11) NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`)) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT='企业微信账号';";

$tables['workweixin']['columns']['id']           ="ALTER TABLE ".tablename('fm453_imms_workweixin')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['workweixin']['columns']['uniacid']      ="ALTER TABLE ".tablename('fm453_imms_workweixin')." ADD "."`uniacid` int(11) DEFAULT '0' COMMENT '关联公众账号'".";";
$tables['workweixin']['columns']['corpid']      ="ALTER TABLE ".tablename('fm453_imms_workweixin')." ADD "."`corpid` int(11) DEFAULT '0' COMMENT '企业微信ID'".";";
$tables['workweixin']['columns']['corpsecret']      ="ALTER TABLE ".tablename('fm453_imms_workweixin')." ADD "."`corpsecret` varchar(50) NOT NULL COMMENT '企业微信应用密钥'".";";
$tables['workweixin']['columns']['title']      ="ALTER TABLE ".tablename('fm453_imms_workweixin')." ADD "."`title` varchar(50) NOT NULL COMMENT '企业微信账号名称'".";";
$tables['workweixin']['columns']['encodingaeskey']      ="ALTER TABLE ".tablename('fm453_imms_workweixin')." ADD "."`encodingaeskey` varchar(255) NOT NULL COMMENT '加密密钥'".";";

$tables['workweixin']['indexes']['id']      ="`id` (`id`)";
$tables['workweixin']['indexes']['uniacid'] ="`uniacid` (`uniacid`)";
$tables['workweixin']['indexes']['corpid']      ="`corpid` (`corpid`)";
$tables['workweixin']['indexes']['title'] ="`title` (`title`)";
//结束
