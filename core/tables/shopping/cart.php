<?php
$tables['cart']=array(
	'name'=>'购物车',
	'type'=>array("goods","member","system","all")
);
$tables['cart']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_shopping_cart')." (`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`)) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;";

$tables['cart']['columns']['id']          ="ALTER TABLE ".tablename('fm453_shopping_cart')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['cart']['columns']['uniacid']     ="ALTER TABLE ".tablename('fm453_shopping_cart')." ADD "."`uniacid` int(11) UNSIGNED NOT NULL COMMENT '所属公众账号'".";";
$tables['cart']['columns']['goodsid']     ="ALTER TABLE ".tablename('fm453_shopping_cart')." ADD "."`goodsid` int(11) NOT NULL".";";
$tables['cart']['columns']['goodstype']   ="ALTER TABLE ".tablename('fm453_shopping_cart')." ADD "."`goodstype` tinyint(1) NOT NULL DEFAULT '1'".";";
$tables['cart']['columns']['from_user']   ="ALTER TABLE ".tablename('fm453_shopping_cart')." ADD "."`from_user` varchar(50) NOT NULL".";";
$tables['cart']['columns']['uid']         ="ALTER TABLE ".tablename('fm453_shopping_cart')." ADD "."`uid` int(11) NOT NULL".";";
$tables['cart']['columns']['total']       ="ALTER TABLE ".tablename('fm453_shopping_cart')." ADD "."`total` int(10) UNSIGNED NOT NULL".";";
$tables['cart']['columns']['optionid']    ="ALTER TABLE ".tablename('fm453_shopping_cart')." ADD "."`optionid` int(10) DEFAULT '0'".";";
$tables['cart']['columns']['marketprice'] ="ALTER TABLE ".tablename('fm453_shopping_cart')." ADD "."`marketprice` decimal(10,2) DEFAULT '0.00'".";";

$tables['cart']['indexes']['id']        ="`id` (`id`)";
$tables['cart']['indexes']['uniacid']   ="`uniacid` (`uniacid`)";
$tables['cart']['indexes']['goodsid']   ="`goodsid` (`goodsid`)";
$tables['cart']['indexes']['goodstype'] ="`goodstype` (`goodstype`)";
$tables['cart']['indexes']['uid']       ="`uid` (`uid`)";
$tables['cart']['indexes']['from_user'] ="`from_user` (`from_user`)";