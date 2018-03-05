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
 * @remark	微文章管理模型
 */
defined('IN_IA') or exit('Access Denied');
//文章模型清单
function fmMod_article_types(){
	$gtpltype=array();
	$gtpltype['default']=array(
		'name'=>'标准文章',
		'status'=>'127',
		);
	$gtpltype['member']=array(
		'name'=>'会员介绍',
		'status'=>'127',
	);
	$gtpltype['business']=array(
		'name'=>'企业介绍',
		'status'=>'127',
	);
	$gtpltype['zhaopin']=array(
		'name'=>'招聘信息',
		'status'=>'127',
	);
	$gtpltype['notice']=array(
		'name'=>'公告通知',
		'status'=>'127',
	);
	$gtpltype['temp1']=array(
		'name'=>'临时-1',
		'status'=>'127',
	);
	$gtpltype['temp2']=array(
		'name'=>'临时-2',
		'status'=>'127',
	);
	$gtpltype['temp3']=array(
		'name'=>'临时-3',
		'status'=>'127',
	);
	return $gtpltype;
}

//获取根据系统文章进行导入式查询,返回本系统中的id（不覆盖）
function fmMod_article_import($articleid){
	global $_GPC;
	global $_W;
	global $_FM;
	$return=array();
	$articleid=intval($articleid);
	$result = pdo_fetchcolumn("select id from " . tablename('fm453_site_article') . " where sn = :articleid ",array(':articleid'=>$articleid));
	if($result) {
		$id=$result;
	}else{
		$public_keys=array(
		// 商城表=》系统微站表
			'sn'=>'id',
			'title'=>'title',
			'uniacid'=>'uniacid',
			'isrecommand'=>'isrecommand',
			'ishot'=>'ishot',
			'viewcount'=>'click',
			'updatetime'=>'createtime',
			'displayorder'=>'displayorder'
		);
		$detail = pdo_fetch("select * from " . tablename('site_article') . " where id = :id ",array(':id'=>$articleid));
		$data=array();
		foreach($public_keys as $nkey=>$okey){
			$data[$nkey]=$detail[$okey];
		}
		$data['statuscode']='64';
		pdo_insert('fm453_site_article',$data);
		$id = pdo_insertid();
	}
	return $id;
}

//获取某条图文详情（系统记录id）,WEB端
function fmMod_article_detail_w($id){
	global $_GPC;
	global $_W;
	global $_FM;
	//header("Content-type:text/html;charset=utf-8");//加上网页字符集强制设置，避免中文传输乱码
	$return=array();
	$newid=pdo_fetchcolumn("select id from " . tablename('fm453_site_article') . " where sn = :id ",array(':id'=>$id));
	if(!$newid) {
		$newid=fmMod_article_import($articleid);
	}
	$detail = pdo_fetch("select * from " . tablename('fm453_site_article') . " where id = :id ",array(':id'=>$newid));
	return $detail;
}

//获取某条图文详情（商城记录id,系统记录sn），APP端
function fmMod_article_detail_m($id=NULL,$sn=NULL){
	global $_GPC;
	global $_W;
	global $_FM;
	//header("Content-type:text/html;charset=utf-8");//加上网页字符集强制设置，避免中文传输乱码
	$return=array();
	$id=intval($id);
	$sn=intval($sn);
	if(!$sn && !$id) {
		$return['result']=FALSE;
		$return['msg']="未传入有效的文章序号";
		return $return;
	}else{
		if($id>0 && empty($sn)){
			$addons=pdo_fetch("select * from " . tablename('fm453_site_article') . " where id = :id ",array(':id'=>$id));
			$sn = $addons['sn'];
			$detail=pdo_fetch("select * from " . tablename('site_article') . " where id = :id ",array(':id'=>$sn));
		}
		elseif($sn>0 && empty($id)){
			$detail=pdo_fetch("select * from " . tablename('site_article') . " where id = :id ",array(':id'=>$sn));
			$addons=pdo_fetch("select * from " . tablename('fm453_site_article') . " where sn = :sn ",array(':sn'=>$sn));
			if(!$addons) {
				$newid=fmMod_article_import($sn);
				$detail=array();
				$detail['sn']=$sn;
				$detail['id']=$newid;
			}
		}elseif( $id>0 && !empty($sn)) {
			$addons=pdo_fetch("select * from " . tablename('fm453_site_article') . " where id = :id ",array(':id'=>$id));
			if($sn != $addons['sn']){
				$return['result']=FALSE;
				$return['msg']="文章序号id与sn不匹配";
			}else{
				$detail = pdo_fetch("select * from " . tablename('site_article') . " where id = :id ",array(':id'=>$sn));
			}
		}
	}
	$addons['a_tpl'] = !$addons['a_tpl'] ? 'default' : $addons['a_tpl'];
	unset($addons['title']);
	if($addons && $detail) {
		$addons['sysPcate']=$detail['pcate'];
		$addons['sysCcate']=$detail['ccate'];
		foreach($addons as $key=>$addon){
			$detail[$key]=$addon;
		}
		if(!$detail['keywords']) {
			$detail['keywords']=$detail['title'];
		}
		$return['result']=TRUE;
		$return['data']=$detail;
		return$return;
	}
}

//获取某条图文基础信息（从系统表读取）
function fmMod_article_basic($id){
	global $_GPC;
	global $_W;
	global $_FM;
	//header("Content-type:text/html;charset=utf-8");//加上网页字符集强制设置，避免中文传输乱码
	$id=intval($id);
	$return=array();
	$detail = pdo_fetch("select * from " . tablename('site_article') . " where id = :id ",array(':id'=>$id));
	return $detail;
}

//新建文章-基础数据-写入模块微站文章表
function fmMod_article_new_basic($data,$platid) {
	global $_GPC;
	global $_W;
	global $_FM;
	header("Content-type:text/html;charset=utf-8");//加上网页字符集强制设置，避免中文传输乱码
	$return=array();
	if(!is_array($data)){
		$return['result']=FALSE;
		$return['msg']='传入数据非法，需是数组';
		return $return;
	}
	//前置判断
	if (empty($data['title'])) {
		//message('请输入文章标题！','referer','fail');
	}
	unset($data['id']);//从数组中踢除id项
	unset($data['sysCategory']);//踢除系统栏目分类项
	//数据拆解
	if(empty($data['category'])){
		$data['category'] = array();
	}
	$data['pcate']=intval($data['category']['parentid']);//父分类
	$data['ccate']=intval($data['category']['childid']);//子分类

	//空字段处理
	$data['keywords']= empty($data['keywords']) ? $data['title'].$data['description'] : $data['keywords'];
	//清理非库内字段
	unset($data['category']);//不是数据库字段，不需入库
	$fields=fmFunc_tables_fields('article','site');
	foreach($data as $field=>$d){
		if(!in_array($field,$fields)){
			unset($data[$field]);
		}
	}
	if(count($data)==0){
		$return['result']=FALSE;
		$return['msg']='传入数据无效';
		return $return;
	}
	//数据格式处理
	$data['uniacid']=$_W['uniacid'];//文章所属公众平台
	$data['kefuphone']=trim($data['kefuphone']);
	$data['displayorder']=intval($data['displayorder']);//排序，DESC
	$data['isrecommand']=intval($data['isrecommand']);//是否推荐
	$data['ishot']=intval($data['ishot']);//是否热销
	$data['isnew']=intval($data['isnew']);//是否新品
	$data['isdiscount']=intval($data['isdiscount']);//是否折扣
	$data['istime']=intval($data['istime']);//是否限时
	$data['timestart']=intval($data['timestart']);//开始时间
	$data['timeend']=intval($data['timeend']);//结束时间

	$data['total']=intval($data['total']);//虚拟库存
	$data['stock']=intval($data['stock']);//真实库存

	$data['status']=intval($data['status']);//在售状态 1上架0下架

	$data['usermaxbuy']=intval($data['usermaxbuy']);//单个用户的最大购买数量
	$data['sales']=intval($data['sales']);//虚拟销量
	$data['realsales']=intval($data['realsales']);//真实销量
	$data['commission']=intval($data['commission']);//1级佣金比例
	$data['commission2']=intval($data['commission2']);//2级佣金比例
	$data['commission3']=intval($data['commission3']);//3级佣金比例

	$data['shareable']=intval($data['shareable']);	//是否允许转发
	$data['directReadable']=intval($data['directReadable']);	//转发是否直接可阅读

	$data['marketprice']=sprintf('%.2f', $data['marketprice']);//销售价
	$data['cankaoprice']=sprintf('%.2f', $data['cankaoprice']);//市场参考价
	$data['costprice']=sprintf('%.2f', $data['costprice']);//成本价
	$data['originalprice']=sprintf('%.2f', $data['originalprice']);//原价

	//数据有效性处理
	$data['lastsales']=0;//上次填写的虚拟销量
	if($data['realsales']<0){
		$data['realsales']= 0;//真实销量
	}

	if($data['istime']==1){
		if($data['timestart']=0 && $data['timeend']=0){
			$data['istime']=0;//未设置开始及结束时间时，取消限时，下架文章
			$data['status'] = 0;
		}elseif($data['timestart']>0 && $data['timeend']>0){
			if($data['timestart']>=$data['timeend']){
				$data['istime']=0;//开始时间晚于结束时间，取消限时，下架文章
				$data['status'] = 0;
			}
		}
	}

	//特别字段处理
	$data['statuscode']=intval($data['statuscode']);//状态码 （用于后期权限流程判断）
	if($platid !=$_W['uniacid']){
		$data['status'] = 0;//当前公众号非平台主体时，文章不直接上架； 需经平台进行审核
		$data['statuscode'] = 0;//文章权限状态调为创建待审
	}

	$data['updatetime']=TIMESTAMP;//记录更新时间

	$result=pdo_insert('fm453_site_article', $data);
	if($result){
		$return['result']=TRUE;
		$return['data']=pdo_insertid();
		return $return;
	}else{
		$return['result']=FALSE;
		$return['msg']='插入数据失败';
		return $return;
	}
}

//编辑更新文章基础数据
function fmMod_article_modify_basic($id,$data) {
	global $_G,$_W,$_FM;
	header("Content-type:text/html;charset=utf-8");//加上网页字符集强制设置，避免中文传输乱码
	$return=array();
	$id=intval($id);
	if($id<=0){
		$return['result']=FALSE;
		$return['msg']='未传入文章ID';
		return $return;
	}
	$fields=fmFunc_tables_fields('article','site');
	$fields = implode(',',$fields);
	$goods=pdo_fetch('SELECT '.$fields.' FROM '.tablename('fm453_site_article').' WHERE id = :id',array(':id'=>$id));
	if(empty($goods)){
		$return['result']=FALSE;
		$return['msg']='未获得文章数据';
		return $return;
	}
	$fields=explode(',',$fields);
	if(!is_array($data)){
		$return['result']=FALSE;
		$return['msg']='传入数据非法，需是数组';
		return $return;
	}
	//数据拆解
	if(empty($data['category'])){
		$data['category'] = array();
	}
	$data['pcate']=intval($data['category']['parentid']);//父分类
	$data['ccate']=intval($data['category']['childid']);//子分类
	//空字段处理
	$data['keywords']= empty($data['keywords']) ? $data['title'].$data['description'] : $data['keywords'];

	//清理非库内字段
	unset($data['category']);//不是数据库字段，不需入库
	foreach($data as $field=>$d){
		if(!in_array($field,$fields)){
			unset($data[$field]);
		}
	}
	if(count($data)==0){
		$return['result']=FALSE;
		$return['msg']='传入数据无效';
		return $return;
	}

	//数据格式处理
	$data['kefuphone']=trim($data['kefuphone']);
	$data['displayorder']=intval($data['displayorder']);//排序，DESC
	$data['isrecommand']=intval($data['isrecommand']);//是否推荐
	$data['ishot']=intval($data['ishot']);//是否热销
	$data['isnew']=intval($data['isnew']);//是否新品
	$data['isdiscount']=intval($data['isdiscount']);//是否折扣
	$data['istime']=intval($data['istime']);//是否限时
	$data['timestart']=intval($data['timestart']);//开始时间
	$data['timeend']=intval($data['timeend']);//结束时间
	$data['total']=intval($data['total']);//虚拟库存
	$data['stock']=intval($data['stock']);//真实库存
	$data['status']=intval($data['status']);//在售状态 1上架0下架
	$data['maxbuy']=intval($data['maxbuy']);//单次购买的最大数量
	$data['usermaxbuy']=intval($data['usermaxbuy']);//单个用户的最大购买数量

	$data['sales']=intval($data['sales']);//虚拟销量
	$data['realsales']=intval($data['realsales']);//真实销量
	$data['commission']=intval($data['commission']);//1级佣金比例
	$data['commission2']=intval($data['commission2']);//2级佣金比例
	$data['commission3']=intval($data['commission3']);//3级佣金比例

	$data['shareable']=intval($data['shareable']);	//是否允许转发
	$data['directReadable']=intval($data['directReadable']);	//转发是否直接可阅读

	$data['marketprice']=sprintf('%.2f', $data['marketprice']);//销售价
	$data['cankaoprice']=sprintf('%.2f', $data['cankaoprice']);//市场参考价
	$data['costprice']=sprintf('%.2f', $data['costprice']);//成本价
	$data['originalprice']=sprintf('%.2f', $data['originalprice']);//原价

	//数据有效性处理
	$data['lastsales']=$goods['sales'];//上次填写的虚拟销量
	if($data['realsales']<0){
		$data['realsales']= intval($goods['realsales']);//真实销量，如输入为负，则继承修改前的真实销量
	}
	unset($data['id']);//从数组中踢除id项
	unset($data['sn']);//序号不可被直接修改
	unset($data['uniacid']);//不允许直接修改文章所属公众平台
	if($data['istime']==1){
		if($data['timestart']==0 && $data['timeend']==0){
			$data['istime'] = 0;//未设置开始及结束时间时，取消限时，下架文章
			$data['status'] = 0;
		}elseif($data['timestart']>0 && $data['timeend']>0){
			if($data['timestart']>$data['timeend']){
				$data['istime']=0;//开始时间晚于结束时间，取消限时，下架文章
				$data['status'] = 0;
			}
		}
	}
	//特别字段处理
	$data['statuscode']=intval($data['statuscode']);//状态码 （用于后期权限流程判断）
	$data['updatetime']=TIMESTAMP;//记录更新时间
	$result=pdo_update('fm453_site_article', $data,array('id'=>$id));
	if($result){
		$return['result']=TRUE;
		return $return;
	}else{
		$return['result']=FALSE;
		$return['msg']='插入数据失败';
		return $return;
	}
}

/*
*查询对应用户的文章点赞记录
@uid		会员ID，默认针对前台，为当前登陆会员
@aid		文章ID，未传入时则查询全部文章记录
@openid		粉丝openid
@multi  用于限制查询结果量；默认取单条数据
@orderby  结果排序方式，按记录号DESC/ASC，默认降序(取最新的)
*/
function fmMod_article_dzQuery($uid=null,$openid=null,$aid=null,$multi=null,$order=null){
	global $_G;
	global $_W;
	global $_FM;
	$fields = "id, artid, arttitle, openid, uid, avatar, viewtime";
	$sql = " SELECT  ".$fields." FROM ".tablename('fm453_site_article_viewlog');

	$condition = " WHERE ";
	$params = array();
	$condition .= "do= :do";
	$params[':do']='dianzan';
	if($uid){
		$condition .= " AND ";
		$condition .= "uid= :uid";
		$params[':uid']=$uid;
	}
	if($openid){
		$condition .= " AND ";
		$condition .= "openid LIKE :openid";
		$params[':openid']=$openid;
	}
	if($aid){
		$condition .= " AND ";
		$condition .= "artid= :artid";
		$params[':artid']=$aid;
	}else{
	    return array(); //空文章不调用数据
	}
	$showorder = (in_array(strtolower($order),array('desc','asc'))) ? $order : 'DESC' ;
	$orderby = " ORDER BY id ".$showorder;
	if($multi){
		$result = pdo_fetchall($sql.$condition.$orderby,$params);
	}else{
		$result = pdo_fetch($sql.$condition.$orderby." LIMIT 1",$params);
	}
	return $result;
}

/*
*查询对应用户的文章分享记录
@uid		会员ID，默认针对前台，为当前登陆会员
@aid		文章ID，未传入时则查询全部文章记录
@openid		粉丝openid
@multi  用于限制查询结果量；默认取单条数据
@orderby  结果排序方式，按记录号DESC/ASC，默认降序(取最新的)
*/
function fmMod_article_fxQuery($uid=null,$openid=null,$aid=null,$multi=null,$order=null){
	global $_G;
	global $_W;
	global $_FM;
	$fields = "id, artid, arttitle, openid, uid, avatar, viewtime";
	$sql = " SELECT  ".$fields." FROM ".tablename('fm453_site_article_viewlog');

	$condition = " WHERE ";
	$params = array();
	$condition .= "do= :do";
	$params[':do']='share';
	if($uid){
		$condition .= " AND ";
		$condition .= "uid= :uid";
		$params[':uid']=$uid;
	}
	if($openid){
		$condition .= " AND ";
		$condition .= "openid LIKE :openid";
		$params[':openid']=$openid;
	}
	if($aid){
		$condition .= " AND ";
		$condition .= "artid= :artid";
		$params[':artid']=$aid;
	}else{
	    return array(); //空文章不调用数据
	}
	$showorder = (in_array(strtolower($order),array('desc','asc'))) ? $order : 'DESC' ;
	$orderby = " ORDER BY id ".$showorder;
	if($multi){
		$result = pdo_fetchall($sql.$condition.$orderby,$params);
	}else{
		$result = pdo_fetch($sql.$condition.$orderby." LIMIT 1",$params);
	}
	return $result;
}

/*
*为某会员自动生成描述文章内容
@currentid		会员ID
@openid		会员微信openid
*/
function fmMod_article_memberAutoContent($uid=null,$openid=null) {
	global $_G,$_W,$_FM;
	$return=array();
	$member=$_FM['member']['info'];
	$settings = $_FM['member']['settings'];
	$uid = $member['uid'];
	$openid = $member['openid'];
	$mobile = trim($member['mobile']);
	$username = $member['nickname'];
	$nowTime = $_W['timestamp'];
	if(!$settings['content']) {
		//会员不存在内容项设置时，创建之
	 	$html = fmMod_article_memberContentFormat($_FM['member']);
	 	fm_load()->fm_model('member');
	 	fmMod_member_settings_save($uid,$openid,$savedata=array('title'=>'','value'=>$html,'status'=>'127'),$setfor='content');
	 }
	 $aticleID = cache_load("articleId_".$openid);
	 if($aticleID) {
	 	exit;
	 }else{
	 	$myArticle_id=pdo_fetchcolumn("SELECT id FROM " . tablename('fm453_site_article') . " WHERE uniacid = :uniacid  AND a_tpl = :a_tpl AND goodadm = :goodadm ORDER BY id ASC",array(":uniacid"=>$_W['uniacid'],":a_tpl"=>'member',':goodadm'=>$openid));
	 	$tempArticle_id=pdo_fetchcolumn("SELECT id FROM " . tablename('fm453_site_article') . " WHERE uniacid = :uniacid  AND a_tpl = :a_tpl AND goodadm = :goodadm ORDER BY id ASC",array(":uniacid"=>$_W['uniacid'],":a_tpl"=>'temp1',':goodadm'=>$openid));
	 	if($myArticle_id || $tempArticle_id) {
	 		exit;
	 	}
	 }
	$data=array();
	$data['title'] = $username;
	$data['dscription'] = '新注册会员';
	$data['keywords'] = '';
	$data['content'] = '';
	$data['a_tpl'] = 'temp1';
	$data['thumb'] = tomedia($member['avatar']);
	$data['goodadm'] = $openid;
	$data['kefuphone'] = $mobile;
	$data['status']=0;
	$data['uniacid']=$_W['uniacid'];

	$data['createtime']=$nowTime;//记录生成时间
	$data['updatetime']=$nowTime;//记录更新时间

	$articleData=array(
		'title'=>$data['title'],
		'uniacid'=>$_W['uniacid'],
		'iscommend'=>0,
		'pcate'=>0,
		'ccate'=>0,
		'ishot'=>0,
		'description'=>$data['dscription'],
		'content'=>htmlspecialchars_decode($html),
		'thumb'=>$data['thumb'],
		'displayorder'=>0,
		'createtime'=>$nowTime,
		);
	pdo_insert('site_article', $articleData);		//插入新文章到主系统的site_article表
	$data['sn']=pdo_insertid();
	$id = $result['data'];
	$result = fmMod_article_new_basic($data,$_W['uniacid']);	//生成新文章到模块的×××_site_article表
	$id = $result['data'];

	if($result['result']){
		$return['result']=TRUE;
		$return['data']=$id;
		return $return;
	}else{
		$return['result']=FALSE;
		$error ='新建会员文章失败';
		$return['msg']=$error;
		trigger_error($error, E_USER_ERROR);
		return $return;
	}
}

/*
*格式化某会员介绍内容
@$member, 合并的会员信息（info,agent,settings)
@$replaceempty ，是否清空无效值
*/
function fmMod_article_memberContentFormat($member,$replaceempty=null) {
	global $_GPC,$_W,$_FM;
	$info=$member['info'];
	$settings = $member['settings'];
	$uid = $info['uid'];
	$openid = $info['openid'];
	$mobile = trim($info['mobile']);
	$username = $info['nickname'];
	$realname = $info['realname'];
	$nowTime = $_W['timestamp'];
	$html='';
	$html .= '
		<section style="border: 0px; margin: 0.5em 0px; padding: 0px; box-sizing: border-box;" class="tn-Powered-by-XIUMI lead">
    <section style="width: 100%; margin-bottom: -4.2em; display: inline-block; vertical-align: bottom; font-size: 1em; font-family: inherit; text-align: center; text-decoration: inherit; color: rgb(255, 255, 255); border-color: rgb(95, 156, 239); box-sizing: border-box;" class="tn-Powered-by-XIUMI">
        <section style="padding: 5px; background-color: rgb(95, 156, 239); box-sizing: border-box;" class="tn-Powered-by-XIUMI">
            <section class="tn-Powered-by-XIUMI" style="box-sizing: border-box;">
                {basic_info}
            </section>
        </section>
        <section style="width: 100%; box-sizing: border-box;" class="tn-Powered-by-XIUMI">
            <section style="width: 0px; float: left; border-right: 4px solid rgb(95, 156, 239); border-top: 4px solid rgb(95, 156, 239); border-left: 4px solid transparent !important; border-bottom: 4px solid transparent !important; box-sizing: border-box;" class="tn-Powered-by-XIUMI"></section>
            <section style="width: 0px; float: right; border-left: 4px solid rgb(95, 156, 239); border-top: 4px solid rgb(95, 156, 239); border-right: 4px solid transparent !important; border-bottom: 4px solid transparent !important; box-sizing: border-box;" class="tn-Powered-by-XIUMI"></section>
        </section>
    </section>
    <section style="width: 100%; padding: 0px 8px; box-sizing: border-box;" class="tn-Powered-by-XIUMI">
        <section style="border: 1px solid rgb(204, 204, 204); padding: 5em 5px 1em; width: 100%; border-radius: 0.3em; box-shadow: rgba(159, 160, 160, 0.498039) 0px 0px 10px; text-decoration: inherit; box-sizing: border-box;" class="tn-Powered-by-XIUMI">
            <section class="tn-Powered-by-XIUMI" style="box-sizing: border-box;">
                <p style="color: rgb(153, 153, 153); font-family: inherit; font-size: 1em;">
                    <span style="font-family: 隶书, SimLi; color: rgb(247, 150, 70); font-size: 18px;">{realname}</span>： &nbsp;{realname_value}
                </p>
                <p style="color: rgb(153, 153, 153); font-family: inherit; font-size: 1em;">
                    <span style="font-family: 隶书, SimLi; color: rgb(247, 150, 70); font-size: 18px;">{birth_place}</span>： &nbsp;{birth_place_value}
                </p>
                <p style="color: rgb(153, 153, 153); font-family: inherit; font-size: 1em;">
                    <span style="font-family: 隶书, SimLi; color: rgb(247, 150, 70); font-size: 18px;">{birth_where}</span><span style="color: rgb(153, 153, 153);">： &nbsp;{birth_where_value}</span>
 &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                </p>
                <p style="color: rgb(153, 153, 153); font-family: inherit; font-size: 1em;">
                    <span style="font-family: 隶书, SimLi; color: rgb(247, 150, 70); font-size: 18px;">{now_place}</span>： &nbsp;{now_place_value}
                </p>
            </section>
        </section>
    </section>
    <section style="width: 0px; height: 0px; clear: both;"></section>
</section>
<section class="tn-Powered-by-XIUMI lead" style="white-space: normal; border: 0px; margin: 0.5em 0px; padding: 0px; box-sizing: border-box;">
    <section class="tn-Powered-by-XIUMI" style="width: 100%; margin-bottom: -4.2em; display: inline-block; vertical-align: bottom; font-size: 1em; font-family: inherit; text-align: center; text-decoration: inherit; color: rgb(255, 255, 255); border-color: rgb(95, 156, 239); box-sizing: border-box;">
        <section class="tn-Powered-by-XIUMI" style="padding: 5px; background-color: rgb(95, 156, 239); box-sizing: border-box;">
            <section class="tn-Powered-by-XIUMI" style="box-sizing: border-box;">
                {job_info}
            </section>
        </section>
        <section class="tn-Powered-by-XIUMI" style="width: 100%; box-sizing: border-box;">
            <section class="tn-Powered-by-XIUMI" style="width: 0px; float: left; border-right: 4px solid rgb(95, 156, 239); border-top: 4px solid rgb(95, 156, 239); box-sizing: border-box; border-left: 4px solid transparent !important; border-bottom: 4px solid transparent !important;"></section>
            <section class="tn-Powered-by-XIUMI" style="width: 0px; float: right; border-left: 4px solid rgb(95, 156, 239); border-top: 4px solid rgb(95, 156, 239); box-sizing: border-box; border-right: 4px solid transparent !important; border-bottom: 4px solid transparent !important;"></section>
        </section>
    </section>
    <section class="tn-Powered-by-XIUMI" style="width: 100%; padding: 0px 8px; box-sizing: border-box;">
        <section class="tn-Powered-by-XIUMI" style="border: 1px solid rgb(204, 204, 204); padding: 5em 5px 1em; width: 100%; border-radius: 0.3em; box-shadow: rgba(159, 160, 160, 0.498039) 0px 0px 10px; font-size: 1em; font-family: inherit; text-decoration: inherit; color: rgb(153, 153, 153); box-sizing: border-box;">
            <section class="tn-Powered-by-XIUMI" style="box-sizing: border-box;">
                <p style="font-family: inherit; font-size: 1em; white-space: normal; color: rgb(153, 153, 153);">
                    <span style="font-family: 隶书, SimLi; color: rgb(247, 150, 70); font-size: 18px;">{company}</span>： &nbsp;{company_value}
                </p>
                <p style="font-family: inherit; font-size: 1em; white-space: normal; color: rgb(153, 153, 153);">
                    <span style="font-family: 隶书, SimLi; color: rgb(247, 150, 70); font-size: 18px;">{job}</span>： &nbsp;{job_value}
                </p>
                <p style="font-family: inherit; font-size: 1em; white-space: normal; color: rgb(153, 153, 153);">
                    <span style="font-family: 隶书, SimLi; color: rgb(247, 150, 70); font-size: 18px;">{business_scope}</span>： &nbsp;{business_scope_value}
                </p>
                <p style="font-family: inherit; font-size: 1em; white-space: normal; color: rgb(153, 153, 153);">
                    <span style="font-family: 隶书, SimLi; color: rgb(247, 150, 70); font-size: 18px;">{company_address}</span>： &nbsp;{company_address_value}
                </p>
            </section>
        </section>
    </section>
    <section style="width: 0px; height: 0px; clear: both;"></section>
</section>
<section style="border: 0px; margin: 0.5em 0px; padding: 0px; box-sizing: border-box;" class="tn-Powered-by-XIUMI lead">
    <section style="width: 100%; margin-bottom: -4.2em; display: inline-block; vertical-align: bottom; font-size: 1em; font-family: inherit; text-align: center; text-decoration: inherit; color: rgb(255, 255, 255); border-color: rgb(95, 156, 239); box-sizing: border-box;" class="tn-Powered-by-XIUMI">
        <section style="padding: 5px; background-color: rgb(95, 156, 239); box-sizing: border-box;" class="tn-Powered-by-XIUMI">
            <section class="tn-Powered-by-XIUMI" style="box-sizing: border-box;">
                {contact_info}
            </section>
        </section>
        <section style="width: 100%; box-sizing: border-box;" class="tn-Powered-by-XIUMI">
            <section style="width: 0px; float: left; border-right: 4px solid rgb(95, 156, 239); border-top: 4px solid rgb(95, 156, 239); border-left: 4px solid transparent !important; border-bottom: 4px solid transparent !important; box-sizing: border-box;" class="tn-Powered-by-XIUMI"></section>
            <section style="width: 0px; float: right; border-left: 4px solid rgb(95, 156, 239); border-top: 4px solid rgb(95, 156, 239); border-right: 4px solid transparent !important; border-bottom: 4px solid transparent !important; box-sizing: border-box;" class="tn-Powered-by-XIUMI"></section>
        </section>
    </section>
    <section style="width: 100%; padding: 0px 8px; box-sizing: border-box;" class="tn-Powered-by-XIUMI">
        <section style="border: 1px solid rgb(204, 204, 204); padding: 5em 5px 1em; width: 100%; border-radius: 0.3em; box-shadow: rgba(159, 160, 160, 0.498039) 0px 0px 10px; font-size: 1em; font-family: inherit; text-decoration: inherit; color: rgb(153, 153, 153); box-sizing: border-box;" class="tn-Powered-by-XIUMI">
            <section class="tn-Powered-by-XIUMI" style="box-sizing: border-box;">
                <p style="font-family: inherit; font-size: 1em; white-space: normal; color: rgb(153, 153, 153);">
                    <span style="font-family: 隶书, SimLi; color: rgb(247, 150, 70); font-size: 18px;">{mobile}</span>： &nbsp;{mobile_value}
                </p>
                <p style="font-family: inherit; font-size: 1em; white-space: normal; color: rgb(153, 153, 153);">
                    <span style="font-family: 隶书, SimLi; color: rgb(247, 150, 70); font-size: 18px;">{qq}</span><span style="color: rgb(153, 153, 153);">： &nbsp;{qq_value}</span>
 &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                </p>
                <p style="font-family: inherit; font-size: 1em; white-space: normal; color: rgb(153, 153, 153);">
                    <span style="color: rgb(153, 153, 153);"><span style="font-family: 隶书, SimLi; color: rgb(247, 150, 70); font-size: 18px;">{wxhao}</span><span style="color: rgb(153, 153, 153);">： &nbsp;{wxhao_value}</span></span>
 &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                </p>
            </section>
        </section>
    </section>
    <section style="width: 0px; height: 0px; clear: both;"></section>
</section>
<p class="lead">
    <br/>
</p>
<section style="border: 0px; margin: 1em; padding: 0px; box-sizing: border-box;" class="lead">
    <section style="text-decoration: inherit; color: rgb(255, 255, 255); border-color: rgb(95, 156, 239); box-sizing: border-box;">
        <section style="padding: 3px 8px; line-height: 1.4; border-top-left-radius: 8px; border-top-right-radius: 8px; background-color: rgb(95, 156, 239); box-sizing: border-box;">
            <section style="box-sizing: border-box;">
                {personal_des}
            </section>
        </section>
        <section style="box-sizing: border-box; border-style: solid; border-width: 0px 1px 1px; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px; border-color: rgb(95, 156, 239);">
            <section style="padding: 16px; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px; line-height: 1.4; text-decoration: inherit; color: rgb(51, 51, 51); border-color: rgb(95, 156, 239); box-sizing: border-box;">
                <p style="box-sizing: border-box;">
                    <span style="display:none;height:0;width:0;">{introduction}</span>{introduction_value}
                </p>
            </section>
        </section>
    </section>
    <section style="width: 0px; height: 0px; clear: both;"></section>
</section>
<section style="margin: 96px 16px 16px; border: 1px solid rgb(95, 156, 239); text-align: center; border-radius: 8px; text-decoration: inherit; box-sizing: border-box; padding: 0px;" class="lead">
    <section style="width: 6.5em; height: 6.5em; margin: -3.3em auto 0px; border-radius: 50%; border: 2px solid rgb(95, 156, 239); box-shadow: rgb(201, 201, 201) 0px 2px 2px 2px; box-sizing: border-box;">
        <section style="width: 100%; height: 100%; border-radius: 100%; background-image: url(&#39;{avatar_value}&#39;); background-position: 50% 50%; background-repeat: no-repeat; background-size: cover; box-sizing: border-box;"></section>
    </section>
    <section style="margin: 8px 8px 3px; line-height: 1.4; box-sizing: border-box;">
        <section style="box-sizing: border-box;">
            <span style="display:none;height:0;width:0;">{wxname}</span>{wxname_value}
        </section>
    </section>
    <section style="margin: 0px 8px; line-height: 1.4; text-decoration: inherit; color: rgb(52, 54, 60); box-sizing: border-box;">
        <section style="box-sizing: border-box;">
            <span style="display:none;height:0;width:0;">{signature}</span>{signature_value}
        </section>
    </section>
    <section style="margin: 16px; border-top: 1px solid rgb(95, 156, 239); border-right-color: rgb(95, 156, 239); border-bottom-color: rgb(95, 156, 239); border-left-color: rgb(95, 156, 239); box-sizing: border-box;"></section>
    <section style="margin: 8px; line-height: 1.4; text-decoration: inherit; box-sizing: border-box;">
        <section style="box-sizing: border-box;">
            {wxhao}：{wxhao_value}
        </section>
    </section>
    <p style="text-align: center;">
        <img style="margin: 0px auto 0.3em; box-sizing: border-box; width: 104px; height: 104px;" src="{qrcode_value}" width="104" height="104" border="0" vspace="0" title="" alt=""/>
 &nbsp; &nbsp;
    </p>
    <section style="width: 0px; height: 0px; clear: both;"></section>
</section>
<section style="border: 0px; margin: 0.5em 0px; padding: 0.3em; box-sizing: border-box;" class="lead">
    <section style="border: 1px solid rgb(192, 200, 209); padding: 10px; box-shadow: rgb(170, 170, 170) 0px 0px 10px; line-height: 1.4; color: rgb(51, 51, 51); text-decoration: inherit; background-color: rgb(250, 250, 239); box-sizing: border-box;">
        <section style="box-sizing: border-box;">
            <span style="display:none;height:0;width:0;">{other_info}</span>{other_info_value}
        </section>
    </section>
    <section style="width: 0px; height: 0px; clear: both;"></section>
</section>
<p>
    <br/>
</p>
	';
	$langs=array();
	$langs['wx']="微信号";
	$langs['basic_info']="基础信息";
	$langs['realname']="姓名";
	$langs['passedname']="曾用名";
	$langs['birth_place']="籍贯";
	$langs['birth_where']="详址";
	$langs['now_place']="现居地";
	$langs['job_info']="工作信息";
	$langs['company']="单位名称";
	$langs['job']="职务";
	$langs['business_scope']="经营范围";
	$langs['company_address']="经营地址";
	$langs['contact_info']="联系信息";
	$langs['mobile']="手机";
	$langs['qq']="Q Q";
	$langs['personal_des']="个人简述";
	$langs['wxname']="微信昵称";
	$langs['signature']="个性签名";
	$langs['wxhao']="微信";
	$langs['other_info']="其他信息";
	$langs['qrcode']="二维码";
	$langs['avatar']="头像";
	$langs['introduction']="个人简述";

	$settings['mobile'] = !empty($settings['mobile']) ? $settings['mobile'] : $mobile;
	$settings['wxname'] = isset($settings['wxname']) ? $settings['wxname'] : $username;
	$settings['realname'] = isset($settings['realname']) ? $settings['realname'] : $realname;
	$settings['qrcode'] = isset($settings['qrcode']) ? $settings['qrcode'] : $member['qrcode'];
	$settings['avatar'] = !empty($settings['avatar']) ? $settings['avatar'] : $info['avatar'];
	$settings['birth_place'] = !empty($settings['birth_province']) ? $settings['birth_province'] : '';
	$settings['birth_place'] .= !empty($settings['birth_city']) ? "-".$settings['birth_city'] : '';
	$settings['birth_where'] = !empty($settings['birth_county']) ? $settings['birth_county'] : '';
	$settings['birth_where'] .= !empty($settings['birth_address']) ? $settings['birth_address'] : '';
	$settings['now_place'] = !empty($settings['now_province']) ? $settings['now_province'] : '';
	$settings['now_place'] .= !empty($settings['now_city']) ? $settings['now_city'] : '';
	$settings['now_place'] .= !empty($settings['now_county']) ? $settings['now_county'] : '';
	$settings['now_place'] .= !empty($settings['now_address']) ? $settings['now_address'] : '';
	$settings['introduction'] = !empty($settings['content']) ? $settings['content'] : '';
	$settings['wx'] = !empty($settings['wx']) ? $settings['wx'] : $settings['wxhao'];

	foreach($langs as $key => $value)
	{
		$search = "{".$key."}";
		$replace = $value;
		$subject = $html;
		$html = str_replace($search, $replace, $subject);
		$search = "{".$key."_value}";
		if(isset($settings[$key])){
			$replace = $settings[$key];
		}elseif($replaceempty){
			$replace = '';
		}
		$subject = $html;
		$html = str_replace($search, $replace, $subject);
	}
	return $html;
}

?>
