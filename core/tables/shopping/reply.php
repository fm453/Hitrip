<?php
$tables['reply']=array(
	'name'=>'回复规则',
	'type'=>array("system","all")
);
$tables['reply']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_shopping_reply')." (`id` int(11) NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`))ENGINE=MyISAM AUTO_INCREMENT=0  DEFAULT CHARSET=utf8 COMMENT='系统回复记录表' ;";

$tables['reply']['columns']['id']         ="ALTER TABLE ".tablename('fm453_shopping_reply')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['reply']['columns']['uniacid']    ="ALTER TABLE ".tablename('fm453_shopping_reply')." ADD "."`uniacid` int(11) NOT NULL".";";
$tables['reply']['columns']['rid']        ="ALTER TABLE ".tablename('fm453_shopping_reply')." ADD "."`rid` int(11) NOT NULL".";";
$tables['reply']['columns']['gsn']        ="ALTER TABLE ".tablename('fm453_shopping_reply')." ADD "."`gsn` int(11) NOT NULL COMMENT '产品编号'".";";
$tables['reply']['columns']['partnersn']  ="ALTER TABLE ".tablename('fm453_shopping_reply')." ADD "."`partnersn` int(11) NOT NULL COMMENT '合作伙伴编号'".";";
$tables['reply']['columns']['deleted']    ="ALTER TABLE ".tablename('fm453_shopping_reply')." ADD "."`deleted` tinyint(1) NOT NULL".";";
$tables['reply']['columns']['createtime'] ="ALTER TABLE ".tablename('fm453_shopping_reply')." ADD "."`createtime` int(11) NOT NULL".";";
$tables['reply']['columns']['statuscode'] ="ALTER TABLE ".tablename('fm453_shopping_reply')." ADD "."`statuscode` tinyint(3) NOT NULL".";";

$tables['reply']['indexes']['id']="`id` (`id`)";