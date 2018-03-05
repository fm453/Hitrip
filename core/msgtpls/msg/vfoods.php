<?php
$notice_data['vfoods']['payresult']['admin']=array(
	'header'=>array('title'=>'事件通知','value'=>'新订单提醒'),
	'user'=>array('title'=>'下单人','value'=>$order['username'].'('.$_W['openid'].')'),
	'contact'=>array('title'=>'联系方式','value'=>$order['mobile']),
	'info'=>array('title'=>'产品信息','value'=>'餐饮订单'),
	'url'=>array('title'=>'订单链接','value'=> fm_murl('appwebvfoods','order','detail',array('id'=>$orderid,'sn'=>$ordersn,"from_map"=>"messagetpl")))
);

$notice_data['vfoods']['payresult']['goodadm']=array(
	'header'=>array('title'=>'事件通知','value'=>'新订单提醒'),
	'user'=>array('title'=>'下单人','value'=>$order['username'].'('.$_W['openid'].')'),
	'contact'=>array('title'=>'联系方式','value'=>$order['mobile']),
	'info'=>array('title'=>'产品信息','value'=>'餐饮订单'),
	'url'=>array('title'=>'订单链接','value'=>fm_murl('appwebvfoods','order','detail',array('id'=>$orderid,'sn'=>$ordersn,"from_map"=>"messagetpl")))
);

$notice_data['vfoods']['payresult']['buyer']=array(
	'header'=>array('title'=>'事件通知','value'=>'订单支付结果'),
	'user'=>array('title'=>'下单人','value'=>$order['username'].'('.$_W['openid'].')'),
	'contact'=>array('title'=>'联系方式','value'=>$order['mobile']),
	'info'=>array('title'=>'产品信息','value'=>'餐饮订单'),
	'url'=>array('title'=>'订单链接','value'=>fm_murl('vfoods','myorder','detail',array('id'=>$orderid,'sn'=>$ordersn,"from_map"=>"messagetpl")))
);
