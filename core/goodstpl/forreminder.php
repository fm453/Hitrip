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
 * 说明：产品模型关联表单插件-订单提醒；
 */

if(empty($goodstpl)) {
	$goodstpl="default";
}

$tips="关联信息:";

if($goodstpl=='hotel') {
		$indate = date('Y-m-d H:i:s',$goodstplinfos[$goodstpl."indate"]);
			if(empty($indate)){
				$tips="\r\n"."入住日期：未填写";
			}else{
				$tips="\r\n"."入住日期：".$indate;
			}
		$outdate = date('Y-m-d H:i:s',$goodstplinfos[$goodstpl."outdate"]);
			if(empty($outdate)){
				$tips.="\r\n"."离店日期：未填写";
			}else{
				$tips.="\r\n"."离店日期：".$outdate;
			}
		$adults = $goodstplinfos[$goodstpl."adults"];
		$kids = $goodstplinfos[$goodstpl."kids"];
		$peoples=$adults+$kids;
			if(empty($adults) || empty($kids)){
				$tips.="\r\n"."入住人数：未填写";
			}else{
				$tips.="；\r\n"."入住人数：".$peoples."人，其中：\r\n"."成人：".$adults."个，儿童：".$kids."名。";
			}

}elseif($goodstpl=='jiesong') {
		$time = date('Y-m-d H:i:s',$goodstplinfos[$goodstpl."time"]);
			if(empty($time)){
				$tips="\r\n"."预计出发时间：未填写";
			}else{
				$tips="\r\n"."预计出发时间：".$time;
			}

		$startarea = $goodstplinfos[$goodstpl."startarea"];
			if(empty($startarea)){
				$tips.="\r\n"."出发地：未填写";
			}else{
				$tips.="\r\n"."出发地：".$startarea;
			}
		$aimarea = $goodstplinfos[$goodstpl."aimarea"];
			if(empty($aimarea)){
				$tips.="\r\n"."目的地：未填写";
			}else{
				$tips.="\r\n"."目的地：".$aimarea;
			}
		$transno = $goodstplinfos[$goodstpl."transno"];
			if(empty($transno)){
				$tips.="\r\n"."航班号/动车车次：未填写";
			}else{
			$tips.="\r\n"."航班号/动车车次：".$transno;
			}
		$peoples = $goodstplinfos[$goodstpl."peoples"];
			if(empty($peoples)){
				$tips.="\r\n"."总人数：未填写";
			}else{
			$tips.="\r\n"."总人数：".$peoples;
			}
		$luggage = $goodstplinfos[$goodstpl."luggage"];
			if(empty($luggage)){
				$tips.="\r\n"."未填写";
			}else{
			$tips.="\r\n"."行李情况的备注：".$luggage;
			}

}elseif($goodstpl=='needs') {
		$type = $goodstplinfos[$goodstpl."type"];
			if(empty($type)){
				$tips="\r\n"."需求类型：未填写";
			}else{
				$tips="\r\n"."需求类型：".$type;
			}
		$starttime = date('Y-m-d H:i:s',$goodstplinfos[$goodstpl."starttime"]);
			if(empty($starttime)){
				$tips.="\r\n"."计划开始时间：未填写";
			}else{
				$tips.="\r\n"."计划开始时间：".$starttime;
			}
		$endtime = date('Y-m-d H:i:s',$goodstplinfos[$goodstpl."endtime"]);
			if(empty($endtime)){
				$tips.="\r\n"."计划结束时间：未填写";
			}else{
				$tips.="\r\n"."计划结束时间：".$endtime;
			}
		$startarea = $goodstplinfos[$goodstpl."startarea"];
			if(empty($startarea)){
				$tips.="\r\n"."未填写";
			}else{
				$tips.="\r\n"."目标地点：".$startarea;
			}
		$aboutneed=$value["remark"];
			if(empty($aboutneed)){
				$tips.="\r\n"."未填写";
			}else{
				$tips.="\r\n"."需求说明：".$aboutneed;
			}
}elseif($goodstpl=='onedaytrip') {
		$time = date('Y-m-d H:i:s',$goodstplinfos[$goodstpl."time"]);
			if(empty($time)){
				$tips="\r\n"."计划游玩日期：未填写";
			}else{
				$tips="\r\n"."计划游玩日期：".$time;
			}
		$startarea = $goodstplinfos[$goodstpl."startarea"];
			if(empty($startarea)){
				$tips.="\r\n"."出发位置：未填写";
			}else{
				$tips.="\r\n"."出发位置：".$startarea;
			}
		$adults = $goodstplinfos[$goodstpl."adults"];
		$kids = $goodstplinfos[$goodstpl."kids"];
		$peoples=$adults+$kids;
			if(empty($adults) || empty($kids)){
				$tips.="\r\n"."计划出行人数：未填写";
			}else{
				$tips.="；\r\n"."计划出行人数：".$peoples."人，其中：\r\n"."成人：".$adults."个，儿童：".$kids."名。";
			}

}elseif($goodstpl=='package_hx') {
		$indate = date('Y-m-d H:i:s',$goodstplinfos[$goodstpl."indate"]);
			if(empty($indate)){
				$tips="\r\n"."入住日期：未填写";
			}else{
				$tips="\r\n"."入住日期：".$indate;
			}
		$adults = $goodstplinfos[$goodstpl."adults"];
		$kids = $goodstplinfos[$goodstpl."kids"];
		$peoples=$adults+$kids;
			if(empty($adults) || empty($kids)){
				$tips.="\r\n"."入住人数：未填写";
			}else{
				$tips.="；\r\n"."入住人数：".$peoples."人，其中：\r\n"."成人：".$adults."个，儿童：".$kids."名。";
			}

}elseif($goodstpl=='tickets') {
		$time = date('Y-m-d H:i:s',$goodstplinfos[$goodstpl."time"]);
			if(empty($time)){
				$tips="\r\n"."计划游玩日期：未填写";
			}else{
				$tips="\r\n"."计划游玩日期：".$time;
			}
		$adults = $goodstplinfos[$goodstpl."adults"];
		$kids = $goodstplinfos[$goodstpl."kids"];
		$peoples=$adults+$kids;
		if(empty($adults) || empty($kids)){
				$tips.="\r\n"."游玩人数：未填写";
			}else{
				$tips.="；\r\n"."游玩人数：".$peoples."人，其中：\r\n"."成人：".$adults."个，儿童：".$kids."名。";
			}

}elseif($goodstpl=='fruit') {
		$aimareatype = $goodstplinfos[$goodstpl."aimareatype"];
			if(empty($aimareatype)){
				$tips="\r\n"."发往哪里：未填写";
			}else{
				$tips="\r\n"."发往哪里：".$aimareatype;
			}
		$incityarea = $goodstplinfos[$goodstpl."incityarea"];
			if(empty($incityarea)){
				$tips="\r\n"."市内配送区域：未填写";
			}else{
				$tips="\r\n"."市内配送区域：".$incityarea;
			}
		$starttime = date('Y-m-d',$goodstplinfos[$goodstpl."starttime"]);
			if(empty($starttime)){
				$tips.="\r\n"."意向配送时间：未填写";
			}else{
				$tips.="\r\n"."意向配送时间：".$starttime;
			}
		$aboutsend = $goodstplinfos[$goodstpl."aboutsend"];
			if(empty($aboutsend)){
				$aboutsend=$defaultaddress['province'].$defaultaddress['city'].$defaultaddress['district'].$defaultaddress['address'];//未填写时，使用默认联系人地址
				$tips.="\r\n"."送货地址：".$defaultaddress['province'].$defaultaddress['city'].$defaultaddress['district'].$defaultaddress['address'];
			}else{
				$tips.="\r\n"."送货地址：".$aboutsend;
			}
		$aboutfruit=$value["remark"];
			if(empty($aboutfruit)){
				$tips.="\r\n"."需求说明：未填写";
			}else{
				$tips.="\r\n"."需求说明：".$aboutfruit;
			}

}elseif($goodstpl=='seafood') {
		$aimareatype = $goodstplinfos[$goodstpl."aimareatype"];
			if(empty($aimareatype)){
				$tips="\r\n"."发往哪里：未填写";
			}else{
				$tips="\r\n"."发往哪里：".$aimareatype;
			}
		$incityarea = $goodstplinfos[$goodstpl."incityarea"];
			if(empty($incityarea)){
				$tips="\r\n"."市内配送区域：未填写";
			}else{
				$tips="\r\n"."市内配送区域：".$incityarea;
			}
		$starttime = date('Y-m-d',$goodstplinfos[$goodstpl."starttime"]);
			if(empty($starttime)){
				$tips.="\r\n"."意向配送时间：未填写";
			}else{
				$tips.="\r\n"."意向配送时间：".$starttime;
			}
		$aboutsend = $goodstplinfos[$goodstpl."aboutsend"];
			if(empty($aboutsend)){
				$aboutsend=$defaultaddress['province'].$defaultaddress['city'].$defaultaddress['district'].$defaultaddress['address'];//未填写时，使用默认联系人地址
				$tips.="\r\n"."送货地址：".$defaultaddress['province'].$defaultaddress['city'].$defaultaddress['district'].$defaultaddress['address'];
			}else{
				$tips.="\r\n"."送货地址：".$aboutsend;
			}
		$aboutseafood=$value["remark"];
			if(empty($aboutseafood)){
				$tips.="\r\n"."需求说明：未填写";
			}else{
				$tips.="\r\n"."需求说明：".$aboutseafood;
			}
}elseif($goodstpl=='halffresh') {
		$aimareatype = $goodstplinfos[$goodstpl."aimareatype"];
			if(empty($aimareatype)){
				$tips="\r\n"."发往哪里：未填写";
			}else{
				$tips="\r\n"."发往哪里：".$aimareatype;
			}
		$incityarea = $goodstplinfos[$goodstpl."incityarea"];
			if(empty($incityarea)){
				$tips="\r\n"."市内配送区域：未填写";
			}else{
				$tips="\r\n"."市内配送区域：".$incityarea;
			}
		$starttime = date('Y-m-d',$goodstplinfos[$goodstpl."starttime"]);
			if(empty($starttime)){
				$tips.="\r\n"."意向配送时间：未填写";
			}else{
				$tips.="\r\n"."意向配送时间：".$starttime;
			}
		$aboutsend = $goodstplinfos[$goodstpl."aboutsend"];
			if(empty($aboutsend)){
				$aboutsend=$defaultaddress['province'].$defaultaddress['city'].$defaultaddress['district'].$defaultaddress['address'];//未填写时，使用默认联系人地址
				$tips.="\r\n"."送货地址：".$defaultaddress['province'].$defaultaddress['city'].$defaultaddress['district'].$defaultaddress['address'];
			}else{
				$tips.="\r\n"."送货地址：".$aboutsend;
			}
		$aboutfruit=$value["remark"];
			if(empty($aboutfruit)){
				$tips.="\r\n"."需求说明：未填写";
			}else{
				$tips.="\r\n"."需求说明：".$aboutfruit;
			}

}else{
		$tips=$value["remark"];
}


/*后台订单读取信息时执行反序列化操作 unserialize */
