<?php
defined('IN_IA') or exit('Access Denied');

fm_load()->fm_func('identitycard');

$needs_tpl = $needs['template'];

/*
* 模型字段序列	键值=》键标题，
*/
$FM_COLS[$needs_tpl]=$cols[$needs_tpl]=array(
	'setfor'=>'数据归属',	//会员id
	'name'=>'会员姓名',
	'mobile'=>'手机号',
	'is_public_mobile'=>'是否公开手机号',
	'idcard'=>'身份证号',
	'is_public_idcard'=>'是否公开身份证号',
	'sex'=>'性别',
	'age'=>'年龄',

	'wxhao'=>'微信号',
	'is_public_wxhao'=>'微信号是否公开',
	'qq'=>'QQ号',
	'email'=>'邮箱',

	'teamname'=>'队名',
	'team'=>'队员',   //{},{},{username,age,sex,phone,idcard,openid,avatar}

	'is_public'=>'是否公开信息',
	'is_search'=>'是否允许被搜索',
	'remark'=>'备注',
	'reply'=>'平台回复',
	'tuijianma'=>'推荐码',
	'stars'=>'活动星级'
);

/*
* 后端WEB管理时，表单数据列表额外显示的字段(为了排版方便，只额外显示6列)
*/
$FM_LISTCOLS[$needs_tpl]=array(
	'teamname'=>'队名',
	'remark'=>'备注',
);

/*
* 后端WEB管理时，表单数据详情页显示的字段
*/
$FM_DETAILCOLS[$needs_tpl]=$FM_COLS[$needs_tpl];

/*
* 拼接模型字段键，前台表单调取数据使用
*/
$fields[$needs_tpl]=array();
foreach($cols[$needs_tpl] as $cols_key=>$cols_title){
	$fields[$needs_tpl][]=$cols_key;
}
?>