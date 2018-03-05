<?php

//定义一些全局可变变量$_FM;

//是否后台管理员
$_FM['isAdminer'] = ($_W['username']==$_FM['settings']['superuser'] || $_W['username']==$_FM['settings']['mainuser']) ? true : false;
$_FM['isAdminer'] = (!isset($_FM['settings']['superuser']) && !isset($_FM['settings']['mainuser'])) ? true : $_FM['isAdminer'];

//是否超级管理员（可为公众号配置普通管理员）
$_FM['isSuper'] = ($_W['username']==$_FM['settings']['superuser']) ? true : false;

//是否普通管理员（一般为客户公众号的主管理员）
$_FM['isManager'] = ($_W['username']==$_FM['settings']['mainuser']) ? true : false;
$_FM['isManager'] = !isset($_FM['settings']['mainuser']) ? true : $_FM['isManager'];
