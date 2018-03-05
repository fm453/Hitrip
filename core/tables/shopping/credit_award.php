<?php
$tables['credit_award']=array(
	'name'=>'积分商城',
	'type'=>array("goods","system","all")
);
$tables['credit_award']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_shopping_credit_award')." ( `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`)) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;";

$tables['credit_award']['columns']['id']          ="ALTER TABLE ".tablename('fm453_shopping_credit_award')." CHANGE `award_id` "."`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT".";";
$tables['credit_award']['columns']['uniacid']     ="ALTER TABLE ".tablename('fm453_shopping_credit_award')." ADD "."`uniacid` int(11) UNSIGNED NOT NULL COMMENT '所属公众账号'".";";
$tables['credit_award']['columns']['title']       ="ALTER TABLE ".tablename('fm453_shopping_credit_award')." ADD "."`title` varchar(50) NOT NULL".";";
$tables['credit_award']['columns']['logo']        ="ALTER TABLE ".tablename('fm453_shopping_credit_award')." ADD "."`logo` varchar(255) NOT NULL".";";
$tables['credit_award']['columns']['amount']      ="ALTER TABLE ".tablename('fm453_shopping_credit_award')." ADD "."`amount` int(11) NOT NULL DEFAULT '0'".";";
$tables['credit_award']['columns']['deadline']    ="ALTER TABLE ".tablename('fm453_shopping_credit_award')." ADD "."`deadline` datetime NOT NULL".";";
$tables['credit_award']['columns']['credit_cost'] ="ALTER TABLE ".tablename('fm453_shopping_credit_award')." ADD "."`credit_cost` int(11) NOT NULL DEFAULT '0'".";";
$tables['credit_award']['columns']['price']       ="ALTER TABLE ".tablename('fm453_shopping_credit_award')." ADD "."`price` int(11) NOT NULL DEFAULT '100'".";";
$tables['credit_award']['columns']['content']     ="ALTER TABLE ".tablename('fm453_shopping_credit_award')." ADD "."`content` text NOT NULL".";";
$tables['credit_award']['columns']['createtime']  ="ALTER TABLE ".tablename('fm453_shopping_credit_award')." ADD "."`createtime` int(10) NOT NULL".";";

$tables['credit_award']['indexes']['id']="`id` (`id`)";