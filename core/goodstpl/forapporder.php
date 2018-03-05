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
 * 说明：产品模型关联表单插件-手机端订单查阅关联；
 */

if(empty($goodstpl)) {
	$goodstpl="default";
}

if($goodstpl=='hotel') {
		$indate = date('Y-m-d H:i:s',$goodstplinfos[$goodstpl."indate"]);
			if(empty($goodstplinfos[$goodstpl."indate"])){
				$tips="入住日期：您还没有告诉我们";
			}else{
				$tips="入住日期：".$indate;
			}
		$outdate = date('Y-m-d H:i:s',$goodstplinfos[$goodstpl."outdate"]);
			if(empty($goodstplinfos[$goodstpl."outdate"])){
				$tips.="<br>离店日期：您还没有告诉我们";
			}else{
				$tips.="<br>离店日期：".$outdate;
			}
		$adults = $goodstplinfos[$goodstpl."adults"];
		$kids = $goodstplinfos[$goodstpl."kids"];
		$peoples=$adults+$kids;
			if(empty($adults) || empty($kids)){
				$tips.="<br>入住人数：您还没有告诉我们成人或者儿童数";
			}else{
				$tips.="；<br>入住人数：".$peoples."人，其中：<br>成人：".$adults."个，儿童：".$kids."名。";
			}

}elseif($goodstpl=='jiesong') {
		$time = date('Y-m-d H:i:s',$goodstplinfos[$goodstpl."time"]);
			if(empty($goodstplinfos[$goodstpl."time"])){
				$tips="预计出发时间：您还没有告诉我们";
			}else{
				$tips="预计出发时间：".$time;
			}
			$tips.="（时间不对？请联系客服或重新下单哦）";
		$startarea = $goodstplinfos[$goodstpl."startarea"];
			if(empty($startarea)){
				$tips.="<br>出发地：您还没有告诉我们";
			}else{
				$tips.="<br>出发地：".$startarea;
			}
		$aimarea = $goodstplinfos[$goodstpl."aimarea"];
			if(empty($aimarea)){
				$tips.="<br>目的地：您还没有告诉我们";
			}else{
				$tips.="<br>目的地：".$aimarea;
			}
		$transno = $goodstplinfos[$goodstpl."transno"];
			if(empty($transno)){
				$tips.="<br>航班号/动车车次：您还没有告诉我们";
			}else{
			$tips.="<br>航班号/动车车次：".$transno;
			}
		$peoples = $goodstplinfos[$goodstpl."peoples"];
			if(empty($peoples)){
				$tips.="<br>总人数：亲，您一行共有多少人呢？请告诉我们，方便为您安排呢";
			}else{
			$tips.="<br>总人数：".$peoples;
			}
		$luggage = $goodstplinfos[$goodstpl."luggage"];
			if(empty($luggage)){
				$tips.="<br>亲，如果您们的行李比较多或者有其他特殊要注意的，请告知我们以便为您合理安排哟";
			}else{
			$tips.="<br>行李情况的备注：".$luggage;
			}

}elseif($goodstpl=='needs') {
		$type = $goodstplinfos[$goodstpl."type"];
			if(empty($type)){
				$tips="需求类型：您没有选择需求类型";
			}else{
				$tips="需求类型：".$type;
			}
		$starttime = date('Y-m-d H:i:s',$goodstplinfos[$goodstpl."starttime"]);
			if(empty($goodstplinfos[$goodstpl."starttime"])){
				$tips.="<br>计划开始时间：您还没有告诉我们";
			}else{
				$tips.="<br>计划开始时间：".$starttime;
			}
		$endtime = date('Y-m-d H:i:s',$goodstplinfos[$goodstpl."endtime"]);
			if(empty($goodstplinfos[$goodstpl."endtime"])){
				$tips.="<br>计划结束时间：您还没有告诉我们";
			}else{
				$tips.="<br>计划结束时间：".$endtime;
			}
		$startarea = $goodstplinfos[$goodstpl."startarea"];
			if(empty($startarea)){
				$tips.="<br>目标地点：您还没有告知需要我们去哪帮您哦";
			}else{
				$tips.="<br>目标地点：".$startarea;
			}
		$aboutneed=$value["remark"];
			if(empty($aboutneed)){
				$tips.="<br>需求说明：您还没有告诉我们；您在下单时将需求说明填写在订单备注里我们就可以知道了";
			}else{
				$tips.="<br>需求说明：".$aboutneed;
			}
}elseif($goodstpl=='onedaytrip') {
		$time = date('Y-m-d H:i:s',$goodstplinfos[$goodstpl."time"]);
			if(empty($goodstplinfos[$goodstpl."time"])){
				$tips="计划游玩日期：您还没有告诉我们";
			}else{
				$tips="计划游玩日期：".$time;
			}
			$tips.="（时间不对？请联系客服或重新下单哦）";
		$startarea = $goodstplinfos[$goodstpl."startarea"];
			if(empty($startarea)){
				$tips.="<br>出发位置：您还没有告诉我们";
			}else{
				$tips.="<br>出发位置：".$startarea;
			}
		$adults = $goodstplinfos[$goodstpl."adults"];
		$kids = $goodstplinfos[$goodstpl."kids"];
		$peoples=$adults+$kids;
			if(empty($adults) || empty($kids)){
				$tips.="<br>计划出行人数：您还没有告诉我们成人或者儿童数";
			}else{
				$tips.="；<br>计划出行人数：".$peoples."人，其中：<br>成人：".$adults."个，儿童：".$kids."名。";
			}

}elseif($goodstpl=='package_hx') {
		$indate = date('Y-m-d H:i:s',$goodstplinfos[$goodstpl."indate"]);
			if(empty($goodstplinfos[$goodstpl."indate"])){
				$tips="入住日期：您还没有告诉我们";
			}else{
				$tips="入住日期：".$indate;
			}
		$adults = $goodstplinfos[$goodstpl."adults"];
		$kids = $goodstplinfos[$goodstpl."kids"];
		$peoples=$adults+$kids;
			if(empty($adults) || empty($kids)){
				$tips.="<br>入住人数：您还没有告诉我们成人或者儿童数";
			}else{
				$tips.="；<br>入住人数：".$peoples."人，其中：<br>成人：".$adults."个，儿童：".$kids."名。";
			}

}elseif($goodstpl=='tickets') {
		$time = date('Y-m-d H:i:s',$goodstplinfos[$goodstpl."time"]);
			if(empty($goodstplinfos[$goodstpl."starttime"])){
				$tips="计划游玩日期：您还没有告诉我们";
			}else{
				$tips="计划游玩日期：".$time;
			}
			$tips.="（时间不对？请联系客服或重新下单哦）";
		$adults = $goodstplinfos[$goodstpl."adults"];
		$kids = $goodstplinfos[$goodstpl."kids"];
		$peoples=$adults+$kids;
		if(empty($adults) || empty($kids)){
				$tips.="<br>游玩人数：您还没有告诉我们成人或者儿童数";
			}else{
				$tips.="；<br>游玩人数：".$peoples."人，其中：<br>成人：".$adults."个，儿童：".$kids."名。";
			}

}elseif($goodstpl=='fruit') {
		$aimareatype = $goodstplinfos[$goodstpl."aimareatype"];
			if(empty($aimareatype)){
				$tips="发往哪里：您没有选择发送区域";
			}else{
				$tips="发往哪里：".$aimareatype;
			}
		$incityarea = $goodstplinfos[$goodstpl."incityarea"];
			if(empty($incityarea)){
				$tips="市内配送区域：您没有选择发送区域";
			}else{
				$tips="市内配送区域：".$incityarea;
			}
		$starttime = date('Y-m-d',$goodstplinfos[$goodstpl."starttime"]);
			if(empty($goodstplinfos[$goodstpl."starttime"])){
				$tips.="<br>意向配送时间：您还没有告诉我们";
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
		$aboutfruit=$value["remark"];
			if(empty($aboutfruit)){
				$tips.="<br>需求说明：您还没有告诉我们；您在下单时将需求说明填写在订单备注里我们就可以知道了";
			}else{
				$tips.="<br>需求说明：".$aboutfruit;
			}

}elseif($goodstpl=='seafood') {
		$aimareatype = $goodstplinfos[$goodstpl."aimareatype"];
			if(empty($aimareatype)){
				$tips="发往哪里：您没有选择发送区域";
			}else{
				$tips="发往哪里：".$aimareatype;
			}
		$incityarea = $goodstplinfos[$goodstpl."incityarea"];
			if(empty($incityarea)){
				$tips="市内配送区域：您没有选择发送区域";
			}else{
				$tips="市内配送区域：".$incityarea;
			}
		$starttime = date('Y-m-d',$goodstplinfos[$goodstpl."starttime"]);
			if(empty($goodstplinfos[$goodstpl."starttime"])){
				$tips.="<br>意向配送时间：您还没有告诉我们";
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
				$tips.="<br>需求说明：您还没有告诉我们；您在下单时将需求说明填写在订单备注里我们就可以知道了";
			}else{
				$tips.="<br>需求说明：".$aboutseafood;
			}

}elseif($goodstpl=='halffresh') {
		$aimareatype = $goodstplinfos[$goodstpl."aimareatype"];
			if(empty($aimareatype)){
				$tips="发往哪里：您没有选择发送区域";
			}else{
				$tips="发往哪里：".$aimareatype;
			}
		$incityarea = $goodstplinfos[$goodstpl."incityarea"];
			if(empty($incityarea)){
				$tips="市内配送区域：您没有选择发送区域";
			}else{
				$tips="市内配送区域：".$incityarea;
			}
		$starttime = date('Y-m-d',$goodstplinfos[$goodstpl."starttime"]);
			if(empty($goodstplinfos[$goodstpl."starttime"])){
				$tips.="<br>意向配送时间：您还没有告诉我们";
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
		$aboutfruit=$value["remark"];
			if(empty($aboutfruit)){
				$tips.="<br>需求说明：您还没有告诉我们；您在下单时将需求说明填写在订单备注里我们就可以知道了";
			}else{
				$tips.="<br>需求说明：".$aboutfruit;
			}

}else{
		$tips=$value["remark"];
}


/*后台订单读取信息时执行反序列化操作 unserialize */
