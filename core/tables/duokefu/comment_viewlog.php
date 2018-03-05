<?php
$tables['comment_viewlog']=array(
	'name'=>'点评浏览操作记录表',
	'type'=>array("service","site","system","all")
);
$tables['comment_viewlog']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_duokefu_comment_viewlog')." (`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`))ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='点评评论浏览操作记录表' ;";

$tables['comment_viewlog']['columns']['id']          ="ALTER TABLE ".tablename('fm453_duokefu_comment_viewlog')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['comment_viewlog']['columns']['cid']         ="ALTER TABLE ".tablename('fm453_duokefu_comment_viewlog')." ADD "."`cid` int(11) NOT NULL COMMENT '评论id'".";";
$tables['comment_viewlog']['columns']['from_openid'] ="ALTER TABLE ".tablename('fm453_duokefu_comment_viewlog')." ADD "."`from_openid` varchar(50) NOT NULL".";";
$tables['comment_viewlog']['columns']['from_uid']    ="ALTER TABLE ".tablename('fm453_duokefu_comment_viewlog')." ADD "."`from_uid` int(11) NOT NULL".";";
$tables['comment_viewlog']['columns']['do']          ="ALTER TABLE ".tablename('fm453_duokefu_comment_viewlog')." ADD "."`do` varchar(50) NOT NULL DEFAULT 'view' COMMENT '执行的动作'".";";
$tables['comment_viewlog']['columns']['username']    ="ALTER TABLE ".tablename('fm453_duokefu_comment_viewlog')." ADD "."`username` varchar(255) NOT NULL DEFAULT 'view' COMMENT '点评者昵称'".";";
$tables['comment_viewlog']['columns']['avatar']      ="ALTER TABLE ".tablename('fm453_duokefu_comment_viewlog')." ADD "."`avatar` varchar(500) NOT NULL COMMENT '粉丝头像'".";";
$tables['comment_viewlog']['columns']['createtime']  ="ALTER TABLE ".tablename('fm453_duokefu_comment_viewlog')." ADD "."`createtime` int(11) NOT NULL COMMENT '记录时间'".";";


$tables['comment_viewlog']['indexes']['id']  ="`id` (`id`)";
$tables['comment_viewlog']['indexes']['cid'] ="`cid` (`cid`)";
//结束
