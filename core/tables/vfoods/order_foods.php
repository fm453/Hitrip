<?php
$tables['order_foods']=array(
	'name'=>'订单产品',
	'type'=>array("foods","vfoods","system","all")
);
$tables['order_foods']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_vfoods_order_foods')." (`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`))ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='订单产品' ;";

$tables['order_foods']['columns']['id']         ="ALTER TABLE ".tablename('fm453_vfoods_order_foods')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['order_foods']['columns']['uniacid']    ="ALTER TABLE ".tablename('fm453_vfoods_order_foods')." ADD "."`uniacid` int(11) UNSIGNED NOT NULL COMMENT '所属公众账号'".";";
$tables['order_foods']['columns']['orderid']    ="ALTER TABLE ".tablename('fm453_vfoods_order_foods')." ADD "."`orderid` int(10) UNSIGNED NOT NULL COMMENT ''".";";
$tables['order_foods']['columns']['foodsid']    ="ALTER TABLE ".tablename('fm453_vfoods_order_foods')." ADD "."`foodsid` int(10) UNSIGNED NOT NULL COMMENT ''".";";
$tables['order_foods']['columns']['total']      ="ALTER TABLE ".tablename('fm453_vfoods_order_foods')." ADD "."`total` int(10) UNSIGNED NOT NULL COMMENT ''".";";
$tables['order_foods']['columns']['createtime'] ="ALTER TABLE ".tablename('fm453_vfoods_order_foods')." ADD "."`createtime` int(10) UNSIGNED NOT NULL COMMENT ''".";";

$tables['order_foods']['indexes']['id']        ="`id` (`id`)";
$tables['order_foods']['indexes']['uniacid']   ="`uniacid` (`uniacid`)";
$tables['order_foods']['indexes']['orderid']   ="`orderid` (`orderid`)";
$tables['order_foods']['indexes']['foodsid']   ="`foodsid` (`foodsid`)";
$tables['order_foods']['indexes']['from_user'] ="`from_user` (`from_user`)";

?>