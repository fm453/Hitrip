<?php

	$op['vsearch']['index']['display']=array('title'=>'微查询管理');
	$ac['vsearch']['index']=array(
		'title'=>'默认入口',
		'icon'=>'fa fa-inbox',
		'sn'=>'0',
		'op'=>$op['vsearch']['index']
	);

	$op['vsearch']['model']['display']=array('title'=>'全部模型');
	$op['vsearch']['model']['param']=array('title'=>'参数');
	$ac['vsearch']['model']=array(
		'title'=>'模型管理',
		'icon'=>'fa fa-database',
		'sn'=>'10',
		'op'=>$op['vsearch']['model']
	);

	$op['vsearch']['instance']['display']=array('title'=>'全部实例');
	$op['vsearch']['instance']['add']=array('title'=>'新增');
	$op['vsearch']['instance']['modify']=array('title'=>'编辑');
	$op['vsearch']['instance']['copy']=array('title'=>'复制');
	$op['vsearch']['instance']['delete']=array('title'=>'删除');
	$ac['vsearch']['instance']=array(
		'title'=>'实例管理',
		'icon'=>'fa fa-cube',
		'sn'=>'20',
		'op'=>$op['vsearch']['instance']
	);

	$op['vsearch']['content']['display']=array('title'=>'全部内容');
	$op['vsearch']['content']['add']=array('title'=>'新增');
	$op['vsearch']['content']['modify']=array('title'=>'编辑');
	$op['vsearch']['content']['copy']=array('title'=>'复制');
	$op['vsearch']['content']['delete']=array('title'=>'删除');
	$ac['vsearch']['content']=array(
		'title'=>'内容管理',
		'icon'=>'fa fa-cube',
		'sn'=>'40',
		'op'=>$op['vsearch']['content']
	);

	$op['vsearch']['addon']['pcate']=array('title'=>'一级');		//用于动态输出父级类的模板
	$op['vsearch']['addon']['ccate']=array('title'=>'二级');		//用于动态输出子级类的模板
	$op['vsearch']['addon']['node']=array('title'=>'节点');		//用于动态输出超出2级分类的子节点模板
	$op['vsearch']['addon']['ajax']=array('title'=>'AJAX');		//用于输出ajax数据
	$ac['vsearch']['addon']=array(
		'title'=>'附属参数',
		'icon'=>'fa fa-cubes',
		'sn'=>'50',
		'op'=>$op['vsearch']['addon']
	);

	$op['vsearch']['recyle']['display']=array('title'=>'全部');
	$op['vsearch']['recyle']['model']=array('title'=>'模型');
	$op['vsearch']['recyle']['instance']=array('title'=>'实例');
	$op['vsearch']['recyle']['param']=array('title'=>'表单参数');
	$op['vsearch']['recyle']['content']=array('title'=>'表单');
	$op['vsearch']['recyle']['addon']=array('title'=>'附属参数');
	$ac['vsearch']['recyle']=array(
		'title'=>'回收站',
		'icon'=>'fa fa-recycle',
		'sn'=>'900',
		'op'=>$op['vsearch']['recyle']
	);

	$op['vsearch']['ajax']['update']=array('title'=>'更新');
	$op['vsearch']['ajax']['delete']=array('title'=>'软删除');
	$op['vsearch']['ajax']['clear']=array('title'=>'物理删除');
	$ac['vsearch']['ajax']=array(
		'title'=>'Ajax操作',
		'icon'=>'fa fa fa-font',
		'sn'=>'1000',
		'op'=>$op['vsearch']['ajax']
	);

	$do['vsearch']=array(
		'title'=>'微查询管理',
		'icon'=>'fa fa-search',
		'sn'=>'vsearch',
		'hide'=>array(),
		'show'=>array(),
		'ac'=>$ac['vsearch']
	);
//vsearch结束