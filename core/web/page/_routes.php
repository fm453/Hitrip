<?php

	$op['page']['index']['display']=array('title'=>'页面管理');
	$ac['page']['index']=array(
		'title'=>'默认入口',
		'icon'=>'fa fa-inbox',
		'sn'=>'0',
		'op'=>$op['page']['index']
	);

	$op['page']['model']['display']=array('title'=>'全部模型');
	$op['page']['model']['add']=array('title'=>'新增');
	$op['page']['model']['modify']=array('title'=>'编辑');
	$op['page']['model']['copy']=array('title'=>'复制');
	$op['page']['model']['param']=array('title'=>'参数');
	$op['page']['model']['delete']=array('title'=>'删除');
	$ac['page']['model']=array(
		'title'=>'模型管理',
		'icon'=>'fa fa-database',
		'sn'=>'10',
		'op'=>$op['page']['model']
	);

	$op['page']['instance']['display']=array('title'=>'全部实例');
	$op['page']['instance']['add']=array('title'=>'新增');
	$op['page']['instance']['modify']=array('title'=>'编辑');
	$op['page']['instance']['copy']=array('title'=>'复制');
	$op['page']['instance']['delete']=array('title'=>'删除');
	$ac['page']['instance']=array(
		'title'=>'实例管理',
		'icon'=>'fa fa-cube',
		'sn'=>'20',
		'op'=>$op['page']['instance']
	);

	$op['page']['content']['display']=array('title'=>'全部内容');
	$op['page']['content']['add']=array('title'=>'新增');
	$op['page']['content']['modify']=array('title'=>'编辑');
	$op['page']['content']['copy']=array('title'=>'复制');
	$op['page']['content']['delete']=array('title'=>'删除');
	$ac['page']['content']=array(
		'title'=>'内容管理',
		'icon'=>'fa fa-cube',
		'sn'=>'40',
		'op'=>$op['page']['content']
	);

	$op['page']['addon']['pcate']=array('title'=>'一级');		//用于动态输出父级类的模板
	$op['page']['addon']['ccate']=array('title'=>'二级');		//用于动态输出子级类的模板
	$op['page']['addon']['node']=array('title'=>'节点');		//用于动态输出超出2级分类的子节点模板
	$op['page']['addon']['ajax']=array('title'=>'AJAX');		//用于输出ajax数据
	$ac['page']['addon']=array(
		'title'=>'附属参数',
		'icon'=>'fa fa-cubes',
		'sn'=>'50',
		'op'=>$op['page']['addon']
	);

	$op['page']['recyle']['display']=array('title'=>'全部');
	$op['page']['recyle']['model']=array('title'=>'模型');
	$op['page']['recyle']['instance']=array('title'=>'实例');
	$op['page']['recyle']['param']=array('title'=>'表单参数');
	$op['page']['recyle']['content']=array('title'=>'表单');
	$op['page']['recyle']['addon']=array('title'=>'附属参数');
	$ac['page']['recyle']=array(
		'title'=>'回收站',
		'icon'=>'fa fa-recycle',
		'sn'=>'900',
		'op'=>$op['page']['recyle']
	);

	$op['page']['ajax']['update']=array('title'=>'更新');
	$op['page']['ajax']['delete']=array('title'=>'软删除');
	$op['page']['ajax']['clear']=array('title'=>'物理删除');
	$ac['page']['ajax']=array(
		'title'=>'Ajax操作',
		'icon'=>'fa fa fa-font',
		'sn'=>'1000',
		'op'=>$op['page']['ajax']
	);

	$do['page']=array(
		'title'=>'页面管理',
		'icon'=>'fa fa-file-powerpoint-o',
		'hide'=>array(),
		'show'=>array(),
		'sn'=>'page',
		'ac'=>$ac['page']
	);
//page结束