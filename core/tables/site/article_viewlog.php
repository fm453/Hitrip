<?php
$tables['article_viewlog']=array(
	'name'=>'文章浏览操作记录表',
	'type'=>array("site","system","all")
);
$tables['article_viewlog']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_site_article_viewlog')." (`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`))ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文章浏览操作记录表' ;";

$tables['article_viewlog']['columns']['id']       ="ALTER TABLE ".tablename('fm453_site_article_viewlog')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['article_viewlog']['columns']['artid']    ="ALTER TABLE ".tablename('fm453_site_article_viewlog')." ADD "."`artid` int(11) NOT NULL COMMENT '文章id(从商城文章表中读取)'".";";
$tables['article_viewlog']['columns']['arttitle'] ="ALTER TABLE ".tablename('fm453_site_article_viewlog')." ADD "."`arttitle` varchar(500) NOT NULL COMMENT '文章标题'".";";
$tables['article_viewlog']['columns']['openid']   ="ALTER TABLE ".tablename('fm453_site_article_viewlog')." ADD "."`openid` varchar(50) NOT NULL".";";
$tables['article_viewlog']['columns']['uid']      ="ALTER TABLE ".tablename('fm453_site_article_viewlog')." ADD "."`uid` int(11) NOT NULL".";";
$tables['article_viewlog']['columns']['do']       ="ALTER TABLE ".tablename('fm453_site_article_viewlog')." ADD "."`do` varchar(50) NOT NULL DEFAULT 'view' COMMENT '执行的动作(阅读、点赞、打赏等)'".";";
$tables['article_viewlog']['columns']['avatar']   ="ALTER TABLE ".tablename('fm453_site_article_viewlog')." ADD "."`avatar` varchar(500) NOT NULL COMMENT '粉丝头像'".";";
$tables['article_viewlog']['columns']['viewtime'] ="ALTER TABLE ".tablename('fm453_site_article_viewlog')." ADD "."`viewtime` int(11) NOT NULL COMMENT '访问时间'".";";

$tables['article_viewlog']['indexes']['id']    ="`id` (`id`)";
$tables['article_viewlog']['indexes']['artid'] ="`artid` (`artid`)";
$tables['article_viewlog']['indexes']['uid']   ="`uid` (`uid`)";
$tables['article_viewlog']['indexes']['do']    ="`do` (`do`)";
//结束
?>
