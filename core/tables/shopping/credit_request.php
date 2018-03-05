<?php
$tables['credit_request']=array(
	'name'=>'积分兑换申请',
	'type'=>array("member","system","all")
);
$tables['credit_request']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_shopping_credit_request')." (`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

$tables['credit_request']['columns']['id']         ="ALTER TABLE ".tablename('fm453_shopping_credit_request')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['credit_request']['columns']['uniacid']    ="ALTER TABLE ".tablename('fm453_shopping_credit_request')." ADD "."`uniacid` int(11) UNSIGNED NOT NULL COMMENT '所属公众账号'".";";
$tables['credit_request']['columns']['from_user']  ="ALTER TABLE ".tablename('fm453_shopping_credit_request')." ADD "."`from_user` varchar(50) NOT NULL".";";
$tables['credit_request']['columns']['award_id']   ="ALTER TABLE ".tablename('fm453_shopping_credit_request')." ADD "."`award_id` int(11) UNSIGNED NOT NULL".";";
$tables['credit_request']['columns']['createtime'] ="ALTER TABLE ".tablename('fm453_shopping_credit_request')." ADD "."`createtime` int(11) UNSIGNED NOT NULL DEFAULT '0'".";";

$tables['credit_request']['indexes']['id']="`id` (`id`)";