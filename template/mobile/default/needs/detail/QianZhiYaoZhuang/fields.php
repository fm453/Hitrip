<?php
defined('IN_IA') or exit('Access Denied');
/*
* 模型字段序列	键值=》键标题，
*/

$FM_COLS['QianZhiYaoZhuang']=$cols['QianZhiYaoZhuang']=array(
	'setfor'=>'数据归属',	//会员id
	'name'=>'会员姓名',
	'mobile'=>'手机号',
	'sex'=>'性别',
	'wxhao'=>'微信号',
	'age'=>'年龄',
	'question'=>'诉求',
	'job'=>'职业/工作',
	'isEverUser'=>'是否使用过产品',
	'starttime'=>'预约时间',
	'isAcceptArrange'=>'是否接受时间安排',
	'thumb'=>'皮肤相片',
	'tuijianma'=>'推荐码',
	'stars'=>'应用评级'
);

/*
* 后端WEB管理时，表单数据列表额外显示的字段(为了排版方便，只额外显示6列)
*/
$FM_LISTCOLS['QianZhiYaoZhuang']=array(
	'sex'=>'性别',
	'wxhao'=>'微信号',
	'age'=>'年龄',
	'question'=>'诉求',
);

/*
* 后端WEB管理时，表单数据详情页显示的字段
*/
$FM_DETAILCOLS['QianZhiYaoZhuang']=$FM_COLS['QianZhiYaoZhuang'];
/*
* 拼接模型字段键，前台表单调取数据使用
*/
$fields['QianZhiYaoZhuang']=array();
foreach($cols['QianZhiYaoZhuang'] as $cols_key=>$cols_title){
	$fields['QianZhiYaoZhuang'][]=$cols_key;
}
?>