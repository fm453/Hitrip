<?php
$tplnotice_data['pay']['payresult']['ota']['admin']['touser'] = $settings['manageropenids'];
$tplnotice_data['pay']['payresult']['ota']['admin']['template_id'] = $settings['msgtpls']['ota_book_success'];
$tplnotice_data['pay']['payresult']['ota']['admin']['url'] = fm_murl('appweborder','detail','index',array('id'=>$orderid,'sn'=>$ordersn));
$tplnotice_data['pay']['payresult']['ota']['admin']['topcolor']= '#0095f6'; //这个参数目前测试没有生效
$tplnotice_data['pay']['payresult']['ota']['admin']['data']=array(
		'first'=>array('color'=>'#f00','value'=>$shopname.'新增订单提醒，订单号：'.$order['ordersn']),
		'type'=>array('color'=>'#0095f6','value'=>'联系人：'.$address['username']),
		'name'=>array('color'=>'#0095f6','value'=>'手机：'.$address['mobile']),
		'productType'=>array('color'=>'#0095f6','value'=>'商品'),
		'serviceName'=>array('color'=>'#0095f6','value'=>$goodstitle),
		'time'=>array('color'=>'#0095f6','value'=>date('Y-m-d H:i:s',$order['createtime'])),
		'remark'=>array('color'=>'#0095f6','value'=>'请尽快登陆后台进行处理'),
	);

$tplnotice_data['pay']['payresult']['ota']['goodsadmin']['touser'] = $goodsadms;
$tplnotice_data['pay']['payresult']['ota']['goodsadmin']['template_id'] = $settings['msgtpls']['ota_book_success'];
$tplnotice_data['pay']['payresult']['ota']['goodsadmin']['url'] = fm_murl('appweborder','detail','index',array('id'=>$orderid,'sn'=>$ordersn));
$tplnotice_data['pay']['payresult']['ota']['goodsadmin']['topcolor']= '#0095f6'; //这个参数目前测试没有生效
$tplnotice_data['pay']['payresult']['ota']['goodsadmin']['data']=array(
		'first'=>array('color'=>'#f00','value'=>$shopname.'订单提醒，订单号：'.$order['ordersn']),
		'type'=>array('color'=>'#0095f6','value'=>'联系人：'.$address['username']),
		'name'=>array('color'=>'#0095f6','value'=>'手机：'.$address['mobile']),
		'productType'=>array('color'=>'#0095f6','value'=>'商品'),
		'serviceName'=>array('color'=>'#0095f6','value'=>$goodstitle),
		'time'=>array('color'=>'#0095f6','value'=>date('Y-m-d H:i:s',$order['createtime'])),
		'remark'=>array('color'=>'#0095f6','value'=>'请尽快登陆后台进行处理'),
	);

$tplnotice_data['pay']['payresult']['ota']['buyer']['touser'] = $buyer;
$tplnotice_data['pay']['payresult']['ota']['buyer']['template_id'] = $settings['msgtpls']['ota_book_success'];
$tplnotice_data['pay']['payresult']['ota']['buyer']['url'] = fm_murl('order','index','index',array('id'=>$orderid,'sn'=>$ordersn));
$tplnotice_data['pay']['payresult']['ota']['buyer']['topcolor']= '#0095f6'; //这个参数目前测试没有生效
$tplnotice_data['pay']['payresult']['ota']['buyer']['data']=array(
		'first'=>array('color'=>'#f00','value'=>$shopname.'订单提醒，订单号：'.$order['ordersn']),
		'type'=>array('color'=>'#0095f6','value'=>'联系人：'.$address['username']),
		'name'=>array('color'=>'#0095f6','value'=>'手机：'.$address['mobile']),
		'productType'=>array('color'=>'#0095f6','value'=>'商品'),
		'serviceName'=>array('color'=>'#0095f6','value'=>$goodstitle),
		'time'=>array('color'=>'#0095f6','value'=>date('Y-m-d H:i:s',$order['createtime'])),
		'remark'=>array('color'=>'#0095f6','value'=>$row['tips'].'亲，您好，以上信息如有错误，请及时联系我们的客服纠正哦'),
	);


$payResultNameType=array(
	0=>'取消支付',
	1=>'支付成功',
	2=>'支付成功',
	3=>'未使用在线支付',
);
//使用回调的paytype作为索引

$tplnotice_data['pay']['payresult']['ebiz']['admin']['touser'] = $settings['manageropenids'];
$tplnotice_data['pay']['payresult']['ebiz']['admin']['template_id'] = $settings['msgtpls']['ebiz_order_payed'];
$tplnotice_data['pay']['payresult']['ebiz']['admin']['url'] = fm_murl('order','index','index',array('id'=>$orderid,'sn'=>$ordersn,"from_map"=>"messagetpl"));
$tplnotice_data['pay']['payresult']['ebiz']['admin']['topcolor']= '#0095f6'; //这个参数目前测试没有生效
$tplnotice_data['pay']['payresult']['ebiz']['admin']['data']=array(
		'first'=>array('color'=>'#f00','value'=>$shopname.'新订单提醒|'.$address['username'].$address['mobile']),
		'keyword1'=>array('color'=>'#0095f6','value'=>' '.$order['ordersn']),
		'keyword2'=>array('color'=>'#0095f6','value'=>$payResultNameType[2].'|'.$payname[$params['type']]),
		'keyword3'=>array('color'=>'#0095f6','value'=>date('Y-m-d H:i:s',$order['createtime'])),
		'keyword4'=>array('color'=>'#0095f6','value'=>$mpaccountname),
		'keyword5'=>array('color'=>'#0095f6','value'=>$order['price'].'元'),
		'remark'=>array('color'=>'#0095f6','value'=>'订单产品信息:'.$goodstitle.''.$tips),
	);

$tplnotice_data['pay']['payresult']['ebiz']['goodsadmin']['touser'] = $goodsadms;
$tplnotice_data['pay']['payresult']['ebiz']['goodsadmin']['template_id'] =  $settings['msgtpls']['ebiz_order_payed'];
$tplnotice_data['pay']['payresult']['ebiz']['goodsadmin']['url'] = fm_murl('order','index','index',array('id'=>$orderid,'sn'=>$ordersn));
$tplnotice_data['pay']['payresult']['ebiz']['goodsadmin']['topcolor']= '#0095f6'; //这个参数目前测试没有生效
$tplnotice_data['pay']['payresult']['ebiz']['goodsadmin']['data']=array(
		'first'=>array('color'=>'#f00','value'=>$shopname.'新订单提醒|'.$address['username'].$address['mobile']),
		'keyword1'=>array('color'=>'#0095f6','value'=>' '.$order['ordersn']),
		'keyword2'=>array('color'=>'#0095f6','value'=>$payResultNameType[2].'|'.$payname[$params['type']]),
		'keyword3'=>array('color'=>'#0095f6','value'=>date('Y-m-d H:i:s',$order['createtime'])),
		'keyword4'=>array('color'=>'#0095f6','value'=>$mpaccountname),
		'keyword5'=>array('color'=>'#0095f6','value'=>$order['price'].'元'),
		'remark'=>array('color'=>'#0095f6','value'=>'订单产品信息:'.$goodstitle.''.$tips),
	);

$tplnotice_data['pay']['payresult']['ebiz']['buyer']['touser'] = $buyer;
$tplnotice_data['pay']['payresult']['ebiz']['buyer']['template_id'] =  $settings['msgtpls']['ebiz_order_payed'];
$tplnotice_data['pay']['payresult']['ebiz']['buyer']['url'] = fm_murl('order','index','index',array('id'=>$orderid,'sn'=>$ordersn));
$tplnotice_data['pay']['payresult']['ebiz']['buyer']['topcolor']= '#0095f6'; //这个参数目前测试没有生效
$tplnotice_data['pay']['payresult']['ebiz']['buyer']['data']=array(
		'first'=>array('color'=>'#f00','value'=>"您的订单我们已经收悉，请稍候，我们正在为您联系安排！"),
		'keyword1'=>array('color'=>'#0095f6','value'=>' '.$order['ordersn']),
		'keyword2'=>array('color'=>'#0095f6','value'=>$payResultNameType[2].'|'.$payname[$params['type']]),
		'keyword3'=>array('color'=>'#0095f6','value'=>date('Y-m-d H:i:s',$order['createtime'])),
		'keyword4'=>array('color'=>'#0095f6','value'=>$mpaccountname),
		'keyword5'=>array('color'=>'#0095f6','value'=>$order['price'].'元'),
		'remark'=>array('color'=>'#0095f6','value'=>'订单产品信息:'.$goodstitle.''.$tips),
	);
