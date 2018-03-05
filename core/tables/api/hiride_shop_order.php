<?php
$tables['hiride_shop_order']=array(
	'name'=>'嗨骑商城订单记录表',
	'type'=>array("api","all")
);
$tables['hiride_shop_order']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_api_hiride_shop_order')." (`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`))ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='嗨骑商城订单记录表' ;";

$tables['hiride_shop_order']['columns']['id']          ="ALTER TABLE ".tablename('fm453_api_hiride_shop_order')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['hiride_shop_order']['columns']['order_no'] ="ALTER TABLE ".tablename('fm453_api_hiride_shop_order')." ADD "."`order_no` varchar(32) NOT NULL COMMENT '订单编号'".";";
// $tables['hiride_shop_order']['columns']['cid']         ="ALTER TABLE ".tablename('fm453_api_hiride_shop_order')." ADD "."`cid` int(11) NOT NULL COMMENT '公司编号'".";";
// $tables['hiride_shop_order']['columns']['bid']         ="ALTER TABLE ".tablename('fm453_api_hiride_shop_order')." ADD "."`bid` int(11) NOT NULL COMMENT '单车编号'".";";
// $tables['hiride_shop_order']['columns']['bmid']         ="ALTER TABLE ".tablename('fm453_api_hiride_shop_order')." ADD "."`bmid` int(11) NOT NULL COMMENT '单车品牌编号'".";";
// $tables['hiride_shop_order']['columns']['lid']         ="ALTER TABLE ".tablename('fm453_api_hiride_shop_order')." ADD "."`lid` int(11) NOT NULL COMMENT '车锁编号'".";";
// $tables['hiride_shop_order']['columns']['ltype']         ="ALTER TABLE ".tablename('fm453_api_hiride_shop_order')." ADD "."`ltype` tinyint(4) NOT NULL".";";
// $tables['hiride_shop_order']['columns']['macaddr'] ="ALTER TABLE ".tablename('fm453_api_hiride_shop_order')." ADD "."`macaddr` varchar(17) NOT NULL".";";
// $tables['hiride_shop_order']['columns']['pid']         ="ALTER TABLE ".tablename('fm453_api_hiride_shop_order')." ADD "."`pid` int(11) NOT NULL".";";
// $tables['hiride_shop_order']['columns']['hid']         ="ALTER TABLE ".tablename('fm453_api_hiride_shop_order')." ADD "."`hid` int(11) NOT NULL".";";
// $tables['hiride_shop_order']['columns']['btid']         ="ALTER TABLE ".tablename('fm453_api_hiride_shop_order')." ADD "."`btid` int(11) NOT NULL".";";
// $tables['hiride_shop_order']['columns']['from_openid'] ="ALTER TABLE ".tablename('fm453_api_hiride_shop_order')." ADD "."`from_openid` varchar(50) NOT NULL".";";

$tables['hiride_shop_order']['columns']['wx_tpl_times']         ="ALTER TABLE ".tablename('fm453_api_hiride_shop_order')." ADD "."`wx_tpl_times` int(11) NOT NULL DEFAULT '0' COMMENT '微信模板消息推送次数'".";";
$tables['hiride_shop_order']['columns']['_ori_content'] ="ALTER TABLE ".tablename('fm453_api_hiride_shop_order')." ADD "."`_ori_content` text NOT NULL COMMENT '数据原始内容'".";";
$tables['hiride_shop_order']['columns']['lastedittime']          ="ALTER TABLE ".tablename('fm453_api_hiride_shop_order')." ADD "."`lastedittime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP".";";
$tables['hiride_shop_order']['columns']['createtime']          ="ALTER TABLE ".tablename('fm453_api_hiride_shop_order')." ADD "."`createtime` int(11) NOT NULL".";";

$tables['hiride_shop_order']['indexes']['id']  ="`id` (`id`)";
$tables['hiride_shop_order']['indexes']['order_no'] ="`order_no` (`order_no`)";
//结束