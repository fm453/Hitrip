<?php
$tables['redirect']=array(
	'name'=>'临时跳转网址(用完即删)',
	'type'=>array("system","all")
);

$tables['redirect']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_imms_redirect')." (`id` int(11) NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`)) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT='临时跳转网址';";

$tables['redirect']['columns']['id']        ="ALTER TABLE ".tablename('fm453_imms_redirect')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['redirect']['columns']['uniacid']   ="ALTER TABLE ".tablename('fm453_imms_redirect')." ADD "."`uniacid` int(11) DEFAULT '0' COMMENT '关联公众账号'".";";
$tables['redirect']['columns']['from']      ="ALTER TABLE ".tablename('fm453_imms_redirect')." ADD "."`from` varchar(255) NOT NULL COMMENT '来路网址'".";";
$tables['redirect']['columns']['to']      ="ALTER TABLE ".tablename('fm453_imms_redirect')." ADD "."`to` varchar(255) NOT NULL COMMENT '要跳往的网址'".";";
$tables['redirect']['columns']['state'] ="ALTER TABLE ".tablename('fm453_imms_redirect')." ADD "."`state` varchar(32) NOT NULL  COMMENT '网址标识'".";";

$tables['redirect']['indexes']['id']        ="`id` (`id`)";
$tables['redirect']['indexes']['uniacid']   ="`uniacid` (`uniacid`)";
$tables['redirect']['indexes']['state']   ="`state` (`state`)";
//结束
