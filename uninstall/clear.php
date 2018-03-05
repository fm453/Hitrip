<?php
/**
 * 嗨旅行商城系统完全卸载文件
 *
 * @author Fm453
 * @url http://bbs.we7.cc/thread-9945-1-1.html
 * @guide WeEngine Team & ewei
 * 说明：删除模块全部数据；
 */
$sql = "
	DROP TABLE IF EXISTS `ims_fm453_shopping_address`;
	DROP TABLE IF EXISTS `ims_fm453_shopping_adv`;
	DROP TABLE IF EXISTS `ims_fm453_shopping_cart`;
	DROP TABLE IF EXISTS `ims_fm453_shopping_category`;
	DROP TABLE IF EXISTS `ims_fm453_shopping_dispatch`;
	DROP TABLE IF EXISTS `ims_fm453_shopping_express`;
	DROP TABLE IF EXISTS `ims_fm453_shopping_feedback`;
	DROP TABLE IF EXISTS `ims_fm453_shopping_goods`;
	DROP TABLE IF EXISTS `ims_fm453_shopping_goods_option`;
	DROP TABLE IF EXISTS `ims_fm453_shopping_goods_param`;
	DROP TABLE IF EXISTS `ims_fm453_shopping_order`;
	DROP TABLE IF EXISTS `ims_fm453_shopping_order_goods`;
	DROP TABLE IF EXISTS `ims_fm453_shopping_ppt`;
	DROP TABLE IF EXISTS `ims_fm453_shopping_product`;
	DROP TABLE IF EXISTS `ims_fm453_shopping_spec`;
	DROP TABLE IF EXISTS `ims_fm453_shopping_spec_item`;
	DROP TABLE IF EXISTS `ims_fm453_shopping_member`;
	DROP TABLE IF EXISTS `ims_fm453_shopping_commission`;
	DROP TABLE IF EXISTS `ims_fm453_shopping_rule`;
	DROP TABLE IF EXISTS `ims_fm453_shopping_rules`;
	DROP TABLE IF EXISTS `ims_fm453_shopping_share_history`;
	DROP TABLE IF EXISTS `ims_fm453_shopping_credit_request`;
	DROP TABLE IF EXISTS `ims_fm453_shopping_credit_award`;
";

pdo_run($sql);
