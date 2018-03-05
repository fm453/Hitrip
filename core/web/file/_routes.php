<?php

	$op['file']['index']['display']=array('title'=>'显示');
	$ac['file']['index']=array(
		'title'=>'默认入口',
		'icon'=>'fa fa-inbox',
		'sn'=>'0',
		'op'=>$op['file']['index']
	);

	$op['file']['list']['display']=array('title'=>'汇总清单');
	$ac['file']['list']=array(
		'title'=>'文件清单',
		'icon'=>'fa fa-files-o',
		'sn'=>'10',
		'op'=>$op['file']['list']
	);

	$op['file']['image']['display']=array('title'=>'在线编辑页');
	$op['file']['image']['modify']=array('title'=>'提交处理');
	$ac['file']['image']=array(
		'title'=>'图片处理',
		'icon'=>'fa fa-file-image-o',
		'sn'=>'20',
		'op'=>$op['file']['image']
	);

	$op['file']['ajax']['modify']=array('title'=>'编辑');
	$ac['file']['ajax']=array(
		'title'=>'Ajax操作',
		'icon'=>'fa fa fa-font',
		'sn'=>'1000',
		'op'=>$op['file']['ajax']
	);

	$do['file']=array(
		'title'=>'文件管理',
		'icon'=>'fa fa-file',
		'sn'=>'file',
		'hide'=>array(),
		'show'=>array(),
		'ac'=>$ac['file']
	);
//file结束
?>
