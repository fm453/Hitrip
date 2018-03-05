<?php

$notice_data['goods']['update']['admin']=array(
	'header'=>array('title'=>'事件通知','value'=>'更新商品'),
	'user'=>array('title'=>'操作人','value'=>$_W['username'].'('.$_W['uid'].')'),
	'goods'=>array('title'=>'产品名称','value'=>$goods['title']."(产品id".$id.")"),
	'previewurl'=>array('title'=>'产品链接','value'=>fm_murl('goods','detail','preview',array('id'=>$id)),
);

$notice_data['new']['admin'] = array(
	'header'=>array('title'=>'事件通知','value'=>'新建商品'),
	'user'=>array('title'=>'操作人','value'=>$_W['username'].'('.$_W['uid'].')'),
	'title'=>array('title'=>'产品名称','value'=>$data['title']),
	'url'=>array('title'=>'产品链接','value'=>fm_murl('goods','detail','index',array('id'=>$id)))
);

$notice_data['detail']['modify']['admin'] = array(
	'header'=>array('title'=>'事件通知','value'=>'更新商品'),
	'user'=>array('title'=>'操作人','value'=>$_W['username'].'('.$_W['uid'].')'),
	'title'=>array('title'=>'产品名称','value'=>$data['title']),
	'url'=>array('title'=>'产品链接','value'=>fm_murl('goods','detail','index',array('id'=>$id)))
)

$notice_data['list']['displayorder']['update']['admin'] = array(
	'header'=>array('title'=>'事件通知','value'=>'后台更新商品排序'),
	'user'=>array('title'=>'操作人','value'=>$_W['username'].'('.$_W['uid'].')'),
	'url'=>array('title'=>'执行网址','value'=>$_W['siteurl']),
)

$notice_data['list']['sn']['update']['admin'] = array(
	'header'=>array('title'=>'事件通知','value'=>'后台批量更新商品编码'),
	'user'=>array('title'=>'操作人','value'=>$_W['username'].'('.$_W['uid'].')'),
	'url'=>array('title'=>'执行网址','value'=>$_W['siteurl']),
)