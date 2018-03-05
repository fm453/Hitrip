<?php
$tables['hiride_tour_order']=array(
	'name'=>'嗨骑骑游订单记录表',
	'type'=>array("api","all")
);
$tables['hiride_tour_order']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_api_hiride_tour_order')." (`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`))ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='嗨骑骑游订单记录表' ;";

$tables['hiride_tour_order']['columns']['id']          ="ALTER TABLE ".tablename('fm453_api_hiride_tour_order')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['hiride_tour_order']['columns']['order_no'] ="ALTER TABLE ".tablename('fm453_api_hiride_tour_order')." ADD "."`order_no` varchar(32) NOT NULL COMMENT '订单编号'".";";

$tables['hiride_tour_order']['columns']['wx_tpl_times']         ="ALTER TABLE ".tablename('fm453_api_hiride_tour_order')." ADD "."`wx_tpl_times` int(11) NOT NULL DEFAULT '0' COMMENT '微信模板消息推送次数'".";";
$tables['hiride_tour_order']['columns']['_ori_content'] ="ALTER TABLE ".tablename('fm453_api_hiride_tour_order')." ADD "."`_ori_content` text NOT NULL COMMENT '数据原始内容'".";";
$tables['hiride_tour_order']['columns']['lastedittime']          ="ALTER TABLE ".tablename('fm453_api_hiride_tour_order')." ADD "."`lastedittime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP".";";
$tables['hiride_tour_order']['columns']['createtime']          ="ALTER TABLE ".tablename('fm453_api_hiride_tour_order')." ADD "."`createtime` int(11) NOT NULL".";";

$tables['hiride_tour_order']['indexes']['id']  ="`id` (`id`)";
$tables['hiride_tour_order']['indexes']['order_no'] ="`order_no` (`order_no`)";
//结束

//  order_no
//  uid
//  phone
//  ttid
//  tdate
//  extranum
//  extratitle1
//  extrafee1
//  extranum1
//  extratitle2
//  extrafee2
//  extranum2
//  extratitle3
//  extrafee3
//  extranum3
//  extratitle4
//  extrafee4
//  extranum4
//  extratitle5
//  extrafee5
//  extranum5
//  extratitle6
//  extrafee6
//  extranum6
//  extratitle7
//  extrafee7
//  extranum7
//  extratitle8
//  extrafee8
//  extranum8
//  extratitle9
//  extrafee9
//  extranum9
//  extratitle10
//  extrafee10
//  extranum10
//  adultprice
//  adultnum
//  childprice
//  childnum
//  roomnum
//  roomprice
//  insuname1
//  insufee1
//  insuind1
//  insuname2
//  insufee2
//  insuind2
//  total
//  save
//  pay
//  refund
//  time
//  travelpass
//  step
//  status 
