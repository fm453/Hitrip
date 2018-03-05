<?php
$tables['hiride_bike_order']=array(
	'name'=>'嗨骑租车订单记录表',
	'type'=>array("api","all")
);
$tables['hiride_bike_order']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_api_hiride_bike_order')." (`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`))ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='嗨骑租车订单记录表' ;";

$tables['hiride_bike_order']['columns']['id']          ="ALTER TABLE ".tablename('fm453_api_hiride_bike_order')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['hiride_bike_order']['columns']['order_no'] ="ALTER TABLE ".tablename('fm453_api_hiride_bike_order')." ADD "."`order_no` varchar(32) NOT NULL COMMENT '订单编号'".";";
// $tables['hiride_bike_order']['columns']['cid']         ="ALTER TABLE ".tablename('fm453_api_hiride_bike_order')." ADD "."`cid` int(11) NOT NULL COMMENT '公司编号'".";";
// $tables['hiride_bike_order']['columns']['bid']         ="ALTER TABLE ".tablename('fm453_api_hiride_bike_order')." ADD "."`bid` int(11) NOT NULL COMMENT '单车编号'".";";
// $tables['hiride_bike_order']['columns']['bmid']         ="ALTER TABLE ".tablename('fm453_api_hiride_bike_order')." ADD "."`bmid` int(11) NOT NULL COMMENT '单车品牌编号'".";";
// $tables['hiride_bike_order']['columns']['lid']         ="ALTER TABLE ".tablename('fm453_api_hiride_bike_order')." ADD "."`lid` int(11) NOT NULL COMMENT '车锁编号'".";";
// $tables['hiride_bike_order']['columns']['ltype']         ="ALTER TABLE ".tablename('fm453_api_hiride_bike_order')." ADD "."`ltype` tinyint(4) NOT NULL".";";
// $tables['hiride_bike_order']['columns']['macaddr'] ="ALTER TABLE ".tablename('fm453_api_hiride_bike_order')." ADD "."`macaddr` varchar(17) NOT NULL".";";
// $tables['hiride_bike_order']['columns']['pid']         ="ALTER TABLE ".tablename('fm453_api_hiride_bike_order')." ADD "."`pid` int(11) NOT NULL".";";
// $tables['hiride_bike_order']['columns']['hid']         ="ALTER TABLE ".tablename('fm453_api_hiride_bike_order')." ADD "."`hid` int(11) NOT NULL".";";
// $tables['hiride_bike_order']['columns']['btid']         ="ALTER TABLE ".tablename('fm453_api_hiride_bike_order')." ADD "."`btid` int(11) NOT NULL".";";
// $tables['hiride_bike_order']['columns']['from_openid'] ="ALTER TABLE ".tablename('fm453_api_hiride_bike_order')." ADD "."`from_openid` varchar(50) NOT NULL".";";

$tables['hiride_bike_order']['columns']['wx_tpl_times']         ="ALTER TABLE ".tablename('fm453_api_hiride_bike_order')." ADD "."`wx_tpl_times` int(11) NOT NULL DEFAULT '0' COMMENT '微信模板消息推送次数'".";";
$tables['hiride_bike_order']['columns']['_ori_content'] ="ALTER TABLE ".tablename('fm453_api_hiride_bike_order')." ADD "."`_ori_content` text NOT NULL COMMENT '数据原始内容'".";";
$tables['hiride_bike_order']['columns']['lastedittime']          ="ALTER TABLE ".tablename('fm453_api_hiride_bike_order')." ADD "."`lastedittime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP".";";
$tables['hiride_bike_order']['columns']['createtime']          ="ALTER TABLE ".tablename('fm453_api_hiride_bike_order')." ADD "."`createtime` int(11) NOT NULL".";";

$tables['hiride_bike_order']['indexes']['id']  ="`id` (`id`)";
$tables['hiride_bike_order']['indexes']['order_no'] ="`order_no` (`order_no`)";
//结束

// ptype tinyint(4) 单车型号编号，如单人车
// unit int(11) 计价单位
// price1~6 int(11) 价格阶梯1~6
// insurance tiny(1) 是否购买保险
// realname char(30) 购买保险人姓名
// id_no char(18) 购买保险人身份证
// realname1 char(20)
// id_no1 char(18)
// realname2 char(20)
// id_no2 char(18)
// realname3 char(30)
// id_no3 char(18)
// uid int(11) 用户编号
// phone char(11) 手机号码
// time_from int(11) 取车时间
// time_to int(11) 还车时间
// time3 int(11) 用车时长
// timefee double 时长费用
// get_from char(30)
// get_usrlng	double
// get_usrlat	double
// rtn_lng	double
// rnt_lat double
// get_mile double
// rnt_mile double
// get_power	int(11)
// rnt_power	int(11)
// mile_by_power	tiny(1)
// miles 	double
// milefee 	double
// total double 总车费
// available double 钱包余额支付金额
// save 	double 内部测试费用减免
// pay double 订单实付金额
// tran_id	char(32) 押金抵扣车费时的押金支付微信订单号
// step 	int(11)	 订单流程步骤（500待还车，700付支付，900已完成）
// status tiny(2) 	订单状态 1正常 其他异常
