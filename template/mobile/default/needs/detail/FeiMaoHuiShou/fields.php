<?php
defined('IN_IA') or exit('Access Denied');
/*
* 模型字段序列	键值=》键标题，
*/

$FM_COLS['FeiMaoHuiShou']=$cols['FeiMaoHuiShou']=array(
	'setfor'=>'数据归属',	//会员id
	'name'=>'会员姓名',
	'mobile'=>'手机号',
	'sex'=>'性别',
	'starttime'=>'预约上门时间',
	'endtime'=>'最晚等候时间',
	'cityarea'=>'区域',
	'street'=>'街道',
	'address'=>'详细地址',
	'house'=>'房号',
	'remark'=>'备注信息',
	'tuijianma'=>'推荐码',
	'stars'=>'应用打分'	//用户对系统的评级
);

/*
* 后端WEB管理时，表单数据列表额外显示的字段(为了排版方便，只额外显示6列)
*/
$FM_LISTCOLS['FeiMaoHuiShou']=array(
	'cityarea'=>'区域',
	'street'=>'街道',
	'address'=>'详细地址',
	'house'=>'房号',
	'starttime'=>'预约上门时间',
	'remark'=>'备注信息'
);

/*
* 后端WEB管理时，表单数据详情页显示的字段
*/
$FM_DETAILCOLS['FeiMaoHuiShou']=$FM_COLS['FeiMaoHuiShou'];

/*
* 拼接模型字段键，前台表单调取数据使用
*/
$fields['FeiMaoHuiShou']=array();
foreach($cols['FeiMaoHuiShou'] as $cols_key=>$cols_title){
	$fields['FeiMaoHuiShou'][]=$cols_key;
}

?>