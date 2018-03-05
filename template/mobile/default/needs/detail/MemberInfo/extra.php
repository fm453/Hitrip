<?php
defined('IN_IA') or exit('Access Denied');
/*
* 会员模型要求表单自动跟当前会员匹配,从当前会员身份进入时,如果该会员已有表单记录，则调用之
@ORDER BY id ASC, 取记录中最早的数据
*/

if(!$sn){
	$sn=pdo_fetchcolumn("SELECT ordersn FROM ".tablename('fm453_shopping_needs_order')." WHERE fromuid = :fromuid AND nid = :nid ORDER BY id ASC",array(':fromuid'=>$_FM['member']['info']['uid'],':nid'=>$id));
	if($sn) {
		$needs_form=pdo_fetchall("SELECT * FROM ".tablename('fm453_shopping_needs_data')." WHERE nid=:nid AND sn=:sn",array(':nid'=>$id,':sn'=>$sn),'title');
		if(!empty($needs_form)){
			foreach($needs_form as $key=>$form){
				$bookerid = $needs_data['setfor']=intval($form['setfor']);
				$needs_data[$key]=$form['value'];
			}
		}
	}
}
/*
* 初始化一些数据,根据前台模块自行设置
*/
//格式化表单提交验证用的数据对象
$needs['form_postdata']=array();
$formPostData_key ="{"."\r";
$_defaultPostData=array('setfor','id','sn','template','token');
foreach($_defaultPostData as $_formPostData_key){
	$needs['form_postdata'][$_formPostData_key]="form.".$_formPostData_key.".value";
	$formPostData_key .= $_formPostData_key.": form.".$_formPostData_key.".value,"."\r";
}
unset($FM_COLS['MemberInfo']['reply']);//不检测reply数据
foreach($FM_COLS['MemberInfo'] as $_formPostData_key=>$_colTitle){
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
//生日日期组件(默认限制可选择范围在8岁～70岁)
$needs['birth_beginYear']= !empty($needs['birth_beginYear']) ? $needs['birth_beginYear'] : date('Y',strtotime('-70 years'));
$needs['birth_endYear']= !empty($needs['birth_endYear']) ? $needs['birth_endYear'] : date('Y',strtotime('-8 years'));
$birthday_options='{'.'"type":"date"'. ',' .'"beginYear":'. $needs['birth_beginYear'] .','. '"endYear":'.$needs['birth_endYear'].'}';

$needs_data['birthday'] = !empty($needs_data['birthday']) ? strtotime($needs_data['birthday']) : (($_FM['member']['info']['birthday']) ? strtotime($_FM['member']['info']['birthday']) : '');

$needs_data['thumb'] = !empty($needs_data['thumb']) ? $needs_data['thumb'] : $_FM['member']['info']['avatar'];

$needs_data['birth_province'] = !empty($needs_data['birth_province']) ? $needs_data['birth_province'] : "安徽省";
$needs_data['birth_city'] = !empty($needs_data['birth_city']) ? $needs_data['birth_city'] : "安庆市";
$needs_data['birth_county'] = !empty($needs_data['birth_county']) ? $needs_data['birth_county'] : "太湖县";

$needs_data['now_province'] = !empty($needs_data['now_province']) ? $needs_data['now_province'] : "海南省";
$needs_data['now_city'] = !empty($needs_data['now_city']) ? $needs_data['now_city'] : "三亚市";
$needs_data['now_county'] = !empty($needs_data['now_county']) ? $needs_data['now_county'] : "吉阳区";

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