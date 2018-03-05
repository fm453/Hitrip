<?php
/**
 * @author Fm453(方少)
 * @DACMS https://api.hiluker.com
 * @site https://www.hiluker.com
 * @url http://s.we7.cc/index.php?c=home&a=author&do=index&uid=662
 * @email fm453@lukegzs.com
 * @QQ 393213759
 * @wechat 393213759
*/

/*
 * @remark UI个性化设置
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;
load()->func('tpl');
load()->func('file');
load()->model('account');//加载公众号函数

//加载风格模板及资源路径
$moduleConf=$this->module['config'];
$fm453style = fmFunc_ui_shopstyle($moduleConf);
$fm453resource =FM_RESOURCE;

//入口判断
$routes=fmFunc_route_web();
$routes_do=fmFunc_route_web_do();
$do= $_GPC['do'];
$ac=$_GPC['ac'];
$all_ac=fmFunc_route_web_ac($do);
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$all_op=fmFunc_route_web_op_single($do,$ac);
if(!in_array($ac,$all_ac) || !in_array($operation,$all_op)){
	die(message('非法访问，请通过有效路径进入！'));
}

fmMod_privilege_adm();//检查用户授权

//开始操作管理
$shopname=$settings['brands']['shopname'];
$shopname = !empty($shopname) ? $shopname :FM_NAME_CN;
$_W['page']['title'] = $shopname.'UI等个性化设置';

$slide_home_num = 5;
$guide_page_num = 5;

if($operation=='display')
{
	for ($i=1; $i <= $slide_home_num ; $i++)
	{
		$settings['slide']['home_'.$i.'_status'] = (isset($settings['slide']['home_'.$i.'_status'])) ? intval($settings['slide']['home_'.$i.'_status']) : 0;
	}
	$color_array=array('1'=>'rgba(0,149,216,1)','2'=>'rgba(2,193,237,1)','3'=>'rgba(103,201,98,1)','4'=>'rgba(252,210,8,1)','5'=>'rgba(252,143,8,1)','6'=>'rgba(252,42,8,1)','7'=>'rgba(252,8,177,1)','8'=>'rgba(0,149,216,1)','9'=>'rgba(255,0,0,1)');
	for ($i=1; $i <= $guide_page_num ; $i++)
	{
		$settings['guide']['page_'.$i.'_status'] = (isset($settings['guide']['page_'.$i.'_status'])) ? intval($settings['guide']['page_'.$i.'_status']) : 0;
		$settings['guide']['page_'.$i.'_bgcolor'] = (isset($settings['guide']['page_'.$i.'_bgcolor'])) ? $settings['guide']['page_'.$i.'_bgcolor'] : $color_array[$i];
	}

	$settings['index']['section_ppt'] = (isset($settings['index']['section_ppt'])) ? intval($settings['index']['section_ppt']) :0;
	$settings['index']['section_square'] = intval($settings['index']['section_square']);
	$settings['common']['section_vcard_status'] = intval($settings['common']['section_vcard_status']);
	$default_vcard = '<!-- vcard start -->
				<fieldset style="white-space: normal; border: 0px none; text-align: center; box-sizing: border-box; padding: 0px;">
				<section  style="display: inline-block; box-sizing: border-box;">
					<section  style="margin: 0.2em 0px 0px; padding: 0px 0.5em 5px; max-width: 100%; color: rgb(42, 52, 58); font-size: 1.8em; line-height: 1; border-bottom-width: 1px; border-bottom-style: solid; font-family: inherit; font-weight: inherit; text-decoration: inherit; border-color:rgb(1, 149, 247); box-sizing: border-box;">
						<section  style="box-sizing: border-box;">
							<span style="color: rgb(1, 149, 247);">'.$_W['uniaccount']['name'].'</span>
						</section>
					</section>
					<section  style="margin: 5px 1em; font-size: 1em; line-height: 1; font-family: inherit; font-weight: inherit;  text-decoration: inherit; color: rgb(120, 124, 129); box-sizing: border-box;">
						<section  style="box-sizing: border-box;">
							<span style="color: rgb(1, 149, 247);">'.$shopname.'</span><br/>
						</section>
					</section>
				</section>
				</fieldset>
				<p style="white-space: normal; text-align: center;">
					<img src="'.tomedia($settings['brands']['qrcode']).'" style="width: 150px; height: 150px;" height="150" width="150"/>
				</p>
				<!-- vcart end -->';

	$settings['common']['section_vcard_content'] = isset($settings['common']['section_vcard_content']) ? htmlspecialchars_decode($settings['common']['section_vcard_content']) : $default_vcard;

	include $this->template('modset/ui');
}
elseif($operation=='modify') {
	if(!empty($settings['shouquan']['sufm453code']) && $settings['mainuser']==$_W['username'] || $_W['isfounder']){
		$saveItem = array();
		$setfor= $_GPC['setfor'];
		$opp=$_GPC['opp'];
		$setkey = 'temp';	//避免错漏,做一个临时键值
		$saveData = array();
		$temp_data=$this->module['config'];
		$dologs=array(
			'url'=>$_W['siteurl'],
			'description'=>'',
			'addons'=>$saveData
		);
		$result=array();
		switch($setfor)
		{
			case 'common':
				switch($opp)
				{
					case 'slide':
						$saveSlide = array();
						for ($i=1; $i <= $slide_home_num ; $i++) {
							$statusValue = $_GPC['home_'.$i.'_status'];	//关闭状态（0,显示，1移除）
							$nameValue = $_GPC['home_'.$i.'_name'];
							$linkValue = $_GPC['home_'.$i.'_value'];
							$iconValue=$_GPC['home_'.$i.'_icon'];
							$imageValue=$_GPC['home_'.$i.'_image'];

							$saveItem['home_'.$i.'_status']=$statusValue;
							$saveItem['home_'.$i.'_name']=$nameValue;
							$saveItem['home_'.$i.'_value']=$linkValue;
							$saveItem['home_'.$i.'_icon']=$iconValue;
							$saveItem['home_'.$i.'_image']=$imageValue;
						}
						$saveData=$saveSlide=$saveItem;
						$temp_data[$setfor][$opp]=$saveData;
						$dologs['description']='自定义主界面侧滑边栏导航设置';
						$setkey='slide';	//侧滑菜单设置统一存入slide键下
					break;

					case 'vcard':
						$saveVcard = array();
						$saveItem['section_vcard_status'] = intval($_GPC['section_vcard_status']);
						$saveItem['section_vcard_content'] = htmlspecialchars($_GPC['section_vcard_content']);
						$saveVcard =$saveItem;
						$saveData=$saveVcard =$saveItem;
						$temp_data[$opp]=$saveData;
						$dologs['description']='内容页默认名片设置';
						$setkey='common';	//名片设置统一存入common键下
					break;

					default:
					break;
				}
			break;

			case 'index':
				switch($opp)
				{
					case 'section':
						$saveSection = array();
						$saveItem['section_ppt'] = intval($_GPC['section_ppt']);
						$saveItem['section_square'] = intval($_GPC['section_square']);
						$saveSection=$saveItem;
						$saveData=$saveSection=$saveItem;
						$temp_data[$opp]=$saveData;
						$dologs['description']='首页区块组合设置';
						$setkey='index';	//区块设置统一存入index键下
					break;
					case 'square':
						$saveData = array();
						for ($i=1; $i <= 9 ; $i++) {
							$statusValue = $_GPC['square_'.$i.'_status'];	//关闭状态（0,显示，1移除）
							$nameValue = $_GPC['square_'.$i.'_name'];
							$linkValue = $_GPC['square_'.$i.'_value'];
							$iconValue=$_GPC['square_'.$i.'_icon'];
							$imageValue=$_GPC['square_'.$i.'_image'];

							$saveItem['square_'.$i.'_status']=$statusValue;
							$saveItem['square_'.$i.'_name']=$nameValue;
							$saveItem['square_'.$i.'_value']=$linkValue;
							$saveItem['square_'.$i.'_icon']=$iconValue;
							$saveItem['square_'.$i.'_image']=$imageValue;
						}
						$saveData=$saveItem;
						$temp_data[$opp]=$saveData;
						$dologs['description']='首页9宫格栏目设置';
						$setkey='index';	//区块设置统一存入index键下
					break;
					case 'sitestyle':
						$saveData = array();
						$saveItem['site_style'] = intval($_GPC['site_style']);
						$saveData=$saveItem;
						$temp_data[$opp]=$saveData;
						$dologs['description']='首页微站风格设置';
						$setkey='index';	//区块设置统一存入index键下
					break;

					default:
					break;
				}
			break;

			case 'guide':
				switch($opp)
				{
					case 'diy':
						$saveGuide=$saveItem;
						for ($i=1; $i <= $guide_page_num ; $i++)
						{
							$statusValue = intval($_GPC['page_'.$i.'_status']);	//关闭状态（0,显示，1移除）
							$tagValue = $_GPC['page_'.$i.'_tag'];
							$titleValue = $_GPC['page_'.$i.'_title'];
							$p1Value = $_GPC['page_'.$i.'_p1'];
							$p2Value = $_GPC['page_'.$i.'_p2'];
							$linkValue = $_GPC['page_'.$i.'_link'];
							$bgcolorValue=$_GPC['page_'.$i.'_bgcolor'];
							$bgimageValue=$_GPC['page_'.$i.'_bgimage'];

							$saveItem['page_'.$i.'_status']=$statusValue;
							$saveItem['page_'.$i.'_tag']=$tagValue;
							$saveItem['page_'.$i.'_title']=$titleValue;
							$saveItem['page_'.$i.'_p1']=$p1Value;
							$saveItem['page_'.$i.'_p2']=$p2Value;
							$saveItem['page_'.$i.'_link']=$linkValue;
							$saveItem['page_'.$i.'_bgcolor']=$bgcolorValue;
							$saveItem['page_'.$i.'_bgimage']=$bgimageValue;
						}
						$saveData = $saveGuide = $saveItem;
						$temp_data[$opp]=$saveData;
						$dologs['description']='引导屏样式与内容设置';
						$setkey='guide';//引导页设置统一存入guide键下
					break;

					default:
					break;
				}
			break;

			default:
				die(json_encode(array('code'=>0,'msg'=>'没有任何修改')));
			break;
		}

		$record = array();
		$record['value']=$saveData;
		$record['status']='127';
		$record=iserializer($record);
		$result=fmMod_setting_save($record,$setkey,$_W['uniacid']);
		$dologs['addons']=$saveData;
			if($result['result']) {
				$this->saveSettings($temp_data);
				fmMod_log_record($platid,$_W['uniacid'],$_W['uid'],$fanid,$openid,'fm453_shopping_settings',$id,'modify',$dologs);
				unset($dologs);
				die(json_encode(array('code'=>1,'msg'=>'已保存')));
			}else{
				die(json_encode(array('code'=>0,'msg'=>'保存失败')));
			}
	}else{
		die(json_encode(array('code'=>-1,'msg'=>'无权限')));
	}
}