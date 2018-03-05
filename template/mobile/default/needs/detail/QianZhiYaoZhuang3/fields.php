<?php
defined('IN_IA') or exit('Access Denied');
/*
* 模型字段序列	键值=》键标题，
*/

$FM_COLS['QianZhiYaoZhuang3']=$cols['QianZhiYaoZhuang3']=array(
	'setfor'=>'数据归属',	//会员id
	'name'=>'会员姓名',
	'mobile'=>'手机号',
	'sex'=>'性别',
	'age'=>'年龄',
	'douling'=>'痘龄',
	'howToKnow'=>'如何得知报名渠道',
	'school'=>'所在中学',
	'remark'=>'备注说明'
);

/*
* 后端WEB管理时，表单数据列表额外显示的字段(为了排版方便，只额外显示6列)
*/
$FM_LISTCOLS['QianZhiYaoZhuang3']=array(
	'age'=>'年龄',
	'douling'=>'痘龄',
);

/*
* 后端WEB管理时，表单数据详情页显示的字段
*/
$FM_DETAILCOLS['QianZhiYaoZhuang3']=$FM_COLS['QianZhiYaoZhuang3'];
/*
* 拼接模型字段键，前台表单调取数据使用
*/
$fields['QianZhiYaoZhuang3']=array();
foreach($cols['QianZhiYaoZhuang3'] as $cols_key=>$cols_title){
	$fields['QianZhiYaoZhuang3'][]=$cols_key;
}
?>
