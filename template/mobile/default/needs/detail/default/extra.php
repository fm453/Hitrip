<?php
/*
* 唯一表单(总参与限制为每人1次)需要查找与当前会员匹配的表单提交记录;有则调用之
@ORDER BY id ASC, 取记录中最早的数据
*/

/*
* 初始化一些数据,根据前台模块自行设置
*/
	$starttime = !empty($needs_data['starttime']) ? (strtotime($needs_data['starttime'])) : (TIMESTAMP + 30*60);
	$start_beginyear = 2017;
	$start_endyear = 2018;
	$starttime_data_options = '{"value":"'. date("Y-m-d H:i",$starttime). '","beginYear":"'.$start_beginyear.'","endYear":"'.$start_endyear.'"}';
	$worktime = array(
		'start'=>array('h'=>8,'i'=>30),	//每天开业时间（时、分）
		'end'=>array('h'=>20,'i'=>30),	//每天停业时间（时、分）
	);
    $_temp_key = rand(0,12);
    $_temp_prices = array(0,1.68,2.58,5.2,6.6,8.8,9.9,18,50,88,100,168,200);
    $needs['price']=$_temp_prices[$_temp_key];
/*
* 启动引导页设置
*/
	$isNoGuide= cache_load($do.'_no_guide'.$_W['openid'].'_nid'.$id);
	if(!$isNoGuide) {
		cache_write($do.'_no_guide'.$_W['openid'].'_nid'.$id,TRUE);
		//$url = fm_murl($do,$ac,$operation,array('needguide'=>1,'id'=>$id,'sn'=>$sn));
		//header("Location:".$url);
		//exit();
	}
$GuideToUrl = fm_murl($do,$ac,$operation,array('id'=>$id,'sn'=>$sn));
?>