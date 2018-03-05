<?php
$tables['loc']=array(
	'name'=>'位置记录',
	'type'=>array("foods","vfoods","system","all")
);
$tables['loc']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_vfoods_loc')." (`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`))ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='购物车' ;";

$tables['loc']['columns']['id']         ="ALTER TABLE ".tablename('fm453_vfoods_loc')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['loc']['columns']['uniacid']    ="ALTER TABLE ".tablename('fm453_vfoods_loc')." ADD "."`uniacid` int(11) UNSIGNED NOT NULL COMMENT '所属公众账号'".";";
$tables['loc']['columns']['from_user']  ="ALTER TABLE ".tablename('fm453_vfoods_loc')." ADD "."`from_user` varchar(50) NOT NULL COMMENT '来源用户'".";";
$tables['loc']['columns']['loc_x']      ="ALTER TABLE ".tablename('fm453_vfoods_loc')." ADD "."`loc_x` varchar(20) NOT NULL COMMENT ''".";";
$tables['loc']['columns']['loc_y']      ="ALTER TABLE ".tablename('fm453_vfoods_loc')." ADD "."`loc_y` varchar(20) NOT NULL COMMENT ''".";";
$tables['loc']['columns']['createtime'] ="ALTER TABLE ".tablename('fm453_vfoods_loc')." ADD "."`createtime` int(10) UNSIGNED NOT NULL COMMENT ''".";";

$tables['loc']['indexes']['id']           ="`id` (`id`)";
$tables['loc']['indexes']['uniacid']      ="`uniacid` (`uniacid`)";
$tables['loc']['indexes']['from_user']        ="`from_user` (`from_user`)";

?>