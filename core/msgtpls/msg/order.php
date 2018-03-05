<?php

$notice_data['order']['new']['admin']=array(
	'header'=>array('title'=>'事件通知','value'=>'新订单生成'),
	'user'=>array('title'=>'下单人','value'=>$_W['fans']['nickname'].'('.$_W['openid'].')'),
	'contact'=>array('title'=>'联系方式','value'=>$address['username']."(".$address['mobile'].")"),
	'info'=>array('title'=>'产品信息','value'=>$goods['title']."(产品id".$id.")"),
	'url'=>array('title'=>'订单链接','value'=>fm_murl('appweborder','detail','index',array('id'=>$orderid,'sn'=>$ordersn))),
);

$notice_data['order']['new']['booker']=array(
	'header'=>array('title'=>'事件通知','value'=>'您的订单已生成'),
	'user'=>array('title'=>'下单人','value'=>$_W['fans']['nickname'].'('.$_W['openid'].')'),
	'contact'=>array('title'=>'联系方式','value'=>$address['username']."(".$address['mobile'].")"),
	'info'=>array('title'=>'产品信息','value'=>$goods['title']."(产品id".$id.")"),
	'url'=>array('title'=>'订单链接','value'=>fm_murl('order','index','index',array('id'=>$orderid,'sn'=>$ordersn))),
);

$notice_data['order']['send']['admin']=array(
	'header'=>array('title'=>'事件通知','value'=>'商城后台发货'),
	'operator'=>array('title'=>'操作人','value'=>$_W['username'].'('.$_W['uid'].')'),
	'ordersn'=>array('title'=>'订单号','value'=>$item['ordersn']),
	'express'=>array('title'=>'快递','value'=>$expresses[$_GPC['express']]),
	'expresssn'=>array('title'=>'快递单号','value'=>$_GPC['expresssn']),
	'user'=>array('title'=>'用户','value'=>$item['username']),
	'mobile'=>array('title'=>'联系手机','value'=>$item['mobile']),
	'adress'=>array('title'=>'联系地址','value'=>$item['user']['address']),
	'url'=>array('title'=>'订单链接','value'=>fm_murl('appweborder','detail','index',array('id'=>$orderid,'sn'=>$ordersn)))
);

$notice_data['order']['send']['goodsadm']=array(
	'header'=>array('title'=>'事件通知','value'=>'商城后台发货'),
	'operator'=>array('title'=>'操作人','value'=>$_W['username'].'('.$_W['uid'].')'),
	'ordersn'=>array('title'=>'订单号','value'=>$item['ordersn']),
	'express'=>array('title'=>'快递','value'=>$expresses[$_GPC['express']]),
	'expresssn'=>array('title'=>'快递单号','value'=>$_GPC['expresssn']),
	'user'=>array('title'=>'用户','value'=>$item['username']),
	'mobile'=>array('title'=>'联系手机','value'=>$item['mobile']),
	'adress'=>array('title'=>'联系地址','value'=>$item['user']['address']),
	'url'=>array('title'=>'订单链接','value'=>fm_murl('appweborder','detail','index',array('id'=>$orderid,'sn'=>$ordersn)))
);

$notice_data['order']['send']['buyer']=array(
	'header'=>array('title'=>'事件通知','value'=>'您的订单已经发货'),
	'ordersn'=>array('title'=>'订单号','value'=>$item['ordersn']),
	'express'=>array('title'=>'快递','value'=>$expresses[$_GPC['express']]),
	'expresssn'=>array('title'=>'快递单号','value'=>$_GPC['expresssn']),
	'user'=>array('title'=>'联系人','value'=>$item['username']),
	'mobile'=>array('title'=>'联系手机','value'=>$item['mobile']),
	'adress'=>array('title'=>'联系地址','value'=>$item['user']['address']),
	'url'=>array('title'=>'订单链接','value'=>fm_murl('order','index','index',array('id'=>$id)))
);

$notice_data['order']['cancelsend']['admin']=array(
	'header'=>array('title'=>'事件通知','value'=>'商城后台取消发货'),
	'operator'=>array('title'=>'操作人','value'=>$_W['username'].'('.$_W['uid'].')'),
	'ordersn'=>array('title'=>'订单号','value'=>$item['ordersn']),
	'user'=>array('title'=>'用户','value'=>$item['username']),
	'mobile'=>array('title'=>'联系手机','value'=>$item['mobile']),
	'reason'=>array('title'=>'取消原因','value'=>$_GPC['cancelreson'])
);

$notice_data['order']['kefubeizhu']['admin']=array(
	'header'=>array('title'=>'事件通知','value'=>'客服添加订单备注'),
	'operator'=>array('title'=>'操作人','value'=>$_W['username'].'('.$_W['uid'].')'),
	'ordersn'=>array('title'=>'订单号','value'=>$item['ordersn']),
	'user'=>array('title'=>'用户','value'=>$item['username']),
	'mobile'=>array('title'=>'联系手机','value'=>$item['mobile']),
	'remark'=>array('title'=>'客服备注','value'=>$_GPC['remark_kf']),
	'url'=>array('title'=>'链接','value'=>fm_murl('appweborder','detail','index',array('id'=>$orderid,'sn'=>$ordersn)))
);

$notice_data['order']['changeprice']['admin']=array(
	'header'=>array('title'=>'事件通知','value'=>'商城后台修改订单价格'),
	'operator'=>array('title'=>'操作人','value'=>$_W['username'].'('.$_W['uid'].')'),
	'ordersn'=>array('title'=>'订单号','value'=>$item['ordersn']),
	'user'=>array('title'=>'用户','value'=>$item['username']),
	'mobile'=>array('title'=>'联系手机','value'=>$item['mobile']),
	'changed'=>array('title'=>'改价情况','value'=>'新价格'.$_GPC['newprice'].'；原价'.$item['price']),
	'url'=>array('title'=>'链接','value'=>fm_murl('appweborder','detail','index',array('id'=>$orderid,'sn'=>$ordersn)))
);

$notice_data['order']['finish']['admin']=array(
	'header'=>array('title'=>'事件通知','value'=>'商城后台确认完成订单'),
	'operator'=>array('title'=>'操作人','value'=>$_W['username'].'('.$_W['uid'].')'),
	'ordersn'=>array('title'=>'订单号','value'=>$item['ordersn']),
	'user'=>array('title'=>'用户','value'=>$item['username']),
	'mobile'=>array('title'=>'联系手机','value'=>$item['mobile']),
);

$notice_data['order']['cancel']['admin']=array(
	'header'=>array('title'=>'事件通知','value'=>'商城后台取消订单'),
	'operator'=>array('title'=>'操作人','value'=>$_W['username'].'('.$_W['uid'].')'),
	'ordersn'=>array('title'=>'订单号','value'=>$item['ordersn']),
	'user'=>array('title'=>'用户','value'=>$item['username']),
	'mobile'=>array('title'=>'联系手机','value'=>$item['mobile']),
	'url'=>array('title'=>'链接','value'=>fm_murl('appweborder','detail','index',array('id'=>$orderid,'sn'=>$ordersn)))
);

$notice_data['order']['delete']['admin']=array(
	'header'=>array('title'=>'事件通知','value'=>'商城后台删除订单'),
	'operator'=>array('title'=>'操作人','value'=>$_W['username'].'('.$_W['uid'].')'),
	'ordersn'=>array('title'=>'订单号','value'=>$item['ordersn']),
	'user'=>array('title'=>'用户','value'=>$item['username']),
	'mobile'=>array('title'=>'联系手机','value'=>$item['mobile']),
	'url'=>array('title'=>'链接','value'=>fm_murl('appweborder','detail','index',array('id'=>$orderid,'sn'=>$ordersn)))
);

$notice_data['order']['export']['admin']=array(
	'header'=>array('title'=>'事件通知','value'=>'后台导出订单数据'),
	'user'=>array('title'=>'操作人','value'=>$_W['username'].'('.$_W['uid'].')'),
	'url'=>array('title'=>'执行网址','value'=>$_W['siteurl']),
);