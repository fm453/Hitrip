<?php

	$op['report']['index']['display']=array('title'=>'显示');
	$ac['report']['index']=array(
		'title'=>'默认入口',
		'icon'=>'fa fa-inbox',
		'sn'=>'0',
		'op'=>$op['report']['index']
	);

	$op['report']['goods']['display']=array('title'=>'汇总显示');
	$op['report']['goods']['detail']=array('title'=>'详情');
	$op['report']['goods']['delete']=array('title'=>'删除');
	$ac['report']['goods']=array(
		'title'=>'商品数据',
		'icon'=>'fa fa-dedent',
		'sn'=>'10',
		'op'=>$op['report']['goods']
	);

	$op['report']['partner']['display']=array('title'=>'汇总显示');
	$op['report']['partner']['detail']=array('title'=>'详情');
	$op['report']['partner']['delete']=array('title'=>'删除');
	$ac['report']['partner']=array(
		'title'=>'商户数据',
		'icon'=>'fa fa-dedent',
		'sn'=>'20',
		'op'=>$op['report']['partner']
	);

	$op['report']['order']['display']=array('title'=>'汇总显示');
	$op['report']['order']['detail']=array('title'=>'详情');
	$op['report']['order']['delete']=array('title'=>'删除');
	$ac['report']['order']=array(
		'title'=>'订单数据',
		'icon'=>'fa fa-dedent',
		'sn'=>'30',
		'op'=>$op['report']['order']
	);

	$op['report']['member']['display']=array('title'=>'汇总显示');
	$op['report']['member']['detail']=array('title'=>'详情');
	$op['report']['member']['delete']=array('title'=>'删除');
	$ac['report']['member']=array(
		'title'=>'会员数据',
		'icon'=>'fa fa-dedent',
		'sn'=>'40',
		'op'=>$op['report']['member']
	);

	$op['report']['traffic']['display']=array('title'=>'汇总显示');
	$op['report']['traffic']['detail']=array('title'=>'详情');
	$op['report']['traffic']['delete']=array('title'=>'删除');
	$ac['report']['traffic']=array(
		'title'=>'流量数据',
		'icon'=>'fa fa-dedent',
		'sn'=>'50',
		'op'=>$op['report']['traffic']
	);

	$op['report']['ads']['display']=array('title'=>'汇总显示');
	$op['report']['ads']['detail']=array('title'=>'详情');
	$op['report']['ads']['delete']=array('title'=>'删除');
	$ac['report']['ads']=array(
		'title'=>'广告数据',
		'icon'=>'fa fa-dedent',
		'sn'=>'60',
		'op'=>$op['report']['ads']
	);

	$op['report']['analysis']['display']=array('title'=>'分析报告');
	$ac['report']['analysis']=array(
		'title'=>'营销分析',
		'icon'=>'fa fa-lightbulb-o',
		'sn'=>'800',
		'op'=>$op['report']['analysis']
	);

	$op['report']['log']['display']=array('title'=>'汇总显示');
	$op['report']['log']['mine']=array('title'=>'我的');
	$op['report']['log']['getcode']=array('title'=>'授权请求','hidden'=>1);
	$op['report']['log']['detail']=array('title'=>'详情');
	$op['report']['log']['delete']=array('title'=>'删除');
	$ac['report']['log']=array(
		'title'=>'日志数据',
		'icon'=>'fa fa-dedent',
		'sn'=>'900',
		'op'=>$op['report']['log']
	);

	$op['report']['ajax']['modify']=array('title'=>'编辑');
	$ac['report']['ajax']=array(
		'title'=>'Ajax操作',
		'icon'=>'fa fa fa-font',
		'sn'=>'1000',
		'op'=>$op['report']['ajax']
	);

	$do['report']=array(
		'title'=>'数据报告',
		'icon'=>'fa fa-bar-chart-o',
		'sn'=>'report',
		'hide'=>array(),
		'show'=>array(),
		'ac'=>$ac['report']
	);
	//report结束
