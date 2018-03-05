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
 * 说明：产品模型关联表单插件；
 */

if($goodstpl=='hotel') {
	$goodstplinfos=array(
		$goodstpl.'indate' => strtotime($_GPC[$goodstpl.'indate']),
		$goodstpl.'outdate' => strtotime($_GPC[$goodstpl.'outdate']),
		$goodstpl.'adults' => intval($_GPC[$goodstpl.'adults']),
		$goodstpl.'kids' => intval($_GPC[$goodstpl.'kids'])
	);
}elseif($goodstpl=='jiesong') {
	$goodstplinfos=array(
		$goodstpl.'time' => strtotime($_GPC[$goodstpl.'time']),
		$goodstpl.'startarea' =>$_GPC[$goodstpl.'startarea'],
		$goodstpl.'aimarea' =>$_GPC[$goodstpl.'aimarea'],
		$goodstpl.'transno' =>$_GPC[$goodstpl.'transno'],
		$goodstpl.'peoples' =>$_GPC[$goodstpl.'peoples'],
		$goodstpl.'luggage' =>$_GPC[$goodstpl.'luggage']
	);
}elseif($goodstpl=='needs') {
		$goodstplinfos=array(
		$goodstpl.'type' =>$_GPC[$goodstpl.'type'],
		$goodstpl.'starttime' =>strtotime($_GPC[$goodstpl.'starttime']),
		$goodstpl.'endtime' =>strtotime($_GPC[$goodstpl.'endtime']),
		$goodstpl.'startarea' =>$_GPC[$goodstpl.'startarea']
	);
}elseif($goodstpl=='onedaytrip') {
		$goodstplinfos=array(
			$goodstpl.'time' =>strtotime($_GPC[$goodstpl.'time']),
			$goodstpl.'adults' => intval($_GPC[$goodstpl.'adults']),
			$goodstpl.'kids' => intval($_GPC[$goodstpl.'kids']),
			$goodstpl.'startarea' =>$_GPC[$goodstpl.'startarea']
	);
}elseif($goodstpl=='package_hx') {
		$goodstplinfos=array(
			$goodstpl.'indate' =>strtotime($_GPC[$goodstpl.'indate']),
			$goodstpl.'adults' => intval($_GPC[$goodstpl.'adults']),
			$goodstpl.'kids' => intval($_GPC[$goodstpl.'kids'])
	);
}elseif($goodstpl=='tickets') {
		$goodstplinfos=array(
			$goodstpl.'time' =>strtotime($_GPC[$goodstpl.'time']),
			$goodstpl.'adults' => intval($_GPC[$goodstpl.'adults']),
			$goodstpl.'kids' => intval($_GPC[$goodstpl.'kids'])
	);
}elseif($goodstpl=='fruit') {
		$goodstplinfos=array(
			$goodstpl.'aimareatype' =>$_GPC[$goodstpl.'aimareatype'],
			$goodstpl.'incityarea' =>$_GPC[$goodstpl.'incityarea'],
			$goodstpl.'starttime' =>strtotime($_GPC[$goodstpl.'starttime']),
			$goodstpl.'aboutsend' =>$_GPC[$goodstpl.'aboutsend']
	);
}elseif($goodstpl=='seafood') {
		$goodstplinfos=array(
			$goodstpl.'aimareatype' =>$_GPC[$goodstpl.'aimareatype'],
			$goodstpl.'incityarea' =>$_GPC[$goodstpl.'incityarea'],
			$goodstpl.'starttime' =>strtotime($_GPC[$goodstpl.'starttime']),
			$goodstpl.'aboutsend' =>$_GPC[$goodstpl.'aboutsend']
	);
}elseif($goodstpl=='halffresh') {
		$goodstplinfos=array(
			$goodstpl.'aimareatype' =>$_GPC[$goodstpl.'aimareatype'],
			$goodstpl.'incityarea' =>$_GPC[$goodstpl.'incityarea'],
			$goodstpl.'starttime' =>strtotime($_GPC[$goodstpl.'starttime']),
			$goodstpl.'aboutsend' =>$_GPC[$goodstpl.'aboutsend']
	);
}else{
	$goodstplinfos=array(

	);
}
$goodstplinfos=serialize($goodstplinfos);
/*将数组序列化以便存入数据库,后台订单读取信息时要执行反序列化操作 */
