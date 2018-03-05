<?php
$tables['anounce']=array(
	'name'=>'商城公告',
	'type'=>array("system","all")
);

$tables['anounce']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_shopping_anounce')." ( `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '记录号',  PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
	
$tables['anounce']['columns']['id']           ="ALTER TABLE ".tablename('fm453_shopping_anounce')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['anounce']['columns']['uniacid']      ="ALTER TABLE ".tablename('fm453_shopping_anounce')." ADD "."`uniacid` int(11) DEFAULT '0' COMMENT '公众号id'".";";
$tables['anounce']['columns']['displayorder'] ="ALTER TABLE ".tablename('fm453_shopping_anounce')." ADD "."`displayorder` int(11) DEFAULT '0' COMMENT '排序'".";";
$tables['anounce']['columns']['title']        ="ALTER TABLE ".tablename('fm453_shopping_anounce')." ADD "."`title` varchar(255) DEFAULT '' COMMENT '公告标题'".";";
$tables['anounce']['columns']['thumb']        ="ALTER TABLE ".tablename('fm453_shopping_anounce')." ADD "."`thumb` varchar(255) DEFAULT '' COMMENT '公告缩略图'".";";
$tables['anounce']['columns']['link']         ="ALTER TABLE ".tablename('fm453_shopping_anounce')." ADD "."`link` varchar(255) DEFAULT '' COMMENT '详情链接'".";";
$tables['anounce']['columns']['detail']       ="ALTER TABLE ".tablename('fm453_shopping_anounce')." ADD "."`detail` text COMMENT '公告内容详情'".";";
$tables['anounce']['columns']['status']       ="ALTER TABLE ".tablename('fm453_shopping_anounce')." ADD "."`status` tinyint(3) DEFAULT '0' COMMENT '状态0待审1删除2通过'".";";
$tables['anounce']['columns']['createtime']   ="ALTER TABLE ".tablename('fm453_shopping_anounce')." ADD "."`createtime` int(11) DEFAULT NULL COMMENT '创建时间'".";";
$tables['anounce']['columns']['starttime']    ="ALTER TABLE ".tablename('fm453_shopping_anounce')." ADD "."`starttime` int(11) NOT NULL COMMENT '开始时间'".";";
$tables['anounce']['columns']['endtime']      ="ALTER TABLE ".tablename('fm453_shopping_anounce')." ADD "."`endtime` int(11) NOT NULL COMMENT '结束时间'".";";

$tables['anounce']['indexes']['id']           ="`id` (`id`)";
$tables['anounce']['indexes']['uniacid']      ="`uniacid` (`uniacid`)";
$tables['anounce']['indexes']['displayorder'] ="`displayorder` (`displayorder`)";
$tables['anounce']['indexes']['status']       ="`status` (`status`)";
//结束
