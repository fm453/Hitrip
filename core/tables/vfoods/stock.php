<?php
$tables['stock']=array(
	'name'=>'菜品库存',
	'type'=>array("foods","vfoods","system","all")
);
$tables['stock']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_vfoods_stock')." (`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`))ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='菜品' ;";

$tables['stock']['columns']['id']         ="ALTER TABLE ".tablename('fm453_vfoods_stock')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['stock']['columns']['uniacid']    ="ALTER TABLE ".tablename('fm453_vfoods_stock')." ADD "."`uniacid` int(11) UNSIGNED NOT NULL COMMENT '所属公众账号'".";";
$tables['stock']['columns']['foodsid']    ="ALTER TABLE ".tablename('fm453_vfoods_stock')." ADD "."`foodsid` int(11) UNSIGNED NOT NULL COMMENT '关联菜品ID'".";";
$tables['stock']['columns']['total']       ="ALTER TABLE ".tablename('fm453_vfoods_stock')." ADD "."`total` int(10) UNSIGNED NOT NULL COMMENT '预订量'".";";
$tables['stock']['columns']['starttime']       ="ALTER TABLE ".tablename('fm453_vfoods_stock')." ADD "."`starttime` int(11) UNSIGNED NOT NULL COMMENT '开始时间'".";";
$tables['stock']['columns']['endtime']       ="ALTER TABLE ".tablename('fm453_vfoods_stock')." ADD "."`endtime` int(11) UNSIGNED NOT NULL COMMENT '结束时间'".";";
$tables['stock']['columns']['createtime'] ="ALTER TABLE ".tablename('fm453_vfoods_stock')." ADD "."`createtime` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT ''".";";

$tables['stock']['indexes']['id']      ="`id` (`id`)";
$tables['stock']['indexes']['uniacid'] ="`uniacid` (`uniacid`)";
$tables['stock']['indexes']['foodsid']   ="`foodsid` (`foodsid`)";

?>