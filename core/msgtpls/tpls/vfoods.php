<?php
$payResultNameType=array(
	0=>'取消支付',
	1=>'支付成功',
	2=>'支付成功',
	3=>'未使用在线支付',
);
//使用回调的paytype作为索引

$tplnotice_data['vfoods']['payresult']['ebiz']['admin']['touser'] = $settings['manageropenids'];
$tplnotice_data['vfoods']['payresult']['ebiz']['admin']['template_id'] = $settings['msgtpls']['ebiz_order_payed'];
$tplnotice_data['vfoods']['payresult']['ebiz']['admin']['url'] = fm_murl('appwebvfoods','order','detail',array('id'=>$orderid,'sn'=>$ordersn,"from_map"=>"messagetpl"));
$tplnotice_data['vfoods']['payresult']['ebiz']['admin']['topcolor']= '#0095f6'; //这个参数目前测试没有生效
$tplnotice_data['vfoods']['payresult']['ebiz']['admin']['data']=array(
		'first'=>array('color'=>'#f00','value'=>$shopname.'新订单提醒|'.$order['username'].$order['mobile']),
		'keyword1'=>array('color'=>'#0095f6','value'=>' '.$order['ordersn']),
		'keyword2'=>array('color'=>'#0095f6','value'=>$payResultNameType[2].'|'.$payname[$params['type']]),
		'keyword3'=>array('color'=>'#0095f6','value'=>date('Y-m-d H:i:s',$order['createtime'])),
		'keyword4'=>array('color'=>'#0095f6','value'=>$_W['uniaccount']['name']),
		'keyword5'=>array('color'=>'#0095f6','value'=>$order['price'].'元'),
		'remark'=>array('color'=>'#0095f6','value'=>'订单备注:'.$order['price']),
	);

$tplnotice_data['vfoods']['payresult']['ebiz']['goodsadmin']['touser'] = $goodsadms;
$tplnotice_data['vfoods']['payresult']['ebiz']['goodsadmin']['template_id'] =  $settings['msgtpls']['ebiz_order_payed'];
$tplnotice_data['vfoods']['payresult']['ebiz']['goodsadmin']['url'] = fm_murl('appwebvfoods','order','detail',array('id'=>$orderid,'sn'=>$ordersn,"from_map"=>"messagetpl"));
$tplnotice_data['vfoods']['payresult']['ebiz']['goodsadmin']['topcolor']= '#0095f6'; //这个参数目前测试没有生效
$tplnotice_data['vfoods']['payresult']['ebiz']['goodsadmin']['data']=array(
		'first'=>array('color'=>'#f00','value'=>$shopname.'新订单提醒|'.$order['username'].$order['mobile']),
		'keyword1'=>array('color'=>'#0095f6','value'=>' '.$order['ordersn']),
		'keyword2'=>array('color'=>'#0095f6','value'=>$payResultNameType[2].'|'.$payname[$params['type']]),
		'keyword3'=>array('color'=>'#0095f6','value'=>date('Y-m-d H:i:s',$order['createtime'])),
		'keyword4'=>array('color'=>'#0095f6','value'=>$_W['uniaccount']['name']),
		'keyword5'=>array('color'=>'#0095f6','value'=>$order['price'].'元'),
		'remark'=>array('color'=>'#0095f6','value'=>'订单备注:'.$order['price']),
	);

$tplnotice_data['vfoods']['payresult']['ebiz']['buyer']['touser'] = $buyer;
$tplnotice_data['vfoods']['payresult']['ebiz']['buyer']['template_id'] =  $settings['msgtpls']['ebiz_order_payed'];
$tplnotice_data['vfoods']['payresult']['ebiz']['buyer']['url'] = fm_murl('vfoods','myorder','detail',array('id'=>$orderid,'sn'=>$ordersn,"from_map"=>"messagetpl"));
$tplnotice_data['vfoods']['payresult']['ebiz']['buyer']['topcolor']= '#0095f6'; //这个参数目前测试没有生效
$tplnotice_data['vfoods']['payresult']['ebiz']['buyer']['data']=array(
		'first'=>array('color'=>'#f00','value'=>"您的订单我们已经收悉，请稍候，我们正在为您联系安排！"),
		'keyword1'=>array('color'=>'#0095f6','value'=>' '.$order['ordersn']),
		'keyword2'=>array('color'=>'#0095f6','value'=>$payResultNameType[2].'|'.$payname[$params['type']]),
		'keyword3'=>array('color'=>'#0095f6','value'=>date('Y-m-d H:i:s',$order['createtime'])),
		'keyword4'=>array('color'=>'#0095f6','value'=>$_W['uniaccount']['name']),
		'keyword5'=>array('color'=>'#0095f6','value'=>$order['price'].'元'),
		'remark'=>array('color'=>'#0095f6','value'=>'订单备注:'.$order['price']),
	);
