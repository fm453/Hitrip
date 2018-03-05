<?php
$tables['brand']=array(
	'name'=>'商城入驻品牌',
	'type'=>array("goods","partner","system","all")
);
$tables['brand']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_shopping_brand')." (`id` int(11) NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

$tables['brand']['columns']['id']           ="ALTER TABLE ".tablename('fm453_shopping_brand')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['brand']['columns']['uniacid']      ="ALTER TABLE ".tablename('fm453_shopping_brand')." ADD "."`uniacid` int(11) DEFAULT '0' COMMENT '公众号ID'".";";
$tables['brand']['columns']['pcate']        ="ALTER TABLE ".tablename('fm453_shopping_brand')." ADD "."`pcate` int(11) NOT NULL COMMENT '当前分类'".";";
$tables['brand']['columns']['ccate']        ="ALTER TABLE ".tablename('fm453_shopping_brand')." ADD "."`ccate` int(11) NOT NULL COMMENT '上级分类'".";";
$tables['brand']['columns']['typemodel']    ="ALTER TABLE ".tablename('fm453_shopping_brand')." ADD "."`typemodel` varchar(50) NOT NULL COMMENT '品牌类型,用于与产品模型对应'".";";
$tables['brand']['columns']['bname']        ="ALTER TABLE ".tablename('fm453_shopping_brand')." ADD "."`bname` varchar(255) DEFAULT '' COMMENT '品牌名称'".";";
$tables['brand']['columns']['description']  ="ALTER TABLE ".tablename('fm453_shopping_brand')." ADD "."`description` text NOT NULL COMMENT '品牌描述'".";";
$tables['brand']['columns']['displayorder'] ="ALTER TABLE ".tablename('fm453_shopping_brand')." ADD "."`displayorder` int(11) DEFAULT '0'".";";
$tables['brand']['columns']['status']       ="ALTER TABLE ".tablename('fm453_shopping_brand')." ADD "."`status` int(11) DEFAULT '0' COMMENT '品牌状态'".";";
$tables['brand']['columns']['createtime']   ="ALTER TABLE ".tablename('fm453_shopping_brand')." ADD "."`createtime` int(11) NOT NULL COMMENT '记录生成时间'".";";

$tables['brand']['indexes']['id']           ="`id` (`id`)";
$tables['brand']['indexes']['uniacid']      ="`uniacid` (`uniacid`)";
$tables['brand']['indexes']['displayorder'] ="`displayorder` (`displayorder`)";
$tables['brand']['indexes']['status']       ="`status` (`status`)";
//结束
