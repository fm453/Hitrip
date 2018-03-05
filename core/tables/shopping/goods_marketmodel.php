<?php
$tables['goods_marketmodel']=array(
	'name'=>'产品关联营销模型',
	'type'=>array("goods","system","all")
);
$tables['goods_marketmodel']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_shopping_goods_marketmodel')." (`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`)) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT='产品营销模型关联表';";

$tables['goods_marketmodel']['columns']['id']             ="ALTER TABLE ".tablename('fm453_shopping_goods_marketmodel')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['goods_marketmodel']['columns']['uniacid']        ="ALTER TABLE ".tablename('fm453_shopping_goods_marketmodel')." ADD "."`uniacid` int(11) UNSIGNED NOT NULL COMMENT '所属公众账号'".";";
$tables['goods_marketmodel']['columns']['gid']            ="ALTER TABLE ".tablename('fm453_shopping_goods_marketmodel')." ADD "."`gid` int(10) UNSIGNED NOT NULL COMMENT '对应产品ID'".";";
$tables['goods_marketmodel']['columns']['ispresale']      ="ALTER TABLE ".tablename('fm453_shopping_goods_marketmodel')." ADD "."`ispresale` int(3) UNSIGNED NOT NULL COMMENT '是否预售1是0否'".";";
$tables['goods_marketmodel']['columns']['islimitnum']     ="ALTER TABLE ".tablename('fm453_shopping_goods_marketmodel')." ADD "."`islimitnum` int(3) UNSIGNED NOT NULL COMMENT '是否限量1是0否'".";";
$tables['goods_marketmodel']['columns']['islimittime']    ="ALTER TABLE ".tablename('fm453_shopping_goods_marketmodel')." ADD "."`islimittime` int(3) UNSIGNED NOT NULL COMMENT '是否限时1是0否'".";";
$tables['goods_marketmodel']['columns']['isfreedispatch'] ="ALTER TABLE ".tablename('fm453_shopping_goods_marketmodel')." ADD "."`isfreedispatch` int(3) UNSIGNED NOT NULL COMMENT '是否包邮1是0否'".";";
$tables['goods_marketmodel']['columns']['isminus']        ="ALTER TABLE ".tablename('fm453_shopping_goods_marketmodel')." ADD "."`isminus` int(3) UNSIGNED NOT NULL COMMENT '是否满减1 是0否'".";";
$tables['goods_marketmodel']['columns']['isgiftable']     ="ALTER TABLE ".tablename('fm453_shopping_goods_marketmodel')." ADD "."`isgiftable` int(3) UNSIGNED NOT NULL COMMENT '是否新手礼1是0否'".";";
$tables['goods_marketmodel']['columns']['isaddable']      ="ALTER TABLE ".tablename('fm453_shopping_goods_marketmodel')." ADD "."`isaddable` int(3) UNSIGNED NOT NULL COMMENT '是否加价购1是0否'".";";
$tables['goods_marketmodel']['columns']['ispintuan']      ="ALTER TABLE ".tablename('fm453_shopping_goods_marketmodel')." ADD "."`ispintuan` int(3) UNSIGNED NOT NULL COMMENT '是否拼团1是0否'".";";
$tables['goods_marketmodel']['columns']['isguessable']    ="ALTER TABLE ".tablename('fm453_shopping_goods_marketmodel')." ADD "."`isguessable` int(3) UNSIGNED NOT NULL COMMENT '是否竞猜1是0否'".";";
$tables['goods_marketmodel']['columns']['islucky']        ="ALTER TABLE ".tablename('fm453_shopping_goods_marketmodel')." ADD "."`islucky` int(3) UNSIGNED NOT NULL COMMENT '是否1元购'".";";
$tables['goods_marketmodel']['columns']['isdiscount']     ="ALTER TABLE ".tablename('fm453_shopping_goods_marketmodel')." ADD "."`isdiscount` int(3) UNSIGNED NOT NULL COMMENT '10无折扣1为1折9为9折'".";";
$tables['goods_marketmodel']['columns']['isonlynewuser']  ="ALTER TABLE ".tablename('fm453_shopping_goods_marketmodel')." ADD "."`isonlynewuser` int(3) UNSIGNED NOT NULL COMMENT '0新老用户共享1新用户专享'".";";
$tables['goods_marketmodel']['columns']['isonlyfemale']   ="ALTER TABLE ".tablename('fm453_shopping_goods_marketmodel')." ADD "."`isonlyfemale` int(3) UNSIGNED NOT NULL COMMENT '0男女通用1女士专用2男士专用'".";";
$tables['goods_marketmodel']['columns']['isonlymember']   ="ALTER TABLE ".tablename('fm453_shopping_goods_marketmodel')." ADD "."`isonlymember` int(3) UNSIGNED NOT NULL COMMENT '0粉丝与会员共享1会员专享'".";";

$tables['goods_marketmodel']['indexes']['id']="`id` (`id`)";
