<?php
$tables['qrandfan']=array(
	'name'=>'二维码与粉丝关联表',
	'type'=>array("service","site","system","all")
);
$tables['qrandfan']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_duokefu_qrandfan')." (`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`))ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='二维码与粉丝关联表' ;";

$tables['qrandfan']['columns']['id']          ="ALTER TABLE ".tablename('fm453_duokefu_qrandfan')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['qrandfan']['columns']['uniacid']     ="ALTER TABLE ".tablename('fm453_duokefu_qrandfan')." ADD "."`uniacid` int(11) UNSIGNED NOT NULL COMMENT '所属公众账号'".";";
$tables['qrandfan']['columns']['qrcodeid']    ="ALTER TABLE ".tablename('fm453_duokefu_qrandfan')." ADD "."`qrcodeid` int(11) NOT NULL COMMENT '二维码记录ID'".";";	
$tables['qrandfan']['columns']['ischecked']   ="ALTER TABLE ".tablename('fm453_duokefu_qrandfan')." ADD "."`ischecked` tinyint(1) UNSIGNED NOT NULL DEFAULT '1'".";";
$tables['qrandfan']['columns']['isavailable'] ="ALTER TABLE ".tablename('fm453_duokefu_qrandfan')." ADD "."`isavailable` tinyint(1) UNSIGNED NOT NULL DEFAULT '1'".";";

$tables['qrandfan']['columns']['openid']      ="ALTER TABLE ".tablename('fm453_duokefu_qrandfan')." ADD "."`openid` varchar(255) DEFAULT '0' COMMENT '关联粉丝OPENID'".";";
$tables['qrandfan']['columns']['qrcodename']  ="ALTER TABLE ".tablename('fm453_duokefu_qrandfan')." ADD "."`qrcodename` varchar(255) DEFAULT '0' COMMENT '二维码名称'".";";
	

$tables['qrandfan']['indexes']['id']      ="`id` (`id`)";
$tables['qrandfan']['indexes']['uniacid'] ="`uniacid` (`uniacid`)";
//结束
