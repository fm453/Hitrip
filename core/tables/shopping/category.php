<?php
$tables['category']=array(
	'name'=>'商城分类',
	'type'=>array("goods","partner","system","all")
);
$tables['category']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_shopping_category')." (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`))ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商城分类表' ;";

$tables['category']['columns']['id']           ="ALTER TABLE ".tablename('fm453_shopping_category')." ADD "."`id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT".";";
$tables['category']['columns']['uniacid']      ="ALTER TABLE ".tablename('fm453_shopping_category')." ADD "."`uniacid` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '所属公众帐号'".";";
$tables['category']['columns']['commission']   ="ALTER TABLE ".tablename('fm453_shopping_category')." ADD "."`commission` int(10) UNSIGNED DEFAULT '0' COMMENT '推荐该类商品所能获得的佣金'".";";
$tables['category']['columns']['name']         ="ALTER TABLE ".tablename('fm453_shopping_category')." ADD "."`name` varchar(50) NOT NULL COMMENT '分类名称'".";";
$tables['category']['columns']['thumb']        ="ALTER TABLE ".tablename('fm453_shopping_category')." ADD "."`thumb` varchar(255) NOT NULL COMMENT '分类图片'".";";
$tables['category']['columns']['pagestyle']    ="ALTER TABLE ".tablename('fm453_shopping_category')." ADD "."`pagestyle` varchar(50) NOT NULL COMMENT '页面风格'".";";
$tables['category']['columns']['parentid']     ="ALTER TABLE ".tablename('fm453_shopping_category')." ADD "."`parentid` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级'".";";
$tables['category']['columns']['isrecommand']  ="ALTER TABLE ".tablename('fm453_shopping_category')." ADD "."`isrecommand` int(10) DEFAULT '0'".";";
$tables['category']['columns']['description']  ="ALTER TABLE ".tablename('fm453_shopping_category')." ADD "."`description` varchar(500) NOT NULL COMMENT '分类介绍'".";";
$tables['category']['columns']['displayorder'] ="ALTER TABLE ".tablename('fm453_shopping_category')." ADD "."`displayorder` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '排序'".";";
$tables['category']['columns']['enabled']      ="ALTER TABLE ".tablename('fm453_shopping_category')." ADD "."`enabled` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '是否开启'".";";
$tables['category']['columns']['sn']           ="ALTER TABLE ".tablename('fm453_shopping_category')." ADD "."`sn` int(11) NOT NULL COMMENT '记录序号，默认用时间戳生成'".";";
$tables['category']['columns']['psn']          ="ALTER TABLE ".tablename('fm453_shopping_category')." ADD "."`psn` int(11) NOT NULL COMMENT '上级分类的记录序号'".";";
$tables['category']['columns']['setfor']       ="ALTER TABLE ".tablename('fm453_shopping_category')." ADD "."`setfor` varchar(50) NOT NULL DEFAULT 'goods' COMMENT '设置对象'".";";
$tables['category']['columns']['deleted']      ="ALTER TABLE ".tablename('fm453_shopping_category')." ADD "."`deleted` tinyint(3) NOT NULL DEFAULT '0' COMMENT '是否删除'".";";
$tables['category']['columns']['statuscode']   ="ALTER TABLE ".tablename('fm453_shopping_category')." ADD "."`statuscode` int(11) NOT NULL COMMENT '状态码'".";";
$tables['category']['columns']['createtime']   ="ALTER TABLE ".tablename('fm453_shopping_category')." ADD "."`createtime` int(12) NOT NULL COMMENT '记录创建时间'".";";

$tables['category']['indexes']['id']       ="`id` (`id`)";
$tables['category']['indexes']['uniacid']  ="`uniacid` (`uniacid`)";
$tables['category']['indexes']['setfor']   ="`setfor` (`setfor`)";
$tables['category']['indexes']['parentid'] ="`parentid` (`parentid`)";
$tables['category']['indexes']['sn']       ="`sn` (`sn`)";
$tables['category']['indexes']['psn']      ="`psn` (`psn`)";
$tables['category']['indexes']['status']   ="`status` (`status`)";
//category结束
