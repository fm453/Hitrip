<?php
defined('IN_IA') or exit('Access Denied');
/*
* 模型字段序列	键值=》键标题，
*/
$FM_COLS['MemberInfo']=$cols['MemberInfo']=array(
	'setfor'=>'数据归属',	//会员id
	'name'=>'会员姓名',
	'mobile'=>'手机号',
	'is_public_mobile'=>'是否公开手机号',
	'sex'=>'性别',
	'thumb'=>'相片',
	'is_thumbToAvatar'=>'相片是否同时设置为头像',

	'birth_province'=>'籍贯省份',
	'birth_city'=>'籍贯城市',
	'birth_county'=>'籍贯区/县',
	'birth_address'=>'籍贯详址',
	'now_province'=>'现居地省份',
	'now_city'=>'现居地城市',
	'now_county'=>'现居地区/县',
	'now_address'=>'现居地详址',
	'birthday'=>'出生日期',

	'wxhao'=>'微信号',
	'is_public_wxhao'=>'微信号是否公开',
	'qq'=>'QQ号',
	'email'=>'邮箱',

	'industry'=>'行业',
	'company'=>'工作单位及部门岗位',
	'is_public_company'=>'是否公开工作单位',
	'job'=>'主要工作',
	'business_scope'=>'公司经营范围',
	'company_address'=>'公司经营地址',
	'organization'=>'社会组织及职务',

	'diploma'=>'学历',
	'school'=>'毕业/在读学校',
	'college'=>'所在学院',
	'major'=>'主要专业',
	'minors'=>'辅修专业',
	'is_student'=>'是否在校生',
	'association'=>'校内协会及职务',

	'qrcode_wx'=>'个人微信二维码',
	'sign'=>'个性签名',
	'honor'=>'个人荣誉',
	'interest'=>'爱好',
	'content'=>'自我介绍',
	'thumbs'=>'相册',
	//'labels'=>'标签印象',	//待

	'is_public'=>'是否公开信息',
	'is_search'=>'是否允许被搜索',
	'remark'=>'备注',
	//'remark_kf'=>'客服备注',
	'reply'=>'平台回复',
	'tuijianma'=>'推荐码',
	'stars'=>'应用打分'	//用户对系统的评级
);

/*
* 后端WEB管理时，表单数据列表额外显示的字段(为了排版方便，只额外显示6列)
*/
$FM_LISTCOLS['MemberInfo']=array(
	'now_city'=>'现居地城市',
	'now_county'=>'现居地区/县',
	'industry'=>'行业',
	'is_public'=>'是否公开信息',
	'remark'=>'备注',
	'tuijianma'=>'推荐码',
);

/*
* 后端WEB管理时，表单数据详情页显示的字段
*/
$FM_DETAILCOLS['MemberInfo']=$FM_COLS['MemberInfo'];

/*
* 拼接模型字段键，前台表单调取数据使用
*/
$fields['MemberInfo']=array();
foreach($cols['MemberInfo'] as $cols_key=>$cols_title){
	$fields['MemberInfo'][]=$cols_key;
}
?>