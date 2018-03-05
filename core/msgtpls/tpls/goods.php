<?php

$tplnotice_data['list']['displayorder']['update']['admin']['touser'] =  $_FM['settings']['manageropenids'];
$tplnotice_data['list']['displayorder']['update']['admin']['template_id'] = $_FM['settings']['msgtpls']['ebiz_task'];
$tplnotice_data['list']['displayorder']['update']['admin']['url'] = '';
$tplnotice_data['list']['displayorder']['update']['admin']['topcolor']= '#0095f6'; //这个参数目前测试没有生效
$tplnotice_data['list']['displayorder']['update']['admin']['data']=array(
		'first'=>array('color'=>'#f00','value'=>$shopname.'操作通知'),
		'keyword1'=>array('color'=>'#0095f6','value'=>'产品排序更新'),
		'keyword2'=>array('color'=>'#0095f6','value'=>'后台管理'),
		'remark'=>array('color'=>'#0095f6','value'=>'管理员'.$_W['username'].'('.$_W['uid'].')'.'更新了产品排序')
	);

$tplnotice_data['list']['sn']['update']['admin']['touser'] =  $_FM['settings']['manageropenids'];
$tplnotice_data['list']['sn']['update']['admin']['template_id'] = $_FM['settings']['msgtpls']['ebiz_task'];
$tplnotice_data['list']['sn']['update']['admin']['url'] = '';
$tplnotice_data['list']['sn']['update']['admin']['topcolor']= '#0095f6'; //这个参数目前测试没有生效
$tplnotice_data['list']['sn']['update']['admin']['data']=array(
		'first'=>array('color'=>'#f00','value'=>$shopname.'操作通知'),
		'keyword1'=>array('color'=>'#0095f6','value'=>'产品编码批量更新'),
		'keyword2'=>array('color'=>'#0095f6','value'=>'后台管理'),
		'remark'=>array('color'=>'#0095f6','value'=>'管理员'.$_W['username'].'('.$_W['uid'].')'.'更新了产品编码')
	);

$tplnotice_data['new']['admin']['touser'] =  $_FM['settings']['manageropenids'];
$tplnotice_data['new']['admin']['template_id'] = $_FM['settings']['msgtpls']['ebiz_task'];
$tplnotice_data['new']['admin']['url'] = fm_murl('goods','detail','index',array('id'=>$id));
$tplnotice_data['new']['admin']['topcolor']= '#0095f6'; //这个参数目前测试没有生效
$tplnotice_data['new']['admin']['data']=array(
		'first'=>array('color'=>'#f00','value'=>$shopname.'操作通知'),
		'keyword1'=>array('color'=>'#0095f6','value'=>'新建产品'),
		'keyword2'=>array('color'=>'#0095f6','value'=>'后台管理'),
		'remark'=>array('color'=>'#0095f6','value'=>'管理员'.$_W['username'].'('.$_W['uid'].')'.'新建产品:"'.$data['title'].'"')
	);

$tplnotice_data['detail']['modify']['admin']['touser'] =  $_FM['settings']['manageropenids'];
$tplnotice_data['detail']['modify']['admin']['template_id'] = $_FM['settings']['msgtpls']['ebiz_task'];
$tplnotice_data['detail']['modify']['admin']['url'] = fm_murl('goods','detail','index',array('id'=>$id));
$tplnotice_data['detail']['modify']['admin']['topcolor']= '#0095f6'; //这个参数目前测试没有生效
$tplnotice_data['detail']['modify']['admin']['data']=array(
		'first'=>array('color'=>'#f00','value'=>$shopname.'操作通知'),
		'keyword1'=>array('color'=>'#0095f6','value'=>'更新产品'),
		'keyword2'=>array('color'=>'#0095f6','value'=>'后台管理'),
		'remark'=>array('color'=>'#0095f6','value'=>'管理员'.$_W['username'].'('.$_W['uid'].')'.'更新产品:"'.$data['title'].'"')
	);

$tplnotice_data['detail']['modify']['goodadm']['touser'] =  $_FM['settings']['manageropenids'];
$tplnotice_data['detail']['modify']['goodadm']['template_id'] = $_FM['settings']['msgtpls']['ebiz_task'];
$tplnotice_data['detail']['modify']['goodadm']['url'] = fm_murl('goods','detail','index',array('id'=>$id));
$tplnotice_data['detail']['modify']['goodadm']['topcolor']= '#0095f6'; //这个参数目前测试没有生效
$tplnotice_data['detail']['modify']['goodadm']['data']=array(
		'first'=>array('color'=>'#f00','value'=>$shopname.'操作通知'),
		'keyword1'=>array('color'=>'#0095f6','value'=>'您管理的产品已被更新'),
		'keyword2'=>array('color'=>'#0095f6','value'=>'后台管理'),
		'remark'=>array('color'=>'#0095f6','value'=>'管理员'.$_W['username'].'('.$_W['uid'].')'.'更新产品:"'.$data['title'].'"')
	);

$tplnotice_data['dianzan']['admin']['touser'] =  $_FM['settings']['manageropenids'];
$tplnotice_data['dianzan']['admin']['template_id'] = $_FM['settings']['msgtpls']['ebiz_task'];
$tplnotice_data['dianzan']['admin']['url'] = fm_murl('goods','detail','index',array('id'=>$id));
$tplnotice_data['dianzan']['admin']['topcolor']= '#0095f6'; //这个参数目前测试没有生效
$tplnotice_data['dianzan']['admin']['data']=array(
		'first'=>array('color'=>'#f00','value'=>$shopname.'操作通知'),
		'keyword1'=>array('color'=>'#0095f6','value'=>'产品点赞'),
		'keyword2'=>array('color'=>'#0095f6','value'=>'前台管理'),
		'remark'=>array('color'=>'#0095f6','value'=>'用户'.$_W['member']['nickname'].'('.$_W['openid'].')'.'点赞产品:"'.$article['title'].'"')
	);

$tplnotice_data['dianzan']['goodadm']['touser'] =  $article['goodadm'];
$tplnotice_data['dianzan']['goodadm']['template_id'] = $_FM['settings']['msgtpls']['ebiz_task'];
$tplnotice_data['dianzan']['goodadm']['url'] = fm_murl('goods','detail','index',array('id'=>$id));
$tplnotice_data['dianzan']['goodadm']['topcolor']= '#0095f6'; //这个参数目前测试没有生效
$tplnotice_data['dianzan']['goodadm']['data']=array(
		'first'=>array('color'=>'#f00','value'=>$shopname.'操作通知'),
		'keyword1'=>array('color'=>'#0095f6','value'=>'产品点赞'),
		'keyword2'=>array('color'=>'#0095f6','value'=>'前台管理'),
		'remark'=>array('color'=>'#0095f6','value'=>'感谢您的赞评;产品:"'.$article['title'].'"')
	);	