<?php

$sql="
DELETE FROM ".tablename('modules_bindings')." WHERE `module` LIKE '%fm453_shopping%' AND `entry` LIKE '%menu%'  ;
INSERT INTO ".tablename('modules_bindings')." (`module`, `entry`, `call`, `title`, `do`, `state`, `direct`, `url`, `icon`, `displayorder`) VALUES
('fm453_shopping', 'menu', '', '订单管理', 'order', '', 0, '', 'fa fa-archive', 0),
('fm453_shopping', 'menu', '', '商品管理', 'goods', '', 0, '', 'fa fa-cubes', 0),
('fm453_shopping', 'menu', '', '分类管理', 'category', '', 0, '', 'fa fa-database', 0),
('fm453_shopping', 'menu', '', '幻灯片管理', 'adv', '', 0, '', 'fa fa-file-powerpoint-o', 0),
('fm453_shopping', 'menu', '', 'Banner图管理', 'banner', '', 0, '', 'fa fa-file-picture-o', 0),
('fm453_shopping', 'menu', '', '积分兑换管理', 'credit', '', 0, '', 'fa fa-money', 0),
('fm453_shopping', 'menu', '', '积分商品设置', 'award', '', 0, '', 'fa fa-gift', 0),
('fm453_shopping', 'menu', '', 'CRM会员管理', 'charge', '', 0, '', 'fa fa-group', 0),
('fm453_shopping', 'menu', '', '代理管理', 'fansmanager', '', 0, '', 'fa fa-briefcase', 0),
('fm453_shopping', 'menu', '', '佣金审核', 'commission', '', 0, '', 'fa fa-legal', 0),
('fm453_shopping', 'menu', '', '发货方式', 'dispatch', '', 0, '', 'fa fa-truck', 0),
('fm453_shopping', 'menu', '', '分销规则', 'rules', '', 0, '', 'fa fa-share-alt-square', 0),
('fm453_shopping', 'menu', '', '维权售后', 'notice', '', 0, '', 'fa fa-exclamation-circle', 0),
('fm453_shopping', 'menu', '', '广告管理', 'ad', '', 0, '', 'fa fa-cloud-upload', 0),
('fm453_shopping', 'menu', '', '系统配置', 'modset', '', 0, '', 'fa fa-cogs', 0);
";