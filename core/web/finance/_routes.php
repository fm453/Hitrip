<?php

	$op['finance']['index']['display']=array('title'=>'财务管理');
	$ac['finance']['index']=array(
		'title'=>'默认入口',
		'icon'=>'fa fa-inbox',
		'sn'=>'0',
		'op'=>$op['finance']['index']
	);

	$op['finance']['credit']['display']=array('title'=>'兑换申请');
	$op['finance']['credit']['detail']=array('title'=>'详情');
	$op['finance']['credit']['check']=array('title'=>'审核');
	$op['finance']['credit']['export']=array('title'=>'导出');
	$op['finance']['credit']['import']=array('title'=>'导入');
	$ac['finance']['credit']=array(
		'title'=>'积分兑换',
		'icon'=>'fa fa-flag-checkered',
		'sn'=>'10',
		'op'=>$op['finance']['credit']
	);

	$op['finance']['commission']['display']=array('title'=>'提现申请');
	$op['finance']['commission']['detail']=array('title'=>'详情');
	$op['finance']['commission']['check']=array('title'=>'审核');
	$op['finance']['commission']['export']=array('title'=>'导出');
	$op['finance']['commission']['import']=array('title'=>'导入');
	$ac['finance']['commission']=array(
		'title'=>'佣金管理',
		'icon'=>'fa fa-flag-checkered',
		'sn'=>'20',
		'op'=>$op['finance']['commission']
	);

	$op['finance']['zhifu']['display']=array('title'=>'打款申请');
	$op['finance']['zhifu']['detail']=array('title'=>'详情');
	$op['finance']['zhifu']['check']=array('title'=>'审核');
	$op['finance']['zhifu']['export']=array('title'=>'导出');
	$op['finance']['zhifu']['import']=array('title'=>'导入');
	$ac['finance']['zhifu']=array(
		'title'=>'支付管理',
		'icon'=>'fa fa-flag-checkered',
		'sn'=>'30',
		'op'=>$op['finance']['zhifu']
	);

	$op['finance']['charge']['display']=array('title'=>'全部记录');
	$op['finance']['charge']['detail']=array('title'=>'详情');
	$op['finance']['charge']['member']=array('title'=>'会员充值');
	$op['finance']['charge']['check']=array('title'=>'审核');
	$op['finance']['charge']['export']=array('title'=>'导出');
	$op['finance']['charge']['import']=array('title'=>'导入');
	$ac['finance']['charge']=array(
		'title'=>'充值管理',
		'icon'=>'fa fa-flag-checkered',
		'sn'=>'40',
		'op'=>$op['finance']['charge']
	);

	$op['finance']['paylog']['display']=array('title'=>'列表');
	$op['finance']['paylog']['member']=array('title'=>'会员记录');
	$op['finance']['paylog']['detail']=array('title'=>'详情');
	$op['finance']['paylog']['export']=array('title'=>'导出');
	$ac['finance']['paylog']=array(
		'title'=>'支付日志',
		'icon'=>'fa fa-flag-checkered',
		'sn'=>'50',
		'op'=>$op['finance']['paylog']
	);

	$op['finance']['ajax']['update']=array('title'=>'更新');
	$op['finance']['ajax']['delete']=array('title'=>'软删除');
	$op['finance']['ajax']['clear']=array('title'=>'物理删除');
	$ac['finance']['ajax']=array(
		'title'=>'Ajax操作',
		'icon'=>'fa fa fa-font',
		'sn'=>'1000',
		'op'=>$op['finance']['ajax']
	);

	$do['finance']=array(
		'title'=>'财务管理',
		'icon'=>'fa fa-cny',
		'sn'=>'finance',
		'hide'=>array(),
		'show'=>array(),
		'ac'=>$ac['finance']
	);
//finance结束
