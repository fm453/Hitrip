<?php

	$op['api']['index']['display']=array('title'=>'显示');
	$ac['api']['index']=array(
		'title'=>'默认入口',
		'icon'=>'fa fa-inbox',
		'sn'=>'0',
		'op'=>$op['api']['index']
	);

	$op['api']['dashang']['display']=array('title'=>'显示');
	$op['api']['dashang']['modify']=array('title'=>'编辑');
	$op['api']['dashang']['test']=array('title'=>'编辑');
	$ac['api']['dashang']=array(
		'title'=>'打赏管理',
		'icon'=>'fa fa-flag-checkered',
		'sn'=>'30',
		'op'=>$op['api']['dashang']
	);

	$op['api']['changyan']['display']=array('title'=>'显示');
	$op['api']['changyan']['modify']=array('title'=>'编辑');
	$op['api']['changyan']['test']=array('title'=>'测试');
	$ac['api']['changyan']=array(
		'title'=>'畅言评论',
		'icon'=>'fa fa-comments-o',
		'sn'=>'40',
		'op'=>$op['api']['changyan']
	);

	$op['api']['hiride']['display']=array('title'=>'显示');
	$op['api']['hiride']['modify']=array('title'=>'编辑');
	$op['api']['hiride']['test']=array('title'=>'测试');
	$ac['api']['hiride']=array(
		'title'=>'嗨骑单车',
		'icon'=>'fa fa-link',
		'sn'=>'40',
		'op'=>$op['api']['hiride']
	);

	$op['api']['ajax']['modify']=array('title'=>'编辑');
	$ac['api']['ajax']=array(
		'title'=>'Ajax操作',
		'icon'=>'fa fa fa-font',
		'sn'=>'1000',
		'op'=>$op['api']['ajax']
	);

	$do['api']=array(
		'title'=>'接口管理',
		'icon'=>'fa fa-soundcloud',
		'sn'=>'api',
		'hide'=>array(),
		'show'=>array(),
		'ac'=>$ac['api']
	);
	//api结束

