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
 * @remark 文章详情管理
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;
load()->func('tpl');
load()->model('account');//加载公众号函数
fm_load()->fm_model('category'); //分类管理模块
fm_load()->fm_model('article'); //文章管理模块
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
$pindex=max(1,intval($_GPC['page']));
$psize=(intval($_GPC['psize'])>5) ? intval($_GPC['psize']) : 5;//最少显示5条主数据

$uniacid=$_W['uniacid'];
$plattype=$settings['plattype'];
$platids=fmFunc_getPlatids();//获取平台模式关联的公众号商户ID参数
$platid=$_W['uniacid'];
$oauthid=$platids['oauthid'];
$fendianids=$platids['fendianids'];
$supplydianids=$platids['supplydianids'];
$blackids=$platids['blackids'];

$gtpltype=fmMod_article_types();//列出全部文章模型清单

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

//获取商城文章分类
$sql = 'SELECT * FROM ' . tablename('fm453_shopping_category') . $condition." AND setfor LIKE 'article' ".' ORDER BY `psn`, `displayorder` DESC, `createtime` ASC';
$category = pdo_fetchall($sql, $params, 'sn');
if (!empty($category)) {
	$parent = $children = array();
	foreach ($category as $cid => $cate) {
		if (!empty($cate['psn'])) {
			$children[$cate['psn']][] = $cate;
		} else {
			$parent[$cate['sn']] = $cate;
		}
	}
}

//获取系统文章分类栏目
$sysCategory = pdo_fetchall("SELECT * FROM ".tablename('site_category')." WHERE uniacid = '{$_W['uniacid']}' AND enabled=1 ORDER BY displayorder DESC,id DESC");
if (!empty($sysCategory)) {
	$sysParent = $sysChildren = array();
	foreach ($sysCategory as $cid => $cate) {
		if (!empty($cate['parentid'])) {
			$sysChildren[$cate['parentid']][] = $cate;
		} else {
			$sysParent[$cate['id']] = $cate;
		}
	}
}
//默认数据
$allDefault=array();

if ($operation == 'display') {
	$id = intval($_GPC['id']);
	$admins=array();
	$members=array();
	if($id>0) {
		$result=fmMod_article_detail_m($id);
		if($result['result']) {
			$item=$result['data'];
			$sn=$item['sn'];
			$tpl=$item['a_tpl'];
			//文章管理页面二维码
			$qrcode=  fmFunc_qrcode_name_w($platid,$do,$ac,$operation,$id);
			fmFunc_qrcode_check($qrcode,$_W['siteurl']);
			$qrcode_admin=tomedia($qrcode);
			//文章预览页面二维码
			$link_preview =  fm_murl('article','detail','index',array('id' => $id));
			$qrcode=fmFunc_qrcode_name_m($platid,'article','detail','index',$id,0,0);
			fmFunc_qrcode_check($qrcode,$link_preview);
			$qrcode_preview=tomedia($qrcode);

			$openids=$item['goodadm'];
			if($openids)
			{
				$openids = explode(',',$openids);
				$openids = array_unique($openids);
				foreach($openids as $o_k=>$openid)
				{
					$admin=array();
					$member=array();
					$member_info= fmMod_member_query('',$openid);
					$member = $admin['info'] = $member_info['data'];
					$mid = $member_info['data']['uid'];
					$admin['settings'] = fmMod_member_settings($mid,$item['uniacid']);
					$admins[$o_k]=$admin;
					$members[$o_k]=$member;
				}
			}

			//预置内容
			if($item['a_tpl'] == 'member' || $item['a_tpl'] == 'temp1')
			{
				$allDefault['content'] =$admins[0]['settings']['content'];
			}
		}else{
			message('未获取到文章信息：'.$result['msg'],referer(),'fail');
		}
	}else{
		$item=array();
		$item['a_tpl']='default';
		// 添加额外条件判断并初始化数据
		$newtpl=$_GPC['a_tpl'];
		$openid=$_GPC['openid'];
		if($newtpl=='member') {
			$item['a_tpl']=$newtpl;
			$item['goodadm']=$openid;

			$member_info= fmMod_member_query('',$openid);	//会员基础资料
			$member_info = $member_info['data'];
			$member_uid=$member_info['uid'];
			fmMod_member_check($openid);
			$agent_info = fmMod_member_query($member_uid);	//会员代理人身份信息
			$agent_info = $agent_info['data'];
			$member_settings = fmMod_member_settings($member_uid,$_W['uniacid']);	//会员额外配置信息

			$admins[0]=array("info"=>$member_info,"settings"=>$member_settings);
			$members[0] = $member_info;
			$allDefault['content'] = $member_settings['content'];
			$item['title']= !empty($member_info['realname']) ? $member_info['realname'] : $agent_info['realname'];
			$item['thumb']=$member_info['avatar'];
			$item['keywords']=$agent_info['keyword'];
			$item['content']=$agent_info['content'];
		}
	}

	//将会员信息预置入会员内容模板中
	$langs=array();
	$langs['realname_value']=$admins[0]['info']['realname'];
	$langs['passedname_value']=$admins[0]['settings']['passedname'];
	$langs['birth_place_value']=$admins[0]['settings']['birth_province']."【省】".$admins[0]['settings']['birth_city']."【市】".$admins[0]['settings']['birth_country']."【县/区】";
	$langs['birth_where_value']=$admins[0]['settings']['birth_address'];
	$langs['now_place_value']=$admins[0]['settings']['now_province']."【省】".$admins[0]['settings']['now_city']."【市】".$admins[0]['settings']['now_country']."【县/区】";
	$langs['company_value']=$admins[0]['settings']['company'];
	$langs['job_value']=$admins[0]['settings']['job'];
	$langs['business_scope_value']=$admins[0]['settings']['business_scope'];
	$langs['company_address_value']=$admins[0]['settings']['company_address'];
	$langs['mobile_value']=$admins[0]['info']['mobile'];
	$langs['qq_value']=$admins[0]['settings']['qq'];
	$langs['wx_value']=$admins[0]['settings']['wxhao'];
	$langs['wxname_value']=$admins[0]['info']['nickname'];
	$langs['signature_value']=$admins[0]['settings']['sign'];
	$langs['wxhao_value']=$admins[0]['settings']['wxhao'];
	$langs['other_info_value']=" ";
	$langs['avatar_value']=tomedia($admins[0]['info']['avatar']);
	$langs['qrcode_value']=tomedia($admins[0]['settings']['qrcode_wx']);

	foreach($langs as $key => $value)
	{
		$search = "{". $key ."}";
		$replace = $value;
		$subject = $allDefault['content'];
		$allDefault['content'] = str_replace($search, $replace, $subject);
	}

	$allDefault['content'] = addslashes($allDefault['content']);	//字符串转义(添加反／)
	$allDefault['content'] = fmFunc_tpl_oneLine($allDefault['content']);	//将字符串去全部空格，压缩成一行

	//查询点赞记录
	$dianzanList = fmMod_article_dzQuery($uid=null,'',$aid=$id,$multi=true,$order=null);

	//查询分享记录
	$shareList = fmMod_article_fxQuery($uid=null,'',$aid=$id,$multi=true,$order=null);

	//公众号的会员分组设置
	$mc_groups=mc_groups($_W['uniacid']);

	include $this->template($fm453style.$do.'/453');
}
elseif ($operation == 'stock') {
	$id = intval($_GPC['id']);
	message('功能完善中，暂不开放','referer','info');
	if($id>0) {
		$result=fmMod_article_detail_all($id);
		if($result['result']) {
			$item=$result['data'];
		}else{
			message('未获取到文章信息：'.$result['msg'],'referer','fail');
		}
	}else{
	message('需要先选定一篇文章',fm_wurl($do,'list','display',array()),'fail');
	}
	include $this->template($fm453style.$do.'/453');
}
elseif ($operation == 'price') {
	$id = intval($_GPC['id']);
			message('功能完善中，暂不开放','referer','info');
	if($id>0) {
		$result=fmMod_article_detail_all($id);
		if($result['result']) {
			$item=$result['data'];
		}else{
			message('未获取到文章信息：'.$result['msg'],'referer','fail');
		}
	}else{
		message('需要先选定一个文章',fm_wurl($do,'list','display',array()),'error');
	}
	include $this->template($fm453style.$do.'/453');
}
elseif ($operation == 'modify') {
	$id = intval($_GPC['id']);
	$sn = $_GPC['sn'];
	if (checksubmit('submit_withSysNotice') || checksubmit('submit_withoutNotice')) {
		if (empty($_GPC['title'])) {
			message('请输入文章标题！');
		}
		//从字段清单中罗列前台对应的请求变量
		$table_goods_column=fmFunc_tables_fields('article','site');
		$data=array();
		foreach($table_goods_column as $key){
			$data[$key]=$_GPC[$key];
		}
		$articleData=array(
			'title'=>$_GPC['title'],
			'uniacid'=>$_W['uniacid'],
			'iscommend'=>intval($_GPC['iscomment']),
			'pcate'=>intval($_GPC['sysCategory']['parentid']),
			'ccate'=>intval($_GPC['sysCategory']['childid']),
			'ishot'=>intval($_GPC['ishot']),
			'description'=>$_GPC['description'],
			'content'=>htmlspecialchars_decode($_GPC['content']),
			'thumb'=>$_GPC['thumb'],
			'displayorder'=>intval($_GPC['displayorder']),
			'createtime'=>$_W['timestamp'],
		);
		//修复一些请求变量（在字段清单中但获取方式特别）
		$data['timestart']=strtotime($data['timestart']);//开始时间
		$data['timeend']=strtotime($data['timeend']);//结束时间
		//关联作者
		$data['goodadm'] = trim($data['goodadm']);
		$_temp = explode(',',$data['goodadm']);
		$_temp = array_unique($_temp);
		$_temp = array_filter($_temp);
		$data['goodadm'] = implode(',',$_temp);

		//附加一些额外的请求变量(不在字段清单中的)
		$data['category']=$_GPC['category'];
		$data['statuscode']='64';//状态码 （用于后期权限流程判断）
		if ($id<=0) {
			pdo_insert('site_article', $articleData);
			$data['sn']=pdo_insertid();
			$result=fmMod_article_new_basic($data,$platid);
			$id = $result['data'];
			//写入操作日志START
			$dologs=array(
				'url'=>$_W['siteurl'],
				'description'=>'商城后台创建新文章；',
				'addons'=>$data,
			);
			fmMod_log_record($platid,$_W['uniacid'],$_W['uid'],$fanid,$openid,'fm453_site_article',$id,'create',$dologs);
			unset($dologs);
			//写入操作日志END
			//给管理员发个微信消息
			require_once MODULE_ROOT.'/core/msgtpls/tpls/article.php';
			//发微信模板通知
			$postdata = $tplnotice_data['task']['member']['manage']['admin'];
			$result= fmMod_notice_tpl($postdata);
			if(!$result) {
				require MODULE_ROOT.'/core/msgtpls/msg/article.php';
				$postdata = $notice_data['new']['admin'];
				$result= fmMod_notice($settings['manageropenids'],$postdata);
			}
		}
		elseif($id>0) {
			$sn =pdo_fetchcolumn('SELECT sn FROM '.tablename('fm453_site_article').' WHERE id= :id',array(':id'=>$id));
			unset($articleData['createtime']);
			$_hasit =pdo_fetchcolumn('SELECT id FROM '.tablename('site_article').' WHERE id= :id',array(':id'=>$sn));
			if($_hasit){
				pdo_update('site_article', $articleData,array('id'=>$sn));
			}
			//直接将提交的数据请求至文章模型进行加工处理
			$result=fmMod_article_modify_basic($id,$data);

			//写入操作日志START
			$dologs=array(
				'url'=>$_W['siteurl'],
				'description'=>'商城后台更新文章；',
				'addons'=>$data,
			);
			fmMod_log_record($platid,$_W['uniacid'],$_W['uid'],$fanid,$openid,'fm453_site_article',$id,'update',$dologs);
			unset($dologs);
			//写入操作日志END

			//检测是否通知到管理员(更新文单时如果选择非静默提交)
			if(checksubmit('submit_withSysNotice')){
				//发微信模板通知
				require_once MODULE_ROOT.'/core/msgtpls/tpls/article.php';
				$postdata = $tplnotice_data['detail']['modify']['admin'];
				$result= fmMod_notice_tpl($postdata);
				if(!$result) {
					require MODULE_ROOT.'/core/msgtpls/msg/article.php';
					$postdata = $notice_data['detail']['modify']['admin'];
					$result= fmMod_notice($settings['manageropenids'],$postdata);
				}
			}
		}
		//至此，文章$id已经一定存在，其他相关表根据此值进行记录
	}
	if($data['goodadm']){
		//给文章对应的作者或者会员发个微信消息
		if($data['goodadm'] !=$settings['manageropenids']) {
			$postdata = $tplnotice_data['detail']['modify']['goodadm'];
			$result= fmMod_notice_tpl($postdata);
			if(!$result) {
				require MODULE_ROOT.'/core/msgtpls/msg/article.php';
				$postdata = $notice_data['detail']['modify']['admin'];
				$result= fmMod_notice($data['goodadm'],$postdata);
			}
		}
	}
	message('文章编辑/更新成功！',fm_wurl('article','detail','display',array('id'=>$id)),'success');
}
