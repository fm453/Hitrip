<?php
$tables['cart']=array(
	'name'=>'购物车',
	'type'=>array("foods","vfoods","system","all")
);
$tables['cart']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_vfoods_cart')." (`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`))ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='购物车' ;";

$tables['cart']['columns']['id']        ="ALTER TABLE ".tablename('fm453_vfoods_cart')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['cart']['columns']['uniacid']   ="ALTER TABLE ".tablename('fm453_vfoods_cart')." ADD "."`uniacid` int(11) UNSIGNED NOT NULL COMMENT '所属公众账号'".";";
$tables['cart']['columns']['sn']        ="ALTER TABLE ".tablename('fm453_vfoods_cart')." ADD "."`sn` int(11) NOT NULL COMMENT '关联菜品编号'".";";
$tables['cart']['columns']['from_user'] ="ALTER TABLE ".tablename('fm453_vfoods_cart')." ADD "."`from_user` varchar(50) NOT NULL COMMENT '来源用户'".";";
$tables['cart']['columns']['ordertype'] ="ALTER TABLE ".tablename('fm453_vfoods_cart')." ADD "."`ordertype` tinyint(1) NOT NULL COMMENT '1外卖，2堂食，3自取'".";";
$tables['cart']['columns']['total']     ="ALTER TABLE ".tablename('fm453_vfoods_cart')." ADD "."`total` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '数量'".";";

$tables['cart']['indexes']['id']        ="`id` (`id`)";
$tables['cart']['indexes']['sn']        ="`sn` (`sn`)";
$tables['cart']['indexes']['from_user'] ="`from_user` (`from_user`)";
$tables['cart']['indexes']['ordertype'] ="`ordertype` (`ordertype`)";
$tables['cart']['indexes']['uniacid']   ="`uniacid` (`uniacid`)";

?>
