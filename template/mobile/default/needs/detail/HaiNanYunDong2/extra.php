<?php
defined('IN_IA') or exit('Access Denied');
/*
* 唯一表单(总参与限制为每人1次)需要查找与当前会员匹配的表单提交记录;有则调用之
* 周期性表单（先计算生命周期；然后统计提交记录）
@ORDER BY id ASC, 取记录中最早的数据
*/

/*
* 初始化一些数据,根据前台模块自行设置
*/
$needs_tpl = $needs['template'];

//参数补充
$settings['needs']['whyIdcard'] = isset($settings['needs']['whyIdcard']) ? $settings['needs']['whyIdcard'] : '您的身份证件信息为我们用于为您及您的团队购买保险、作颁发奖品凭证等；我们郑重承诺，为您的隐私信息保密，不向任何其他无关第三方透露。';
//活动类型
	$activity_types=array();
	$activity_types['cats']=array(
		'cat1'=>'竞技比赛',
		'cat2'=>'户外运动',
		'cat3'=>'朋友聚会',
		'cat4'=>'拉练PK',
	);

	//默认的价格
	$needs['price'] = !isset($needs['price']) ? $needs['price'] : 150;
	//身份证号
	$needs_data['idcard'] = fmFunc_idcard_mask($needs_data['idcard']);
	//用户提交数据的格式化显示
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

	//格式化表单提交验证用的数据对象
$needs['form_postdata']=array();
$formPostData_key ="{"."\r";
$_defaultPostData=array('setfor','id','sn','template','token');
foreach($_defaultPostData as $_formPostData_key){
	$needs['form_postdata'][$_formPostData_key]="form.".$_formPostData_key.".value";
	$formPostData_key .= $_formPostData_key.": form.".$_formPostData_key.".value,"."\r";
}
unset($FM_COLS[$needs_tpl]['reply']);//不检测reply数据
foreach($FM_COLS[$needs_tpl] as $_formPostData_key=>$_colTitle){
	$needs['form_postdata'][$_formPostData_key]="form.".$_formPostData_key.".value";
	$formPostData_key .= $_formPostData_key.": form.".$_formPostData_key.".value,"."\r";
}
$formPostData_key .="}";

//如果进行匹配判断后仍然没有查到会员对应的表单数据，说明该会员没有提交历史；基于该情况初始化部分数据(可从表单配置中取得)
if(!$sn) {
	$needs_data['is_public'] = !empty($needs_data['is_public']) ? $needs_data['is_public'] : (!empty($needs['default_is_public']) ? $needs_data['default_is_public'] : 1);
	$needs_data['is_search'] = !empty($needs_data['is_search']) ? $needs_data['is_search'] : (!empty($needs['default_is_search']) ? $needs_data['default_is_search'] : 1);
	$needs_data['is_public_wxhao'] = !empty($needs_data['is_public_wxhao']) ? $needs_data['is_public_wxhao'] : (!empty($needs['default_is_public_wxhao']) ? $needs_data['default_is_public_wxhao'] : 1);
	$needs_data['is_thumbToAvatar'] = !empty($needs_data['is_thumbToAvatar']) ? $needs_data['is_thumbToAvatar'] : (!empty($needs['default_is_thumbToAvatar']) ? $needs_data['default_is_thumbToAvatar'] : 0);
	$needs_data['is_public_company'] = !empty($needs_data['is_public_company']) ? $needs_data['is_public_company'] : (!empty($needs['default_is_public_company']) ? $needs_data['default_is_public_company'] : 1);
	$needs_data['is_student'] = !empty($needs_data['is_student']) ? $needs_data['is_student'] : (!empty($needs['default_is_student']) ? $needs_data['default_is_student'] : 0);
}

//生日日期组件(默认限制可选择范围在8岁～60岁)
$needs['birth_beginYear']= !empty($needs['birth_beginYear']) ? $needs['birth_beginYear'] : date('Y',strtotime('-60 years'));
$needs['birth_endYear']= !empty($needs['birth_endYear']) ? $needs['birth_endYear'] : date('Y',strtotime('-8 years'));
$birthday_options='{'.'"type":"date"'. ',' .'"beginYear":'. $needs['birth_beginYear'] .','. '"endYear":'.$needs['birth_endYear'].'}';

$needs_data['birthday'] = !empty($needs_data['birthday']) ? strtotime($needs_data['birthday']) : (($_FM['member']['info']['birthday']) ? strtotime($_FM['member']['info']['birthday']) : '');

$needs_data['thumb'] = !empty($needs_data['thumb']) ? $needs_data['thumb'] : $_FM['member']['info']['avatar'];

$needs_data['birth_province'] = !empty($needs_data['birth_province']) ? $needs_data['birth_province'] : "海南省";
$needs_data['birth_city'] = !empty($needs_data['birth_city']) ? $needs_data['birth_city'] : "海口市";
$needs_data['birth_county'] = !empty($needs_data['birth_county']) ? $needs_data['birth_county'] : "龙华区";

$needs_data['now_province'] = !empty($needs_data['now_province']) ? $needs_data['now_province'] : "海南省";
$needs_data['now_city'] = !empty($needs_data['now_city']) ? $needs_data['now_city'] : "海口市";
$needs_data['now_county'] = !empty($needs_data['now_county']) ? $needs_data['now_county'] : "龙华区";

//默认行业
$default_industry = array(
	"architecture"=>"房地产|建筑业",
	"business"=>"商业|超市|贸易",
	"tourist"=>"酒店|景区|旅游|票务",
	"org"=>"政府|事业单位",
	"finance"=>"银行|金融|保险",
	"electronics"=>"电子硬件及周边",
	"it"=>"网络科技|电子商务",
	"agriculture"=>"果蔬|农副业",
	"factory"=>"生产加工|能源矿产",
	"art"=>"文体|艺术",
	"wuliu"=>"交通|运输|物流|仓储",
	"other"=>"其他",
);
$needs['industry'] = !empty($needs['industry']) ? $needs['industry'] : $default_industry;
$industry="[";
foreach($needs['industry'] as $i_key=>$i_tile){
	$industry .="{value:'".$i_key."',text:'".$i_tile."'},";
}
$industry = substr($industry, 0,-1);	//截取字符,从开始位置至倒数第二位
$industry .="]";
$needs_industry = $industry;

//默认学历
$default_diploma = array(
	"undergraduate"=>"大学本科",
	"master"=>"硕士研究生",
	"doctor"=>"博士|博士后",
	"junior"=>"专科",
	"senior"=>"高中",
	"middle"=>"初中",
	"other"=>"其他",
);
$needs['diploma'] = !empty($needs['diploma']) ? $needs['diploma'] : $default_diploma;
$diploma="[";
foreach($needs['diploma'] as $i_key=>$i_tile){
	$diploma .="{value:'".$i_key."',text:'".$i_tile."'},";
}
$diploma = substr($diploma, 0,-1);	//截取字符,从开始位置至倒数第二位
$diploma .="]";
$needs_diploma = $diploma;

/*
* 启动引导页设置
*/
	$isNoGuide= cache_load($do.'_no_guide'.$_W['openid'].'_nid'.$id);
	if(!$isNoGuide) {
		cache_write($do.'_no_guide'.$_W['openid'].'_nid'.$id,TRUE);
		//$url = fm_murl($do,$ac,$operation,array('needguide'=>1,'id'=>$id,'sn'=>$sn));
		//header("Location:".$url);
		//exit();
	}
    $GuideToUrl = fm_murl($do,$ac,$operation,array('id'=>$id,'sn'=>$sn));
?>