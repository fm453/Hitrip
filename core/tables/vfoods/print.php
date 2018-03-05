<?php
$tables['print']=array(
	'name'=>'打印机',
	'type'=>array("foods","vfoods","system","all")
);
$tables['print']['sql']="CREATE TABLE IF NOT EXISTS ".tablename('fm453_vfoods_print')." (`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`))ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='打印机' ;";

$tables['print']['columns']['id']        ="ALTER TABLE ".tablename('fm453_vfoods_print')." ADD "."`id` int(11) NOT NULL AUTO_INCREMENT".";";
$tables['print']['columns']['uniacid']   ="ALTER TABLE ".tablename('fm453_vfoods_print')." ADD "."`uniacid` int(11) UNSIGNED NOT NULL COMMENT '所属公众账号'".";";
$tables['print']['columns']['cateid']    ="ALTER TABLE ".tablename('fm453_vfoods_print')." ADD "."`cateid` int(10) NOT NULL COMMENT ''".";";
$tables['print']['columns']['deviceno']  ="ALTER TABLE ".tablename('fm453_vfoods_print')." ADD "."`deviceno` varchar(20) NOT NULL COMMENT ''".";";
$tables['print']['columns']['key']       ="ALTER TABLE ".tablename('fm453_vfoods_print')." ADD "."`key` varchar(20) NOT NULL COMMENT ''".";";
$tables['print']['columns']['printtime'] ="ALTER TABLE ".tablename('fm453_vfoods_print')." ADD "."`printtime` int(10) UNSIGNED NOT NULL COMMENT ''".";";
$tables['print']['columns']['qr']        ="ALTER TABLE ".tablename('fm453_vfoods_print')." ADD "."`qr` varchar(200) NOT NULL COMMENT ''".";";
$tables['print']['columns']['enabled']   ="ALTER TABLE ".tablename('fm453_vfoods_print')." ADD "."`enabled` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '是否启用'".";";

$tables['print']['indexes']['id']       ="`id` (`id`)";
$tables['print']['indexes']['uniacid']  ="`uniacid` (`uniacid`)";
$tables['print']['indexes']['deviceno'] ="`deviceno` (`deviceno`)";

?>
