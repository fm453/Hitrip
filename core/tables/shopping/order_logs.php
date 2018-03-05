<?php
$tables['order_logs']=array(
	'name'=>'订单操作记录',
	'type'=>array("order","system","all")
);
$tables['order_logs']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_shopping_order_logs')." (`id` int(11) NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='订单操作记录表';";

$tables['order_logs']['columns']['id']         ="ALTER TABLE ".tablename('fm453_shopping_order_logs')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['order_logs']['columns']['orderid']    ="ALTER TABLE ".tablename('fm453_shopping_order_logs')." ADD "."`orderid` int(10) UNSIGNED NOT NULL".";";
$tables['order_logs']['columns']['ordersn']    ="ALTER TABLE ".tablename('fm453_shopping_order_logs')." ADD "."`ordersn` int(11) NOT NULL COMMENT '关联订单编号sn'".";";
$tables['order_logs']['columns']['uniacid']    ="ALTER TABLE ".tablename('fm453_shopping_order_logs')." ADD "."`uniacid` int(11) NOT NULL COMMENT '公众号id'".";";
$tables['order_logs']['columns']['uid']        ="ALTER TABLE ".tablename('fm453_shopping_order_logs')." ADD "."`uid` int(10) NOT NULL COMMENT '后台操作用户的id'".";";
$tables['order_logs']['columns']['fanid']      ="ALTER TABLE ".tablename('fm453_shopping_order_logs')." ADD "."`fanid` int(11) NOT NULL COMMENT '前台操作用户的id'".";";
$tables['order_logs']['columns']['openid']     ="ALTER TABLE ".tablename('fm453_shopping_order_logs')." ADD "."`openid` int(11) NOT NULL COMMENT '前台用户的Openid'".";";
$tables['order_logs']['columns']['do']         ="ALTER TABLE ".tablename('fm453_shopping_order_logs')." ADD "."`do` varchar(50) NOT NULL COMMENT '对订单的操作行为,如view,comment,edit等'".";";
$tables['order_logs']['columns']['details']    ="ALTER TABLE ".tablename('fm453_shopping_order_logs')." ADD "."`details` text NOT NULL".";";
$tables['order_logs']['columns']['createtime'] ="ALTER TABLE ".tablename('fm453_shopping_order_logs')." ADD "."`createtime` int(11) NOT NULL COMMENT '记录生成时间'".";";

$tables['order_logs']['indexes']['id']="`id` (`id`)";
