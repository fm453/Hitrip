<?php

	$op['vfoods']['index']['display']=array('title'=>'管理');
	$ac['vfoods']['index']=array(
		'title'=>'默认入口',
		'icon'=>'fa fa-inbox',
		'sn'=>'0',
		'op'=>$op['vfoods']['index']
	);

	$op['vfoods']['shoptype']['display']=array('title'=>'显示');
	$op['vfoods']['shoptype']['post']=array('title'=>'编辑新增');
	$op['vfoods']['shoptype']['delete']=array('title'=>'删除','hidden'=>true);
	$ac['vfoods']['shoptype']=array(
		'title'=>'餐厅分类',
		'icon'=>'fa fa-edit',
		'sn'=>'10',
		'op'=>$op['vfoods']['shoptype']
	);


	$op['vfoods']['category']['display']=array('title'=>'显示');
	$op['vfoods']['category']['post']=array('title'=>'编辑/新增');
	$op['vfoods']['category']['delete']=array('title'=>'删除','hidden'=>true);
	// $op['vfoods']['category']['export']=array('title'=>'导出');
	// $op['vfoods']['category']['import']=array('title'=>'导入');
	$ac['vfoods']['category']=array(
		'title'=>'餐厅与菜系',
		'icon'=>'fa fa-barcode',
		'sn'=>'20',
		'op'=>$op['vfoods']['category']
	);

	$op['vfoods']['foods']['display']=array('title'=>'显示');
	$op['vfoods']['foods']['post']=array('title'=>'编辑新增');
	$ac['vfoods']['foods']=array(
		'title'=>'菜品管理',
		'icon'=>'fa fa-copy',
		'sn'=>'30',
		'op'=>$op['vfoods']['foods']
	);

	$op['vfoods']['order']['display']=array('title'=>'列表');
	$op['vfoods']['order']['detail']=array('title'=>'详情');
	$ac['vfoods']['order']=array(
		'title'=>'订单管理',
		'icon'=>'fa fa-puzzle-piece',
		'sn'=>'40',
		'op'=>$op['vfoods']['order']
	);

	$op['vfoods']['settings']['display']=array('title'=>'基础设置');
	$op['vfoods']['settings']['diancantypes']=array('title'=>'点餐模式');
	$op['vfoods']['settings']['waimai']=array('title'=>'外卖设置');
	$op['vfoods']['settings']['tangshi']=array('title'=>'堂食设置');
	$op['vfoods']['settings']['ziqu']=array('title'=>'自取设置');
	$op['vfoods']['settings']['links']=array('title'=>'常用链接');
	$op['vfoods']['settings']['transfer']=array('title'=>'老数据迁移','hidden'=>true);
	$ac['vfoods']['settings']=array(
		'title'=>'功能配置',
		'icon'=>'fa fa-recycle',
		'sn'=>'50',
		'op'=>$op['vfoods']['settings']
	);

	$op['vfoods']['print']['display']=array('title'=>'显示');
	$op['vfoods']['print']['post']=array('title'=>'编辑新增','hidden'=>true);
	$op['vfoods']['print']['status']=array('title'=>'打印状态','hidden'=>true);
	$ac['vfoods']['print']=array(
		'title'=>'打印机',
		'icon'=>'fa fa-search',
		'sn'=>'60',
		'op'=>$op['vfoods']['print'],
	);

	$op['vfoods']['ajax']['update']=array('title'=>'更新');
	$op['vfoods']['ajax']['delete']=array('title'=>'软删除');
	$op['vfoods']['ajax']['clear']=array('title'=>'物理删除');
	$ac['vfoods']['ajax']=array(
		'title'=>'Ajax操作',
		'icon'=>'fa fa fa-font',
		'sn'=>'1000',
		'op'=>$op['vfoods']['ajax'],
	);

	$do['vfoods']=array(
		'title'=>'微餐饮管理',
		'icon'=>'fa fa-cutlery',
		'sn'=>'foods',
		'ac'=>$ac['vfoods'],
	);
