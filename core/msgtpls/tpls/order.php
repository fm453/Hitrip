<?php

$tplnotice_data['order']['new']['booker']['touser']=$_W['openid'];
$tplnotice_data['order']['new']['booker']['template_id']=$settings['msgtpls']['ebiz_order_post'];
$tplnotice_data['order']['new']['booker']['url']=fm_murl('order','detail','index',array('id'=>$orderid));
$tplnotice_data['order']['new']['booker']['topcolor']='#0095f6';
$tplnotice_data['order']['new']['booker']['data']=array(
		'first'=>array('color'=>'#f00','value'=>'您的订单已经提交'),
		'keyword1'=>array('color'=>'#0095f6','value'=>$shopname),
		'keyword2'=>array('color'=>'#0095f6','value'=>date('Y年m月d日 H:i',TIMESTAMP)),
		'keyword3'=>array('color'=>'#0095f6','value'=>$goods['title']),
		'keyword4'=>array('color'=>'#0095f6','value'=>$totalprice.'元'),
		'remark'=>array('color'=>'#0095f6','value'=>'点击链接可进入订单详情页；订单正等您支付哦…')
	);

$tplnotice_data['order']['new']['goodsadm']['touser']=$goods['goodadm'];
$tplnotice_data['order']['new']['goodsadm']['template_id']=$settings['msgtpls']['ebiz_order_post'];
$tplnotice_data['order']['new']['goodsadm']['url']=fm_murl('appweborder','detail','index',array('id'=>$orderid,'sn'=>$ordersn));
$tplnotice_data['order']['new']['goodsadm']['topcolor']='#0095f6';
$tplnotice_data['order']['new']['goodsadm']['data']=array(
		'first'=>array('color'=>'#f00','value'=>'您所管理的产品刚刚有一张新订单产生'),
		'keyword1'=>array('color'=>'#0095f6','value'=>$shopname),
		'keyword2'=>array('color'=>'#0095f6','value'=>date('Y年m月d日 H:i',TIMESTAMP)),
		'keyword3'=>array('color'=>'#0095f6','value'=>$goods['title']),
		'keyword4'=>array('color'=>'#0095f6','value'=>$totalprice.'元'),
		'remark'=>array('color'=>'#0095f6','value'=>'请保持跟进订单(订单尚未支付)')
	);

$tplnotice_data['order']['new']['admin']['touser']=$settings['manageropenids'];
$tplnotice_data['order']['new']['admin']['template_id']=$settings['msgtpls']['ebiz_order_post'];
$tplnotice_data['order']['new']['admin']['url']=fm_murl('appweborder','detail','index',array('id'=>$orderid,'sn'=>$ordersn));
$tplnotice_data['order']['new']['admin']['topcolor']='#0095f6';
$tplnotice_data['order']['new']['admin']['data']=array(
		'first'=>array('color'=>'#f00','value'=>'您所管理的产品刚刚有一张新订单产生'),
		'keyword1'=>array('color'=>'#0095f6','value'=>$shopname),
		'keyword2'=>array('color'=>'#0095f6','value'=>date('Y年m月d日 H:i',TIMESTAMP)),
		'keyword3'=>array('color'=>'#0095f6','value'=>$goods['title']),
		'keyword4'=>array('color'=>'#0095f6','value'=>$totalprice.'元'),
		'remark'=>array('color'=>'#0095f6','value'=>'请保持跟进订单(订单尚未支付)')
	);

$tplnotice_data['order']['send']['booker']['touser']=$item['from_user'];
$tplnotice_data['order']['send']['booker']['template_id']=$settings['msgtpls']['ebiz_order_send'];
$tplnotice_data['order']['send']['booker']['url']=fm_murl('order','detail','index',array('id'=>$id));
$tplnotice_data['order']['send']['booker']['topcolor']='#0095f6';
$tplnotice_data['order']['send']['booker']['data']=array(
		'first'=>array('color'=>'#f00','value'=>'您的订单已经发货'),
		'keyword1'=>array('color'=>'#0095f6','value'=>$goodsName),
		'keyword2'=>array('color'=>'#0095f6','value'=>$expresses[$_GPC['express']]),
		'keyword3'=>array('color'=>'#0095f6','value'=>$_GPC['expresssn']),
		'keyword4'=>array('color'=>'#0095f6','value'=>$item['username'].'    '.$item['mobile'].'    '.$item['address']),
		'remark'=>array('color'=>'#0095f6','value'=>'请注意保持手机畅通，以便及时收货哦！')
	);

$tplnotice_data['order']['send']['goodsadm']['touser']=$goodsAdm;
$tplnotice_data['order']['send']['goodsadm']['template_id']=$settings['msgtpls']['ebiz_order_send'];
$tplnotice_data['order']['send']['goodsadm']['url']=fm_murl('appweborder','detail','index',array('id'=>$id));
$tplnotice_data['order']['send']['goodsadm']['topcolor']='#0095f6';
$tplnotice_data['order']['send']['goodsadm']['data']=array(
		'first'=>array('color'=>'#f00','value'=>'您所管理的产品刚刚有一张订单已经发货'),
		'keyword1'=>array('color'=>'#0095f6','value'=>$goodsName),
		'keyword2'=>array('color'=>'#0095f6','value'=>$expresses[$_GPC['express']]),
		'keyword3'=>array('color'=>'#0095f6','value'=>$_GPC['expresssn']),
		'keyword4'=>array('color'=>'#0095f6','value'=>$item['username'].'    '.$item['mobile'].'    '.$item['address']),
		'remark'=>array('color'=>'#0095f6','value'=>'请保持跟进订单进度！')
	);

$tplnotice_data['order']['send']['admin']['touser']=$settings['manageropenids'];
$tplnotice_data['order']['send']['admin']['template_id']=$settings['msgtpls']['ebiz_order_send'];
$tplnotice_data['order']['send']['admin']['url']=fm_murl('appweborder','detail','index',array('id'=>$id));
$tplnotice_data['order']['send']['admin']['topcolor']='#0095f6';
$tplnotice_data['order']['send']['admin']['data']=array(
		'first'=>array('color'=>'#f00','value'=>'您的商城刚刚有一张订单已经发货'),
		'keyword1'=>array('color'=>'#0095f6','value'=>$goodsName),
		'keyword2'=>array('color'=>'#0095f6','value'=>$expresses[$_GPC['express']]),
		'keyword3'=>array('color'=>'#0095f6','value'=>$_GPC['expresssn']),
		'keyword4'=>array('color'=>'#0095f6','value'=>$item['username'].'    '.$item['mobile'].'    '.$item['address']),
		'remark'=>array('color'=>'#0095f6','value'=>'请及时安排相关人员保持跟进订单进度！')
	);