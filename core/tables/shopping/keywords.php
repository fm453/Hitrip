<?php
$tables['logs']=array(
	'name'=>'操作记录',
	'type'=>array("system","all")
);
$tables['logs']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_shopping_logs')." (`id` int(11) NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`))ENGINE=MyISAM AUTO_INCREMENT=0  DEFAULT CHARSET=utf8 COMMENT='系统操作日志记录表' ;";

$tables['logs']['columns']['id']         ="ALTER TABLE ".tablename('fm453_shopping_logs')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['logs']['columns']['platid']     ="ALTER TABLE ".tablename('fm453_shopping_logs')." ADD "."`platid` int(11) NOT NULL COMMENT '平台编号'".";";
$tables['logs']['columns']['uniacid']    ="ALTER TABLE ".tablename('fm453_shopping_logs')." ADD "."`uniacid` int(11) NOT NULL COMMENT '公众号id'".";";
$tables['logs']['columns']['uid']        ="ALTER TABLE ".tablename('fm453_shopping_logs')." ADD "."`uid` int(11) NOT NULL COMMENT '后台操作用户的id'".";";
$tables['logs']['columns']['fanid']      ="ALTER TABLE ".tablename('fm453_shopping_logs')." ADD "."`fanid` int(11) NOT NULL COMMENT '前台操作用户的id'".";";
$tables['logs']['columns']['openid']     ="ALTER TABLE ".tablename('fm453_shopping_logs')." ADD "."`openid` varchar(50) NOT NULL COMMENT '前台用户的Openid'".";";
$tables['logs']['columns']['tablename']  ="ALTER TABLE ".tablename('fm453_shopping_logs')." ADD "."`tablename` varchar(255) NOT NULL COMMENT '操作的表名'".";";
$tables['logs']['columns']['sn']         ="ALTER TABLE ".tablename('fm453_shopping_logs')." ADD "."`sn` int(11) NOT NULL COMMENT '表记录编号，默认取对应表记录ID'".";";
$tables['logs']['columns']['do']         ="ALTER TABLE ".tablename('fm453_shopping_logs')." ADD "."`do` varchar(50) NOT NULL COMMENT '对订单的操作行为,如view,comment,edit等'".";";
$tables['logs']['columns']['details']    ="ALTER TABLE ".tablename('fm453_shopping_logs')." ADD "."`details` text NOT NULL".";";
$tables['logs']['columns']['createtime'] ="ALTER TABLE ".tablename('fm453_shopping_logs')." ADD "."`createtime` int(11) NOT NULL;".";";

$tables['logs']['indexes']['id']="`id` (`id`)";