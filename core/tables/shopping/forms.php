<?php
$tables['forms']=array(
	'name'=>'万能表单',
	'type'=>array("order","member","system","all")
);
$tables['forms']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_shopping_forms')." (`id` int(11) NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

$tables['forms']['columns']['id']                   ="ALTER TABLE ".tablename('fm453_shopping_forms')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['forms']['columns']['uniacid']              ="ALTER TABLE ".tablename('fm453_shopping_forms')." ADD "."`uniacid` int(11) DEFAULT '0'".";";
$tables['forms']['columns']['orderid']              ="ALTER TABLE ".tablename('fm453_shopping_forms')." ADD "."`orderid` int(11) DEFAULT '0'".";";
$tables['forms']['columns']['goodsid']              ="ALTER TABLE ".tablename('fm453_shopping_forms')." ADD "."`goodsid` int(11) DEFAULT '0'".";";
$tables['forms']['columns']['openid']               ="ALTER TABLE ".tablename('fm453_shopping_forms')." ADD "."`openid` varchar(50) DEFAULT ''".";";
$tables['forms']['columns']['nickname']             ="ALTER TABLE ".tablename('fm453_shopping_forms')." ADD "."`nickname` varchar(50) DEFAULT ''".";";
$tables['forms']['columns']['headimgurl']           ="ALTER TABLE ".tablename('fm453_shopping_forms')." ADD "."`headimgurl` varchar(255) DEFAULT ''".";";
$tables['forms']['columns']['level']                ="ALTER TABLE ".tablename('fm453_shopping_forms')." ADD "."`level` tinyint(3) DEFAULT '0'".";";
$tables['forms']['columns']['content']              ="ALTER TABLE ".tablename('fm453_shopping_forms')." ADD "."`content` varchar(255) DEFAULT ''".";";
$tables['forms']['columns']['images']               ="ALTER TABLE ".tablename('fm453_shopping_forms')." ADD "."`images` text".";";
$tables['forms']['columns']['append_content']       ="ALTER TABLE ".tablename('fm453_shopping_forms')." ADD "."`append_content` varchar(255) DEFAULT ''".";";
$tables['forms']['columns']['append_images']        ="ALTER TABLE ".tablename('fm453_shopping_forms')." ADD "."`append_images` text".";";
$tables['forms']['columns']['reply_content']        ="ALTER TABLE ".tablename('fm453_shopping_forms')." ADD "."`reply_content` varchar(255) DEFAULT ''".";";
$tables['forms']['columns']['reply_images']         ="ALTER TABLE ".tablename('fm453_shopping_forms')." ADD "."`reply_images` text".";";
$tables['forms']['columns']['append_reply_content'] ="ALTER TABLE ".tablename('fm453_shopping_forms')." ADD "."`append_reply_content` varchar(255) DEFAULT ''".";";
$tables['forms']['columns']['append_reply_images']  ="ALTER TABLE ".tablename('fm453_shopping_forms')." ADD "."`append_reply_images` text".";";
$tables['forms']['columns']['createtime']           ="ALTER TABLE ".tablename('fm453_shopping_forms')." ADD "."`createtime` int(11) DEFAULT '0'".";";
$tables['forms']['columns']['deleted']              ="ALTER TABLE ".tablename('fm453_shopping_forms')." ADD "."`deleted` tinyint(3) DEFAULT '0'".";";

$tables['forms']['indexes']['id']="`id` (`id`)";