<?php
/*
* 表单定义文件
*/

$_config = array();
$_config['title'] = "千植药妆";
$_config['status'] = 1;	//状态1可用0停用;-1删除不对外显示

//显示或隐藏权限配置array()中填入公众号uniacid
$_config['show'] = array();	//仅对这些公众号生效，权重最高
$_config['hide'] = array();	//不对这些公众号生效，权重低于show键，设置show键时失效

return $_config;
?>