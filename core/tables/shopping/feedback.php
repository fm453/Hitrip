<?php
$tables['feedback']=array(
	'name'=>'售后维权',
	'type'=>array("goods","member","system","all")
);
$tables['feedback']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_shopping_feedback')." (`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`)) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;";

$tables['feedback']['columns']['id']         ="ALTER TABLE ".tablename('fm453_shopping_feedback')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['feedback']['columns']['uniacid']    ="ALTER TABLE ".tablename('fm453_shopping_feedback')." ADD"."`uniacid` int(11) UNSIGNED NOT NULL COMMENT '所属公众账号'".";";
$tables['feedback']['columns']['openid']     ="ALTER TABLE ".tablename('fm453_shopping_feedback')." ADD "."`openid` varchar(50) NOT NULL".";";
$tables['feedback']['columns']['type']       ="ALTER TABLE ".tablename('fm453_shopping_feedback')." ADD "."`type` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '1为维权，2为告警，3为报错，4为建议'".";";
$tables['feedback']['columns']['status']     ="ALTER TABLE ".tablename('fm453_shopping_feedback')." ADD "."`status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态0未解决/处理中，1用户同意，2用户拒绝，3报错已解决，4建议已采纳，5建议未采纳'".";";
$tables['feedback']['columns']['feedbackid'] ="ALTER TABLE ".tablename('fm453_shopping_feedback')." ADD "."`feedbackid` varchar(30) NOT NULL COMMENT '反馈单号'".";";
$tables['feedback']['columns']['transid']    ="ALTER TABLE ".tablename('fm453_shopping_feedback')." ADD "."`transid` varchar(30) NOT NULL COMMENT '支付记录订单号'".";";
$tables['feedback']['columns']['reason']     ="ALTER TABLE ".tablename('fm453_shopping_feedback')." ADD "."`reason` varchar(1000) NOT NULL COMMENT '理由'".";";
$tables['feedback']['columns']['solution']   ="ALTER TABLE ".tablename('fm453_shopping_feedback')." ADD "."`solution` varchar(1000) NOT NULL COMMENT '期待解决方案'".";";
$tables['feedback']['columns']['remark']     ="ALTER TABLE ".tablename('fm453_shopping_feedback')." ADD "."`remark` varchar(1000) NOT NULL COMMENT '备注'".";";
$tables['feedback']['columns']['reply']      ="ALTER TABLE ".tablename('fm453_shopping_feedback')." ADD "."`reply` text  COMMENT '回复'".";";
$tables['feedback']['columns']['msgtitle']   ="ALTER TABLE ".tablename('fm453_shopping_feedback')." ADD "."`msgtitle` varchar(100) NOT NULL COMMENT '信息标题'".";";
$tables['feedback']['columns']['msg']        ="ALTER TABLE ".tablename('fm453_shopping_feedback')." ADD "."`msg` varchar(1000) NOT NULL COMMENT '反馈的信息'".";";
$tables['feedback']['columns']['images']     ="ALTER TABLE ".tablename('fm453_shopping_feedback')." ADD "."`images` varchar(1000) NOT NULL COMMENT '图片附件'".";";
$tables['feedback']['columns']['files']      ="ALTER TABLE ".tablename('fm453_shopping_feedback')." ADD "."`files` varchar(1000) NOT NULL COMMENT '文件附件'".";";
$tables['feedback']['columns']['stars']      ="ALTER TABLE ".tablename('fm453_shopping_feedback')." ADD"."`stars` int(11) UNSIGNED NOT NULL COMMENT '星级'".";";
$tables['feedback']['columns']['createtime'] ="ALTER TABLE ".tablename('fm453_shopping_feedback')." ADD "."`createtime` int(10) UNSIGNED NOT NULL".";";
$tables['feedback']['columns']['replytime']  ="ALTER TABLE ".tablename('fm453_shopping_feedback')." ADD "."`replytime` int(11) UNSIGNED NOT NULL".";";

$tables['feedback']['indexes']['id']="`id` (`id`)";