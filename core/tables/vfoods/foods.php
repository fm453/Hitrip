<?php
$tables['foods']=array(
	'name'=>'菜品',
	'type'=>array("foods","vfoods","system","all")
);
$tables['foods']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_vfoods_foods')." (`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`))ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='菜品' ;";

$tables['foods']['columns']['id']         ="ALTER TABLE ".tablename('fm453_vfoods_foods')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['foods']['columns']['uniacid']    ="ALTER TABLE ".tablename('fm453_vfoods_foods')." ADD "."`uniacid` int(11) UNSIGNED NOT NULL COMMENT '所属公众账号'".";";
$tables['foods']['columns']['pcate']      ="ALTER TABLE ".tablename('fm453_vfoods_foods')." ADD "."`pcate` int(11) NOT NULL COMMENT ''".";";
$tables['foods']['columns']['ccate']      ="ALTER TABLE ".tablename('fm453_vfoods_foods')." ADD "."`ccate` int(11) NOT NULL COMMENT ''".";";
$tables['foods']['columns']['status']     ="ALTER TABLE ".tablename('fm453_vfoods_foods')." ADD "."`status` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT ''".";";
$tables['foods']['columns']['ishot']      ="ALTER TABLE ".tablename('fm453_vfoods_foods')." ADD "."`ishot` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT ''".";";
$tables['foods']['columns']['title']      ="ALTER TABLE ".tablename('fm453_vfoods_foods')." ADD "."`title` varchar(100) NOT NULL COMMENT '标题'".";";
$tables['foods']['columns']['thumb']      ="ALTER TABLE ".tablename('fm453_vfoods_foods')." ADD "."`thumb` varchar(100) NOT NULL DEFAULT '0' COMMENT ''".";";
$tables['foods']['columns']['unit']       ="ALTER TABLE ".tablename('fm453_vfoods_foods')." ADD "."`unit` varchar(5) NOT NULL DEFAULT '' COMMENT ''".";";
$tables['foods']['columns']['preprice']   ="ALTER TABLE ".tablename('fm453_vfoods_foods')." ADD "."`preprice` varchar(10) NOT NULL DEFAULT '' COMMENT ''".";";
$tables['foods']['columns']['oriprice']   ="ALTER TABLE ".tablename('fm453_vfoods_foods')." ADD "."`oriprice` varchar(10) NOT NULL DEFAULT '' COMMENT ''".";";
$tables['foods']['columns']['hits']       ="ALTER TABLE ".tablename('fm453_vfoods_foods')." ADD "."`hits` int(10) UNSIGNED NOT NULL COMMENT ''".";";
$tables['foods']['columns']['stock']       ="ALTER TABLE ".tablename('fm453_vfoods_foods')." ADD "."`stock` int(10) UNSIGNED NOT NULL COMMENT '库存'".";";
$tables['foods']['columns']['overbook']       ="ALTER TABLE ".tablename('fm453_vfoods_foods')." ADD "."`overbook` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '允许超售量'".";";
$tables['foods']['columns']['createtime'] ="ALTER TABLE ".tablename('fm453_vfoods_foods')." ADD "."`createtime` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT ''".";";

$tables['foods']['indexes']['id']      ="`id` (`id`)";
$tables['foods']['indexes']['uniacid'] ="`uniacid` (`uniacid`)";
$tables['foods']['indexes']['ishot']   ="`ishot` (`ishot`)";

?>