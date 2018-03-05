<?php
/**
 * @author Fm453(方少)
 * @DACMS https://api.hiluker.com
 * @site https://www.hiluker.com
 * @url http://s.we7.cc/index.php?c=home&a=author&do=index&uid=662
 * @email fm453@lukegzs.com
 * @QQ 393213759
 * @wechat 393213759
*/

/*
 * @remark 接口-乐达嗨骑
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;
fm_load()->fm_func('server'); //授权服务器
//入口判断
$do= $_GPC['do'];
$ac=$_GPC['ac'];
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'index';

//引入配置
require_once(FM_APP.$do.'/hiride/_config.php');
//来路计算
$ip = fmFunc_server_via_ip();
$domain = fmFunc_server_via_domain();

//开始操作管理

if($op=='index'){
	checkauth();
	$appstyle = empty($settings['appstyle']) ? FM_APPSTYLE : $settings['appstyle'];
	$appsrc =MODULE_URL.'template/mobile/'.$appstyle.'453/';
	$htmlsrc =MODULE_URL.'template/mobile/'.$appstyle;
	$fm453resource = FM_RESOURCE;
	//显示订单信息
	$state = $_GET['state'];
	$id = $_GET['id'];
	$expire=$_GET['expire'];
	$code = $_GET['code'];
	if($code !=md5('fm453_'.$expire)){
		// fm_error('抱歉，您没有该链接的查看权限','系统提示');
	}
	if($state=='bike'){
		$table = 'fm453_api_hiride_bike_order';
		$sql = "SELECT * FROM ".tablename($table)." WHERE `id` =:id";
		$_order = pdo_fetch($sql,array(':id'=>$id));
		$_order = $_order['_ori_content'];
		$_order = json_decode($_order, JSON_UNESCAPED_UNICODE);
		$order = $_order['data'];
	}
	if($state=='shop'){
		$table = 'fm453_api_hiride_shop_order';
		$sql = "SELECT * FROM ".tablename($table)." WHERE `id` =:id";
		$_order = pdo_fetch($sql,array(':id'=>$id));
		$_order = $_order['_ori_content'];
		$_order = json_decode($_order, JSON_UNESCAPED_UNICODE);
		$order = $_order;

		if($order['step']==700){
			$order['step']="待支付";
		}elseif ($order['step']==900) {
			$order['step']="待发货";
		}else{
			$order['step']="其他";
		}
		if($order['status']==1){
			$order['step'] .= "|正常";
		}else{
			$order['step'] .= "异常";
		}
	}
	if($state=='tour'){
		$table = 'fm453_api_hiride_tour_order';
		$sql = "SELECT * FROM ".tablename($table)." WHERE `id` =:id";
		$_order = pdo_fetch($sql,array(':id'=>$id));
		$_order = $_order['_ori_content'];
		$_order = json_decode($_order, JSON_UNESCAPED_UNICODE);
		$order = $_order['data'];

		//格式化同行人员
		$teamers = array();
		foreach($order['adultnamearr'] as $i=>$item){
			$teamers[] = array('label'=>'成人','name'=>$item,'idcard'=>$order['adultidarr'][$i]);
		}
		foreach($order['childnamearr'] as $i=>$item){
			$teamers[] = array('label'=>'儿童','name'=>$item,'idcard'=>$order['childidarr'][$i]);
		}
		$order['teamers'] = $teamers;
	}

	include $this->template($appstyle.$do.'/453');
}

if($op=='bike'){
	//租车推送
	$temp_order = $_GPC['order'];
	if($_GET['debug']==1){
		$temp_order = '{"code":202,"msg":"您还有进行中的订单","data":{"order_no":"20180116141849xQoQ","bid":75500005,"bmid":"2","lid":"121","ltype":2,"macaddr":"C9:A7:AF:D9:F1:3B","pid":"31","hid":"19","btid":"2","ptype":"2","unit":"1","price1":"1","price2":"1","price3":"1","price4":"1","price5":"1","price6":"1","insurance":"1","realname":"技术猿","id_no":"460101198806028888","realname1":"嗨骑","id_no1":"460101198806029999","realname2":"","id_no2":"","realname3":"","id_no3":"","phone":"13425134901","time_from":"2018年01月16日 14:18","time_to":"","get_mile":0,"rtn_mile":0,"get_power":"0","rtn_power":"0","mile_by_power":"0","times":0,"timefee":0,"get_from":"","get_lng":0,"get_lat":0,"rtn_to":"","rtn_lng":0,"rtn_lat":0,"miles":0,"milefee":0,"total":0,"available":0,"save":0,"pay":0,"step":500,"status":1,"time_from_ut":"1516083529","lng":0,"lat":0,"co2":0}}';	//测试订单数据
	}
	$_order = json_decode($temp_order, JSON_UNESCAPED_UNICODE);
	$order = $_order['data'];
	if(!$order){
	    $result = array('code'=>1,'msg'=>'未传送有效订单信息');
	    exit(json_encode($result));
	}
	$sn = $order['order_no'];
	$table = 'fm453_api_hiride_bike_order';
	$sql = "SELECT * FROM ".tablename($table)." WHERE `order_no` =:order_no";
	$local_order = pdo_fetch($sql,array(':order_no'=>$sn));
	if(!$local_order){
		$local_order = array('order_no'=>$sn,'_ori_content'=>$temp_order,'createtime'=>$_W['timestamp'],'wx_tpl_times'=>0);
		pdo_insert($table,$local_order);
		$id = pdo_insertid();
	}else{
		$id = $local_order['id'];
	}
	$expire = $_W['timestamp']+3600*12;
	$url = fm_murl($do,$ac,'index',array('state'=>'bike','id'=>$id,'code'=>md5('fm453_'.$expire),'expire'=>$expire));

	//给fm453发个微信消息
	$result = fmMod_notice('oD7Cmsy_2Rq2jV_fysgvaxMsJndo',array(
		'header'=>array('title'=>'事件通知','value'=>'嗨骑定制接口租车订单推送'),
		'ip'=>array('title'=>'来路IP','value'=>$ip),
		'domain'=>array('title'=>'来路域名','value'=>$domain),
		'url'=>array('title'=>'查看链接','value'=>$url)
	));

	//给嗨骑关联人员发送微信模板消息
	foreach($openids['bike']['leda'] as $item){
		if($item['status']==1){
			$tousers = $item['openid'];
			$remark = '下单人：'.$order['realname'].' ; 手机：'.$order['phone'];
			$remark .= "\r\n";
			$remark .= "请尽快查看处理;点击可查看订单摘要信息。";
			$postdata = array(
				'first'=>array('color'=>'#f00','value'=>$item['name'].'，您收到一条订单信息'),
				'keyword1'=>array('color'=>'#0095f6','value'=>'嗨骑租车订单推送通知'),
				'keyword2'=>array('color'=>'#0095f6','value'=>'新增订单'.$sn),
				'remark'=>array('color'=>'#0095f6','value'=>$remark)
			);
			$result = fmFunc_msg_sendTplNotice($tousers, $wxmsg_template_id, $postdata, $url, $platid = 1, $WeAccount = NULL);
		}
	}

	//给网点关联人员发送微信模板消息
	foreach($openids['bike']['hotel'] as $item){
		if($item['status']==1){
			$tousers = $item['openid'];
			$remark = '下单人：'.$order['realname'].' ; 手机：'.$order['phone'];
			$remark .= "\r\n";
			$remark .= "请尽快查看处理;点击可查看订单摘要信息。";
			$postdata = array(
				'first'=>array('color'=>'#f00','value'=>$item['name'].'，您收到一条订单信息'),
				'keyword1'=>array('color'=>'#0095f6','value'=>'嗨骑租车订单推送通知'),
				'keyword2'=>array('color'=>'#0095f6','value'=>'新增订单'.$sn),
				'remark'=>array('color'=>'#0095f6','value'=>$remark)
			);
			$result = fmFunc_msg_sendTplNotice($tousers, $wxmsg_template_id, $postdata, $url, $platid = 1, $WeAccount = NULL);
		}
	}

	//累计一次通知次数
	pdo_update($table,array('wx_tpl_times'=>$local_order['wx_tpl_times']+1),array('id'=>$id));
	$result = array('code'=>0,'msg'=>'消息发送完毕');
	exit(json_encode($result));
}

if($op=='shop'){
	//商城推送
	$temp_order = $_GPC['order'];
	if($_GET['debug']==1){
		$temp_order = '{"order_no":"20180205113645O32x","uid":11,"phone":"13425134901","mgid":1,"mgname":"飘鱼S6 智能助力车","color":"绿色","price":4888,"num":1,"name":"嗨骑","contact":"18620627389","addr":"海南省三亚市天涯区嗨骑小院","postcode":"510180","total":4888,"step":700,"pay":0.01,"save":"4887.99","status":1,"time":1517801805}';	//测试订单数据
	}
	$_order = json_decode($temp_order, JSON_UNESCAPED_UNICODE);
	$order = $_order;
	if(!$order){
	    $result = array('code'=>1,'msg'=>'未传送有效订单信息');
	    exit(json_encode($result));
	}
	$sn = $order['order_no'];
	$table = 'fm453_api_hiride_shop_order';
	$sql = "SELECT * FROM ".tablename($table)." WHERE `order_no` =:order_no";
	$local_order = pdo_fetch($sql,array(':order_no'=>$sn));
	if(!$local_order){
		$local_order = array('order_no'=>$sn,'_ori_content'=>$temp_order,'createtime'=>$_W['timestamp'],'wx_tpl_times'=>0);
		pdo_insert($table,$local_order);
		$id = pdo_insertid();
	}else{
		$id = $local_order['id'];
	}
	$expire = $_W['timestamp']+3600*12;
	$url = fm_murl($do,$ac,'index',array('state'=>'shop','id'=>$id,'code'=>md5('fm453_'.$expire),'expire'=>$expire));

	//给fm453发个微信消息
	$result = fmMod_notice('oD7Cmsy_2Rq2jV_fysgvaxMsJndo',array(
		'header'=>array('title'=>'事件通知','value'=>'嗨骑定制接口商城订单推送'),
		'ip'=>array('title'=>'来路IP','value'=>$ip),
		'domain'=>array('title'=>'来路域名','value'=>$domain),
		'url'=>array('title'=>'查看链接','value'=>$url)
	));

	//给关联人员发送微信模板消息
	foreach($openids['shop']['leda'] as $item){
		if($item['status']==1){
			$tousers = $item['openid'];
			$remark = '下单人：'.$order['name'].' ; 手机：'.$order['phone'];
			$remark .= "\r\n";
			$remark .= "请尽快查看处理;点击可查看订单摘要信息。";
			$postdata = array(
				'first'=>array('color'=>'#f00','value'=>$item['name'].'，您收到一条订单信息'),
				'keyword1'=>array('color'=>'#0095f6','value'=>'嗨骑商城订单推送通知'),
				'keyword2'=>array('color'=>'#0095f6','value'=>'新增订单'.$sn),
				'remark'=>array('color'=>'#0095f6','value'=>$remark)
			);
			$result = fmFunc_msg_sendTplNotice($tousers, $wxmsg_template_id, $postdata, $url, $platid = 1, $WeAccount = NULL);
		}
	}

	//累计一次通知次数
	pdo_update($table,array('wx_tpl_times'=>$local_order['wx_tpl_times']+1),array('id'=>$id));
	$result = array('code'=>0,'msg'=>'消息发送完毕');
	exit(json_encode($result));
}

if($op=='tour'){
	//骑游推送
	$temp_order = $_GPC['order'];
	if($_GET['debug']==1){
		$temp_order = '{"code":200,"msg":"骑游订单查询成功","data":{"tname":"周末槟榔花开邀你游","tdesc":"周末槟榔花开邀你游 槟榔河是距离三亚 三亚美丽的槟榔河旅游风景区内，隐居黎族槟榔园内，是三亚少有的高品骑行主环境，四周环水、素雅别致、花香草绿，原汁原味的田园黎族风情融合了，清新古朴园林式自然景观，还有一座黎族文化博物馆，骑游过程还能参观黎苗文化的展示","tdays":1,"tnights":0,"order_no":"20171023185504LCa9","tdate":"2017-10-25","adultprice":88,"adultnum":2,"childprice":50,"childnum":1,"roomprice":0,"roomnum":0,"pay":"0.01","total":"466","extraind":true,"extralist":[{"title":"百变围兜","fee":35,"num":2},{"title":"防晒袖套","fee":35,"num":1},{"title":"防晒腿套","fee":35,"num":3}],"insuind":true,"insulist":[{"title":"大地保险","fee":10,"checked":1}],"adultnamearr":["陈秀明","夏京"],"adultidarr":["421182198208082325","421182198302040231"],"childnamearr":["夏天"],"childidarr":["440000201211299944"]}}';	//测试订单数据
	}
	$_order = json_decode($temp_order, JSON_UNESCAPED_UNICODE);
	$order = $_order['data'];

	if(!$order){
	    $result = array('code'=>1,'msg'=>'未传送有效订单信息');
	    exit(json_encode($result));
	}
	$sn = $order['order_no'];
	$table = 'fm453_api_hiride_tour_order';
	$sql = "SELECT * FROM ".tablename($table)." WHERE `order_no` =:order_no";
	$local_order = pdo_fetch($sql,array(':order_no'=>$sn));
	if(!$local_order){
		$local_order = array('order_no'=>$sn,'_ori_content'=>$temp_order,'createtime'=>$_W['timestamp'],'wx_tpl_times'=>0);
		pdo_insert($table,$local_order);
		$id = pdo_insertid();
	}else{
		$id = $local_order['id'];
	}
	$expire = $_W['timestamp']+3600*12;
	$url = fm_murl($do,$ac,'index',array('state'=>'tour','id'=>$id,'code'=>md5('fm453_'.$expire),'expire'=>$expire));

	//给fm453发个微信消息
	$result = fmMod_notice('oD7Cmsy_2Rq2jV_fysgvaxMsJndo',array(
		'header'=>array('title'=>'事件通知','value'=>'嗨骑定制接口骑游订单推送'),
		'ip'=>array('title'=>'来路IP','value'=>$ip),
		'domain'=>array('title'=>'来路域名','value'=>$domain),
		'url'=>array('title'=>'查看链接','value'=>$url)
	));

	//给关联人员发送微信模板消息
	foreach($openids['tour']['leda'] as $item){
		if($item['status']==1){
			$tousers = $item['openid'];
			$remark = '线路：'.$order['tname'].' ;';
			$remark .= "\r\n";
			$remark .= "请尽快查看处理;点击可查看订单摘要信息。";
			$postdata = array(
				'first'=>array('color'=>'#f00','value'=>$item['name'].'，您收到一条订单信息'),
				'keyword1'=>array('color'=>'#0095f6','value'=>'嗨骑骑游订单推送通知'),
				'keyword2'=>array('color'=>'#0095f6','value'=>'新增订单'.$sn),
				'remark'=>array('color'=>'#0095f6','value'=>$remark)
			);
			$result = fmFunc_msg_sendTplNotice($tousers, $wxmsg_template_id, $postdata, $url, $platid = 1, $WeAccount = NULL);
		}
	}

	//累计一次通知次数
	pdo_update($table,array('wx_tpl_times'=>$local_order['wx_tpl_times']+1),array('id'=>$id));
	$result = array('code'=>0,'msg'=>'消息发送完毕');
	exit(json_encode($result));
}