<?php

	$op['modset']['index']['display']=array('title'=>'显示');
	$ac['modset']['index']=array(
		'title'=>'默认入口',
		'icon'=>'fa fa-inbox',
		'sn'=>'0',
		'op'=>$op['modset']['index']
	);

	$op['modset']['getcode']['display']=array('title'=>'显示');
	$op['modset']['getcode']['modify']=array('title'=>'编辑');
	$op['modset']['getcode']['delete']=array('title'=>'删除');
	$ac['modset']['getcode']=array(
		'title'=>'授权配置',
		'icon'=>'fa fa-key',
		'sn'=>'10',
		'op'=>$op['modset']['getcode']
	);

	$op['modset']['onoff']['display']=array('title'=>'显示');
	$op['modset']['onoff']['modify']=array('title'=>'编辑');
	$op['modset']['onoff']['delete']=array('title'=>'删除');
	$ac['modset']['onoff']=array(
		'title'=>'功能开关',
		'icon'=>'fa fa-minus-square',
		'sn'=>'20',
		'op'=>$op['modset']['onoff']
	);

	$op['modset']['basic']['display']=array('title'=>'显示');
	$op['modset']['basic']['modify']=array('title'=>'编辑');
	$ac['modset']['basic']=array(
		'title'=>'基础配置',
		'icon'=>'fa fa-bold',
		'sn'=>'30',
		'op'=>$op['modset']['basic']
	);

	$op['modset']['brands']['display']=array('title'=>'显示');
	$op['modset']['brands']['modify']=array('title'=>'编辑');
	$op['modset']['brands']['delete']=array('title'=>'删除');
	$ac['modset']['brands']=array(
		'title'=>'品牌信息',
		'icon'=>'fa fa-info-circle',
		'sn'=>'40',
		'op'=>$op['modset']['brands']
	);

	$op['modset']['msgtpls']['display']=array('title'=>'显示');
	$op['modset']['msgtpls']['modify']=array('title'=>'编辑');
	$ac['modset']['msgtpls']=array(
		'title'=>'消息模板',
		'icon'=>'fa fa-file-code-o',
		'sn'=>'50',
		'op'=>$op['modset']['msgtpls']
	);

	$op['modset']['safe']['display']=array('title'=>'显示');
	$op['modset']['safe']['modify']=array('title'=>'编辑');
	$op['modset']['safe']['delete']=array('title'=>'删除');
	$ac['modset']['safe']=array(
		'title'=>'安全配置',
		'icon'=>'fa fa-warning',
		'sn'=>'60',
		'op'=>$op['modset']['safe']
	);

	$op['modset']['navs']['display']=array('title'=>'显示');
	$op['modset']['navs']['modify']=array('title'=>'编辑');
	$op['modset']['navs']['delete']=array('title'=>'删除');
	$ac['modset']['navs']=array(
		'title'=>'自定义导航条',
		'icon'=>'fa fa-list',
		'sn'=>'70',
		'op'=>$op['modset']['navs']
	);

	$op['modset']['fenxiao']['display']=array('title'=>'显示');
	$op['modset']['fenxiao']['modify']=array('title'=>'编辑');
	$op['modset']['fenxiao']['delete']=array('title'=>'删除');
	$ac['modset']['fenxiao']=array(
		'title'=>'分享机制',
		'icon'=>'fa fa-users',
		'sn'=>'80',
		'op'=>$op['modset']['fenxiao']
	);

	$op['modset']['privilege']['display']=array('title'=>'显示');
	$op['modset']['privilege']['modify']=array('title'=>'编辑');
	$op['modset']['privilege']['delete']=array('title'=>'删除');
	$ac['modset']['privilege']=array(
		'title'=>'权限分配',
		'icon'=>'fa fa-exclamation-triangle',
		'sn'=>'90',
		'op'=>$op['modset']['privilege']
	);

	$op['modset']['ui']['display']=array('title'=>'显示');
	$op['modset']['ui']['modify']=array('title'=>'编辑');
	$op['modset']['ui']['delete']=array('title'=>'删除');
	$ac['modset']['ui']=array(
		'title'=>'个性UI设置',
		'icon'=>'fa fa-file-powerpoint-o',
		'sn'=>'100',
		'op'=>$op['modset']['ui']
	);

	$op['modset']['api']['display']=array('title'=>'显示');
	$op['modset']['api']['modify']=array('title'=>'编辑');
	$op['modset']['api']['delete']=array('title'=>'删除');
	$ac['modset']['api']=array(
		'title'=>'API设置',
		'icon'=>'fa fa-soundcloud',
		'sn'=>'110',
		'op'=>$op['modset']['api']
	);

	$op['modset']['source']['display']=array('title'=>'显示');
	$op['modset']['source']['modify']=array('title'=>'编辑');
	$op['modset']['source']['delete']=array('title'=>'删除');
	$op['modset']['source']['ajax']=array('title'=>'AJAX');
	$ac['modset']['source']=array(
		'title'=>'内容源设置',
		'icon'=>'fa fa-files-o',
		'sn'=>'120',
		'op'=>$op['modset']['source']
	);

	$op['modset']['showroute']['display']=array('title'=>'显示');
	$op['modset']['showroute']['modify']=array('title'=>'编辑');
	$op['modset']['showroute']['delete']=array('title'=>'删除');
	$ac['modset']['showroute']=array(
		'title'=>'路径设置',
		'icon'=>'fa fa-eye',
		'sn'=>'130',
		'op'=>$op['modset']['showroute']
	);

	$op['modset']['test']['display']=array('title'=>'测试结果');
	$ac['modset']['test']=array(
		'title'=>'测试',
		'icon'=>'fa fa-bug',
		'sn'=>'890',
		'op'=>$op['modset']['test']
	);

	$op['modset']['checkdb']['display']=array('title'=>'检查结果');
	$op['modset']['checkdb']['modify']=array('title'=>'数据表编辑');
	$op['modset']['checkdb']['export']=array('title'=>'数据导出');
	$op['modset']['checkdb']['repair']=array('title'=>'数据表修复');
	$ac['modset']['checkdb']=array(
		'title'=>'检查数据表',
		'icon'=>'fa fa-bug',
		'sn'=>'900',
		'op'=>$op['modset']['checkdb']
	);

	$op['modset']['updates']['display']=array('title'=>'在线更新');
	$op['modset']['updates']['check']=array('title'=>'检查');
	$op['modset']['updates']['modify']=array('title'=>'操作');
	$ac['modset']['updates']=array(
		'title'=>'检查更新',
		'icon'=>'fa fa-cloud-upload',
		'sn'=>'910',
		'op'=>$op['modset']['updates']
	);

	$op['modset']['upgrade']['display']=array('title'=>'提示');
	$op['modset']['upgrade']['modify']=array('title'=>'操作');
	$ac['modset']['upgrade']=array(
		'title'=>'自助升级',
		'icon'=>'fa fa-random',
		'sn'=>'1000',
		'op'=>$op['modset']['upgrade']
	);

	$do['modset']=array(
		'title'=>'模块配置',
		'icon'=>'fa fa-cogs',
		'sn'=>'9999',
		'ac'=>$ac['modset']
	);
//modset结束
?>
