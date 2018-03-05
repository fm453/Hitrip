<?php

	$op['realty']['index']['display']=array('title'=>'房产管理');
	$ac['realty']['index']=array(
		'title'=>'默认入口',
		'icon'=>'fa fa-inbox',
		'sn'=>'0',
		'op'=>$op['realty']['index']
	);

	$op['realty']['model']['display']=array('title'=>'房产模型');
	$op['realty']['model']['add']=array('title'=>'新增');
	$op['realty']['model']['modify']=array('title'=>'编辑');
	$op['realty']['model']['copy']=array('title'=>'复制');
	$op['realty']['model']['param']=array('title'=>'参数');
	$op['realty']['model']['delete']=array('title'=>'删除');
	$ac['realty']['model']=array(
		'title'=>'模型管理',
		'icon'=>'fa fa-database',
		'sn'=>'10',
		'op'=>$op['realty']['model']
	);

	$op['realty']['house']['display']=array('title'=>'全部楼盘');
	$op['realty']['house']['add']=array('title'=>'新增');
	$op['realty']['house']['modify']=array('title'=>'编辑');
	$op['realty']['house']['copy']=array('title'=>'复制');
	$op['realty']['house']['delete']=array('title'=>'删除');
	$ac['realty']['house']=array(
		'title'=>'楼盘管理',
		'icon'=>'fa fa-cube',
		'sn'=>'20',
		'op'=>$op['realty']['house']
	);

	$op['realty']['room']['display']=array('title'=>'全部户型');
	$op['realty']['room']['add']=array('title'=>'新增');
	$op['realty']['room']['modify']=array('title'=>'编辑');
	$op['realty']['room']['copy']=array('title'=>'复制');
	$op['realty']['room']['delete']=array('title'=>'删除');
	$ac['realty']['room']=array(
		'title'=>'户型管理',
		'icon'=>'fa fa-cube',
		'sn'=>'40',
		'op'=>$op['realty']['room']
	);

	$op['realty']['addon']['pcate']=array('title'=>'一级');		//用于动态输出父级类的模板
	$op['realty']['addon']['ccate']=array('title'=>'二级');		//用于动态输出子级类的模板
	$op['realty']['addon']['node']=array('title'=>'节点');		//用于动态输出超出2级分类的子节点模板
	$op['realty']['addon']['ajax']=array('title'=>'AJAX');		//用于输出ajax数据
	$ac['realty']['addon']=array(
		'title'=>'附属参数',
		'icon'=>'fa fa-cubes',
		'sn'=>'50',
		'op'=>$op['realty']['addon']
	);

	$op['realty']['recyle']['display']=array('title'=>'全部');
	$op['realty']['recyle']['model']=array('title'=>'楼盘模型');
	$op['realty']['recyle']['house']=array('title'=>'楼盘');
	$op['realty']['recyle']['param']=array('title'=>'楼盘参数');
	$op['realty']['recyle']['room']=array('title'=>'户型');
	$op['realty']['recyle']['addon']=array('title'=>'户型参数');
	$ac['realty']['recyle']=array(
		'title'=>'回收站',
		'icon'=>'fa fa-recycle',
		'sn'=>'900',
		'op'=>$op['realty']['recyle']
	);

	$op['realty']['ajax']['update']=array('title'=>'更新');
	$op['realty']['ajax']['delete']=array('title'=>'软删除');
	$op['realty']['ajax']['clear']=array('title'=>'物理删除');
	$ac['realty']['ajax']=array(
		'title'=>'Ajax操作',
		'icon'=>'fa fa fa-font',
		'sn'=>'1000',
		'op'=>$op['realty']['ajax']
	);

	$do['realty']=array(
		'title'=>'房产管理',
		'icon'=>'fa fa-institution',
		'sn'=>'realty',
		'hide'=>array(),
		'show'=>array(),
		'ac'=>$ac['realty']
	);
//realty结束
