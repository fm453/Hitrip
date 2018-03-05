<?php

	$op['crm']['index']['display']=array('title'=>'会员板块');
	$ac['crm']['index']=array(
		'title'=>'默认入口',
		'icon'=>'fa fa-inbox',
		'sn'=>'0',
		'op'=>$op['crm']['index']
	);

	$op['crm']['member']['display']=array('title'=>'用户列表');
	$op['crm']['member']['modify']=array('title'=>'编辑');
	$op['crm']['member']['export']=array('title'=>'导出');
	$op['crm']['member']['import']=array('title'=>'导入');
	$op['crm']['member']['query']=array('title'=>'查询');
	$ac['crm']['member']=array(
		'title'=>'用户管理',
		'icon'=>'fa fa-group',
		'sn'=>'10',
		'op'=>$op['crm']['member']
	);

	$op['crm']['agent']['display']=array('title'=>'会员清单');
	$op['crm']['agent']['modify']=array('title'=>'编辑');
	$op['crm']['agent']['export']=array('title'=>'导出');
	$op['crm']['agent']['import']=array('title'=>'导入');
	$ac['crm']['agent']=array(
		'title'=>'会员管理',
		'icon'=>'fa fa-user',
		'sn'=>'20',
		'op'=>$op['crm']['agent']
	);

	$op['crm']['partner']['display']=array('title'=>'商户清单');
	$op['crm']['partner']['modify']=array('title'=>'编辑');
	$op['crm']['partner']['add']=array('title'=>'新增');
	$op['crm']['partner']['copy']=array('title'=>'复制编辑');
	$op['crm']['partner']['export']=array('title'=>'导出');
	$op['crm']['partner']['import']=array('title'=>'导入');
	$ac['crm']['partner']=array(
		'title'=>'商户管理',
		'icon'=>'fa fa-bank',
		'sn'=>'30',
		'op'=>$op['crm']['partner']
	);

	$op['crm']['ajax']['modify']=array('title'=>'编辑');
	$op['crm']['ajax']['delete']=array('title'=>'删除');
	$ac['crm']['ajax']=array(
		'title'=>'Ajax操作',
		'icon'=>'fa fa fa-font',
		'sn'=>'1000',
		'op'=>$op['crm']['ajax']
	);

	$do['crm']=array(
		'title'=>'客户管理',
		'icon'=>'fa fa-child',
		'sn'=>'crm',
		'hide'=>array(),
		'show'=>array(),
		'ac'=>$ac['crm']
	);
//crm结束
