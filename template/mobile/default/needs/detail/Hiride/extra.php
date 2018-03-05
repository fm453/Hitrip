<?php
defined('IN_IA') or exit('Access Denied');
/*
* 唯一表单(总参与限制为每人1次)需要查找与当前会员匹配的表单提交记录;有则调用之
@ORDER BY id ASC, 取记录中最早的数据
*/

/*
* 初始化一些数据,根据前台模块自行设置
*/
//回收品类
	$recyle_prices=array();
	$recyle_prices['cats']=array(
		'cat1'=>'生活类',
		'cat2'=>'纸质类',
		'cat3'=>'衣物类',
	);
//品类价格
	$recyle_prices['cat1']=array();
	$recyle_prices['cat1']['f1']=array('title'=>'饮料瓶','price'=>'1','unit'=>'元/公斤');
	$recyle_prices['cat1']['f2']=array('title'=>'易拉罐(铁质)','price'=>'0.3','unit'=>'元/公斤');
	$recyle_prices['cat1']['f3']=array('title'=>'易拉罐(铝质)','price'=>'1','unit'=>'元/公斤');
	$recyle_prices['cat1']['f4']=array('title'=>'衣物','price'=>'0.4','unit'=>'元/公斤');

	$recyle_prices['cat2']=array();
	$recyle_prices['cat2']['f1']=array('title'=>'饮料瓶','price'=>'1','unit'=>'元/公斤');
	$recyle_prices['cat2']['f2']=array('title'=>'易拉罐(铁质)','price'=>'0.3','unit'=>'元/公斤');
	$recyle_prices['cat2']['f3']=array('title'=>'易拉罐(铝质)','price'=>'1','unit'=>'元/公斤');

	$recyle_prices['cat3']=array();
	$recyle_prices['cat3']['f1']=array('title'=>'饮料瓶','price'=>'1','unit'=>'元/公斤');
	$recyle_prices['cat3']['f2']=array('title'=>'易拉罐(铁质)','price'=>'0.3','unit'=>'元/公斤');
	$recyle_prices['cat3']['f3']=array('title'=>'易拉罐(铝质)','price'=>'1','unit'=>'元/公斤');

	//
	$needs_data['starttime']= !empty($needs_data['starttime']) ? (strtotime($needs_data['starttime'])) : ($_W['timestamp']+35*60);
	//开始日期组件
	$starttime = $needs_data['starttime'];
	$start_beginyear = 2017;
	$start_endyear = 2018;
	$starttime_data_options = '{"value":"'. date("Y-m-d H:i",$starttime). '","beginYear":"'.$start_beginyear.'","endYear":"'.$start_endyear.'"}';
//工作时间
	$worktime = array(
		'start'=>array('h'=>8,'i'=>30),	//每天开业时间（时、分）
		'end'=>array('h'=>20,'i'=>30),	//每天停业时间（时、分）
	);
?>