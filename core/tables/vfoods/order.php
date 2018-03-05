<?php
$tables['order']=array(
	'name'=>'订单',
	'type'=>array("foods","vfoods","system","all")
);
$tables['order']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_vfoods_order')." (`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`))ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='订单' ;";

$tables['order']['columns']['id']         ="ALTER TABLE ".tablename('fm453_vfoods_order')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['order']['columns']['uniacid']    ="ALTER TABLE ".tablename('fm453_vfoods_order')." ADD "."`uniacid` int(11) UNSIGNED NOT NULL COMMENT '所属公众账号'".";";
$tables['order']['columns']['from_user']  ="ALTER TABLE ".tablename('fm453_vfoods_order')." ADD "."`from_user` varchar(50) NOT NULL COMMENT '来源用户'".";";
$tables['order']['columns']['pcate']    ="ALTER TABLE ".tablename('fm453_vfoods_order')." ADD "."`pcate` int(10) UNSIGNED NOT NULL COMMENT ''".";";
$tables['order']['columns']['username']    ="ALTER TABLE ".tablename('fm453_vfoods_order')." ADD "."`username` varchar(50) NOT NULL COMMENT '用户名'".";";
$tables['order']['columns']['mobile']     ="ALTER TABLE ".tablename('fm453_vfoods_order')." ADD "."`mobile` bigint(50) NOT NULL COMMENT ''".";";
$tables['order']['columns']['address']    ="ALTER TABLE ".tablename('fm453_vfoods_order')." ADD "."`address` varchar(50) NOT NULL COMMENT '用餐地址'".";";
$tables['order']['columns']['ordersn']    ="ALTER TABLE ".tablename('fm453_vfoods_order')." ADD "."`ordersn` varchar(20) NOT NULL COMMENT ''".";";
$tables['order']['columns']['price']      ="ALTER TABLE ".tablename('fm453_vfoods_order')." ADD "."`price` varchar(10) NOT NULL COMMENT ''".";";
$tables['order']['columns']['status']     ="ALTER TABLE ".tablename('fm453_vfoods_order')." ADD "."`status` tinyint(4) NOT NULL COMMENT '-2已删除，-1已取消，0已完成，1等待支付，2已下单，3已确认'".";";
$tables['order']['columns']['paytype']    ="ALTER TABLE ".tablename('fm453_vfoods_order')." ADD "."`paytype` tinyint(1) UNSIGNED NOT NULL COMMENT '1为在线付款，2为餐到付款'".";";
$tables['order']['columns']['ordertype']    ="ALTER TABLE ".tablename('fm453_vfoods_order')." ADD "."`ordertype` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '1外卖，2堂食，3自取'".";";
$tables['order']['columns']['desknum']      ="ALTER TABLE ".tablename('fm453_vfoods_order')." ADD "."`desknum` varchar(20) NOT NULL COMMENT '桌台号'".";";
$tables['order']['columns']['guests']      ="ALTER TABLE ".tablename('fm453_vfoods_order')." ADD "."`guests` int(11) NOT NULL DEFAULT '1' COMMENT '用餐人数'".";";
$tables['order']['columns']['other']      ="ALTER TABLE ".tablename('fm453_vfoods_order')." ADD "."`other` varchar(100) NOT NULL COMMENT ''".";";
$tables['order']['columns']['time']       ="ALTER TABLE ".tablename('fm453_vfoods_order')." ADD "."`time` varchar(20) NOT NULL COMMENT ''".";";
$tables['order']['columns']['createtime'] ="ALTER TABLE ".tablename('fm453_vfoods_order')." ADD "."`createtime` int(10) UNSIGNED NOT NULL COMMENT ''".";";

$tables['order']['indexes']['id']        ="`id` (`id`)";
$tables['order']['indexes']['uniacid']   ="`uniacid` (`uniacid`)";
$tables['order']['indexes']['from_user'] ="`from_user` (`from_user`)";

?>