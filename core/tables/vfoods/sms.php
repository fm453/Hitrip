<?php
$tables['sms']=array(
	'name'=>'邮箱及短信配置',
	'type'=>array("foods","vfoods","system","all")
);
$tables['sms']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_vfoods_sms')." (`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`))ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='邮箱及短信配置' ;";

$tables['sms']['columns']['id']      ="ALTER TABLE ".tablename('fm453_vfoods_sms')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['sms']['columns']['uniacid'] ="ALTER TABLE ".tablename('fm453_vfoods_sms')." ADD "."`uniacid` int(11) UNSIGNED NOT NULL COMMENT '所属公众账号'".";";
$tables['sms']['columns']['email']   ="ALTER TABLE ".tablename('fm453_vfoods_sms')." ADD "."`email` varchar(50) NOT NULL COMMENT ''".";";
$tables['sms']['columns']['emailpsw']="ALTER TABLE ".tablename('fm453_vfoods_sms')." ADD "."`emailpsw` varchar(50) NOT NULL COMMENT ''".";";
$tables['sms']['columns']['smtp']    ="ALTER TABLE ".tablename('fm453_vfoods_sms')." ADD "."`smtp` varchar(50) NOT NULL COMMENT ''".";";
$tables['sms']['columns']['smsnum']  ="ALTER TABLE ".tablename('fm453_vfoods_sms')." ADD "."`smsnum` varchar(50) NOT NULL COMMENT ''".";";
$tables['sms']['columns']['smspsw']  ="ALTER TABLE ".tablename('fm453_vfoods_sms')." ADD "."`smspsw` varchar(50) NOT NULL COMMENT ''".";";
$tables['sms']['columns']['smstest'] ="ALTER TABLE ".tablename('fm453_vfoods_sms')." ADD "."`smstest` bigint(20) UNSIGNED DEFAULT NULL COMMENT ''".";";
$tables['sms']['columns']['managers']   ="ALTER TABLE ".tablename('fm453_vfoods_sms')." ADD "."`managers` text COMMENT '全局核销员'".";";

$tables['sms']['indexes']['id']      ="`id` (`id`)";
$tables['sms']['indexes']['uniacid'] ="`uniacid` (`uniacid`)";
$tables['sms']['indexes']['email']   ="`email` (`email`)";

?>
