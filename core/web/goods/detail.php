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
 * @remark 商品详情；
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;

load()->func('tpl');
load()->model('account');//加载公众号函数
fm_load()->fm_model('category'); //分类管理模块
fm_load()->fm_model('goods'); //商品管理模块
fm_load()->fm_model('goodstpl'); //商品模型管理模块
fm_load()->fm_func('tpl'); //代码块函数

//加载风格模板及资源路径

$fm453style = fmFunc_ui_shopstyle();
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
$_W['page']['title'] = $shopname.$routes[$do]['title'];
$direct_url=fm_wurl($do,$ac,$operation,'');

$uniacid=$_W['uniacid'];
$plattype=$settings['plattype'];
$platids= fmFunc_getPlatids();//获取平台模式关联的公众号商户ID参数
$platid=$_W['uniacid'];
$oauthid=$platids['oauthid'];
$fendianids=$platids['fendianids'];
$supplydianids=$platids['supplydianids'];
$blackids=$platids['blackids'];

$gtpltype = fmMod_goodstpl_type_get();//列出全部产品模型清单
$marketmodeltypes =fmFunc_market_types();
$catestatus=fmFunc_status_get('category');
$datatype= fmFunc_data_types();

//平台模式处理
include_once FM_PUBLIC.'plat.php';
$supplydians=explode(',',$supplydianids);//字符串转数组
$supplydians=array_filter($supplydians);//数组去空

//按平台模式前置筛选条件
$condition=' WHERE ';
$params=array();
include_once FM_PUBLIC.'forsearch.php';
$condition .= ' AND `deleted` = :deleted';
$params[':deleted'] = 0;

//获取分类
$allcategory = fmMod_category_get('goods');
$parent = $child = array();
$parent = $allcategory['parent'];
$child = $allcategory['child'];

if ($operation == 'display') {
	$id = intval($_GPC['id']);
	$members=array();
	if($id>0) {
		$result=fmMod_goods_detail_all($id);
		if($result['result']) {
			$item=$result['data'];
			$allspecs=$item['allspecs'];
			$options=$item['options'];
			$tpl=$item['goodstpl'];
			$goodstplparams = fmMod_goodstpl_query_param($tpl,$platid);
			//产品管理页面二维码
			$qrcode=  fmFunc_qrcode_name_w($platid,$do,$ac,$operation,$id);
			fmFunc_qrcode_check($qrcode,$_W['siteurl']);
			$qrcode_admin=tomedia($qrcode);
			//产品预览页面二维码
			$link_preview =  fm_murl('goods','detail','index',array('id' => $id));
			$qrcode=fmFunc_qrcode_name_m($platid,'goods','detail','index',$id,0,0);
			fmFunc_qrcode_check($qrcode,$link_preview);
			$qrcode_preview=tomedia($qrcode);
			//规格参数
			$optionparams=fmMod_goods_optionparams();
			//产品专员
			$openids=$item['goodadm'];
			if($openids)
			{
				$openids = explode(',',$openids);
				$openids = array_unique($openids);
				foreach($openids as $o_k=>$openid)
				{
					$member=array();
					$member_info= fmMod_member_query('',$openid);
					$member = $admin['info'] = $member_info['data'];
					$members[$o_k]=$member;
				}
			}
		}else{
			message('未获取到商品信息：'.$result['msg'],'referer','error');
		}
	}else{
		$item=array();
		$item['goodtpl']='default';
	}
	include $this->template($fm453style.$do.'/453');
}
elseif ($operation == 'stock') {
	$id = intval($_GPC['id']);
	message('功能完善中，暂不开放','referer','info');
	if($id>0) {
		$result=fmMod_goods_detail_all($id);
		if($result['result']) {
			$item=$result['data'];
		}else{
			message('未获取到商品信息：'.$result['msg'],'referer','error');
		}
	}else{
	message('需要先选定一个商品',fm_wurl($do,'list','display',array()),'error');
	}
	include $this->template($fm453style.$do.'/453');
}
elseif ($operation == 'price') {
	$id = intval($_GPC['id']);
			message('功能完善中，暂不开放','referer','info');
	if($id>0) {
		$result=fmMod_goods_detail_all($id);
		if($result['result']) {
			$item=$result['data'];
		}else{
			message('未获取到商品信息：'.$result['msg'],'referer','error');
		}
	}else{
		message('需要先选定一个商品',fm_wurl($do,'list','display',array()),'error');
	}
	include $this->template($fm453style.$do.'/453');
}
elseif ($operation == 'modify') {
	$id = intval($_GPC['id']);
	if (checksubmit('submit_withSysNotice') || checksubmit('submit_withoutNotice')) {
		if (empty($_GPC['title'])) {
			message('请输入商品名称！');
		}

		//从字段清单中罗列前台对应的请求变量
		$table_goods_column=fmFunc_tables_fields('goods');
		$data=array();
		foreach($table_goods_column as $key){
			$data[$key]=$_GPC[$key];
		}

		//修复一些请求变量（在字段清单中但获取方式特别）
		$data['timestart']=strtotime($data['timestart']);//开始时间
		$data['timeend']=strtotime($data['timeend']);//结束时间
		//关联产品专员
		$data['goodadm'] = trim($data['goodadm']);
		$_temp = explode(',',$data['goodadm']);
		$_temp = array_unique($_temp);
		$_temp = array_filter($_temp);
		$data['goodadm'] = implode(',',$_temp);

		//附加一些额外的请求变量(不在字段清单中的)
		$data['category']=$_GPC['category'];
		$data['thumbs']=$_GPC['thumbs'];
		$data['statuscode']='64';//状态码 （用于后期权限流程判断）
		if ($id<=0) {
			$result=fmMod_goods_new_basic($data,$platid);
			$id = $result['data'];
			//写入操作日志START
			$dologs=array(
				'url'=>$_W['siteurl'],
				'description'=>'商城后台创建新产品；',
				'addons'=>$data,
			);
			fmMod_log_record($platid,$_W['uniacid'],$_W['uid'],$fanid,$openid,'fm453_shopping_goods',$id,'create',$dologs);
			unset($dologs);
			//写入操作日志END
			//给管理员发个微信消息
			require_once MODULE_ROOT.'/core/msgtpls/tpls/goods.php';
			//发微信模板通知
			$postdata = $tplnotice_data['new']['admin'];
			$result= fmMod_notice_tpl($postdata);
			if(!$result) {
				require MODULE_ROOT.'/core/msgtpls/msg/article.php';
				$postdata = $notice_data['new']['admin'];
				$result= fmMod_notice($settings['manageropenids'],$postdata);
			}
		}
		elseif($id>0) {
			//直接将数据请求至商品管理模型进行加工处理
			$result=fmMod_goods_modify_basic($id,$data);

			//写入操作日志START
			$dologs=array(
				'url'=>$_W['siteurl'],
				'description'=>'商城后台更新产品；',
				'addons'=>$data,
			);
			fmMod_log_record($platid,$_W['uniacid'],$_W['uid'],$fanid,$openid,'fm453_shopping_goods',$id,'update',$dologs);
			unset($dologs);
			//写入操作日志END

			//检测是否通知到管理员(更新文单时如果选择非静默提交)
			if(checksubmit('submit_withSysNotice')){
				//给管理员发个微信消息
				require_once MODULE_ROOT.'/core/msgtpls/tpls/goods.php';
				//发微信模板通知
				$postdata = $tplnotice_data['detail']['modify']['admin'];
				$result= fmMod_notice_tpl($postdata);
				if(!$result) {
					require MODULE_ROOT.'/core/msgtpls/msg/article.php';
					$postdata = $notice_data['detail']['modify']['admin'];
					$result= fmMod_notice($settings['manageropenids'],$postdata);
				}
			}
		}
		//至此，产品ID的值$id已经一定存在，其他产品相关表根据此值进行记录
		if($data['goodadm']){
		//给文章对应的作者或者会员发个微信消息
			if($data['goodadm'] !=$settings['manageropenids']) {
				$postdata = $tplnotice_data['detail']['modify']['goodadm'];
				$result= fmMod_notice_tpl($postdata);
				if(!$result) {
					require MODULE_ROOT.'/core/msgtpls/msg/goods.php';
					$postdata = $notice_data['detail']['modify']['admin'];
					$result= fmMod_notice($data['goodadm'],$postdata);
				}
			}
		}

		$totalstocks = 0;
		//处理自定义参数
		$param_data=array();
		$temp_keys=array('id','title','value','displayorder');
		foreach($temp_keys as $key){
			$post_key='param_'.$key;
			$param_data[$key.'s']=$_POST[$post_key];
		}
		$result=fmMod_goods_modify_params($id,$param_data);
		unset($post_key);
		unset($temp_keys);
		//写入操作日志START
		$dologs=array(
			'url'=>$_W['siteurl'],
			'description'=>'商城后台更新产品自定义参数；',
			'addons'=>$result['data'],
		);
		fmMod_log_record($platid,$_W['uniacid'],$_W['uid'],$fanid,$openid,'fm453_shopping_goods_param',$id,'update',$dologs);
		unset($dologs);
		unset($temp_a);
		//写入操作日志END

		//处理自定义标签
		$label_data=array();
		$temp_keys=array('id','title','value','displayorder');
		foreach($temp_keys as $key){
			$post_key='label_'.$key;
			$label_data[$key.'s']=$_POST[$post_key];
		}
		$result=fmMod_goods_modify_labels($id,$label_data);
		unset($post_key);
		unset($temp_keys);
		//写入操作日志START
		$dologs=array(
			'url'=>$_W['siteurl'],
			'description'=>'商城后台更新产品自定义标签；',
			'addons'=>$result['data'],
		);
		fmMod_log_record($platid,$_W['uniacid'],$_W['uid'],$fanid,$openid,'fm453_shopping_goods_label',$id,'update',$dologs);
		unset($dologs);
		unset($temp_a);
		//写入操作日志END
		//处理商品规格
		$totalstocks=0;//总库存基量
		$files = $_FILES;
		$spec_data=array();
		$temp_keys=array('id','title');
		foreach($temp_keys as $key){
			$post_key='spec_'.$key;
			$spec_data[$key.'s']=$_POST[$post_key];
		}
		unset($post_key);
		unset($temp_keys);
		$spec_item_data=$_GPC['spec_item'];
		$result=fmMod_goods_modify_specs($id,$spec_data,$spec_item_data);
		$return_specs=$result['data'];
		$return_spec_items=$return_specs['spec_items'];//经处理后，返回规格子项数据
		$spec_items=array();
		if($return_specs['done_ids']){
			foreach($return_specs['done_ids'] as $r_s_d){
				$specid=$r_s_d;
				foreach($return_spec_items[$specid]['done'] as $ssk => $spec_items_done){
					$spec_items[]=$spec_items_done;
				}
			}
		}
		//保存规格
		$option_idss = $_POST['option_ids'];
		$option_cankaoprices = $_POST['option_cankaoprice'];
		$option_marketprices = $_POST['option_marketprice'];
		$option_costprices = $_POST['option_costprice'];
		$option_agentprices = $_POST['option_agentprice'];
		$option_agentsaleprices = $_POST['option_agentsaleprice'];
		$option_stocks = $_POST['option_stock'];
		$option_weights = $_POST['option_weight'];
		$len = count($option_idss);
		$optionids = array();
		for ($k = 0; $k < $len; $k++) {
			$option_id = "";
			$ids = $option_idss[$k];
			$get_option_id = $_GPC['option_id_' . $ids][0];
			$idsarr = explode("_",$ids);
			$newids = array();
			foreach($idsarr as $key=>$ida){
				foreach($spec_items as $it){
					if($it['get_id']==$ida){
						$newids[] = $it['id'];
						break;
					}
				}
			}
	    	$newids = implode("_",$newids);
			$a = array(
				"goodsid" => $id,
				"title" => $_GPC['option_title_' . $ids][0],
				"thumb"=>$_GPC['option_title_thumb_'.$ids][0],
				"cankaoprice" => $_GPC['option_cankaoprice_' . $ids][0],
				"costprice" => $_GPC['option_costprice_' . $ids][0],
				"marketprice" => $_GPC['option_marketprice_' . $ids][0],
				"agentprice" => $_GPC['option_agentprice_' . $ids][0],
				"agentsaleprice" => $_GPC['option_agentsaleprice_' . $ids][0],
				"stock" => $_GPC['option_stock_' . $ids][0],
				"weight" => $_GPC['option_weight_' . $ids][0],
				"specs" => $newids
			);
			$totalstocks+=$a['stock'];//根据规格项计算总库存
			if (empty($get_option_id)) {
				pdo_insert("fm453_shopping_goods_option", $a);
				$option_id = pdo_insertid();
			} else {
				pdo_update("fm453_shopping_goods_option", $a, array('id' => $get_option_id));
				$option_id = $get_option_id;
			}
    		$optionids[] = $option_id;
		}
		if (count($optionids) > 0) {
			pdo_query("delete from " . tablename('fm453_shopping_goods_option') . " where goodsid= ".$id." and id not in ( " . implode(',', $optionids) . ")");
		}else{
			pdo_query("delete from " . tablename('fm453_shopping_goods_option') . " where goodsid=".$id);
		}
		//写入操作日志START
		$dologs=array(
			'url'=>$_W['siteurl'],
			'description'=>'商城后台更新产品规格；',
			'addons'=>'',
		);
		fmMod_log_record($platid,$_W['uniacid'],$_W['uid'],$fanid,$openid,'fm453_shopping_goods_option',$id,'update',$dologs);
		unset($dologs);
		//写入操作日志END
		//处理营销模型选项
		$mmid=$marketmodels['id'];
		$marketmodeldata = array(
			'uniacid' => intval($_W['uniacid']),
			'gid' => intval($id),
			'ispresale' => intval($_GPC['ispresale']),
			'islimitnum' => intval($_GPC['islimitnum']),
			'islimittime' => intval($_GPC['islimittime']),
			'isfreedispatch' => intval($_GPC['isfreedispatch']),
			'isminus' => intval($_GPC['isminus']),
			'isgiftable' => intval($_GPC['isgiftable']),
			'isaddable' => intval($_GPC['isaddable']),
			'ispintuan' => intval($_GPC['ispintuan']),
			'isguessable' => intval($_GPC['isguessable']),
			'islucky' => intval($_GPC['islucky']),
			'isdiscount' => intval($_GPC['isdiscount']),
			'isonlynewuser' => intval($_GPC['isonlynewuser']),
			'isonlyfemale' => intval($_GPC['isonlyfemale']),
			'isonlymember' => intval($_GPC['isonlymember'])
		);

		if (empty($mmid)) {
			pdo_insert('fm453_shopping_goods_marketmodel', $marketmodeldata);
			$mmid = pdo_insertid();
		} else {
			pdo_update('fm453_shopping_goods_marketmodel', $marketmodeldata, array('id' => $mmid,));
		}
		//写入操作日志START
			$dologs=array(
				'url'=>$_W['siteurl'],
				'description'=>'商城后台更新产品营销模型；',
				'addons'=>$marketmodeldata,
			);
			fmMod_log_record($platid,$_W['uniacid'],$_W['uid'],$fanid,$openid,'fm453_shopping_goods_label',$id,'update',$dologs);
			unset($dologs);
			//写入操作日志END
		//处理营销模型关联购买链接选项
		//预售
		$buylink_presale_id=$item['buylink']['presale']['id'];
		$buylinkdata['presale'] =array(
		'gid' => intval($id),
		'marketmodel' => 'presale',
		'linkurl' => $_GPC['presale_link'],
		'isused' => intval($_GPC['presale_link_isused'])
		);
		if (empty($buylink_presale_id)) {
			pdo_insert('fm453_shopping_goods_buylink', $buylinkdata['presale']);
			$buylink_presale_id = pdo_insertid();
		} else {
			pdo_update('fm453_shopping_goods_buylink', $buylinkdata['presale'], array('id' => $buylink_presale_id));
		}
		//拼团
		$buylink_pintuan_id=$item['buylink']['pintuan']['id'];
		$buylinkdata['pintuan'] =array(
		'gid' => intval($id),
		'marketmodel' => 'pintuan',
		'linkurl' => $_GPC['pintuan_link'],
		'isused' => intval($_GPC['pintuan_link_isused'])
		);
		if (empty($buylink_pintuan_id)) {
			pdo_insert('fm453_shopping_goods_buylink', $buylinkdata['pintuan']);
			$buylink_pintuan_id = pdo_insertid();
		} else {
			pdo_update('fm453_shopping_goods_buylink', $buylinkdata['pintuan'], array('id' => $buylink_pintuan_id));
		}
		//写入操作日志START
		$dologs=array(
			'url'=>$_W['siteurl'],
			'description'=>'商城后台更新产品营销模型关联购买链接；',
			'addons'=>$buylinkdata,
		);
		fmMod_log_record($platid,$_W['uniacid'],$_W['uid'],$fanid,$openid,'fm453_shopping_goods_buylink',$id,'update',$dologs);
		unset($dologs);
		//写入操作日志END
		//处理产品模型关联的产品参数 BY FM453 160509
		$tpl=$_GPC['goodtpl'];
		$temp_row = pdo_fetch("SELECT sn FROM " . tablename('fm453_shopping_goods') . " WHERE id = :id", array(':id' => $id));
		$gsn=$temp_row['sn'];
		unset($goodstplparams);
		$goodstplparams=fmMod_goodstpl_query_param($tpl,$platid);
		$old_tplparam_item= pdo_fetchall("SELECT * FROM " . tablename('fm453_shopping_goods_tplparam') . " WHERE gsn = :gsn AND goodstpl ='{$tpl}' AND deleted = 0", array(':gsn' => $gsn),'tplparam');
		foreach($goodstplparams as $tplindex=>$tplparams){
			$temp_tplparam=$_GPC['tplparams'];
			// print_r($temp_tplparam);
			$temp_tplparam_item=$_GPC['tplparams'][$tpl][$tplindex];
			$temp_title=$temp_tplparam_item['title'];
			if(empty($temp_title)) {
				$temp_deleted=1;
				$temp_tplparam_data=array(
					"deleted"=>1,
					"tplparam"=>$tplindex,
					"gsn"=>$gsn,
					"goodstpl"=>$tpl
				);
			}else {
				$temp_deleted=0;
				$temp_value=$temp_tplparam_item['value'];
				$temp_displayorder=$temp_tplparam_item['displayorder'];
				$temp_tplparam_data=array(
					"deleted"=>0,
					"title"=>$temp_title,
					"value"=>$temp_value,
					"displayorder"=>$temp_displayorder,
					"tplparam"=>$tplindex,
					"gsn"=>$gsn,
					"goodstpl"=>$tpl
				);
			}
			//print_r($temp_tplparam_data);
			if($old_tplparam_item[$tplindex]){
				//系统中已经有记录，使用追加更新的方式
				pdo_update('fm453_shopping_goods_tplparam',$temp_tplparam_data,$old_tplparam_item[$tplindex]['id']);
			}else {
				//系统暂无记录，则新建一条
				pdo_insert('fm453_shopping_goods_tplparam',$temp_tplparam_data);
			}
			//写入操作日志START
			$dologs=array(
				'url'=>$_W['siteurl'],
				'description'=>'商城后台更新产品模型对应的具体参数；',
				'addons'=>$temp_tplparam_data,
			);
			fmMod_log_record($platid,$_W['uniacid'],$_W['uid'],$fanid,$openid,'fm453_shopping_goods_tplparam',$id,'update',$dologs);
			unset($dologs);
			//写入操作日志END
			unset($temp_tplparam_item);
			unset($temp_title);
			unset($temp_value);
			unset($temp_displayorder);
			unset($temp_deleted);
			unset($temp_tplparam_data);
		}

		//更新总库存
		if ( ($totalstocks > 0) && ($data['totalcnf'] != 2) ) {
			pdo_update("fm453_shopping_goods", array("total" => $totalstocks), array("id" => $id));
		}
		message('产品编辑/更新成功！',fm_wurl('goods','detail','display',array('id'=>$id)),'success');
	}
}
