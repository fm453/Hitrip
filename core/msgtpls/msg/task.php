<?php
$notice_data['feedback']['help']['admin']=array(
	'header'=>array('title'=>'事件通知','value'=>'前台用户反馈建议'),
	'user'=>array('title'=>'提交人','value'=>$FM_member['nickname'].'('.$_W['openid'].')'),
	'contact'=>array('title'=>'联系方式','value'=>$contact),
	'info'=>array('title'=>'反馈信息','value'=>$info),
	'stars'=>array('title'=>'星级','value'=>$stars),
	'url'=>array('title'=>'执行网址','value'=>$_W['siteurl'])
);

$notice_data['test']['manageropenids']['admin']=array(
	'header'=>array('title'=>'事件通知','value'=>$shopname.'后台测试'),
	'user'=>array('title'=>'管理员','value'=>$_W['username'].'('.$_W['uid'].')'),
	'info'=>array('title'=>'反馈信息','value'=>'您被添加为商城管理员，可接收到商城的相关系统通知')
);

$notice_data['task']['share']['sharefrom']=array(
	'header'=>array('title'=>'事件通知','value'=>$shopname.'分享反馈'),
	'clicker'=>array('title'=>'点击人','value'=>$_W['member']['realrname']),
	'info'=>array('title'=>'反馈信息','value'=>'您分享的链接获得有效点击一次，感谢您的支持！')
);

$notice_data['form']['new']['admin']=array(
	'header'=>array('title'=>'事件通知','value'=>$shopname.' 有求必应表单有新提交'),
	'clicker'=>array('title'=>'提交人','value'=>$username.'('.$mobile.')'),
	'url'=>array('title'=>'链接','value'=> fm_murl('appwebneeds','detail','index',array('id'=>$id,'sn'=>$sn)))
);

$notice_data['form']['modify']['admin']=array(
	'header'=>array('title'=>'事件通知','value'=>$shopname.' 有求必应表单信息更新'),
	'clicker'=>array('title'=>'更新人','value'=>$username.'('.$mobile.')'),
	'url'=>array('title'=>'链接','value'=> fm_murl('appwebneeds','detail','index',array('id'=>$id,'sn'=>$sn)))
);

$notice_data['form']['dianzan']['admin']=array(
	'header'=>array('title'=>'事件通知','value'=>$shopname.' 有求必应表单收到点赞'),
	'clicker'=>array('title'=>'点赞人','value'=>$_W['fans']['nickname']),
	'url'=>array('title'=>'链接','value'=> fm_murl('appwebneeds','detail','index',array('id'=>$id,'sn'=>$sn)))
);

$notice_data['form']['reply']['admin']=array(
	'header'=>array('title'=>'事件通知','value'=>$shopname.' 有求必应表单信息更新'),
	'clicker'=>array('title'=>'回复人','value'=>$username.'('.$mobile.')'),
	'content'=>array('title'=>'回复内容','value'=>$content),
	'url'=>array('title'=>'链接','value'=> fm_murl('appwebneeds','detail','index',array('id'=>$id,'sn'=>$sn)))
);

$notice_data['form']['reply']['booker'] =	"您提交的表单信息收到回复：".'\n\r'.$content.'\n\r'."<a href='". fm_murl('needs','detail','index',array('id'=>$id,'sn'=>$sn))."'>点击链接查看详情</a>";

$notice_data['errorreport']['admin'] = array(
	'header'=>array('title'=>'事件通知','value'=>'前台报错反馈'),
	'user'=>array('title'=>'报错人','value'=>$FM_member['nickname'].'('.$_W['openid'].')'),
	'contact'=>array('title'=>'联系方式','value'=>$contact),
	'info'=>array('title'=>'报错信息','value'=>$errorinfo),
	'url'=>array('title'=>'执行网址','value'=>$reportUrl)
);

$notice_data['member']['manage']['admin'] = array(
	'header'=>array('title'=>'事件通知','value'=>'后台编辑会员资料'),
	'operator'=>array('title'=>'操作人','value'=>$_W['username'].'('.$_W['uid'].')'),
	'user'=>array('title'=>'用户','value'=>$profile['openid'])
);