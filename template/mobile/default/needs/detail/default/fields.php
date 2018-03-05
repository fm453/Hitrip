<?php
defined('IN_IA') or exit('Access Denied');
/*
* 模型字段序列	键值=》键标题，
*/
$FM_COLS['default']=$cols['default']=array(
	'setfor'=>'数据归属',	//会员id
	'name'=>'会员姓名',
	'mobile'=>'手机号',
	'sex'=>'性别',
	'wxhao'=>'微信号',
	'remark'=>'备注',
	'reply'=>'平台回复',
	'tuijianma'=>'推荐码',
	'stars'=>'应用打分'	//用户评级
);

/*
* 后端WEB管理时，表单数据列表额外显示的字段(为了排版方便，只额外显示6列)
*/
$FM_LISTCOLS['default']=array(
	'remark'=>'备注',
	'tuijianma'=>'推荐码',
);

/*
* 后端WEB管理时，表单数据详情页显示的字段
*/
$FM_DETAILCOLS['default']=$FM_COLS['default'];
/*
* 拼接模型字段键，前台表单调取数据使用
*/
$fields['default']=array();
if($cols['default']){
    foreach($cols['default'] as $cols_key=>$cols_title){
    	$fields['default'][]=$cols_key;
    }
}
?>