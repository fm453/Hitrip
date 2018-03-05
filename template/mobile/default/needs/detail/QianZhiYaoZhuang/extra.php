<?php
/*
* 可复用表单
@ORDER BY id ASC, 取记录中最早的数据
*/

/*
* 初始化一些数据,根据前台模块自行设置
*/
    $needs_data['isAcceptArrange'] = isset($needs_data['isAcceptArrange']) ? $needs_data['isAcceptArrange'] : 1;
    $needs_data['isEverUser'] = isset($needs_data['isEverUser']) ? $needs_data['isEverUser'] : 0;

	$needs_data['starttime']= !empty($needs_data['starttime']) ? (strtotime($needs_data['starttime'])) : ($_W['timestamp']+35*60);
	//开始日期组件
	$starttime = $needs_data['starttime'];
	$start_beginyear = 2017;
	$start_endyear = 2018;
	$starttime_data_options = '{"value":"'. date("Y-m-d H:i",$starttime). '","beginYear":"'.$start_beginyear.'","endYear":"'.$start_endyear.'"}';
//工作时间
	$worktime = array(
		'start'=>array('h'=>10,'i'=>'00'),	//每天开业时间（时、分）
		'end'=>array('h'=>21,'i'=>'00'),	//每天停业时间（时、分）
	);

?>