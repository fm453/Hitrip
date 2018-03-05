<?php

	$op['article']['index']['display']=array('title'=>'显示');
	$ac['article']['index']=array(
		'title'=>'默认入口',
		'icon'=>'fa fa-inbox',
		'sn'=>'0',
		'op'=>$op['article']['index']
	);

	$op['article']['list']['display']=array('title'=>'全部');
	$ac['article']['list']=array(
		'title'=>'文章列表',
		'icon'=>'fa fa-barcode',
		'sn'=>'10',
		'op'=>$op['article']['list']
	);

	$op['article']['detail']['display']=array('title'=>'文章');
	$op['article']['detail']['modify']=array('title'=>'编辑保存');
	$ac['article']['detail']=array(
		'title'=>'编辑|新增',
		'icon'=>'fa fa-edit',
		'sn'=>'20',
		'op'=>$op['article']['detail']
	);

	$op['article']['recyle']['display']=array('title'=>'显示');
	$ac['article']['recyle']=array(
		'title'=>'回收站',
		'icon'=>'fa fa-recycle',
		'sn'=>'30',
		'op'=>$op['article']['recyle']
	);

	$op['article']['copy']['display']=array('title'=>'显示');
	$ac['article']['copy']=array(
		'title'=>'复制增加',
		'icon'=>'fa fa-copy',
		'sn'=>'40',
		'op'=>$op['article']['copy']
	);

	$op['article']['query']['display']=array('title'=>'概览');
	$op['article']['query']['search']=array('title'=>'检索');
	$ac['article']['query']=array(
		'title'=>'查询概览',
		'icon'=>'fa fa-search',
		'sn'=>'60',
		'op'=>$op['article']['query']
	);

	$op['article']['ajax']['update']=array('title'=>'更新');
	$op['article']['ajax']['delete']=array('title'=>'软删除');
	$op['article']['ajax']['clear']=array('title'=>'物理删除');
	$ac['article']['ajax']=array(
		'title'=>'Ajax操作',
		'icon'=>'fa fa fa-font',
		'sn'=>'1000',
		'op'=>$op['article']['ajax']
	);

	$do['article']=array(
		'title'=>'文章管理',
		'icon'=>'fa fa-list-ol',
		'sn'=>'article',
		'hide'=>array(),
		'show'=>array(),
		'ac'=>$ac['article']
	);
//article结束
