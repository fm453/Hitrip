<?php
$tables['member_favorite']=array(
	'name'=>'会员收藏列表',
	'type'=>array("goods","member","system","all")
);
$tables['member_favorite']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_shopping_member_favorite')." (`id` int(11) NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

$tables['member_favorite']['columns']['id']         ="ALTER TABLE ".tablename('fm453_shopping_member_favorite')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['member_favorite']['columns']['uniacid']    ="ALTER TABLE ".tablename('fm453_shopping_member_favorite')." ADD "."`uniacid` int(11) DEFAULT '0'".";";
$tables['member_favorite']['columns']['goodsid']    ="ALTER TABLE ".tablename('fm453_shopping_member_favorite')." ADD "."`goodsid` int(10) DEFAULT '0'".";";
$tables['member_favorite']['columns']['openid']     ="ALTER TABLE ".tablename('fm453_shopping_member_favorite')." ADD "."`openid` varchar(50) DEFAULT ''".";";
$tables['member_favorite']['columns']['createtime'] ="ALTER TABLE ".tablename('fm453_shopping_member_favorite')." ADD "."`createtime` int(11) DEFAULT '0'".";";
$tables['member_favorite']['columns']['deleted']    ="ALTER TABLE ".tablename('fm453_shopping_member_favorite')." ADD "."`deleted` tinyint(1) DEFAULT '0'".";";

$tables['member_favorite']['indexes']['id']="`id` (`id`)";