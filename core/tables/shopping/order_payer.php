<?php
$tables['order_payer']=array(
	'name'=>'商城订单支付者记录',
	'type'=>array("goods","order","system","all")
);
$tables['order_payer']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_shopping_order_payer')." (`id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;";

$tables['order_payer']['columns']['id']         ="ALTER TABLE ".tablename('fm453_shopping_order_payer')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['order_payer']['columns']['orderid']    ="ALTER TABLE ".tablename('fm453_shopping_order_payer')." ADD "."`orderid` int(11) NOT NULL COMMENT '关联订单ID'".";";
$tables['order_payer']['columns']['ordersn']    ="ALTER TABLE ".tablename('fm453_shopping_order_payer')." ADD "."`ordersn` int(11) NOT NULL COMMENT '关联订单编号sn'".";";
$tables['order_payer']['columns']['uniacid']    ="ALTER TABLE ".tablename('fm453_shopping_order_payer')." ADD "."`uniacid` int(11) NOT NULL COMMENT '公众号id'".";";
$tables['order_payer']['columns']['fanid']      ="ALTER TABLE ".tablename('fm453_shopping_order_payer')." ADD "."`fanid` int(11) NOT NULL COMMENT '支付者的id'".";";
$tables['order_payer']['columns']['openid']     ="ALTER TABLE ".tablename('fm453_shopping_order_payer')." ADD "."`openid` int(11) NOT NULL COMMENT '支付者的Openid'".";";
$tables['order_payer']['columns']['payid']      ="ALTER TABLE ".tablename('fm453_shopping_order_payer')." ADD "."`payid` int(11) NOT NULL COMMENT '支付日志id'".";";
$tables['order_payer']['columns']['createtime'] ="ALTER TABLE ".tablename('fm453_shopping_order_payer')." ADD "."`createtime` int(11) NOT NULL COMMENT '记录生成时间'".";";


$tables['order_payer']['indexes']['id']       ="`id` (`id`)";
$tables['order_payer']['indexes']['uniaciid'] ="`uniacid` (`uniacid`)";

