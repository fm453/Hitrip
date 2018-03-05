<?php

	$op['dispatch']['index']['display']=array('title'=>'物流配送');
	$ac['dispatch']['index']=array(
		'title'=>'默认入口',
		'icon'=>'fa fa-inbox',
		'sn'=>'0',
		'op'=>$op['dispatch']['index']
	);

	$op['dispatch']['express']['display']=array('title'=>'清单');
	$op['dispatch']['express']['modify']=array('title'=>'编辑');
	$op['dispatch']['express']['add']=array('title'=>'新增');
	$op['dispatch']['express']['copy']=array('title'=>'复制编辑');
	$op['dispatch']['express']['export']=array('title'=>'导出');
	$op['dispatch']['express']['import']=array('title'=>'导入');
	$ac['dispatch']['express']=array(
		'title'=>'物流公司',
		'icon'=>'fa fa-flag-checkered',
		'sn'=>'10',
		'op'=>$op['dispatch']['express']
	);

	$op['dispatch']['area']['display']=array('title'=>'清单');
	$op['dispatch']['area']['modify']=array('title'=>'编辑');
	$op['dispatch']['area']['add']=array('title'=>'新增');
	$op['dispatch']['area']['copy']=array('title'=>'复制编辑');
	$op['dispatch']['area']['export']=array('title'=>'导出');
	$op['dispatch']['area']['import']=array('title'=>'导入');
	$ac['dispatch']['area']=array(
		'title'=>'配送区域',
		'icon'=>'fa fa-flag-checkered',
		'sn'=>'20',
		'op'=>$op['dispatch']['area']
	);

	$op['dispatch']['ziti']['display']=array('title'=>'清单');
	$op['dispatch']['ziti']['modify']=array('title'=>'编辑');
	$op['dispatch']['ziti']['add']=array('title'=>'新增');
	$op['dispatch']['ziti']['copy']=array('title'=>'复制编辑');
	$op['dispatch']['ziti']['export']=array('title'=>'导出');
	$op['dispatch']['ziti']['import']=array('title'=>'导入');
	$ac['dispatch']['ziti']=array(
		'title'=>'自提点',
		'icon'=>'fa fa-flag-checkered',
		'sn'=>'30',
		'op'=>$op['dispatch']['ziti']
	);

	$op['dispatch']['yunfei']['display']=array('title'=>'配送方式');
	$op['dispatch']['yunfei']['modify']=array('title'=>'编辑规则');
	$op['dispatch']['yunfei']['add']=array('title'=>'新增');
	$op['dispatch']['yunfei']['copy']=array('title'=>'复制编辑');
	$op['dispatch']['yunfei']['export']=array('title'=>'导出');
	$op['dispatch']['yunfei']['import']=array('title'=>'导入');
	$ac['dispatch']['yunfei']=array(
		'title'=>'配送费',
		'icon'=>'fa fa-flag-checkered',
		'sn'=>'40',
		'op'=>$op['dispatch']['yunfei']
	);

	$op['dispatch']['ajax']['update']=array('title'=>'更新');
	$op['dispatch']['ajax']['delete']=array('title'=>'软删除');
	$op['dispatch']['ajax']['clear']=array('title'=>'物理删除');
	$ac['dispatch']['ajax']=array(
		'title'=>'Ajax操作',
		'icon'=>'fa fa fa-font',
		'sn'=>'1000',
		'op'=>$op['dispatch']['ajax']
	);

	$do['dispatch']=array(
		'title'=>'物流配送',
		'icon'=>'fa fa-truck',
		'sn'=>'dispatch',
		'hide'=>array(),
		'show'=>array(),
		'ac'=>$ac['dispatch']
	);
//dispatch结束