<?php
$tables['category']=array(
	'name'=>'店铺与菜系',
	'type'=>array("foods","vfoods","system","all")
);
$tables['category']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_vfoods_category')." (`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`))ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='店铺与菜系' ;";

$tables['category']['columns']['id']           ="ALTER TABLE ".tablename('fm453_vfoods_category')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['category']['columns']['uniacid']      ="ALTER TABLE ".tablename('fm453_vfoods_category')." ADD "."`uniacid` int(11) UNSIGNED NOT NULL COMMENT '所属公众账号'".";";
$tables['category']['columns']['title']        ="ALTER TABLE ".tablename('fm453_vfoods_category')." ADD "."`title` varchar(50) NOT NULL COMMENT '分类标题'".";";
$tables['category']['columns']['psn']          ="ALTER TABLE ".tablename('fm453_vfoods_category')." ADD "."`psn` int(11) NOT NULL COMMENT '上级ID，0为店铺'".";";
$tables['category']['columns']['displayorder'] ="ALTER TABLE ".tablename('fm453_vfoods_category')." ADD "."`displayorder` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '排序'".";";
$tables['category']['columns']['enabled']      ="ALTER TABLE ".tablename('fm453_vfoods_category')." ADD "."`enabled` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '是否开启'".";";
$tables['category']['columns']['sendprice']    ="ALTER TABLE ".tablename('fm453_vfoods_category')." ADD "."`sendprice` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT ''".";";
$tables['category']['columns']['total']        ="ALTER TABLE ".tablename('fm453_vfoods_category')." ADD "."`total` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT ''".";";
$tables['category']['columns']['shouji']       ="ALTER TABLE ".tablename('fm453_vfoods_category')." ADD "."`shouji` bigint(50) UNSIGNED NOT NULL DEFAULT '0' COMMENT '店家手机'".";";
$tables['category']['columns']['email']        ="ALTER TABLE ".tablename('fm453_vfoods_category')." ADD "."`email` varchar(50) NOT NULL DEFAULT '0' COMMENT ''".";";
$tables['category']['columns']['typeid']       ="ALTER TABLE ".tablename('fm453_vfoods_category')." ADD "."`typeid` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT ''".";";
$tables['category']['columns']['thumb']        ="ALTER TABLE ".tablename('fm453_vfoods_category')." ADD "."`thumb` varchar(100) NOT NULL DEFAULT '0' COMMENT ''".";";
$tables['category']['columns']['description']  ="ALTER TABLE ".tablename('fm453_vfoods_category')." ADD "."`description` varchar(1000) NOT NULL DEFAULT '0' COMMENT ''".";";
$tables['category']['columns']['time1']        ="ALTER TABLE ".tablename('fm453_vfoods_category')." ADD "."`time1` varchar(10) NOT NULL DEFAULT '0' COMMENT ''".";";
$tables['category']['columns']['time2']        ="ALTER TABLE ".tablename('fm453_vfoods_category')." ADD "."`time2` varchar(10) NOT NULL DEFAULT '0' COMMENT ''".";";
$tables['category']['columns']['time3']        ="ALTER TABLE ".tablename('fm453_vfoods_category')." ADD "."`time3` varchar(10) NOT NULL DEFAULT '0' COMMENT ''".";";
$tables['category']['columns']['time4']        ="ALTER TABLE ".tablename('fm453_vfoods_category')." ADD "."`time4` varchar(10) NOT NULL DEFAULT '0' COMMENT ''".";";
$tables['category']['columns']['address']      ="ALTER TABLE ".tablename('fm453_vfoods_category')." ADD "."`address` varchar(100) NOT NULL DEFAULT '' COMMENT ''".";";
$tables['category']['columns']['managers']      ="ALTER TABLE ".tablename('fm453_vfoods_category')." ADD "."`managers` varchar(255) NOT NULL DEFAULT '' COMMENT '核销人员'".";";
$tables['category']['columns']['loc_x']        ="ALTER TABLE ".tablename('fm453_vfoods_category')." ADD "."`loc_x` varchar(20) NOT NULL DEFAULT '0' COMMENT ''".";";
$tables['category']['columns']['loc_y']        ="ALTER TABLE ".tablename('fm453_vfoods_category')." ADD "."`loc_y` varchar(20) NOT NULL DEFAULT '0' COMMENT ''".";";
$tables['category']['columns']['mbgroup']      ="ALTER TABLE ".tablename('fm453_vfoods_category')." ADD "."`mbgroup` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT ''".";";
$tables['category']['columns']['iswaimai'] ="ALTER TABLE ".tablename('fm453_vfoods_category')." ADD "."`iswaimai` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否支持外卖'".";";
$tables['category']['columns']['istangshi'] ="ALTER TABLE ".tablename('fm453_vfoods_category')." ADD "."`istangshi` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否支持堂食'".";";
$tables['category']['columns']['isziqu'] ="ALTER TABLE ".tablename('fm453_vfoods_category')." ADD "."`isziqu` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否支持自取'".";";
$tables['category']['columns']['isdefault'] ="ALTER TABLE ".tablename('fm453_vfoods_category')." ADD "."`isdefault` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否默认'".";";//设置为默认时，则在打开其对应点餐类型的页面时，略过列表页，直接跳转至对应店铺，
$tables['category']['columns']['isnodetail'] ="ALTER TABLE ".tablename('fm453_vfoods_category')." ADD "."`isnodetail` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否跳过详情页'".";";//跳过店铺详情页时，直接跳转至对应店铺的菜品列表进行点餐
$tables['category']['columns']['createtime']   ="ALTER TABLE ".tablename('fm453_vfoods_category')." ADD "."`createtime` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT ''".";";

$tables['category']['indexes']['id']           ="`id` (`id`)";
$tables['category']['indexes']['uniacid']      ="`uniacid` (`uniacid`)";
?>