<?php
$tables['goodstpl']=array(
	'name'=>'产品模型参数项',
	'type'=>array("goods","system","all")
);
$tables['goodstpl']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_shopping_goodstpl')." (`id` int(11) NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`))ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='产品模型参数' ;";

$tables['goodstpl']['columns']['id']           ="ALTER TABLE ".tablename('fm453_shopping_goodstpl')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['goodstpl']['columns']['sn']           ="ALTER TABLE ".tablename('fm453_shopping_goodstpl')." ADD "."`sn` int(11) NOT NULL COMMENT '系统记录编号'".";";
$tables['goodstpl']['columns']['uniacid']      ="ALTER TABLE ".tablename('fm453_shopping_goodstpl')." ADD "."`uniacid` int(11) NOT NULL DEFAULT '0'".";";
$tables['goodstpl']['columns']['goodstpl']     ="ALTER TABLE ".tablename('fm453_shopping_goodstpl')." ADD "."`goodstpl` varchar(50) NOT NULL COMMENT '模型名称'".";";
$tables['goodstpl']['columns']['isfront']      ="ALTER TABLE ".tablename('fm453_shopping_goodstpl')." ADD "."`isfront` tinyint(3) NOT NULL DEFAULT '0' COMMENT '是否为前台调用'".";";
$tables['goodstpl']['columns']['isform']       ="ALTER TABLE ".tablename('fm453_shopping_goodstpl')." ADD "."`isform` tinyint(3) NOT NULL DEFAULT '0' COMMENT '是否为表单收集项'".";";
$tables['goodstpl']['columns']['param']        ="ALTER TABLE ".tablename('fm453_shopping_goodstpl')." ADD "."`param` varchar(255) NOT NULL COMMENT '参数'".";";
$tables['goodstpl']['columns']['datatype']     ="ALTER TABLE ".tablename('fm453_shopping_goodstpl')." ADD "."`datatype` varchar(50) NOT NULL DEFAULT 'text' COMMENT '参数数据类型'".";";
$tables['goodstpl']['columns']['value']        ="ALTER TABLE ".tablename('fm453_shopping_goodstpl')." ADD "."`value` varchar(255) NOT NULL COMMENT '参数值'".";";
$tables['goodstpl']['columns']['isneeded']     ="ALTER TABLE ".tablename('fm453_shopping_goodstpl')." ADD "."`isneeded` tinyint(3) NOT NULL DEFAULT '1' COMMENT '是否必填项'".";";
$tables['goodstpl']['columns']['statuscode']   ="ALTER TABLE ".tablename('fm453_shopping_goodstpl')." ADD "."`statuscode` tinyint(3) DEFAULT '1' COMMENT '参数状态'".";";
$tables['goodstpl']['columns']['displayorder'] ="ALTER TABLE ".tablename('fm453_shopping_goodstpl')." ADD "."`displayorder` int(11) NOT NULL COMMENT '排序'".";";
$tables['goodstpl']['columns']['createtime']   ="ALTER TABLE ".tablename('fm453_shopping_goodstpl')." ADD "."`createtime` int(11) NOT NULL COMMENT '记录创建时间'".";";
$tables['goodstpl']['columns']['deleted']      ="ALTER TABLE ".tablename('fm453_shopping_goodstpl')." ADD "."`deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除'".";";

$tables['goodstpl']['indexes']['id']           ="`id` (`id`)";
$tables['goodstpl']['indexes']['sn']           ="`sn` (`sn`)";
$tables['goodstpl']['indexes']['uniacid']      ="`uniacid` (`uniacid`)";
$tables['goodstpl']['indexes']['displayorder'] ="`displayorder` (`displayorder`)";
$tables['goodstpl']['indexes']['statuscode']   ="`statuscode` (`statuscode`)";
$tables['goodstpl']['indexes']['deleted']      ="`deleted` (`deleted`)";
	//结束
