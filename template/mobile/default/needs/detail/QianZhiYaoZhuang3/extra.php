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

	//默认年龄范围
$default_age = array(
	"15"=>"15岁",
	"16"=>"16岁",
	"17"=>"17岁",
	"18"=>"18岁",
	"19"=>"19岁",
	"20"=>"20岁",
	"21"=>"21岁",
);
$needs['age'] = !empty($needs['age']) ? $needs['age'] : $default_age;
$age="[";
foreach($needs['age'] as $i_key=>$i_tile){
	$age .="{value:'".$i_key."',text:'".$i_tile."'},";
}
$age = substr($age, 0,-1);	//截取字符,从开始位置至倒数第二位
$age .="]";
$needs_age = $age;

	//默认痘龄范围
$default_douling = array(
	"0.5"=>"约半年",
	"1"=>"1年左右",
	"1.5"=>"约1年半",
	"2"=>"2年左右",
	"3"=>"2年左右",
	"4"=>"4年左右",
	"5"=>"5年左右",
	"x"=>"尤来已久，超过5年了",
	"0"=>"记不清了",
);
$needs['douling'] = !empty($needs['douling']) ? $needs['douling'] : $default_douling;
$douling="[";
foreach($needs['douling'] as $i_key=>$i_tile){
	$douling .="{value:'".$i_key."',text:'".$i_tile."'},";
}
$douling = substr($douling, 0,-1);	//截取字符,从开始位置至倒数第二位
$douling .="]";
$needs_douling = $douling;

	//所在学校
$default_school = array(
	"yizhong"=>"一中",
	"bayi"=>"八一",
	"erzhong"=>"二中",
	"sanzhong"=>"三中",
	"gangzhong"=>"港中",
	"luxvn"=>"鲁迅中学",
	"other"=>"其他",
);
$needs['school'] = !empty($needs['school']) ? $needs['school'] : $default_school;
$school="[";
foreach($needs['school'] as $i_key=>$i_tile){
	$school .="{value:'".$i_key."',text:'".$i_tile."'},";
}
$school = substr($school, 0,-1);	//截取字符,从开始位置至倒数第二位
$school .="]";
$needs_school = $school;

?>