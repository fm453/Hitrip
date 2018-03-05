<?php

	$op['category']['index']['display']=array('title'=>'分类列表');
	$ac['category']['index']=array(
		'title'=>'默认入口',
		'icon'=>'fa fa-inbox',
		'sn'=>'0',
		'op'=>$op['category']['index']
	);

	$op['category']['goods']['display']=array('title'=>'清单');
	$op['category']['goods']['modify']=array('title'=>'编辑');
	$op['category']['goods']['add']=array('title'=>'新增');
	$op['category']['goods']['copy']=array('title'=>'复制编辑');
	$op['category']['goods']['export']=array('title'=>'导出');
	$op['category']['goods']['import']=array('title'=>'导入');
	$ac['category']['goods']=array(
		'title'=>'产品分类',
		'icon'=>'fa fa-bars',
		'sn'=>'10',
		'op'=>$op['category']['goods']
	);

	$op['category']['brand']['display']=array('title'=>'清单');
	$op['category']['brand']['modify']=array('title'=>'编辑');
	$op['category']['brand']['add']=array('title'=>'新增');
	$op['category']['brand']['copy']=array('title'=>'复制编辑');
	$op['category']['brand']['export']=array('title'=>'导出');
	$op['category']['brand']['import']=array('title'=>'导入');
	$ac['category']['brand']=array(
		'title'=>'品牌分类',
		'icon'=>'fa fa-bars',
		'sn'=>'20',
		'op'=>$op['category']['brand']
	);

	$op['category']['partner']['display']=array('title'=>'清单');
	$op['category']['partner']['modify']=array('title'=>'编辑');
	$op['category']['partner']['add']=array('title'=>'新增');
	$op['category']['partner']['copy']=array('title'=>'复制编辑');
	$op['category']['partner']['export']=array('title'=>'导出');
	$op['category']['partner']['import']=array('title'=>'导入');
	$ac['category']['partner']=array(
		'title'=>'合作商户分类',
		'icon'=>'fa fa-bars',
		'sn'=>'30',
		'op'=>$op['category']['partner']
	);

	$op['category']['article']['display']=array('title'=>'清单');
	$op['category']['article']['modify']=array('title'=>'编辑');
	$op['category']['article']['add']=array('title'=>'新增');
	$op['category']['article']['copy']=array('title'=>'复制编辑');
	$op['category']['article']['export']=array('title'=>'导出');
	$op['category']['article']['import']=array('title'=>'导入');
	$ac['category']['article']=array(
		'title'=>'文章分类',
		'icon'=>'fa fa-bars',
		'sn'=>'40',
		'op'=>$op['category']['article']
	);


	$op['category']['ajax']['update']=array('title'=>'更新');
	$op['category']['ajax']['delete']=array('title'=>'软删除');
	$op['category']['ajax']['clear']=array('title'=>'物理删除');
	$ac['category']['ajax']=array(
		'title'=>'Ajax操作',
		'icon'=>'fa fa-font',
		'sn'=>'1000',
		'op'=>$op['category']['ajax']
	);

	$do['category']=array(
		'title'=>'分类管理',
		'icon'=>'fa fa-tasks',
		'sn'=>'category',
		'hide'=>array(),
		'show'=>array(),
		'ac'=>$ac['category']
	);
//category结束
