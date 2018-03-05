<?php

	$op['comment']['index']['display']=array('title'=>'评论');
	$ac['comment']['index']=array(
		'title'=>'默认入口',
		'icon'=>'fa fa-inbox',
		'sn'=>'0',
		'op'=>$op['comment']['index']
	);

	$op['comment']['dianping']['display']=array('title'=>'列表');
	$op['comment']['dianping']['detail']=array('title'=>'详情');
	$op['comment']['dianping']['check']=array('title'=>'审核');
	$op['comment']['dianping']['export']=array('title'=>'导出');
	$op['comment']['dianping']['import']=array('title'=>'导入');
	$ac['comment']['dianping']=array(
		'title'=>'点评管理',
		'icon'=>'fa fa-flag-checkered',
		'sn'=>'10',
		'op'=>$op['comment']['dianping']
	);

	$op['comment']['changyang']['display']=array('title'=>'列表');
	$op['comment']['changyang']['detail']=array('title'=>'详情');
	$op['comment']['changyang']['check']=array('title'=>'审核');
	$op['comment']['changyang']['export']=array('title'=>'导出');
	$op['comment']['changyang']['import']=array('title'=>'导入');
	$ac['comment']['changyang']=array(
		'title'=>'搜狐畅言评论管理',
		'icon'=>'fa fa-flag-checkered',
		'sn'=>'20',
		'op'=>$op['comment']['changyang']
	);

	$op['comment']['ajax']['update']=array('title'=>'更新');
	$op['comment']['ajax']['delete']=array('title'=>'软删除');
	$op['comment']['ajax']['clear']=array('title'=>'物理删除');
	$ac['comment']['ajax']=array(
		'title'=>'Ajax操作',
		'icon'=>'fa fa fa-font',
		'sn'=>'1000',
		'op'=>$op['comment']['ajax']
	);

	$do['comment']=array(
		'title'=>'评论管理',
		'icon'=>'fa fa-pencil-square-o',
		'sn'=>'comment',
		'ac'=>$ac['comment']
	);
//comment结束