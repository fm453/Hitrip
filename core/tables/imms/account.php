<?php
$tables['account']=array(
	'name'=>'接入的平台账号',
	'type'=>array("system","all"),
	'disabled'=>true
);

$tables['account']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_imms_account')." (`id` int(11) NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`)) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT='接入的平台账号';";

$tables['account']['columns']['id']        ="ALTER TABLE ".tablename('fm453_imms_account')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['account']['columns']['uniacid']   ="ALTER TABLE ".tablename('fm453_imms_account')." ADD "."`uniacid` int(11) DEFAULT '0' COMMENT '关联公众账号'".";";
$tables['account']['columns']['corpid']    ="ALTER TABLE ".tablename('fm453_imms_account')." ADD "."`corpid` int(11) DEFAULT '0' COMMENT '接入平台账号'".";";
$tables['account']['columns']['type']      ="ALTER TABLE ".tablename('fm453_imms_account')." ADD "."`type` tinyint(3) DEFAULT '1' COMMENT '账号类型1企业微信'".";";
$tables['account']['columns']['connected'] ="ALTER TABLE ".tablename('fm453_imms_account')." ADD "."`connected` tinyint(3) DEFAULT '0' COMMENT '是否连接，1是0否'".";";
$tables['account']['columns']['deleted']   ="ALTER TABLE ".tablename('fm453_imms_account')." ADD "."`deleted` tinyint(1) DEFAULT '0' COMMENT '是否删除，1是0否'".";";

$tables['account']['indexes']['id']        ="`id` (`id`)";
$tables['account']['indexes']['uniacid']   ="`uniacid` (`uniacid`)";
$tables['account']['indexes']['corpid']    ="`corpid` (`corpid`)";
$tables['account']['indexes']['connected'] ="`connected` (`connected`)";
$tables['account']['indexes']['deleted']   ="`deleted` (`deleted`)";
//结束
