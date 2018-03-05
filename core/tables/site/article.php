<?php
$tables['article']=array(
	'name'=>'文章表',
	'type'=>array("article","site","system","all")
);
$tables['article']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_site_article')." (`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`))ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文章表' ;";

$tables['article']['columns']['id']              ="ALTER TABLE ".tablename('fm453_site_article')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['article']['columns']['uniacid']         ="ALTER TABLE ".tablename('fm453_site_article')." ADD "."`uniacid` int(11) UNSIGNED NOT NULL COMMENT '所属公众账号'".";";
$tables['article']['columns']['sn']              ="ALTER TABLE ".tablename('fm453_site_article')." ADD "."`sn` int(11) NOT NULL COMMENT '系统关联微站文章记录编号'".";";
$tables['article']['columns']['title']           ="ALTER TABLE ".tablename('fm453_site_article')." ADD "."`title` varchar(255) NOT NULL COMMENT '文章标题'".";";
$tables['article']['columns']['title1']           ="ALTER TABLE ".tablename('fm453_site_article')." ADD "."`title1` varchar(255) NOT NULL COMMENT '主标题'".";";
$tables['article']['columns']['title2']           ="ALTER TABLE ".tablename('fm453_site_article')." ADD "."`title2` varchar(255) NOT NULL COMMENT '副标题'".";";
$tables['article']['columns']['keywords']        ="ALTER TABLE ".tablename('fm453_site_article')." ADD "."`keywords` varchar(255) NOT NULL COMMENT '供检索的关键词组'".";";
$tables['article']['columns']['pcate']           ="ALTER TABLE ".tablename('fm453_site_article')." ADD "."`pcate` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '当前分类'".";";
$tables['article']['columns']['ccate']           ="ALTER TABLE ".tablename('fm453_site_article')." ADD "."`ccate` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '上级分类，为0时表示当前为一级分类'".";";

$tables['article']['columns']['status']          ="ALTER TABLE ".tablename('fm453_site_article')." ADD "."`status` tinyint(1) UNSIGNED NOT NULL DEFAULT '1'".";";
$tables['article']['columns']['displayorder']    ="ALTER TABLE ".tablename('fm453_site_article')." ADD "."`displayorder` int(10) UNSIGNED NOT NULL DEFAULT '0'".";";

$tables['article']['columns']['marketprice']     ="ALTER TABLE ".tablename('fm453_site_article')." ADD "."`marketprice` decimal(10,2) NOT NULL DEFAULT '0.00'".";";
$tables['article']['columns']['cankaoprice']     ="ALTER TABLE ".tablename('fm453_site_article')." ADD "."`cankaoprice` decimal(10,2) NOT NULL DEFAULT '0.00'".";";
$tables['article']['columns']['costprice']       ="ALTER TABLE ".tablename('fm453_site_article')." ADD "."`costprice` decimal(10,2) NOT NULL DEFAULT '0.00'".";";
$tables['article']['columns']['originalprice']   ="ALTER TABLE ".tablename('fm453_site_article')." ADD "."`originalprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '原价'".";";
$tables['article']['columns']['total']           ="ALTER TABLE ".tablename('fm453_site_article')." ADD "."`total` int(10) NOT NULL DEFAULT '0' COMMENT '对外库存总量'".";";
$tables['article']['columns']['stock']           ="ALTER TABLE ".tablename('fm453_site_article')." ADD "."`stock` int(10) NOT NULL DEFAULT '0' COMMENT '真实库存总量'".";";
$tables['article']['columns']['sales']           ="ALTER TABLE ".tablename('fm453_site_article')." ADD "."`sales` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '外显付费阅读量'".";";
$tables['article']['columns']['realsales']       ="ALTER TABLE ".tablename('fm453_site_article')." ADD "."`realsales` int(10) UNSIGNED DEFAULT NULL COMMENT '真实付费阅读量'".";";
$tables['article']['columns']['lastsales']       ="ALTER TABLE ".tablename('fm453_site_article')." ADD "."`lastsales` int(10) UNSIGNED DEFAULT NULL COMMENT '上一次填写的阅读量'".";";

$tables['article']['columns']['credit']          ="ALTER TABLE ".tablename('fm453_site_article')." ADD "."`credit` decimal(10,2) NOT NULL DEFAULT '0.00'".";";
$tables['article']['columns']['maxbuy']          ="ALTER TABLE ".tablename('fm453_site_article')." ADD "."`maxbuy` int(11) DEFAULT '0'".";";
$tables['article']['columns']['usermaxbuy']      ="ALTER TABLE ".tablename('fm453_site_article')." ADD "."`usermaxbuy` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '用户最多浏览次数'".";";

$tables['article']['columns']['goodadm']         ="ALTER TABLE ".tablename('fm453_site_article')." ADD "."`goodadm` varchar(255) NOT NULL COMMENT '作者openids'".";";
$tables['article']['columns']['a_tpl']           ="ALTER TABLE ".tablename('fm453_site_article')." ADD "."`a_tpl` varchar(50) NOT NULL COMMENT '文章模型'".";";
$tables['article']['columns']['kefuphone']       ="ALTER TABLE ".tablename('fm453_site_article')." ADD "."`kefuphone` varchar(50) DEFAULT '0' COMMENT '客服专线电话'".";";
$tables['article']['columns']['isdashang']       ="ALTER TABLE ".tablename('fm453_site_article')." ADD "."`isdashang` tinyint(3) DEFAULT '1'".";";
$tables['article']['columns']['iscomment']       ="ALTER TABLE ".tablename('fm453_site_article')." ADD "."`iscomment` tinyint(3) DEFAULT '1'".";";
$tables['article']['columns']['isnew']           ="ALTER TABLE ".tablename('fm453_site_article')." ADD "."`isnew` int(11) DEFAULT '0'".";";
$tables['article']['columns']['ishot']           ="ALTER TABLE ".tablename('fm453_site_article')." ADD "."`ishot` int(11) DEFAULT '0'".";";
$tables['article']['columns']['isdiscount']      ="ALTER TABLE ".tablename('fm453_site_article')." ADD "."`isdiscount` int(11) DEFAULT '0'".";";
$tables['article']['columns']['isrecommand']     ="ALTER TABLE ".tablename('fm453_site_article')." ADD "."`isrecommand` int(11) DEFAULT '0'".";";
$tables['article']['columns']['istime']          ="ALTER TABLE ".tablename('fm453_site_article')." ADD "."`istime` int(11) DEFAULT '0'".";";
$tables['article']['columns']['timestart']       ="ALTER TABLE ".tablename('fm453_site_article')." ADD "."`timestart` int(11) DEFAULT '0'".";";
$tables['article']['columns']['timeend']         ="ALTER TABLE ".tablename('fm453_site_article')." ADD "."`timeend` int(11) DEFAULT '0'".";";
$tables['article']['columns']['viewcount']       ="ALTER TABLE ".tablename('fm453_site_article')." ADD "."`viewcount` int(11) DEFAULT '0' COMMENT'浏览次数'".";";
$tables['article']['columns']['sharecount']      ="ALTER TABLE ".tablename('fm453_site_article')." ADD "."`sharecount` int(11) DEFAULT '0' COMMENT'分享次数'".";";
$tables['article']['columns']['uv']              ="ALTER TABLE ".tablename('fm453_site_article')." ADD "."`uv` int(11) DEFAULT '0' COMMENT'独立访客数'".";";
$tables['article']['columns']['dianzan']         ="ALTER TABLE ".tablename('fm453_site_article')." ADD "."`dianzan` int(11) DEFAULT '0' COMMENT'点赞数'".";";
$tables['article']['columns']['commission']      ="ALTER TABLE ".tablename('fm453_site_article')." ADD "."`commission` int(3) NOT NULL".";";
$tables['article']['columns']['commission2']     ="ALTER TABLE ".tablename('fm453_site_article')." ADD "."`commission2` int(3) DEFAULT NULL".";";
$tables['article']['columns']['commission3']     ="ALTER TABLE ".tablename('fm453_site_article')." ADD "."`commission3` int(3) DEFAULT NULL".";";
$tables['article']['columns']['statuscode']      ="ALTER TABLE ".tablename('fm453_site_article')." ADD "."`statuscode` tinyint(3) NOT NULL COMMENT '状态码'".";";
$tables['article']['columns']['deleted']         ="ALTER TABLE ".tablename('fm453_site_article')." ADD "."`deleted` tinyint(3) UNSIGNED NOT NULL DEFAULT '0'".";";
$tables['article']['columns']['freereadlimit']   ="ALTER TABLE ".tablename('fm453_site_article')." ADD "."`freereadlimit` int(11) DEFAULT  NULL COMMENT '阅读者最少拥有的积分'".";";
$tables['article']['columns']['isfreereadlimit'] ="ALTER TABLE ".tablename('fm453_site_article')." ADD "."`isfreereadlimit` tinyint(3) DEFAULT  '0' COMMENT '是否开启阅读积分限制'".";";
$tables['article']['columns']['freereadprice']   ="ALTER TABLE ".tablename('fm453_site_article')." ADD "."`freereadprice` int(11) DEFAULT  NULL COMMENT '阅读者最少拥有的余额'".";";
$tables['article']['columns']['isfreereadprice'] ="ALTER TABLE ".tablename('fm453_site_article')." ADD "."`isfreereadprice` tinyint(3) DEFAULT  '0' COMMENT '是否开启阅读余额限制'".";";
$tables['article']['columns']['forceInWeixin']   ="ALTER TABLE ".tablename('fm453_site_article')." ADD "."`forceInWeixin` tinyint(3) DEFAULT  '0' COMMENT '是否只能在微信中阅读'".";";
$tables['article']['columns']['forceWxAuth']     ="ALTER TABLE ".tablename('fm453_site_article')." ADD "."`forceWxAuth` tinyint(3) DEFAULT  '0' COMMENT '是否强制要求微信网页授权'".";";
$tables['article']['columns']['forceFollow']     ="ALTER TABLE ".tablename('fm453_site_article')." ADD "."`forceFollow` tinyint(3) DEFAULT  '0' COMMENT '是否强制要求关注才能访问'".";";
$tables['article']['columns']['shareable']     ="ALTER TABLE ".tablename('fm453_site_article')." ADD "."`shareable` tinyint(3) DEFAULT  '1' COMMENT '是否允许分享转发'".";";
$tables['article']['columns']['directReadable']     ="ALTER TABLE ".tablename('fm453_site_article')." ADD "."`directReadable` tinyint(3) DEFAULT  '1' COMMENT '转发链接是否允许直接查看'".";";
$tables['article']['columns']['updatetime']      ="ALTER TABLE ".tablename('fm453_site_article')." ADD "."`updatetime` int(11) UNSIGNED NOT NULL".";";

$tables['article']['indexes']['id']           ="`id` (`id`)";
$tables['article']['indexes']['sn']           ="`sn` (`sn`)";
$tables['article']['indexes']['uniacid']      ="`uniacid` (`uniacid`)";
$tables['article']['indexes']['pcate']        ="`pcate` (`pcate`)";
$tables['article']['indexes']['ccate']        ="`ccate` (`ccate`)";
$tables['article']['indexes']['isnew']        ="`isnew` (`isnew`)";
$tables['article']['indexes']['ishot']        ="`ishot` (`ishot`)";
$tables['article']['indexes']['isdiscount']   ="`isdiscount` (`isdiscount`)";
$tables['article']['indexes']['isrecommand']  ="`isrecommand` (`isrecommand`)";
$tables['article']['indexes']['istime']       ="`istime` (`istime`)";
$tables['article']['indexes']['isdashang']    ="`isdashang` (`isdashang`)";
$tables['article']['indexes']['iscomment']    ="`iscomment` (`iscomment`)";
$tables['article']['indexes']['deleted']      ="`deleted` (`deleted`)";
$tables['article']['indexes']['a_tpl']        ="`a_tpl` (`a_tpl`)";
$tables['article']['indexes']['displayorder'] ="`displayorder` (`displayorder`)";
$tables['article']['indexes']['status']       ="`status` (`status`)";
$tables['article']['indexes']['statuscode']   ="`statuscode` (`statuscode`)";
	//结束
?>