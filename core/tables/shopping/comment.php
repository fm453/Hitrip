<?php
$tables['comment']=array(
	'name'=>'评论列表，包含对商户、对订单、对产品、对文章等各方面的评论及评论回复',
	'type'=>array("goods","partner","order","member","article","comment","system","all")
);
$tables['comment']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_shopping_comment')." (`id` int(11) NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

$tables['comment']['columns']['id']                   ="ALTER TABLE ".tablename('fm453_shopping_comment')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['comment']['columns']['uniacid']              ="ALTER TABLE ".tablename('fm453_shopping_comment')." ADD "."`uniacid` int(11) DEFAULT '0'".";";
$tables['comment']['columns']['commentid']            ="ALTER TABLE ".tablename('fm453_shopping_comment')." ADD "."`commentid` int(11) DEFAULT '0' COMMENT '关联评论id'".";";
$tables['comment']['columns']['replyid']              ="ALTER TABLE ".tablename('fm453_shopping_comment')." ADD "."`replyid` int(11) DEFAULT '0' COMMENT '关联回复评论id'".";";
$tables['comment']['columns']['orderid']              ="ALTER TABLE ".tablename('fm453_shopping_comment')." ADD "."`orderid` int(11) DEFAULT '0'".";";
$tables['comment']['columns']['goodsid']              ="ALTER TABLE ".tablename('fm453_shopping_comment')." ADD "."`goodsid` int(11) DEFAULT '0'".";";
$tables['comment']['columns']['articleid']            ="ALTER TABLE ".tablename('fm453_shopping_comment')." ADD "."`articleid` int(11) DEFAULT '0'".";";
$tables['comment']['columns']['uid']                  ="ALTER TABLE ".tablename('fm453_shopping_comment')." ADD "."`uid` int(11) DEFAULT '0'".";";
$tables['comment']['columns']['openid']               ="ALTER TABLE ".tablename('fm453_shopping_comment')." ADD "."`openid` varchar(50) DEFAULT ''".";";
$tables['comment']['columns']['nickname']             ="ALTER TABLE ".tablename('fm453_shopping_comment')." ADD "."`nickname` varchar(50) DEFAULT ''".";";
$tables['comment']['columns']['headimgurl']           ="ALTER TABLE ".tablename('fm453_shopping_comment')." ADD "."`headimgurl` varchar(255) DEFAULT ''".";";
$tables['comment']['columns']['level']                ="ALTER TABLE ".tablename('fm453_shopping_comment')." ADD "."`level` tinyint(3) DEFAULT '0'".";";
$tables['comment']['columns']['content']              ="ALTER TABLE ".tablename('fm453_shopping_comment')." ADD "."`content` varchar(255) DEFAULT ''".";";
$tables['comment']['columns']['images']               ="ALTER TABLE ".tablename('fm453_shopping_comment')." ADD "."`images` text".";";
$tables['comment']['columns']['attachments']          ="ALTER TABLE ".tablename('fm453_shopping_comment')." ADD "."`attachments` text".";";
$tables['comment']['columns']['append_content']       ="ALTER TABLE ".tablename('fm453_shopping_comment')." ADD "."`append_content` varchar(255) DEFAULT ''".";";
$tables['comment']['columns']['append_images']        ="ALTER TABLE ".tablename('fm453_shopping_comment')." ADD "."`append_images` text".";";
$tables['comment']['columns']['reply_content']        ="ALTER TABLE ".tablename('fm453_shopping_comment')." ADD "."`reply_content` varchar(255) DEFAULT ''".";";
$tables['comment']['columns']['reply_images']         ="ALTER TABLE ".tablename('fm453_shopping_comment')." ADD "."`reply_images` text".";";
$tables['comment']['columns']['append_reply_content'] ="ALTER TABLE ".tablename('fm453_shopping_comment')." ADD "."`append_reply_content` varchar(255) DEFAULT ''".";";
$tables['comment']['columns']['append_reply_images']  ="ALTER TABLE ".tablename('fm453_shopping_comment')." ADD "."`append_reply_images` text".";";
$tables['comment']['columns']['createtime']           ="ALTER TABLE ".tablename('fm453_shopping_comment')." ADD "."`createtime` int(11) DEFAULT '0'".";";
$tables['comment']['columns']['addons']               ="ALTER TABLE ".tablename('fm453_shopping_comment')." ADD "."`addons` text COMMENT '评论的原始数据'".";";
$tables['comment']['columns']['deleted']              ="ALTER TABLE ".tablename('fm453_shopping_comment')." ADD "."`deleted` tinyint(3) DEFAULT '0'".";";

$tables['comment']['indexes']['id']         ="`id` (`id`)";
$tables['comment']['indexes']['uniacid']    ="`uniacid` (`uniacid`)";
$tables['comment']['indexes']['goodsid']    ="`goodsid` (`goodsid`)";
$tables['comment']['indexes']['orderid']    ="`orderid` (`orderid`)";
$tables['comment']['indexes']['articleid']  ="`articleid` (`articleid`)";
$tables['comment']['indexes']['uid']        ="`uid` (`uid`)";
$tables['comment']['indexes']['createtime'] ="`createtime` (`createtime`)";
$tables['comment']['indexes']['deleted']    ="`deleted` (`deleted`)";
$tables['comment']['indexes']['commentid']  ="`commentid` (`commentid`)";
$tables['comment']['indexes']['replyid']    ="`replyid` (`replyid`)";
