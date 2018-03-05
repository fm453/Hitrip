<?php
$tables['price']=array(
	'name'=>'价格表',
	'type'=>array("system", 'goods', "all")
);
$tables['price']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_shopping_price')." (`id` int(11) NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`)) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT='价格体系表';";

$tables['price']['columns']['id']          ="ALTER TABLE ".tablename('fm453_shopping_price')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['price']['columns']['ruleid']      ="ALTER TABLE ".tablename('fm453_shopping_price')." ADD "."`ruleid` int(11) NOT NULL".";";
$tables['price']['columns']['goodsid']     ="ALTER TABLE ".tablename('fm453_shopping_price')." ADD "."`goodsid` int(11) NOT NULL".";";
$tables['price']['columns']['optionid']    ="ALTER TABLE ".tablename('fm453_shopping_price')." ADD "."`optionid` int(11) NOT NULL".";";
$tables['price']['columns']['roomid']      ="ALTER TABLE ".tablename('fm453_shopping_price')." ADD "."`roomid` int(11) NOT NULL".";";
$tables['price']['columns']['hotelid']     ="ALTER TABLE ".tablename('fm453_shopping_price')." ADD "."`hotelid` int(11) NOT NULL".";";
$tables['price']['columns']['partnerid']   ="ALTER TABLE ".tablename('fm453_shopping_price')." ADD "."`partnerid` int(11) NOT NULL".";";
$tables['price']['columns']['usergroupid'] ="ALTER TABLE ".tablename('fm453_shopping_price')." ADD "."`usergroupid` int(11) NOT NULL".";";
$tables['price']['columns']['timestart']   ="ALTER TABLE ".tablename('fm453_shopping_price')." ADD "."`timestart` int(11) NOT NULL".";";
$tables['price']['columns']['timeend']     ="ALTER TABLE ".tablename('fm453_shopping_price')." ADD "."`timeend` int(11) NOT NULL".";";
$tables['price']['columns']['istimelimit'] ="ALTER TABLE ".tablename('fm453_shopping_price')." ADD "."`istimelimit` tinyint(3) NOT NULL".";";
$tables['price']['columns']['statuscode']  ="ALTER TABLE ".tablename('fm453_shopping_price')." ADD "."`statuscode` tinyint(3) NOT NULL".";";
$tables['price']['columns']['marketprice'] ="ALTER TABLE ".tablename('fm453_shopping_price')." ADD "."`marketprice` decimal(10,2) DEFAULT '0.00'".";";

$tables['price']['indexes']['id']          ="`id` (`id`)";
$tables['price']['indexes']['goodsid']     ="`goodsid` (`goodsid`)";
$tables['price']['indexes']['optionid']    ="`optionid` (`optionid`)";
$tables['price']['indexes']['roomid']      ="`roomid` (`roomid`)";
$tables['price']['indexes']['hotelid']     ="`hotelid` (`hotelid`)";
$tables['price']['indexes']['partnerid']   ="`partnerid` (`partnerid`)";
$tables['price']['indexes']['usergroupid'] ="`usergroupid` (`usergroupid`)";
