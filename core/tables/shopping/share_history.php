<?php
$tables['share_history']=array(
	'name'=>'分享记录',
	'type'=>array("member","system","all")
);
$tables['share_history']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_shopping_share_history')." (`id` int(11) NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

$tables['share_history']['columns']['id']         ="ALTER TABLE ".tablename('fm453_shopping_share_history')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['share_history']['columns']['uniacid']    ="ALTER TABLE ".tablename('fm453_shopping_share_history')." ADD "."`uniacid` int(11) DEFAULT NULL COMMENT '所属公众账号'".";";
$tables['share_history']['columns']['lastid']     ="ALTER TABLE ".tablename('fm453_shopping_share_history')." ADD "."`lastid` int(11) NOT NULL".";";
$tables['share_history']['columns']['shareid']    ="ALTER TABLE ".tablename('fm453_shopping_share_history')." ADD "."`shareid` int(11) NOT NULL".";";
$tables['share_history']['columns']['mid']        ="ALTER TABLE ".tablename('fm453_shopping_share_history')." ADD "."`mid` int(11) NOT NULL".";";
$tables['share_history']['columns']['from_user']  ="ALTER TABLE ".tablename('fm453_shopping_share_history')." ADD "."`from_user` varchar(50) DEFAULT NULL".";";
$tables['share_history']['columns']['share_user'] ="ALTER TABLE ".tablename('fm453_shopping_share_history')." ADD "."`share_user` varchar(50) DEFAULT NULL".";";
$tables['share_history']['columns']['do']         ="ALTER TABLE ".tablename('fm453_shopping_share_history')." ADD "."`do` varchar(50) DEFAULT NULL".";";
$tables['share_history']['columns']['ac']         ="ALTER TABLE ".tablename('fm453_shopping_share_history')." ADD "."`ac` varchar(50) DEFAULT NULL".";";
$tables['share_history']['columns']['op']         ="ALTER TABLE ".tablename('fm453_shopping_share_history')." ADD "."`op` varchar(50) DEFAULT NULL".";";
$tables['share_history']['columns']['obj_id']     ="ALTER TABLE ".tablename('fm453_shopping_share_history')." ADD "."`obj_id` varchar(50) DEFAULT NULL".";";
$tables['share_history']['columns']['obj_sn']     ="ALTER TABLE ".tablename('fm453_shopping_share_history')." ADD "."`obj_sn` varchar(50) DEFAULT NULL".";";
$tables['share_history']['columns']['url']        ="ALTER TABLE ".tablename('fm453_shopping_share_history')." ADD "."`url` text".";";
$tables['share_history']['columns']['createtime'] ="ALTER TABLE ".tablename('fm453_shopping_share_history')." ADD "."`createtime` int(11) NOT NULL".";";

$tables['share_history']['indexes']['id']      ="`id` (`id`)";
$tables['share_history']['indexes']['lastid']  ="`lastid` (`lastid`)";
$tables['share_history']['indexes']['shareid'] ="`shareid` (`shareid`)";
$tables['share_history']['indexes']['mid']     ="`mid` (`mid`)";
$tables['share_history']['indexes']['do']      ="`do` (`do`)";
$tables['share_history']['indexes']['ac']      ="`ac` (`ac`)";