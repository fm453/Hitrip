<?php
$tables['goods_tplparam']=array(
	'name'=>'产品具体模型参数值',
	'type'=>array("goods","system","all")
);
$tables['goods_tplparam']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_shopping_goods_tplparam')." (`id` int(11) NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`)) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT='产品模型具体参数值';";

$tables['goods_tplparam']['columns']['id']           ="ALTER TABLE ".tablename('fm453_shopping_goods_tplparam')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['goods_tplparam']['columns']['gsn']          ="ALTER TABLE ".tablename('fm453_shopping_goods_tplparam')." ADD "."`gsn` int(10) DEFAULT NULL COMMENT '对应产品系编号'".";";
$tables['goods_tplparam']['columns']['goodstpl']     ="ALTER TABLE ".tablename('fm453_shopping_goods_tplparam')." ADD "."`goodstpl` varchar(50) NOT NULL COMMENT '所属产品模型'".";";
$tables['goods_tplparam']['columns']['tplparam']     ="ALTER TABLE ".tablename('fm453_shopping_goods_tplparam')." ADD "."`tplparam` varchar(255) NOT NULL COMMENT '对应产品模型参数项'".";";
$tables['goods_tplparam']['columns']['title']        ="ALTER TABLE ".tablename('fm453_shopping_goods_tplparam')." ADD "."`title` varchar(50) DEFAULT NULL".";";
$tables['goods_tplparam']['columns']['value']        ="ALTER TABLE ".tablename('fm453_shopping_goods_tplparam')." ADD "."`value` text".";";
$tables['goods_tplparam']['columns']['displayorder'] ="ALTER TABLE ".tablename('fm453_shopping_goods_tplparam')." ADD "."`displayorder` int(11) DEFAULT '0'".";";
$tables['goods_tplparam']['columns']['deleted']      ="ALTER TABLE ".tablename('fm453_shopping_goods_tplparam')." ADD "."`deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除，1是0否'".";";
$tables['goods_tplparam']['columns']['statuscode']   ="ALTER TABLE ".tablename('fm453_shopping_goods_tplparam')." ADD "."`statuscode` tinyint(3) NOT NULL COMMENT '状态码'".";";

$tables['goods_tplparam']['indexes']['id']="`id` (`id`)";