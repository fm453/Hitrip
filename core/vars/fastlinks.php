<?php

$fastlinks      =array();
$i=0;
$fastlinks[$i]   =array();	//独立板块主入口
$fastlinks[$i][] =array('url'=>fm_murl('index','index','index',array("showguide"=>1),$issimple=1),'title'=>'欢迎页');
$fastlinks[$i][] =array('url'=>fm_murl('index','index','index',array("showguide"=>0),$issimple=1),'title'=>'默认首页');
$fastlinks[$i][] =array('url'=>fm_murl('site','index','index',array(),$issimple=1),'title'=>'微网站');
$fastlinks[$i][] =array('url'=>fm_murl('vshop','index','index',array(),$issimple=1),'title'=>'积分商城');
$fastlinks[$i][] =array('url'=>fm_murl('search','index','index',array(),$issimple=1),'title'=>'搜索中心');
$fastlinks[$i][] =array('url'=>fm_murl('realty','index','index',array(),$issimple=1),'title'=>'房产网站');
$fastlinks[$i][] =array('url'=>fm_murl('vcard','index','index',array(),$issimple=1),'title'=>'电子名片');
$fastlinks[$i][] =array('url'=>fm_murl('needs','index','index',array(),$issimple=1),'title'=>'有求必应');
$fastlinks[$i][] =array('url'=>fm_murl('search','index','index',array(),$issimple=1),'title'=>'综合搜索');
$fastlinks[$i][] =array('url'=>fm_murl('special','index','index',array(),$issimple=1),'title'=>'专题页');
$fastlinks[$i][] =array('url'=>fm_murl('vfoods','index','index',array(),$issimple=1),'title'=>'微餐饮');
$fastlinks[$i][] =array('url'=>fm_murl('appweborder','index','index',array(),$issimple=1),'title'=>'核销端');
$fastlinks[$i][] =array('url'=>fm_murl('demo','index','index',array(),$issimple=1),'title'=>'前台DEMO');

++$i;
$fastlinks[$i]   =array();	//主功能入口
$fastlinks[$i][] =array('url'=>fm_murl('search','goods','index',array(),$issimple=1),'title'=>'商城产品搜索入口');
$fastlinks[$i][] =array('url'=>fm_murl('search','article','index',array(),$issimple=1),'title'=>'微站文章搜索入口');
$fastlinks[$i][] =array('url'=>fm_murl('search','member','index',array(),$issimple=1),'title'=>'平台会员搜索入口');
$fastlinks[$i][] =array('url'=>fm_murl('index','home','index',array('showguide'=>0)),'title'=>'综合版商城首页');
$fastlinks[$i][] =array('url'=>fm_murl('index','simple','index',array('showguide'=>0)),'title'=>'简版商城首页(不加载引导页)');
$fastlinks[$i][] =array('url'=>fm_murl('order','myorder','all',array(),$issimple=1),'title'=>'订单中心');
$fastlinks[$i][] =array('url'=>fm_murl('fenxiao','index','index',array(),$issimple=1),'title'=>'分销中心');
$fastlinks[$i][] =array('url'=>fm_murl('member','index','index',array(),$issimple=1),'title'=>'会员中心');

++$i;
$fastlinks[$i]   =array();	//功能细节快捷方式
$fastlinks[$i][] =array('url'=>fm_murl('site','category','index',array(),$issimple=1),'title'=>'微站栏目入口');
$fastlinks[$i][] =array('url'=>fm_murl('article','list','index',array(),$issimple=1),'title'=>'微站文章栏目列表');
$fastlinks[$i][] =array('url'=>fm_murl('help','aboutus','index',array(),$issimple=1),'title'=>'关于我们');
$fastlinks[$i][] =array('url'=>fm_murl('help','advise','index',array(),$issimple=1),'title'=>'建言献策');
$fastlinks[$i][] =array('url'=>fm_murl('error','index','index',array(),$issimple=1),'title'=>'反馈报错');
$fastlinks[$i][] =array('url'=>fm_murl('chat','detail','all',array(),$issimple=1),'title'=>'全员微聊入口');
$fastlinks[$i][] =array('url'=>fm_murl('member','address','index',array(),$issimple=1),'title'=>'管理收货地址');

++$i;
$fastlinks[$i]   =array();	//便利开发管理
$fastlinks[$i][] =array('url'=>fm_murl('test','browser','index',array(),$issimple=1),'title'=>'浏览器信息测试');
$fastlinks[$i][] =array('url'=>fm_murl('crontab','','',array(),$issimple=1),'title'=>'定时任务网页版');
$fastlinks[$i][] =array('url'=>fm_murl('followus','','',array(),$issimple=1),'title'=>'引导关注说明页');
$fastlinks[$i][] =array('url'=>fm_murl('workweixin','oauth2','index',array(),$issimple=1),'title'=>'企业微信网页授权');
$fastlinks[$i][] =array('url'=>fm_murl('weixin','oauth2','index',array(),$issimple=1),'title'=>'微信公众号网页授权');

unset($i);
