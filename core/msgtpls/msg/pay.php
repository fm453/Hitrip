<?php
$notice_data['pay']['result']['admin']=array(
	'header'=>array('title'=>'事件通知','value'=>'订单支付结果'),
	'user'=>array('title'=>'下单人','value'=>$order['fromuid'].'('.$_W['openid'].')'),
	'contact'=>array('title'=>'联系方式','value'=>$address['username']."(".$address['mobile'].")"),
	'info'=>array('title'=>'产品信息','value'=>$goodstitle),
	'url'=>array('title'=>'订单链接','value'=>fm_murl('order','index','index',array('id'=>$orderid,'sn'=>$ordersn))),
);

$notice_data['pay']['result']['goodadm']=array(
	'header'=>array('title'=>'事件通知','value'=>'订单支付结果'),
	'user'=>array('title'=>'下单人','value'=>$order['fromuid'].'('.$_W['openid'].')'),
	'contact'=>array('title'=>'联系方式','value'=>$address['username']."(".$address['mobile'].")"),
	'info'=>array('title'=>'产品信息','value'=>$goodstitle),
	'url'=>array('title'=>'订单链接','value'=>fm_murl('order','index','index',array('id'=>$orderid,'sn'=>$ordersn))),
);

$notice_data['pay']['result']['buyer']=array(
	'header'=>array('title'=>'事件通知','value'=>'订单支付结果'),
	'user'=>array('title'=>'下单人','value'=>$order['fromuid'].'('.$_W['openid'].')'),
	'contact'=>array('title'=>'联系方式','value'=>$address['username']."(".$address['mobile'].")"),
	'info'=>array('title'=>'产品信息','value'=>$goodstitle),
	'url'=>array('title'=>'订单链接','value'=>fm_murl('order','index','index',array('id'=>$orderid,'sn'=>$ordersn))),
);