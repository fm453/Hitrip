<?php

	$op['order']['index']['display']=array('title'=>'订单模块');
	$ac['order']['index']=array(
		'title'=>'默认入口',
		'icon'=>'fa fa-inbox',
		'sn'=>'0',
		'op'=>$op['order']['index']
	);

	$op['order']['list']['display']=array('title'=>'清单');
	$op['order']['list']['export']=array('title'=>'导出');
	$ac['order']['list']=array(
		'title'=>'订单列表',
		'icon'=>'fa fa-barcode',
		'sn'=>'10',
		'op'=>$op['order']['list']
	);

	$op['order']['detail']['display']=array('title'=>'查看');
	$op['order']['detail']['print']=array('title'=>'打印');
	$ac['order']['detail']=array(
		'title'=>'订单详情',
		'icon'=>'fa fa-print',
		'sn'=>'20',
		'op'=>$op['order']['detail']
	);

	$op['order']['recyle']['display']=array('title'=>'显示');
	$op['order']['recyle']['modify']=array('title'=>'管理');
	$ac['order']['recyle']=array(
		'title'=>'订单回收站',
		'icon'=>'fa fa-recycle',
		'sn'=>'30',
		'op'=>$op['order']['recyle']
	);

	$op['order']['bygoods']['display']=array('title'=>'清单');
	$op['order']['bygoods']['export']=array('title'=>'导出');
	$ac['order']['bygoods']=array(
		'title'=>'按产品检索订单',
		'icon'=>'fa fa-search',
		'sn'=>'50',
		'op'=>$op['order']['bygoods']
	);

	$op['order']['bygoodtpl']['display']=array('title'=>'清单');
	$op['order']['bygoodtpl']['export']=array('title'=>'导出');
	$ac['order']['bygoodtpl']=array(
		'title'=>'按模型检索订单',
		'icon'=>'fa fa-search',
		'sn'=>'60',
		'op'=>$op['order']['bygoodtpl']
	);

	$op['order']['bymember']['display']=array('title'=>'清单');
	$op['order']['bymember']['export']=array('title'=>'导出');
	$ac['order']['bymember']=array(
		'title'=>'按会员检索订单',
		'icon'=>'fa fa-search',
		'sn'=>'70',
		'op'=>$op['order']['bymember']
	);

	$op['order']['bycategory']['display']=array('title'=>'清单');
	$op['order']['bycategory']['export']=array('title'=>'导出');
	$ac['order']['bycategory']=array(
		'title'=>'按产品的分类检索订单',
		'icon'=>'fa fa-search',
		'sn'=>'80',
		'op'=>$op['order']['bycategory']
	);

	$op['order']['bypartner']['display']=array('title'=>'清单');
	$op['order']['bypartner']['export']=array('title'=>'导出');
	$ac['order']['bypartner']=array(
		'title'=>'按商家检索订单',
		'icon'=>'fa fa-search',
		'sn'=>'80',
		'op'=>$op['order']['bypartner']
	);

	$op['order']['ajax']['modify']=array('title'=>'编辑');
	$op['order']['ajax']['delete']=array('title'=>'软删除');
	$op['order']['ajax']['clear']=array('title'=>'物理删除');
	$ac['order']['ajax']=array(
		'title'=>'Ajax操作',
		'icon'=>'fa fa fa-font',
		'sn'=>'1000',
		'op'=>$op['order']['ajax']
	);

	$do['order']=array(
		'title'=>'订单管理',
		'icon'=>'fa fa-archive',
		'sn'=>'order',
		'hide'=>array(),
		'show'=>array(),
		'ac'=>$ac['order']
	);
	//order结束


