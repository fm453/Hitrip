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
 * @remark 有求必应定义；
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC, $_W,$_FM;
load()->func('tpl');
load()->model('account');//加载公众号函数

fm_load()->fm_model('needs');

//加载风格模板及资源路径

$fm453style = fmFunc_ui_shopstyle();
$fm453resource = fmFunc_ui_resource();
$appstyle = empty($settings['appstyle']) ? FM_APPSTYLE : $settings['appstyle'];

//入口判断
$routes=fmFunc_route_web();
$routes_do=fmFunc_route_web_do();
$do= $_GPC['do'];
$ac=$_GPC['ac'];
$all_ac=fmFunc_route_web_ac($do);
$all_op=fmFunc_route_web_op_single($do,$ac);
$operation = empty($_GPC['op']) ? 'display' : $_GPC['op'];
if(!in_array($ac,$all_ac) || !in_array($operation,$all_op)){
	die(message('非法访问，请通过有效路径进入！'));
}

fmMod_privilege_adm();//检查用户授权

//开始操作管理
$shopname=$settings['brands']['shopname'];
$shopname = !empty($shopname) ? $shopname :FM_NAME_CN;
$_W['page']['title'] = $shopname.$routes[$do]['title'];
$direct_url=fm_wurl($do,$ac,$operation,'');
$pindex=max(1,intval($_GPC['page']));
$psize=(intval($_GPC['psize'])>10) ? intval($_GPC['psize']) : 10;//最少显示10条主数据

$uniacid=$_W['uniacid'];
$platids=fmFunc_getPlatids();//获取平台模式关联的公众号商户ID
$oauthid=$platids['oauthid'];
$fendianids=$platids['fendianids'];
$supplydianids=$platids['supplydianids'];
$blackids=$platids['blackids'];

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

$showorder=" ORDER BY uniacid ASC,  displayorder DESC ,id DESC";
$limit=" LIMIT ".($pindex-1)*$psize.",".$psize;

$templates=fmMod_needs_tpl();
$cycles=array(
	'day'=>'天',
	'month'=>'周',
	'hour'=>'时',
	'minute'=>'分'
);

if ($operation == 'display') {
	$isbytime=intval($_GPC['isbytime']);
	if (!empty($_GPC['date'])) {
		$starttime = strtotime($_GPC['date']['start']);
		$endtime = strtotime($_GPC['date']['end']) + 86399;
	} else {
		$starttime = strtotime('-3 month');
		$endtime = time();
	}
	if($isbytime) {
		$condition .= " AND `createtime` >= :starttime AND `createtime` < :endtime";
		$params[':starttime'] = $starttime;
		$params[':endtime'] = $endtime;
	}
	$keyword = trim($_GPC['keyword']);
	if (!empty($keyword)) {
		$condition .= " AND `title`LIKE :title";
		$params[':title'] = $keyword;
	}
		$status = empty($_GPC['status']) ? 0 : intval($_GPC['status']);
		$status = intval($status);
		if ($status != 0) {
			$condition .= " AND `status` = :status";
			$params[':status'] = $status;
		}
		$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('fm453_shopping_needs') . $condition, $params);
		$list = pdo_fetchall("SELECT * FROM " . tablename('fm453_shopping_needs') . $condition . $showorder . $limit, $params);
		$pager = pagination($total, $pindex, $psize);

		foreach ($list as $row) {

		}
	include $this->template($fm453style.$do.'/453');
}elseif($operation=='new'){
	$fields=fmFunc_tables_fields('needs',$dtype='shopping');
	$data=array();
	foreach($fields as $i=>$key){
		$data[$key]=$_GPC[$key];
	}
	$data['createtime']=$_W['timestamp'];
	$data['uniacid']=$_W['uniacid'];
	unset($data['id']);
	if(checksubmit()) {
		pdo_insert('fm453_shopping_needs',$data);
		$id=pdo_insertid();
		message('表单添加成功,为您跳转到详情页，可进行更详细的设置',fm_wurl($do,$ac,'detail',array('id'=>$id)),'success');
	}
	include $this->template($fm453style.$do.'/453');
}elseif($operation=='detail'){
	$id=intval($_GPC['id']);
	if($id<=0) {
		message('需要先选择一个表单',fm_wurl($do,$ac,'display',array()),'info');
	}
	//表单预览页面二维码
	$link_preview =  fm_murl('needs','detail','index',array('id' => $id));
	$qrcode=fmFunc_qrcode_name_m($platid,'needs','detail','index',$id,0,0);
	fmFunc_qrcode_check($qrcode,$link_preview);
	$qrcode_preview=tomedia($qrcode);

	$needs=pdo_fetch("SELECT * FROM ".tablename('fm453_shopping_needs')." WHERE id=:id ",array(':id'=>$id));
	$needs['is_dianzan'] = isset($needs['is_dianzan']) ? $needs['is_dianzan'] : 1;
	$needs['is_pay'] = isset($needs['is_pay']) ? $needs['is_pay'] : 1;
	$needs['is_comment'] = isset($needs['is_comment']) ? $needs['is_comment'] : 1;
	$settings['needs'] = fmMod_needs_params($id);
	$needs_params = fmMod_needs_params($id,true);	//返回未拼接的参数

	$template=$needs['template'];
	$cols=array();
	$fields_file = MODULE_ROOT.'/template/mobile/'.$appstyle.'needs'.'/detail/'.$template.'/fields.php';
	if(!file_exists($fields_file)) {
		$fields_file = MODULE_ROOT.'/template/mobile/default/needs/detail/'.$template.'/fields.php';
	}
	if(file_exists($fields_file)) {
		require_once $fields_file;
	}

	$needs['content'] = htmlspecialchars_decode($needs['content']);
	if(checksubmit()) {
		$fields=fmFunc_tables_fields('needs',$dtype='shopping');
		$data=array();
		foreach($fields as $i=>$key){
			if(isset($_GPC[$key])){$data[$key]=$_GPC[$key];}
		}
		//重新整理部分数据
		$data['starttime']= strtotime($data['starttime']);
		$data['endtime']=strtotime($data['endtime']);
		$intvals = array('is_banner','is_cycle','is_max','is_rec','is_hot','is_dianzan','is_share','is_pay','is_comment','is_forcelogin','is_forcefollow','is_wechat','is_time','is_timestart','is_timeend');
		foreach($intvals as $kk=>$vv){
			$data[$vv] = intval($_GPC[$vv]);
		}
		// $data['is_banner']=intval($_GPC['is_banner']);

		$unsets = array('id','uniacid','createtime','viewcount','dianzancount','sharecount');
		foreach($unsets as $kk=>$vv){
			unset($data[$vv]);
		}
		$result=fmMod_needs_w_modify($data,$id);

		//保存参数
		$paramsData = $_GPC['np'];
		$result=fmMod_needs_params_save($paramsData,$id);

		message('表单更新成功',fm_wurl($do,$ac,'detail',array('id'=>$id)),'success');
	}
	include $this->template($fm453style.$do.'/453');
}elseif($operation=='data'){
	$id=intval($_GPC['id']);
	if($id<=0) {
		message('需要先选择一个表单',fm_wurl($do,$ac,'display',array()),'info');
	}
	$needs=pdo_fetch("SELECT * FROM ".tablename('fm453_shopping_needs')." WHERE id=:id ",array(':id'=>$id));

	$template=$needs['template'];
	$cols=array();
	$fields_file = MODULE_ROOT.'/template/mobile/'.$appstyle.'needs'.'/detail/'.$template.'/fields.php';
	if(!file_exists($fields_file)) {
		$fields_file = MODULE_ROOT.'/template/mobile/default/needs/detail/'.$template.'/fields.php';
	}
	if(file_exists($fields_file)) {
		require_once $fields_file;
	}
	$cols['default']=array(
		'setfor'=>'会员',
		'name'=>'姓名',
		'mobile'=>'手机'
	);

	$condition='';
	$params=array();
	$isbytime=intval($_GPC['isbytime']);
	if (!empty($_GPC['date'])) {
		$starttime = strtotime($_GPC['date']['start']);
		$endtime = strtotime($_GPC['date']['end']) + 86399;
	} else {
		$starttime = strtotime('-3 month');
		$endtime = time();
	}
	if($isbytime) {
		$condition .= " AND `createtime` >= :starttime AND `createtime` < :endtime";
		$params[':starttime'] = $starttime;
		$params[':endtime'] = $endtime;
	}
	$keyword = trim($_GPC['keyword']);
	if (!empty($keyword)) {
		$temp_sns = pdo_fetchall("SELECT sn FROM " . tablename('fm453_shopping_needs_data') . " WHERE nid=:nid AND `value` LIKE :value " , array(':nid'=>$id,':value'=>'%'.$keyword.'%'));
		$_temp_sns ="(";
		foreach($temp_sns as $t_s){
			$_temp_sns .= $t_s['sn'].",";
		}
		$_temp_sns = substr($_temp_sns,0,-1);
		$_temp_sns .=")";
		if(intval($keyword)>0){
		    $condition .= " AND (";
		    $condition .= " sn IN ".$_temp_sns;
		    $condition .= " OR ";
		    $condition .= " `setfor` = :setfor";
		    $condition .= " )";
		    $params[':setfor'] = intval($keyword);
		}else{
		    $condition .= " AND";
		    $condition .= " sn IN ".$_temp_sns;
		}

		unset($temp_sns);
		unset($_temp_sns);
	}

	$status = empty($_GPC['status']) ? 0 : intval($_GPC['status']);
	$status = intval($status);
	if ($status != 0) {
		$condition .= " AND `status` = :status";
		$params[':status'] = $status;
	}
	//判断是否进行了搜索
	$issearch = false;
	if($keyword){
		$issearch = true;
	}

	if($issearch){
		$showorder=" ORDER BY createtime DESC ";
		$params[':nid']=$id;
		$needs_sn = pdo_fetchall("SELECT sn FROM " . tablename('fm453_shopping_needs_data') . " WHERE nid=:nid " . $condition . $showorder, $params,'sn');

		$needs_sn = array_unique($needs_sn);
		$needs_data = array();
		if($needs_sn) {
			foreach($needs_sn as $key=>$_sn){
				$params[':sn']=$key;
				$needs_data[$key] = pdo_fetchall("SELECT * FROM " . tablename('fm453_shopping_needs_data') . " WHERE nid=:nid " . $condition ." AND sn =:sn ". $showorder , $params);
			}
		}

		$list=array();
		$keys=array();
		$users=$setfors=array();
		if(is_array($needs_data) && !empty($needs_data)) {
			foreach($needs_data as $key=>$dvalue){
				$keys[$key]=$key;
				$setfor=$dvalue[0]['setfor'];
				$setfors[$setfor]=$setfor;
				$list[$key]=array();
				$list[$key]['setfor']=$setfor;
				$list[$key]['status']=$dvalue[0]['status'];
				$list[$key]['createtime']=$dvalue[0]['createtime'];
				$list[$key]['user_info']=fmMod_member_query($setfor)['data'];
				foreach($dvalue as $i=>$value){
					$title=$value['title'];
					if($cols[$needs['template']][$title] || $title=='name' || $title=='mobile') {
						$list[$key][$title]=$value['value'];
					}elseif($cols['default'][$title]){
						unset($dvalue[$i]);
					}
				}
				unset($dvalue);
			}
		}
		$total=count($list);
	}else{
		$condition='';
		$params=array();
		$params[':nid']=$id;
		$showorder=" ORDER BY uniacid ASC ,id DESC";
		$limit=" LIMIT ".($pindex-1)*$psize.",".$psize;
		$condition .= " AND `createtime` >= :starttime AND `createtime` < :endtime";
		$params[':starttime'] = $starttime;
		$params[':endtime'] = $endtime;
		$list=pdo_fetchall("SELECT id,nid,from_user,fromuid,shareid,ordersn,price,paytype,transid,status,remark,reply FROM " . tablename('fm453_shopping_needs_order') . " WHERE nid=:nid " . $condition . $showorder .$limit, $params,'ordersn');
		$total = pdo_fetchcolumn("SELECT COUNT(id) FROM " . tablename('fm453_shopping_needs_order') . " WHERE nid=:nid " . $condition . $showorder , $params);
		foreach($list as $k_sn=>&$v){
			$key = $k_sn;
			unset($params);
			$params=array();
			$params[':nid']=$id;
			$params[':sn']=$key;
			$showorder=" ORDER BY createtime DESC ";
			$needs_data = pdo_fetchall("SELECT * FROM " . tablename('fm453_shopping_needs_data') . " WHERE nid=:nid  AND sn =:sn ". $showorder , $params);
			$setfor = $v['fromuid'];
			$v['setfor']=$setfor;
			$v['user_info']=fmMod_member_query($setfor)['data'];
			$v['sn'] = $v['ordersn'];
			if(!empty($needs_data)) {
				foreach($needs_data as $value){
					$title=$value['title'];
					if($cols[$needs['template']][$title] || $title=='name' || $title=='mobile') {
						$v[$title]=$value['value'];
					}elseif($cols['default'][$title]){
						unset($value);
					}
				}
			}
		}
	}

	$pager = pagination($total, $pindex, $psize);
	//导出数据START
	if ($_GPC['export']) {
		/* 输入到CSV文件 */
		$html = "\xEF\xBB\xBF";//UTF8标记
		$html .= "\n";
		/* 输出表头 */
		$filter = array(
			'sn' => '分类编号',
			'uniacid' => '公号',
			'platname' => '平台',
		);
		foreach($FM_COLS[$needs_tpl] as $k=>$v){
		    $filter[$k] = $v;
		}
		foreach ($filter as $key => $title) {
			$html .= $title . "\t";
		}
		$html .= "\n";

		$outdata = $list;

		foreach ($outdata as $k => $v) {
			foreach ($filter as $key => $title) {
				if($key=="platname") {
					$default= @uni_account_default($v['uniacid']);
					$html .= $default['name']."\t";
				}elseif($key=="sn") {
					$html .= "'".$v['sn']."\t";
				}elseif($key=="team") {
				    $_temp_ks = array('name'=>'姓名','age'=>'年龄','mobile'=>'手机','sex'=>'性别','idcard'=>'身份证号','clothessize'=>'服装尺寸');
				    $_temp_strs = '';
				    $v[$key] = iunserializer($v[$key]);
				    foreach($v[$key] as $k_v_team=>$v_v_team){
				        $_temp_str = '';
				        foreach($v_v_team as $kk_team=>$vv_team){
				            if($kk_team=='sex'){
				                $_temparray= array('0'=>'未知','1'=>'男','2'=>'女');
				                $_temp_str .= $_temp_ks[$kk_team].':'.$_temparray[$vv_team].',';
				            }else{
				                $_temp_str .= $_temp_ks[$kk_team].':'.$vv_team.',';
				            }
				        }
				       $_temp_strs .= $_temp_str;
				    }
					$html .= $_temp_strs."\t";
				}else {
					$html .= $v[$key] . "\t";
				}
			}
			$html .= "\n";
		}

		$sheetname = $shopname;
		if($needs['title']){
		    $sheetname = $needs['title'];
		}
		/* 输出CSV文件 */
		header("Content-type:text/xls");
		header("Content-Type:application/force-download");
		header("Content-Type:application/vnd.ms-execl");
		header("Content-Type:application/octet-stream");
		header("Content-Type:application/download");
		header("Content-Transfer-Encoding:binary");
		header("Content-Disposition:attachment; filename=".$sheetname."表单数据.xls");
		echo $html;

		$dologs=array(
			'url'=>$_W['siteurl'],
			'description'=>'后台导出有求必应表单数据',
		);
		fmMod_log_record($platid,$_W['uniacid'],$_W['uid'],$fanid,$openid,'fm453_shopping_needs',$id,'export',$dologs);
		unset($dologs);
		//给管理员发个微信消息
		fmMod_notice($settings[manageropenids],array(
			'header'=>array('title'=>'事件通知','value'=>'后台导出有求必应表单数据'),
			'user'=>array('title'=>'操作人','value'=>$_W['username'].'('.$_W['uid'].')'),
			'url'=>array('title'=>'执行网址','value'=>$_W['siteurl']),
		));
		exit();
	}
//导出数据END
	include $this->template($fm453style.$do.'/453');
}
elseif($operation=='reply'){
	$id=intval($_GPC['id']);
	if($id<=0) {
		message('需要先选择一个表单',fm_wurl($do,$ac,'display',array()),'info');
	};

	$sn=intval($_GPC['sn']);

	$link_preview =  fm_murl('appwebneeds','detail','index',array('id' => $id,'sn'=>$sn));
	$qrcode=fmFunc_qrcode_name_m($platid,'appwebneeds','detail','index',$id,0,0);
	fmFunc_qrcode_check($qrcode,$link_preview);
	$qrcode_preview=tomedia($qrcode);

	$needs=pdo_fetch("SELECT * FROM ".tablename('fm453_shopping_needs')." WHERE id=:id ",array(':id'=>$id));
	if($sn<=0) {
		message('需要先选择一条表单数据',fm_wurl($do,$ac,'detail',array('id'=>$id)),'info');
	}
	$template=$needs['template'];
	$cols=array();
	$fields_file = MODULE_ROOT.'/template/mobile/'.$appstyle.'needs'.'/detail/'.$template.'/fields.php';
	if(!file_exists($fields_file)) {
		$fields_file = MODULE_ROOT.'/template/mobile/default/needs/detail/'.$template.'/fields.php';
	}
	if(file_exists($fields_file)) {
		require_once $fields_file;
	}

	$needs_data=pdo_fetchall("SELECT * FROM ".tablename('fm453_shopping_needs_data')." WHERE nid=:nid  AND sn=:sn",array(':nid'=>$id,':sn'=>$sn),'title');
	if(!empty($needs_data)){
		foreach($needs_data as $key=>$form){
			//$needs_data[$key]=$form['value'];
		}
	}

	$setfor=$needs_data['name']['setfor'];
	$user_info=fmMod_member_query($setfor)['data'];

	if(checksubmit()) {
		$fields=fmFunc_tables_fields('needs_data',$dtype='shopping');
		$data=array();
		foreach($fields as $i=>$key){
			$data[$key]=$_GPC[$key];
		}

		message('回复处理成功',fm_wurl($do,$ac,'data',array('id'=>$id,'sn'=>$sn)),'success');
	}
	include $this->template($fm453style.$do.'/453');
}
else {
	message('请求方式不存在');
}