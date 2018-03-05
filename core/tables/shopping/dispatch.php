<?php
$tables['dispatch']=array(
	'name'=>'配送方式',
	'type'=>array("system","shopping","all")
);
$tables['dispatch']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_shopping_dispatch')." (`id` int(11) NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`)) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;";

$tables['dispatch']['columns']['id']           ="ALTER TABLE ".tablename('fm453_shopping_dispatch')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['dispatch']['columns']['uniacid']      ="ALTER TABLE ".tablename('fm453_shopping_dispatch')." ADD "."`uniacid` int(11) DEFAULT '0' COMMENT '所属公众账号'".";";
$tables['dispatch']['columns']['dispatchname'] ="ALTER TABLE ".tablename('fm453_shopping_dispatch')." ADD "."`dispatchname` varchar(50) DEFAULT ''".";";
$tables['dispatch']['columns']['dispatchtype'] ="ALTER TABLE ".tablename('fm453_shopping_dispatch')." ADD "."`dispatchtype` int(11) DEFAULT '0'".";";
$tables['dispatch']['columns']['displayorder'] ="ALTER TABLE ".tablename('fm453_shopping_dispatch')." ADD "."`displayorder` int(11) DEFAULT '0'".";";
$tables['dispatch']['columns']['firstprice']   ="ALTER TABLE ".tablename('fm453_shopping_dispatch')." ADD "."`firstprice` decimal(10,2) DEFAULT '0.00'".";";
$tables['dispatch']['columns']['secondprice']  ="ALTER TABLE ".tablename('fm453_shopping_dispatch')." ADD "."`secondprice` decimal(10,2) DEFAULT '0.00'".";";
$tables['dispatch']['columns']['firstweight']  ="ALTER TABLE ".tablename('fm453_shopping_dispatch')." ADD "."`firstweight` int(11) DEFAULT '0'".";";
$tables['dispatch']['columns']['secondweight'] ="ALTER TABLE ".tablename('fm453_shopping_dispatch')." ADD "."`secondweight` int(11) DEFAULT '0'".";";
$tables['dispatch']['columns']['express']      ="ALTER TABLE ".tablename('fm453_shopping_dispatch')." ADD "."`express` int(11) DEFAULT '0'".";";
$tables['dispatch']['columns']['description']  ="ALTER TABLE ".tablename('fm453_shopping_dispatch')." ADD "."`description` text".";";
$tables['dispatch']['columns']['ischecked']    ="ALTER TABLE ".tablename('fm453_shopping_dispatch')." ADD "."`ischecked` tinyint(3) NOT NULL COMMENT '是否通过审核,-1待0否1是'".";";
$tables['dispatch']['columns']['isavailable']  ="ALTER TABLE ".tablename('fm453_shopping_dispatch')." ADD "."`isavailable` tinyint(3) NOT NULL COMMENT '是否有效，0否1是'".";";
$tables['dispatch']['columns']['deleted']      ="ALTER TABLE ".tablename('fm453_shopping_dispatch')." ADD "."`deleted` tinyint(3) NOT NULL COMMENT '是否删除，0否1是'".";";

$tables['dispatch']['indexes']['id']="`id` (`id`)";