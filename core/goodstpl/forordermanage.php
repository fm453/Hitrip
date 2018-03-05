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
 * 说明：产品模型关联表单插件-订单管理员查阅时调用；
 */

if(empty($goodstpl)) {
	$goodstpl="default";
}

if($goodstpl=='hotel') {
		$indate = date('Y-m-d H:i:s',$goodstplinfos[$goodstpl."indate"]);
			if(empty($indate)){
				$tips="入住日期：顾客未填写";
			}else{
				$tips="入住日期：".$indate;
			}
		$outdate = date('Y-m-d H:i:s',$goodstplinfos[$goodstpl."outdate"]);
			if(empty($outdate)){
				$tips.="<br>离店日期：顾客未填写";
			}else{
				$tips.="<br>离店日期：".$outdate;
			}
		$adults = $goodstplinfos[$goodstpl."adults"];
		$kids = $goodstplinfos[$goodstpl."kids"];
		$peoples=$adults+$kids;
			if(empty($adults) || empty($kids)){
				$tips.="<br>入住人数：顾客未填写";
			}else{
				$tips.="；<br>入住人数：".$peoples."人，其中：<br>成人：".$adults."个，儿童：".$kids."名。";
			}

}elseif($goodstpl=='jiesong') {
		$time = date('Y-m-d H:i:s',$goodstplinfos[$goodstpl."time"]);
			if(empty($time)){
				$tips="预计出发时间：顾客未填写";
			}else{
				$tips="预计出发时间：".$time;
			}
		$startarea = $goodstplinfos[$goodstpl."startarea"];
			if(empty($startarea)){
				$tips.="<br>出发地：顾客未填写";
			}else{
				$tips.="<br>出发地：".$startarea;
			}
		$aimarea = $goodstplinfos[$goodstpl."aimarea"];
			if(empty($aimarea)){
				$tips.="<br>目的地：顾客未填写";
			}else{
				$tips.="<br>目的地：".$aimarea;
			}
		$transno = $goodstplinfos[$goodstpl."transno"];
			if(empty($transno)){
				$tips.="<br>航班号/动车车次：顾客未填写";
			}else{
			$tips.="<br>航班号/动车车次：".$transno;
			}
		$peoples = $goodstplinfos[$goodstpl."peoples"];
			if(empty($peoples)){
				$tips.="<br>总人数：顾客未填写";
			}else{
			$tips.="<br>总人数：".$peoples;
			}
		$luggage = $goodstplinfos[$goodstpl."luggage"];
			if(empty($luggage)){
				$tips.="<br>顾客未填写";
			}else{
			$tips.="<br>行李情况的备注：".$luggage;
			}

}elseif($goodstpl=='needs') {
		$type = $goodstplinfos[$goodstpl."type"];
			if(empty($type)){
				$tips="需求类型：顾客未选择";
			}else{
				$tips="需求类型：".$type;
			}
		$starttime = date('Y-m-d H:i:s',$goodstplinfos[$goodstpl."starttime"]);
			if(empty($starttime)){
				$tips.="<br>计划开始时间：顾客未填写";
			}else{
				$tips.="<br>计划开始时间：".$starttime;
			}
		$endtime = date('Y-m-d H:i:s',$goodstplinfos[$goodstpl."endtime"]);
			if(empty($endtime)){
				$tips.="<br>计划结束时间：顾客未填写";
			}else{
				$tips.="<br>计划结束时间：".$endtime;
			}
		$startarea = $goodstplinfos[$goodstpl."startarea"];
			if(empty($startarea)){
				$tips.="<br>目标地点：顾客未填写";
			}else{
				$tips.="<br>目标地点：".$startarea;
			}
		$aboutneed=$value["remark"];
			if(empty($aboutneed)){
				$tips.="<br>需求说明：顾客未填写";
			}else{
				$tips.="<br>需求说明：".$aboutneed;
			}
}elseif($goodstpl=='onedaytrip') {
		$time = date('Y-m-d H:i:s',$goodstplinfos[$goodstpl."time"]);
			if(empty($time)){
				$tips="计划游玩日期：顾客未填写";
			}else{
				$tips="计划游玩日期：".$time;
			}
		$startarea = $goodstplinfos[$goodstpl."startarea"];
			if(empty($startarea)){
				$tips.="<br>出发位置：顾客未填写";
			}else{
				$tips.="<br>出发位置：".$startarea;
			}
		$adults = $goodstplinfos[$goodstpl."adults"];
		$kids = $goodstplinfos[$goodstpl."kids"];
		$peoples=$adults+$kids;
			if(empty($adults) || empty($kids)){
				$tips.="<br>计划出行人数：顾客未填写";
			}else{
				$tips.="；<br>计划出行人数：".$peoples."人，其中：<br>成人：".$adults."个，儿童：".$kids."名。";
			}

}elseif($goodstpl=='package_hx') {
		$indate = date('Y-m-d H:i:s',$goodstplinfos[$goodstpl."indate"]);
			if(empty($indate)){
				$tips="入住日期：顾客未填写";
			}else{
				$tips="入住日期：".$indate;
			}
		$adults = $goodstplinfos[$goodstpl."adults"];
		$kids = $goodstplinfos[$goodstpl."kids"];
		$peoples=$adults+$kids;
			if(empty($adults) || empty($kids)){
				$tips.="<br>入住人数：顾客未填写";
			}else{
				$tips.="；<br>入住人数：".$peoples."人，其中：<br>成人：".$adults."个，儿童：".$kids."名。";
			}

}elseif($goodstpl=='tickets') {
		$time = date('Y-m-d H:i:s',$goodstplinfos[$goodstpl."time"]);
			if(empty($time)){
				$tips="计划游玩日期：您还没有告诉我们";
			}else{
				$tips="计划游玩日期：".$time;
			}
		$adults = $goodstplinfos[$goodstpl."adults"];
		$kids = $goodstplinfos[$goodstpl."kids"];
		$peoples=$adults+$kids;
		if(empty($adults) || empty($kids)){
				$tips.="<br>游玩人数：顾客未填写";
			}else{
				$tips.="；<br>游玩人数：".$peoples."人，其中：<br>成人：".$adults."个，儿童：".$kids."名。";
			}

}elseif($goodstpl=='fruit') {
		$aimareatype = $goodstplinfos[$goodstpl."aimareatype"];
			if(empty($aimareatype)){
				$aimareatype ="客户未选择";
				$tips="发往哪里：客户未选择";
			}else{
				$tips="发往哪里：".$aimareatype;
			}
		$incityarea = $goodstplinfos[$goodstpl."incityarea"];
			if(empty($incityarea)){
				$tips="市内配送区域：客户未选择";
			}else{
				$tips="市内配送区域：".$incityarea;
			}
		$starttime = date('Y-m-d',$goodstplinfos[$goodstpl."starttime"]);
			if($goodstplinfos[$goodstpl."starttime"]==0){
				$starttime ="未填写";
				$tips.="<br>意向配送时间：客户未填写";
			}else{
				$tips.="<br>意向配送时间：".$starttime;
			}
		$aboutsend = $goodstplinfos[$goodstpl."aboutsend"];
			if(empty($aboutsend)){
				$aboutsend = $defaultaddress['province'].$defaultaddress['city'].$defaultaddress['district'].$defaultaddress['address'];
				$tips.="<br>送货地址，客户未选择；使用客户的默认收货地址：".$defaultaddress['province'].$defaultaddress['city'].$defaultaddress['district'].$defaultaddress['address'];
			}else{
				$tips.="<br>送货地址：".$aboutsend;
			}
		$aboutfruit=$value["remark"];
			if(empty($aboutfruit)){
				$tips.="<br>需求说明：客户无备注";
			}else{
				$tips.="<br>需求说明：".$aboutfruit;
			}

}elseif($goodstpl=='seafood') {
		$aimareatype = $goodstplinfos[$goodstpl."aimareatype"];
			if(empty($aimareatype)){
				$tips="发往哪里：顾客未选择";
			}else{
				$tips="发往哪里：".$aimareatype;
			}
		$incityarea = $goodstplinfos[$goodstpl."incityarea"];
			if(empty($incityarea)){
				$tips="市内配送区域：顾客未选择";
			}else{
				$tips="市内配送区域：".$incityarea;
			}
		$starttime = date('Y-m-d',$goodstplinfos[$goodstpl."starttime"]);
			if(empty($starttime)){
				$tips.="<br>意向配送时间：顾客未填写";
			}else{
				$tips.="<br>意向配送时间：".$starttime;
			}
		$aboutsend = $goodstplinfos[$goodstpl."aboutsend"];
			if(empty($aboutsend)){
				$aboutsend=$defaultaddress['province'].$defaultaddress['city'].$defaultaddress['district'].$defaultaddress['address'];//未填写时，使用默认联系人地址
				$tips.="<br>送货地址：".$defaultaddress['province'].$defaultaddress['city'].$defaultaddress['district'].$defaultaddress['address'];
			}else{
				$tips.="<br>送货地址：".$aboutsend;
			}
		$aboutseafood=$value["remark"];
			if(empty($aboutseafood)){
				$tips.="<br>需求说明：顾客未填写";
			}else{
				$tips.="<br>需求说明：".$aboutseafood;
			}

}elseif($goodstpl=='halffresh') {
		$aimareatype = $goodstplinfos[$goodstpl."aimareatype"];
			if(empty($aimareatype)){
				$aimareatype ="客户未选择";
				$tips="发往哪里：客户未选择";
			}else{
				$tips="发往哪里：".$aimareatype;
			}
		$incityarea = $goodstplinfos[$goodstpl."incityarea"];
			if(empty($incityarea)){
				$tips="市内配送区域：客户未选择";
			}else{
				$tips="市内配送区域：".$incityarea;
			}
		$starttime = date('Y-m-d',$goodstplinfos[$goodstpl."starttime"]);
			if($goodstplinfos[$goodstpl."starttime"]==0){
				$starttime ="未填写";
				$tips.="<br>意向配送时间：客户未填写";
			}else{
				$tips.="<br>意向配送时间：".$starttime;
			}
		$aboutsend = $goodstplinfos[$goodstpl."aboutsend"];
			if(empty($aboutsend)){
				$aboutsend = $defaultaddress['province'].$defaultaddress['city'].$defaultaddress['district'].$defaultaddress['address'];
				$tips.="<br>送货地址，客户未选择；使用客户的默认收货地址：".$defaultaddress['province'].$defaultaddress['city'].$defaultaddress['district'].$defaultaddress['address'];
			}else{
				$tips.="<br>送货地址：".$aboutsend;
			}
		$aboutfruit=$value["remark"];
			if(empty($aboutfruit)){
				$tips.="<br>需求说明：客户无备注";
			}else{
				$tips.="<br>需求说明：".$aboutfruit;
			}

}else{
		$tips=$value["remark"];
}


/*后台订单读取信息时执行反序列化操作 unserialize */
