<?php
$tables['needs']=array(
	'name'=>'有求必应主表',
	'type'=>array("system","needs","all")
);
$tables['needs']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_shopping_needs')." (`id` int(11) NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='有求必应主表';";

$tables['needs']['columns']['id']               ="ALTER TABLE ".tablename('fm453_shopping_needs')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['needs']['columns']['uniacid']          ="ALTER TABLE ".tablename('fm453_shopping_needs')." ADD "."`uniacid` int(11) NOT NULL COMMENT '公众号id'".";";
$tables['needs']['columns']['title']            ="ALTER TABLE ".tablename('fm453_shopping_needs')." ADD "."`title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '需求表单的标题'".";";
$tables['needs']['columns']['description']      ="ALTER TABLE ".tablename('fm453_shopping_needs')." ADD "."`description` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '需求表单的简介'".";";
$tables['needs']['columns']['content']          ="ALTER TABLE ".tablename('fm453_shopping_needs')." ADD "."`content` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '需求表单的详细介绍'".";";
$tables['needs']['columns']['res']              ="ALTER TABLE ".tablename('fm453_shopping_needs')." ADD "."`res` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '提交成功的提示消息'".";";
$tables['needs']['columns']['notice_mobile']    ="ALTER TABLE ".tablename('fm453_shopping_needs')." ADD "."`notice_mobile` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '知会手机号'".";";
$tables['needs']['columns']['notice_email']     ="ALTER TABLE ".tablename('fm453_shopping_needs')." ADD "."`notice_email` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '知会邮箱'".";";
$tables['needs']['columns']['notice_openid']    ="ALTER TABLE ".tablename('fm453_shopping_needs')." ADD "."`notice_openid` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '知会微信openid'".";";
$tables['needs']['columns']['status']           ="ALTER TABLE ".tablename('fm453_shopping_needs')." ADD "."`status` tinyint(3) NOT NULL COMMENT '状态，如是否启用'".";";
$tables['needs']['columns']['is_time']          ="ALTER TABLE ".tablename('fm453_shopping_needs')." ADD "."`is_time` tinyint(3) NOT NULL COMMENT '是否限时'".";";
$tables['needs']['columns']['is_timestart']     ="ALTER TABLE ".tablename('fm453_shopping_needs')." ADD "."`is_timestart` tinyint(3) NOT NULL COMMENT '是否限制开始时间'".";";
$tables['needs']['columns']['is_timeend']       ="ALTER TABLE ".tablename('fm453_shopping_needs')." ADD "."`is_timeend` tinyint(3) NOT NULL COMMENT '是否限制结束时间'".";";
$tables['needs']['columns']['starttime']        ="ALTER TABLE ".tablename('fm453_shopping_needs')." ADD "."`starttime` int(11) NOT NULL COMMENT '开始时间'".";";
$tables['needs']['columns']['endtime']          ="ALTER TABLE ".tablename('fm453_shopping_needs')." ADD "."`endtime` int(11) NOT NULL COMMENT '结束时间'".";";
$tables['needs']['columns']['template']         ="ALTER TABLE ".tablename('fm453_shopping_needs')." ADD "."`template` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '需求表单的模板'".";";
$tables['needs']['columns']['banner']           ="ALTER TABLE ".tablename('fm453_shopping_needs')." ADD "."`banner` text  NOT NULL COMMENT 'banner图'".";";
$tables['needs']['columns']['is_banner']        ="ALTER TABLE ".tablename('fm453_shopping_needs')." ADD "."`is_banner` tinyint(3) NOT NULL COMMENT '是否开启banner图'".";";
$tables['needs']['columns']['thumb']            ="ALTER TABLE ".tablename('fm453_shopping_needs')." ADD "."`thumb` text NOT NULL COMMENT '封面图'".";";
$tables['needs']['columns']['is_cycle']         ="ALTER TABLE ".tablename('fm453_shopping_needs')." ADD "."`is_cycle` tinyint(3)  NOT NULL COMMENT '是否开启生命周期'".";";
$tables['needs']['columns']['cycle']            ="ALTER TABLE ".tablename('fm453_shopping_needs')." ADD "."`cycle` varchar(50) NOT NULL COMMENT '表单生命周期(即隔多久视为一次新个表,月/周/天/小时/分钟)'".";";
$tables['needs']['columns']['cycle_lenth']      ="ALTER TABLE ".tablename('fm453_shopping_needs')." ADD "."`cycle_lenth` varchar(50) NOT NULL COMMENT '表单生命周期的长度'".";";
$tables['needs']['columns']['single_all_times'] ="ALTER TABLE ".tablename('fm453_shopping_needs')." ADD "."`single_all_times` int(11) NOT NULL COMMENT '单个生命周期内可报名总人数,为0不限制'".";";
$tables['needs']['columns']['single_times']     ="ALTER TABLE ".tablename('fm453_shopping_needs')." ADD "."`single_times` int(11) NOT NULL  COMMENT '单个生命周期内单会员可报名次数,为0时不限制'".";";
$tables['needs']['columns']['max_times']        ="ALTER TABLE ".tablename('fm453_shopping_needs')." ADD "."`max_times` int(11) NOT NULL  COMMENT '最大报名人次,为0时不限制'".";";
$tables['needs']['columns']['is_max']           ="ALTER TABLE ".tablename('fm453_shopping_needs')." ADD "."`is_max` tinyint(3) NOT NULL COMMENT '是否限制总参与人数'".";";
$tables['needs']['columns']['is_rec']           ="ALTER TABLE ".tablename('fm453_shopping_needs')." ADD "."`is_rec` tinyint(3) NOT NULL COMMENT '是否推荐'".";";
$tables['needs']['columns']['is_hot']           ="ALTER TABLE ".tablename('fm453_shopping_needs')." ADD "."`is_hot` tinyint(3) NOT NULL COMMENT '是否热门'".";";
$tables['needs']['columns']['is_share']         ="ALTER TABLE ".tablename('fm453_shopping_needs')." ADD "."`is_share` tinyint(3) NOT NULL COMMENT '预约信息是否可分享公开'".";";
$tables['needs']['columns']['is_dianzan']       ="ALTER TABLE ".tablename('fm453_shopping_needs')." ADD "."`is_dianzan` tinyint(3) NOT NULL COMMENT '是否允许点赞'".";";
$tables['needs']['columns']['is_pay']           ="ALTER TABLE ".tablename('fm453_shopping_needs')." ADD "."`is_pay` tinyint(3) NOT NULL COMMENT '是否开启支付'".";";
$tables['needs']['columns']['price']            ="ALTER TABLE ".tablename('fm453_shopping_needs')." ADD "."`price` int(11) NOT NULL  COMMENT '设置支付的价格'".";";
$tables['needs']['columns']['is_comment']       ="ALTER TABLE ".tablename('fm453_shopping_needs')." ADD "."`is_comment` tinyint(3) NOT NULL COMMENT '是否允许评论'".";";
$tables['needs']['columns']['is_forcelogin']    ="ALTER TABLE ".tablename('fm453_shopping_needs')." ADD "."`is_forcelogin` tinyint(3) NOT NULL COMMENT '是否强制登陆'".";";
$tables['needs']['columns']['is_forcefollow']   ="ALTER TABLE ".tablename('fm453_shopping_needs')." ADD "."`is_forcefollow` tinyint(3) NOT NULL COMMENT '是否强制关注'".";";
$tables['needs']['columns']['is_wechat']        ="ALTER TABLE ".tablename('fm453_shopping_needs')." ADD "."`is_wechat` tinyint(3) NOT NULL COMMENT '是否必须在微信中打开'".";";
$tables['needs']['columns']['deleted']          ="ALTER TABLE ".tablename('fm453_shopping_needs')." ADD "."`deleted` tinyint(3) NOT NULL COMMENT '是否删除'".";";
$tables['needs']['columns']['displayorder']     ="ALTER TABLE ".tablename('fm453_shopping_needs')." ADD "."`displayorder` int(11) NOT NULL COMMENT '排序'".";";
$tables['needs']['columns']['viewcount']        ="ALTER TABLE ".tablename('fm453_shopping_needs')." ADD "."`viewcount` int(11) NOT NULL COMMENT '浏览量'".";";
$tables['needs']['columns']['dianzancount']     ="ALTER TABLE ".tablename('fm453_shopping_needs')." ADD "."`dianzancount` int(11) NOT NULL COMMENT '点赞量'".";";
$tables['needs']['columns']['sharecount']       ="ALTER TABLE ".tablename('fm453_shopping_needs')." ADD "."`sharecount` int(11) NOT NULL COMMENT '转发分享量'".";";

$tables['needs']['columns']['createtime']       ="ALTER TABLE ".tablename('fm453_shopping_needs')." ADD "."`createtime` int(11) NOT NULL COMMENT '创建时间'".";";

$tables['needs']['indexes']['id']      ="`id` (`id`)";
$tables['needs']['indexes']['uniacid'] ="`uniacid` (`uniacid`)";
$tables['needs']['indexes']['status']  ="`status` (`status`)";
//settings结束
