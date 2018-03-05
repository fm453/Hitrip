<?php

	$op['service']['index']['display']=array('title'=>'显示');
	$ac['service']['index']=array(
		'title'=>'默认入口',
		'icon'=>'fa fa-inbox',
		'sn'=>'0',
		'op'=>$op['service']['index']
	);

	$op['service']['feedback']['display']=array('title'=>'全部');
	$op['service']['feedback']['detail']=array('title'=>'详情');
	$op['service']['feedback']['check']=array('title'=>'审核');
	$ac['service']['feedback']=array(
		'title'=>'维权告警',
		'icon'=>'fa fa-gavel',
		'sn'=>'10',
		'op'=>$op['service']['feedback']
	);

	$op['service']['error']['display']=array('title'=>'全部');
	$op['service']['error']['detail']=array('title'=>'详情');
	$op['service']['error']['reply']=array('title'=>'回复处理');
	$ac['service']['error']=array(
		'title'=>'报错记录',
		'icon'=>'fa fa-times-circle-o',
		'sn'=>'20',
		'op'=>$op['service']['error']
	);

	$op['service']['advise']['display']=array('title'=>'全部');
	$op['service']['advise']['detail']=array('title'=>'详情');
	$op['service']['advise']['reply']=array('title'=>'回复处理');
	$ac['service']['advise']=array(
		'title'=>'意见反馈',
		'icon'=>'fa fa-pencil-square-o',
		'sn'=>'30',
		'op'=>$op['service']['advise']
	);

	$op['service']['remind']['display']=array('title'=>'内容设定');
	$op['service']['remind']['send']=array('title'=>'推送');
	$op['service']['remind']['log']=array('title'=>'推送日志');
	$ac['service']['remind']=array(
		'title'=>'知会推送',
		'icon'=>'fa fa-bullhorn',
		'sn'=>'40',
		'op'=>$op['service']['remind']
	);

	$op['service']['ajax']['modify']=array('title'=>'编辑');
	$ac['service']['ajax']=array(
		'title'=>'Ajax操作',
		'icon'=>'fa fa fa-font',
		'sn'=>'1000',
		'op'=>$op['service']['ajax']
	);

	$do['service']=array(
		'title'=>'服务管理',
		'icon'=>'fa fa fa-meh-o',
		'sn'=>'service',
		'hide'=>array(),
		'show'=>array(),
		'ac'=>$ac['service']
	);
	//service结束
