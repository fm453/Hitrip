<?php

	$op['help']['index']['display']=array('title'=>'帮助显示');
	$ac['help']['index']=array(
		'title'=>'默认入口',
		'icon'=>'fa fa-inbox',
		'sn'=>'0',
		'op'=>$op['help']['index']
	);

	$op['help']['manual']['display']=array('title'=>'显示');
	$ac['help']['manual']=array(
		'title'=>'后台使用指南',
		'icon'=>'fa fa-th',
		'sn'=>'10',
		'op'=>$op['help']['manual']
	);

	$op['help']['develop']['display']=array('title'=>'显示');
	$ac['help']['develop']=array(
		'title'=>'二次开发指南',
		'icon'=>'fa fa-th',
		'sn'=>'20',
		'op'=>$op['help']['develop']
	);

	$op['help']['status']['display']=array('title'=>'显示');
	$op['help']['status']['modify']=array('title'=>'编辑');
	$ac['help']['status']=array(
		'title'=>'数据状态说明',
		'icon'=>'fa fa-th',
		'sn'=>'30',
		'op'=>$op['help']['status']
	);

	$do['help']=array(
		'title'=>'操作手册',
		'icon'=>'fa fa-file-text',
		'sn'=>'9998',
		'ac'=>$ac['help']
	);
//help结束
