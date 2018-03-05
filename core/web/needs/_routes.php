<?php
	$op['needs']['index']['display']=array('title'=>'显示');
	$ac['needs']['index']=array(
		'title'=>'默认入口',
		'icon'=>'fa fa-inbox',
		'sn'=>'0',
		'op'=>$op['needs']['index']
	);

	$op['needs']['diy']['display']=array('title'=>'全部表单');
	$op['needs']['diy']['detail']=array('title'=>'表单详情');
	$op['needs']['diy']['data']=array('title'=>'表单数据');
	$op['needs']['diy']['new']=array('title'=>'新建表单');
	$op['needs']['diy']['reply']=array('title'=>'回复处理');
	$ac['needs']['diy']=array(
		'title'=>'自定义表单',
		'icon'=>'fa fa-calendar',
		'sn'=>'10',
		'op'=>$op['needs']['diy']
	);

	$op['needs']['ajax']['modify']=array('title'=>'编辑');
	$ac['needs']['ajax']=array(
		'title'=>'Ajax操作',
		'icon'=>'fa fa-font',
		'sn'=>'1000',
		'op'=>$op['needs']['ajax']
	);

	$do['needs']=array(
		'title'=>'有求必应',
		'icon'=>'fa fa-magnet',
		'sn'=>'needs',
		'hide'=>array(),
		'show'=>array(),
		'ac'=>$ac['needs']
	);
	//needs结束
?>