<?php
$tables['comment']=array(
	'name'=>'点评模块的点评记录表',
	'type'=>array("comment","system","all")
);
$tables['comment']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_duokefu_comment')." (`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`))ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='点评评论记录表' ;";

$tables['comment']['columns']['id']           ="ALTER TABLE ".tablename('fm453_duokefu_comment')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['comment']['columns']['uniacid']      ="ALTER TABLE ".tablename('fm453_duokefu_comment')." ADD "."`uniacid` int(11) NOT NULL".";";
$tables['comment']['columns']['adm_openid']   ="ALTER TABLE ".tablename('fm453_duokefu_comment')." ADD "."`adm_openid` text NOT NULL COMMENT '管理员的openid'".";";
$tables['comment']['columns']['from_openid']  ="ALTER TABLE ".tablename('fm453_duokefu_comment')." ADD "."`from_openid` varchar(50) NOT NULL COMMENT '粉丝openid'".";";
$tables['comment']['columns']['from_uid']     ="ALTER TABLE ".tablename('fm453_duokefu_comment')." ADD "."`from_uid` int(11) DEFAULT NULL COMMENT '会员id'".";";
$tables['comment']['columns']['from_channel'] ="ALTER TABLE ".tablename('fm453_duokefu_comment')." ADD "."`from_channel` varchar(255) NOT NULL COMMENT '被点评对象的来源渠道，比如OTA'".";";
$tables['comment']['columns']['username']     ="ALTER TABLE ".tablename('fm453_duokefu_comment')." ADD "."`username` varchar(100) NOT NULL COMMENT '姓名'".";";
$tables['comment']['columns']['mobile']       ="ALTER TABLE ".tablename('fm453_duokefu_comment')." ADD "."`mobile` varchar(50) NOT NULL COMMENT '手机'".";";
$tables['comment']['columns']['address']      ="ALTER TABLE ".tablename('fm453_duokefu_comment')." ADD "."`address` varchar(255) NOT NULL COMMENT '地址'".";";
$tables['comment']['columns']['starttime']    ="ALTER TABLE ".tablename('fm453_duokefu_comment')." ADD "."`starttime` int(11) NOT NULL COMMENT '开始时间'".";";
$tables['comment']['columns']['endtime']      ="ALTER TABLE ".tablename('fm453_duokefu_comment')." ADD "."`endtime` int(11) NOT NULL COMMENT '结束时间'".";";
$tables['comment']['columns']['rnumber']      ="ALTER TABLE ".tablename('fm453_duokefu_comment')." ADD "."`rnumber` varchar(50) NOT NULL COMMENT '补充号码，如房号'".";";
$tables['comment']['columns']['thumb']        ="ALTER TABLE ".tablename('fm453_duokefu_comment')." ADD "."`thumb` text NOT NULL COMMENT '单图片/首图链接'".";";
$tables['comment']['columns']['images']       ="ALTER TABLE ".tablename('fm453_duokefu_comment')." ADD "."`images` text NOT NULL COMMENT '多图片链接'".";";
$tables['comment']['columns']['remark']       ="ALTER TABLE ".tablename('fm453_duokefu_comment')." ADD "."`remark` text NOT NULL COMMENT '备注'".";";
$tables['comment']['columns']['ischecked']    ="ALTER TABLE ".tablename('fm453_duokefu_comment')." ADD "."`ischecked` int(11) NOT NULL DEFAULT '-1' COMMENT '是否通过审核,-1待0否1是'".";";
$tables['comment']['columns']['why_failure']  ="ALTER TABLE ".tablename('fm453_duokefu_comment')." ADD "."`why_failure` text NOT NULL COMMENT '点评审核失败原因'".";";
$tables['comment']['columns']['ispayed']      ="ALTER TABLE ".tablename('fm453_duokefu_comment')." ADD "."`ispayed` int(11) NOT NULL COMMENT '是否支付,0否1是'".";";
$tables['comment']['columns']['reply']        ="ALTER TABLE ".tablename('fm453_duokefu_comment')." ADD "."`reply` text NOT NULL COMMENT '商户回复'".";";
$tables['comment']['columns']['createtime']   ="ALTER TABLE ".tablename('fm453_duokefu_comment')." ADD "."`createtime` int(11) NOT NULL COMMENT '创建时间'".";";
$tables['comment']['columns']['paytime']      ="ALTER TABLE ".tablename('fm453_duokefu_comment')." ADD "."`paytime` int(11) NOT NULL COMMENT '确认奖励发放时间'".";";
$tables['comment']['columns']['pay_no']       ="ALTER TABLE ".tablename('fm453_duokefu_comment')." ADD "."`pay_no` varchar(255) NOT NULL COMMENT '奖励支付时的流水号'".";";
$tables['comment']['columns']['pay_money']    ="ALTER TABLE ".tablename('fm453_duokefu_comment')." ADD "."`pay_money` decimal(10,2) NOT NULL COMMENT '已奖励支付金额'".";";
$tables['comment']['columns']['displayorder'] ="ALTER TABLE ".tablename('fm453_duokefu_comment')." ADD "."`displayorder` int(11) NOT NULL COMMENT '排序，数字大的靠前'".";";
$tables['comment']['columns']['ispublic']     ="ALTER TABLE ".tablename('fm453_duokefu_comment')." ADD "."`ispublic` int(11) NOT NULL COMMENT '是否公开'".";";
$tables['comment']['columns']['up_count']     ="ALTER TABLE ".tablename('fm453_duokefu_comment')." ADD "."`up_count` int(11) NOT NULL COMMENT '支持数'".";";
$tables['comment']['columns']['down_count']   ="ALTER TABLE ".tablename('fm453_duokefu_comment')." ADD "."`down_count` int(11) NOT NULL COMMENT '不支持数量'".";";
$tables['comment']['columns']['view_count']   ="ALTER TABLE ".tablename('fm453_duokefu_comment')." ADD "."`view_count` int(11) NOT NULL COMMENT '浏览量'".";";
$tables['comment']['columns']['isapi']        ="ALTER TABLE ".tablename('fm453_duokefu_comment')." ADD "."`isapi` int(11) NOT NULL DEFAULT '0' COMMENT '是否为其他模块调用，0否1是'".";";
$tables['comment']['columns']['api_module']   ="ALTER TABLE ".tablename('fm453_duokefu_comment')." ADD ".
"`api_module` varchar(50) NOT NULL DEFAULT 'fm453_duokefu' COMMENT '调用该评论的模块'".";";
$tables['comment']['columns']['api_id']       ="ALTER TABLE ".tablename('fm453_duokefu_comment')." ADD "."`api_id` int(11) NOT NULL COMMENT '被评论的模块传入的关联id值'".";";
$tables['comment']['columns']['api_info']     ="ALTER TABLE ".tablename('fm453_duokefu_comment')." ADD "."`api_info` text NOT NULL COMMENT '被评论的模块传入更多信息'".";";
$tables['comment']['columns']['deleted']      ="ALTER TABLE ".tablename('fm453_duokefu_comment')." ADD "."`deleted` int(1) NOT NULL DEFAULT '0' COMMENT '是否删除'".";";
$tables['comment']['columns']['log']          ="ALTER TABLE ".tablename('fm453_duokefu_comment')." ADD "."`log` text NOT NULL COMMENT '操作记录'".";";

$tables['comment']['indexes']['id']      ="`id` (`id`)";
$tables['comment']['indexes']['uniacid'] ="`uniacid` (`uniacid`)";
//结束