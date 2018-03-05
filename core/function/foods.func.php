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
 * @remark：微餐饮处理函数
 */
defined('IN_IA') or exit('Access Denied');
fm_load()->fm_class('HttpClient');
fm_load()->fm_class('FeiEYun.Print'); //获取飞鹅云打印机类

//打印机
//格式化为供打印的订单格式
function fmFunc_foods_printOrderInfo($shopname='',$order=array(),$foods=array(),$qrcode){
	global $_W,$_GPC,$_FM;
	$orderInfo  = '<CB>'.$shopname.'</CB><BR>';
	$orderInfo .= '--------------------------------<BR>';
	$orderInfo .= '--订餐号：'.$order['ordersn'].'<BR>';
	if($order['username']){
		$orderInfo .= '--用餐人：'.$order['username'].'<BR>';
	}
	$orderInfo .= '联系电话：'.$order['mobile'].'<BR>';
	if($order['time']){
		$orderInfo .= '送餐时间：'.$order['time'].'<BR>';
	}
	if($order['address']){
		$orderInfo .= '送餐地址：'.$order['address'].'<BR>';
	}
	if($order['desknum']){
		$orderInfo .= '就餐桌号：'.$order['desknum'].'<BR>';
	}

	if($order['paytype'] == 1){
		$orderInfo .= '支付方式：在线支付<BR>';
	}else if($order['paytype'] == 2){
		$orderInfo .= '支付方式：餐到付款<BR>';
	}
	if($order['other']){
		$orderInfo .= '----备注：'.$order['other'].'<BR>';
	}
	$orderInfo .= '--------------------------------<BR>';

	$foods_ids = pdo_fetchall("SELECT foodsid, total FROM ".tablename('fm453_vfoods_order_foods')." WHERE orderid =:orderid", array(":orderid"=>$order['id']), 'foodsid');
	foreach ($foods as $row) {
		if($row['preprice']){
			$rowprice = $row['preprice'];
		}else{
			$rowprice = $row['oriprice'];
		}
		$orderInfo .= $row['title'].'　x '.$foods_ids[$row['id']]['total'].$row['unit'].'    '.$foods_ids[$row['id']]['total']*$rowprice.'元<BR>';
	}
	$orderInfo .= '合计：'.$order['price'].'元<BR>';
	if($printrow['qr']){
		$orderInfo .= '----------订单二维码----------';
		$orderInfo .= '<QR>'.$qrcode.'</QR>';
		$orderInfo .= '<BR>';
	}
	return $orderInfo;
}

//暂未用到
function fmFunc_foods_sendSelfFormatOrderInfo($device_no,$key,$times,$orderInfo){
	$selfMessage = array(
		'clientCode'=>$device_no,
		'printInfo'=>$orderInfo,
		'apitype'=>'php',
		'key'=>$key,
	    'printTimes'=>$times
	);
	$instance = new FeiEYunPrint;
	$result = $instance->sendSelfFormatMessage($msgInfo=$selfMessage);
	return $result;
}

//按时间查询打印统计
function fmFunc_foods_queryOrderNumbersByTime($device_no,$date){
	$instance = new FeiEYunPrint;
	$result = $instance->getOrderNumbersByTime($device_no,$date);
	$return['printedNumber'] = $result['msg']['print'];
	$return['waitingNumber'] = $result['msg']['waiting'];
	return $return;
}

//查询打印机状态
function fmFunc_foods_queryPrinterStatus($device_no){
	$instance = new FeiEYunPrint;
	$result = $instance->getStatus($device_no);
	return $result['msg'];
}

//打印订单信息
function fmFunc_foods_orderPrint($device_no,$key,$times,$orderInfo){
	$cache_key = md5($device_no);
	$hasPrinted = cache_load($cache_key);
	if($hasPrinted){
		return $hasPrinted;
	}
	$instance = new FeiEYunPrint;
	$result = $instance->sendFreeMessage($deviceNo=$device_no,$deviceKey=$key,$times,$orderInfo);
	cache_write($cache_key,$$result);
	return $result;
}

//发短信
function fmFunc_foods_sendSMS($uid,$pwd,$mobile,$content,$time='',$mid=''){
	$http = 'http://sms.shwsms.com/httpInterfaceSubmitAction.do';
	$data = array
	(
		'account'=>$uid,     //用户账号
		'password'=>strtolower(md5($pwd)), //MD5位32密码
		'mobile'=>$mobile,    //号码
		'content'=>$content,   //内容
		'time'=>$time,  //定时发送
		'mid'=>$mid      //子扩展号
	);
	$re= postSMS($http,$data);   //POST方式提交
	if($re == '111' )
	{
		return "发送成功!";
	}
	else
	{
		return "发送失败! 状态：".$re;
	}
}

function fmFunc_foods_postSMS($url,$data=''){
	$row = parse_url($url);
	$host = $row['host'];
	$port = $row['port'] ? $row['port']:80;
	$file = $row['path'];
	while (list($k,$v) = each($data))
	{
		$post .= rawurlencode($k)."=".rawurlencode($v)."&"; //转URL标准码
	}
	$post = substr( $post , 0 , -1 );
	$len = strlen($post);
	$fp = @fsockopen( $host ,$port, $errno, $errstr, 10);
	if (!$fp) {
		return "$errstr ($errno)\n";
	} else {
	$receive = '';
	$out = "POST $file HTTP/1.1\r\n";
	$out .= "Host: $host\r\n";
	$out .= "Content-type: application/x-www-form-urlencoded\r\n";
	$out .= "Connection: Close\r\n";
	$out .= "Content-Length: $len\r\n\r\n";
	$out .= $post;
	fwrite($fp, $out);
	while (!feof($fp)) {
		$receive .= fgets($fp, 128);
	}
	fclose($fp);
	$receive = explode("\r\n\r\n",$receive);
	unset($receive[0]);
	return implode("",$receive);
	}
}

//订单模式
/*
@simple 是否返回简版
*/
function fmFunc_foods_dingcantypes($simple=true){
	if($simple){
		return array('waimai','tangshi','take');
	}else{
		$buytypes = array();
		$do = 'vfoods';
		$htmlsrc = MODULE_URL.'template/mobile/default';
		$buytypes['tangshi'] = array('title'=>'堂食区','des'=>'仅在食堂内就餐','css'=>'','url'=>fm_murl($do,'tangshi','index',array()),'icon'=>$htmlsrc.'/'.$do.'/_statics/t1.png');
		$buytypes['take'] = array('title'=>'生菜区','css'=>'','des'=>'买菜在这更方便','url'=>fm_murl($do,'take','index',array()),'icon'=>$htmlsrc.'/'.$do.'/_statics/t2.png');
		$buytypes['waimai'] = array('title'=>'外送区','css'=>'','des'=>'这里的餐食可以外送','url'=>fm_murl($do,'waimai','index',array()),'icon'=>$htmlsrc.'/'.$do.'/_statics/t3.png');
		return $buytypes;
	}
}

//餐厅营业时间判断
//只要有任一时间点被设置，即开始判断
//未设置上午开始时间时，如果设置了上午结束时间且下午时段未设置，则有效；
//或，未设置上午开始时间时，如果仅设置了下午结束时间，则有效；
//上午的结束时间仅在设置了下午起始时间时，才有效
//下午的开始时间仅在设置了上午结束时间且结束时间早于下午开始时间时，才有效
function fmFunc_foods_checkTime($time1,$time2,$time3,$time4){
	$isInTime = true;
	if($time1 || $time2 || $time3 || $time4){
		if($time1){
			$time1 = strtotime($time1);
			if($time1>TIMESTAMP){
				$isInTime = false;	//未开始营业
			}elseif($time2 && $time3 && $time4){
				$time2 = strtotime($time2);
				$time3 = strtotime($time3);
				$time4 = strtotime($time4);
				if($time2<=TIMESTAMP && $time3>TIMESTAMP){
					$isInTime = false;	//第一周期时间已到，第二周期时间未到，暂停营业
				}elseif($time4<=TIMESTAMP){
					$isInTime = false;	//第二周期时间已到，结束营业
				}
			}

		}else{
			if($time2){
				if(!$time3 && !$time4){
					$time2 = strtotime($time2);
					if($time2<=TIMESTAMP){
						$isInTime = false;	//周期时间已到，结束营业
					}
				}elseif($time4){
					$time4 = strtotime($time4);
					if($time4<=TIMESTAMP){
						$isInTime = false;	//周期时间已到，结束营业
					}
				}
			}else{
				if($time3 && $time4){
					$time3 = strtotime($time3);
					$time4 = strtotime($time4);
					if($time3>TIMESTAMP || $time4<=TIMESTAMP){
						$isInTime = false;	//周期开始时间未到、暂停营业，或结束时间已过、结束营业
					}
				}elseif($time4){
					$time4 = strtotime($time4);
					if($time4<=TIMESTAMP){
						$isInTime = false;	//周期时间已到，结束营业
					}
				}
			}
		}
	}
	return $isInTime;
}
