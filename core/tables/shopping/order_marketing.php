<?php
$tables['order_marketing']=array(
	'name'=>'商城营销模型订单记录',
	'type'=>array("goods","order","system","all")
);
$tables['order_marketing']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_shopping_order_marketing')." (`id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;";

$tables['order_marketing']['columns']['id']         ="ALTER TABLE ".tablename('fm453_shopping_order_marketing')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['order_marketing']['columns']['orderid']    ="ALTER TABLE ".tablename('fm453_shopping_order_marketing')." ADD "."`orderid` int(11) NOT NULL COMMENT '关联订单编号id'".";";
$tables['order_marketing']['columns']['ordersn']    ="ALTER TABLE ".tablename('fm453_shopping_order_marketing')." ADD "."`ordersn` int(11) NOT NULL COMMENT '关联订单编号sn'".";";
$tables['order_marketing']['columns']['uniacid']    ="ALTER TABLE ".tablename('fm453_shopping_order_marketing')." ADD "."`uniacid` int(11) NOT NULL COMMENT '公众号id'".";";
$tables['order_marketing']['columns']['fanid']      ="ALTER TABLE ".tablename('fm453_shopping_order_marketing')." ADD "."`fanid` int(11) NOT NULL COMMENT '支付者的id'".";";
$tables['order_marketing']['columns']['openid']     ="ALTER TABLE ".tablename('fm453_shopping_order_marketing')." ADD "."`openid` int(11) NOT NULL COMMENT '支付者的Openid'".";";
$tables['order_marketing']['columns']['payid']      ="ALTER TABLE ".tablename('fm453_shopping_order_marketing')." ADD "."`payid` int(11) NOT NULL COMMENT '支付日志id'".";";
$tables['order_marketing']['columns']['createtime'] ="ALTER TABLE ".tablename('fm453_shopping_order_marketing')." ADD "."`createtime` int(11) NOT NULL COMMENT '记录生成时间'".";";


$tables['order_marketing']['indexes']['id']       ="`id` (`id`)";
$tables['order_marketing']['indexes']['uniaciid'] ="`uniacid` (`uniacid`)";
$tables['order_marketing']['indexes']['orderid']  ="`orderid` (`orderid`)";
$tables['order_marketing']['indexes']['ordersn']  ="`ordersn` (`ordersn`)";


