<?php
$tables['ppt']=array(
	'name'=>'商城各分类关联的幻灯片',
	'type'=>array("system","all")
);
$tables['ppt']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_shopping_ppt')." (`id` int(11) NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`)) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT='商城各分类关联的幻灯片';";

$tables['ppt']['columns']['id']           ="ALTER TABLE ".tablename('fm453_shopping_ppt')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['ppt']['columns']['uniacid']      ="ALTER TABLE ".tablename('fm453_shopping_ppt')." ADD "."`uniacid` int(11) DEFAULT '0' COMMENT '所属公众账号'".";";
$tables['ppt']['columns']['pcate']        ="ALTER TABLE ".tablename('fm453_shopping_ppt')." ADD "."`pcate` int(10) NOT NULL COMMENT '上级分类'".";";
$tables['ppt']['columns']['ccate']        ="ALTER TABLE ".tablename('fm453_shopping_ppt')." ADD "."`ccate` int(10) DEFAULT NULL COMMENT '当前分类'".";";
$tables['ppt']['columns']['advname']      ="ALTER TABLE ".tablename('fm453_shopping_ppt')." ADD "."`advname` varchar(50) DEFAULT NULL".";";
$tables['ppt']['columns']['link']         ="ALTER TABLE ".tablename('fm453_shopping_ppt')." ADD "."`link` varchar(255) NOT NULL DEFAULT ''".";";
$tables['ppt']['columns']['thumb']        ="ALTER TABLE ".tablename('fm453_shopping_ppt')." ADD "."`thumb` varchar(255) DEFAULT ''".";";
$tables['ppt']['columns']['displayorder'] ="ALTER TABLE ".tablename('fm453_shopping_ppt')." ADD "."`displayorder` int(11) DEFAULT '0'".";";
$tables['ppt']['columns']['enabled']      ="ALTER TABLE ".tablename('fm453_shopping_ppt')." ADD "."`enabled` int(11) DEFAULT '0'".";";
$tables['ppt']['columns']['viewcount']    ="ALTER TABLE ".tablename('fm453_shopping_ppt')." ADD "."`viewcount` int(11) NOT NULL DEFAULT '0' COMMENT '浏览量'".";";
$tables['ppt']['columns']['clickcount']   ="ALTER TABLE ".tablename('fm453_shopping_ppt')." ADD "."`clickcount` int(11) NOT NULL DEFAULT '0' COMMENT '点击量'".";";
$tables['ppt']['columns']['deleted']      ="ALTER TABLE ".tablename('fm453_shopping_ppt')." ADD "."`deleted` tinyint(1) DEFAULT '0' COMMENT '是否删除，1是0否'".";";

$tables['ppt']['indexes']['id']      ="`id` (`id`)";
$tables['ppt']['indexes']['uniacid'] ="`uniacid` (`uniacid`)";
$tables['ppt']['indexes']['enabled'] ="`enabled` (`enabled`)";
$tables['ppt']['indexes']['deleted'] ="`deleted` (`deleted`)";
//结束
