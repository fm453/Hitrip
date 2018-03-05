<?php
$tables['member']=array(
	'name'=>'商城会员',
	'type'=>array("member","system","all")
);
$tables['member']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_shopping_member')." (`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`)) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;";

$tables['member']['columns']['id']           ="ALTER TABLE ".tablename('fm453_shopping_member')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['member']['columns']['uniacid']      ="ALTER TABLE ".tablename('fm453_shopping_member')." ADD "."`uniacid` int(11) UNSIGNED NOT NULL COMMENT '所属公众账号'".";";
$tables['member']['columns']['shareid']      ="ALTER TABLE ".tablename('fm453_shopping_member')." ADD "."`shareid` int(11) DEFAULT NULL COMMENT '分销上级会员ID'".";";
$tables['member']['columns']['uid']          ="ALTER TABLE ".tablename('fm453_shopping_member')." ADD "."`uid` int(11) DEFAULT NULL".";";
$tables['member']['columns']['from_user']   ="ALTER TABLE ".tablename('fm453_shopping_member')." ADD "."`from_user` varchar(50) NOT NULL COMMENT '会员的微信Openid'".";";
$tables['member']['columns']['qywx_userid'] ="ALTER TABLE ".tablename('fm453_shopping_member')." ADD "."`qywx_userid` VARCHAR(50) DEFAULT NULL COMMENT '关联企业微信的用户ID'".";";
$tables['member']['columns']['qywx_openid'] ="ALTER TABLE ".tablename('fm453_shopping_member')." ADD "."`qywx_openid` varchar(50) NOT NULL COMMENT '关联企业微信的Openid'".";";
$tables['member']['columns']['realname']    ="ALTER TABLE ".tablename('fm453_shopping_member')." ADD "."`realname` varchar(20) NOT NULL".";";
$tables['member']['columns']['keyword']      ="ALTER TABLE ".tablename('fm453_shopping_member')." ADD "."`keyword` varchar(255) NOT NULL COMMENT '用于检索的关键字' ".";";
$tables['member']['columns']['slogan']       ="ALTER TABLE ".tablename('fm453_shopping_member')." ADD "."`slogan` varchar(255) NOT NULL COMMENT '个性签名' ".";";
$tables['member']['columns']['mobile']       ="ALTER TABLE ".tablename('fm453_shopping_member')." ADD "."`mobile` varchar(11) NOT NULL".";";
$tables['member']['columns']['pwd']          ="ALTER TABLE ".tablename('fm453_shopping_member')." ADD "."`pwd` varchar(20) NOT NULL".";";
$tables['member']['columns']['bankcard']     ="ALTER TABLE ".tablename('fm453_shopping_member')." ADD "."`bankcard` varchar(20) DEFAULT NULL".";";
$tables['member']['columns']['banktype']     ="ALTER TABLE ".tablename('fm453_shopping_member')." ADD "."`banktype` varchar(20) DEFAULT NULL".";";
$tables['member']['columns']['alipay']       ="ALTER TABLE ".tablename('fm453_shopping_member')." ADD "."`alipay` varchar(100) DEFAULT NULL".";";
$tables['member']['columns']['wxhao']        ="ALTER TABLE ".tablename('fm453_shopping_member')." ADD "."`wxhao` varchar(100) DEFAULT NULL".";";
$tables['member']['columns']['commission']   ="ALTER TABLE ".tablename('fm453_shopping_member')." ADD "."`commission` decimal(10,2) UNSIGNED DEFAULT '0.00' COMMENT '已结佣佣金'".";";
$tables['member']['columns']['zhifu']        ="ALTER TABLE ".tablename('fm453_shopping_member')." ADD "."`zhifu` decimal(10,2) UNSIGNED DEFAULT '0.00' COMMENT '已打款佣金'".";";
$tables['member']['columns']['content']      ="ALTER TABLE ".tablename('fm453_shopping_member')." ADD "."`content` text COMMENT '会员简介'".";";
$tables['member']['columns']['createtime']   ="ALTER TABLE ".tablename('fm453_shopping_member')." ADD "."`createtime` int(10) NOT NULL".";";
$tables['member']['columns']['flagtime']     ="ALTER TABLE ".tablename('fm453_shopping_member')." ADD "."`flagtime` int(10) DEFAULT NULL COMMENT '成为推广人的时间'".";";
$tables['member']['columns']['status']       ="ALTER TABLE ".tablename('fm453_shopping_member')." ADD "."`status` tinyint(1) DEFAULT '1' COMMENT '0为禁用，1为可用'".";";
$tables['member']['columns']['flag']         ="ALTER TABLE ".tablename('fm453_shopping_member')." ADD "."`flag` tinyint(1) DEFAULT '0' COMMENT '0为非推广人，1为推广人'".";";
$tables['member']['columns']['clickcount']   ="ALTER TABLE ".tablename('fm453_shopping_member')." ADD "."`clickcount` int(11) NOT NULL DEFAULT '0' COMMENT '链接、分享等被点击次数'".";";
$tables['member']['columns']['displayorder'] ="ALTER TABLE ".tablename('fm453_shopping_member')." ADD "."`displayorder` int(11) NOT NULL DEFAULT '0' COMMENT '显示排序'".";";
$tables['member']['columns']['isblack']       ="ALTER TABLE ".tablename('fm453_shopping_member')." ADD "."`isblack` tinyint(1) DEFAULT '0' COMMENT '加黑名单，0否1是'".";";
$tables['member']['columns']['deleted']       ="ALTER TABLE ".tablename('fm453_shopping_member')." ADD "."`deleted` tinyint(1) DEFAULT '0' COMMENT '软删除，0否1是'".";";

$tables['member']['indexes']['id']="`id` (`id`)";
$tables['member']['indexes']['uid']="`uid` (`uid`)";
$tables['member']['indexes']['uniacid']="`uniacid` (`uniacid`)";
$tables['member']['indexes']['keyword']="`keyword` (`keyword`)";
$tables['member']['indexes']['mobile']="`mobile` (`mobile`)";