<?php

	$op['goods']['index']['display']=array('title'=>'产品管理');
	$ac['goods']['index']=array(
		'title'=>'默认入口',
		'icon'=>'fa fa-inbox',
		'sn'=>'0',
		'op'=>$op['goods']['index']
	);

	$op['goods']['list']['display']=array('title'=>'产品列表');
	$op['goods']['list']['export']=array('title'=>'导出');
	$op['goods']['list']['import']=array('title'=>'导入');
	$ac['goods']['list']=array(
		'title'=>'产品列表',
		'icon'=>'fa fa-barcode',
		'sn'=>'10',
		'op'=>$op['goods']['list']
	);

	$op['goods']['detail']['display']=array('title'=>'显示');
	$op['goods']['detail']['stock']=array('title'=>'库存管理');
	$op['goods']['detail']['price']=array('title'=>'价格管理');
	$op['goods']['detail']['modify']=array('title'=>'编辑保存');
	$ac['goods']['detail']=array(
		'title'=>'编辑|新增',
		'icon'=>'fa fa-edit',
		'sn'=>'20',
		'op'=>$op['goods']['detail']
	);

	$op['goods']['recyle']['display']=array('title'=>'全部产品');
	$ac['goods']['recyle']=array(
		'title'=>'产品回收站',
		'icon'=>'fa fa-recycle',
		'sn'=>'30',
		'op'=>$op['goods']['recyle']
	);

	$op['goods']['copy']['display']=array('title'=>'复制编辑');
	$ac['goods']['copy']=array(
		'title'=>'复制增加',
		'icon'=>'fa fa-copy',
		'sn'=>'40',
		'op'=>$op['goods']['copy']
	);

	$op['goods']['addons']['display']=array('title'=>'显示');
	$op['goods']['addons']['param']=array('title'=>'参数');
	$op['goods']['addons']['label']=array('title'=>'标签');
	$op['goods']['addons']['option']=array('title'=>'规格');
	$op['goods']['addons']['spec']=array('title'=>'规格名');
	$op['goods']['addons']['specitem']=array('title'=>'规格项');
	$op['goods']['addons']['goodstpl']=array('title'=>'产品模型参数');
	$ac['goods']['addons']=array(
		'title'=>'附加参数',
		'icon'=>'fa fa-puzzle-piece',
		'sn'=>'50',
		'op'=>$op['goods']['addons']
	);

	$op['goods']['query']['display']=array('title'=>'概览');
	$op['goods']['query']['search']=array('title'=>'检索');
	$ac['goods']['query']=array(
		'title'=>'查询概览',
		'icon'=>'fa fa-search',
		'sn'=>'60',
		'op'=>$op['goods']['query']
	);

	$op['goods']['tpl']['display']=array('title'=>'概览');
	$op['goods']['tpl']['modify']=array('title'=>'编辑');
	$op['goods']['tpl']['delete']=array('title'=>'删除');
	$ac['goods']['tpl']=array(
		'title'=>'产品模型',
		'icon'=>'fa fa-table',
		'sn'=>'70',
		'op'=>$op['goods']['tpl']
	);

	$op['goods']['ajax']['update']=array('title'=>'更新');
	$op['goods']['ajax']['delete']=array('title'=>'软删除');
	$op['goods']['ajax']['clear']=array('title'=>'物理删除');
	$ac['goods']['ajax']=array(
		'title'=>'Ajax操作',
		'icon'=>'fa fa fa-font',
		'sn'=>'1000',
		'op'=>$op['goods']['ajax']
	);

	$do['goods']=array(
		'title'=>'商品管理',
		'icon'=>'fa fa-cubes',
		'sn'=>'goods',
		'ac'=>$ac['goods']
	);
//goods结束
