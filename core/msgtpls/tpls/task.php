<?php

$tplnotice_data['task']['errorreport']['admin']['touser'] = $settings['manageropenids'];
$tplnotice_data['task']['errorreport']['admin']['template_id'] = $settings['msgtpls']['ebiz_task'];
$tplnotice_data['task']['errorreport']['admin']['url'] = fm_murl('error','index','index',array('msg[title]'=>$msg['title'],'msg[body]'=>$msg['body']));
$tplnotice_data['task']['errorreport']['admin']['topcolor']= '#0095f6'; //这个参数目前测试没有生效
$tplnotice_data['task']['errorreport']['admin']['data']=array(
		'first'=>array('color'=>'#f00','value'=>'您收到一条报错信息'),
		'keyword1'=>array('color'=>'#0095f6','value'=>$contact.'提交反馈'),
		'keyword2'=>array('color'=>'#0095f6','value'=>'前台报错反馈'),
		'remark'=>array('color'=>'#0095f6','value'=>'信息摘要:'.$errorinfo)
	);

$tplnotice_data['task']['errorreport']['reporter']['touser'] = $from_user;
$tplnotice_data['task']['errorreport']['reporter']['template_id'] = $settings['msgtpls']['ebiz_task'];
$tplnotice_data['task']['errorreport']['reporter']['url'] = fm_murl('error','index','index',array('msg[title]'=>$msg['title'],'msg[body]'=>$msg['body']));
$tplnotice_data['task']['errorreport']['reporter']['topcolor']= '#0095f6'; //这个参数目前测试没有生效
$tplnotice_data['task']['errorreport']['reporter']['data']=array(
		'first'=>array('color'=>'#f00','value'=>'感谢您的反馈'),
		'keyword1'=>array('color'=>'#0095f6','value'=>$contact.'提交反馈'.$msg['title']),
		'keyword2'=>array('color'=>'#0095f6','value'=>'报错反馈'),
		'remark'=>array('color'=>'#0095f6','value'=>'信息摘要:'.$errorinfo)
	);

$tplnotice_data['task']['feedback']['admin']['touser'] = $settings['manageropenids'];
$tplnotice_data['task']['feedback']['admin']['template_id'] = $settings['msgtpls']['ebiz_task'];
$tplnotice_data['task']['feedback']['admin']['url'] = fm_murl('help','advise','index',array('info'=>$info,'contact'=>$contact));
$tplnotice_data['task']['feedback']['admin']['topcolor']= '#0095f6'; //这个参数目前测试没有生效
$tplnotice_data['task']['feedback']['admin']['data']=array(
		'first'=>array('color'=>'#f00','value'=>'您管理的商城收到一条用户反馈'),
		'keyword1'=>array('color'=>'#0095f6','value'=>'反馈建议'),
		'keyword2'=>array('color'=>'#0095f6','value'=>$info),
		'remark'=>array('color'=>'#0095f6','value'=>'联系方式:'.$contact)
	);

$tplnotice_data['task']['feedback']['reporter']['touser'] = $from_user;
$tplnotice_data['task']['feedback']['reporter']['template_id'] = $settings['msgtpls']['ebiz_task'];
$tplnotice_data['task']['feedback']['reporter']['url'] = fm_murl('help','advise','index',array('info'=>$info,'contact'=>$contact));
$tplnotice_data['task']['feedback']['reporter']['topcolor']= '#0095f6'; //这个参数目前测试没有生效
$tplnotice_data['task']['feedback']['reporter']['data']=array(
		'first'=>array('color'=>'#f00','value'=>'感谢您的反馈建议'),
		'keyword1'=>array('color'=>'#0095f6','value'=>'反馈建议'),
		'keyword2'=>array('color'=>'#0095f6','value'=>$info),
		'remark'=>array('color'=>'#0095f6','value'=>'联系方式:'.$contact)
	);

$tplnotice_data['task']['dashang']['admin']['touser'] =  $settings['manageropenids'];
$tplnotice_data['task']['dashang']['admin']['template_id'] = $settings['msgtpls']['ebiz_task'];
$tplnotice_data['task']['dashang']['admin']['url'] = fm_murl('article','detail','index',array('id'=>'','sn'=>$sn));
$tplnotice_data['task']['dashang']['admin']['topcolor']= '#0095f6'; //这个参数目前测试没有生效
$tplnotice_data['task']['dashang']['admin']['data']=array(
		'first'=>array('color'=>'#f00','value'=>'您的网站收到会员(ID:'.$order['fromuid'].')打赏'.$order['price'].'元'),
		'keyword1'=>array('color'=>'#0095f6','value'=>'结果通知'),
		'keyword2'=>array('color'=>'#0095f6','value'=>'文章打赏'),
		'remark'=>array('color'=>'#0095f6','value'=>'原文:'.$title)
	);

$tplnotice_data['task']['dashang']['author']['touser'] =  $articleAdm;
$tplnotice_data['task']['dashang']['author']['template_id'] = $settings['msgtpls']['ebiz_task'];
$tplnotice_data['task']['dashang']['author']['url'] = fm_murl('article','detail','index',array('id'=>'','sn'=>$sn));
$tplnotice_data['task']['dashang']['author']['topcolor']= '#0095f6'; //这个参数目前测试没有生效
$tplnotice_data['task']['dashang']['author']['data']=array(
		'first'=>array('color'=>'#f00','value'=>'您的关联文章收到会员(ID:'.$order['fromuid'].')打赏'.$order['price'].'元'),
		'keyword1'=>array('color'=>'#0095f6','value'=>'结果通知'),
		'keyword2'=>array('color'=>'#0095f6','value'=>'文章打赏'),
		'remark'=>array('color'=>'#0095f6','value'=>'原文:'.$title)
	);

$tplnotice_data['task']['order']['export']['admin']['touser'] =  $settings['manageropenids'];
$tplnotice_data['task']['order']['export']['admin']['template_id'] = $settings['msgtpls']['ebiz_task'];
$tplnotice_data['task']['order']['export']['admin']['url'] = '';
$tplnotice_data['task']['order']['export']['admin']['topcolor']= '#0095f6'; //这个参数目前测试没有生效
$tplnotice_data['task']['order']['export']['admin']['data']=array(
		'first'=>array('color'=>'#f00','value'=>$shopname.'通知'),
		'keyword1'=>array('color'=>'#0095f6','value'=>'订单导出'),
		'keyword2'=>array('color'=>'#0095f6','value'=>'后台管理'),
		'remark'=>array('color'=>'#0095f6','value'=>'管理员'.$_W['username'].'('.$_W['uid'].')'.'导出了订单数据')
	);

$tplnotice_data['task']['test']['admin']['touser'] =  $settings['manageropenids'];
$tplnotice_data['task']['test']['admin']['template_id'] = $settings['msgtpls']['ebiz_task'];
$tplnotice_data['task']['test']['admin']['url'] = '';
$tplnotice_data['task']['test']['admin']['topcolor']= '#0095f6'; //这个参数目前测试没有生效
$tplnotice_data['task']['test']['admin']['data']=array(
		'first'=>array('color'=>'#f00','value'=>$shopname.'测试'),
		'keyword1'=>array('color'=>'#0095f6','value'=>'管理员测试'),
		'keyword2'=>array('color'=>'#0095f6','value'=>'后台管理'),
		'remark'=>array('color'=>'#0095f6','value'=>'管理员'.$_W['username'].'('.$_W['uid'].')'.'将你设置为商城管理员，可接收到商城的相关系统通知')
	);

$tplnotice_data['task']['share']['sharefrom']['touser'] = $share_user;
$tplnotice_data['task']['share']['sharefrom']['template_id'] = $settings['msgtpls']['ebiz_task'];
$tplnotice_data['task']['share']['sharefrom']['url'] = $url;
$tplnotice_data['task']['share']['sharefrom']['topcolor']= '#0095f6'; //这个参数目前测试没有生效
$tplnotice_data['task']['share']['sharefrom']['data']=array(
		'first'=>array('color'=>'#f00','value'=>$shopname.'反馈'),
		'keyword1'=>array('color'=>'#0095f6','value'=>'分享结果'),
		'keyword2'=>array('color'=>'#0095f6','value'=>'前台通知'),
		'remark'=>array('color'=>'#0095f6','value'=>'你的分享我很喜欢，谢谢！(-By '.$_FM['member']['info']['nickname'].')')
	);

$tplnotice_data['task']['form']['new']['admin']['touser'] = $settings['manageropenids'];
$tplnotice_data['task']['form']['new']['admin']['template_id'] = $settings['msgtpls']['ebiz_task'];
$tplnotice_data['task']['form']['new']['admin']['url'] = fm_murl('appwebneeds','detail','index',array('id'=>$id,'sn'=>$sn));
$tplnotice_data['task']['form']['new']['admin']['topcolor']= '#0095f6'; //这个参数目前测试没有生效
$tplnotice_data['task']['form']['new']['admin']['data']=array(
		'first'=>array('color'=>'#f00','value'=>$shopname.'反馈'),
		'keyword1'=>array('color'=>'#0095f6','value'=>'有求必应'),
		'keyword2'=>array('color'=>'#0095f6','value'=>'前台通知'),
		'remark'=>array('color'=>'#0095f6','value'=>'收到用户提交的表单信息，请查看！(-By '.$username.'-'.$mobile.')')
	);

$tplnotice_data['task']['form']['new']['user']['touser'] = $_W['openid'];
$tplnotice_data['task']['form']['new']['user']['template_id'] = $settings['msgtpls']['ebiz_task'];
$tplnotice_data['task']['form']['new']['user']['url'] = fm_murl('needs','detail','index',array('id'=>$id,'sn'=>$sn));
$tplnotice_data['task']['form']['new']['user']['topcolor']= '#0095f6'; //这个参数目前测试没有生效
$tplnotice_data['task']['form']['new']['user']['data']=array(
		'first'=>array('color'=>'#f00','value'=>$shopname.'反馈'),
		'keyword1'=>array('color'=>'#0095f6','value'=>'有求必应'),
		'keyword2'=>array('color'=>'#0095f6','value'=>'前台通知'),
		'remark'=>array('color'=>'#0095f6','value'=>'我们已收到您提交的信息，点击详情可查看')
	);

$tplnotice_data['task']['form']['modify']['admin']['touser'] = $settings['manageropenids'];
$tplnotice_data['task']['form']['modify']['admin']['template_id'] = $settings['msgtpls']['ebiz_task'];
$tplnotice_data['task']['form']['modify']['admin']['url'] = fm_murl('appwebneeds','detail','index',array('id'=>$id,'sn'=>$sn));
$tplnotice_data['task']['form']['modify']['admin']['topcolor']= '#0095f6'; //这个参数目前测试没有生效
$tplnotice_data['task']['form']['modify']['admin']['data']=array(
		'first'=>array('color'=>'#f00','value'=>$shopname.'反馈'),
		'keyword1'=>array('color'=>'#0095f6','value'=>'有求必应'),
		'keyword2'=>array('color'=>'#0095f6','value'=>'前台通知'),
		'remark'=>array('color'=>'#0095f6','value'=>'用户表单信息更新，请查看！(-By '.$username.'-'.$mobile.')')
	);

$tplnotice_data['task']['form']['modify']['user']['touser'] = $booker['openid'];
$tplnotice_data['task']['form']['modify']['user']['template_id'] = $settings['msgtpls']['ebiz_task'];
$tplnotice_data['task']['form']['modify']['user']['url'] = fm_murl('needs','detail','index',array('id'=>$id,'sn'=>$sn));
$tplnotice_data['task']['form']['modify']['user']['topcolor']= '#0095f6'; //这个参数目前测试没有生效
$tplnotice_data['task']['form']['modify']['user']['data']=array(
		'first'=>array('color'=>'#f00','value'=>$shopname.'反馈'),
		'keyword1'=>array('color'=>'#0095f6','value'=>'信息更新'),
		'keyword2'=>array('color'=>'#0095f6','value'=>'前台通知'),
		'remark'=>array('color'=>'#0095f6','value'=>'您提交的信息已被更新，点击详情可查看')
	);

$tplnotice_data['task']['form']['reply']['admin']['touser'] = $settings['manageropenids'];
$tplnotice_data['task']['form']['reply']['admin']['template_id'] = $settings['msgtpls']['ebiz_task'];
$tplnotice_data['task']['form']['reply']['admin']['url'] = fm_murl('appwebneeds','detail','index',array('id'=>$id,'sn'=>$sn));
$tplnotice_data['task']['form']['reply']['admin']['topcolor']= '#0095f6'; //这个参数目前测试没有生效
$tplnotice_data['task']['form']['reply']['admin']['data']=array(
		'first'=>array('color'=>'#f00','value'=>$shopname.'反馈'),
		'keyword1'=>array('color'=>'#0095f6','value'=>'有求必应'),
		'keyword2'=>array('color'=>'#0095f6','value'=>'前台通知'),
		'remark'=>array('color'=>'#0095f6','value'=>'回复内容已记录，系统自动通知到对应用户！(-By '.$username.'-'.$mobile.')')
	);

$tplnotice_data['task']['form']['reply']['user']['touser'] = $booker['openid'];
$tplnotice_data['task']['form']['reply']['user']['template_id'] = $settings['msgtpls']['ebiz_task'];
$tplnotice_data['task']['form']['reply']['user']['url'] = fm_murl('needs','detail','index',array('id'=>$id,'sn'=>$sn));
$tplnotice_data['task']['form']['reply']['user']['topcolor']= '#0095f6'; //这个参数目前测试没有生效
$tplnotice_data['task']['form']['reply']['user']['data']=array(
		'first'=>array('color'=>'#f00','value'=>$shopname.'反馈'),
		'keyword1'=>array('color'=>'#0095f6','value'=>'信息更新'),
		'keyword2'=>array('color'=>'#0095f6','value'=>'前台通知'),
		'remark'=>array('color'=>'#0095f6','value'=>'管理员回复了您提交的信息，点击详情可查看')
	);

$tplnotice_data['task']['form']['dianzan']['admin']['touser'] = $settings['manageropenids'];
$tplnotice_data['task']['form']['dianzan']['admin']['template_id'] = $settings['msgtpls']['ebiz_task'];
$tplnotice_data['task']['form']['dianzan']['admin']['url'] = fm_murl('needs','detail','index',array('id'=>$id,'sn'=>$sn));
$tplnotice_data['task']['form']['dianzan']['admin']['topcolor']= '#0095f6'; //这个参数目前测试没有生效
$tplnotice_data['task']['form']['dianzan']['admin']['data']=array(
		'first'=>array('color'=>'#f00','value'=>$shopname.'反馈'),
		'keyword1'=>array('color'=>'#0095f6','value'=>'预约表单'),
		'keyword2'=>array('color'=>'#0095f6','value'=>'前台通知'),
		'remark'=>array('color'=>'#0095f6','value'=> $_W['fans']['nickname'].'给表单'.$needs['title'].'点赞了，点击查看详情！')
	);

$tplnotice_data['task']['form']['dianzan']['user']['touser'] = $booker['openid'];
$tplnotice_data['task']['form']['dianzan']['user']['template_id'] = $settings['msgtpls']['ebiz_task'];
$tplnotice_data['task']['form']['dianzan']['user']['url'] = fm_murl('needs','detail','index',array('id'=>$id,'sn'=>$sn));
$tplnotice_data['task']['form']['dianzan']['user']['topcolor']= '#0095f6'; //这个参数目前测试没有生效
$tplnotice_data['task']['form']['dianzan']['user']['data']=array(
		'first'=>array('color'=>'#f00','value'=>$shopname.'反馈'),
		'keyword1'=>array('color'=>'#0095f6','value'=>$needs['title']),
		'keyword2'=>array('color'=>'#0095f6','value'=>'点赞结果'),
		'remark'=>array('color'=>'#0095f6','value'=> $_W['fans']['nickname'].'感谢您的点赞支持，点击链接可继续查看！')
	);

$tplnotice_data['task']['member']['manage']['admin']['touser'] = $settings['manageropenids'];
$tplnotice_data['task']['member']['manage']['admin']['template_id'] = $settings['msgtpls']['ebiz_task'];
$tplnotice_data['task']['member']['manage']['admin']['url'] = '';
$tplnotice_data['task']['member']['manage']['admin']['topcolor']= '#0095f6'; //这个参数目前测试没有生效
$tplnotice_data['task']['member']['manage']['admin']['data']=array(
		'first'=>array('color'=>'#f00','value'=>$shopname.'提醒'),
		'keyword1'=>array('color'=>'#0095f6','value'=>'会员信息编辑'),
		'keyword2'=>array('color'=>'#0095f6','value'=>'后台通知'),
		'remark'=>array('color'=>'#0095f6','value'=>$_W['username'].'('.$_W['uid'].')'.'刚刚编辑了用户'.$profile['nickname'].'('.$profile['openid'].')的资料')
	);

$tplnotice_data['task']['order']['cancelsend']['admin']['touser'] = $settings['manageropenids'];
$tplnotice_data['task']['order']['cancelsend']['admin']['template_id'] = $settings['msgtpls']['ebiz_task'];
$tplnotice_data['task']['order']['cancelsend']['admin']['url'] = fm_murl('order','detail','index',array('id'=>$id));
$tplnotice_data['task']['order']['cancelsend']['admin']['topcolor']= '#0095f6'; //这个参数目前测试没有生效
$tplnotice_data['task']['order']['cancelsend']['admin']['data']=array(
		'first'=>array('color'=>'#f00','value'=>$shopname.'提醒'),
		'keyword1'=>array('color'=>'#0095f6','value'=>'更改订单状态为取消发货'),
		'keyword2'=>array('color'=>'#0095f6','value'=>'订单管理'),
		'remark'=>array('color'=>'#0095f6','value'=>'管理员'.$_W['username'].'('.$_W['uid'].')'.'操作,原因：'.$_GPC['cancelreason'])
	);