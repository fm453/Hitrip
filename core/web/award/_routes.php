<?php

	$op['award']['index']['display']=array('title'=>'显示');
	$ac['award']['index']=array(
		'title'=>'默认入口',
		'icon'=>'fa fa-inbox',
		'sn'=>'0',
		'op'=>$op['award']['index']
	);

	$op['award']['jifen']['display']=array('title'=>'清单');
	$op['award']['jifen']['modify']=array('title'=>'编辑');
	$ac['award']['jifen']=array(
		'title'=>'积分奖品',
		'icon'=>'fa fa-history',
		'sn'=>'10',
		'op'=>$op['award']['jifen']
	);

	$op['award']['vshop']['display']=array('title'=>'显示');
	$op['award']['vshop']['modify']=array('title'=>'编辑');
	$ac['award']['vshop']=array(
		'title'=>'积分商城',
		'icon'=>'fa fa-gift',
		'sn'=>'20',
		'op'=>$op['award']['vshop']
	);

	$op['award']['ajax']['modify']=array('title'=>'编辑');
	$ac['award']['ajax']=array(
		'title'=>'Ajax操作',
		'icon'=>'fa fa fa-font',
		'sn'=>'1000',
		'op'=>$op['award']['ajax']
	);

	$do['award']=array(
		'title'=>'积分营销',
		'icon'=>'fa fa-gift',
		'sn'=>'award',
		'hide'=>array(),
		'show'=>array(),
		'ac'=>$ac['award']
	);
//award结束
