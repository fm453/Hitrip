<?php

$notice_data['dashang']['result']['admin'] = array(
	'header'=>array('title'=>'事件通知','value'=>'文章打赏'),
	'user'=>array('title'=>'下单人','value'=>$order['fromuid'].'('.$_W['openid'].')'),
	'contact'=>array('title'=>'联系方式','value'=>$address['username']."(".$address['mobile'].")"),
	'jifen'=>array('title'=>'金额','value'=>$order['price'].'元'),
	'info'=>array('title'=>'文章信息','value'=>$title),
	'url'=>array('title'=>'原文链接','value'=>fm_murl('article','detail','index',array('id'=>'','sn'=>$sn)))
);

$notice_data['dashang']['result']['author'] = array(
	'header'=>array('title'=>'事件通知','value'=>'文章打赏'),
	'user'=>array('title'=>'下单人','value'=>$order['fromuid'].'('.$_W['openid'].')'),
	'contact'=>array('title'=>'联系方式','value'=>$address['username']."(".$address['mobile'].")"),
	'jifen'=>array('title'=>'金额','value'=>$order['price'].'元'),
	'info'=>array('title'=>'文章信息','value'=>$title),
	'url'=>array('title'=>'原文链接','value'=>fm_murl('article','detail','index',array('id'=>'','sn'=>$sn)))
);

$notice_data['dashang']['jifen']['result']['complete']['admin'] = array(
	'header'=>array('title'=>'事件通知','value'=>'文章打赏结果'),
	'user'=>array('title'=>'下单人','value'=>$currentid.'('.$_W['openid'].')'),
	'info'=>array('title'=>'文章信息','value'=>$goodstitle),
	'jifen'=>array('title'=>'消耗积分','value'=>$params['fee']."(全额)"),
	'url'=>array('title'=>'原文链接','value'=>fm_murl('article','detail','index',array('id'=>$id,'sn'=>$sn)))
);

$notice_data['dashang']['jifen']['result']['complete']['payer'] =array(
	'header'=>array('title'=>'事件通知','value'=>'文章打赏结果'),
	'info'=>array('title'=>'文章信息','value'=>$goodstitle),
	'jifen'=>array('title'=>'消耗积分','value'=>$params['fee']."(感谢您的热心支持)"),
	'url'=>array('title'=>'原文链接','value'=>fm_murl('article','detail','index',array('id'=>$id,'sn'=>$sn)))
);

$notice_data['dashang']['jifen']['result']['part']['admin'] = array(
	'header'=>array('title'=>'事件通知','value'=>'文章打赏结果'),
	'user'=>array('title'=>'下单人','value'=>$currentid.'('.$_W['openid'].')'),
	'info'=>array('title'=>'文章信息','value'=>$goodstitle),
	'jifen'=>array('title'=>'消耗积分','value'=>"积分不足,此次消耗".$hascredit1),
	'url'=>array('title'=>'原文链接','value'=>fm_murl('article','detail','index',array('id'=>$id,'sn'=>$sn)))
);

$notice_data['dashang']['jifen']['result']['part']['payer'] = array(
	'header'=>array('title'=>'事件通知','value'=>'文章打赏结果'),
	'info'=>array('title'=>'文章信息','value'=>$goodstitle),
	'jifen'=>array('title'=>'消耗积分','value'=>"Sorry,您积分不足,此次使用".$hascredit1),
	'url'=>array('title'=>'原文链接','value'=>fm_murl('article','detail','index',array('id'=>$id,'sn'=>$sn)))
);

$notice_data['list']['displayorder']['update']['admin'] = array(
	'header'=>array('title'=>'事件通知','value'=>'后台更新文章排序'),
	'user'=>array('title'=>'操作人','value'=>$_W['username'].'('.$_W['uid'].')'),
	'url'=>array('title'=>'执行网址','value'=>$_W['siteurl']),
);

$notice_data['new']['admin'] = array(
	'header'=>array('title'=>'事件通知','value'=>'新建文章'),
	'user'=>array('title'=>'操作人','value'=>$_W['username'].'('.$_W['uid'].')'),
	'title'=>array('title'=>'文章名称','value'=>$articleData['title']),
	'url'=>array('title'=>'文章链接','value'=>fm_murl('article','detail','index',array('id'=>$id)))
);

$notice_data['detail']['modify']['admin'] = array(
	'header'=>array('title'=>'事件通知','value'=>'更新文章'),
	'user'=>array('title'=>'操作人','value'=>$_W['username'].'('.$_W['uid'].')'),
	'title'=>array('title'=>'文章名称','value'=>$articleData['title']),
	'url'=>array('title'=>'文章链接','value'=>fm_murl('article','detail','index',array('id'=>$id)))
);

$notice_data['dianzan']['admin'] = array(
	'header'=>array('title'=>'事件通知','value'=>'前台用户点赞'),
	'operator'=>array('title'=>'操作人','value'=>$_W['member']['nickname'].'('.$_W['openid'].')'),
	'user'=>array('title'=>'文章','value'=>$article['title']),
	'url'=>array('title'=>'链接','value'=>fm_murl('article','detail','index',array('id'=>$id)))
);

$notice_data['dianzan']['goodadm'] = array(
	'header'=>array('title'=>'事件通知','value'=>'文章点赞'),
	'operator'=>array('title'=>'操作人','value'=>$_W['member']['nickname'].'('.$_W['openid'].')'),
	'user'=>array('title'=>'文章','value'=>$article['title']),
	'url'=>array('title'=>'链接','value'=>fm_murl('article','detail','index',array('id'=>$id)))
);

$notice_data['dianzan']['reader'] = array(
	'header'=>array('title'=>'感谢支持','value'=>'您的点赞我们收到啦'),
	'content'=>array('title'=>'文章','value'=>$article['title']),
	'url'=>array('title'=>'链接','value'=>fm_murl('article','detail','index',array('id'=>$id)))
);