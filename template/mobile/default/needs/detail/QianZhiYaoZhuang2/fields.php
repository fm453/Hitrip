<?php
defined('IN_IA') or exit('Access Denied');
/*
* 模型字段序列	键值=》键标题，
*/

$FM_COLS['QianZhiYaoZhuang2']=$cols['QianZhiYaoZhuang2']=array(
	'setfor'=>'数据归属',	//会员id
	'name'=>'会员姓名',
	'mobile'=>'手机号',
	'sex'=>'性别',
	'TicketNumber'=>'准考证号',
	'thumb'=>'皮肤相片(正脸)',
	'thumbLeft'=>'皮肤相片(左脸)',
	'thumbright'=>'皮肤相片(右脸)',
	'howToKnow'=>'如何得知报名渠道',
	'isSick'=>'皮肤是否薄或敏感'
);

/*
* 后端WEB管理时，表单数据列表额外显示的字段(为了排版方便，只额外显示6列)
*/
$FM_LISTCOLS['QianZhiYaoZhuang2']=array(
	'TicketNumber'=>'准考证号',
);

/*
* 后端WEB管理时，表单数据详情页显示的字段
*/
$FM_DETAILCOLS['QianZhiYaoZhuang2']=$FM_COLS['QianZhiYaoZhuang2'];
/*
* 拼接模型字段键，前台表单调取数据使用
*/
$fields['QianZhiYaoZhuang2']=array();
foreach($cols['QianZhiYaoZhuang2'] as $cols_key=>$cols_title){
	$fields['QianZhiYaoZhuang2'][]=$cols_key;
}
?>
