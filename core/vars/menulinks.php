<?php

//后台顶部菜单条
$menulinks      =array();
$i=0;
$menulinks[$i]   =array('title'=>"商城管理",'icon'=>"icon-shopping-cart",'subcat'=>array());
$menulinks[$i]['subcat'][] =array('url'=>fm_wurl('goods','list','display', ''),'title'=>"产品管理",'icon'=>"icon-inbox");
$menulinks[$i]['subcat'][] =array('url'=>fm_wurl('article','list','display', ''),'title'=>"文章管理",'icon'=>"fa fa-list-ol");
$menulinks[$i]['subcat'][] =array('url'=>fm_wurl('category','goods','display',''),'title'=>"分类管理",'icon'=>"icon-sitemap");
$menulinks[$i]['subcat'][] =array('url'=>fm_wurl('ad','ads','display',''),'title'=>"广告图",'icon'=>"icon-picture");
$menulinks[$i]['subcat'][] =array('url'=>fm_wurl('ad','adv','display',''),'title'=>"幻灯片",'icon'=>"icon-th-large");
$menulinks[$i]['subcat'][] =array();
$menulinks[$i]['subcat'][] =array('url'=>fm_wurl('service','feedback','display',''),'title'=>"售后维权",'icon'=>"icon-truck");
$menulinks[$i]['subcat'][] =array('url'=>fm_wurl('comment','index', 'display', ''),'title'=>"评论管理",'icon'=>"icon-comments");

++$i;
$menulinks[$i]   =array('title'=>"订单管理",'icon'=>"icon-th-list",'subcat'=>array());
$menulinks[$i]['subcat'][] =array('url'=>'','title'=>"标准订单",'icon'=>"",'disabled'=>true);
$menulinks[$i]['subcat'][] =array();
$menulinks[$i]['subcat'][] =array('url'=>fm_wurl('order','list','display',array('status' => 'code')),'title'=>"全部订单",'icon'=>"icon-list-ol");
$menulinks[$i]['subcat'][] =array('url'=>fm_wurl('order','list','display',array('status' => 'code0')),'title'=>"待付款",'icon'=>"icon-money");
$menulinks[$i]['subcat'][] =array('url'=>fm_wurl('order','list','display',array('status' => 'code1')),'title'=>"已支付",'icon'=>"icon-retweet");
$menulinks[$i]['subcat'][] =array('url'=>fm_wurl('order','list','display',array('status' => 'code3')),'title'=>"已完成",'icon'=>"icon-eject");
$menulinks[$i]['subcat'][] =array();
$menulinks[$i]['subcat'][] =array('url'=>'','title'=>"活动订单",'icon'=>"",'disabled'=>true);
$menulinks[$i]['subcat'][] =array();
$menulinks[$i]['subcat'][] =array('url'=>fm_wurl('order','list','display',array('type' => 'pintuan')),'title'=>"拼团订单",'icon'=>"icon-copy");
$menulinks[$i]['subcat'][] =array('url'=>fm_wurl('order','list','display',array('type' => 'presale')),'title'=>"预购订单",'icon'=>"icon-leaf");

++$i;
$menulinks[$i]   =array('title'=>"客户管理",'icon'=>"icon-group",'subcat'=>array());
$menulinks[$i]['subcat'][] =array('url'=>fm_wurl('crm', 'member','display', ''),'title'=>"用户管理",'icon'=>"fa fa-child");
$menulinks[$i]['subcat'][] =array('url'=>fm_wurl('crm', 'agent','display', ''),'title'=>"会员管理",'icon'=>"icon-user");
$menulinks[$i]['subcat'][] =array('url'=>fm_wurl('crm', 'partner','display', ''),'title'=>"商户管理",'icon'=>"icon-user-md");
$menulinks[$i]['subcat'][] =array();
$menulinks[$i]['subcat'][] =array('url'=>fm_wurl('finance', 'commission','display', ''),'title'=>"分销佣金",'icon'=>"icon-list-ol");
$menulinks[$i]['subcat'][] =array('url'=>fm_wurl('finance', 'charge','display', ''),'title'=>"账户充值",'icon'=>"icon-money");

++$i;
$menulinks[$i]   =array('title'=>"插件中心",'icon'=>"icon-download-alt",'subcat'=>array());
$menulinks[$i]['subcat'][] =array('url'=>fm_wurl('needs','diy','display',''),'title'=>"有求必应",'icon'=>"fa fa-magnet");
$menulinks[$i]['subcat'][] =array('url'=>fm_wurl('order', 'recyle', 'display', ''),'title'=>"订单回收站",'icon'=>"icon-inbox");
$menulinks[$i]['subcat'][] =array('url'=>fm_wurl('award', 'vshop', 'display', ''),'title'=>"积分营销",'icon'=>"icon-gift");
$menulinks[$i]['subcat'][] =array('url'=>fm_wurl('help', 'manual', 'display', array()),'title'=>"操作手册",'icon'=>"icon-book");
$menulinks[$i]['subcat'][] =array();
if($settings['onoffs']['isnavmenu']==1){
	$menulinks[$i]['subcat'][] =array('url'=>'','title'=>"自定义导航栏",'icon'=>"",'disabled'=>true);
	$menulinks[$i]['subcat'][] =array();
	for ($j=0; $j < $settings['navmenusnum']; $j++) {
		$menulinks[$i]['subcat'][] =array('url'=>$settings['navmenus']['value'.$j],'title'=>$settings['navmenus']['name'.$j],'icon'=>$settings['navmenus']['icon'.$j]);
	}
	$menulinks[$i]['subcat'][] =array();
}
$menulinks[$i]['subcat'][] =array('url'=>"http://demo.hiluker.com/case/vcmseditor/",'title'=>"样式代码转换器",'icon'=>"",'target'=>"_blank");
$menulinks[$i]['subcat'][] =array('url'=>"http://www.135editor.com/",'title'=>"135图文排版工具",'icon'=>"",'target'=>"_blank");

++$i;
$menulinks[$i]   =array('title'=>"数据统计",'icon'=>"icon-bar-chart",'subcat'=>array());
$menulinks[$i]['subcat'][] =array('url'=>fm_wurl('report', 'goods', 'display', ''),'title'=>"商品统计",'icon'=>"icon-inbox");
$menulinks[$i]['subcat'][] =array('url'=>fm_wurl('report', 'order', 'display', ''),'title'=>"订单统计",'icon'=>"icon-th-list");
$menulinks[$i]['subcat'][] =array('url'=>fm_wurl('report', 'member', 'display', ''),'title'=>"会员统计",'icon'=>"icon-credit-card");
$menulinks[$i]['subcat'][] =array('url'=>fm_wurl('report', 'ads', 'display', ''),'title'=>"广告统计",'icon'=>"icon-rss");
$menulinks[$i]['subcat'][] =array('url'=>fm_wurl('report', 'analysis', 'display', ''),'title'=>"营销分析",'icon'=>"icon-signal");

++$i;
$menulinks[$i]   =array('title'=>"系统设置",'icon'=>"icon-cog icon-spin",'subcat'=>array());
$menulinks[$i]['subcat'][] =array('url'=>fm_wurl('dispatch','express','display',array()),'title'=>"配送管理",'icon'=>"icon-truck");
$menulinks[$i]['subcat'][] =array();
if($_W['isfounder'] || $_FM['isAdminer']){
	$menulinks[$i]['subcat'][] =array('url'=>'','title'=>"以下部分管理员可见",'icon'=>"",'disabled'=>true);
	$menulinks[$i]['subcat'][] =array();
	$menulinks[$i]['subcat'][] =array('url'=>fm_wurl('modset','basic','display',''),'title'=>"商城配置",'icon'=>"icon-cogs");
	$menulinks[$i]['subcat'][] =array('url'=>fm_wurl('goods', 'tpl', 'display', ''),'title'=>"产品模型管理",'icon'=>"icon-magic");
	$menulinks[$i]['subcat'][] =array('url'=>fm_wurl('ad', 'adbox', 'display', array()),'title'=>"广告位资源管理",'icon'=>"icon-folder-open-alt");
	$menulinks[$i]['subcat'][] =array('url'=>fm_wurl('report', 'log', 'display', ''),'title'=>"操作日志",'icon'=>"icon-signal");
	$menulinks[$i]['subcat'][] =array();
}
if($_W['isfounder']){
	$menulinks[$i]['subcat'][] =array('url'=>'','title'=>"以下仅站长可见",'icon'=>"",'disabled'=>true);
	$menulinks[$i]['subcat'][] =array();
	$menulinks[$i]['subcat'][] =array('url'=>fm_wurl('modset', 'getcode', 'display', ''),'title'=>"绑定接口",'icon'=>"fa fa-link");
	$menulinks[$i]['subcat'][] =array('url'=>fm_wurl('modset', 'checkdb', 'display', ''),'title'=>"检查数据表",'icon'=>"fa fa-bug");
	$menulinks[$i]['subcat'][] =array('url'=>fm_wurl('modset', 'upgrade', 'display', ''),'title'=>"自助升级",'icon'=>"icon-upload");
}

unset($i);
