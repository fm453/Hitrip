<?php
$tables['partner']=array(
	'name'=>'合作商户',
	'type'=>array("partner","system","all")
);
$tables['partner']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_shopping_partner')." (`id` int(11) NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='合作伙伴列表';";

$tables['partner']['columns']['id']          ="ALTER TABLE ".tablename('fm453_shopping_partner')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['partner']['columns']['uniacid']     ="ALTER TABLE ".tablename('fm453_shopping_partner')." ADD "."`uniacid` int(11) DEFAULT '0' COMMENT '公众号ID'".";";
$tables['partner']['columns']['psn']         ="ALTER TABLE ".tablename('fm453_shopping_partner')." ADD "."`psn` varchar(20) NOT NULL COMMENT '合作伙伴编号'".";";
$tables['partner']['columns']['pcate']       ="ALTER TABLE ".tablename('fm453_shopping_partner')." ADD "."`pcate` int(11) NOT NULL COMMENT '当前分类'".";";
$tables['partner']['columns']['ccate']       ="ALTER TABLE ".tablename('fm453_shopping_partner')." ADD "."`ccate` int(11) NOT NULL COMMENT '上级分类'".";";
$tables['partner']['columns']['ptype']       ="ALTER TABLE ".tablename('fm453_shopping_partner')." ADD "."`ptype` varchar(50) NOT NULL COMMENT '伙伴类型'".";";
$tables['partner']['columns']['name']        ="ALTER TABLE ".tablename('fm453_shopping_partner')." ADD "."`name` varchar(255) DEFAULT '' COMMENT '商家名称'".";";
$tables['partner']['columns']['openid']        ="ALTER TABLE ".tablename('fm453_shopping_partner')." ADD "."`openid` varchar(50) DEFAULT '' COMMENT '关联微信OPENid'".";";
$tables['partner']['columns']['mobile1']        ="ALTER TABLE ".tablename('fm453_shopping_partner')." ADD "."`mobile1` varchar(11) DEFAULT '' COMMENT '手机1'".";";
$tables['partner']['columns']['tel1']        ="ALTER TABLE ".tablename('fm453_shopping_partner')." ADD "."`tel1` varchar(20) DEFAULT '' COMMENT '电话1'".";";
$tables['partner']['columns']['mobile2']        ="ALTER TABLE ".tablename('fm453_shopping_partner')." ADD "."`mobile2` varchar(11) DEFAULT '' COMMENT '手机2'".";";
$tables['partner']['columns']['tel2']        ="ALTER TABLE ".tablename('fm453_shopping_partner')." ADD "."`tel2` varchar(20) DEFAULT '' COMMENT '电话2'".";";
$tables['partner']['columns']['address']        ="ALTER TABLE ".tablename('fm453_shopping_partner')." ADD "."`address` varchar(255) DEFAULT '' COMMENT '地址'".";";
$tables['partner']['columns']['locx']        ="ALTER TABLE ".tablename('fm453_shopping_partner')." ADD "."`locx` varchar(50) DEFAULT '' COMMENT '地理经度'".";";
$tables['partner']['columns']['locy']        ="ALTER TABLE ".tablename('fm453_shopping_partner')." ADD "."`locy` varchar(50) DEFAULT '' COMMENT '地理纬度'".";";
$tables['partner']['columns']['email']        ="ALTER TABLE ".tablename('fm453_shopping_partner')." ADD "."`email` varchar(50) DEFAULT '' COMMENT '电子邮箱'".";";
$tables['partner']['columns']['logo']        ="ALTER TABLE ".tablename('fm453_shopping_partner')." ADD "."`logo` varchar(255) DEFAULT '' COMMENT 'LOGO地址'".";";
$tables['partner']['columns']['qrcode']        ="ALTER TABLE ".tablename('fm453_shopping_partner')." ADD "."`qrcode` varchar(255) DEFAULT '' COMMENT '二维码地址'".";";
$tables['partner']['columns']['biz'] ="ALTER TABLE ".tablename('fm453_shopping_partner')." ADD "."`biz` text NOT NULL COMMENT '主营业务'".";";
$tables['partner']['columns']['description'] ="ALTER TABLE ".tablename('fm453_shopping_partner')." ADD "."`description` text NOT NULL COMMENT '品牌描述'".";";
$tables['partner']['columns']['status']      ="ALTER TABLE ".tablename('fm453_shopping_partner')." ADD "."`status` tinyint(3) DEFAULT '0' COMMENT '商户状态'".";";
$tables['partner']['columns']['hit']      ="ALTER TABLE ".tablename('fm453_shopping_partner')." ADD "."`hit` int(11) DEFAULT '0' COMMENT '点击次数'".";";
$tables['partner']['columns']['vhit']      ="ALTER TABLE ".tablename('fm453_shopping_partner')." ADD "."`vhit` int(11) DEFAULT '0' COMMENT '虚拟点击次数'".";";
$tables['partner']['columns']['isrec']      ="ALTER TABLE ".tablename('fm453_shopping_partner')." ADD "."`isrec` tinyint(1) DEFAULT '0' COMMENT '是否推荐'".";";
$tables['partner']['columns']['displayorder']      ="ALTER TABLE ".tablename('fm453_shopping_partner')." ADD "."`displayorder` int(11) DEFAULT '0' COMMENT '显示顺序'".";";
$tables['partner']['columns']['deleted']      ="ALTER TABLE ".tablename('fm453_shopping_partner')." ADD "."`deleted` tinyint(1) DEFAULT '0' COMMENT '是否删除'".";";
$tables['partner']['columns']['createtime']  ="ALTER TABLE ".tablename('fm453_shopping_partner')." ADD "."`createtime` int(11) NOT NULL COMMENT '记录生成时间'".";";

$tables['partner']['indexes']['id']="`id` (`id`)";
$tables['partner']['indexes']['psn']="`psn` (`psn`)";
$tables['partner']['indexes']['name']="`name` (`name`)";
