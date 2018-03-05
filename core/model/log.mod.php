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
 * @remark	日志模型
 */
defined('IN_IA') or exit('Access Denied');
//保存入库
function fmMod_log_record($platid,$uniacid,$uid,$fanid,$openid,$tablename,$sn,$do,$details) {
	header("Content-type:text/html;charset=utf-8");//加上网页字符集强制设置，避免中文传输乱码
	global $_GPC;
	global $_W;
	global $_FM;
	$return=array();
	if (empty($do)) {
		$return['result']=FALSE;
		$return['msg']='未传入具体日志动作';
		return $return;
	}
	$data=array();
	$data['platid']=$platid;
	$data['uniacid']=$uniacid;
	$data['uid']=$uid;
	$data['fanid']=$fanid;
	$data['openid']=$openid;
	$data['tablename']=$tablename;
	$data['sn']=$sn;
	$data['do']=$do;
	$data['details']=json_encode($details);
	$data['createtime']=$_W['timestamp'];
	$result=pdo_insert('fm453_shopping_logs', $data);
	if($result) {
		$return['result']=TRUE;
		$return['msg']='';
		$return['data']=$result;
	}else {
		$return['result']=FALSE;
		$return['msg']='插入数据失败';
	}
	return $return;
}

function fmMod_log_query_orderby(){
	return $showorder=' ORDER BY createtime DESC, tablename DESC, do ASC, sn DESC, id DESC ';//强制设定排序规则
}

//根据当前后台用户读取指定范围的日志（$do,$tablename,$sn,支持数组参数，但不能带入notable作为参数；$sn参数生效的前提是传入字符串类型的$tablename）//附加参数addons(tabletype,time,)
function fmMod_log_query_uid($platid,$do,$tablename,$sn,$first,$getnum,$addons) {
	global $_GPC;
	global $_W;
	global $_FM;
	fm_load()->fm_func('tables'); //获取数据表函数
	header("Content-type:text/html;charset=utf-8");//加上网页字符集强制设置，避免中文传输乱码
	$uniacid=$_W['uniacid'];
	$uid=$_W['uid'];
	$platid=intval($platid);
	$tablelist=fmFunc_tables_list();
	$tabletypelist=fmFunc_tables_type();
	$tablename=(in_array($tablename,$tablelist)) ? $tablename : '';
	$addons['orderby']=fmMod_log_query_orderby();//强制设定排序规则
	$first=(intval($first)>0) ? intval($first) : 0;
	$getnum=(intval($getnum)>0) ? intval($getnum) : -1;//-1表示取剩余全部
	$limit=' LIMIT '.$first.','.$getnum;//强制设定数据量限制
	$return=array();
	$params =array();
	$fields='`id`,`platid`,`uniacid`,`uid`,`fanid`,`openid`,`tablename`,`do`,`createtime`';	//要查询的字段范围
	$sql='SELECT '.$fields.' FROM '.tablename('fm453_shopping_logs');
	$condition = ' WHERE ';
	$condition .= '`uid` =:uid';
	$params[':uid']=$uid;
	//传入了平台platid>0时，不考虑uniacid
	if($platid>0) {
		$condition .=' AND ';
		$condition .= '`platid` =:platid';
		$params[':platid']=$platid;
	}else{
		$condition .=' AND ';
		$condition .= '`uniacid` =:uniacid';
		$params[':uniacid']=$uniacid;
	}
	//数据操作类型范围
	if(!empty($do)){
		$condition .=' AND ';
		$thedo ='(';
		if(is_array($do)) {
			foreach($do as $d){
				$thedo .='"'.$d.'",';
			}
		}else{
			$thedo .='"'.$do.'",';
		}
		$thedo .='"nodo")';
		$do=$thedo;
		$condition .= '`do` IN '.$do;
	}
	//数据表范围
	if(empty($addons['tabletype']) && !empty($tablename) ){
		//未传入数据类型附加参数
		if(!is_array($tablename) && ($sn)) {
			//单一表名且传入了$sn
			$condition .=' AND ';
			$condition .= '`tablename` = :tablename';
			$params[':tablename']='fm453_shopping_'.$tablename;
			$condition .=' AND ';
			$thesn ='(';
			if(is_array($sn)) {
				foreach($sn as $s){
					$thesn .=intval($s).',';
				}
			}else{
				$thesn .=intval($sn).',';
			}
			$thesn .='0)';
			$sn = $thesn;
			$condition .= '`sn` IN '.$sn;
		}else{
			//传入表为数组
			$condition .=' AND ';
			$thetable ='(';
			foreach($tablename as $table){
				$tmpname='fm453_shopping_'.$table;
				$thetable .='"'.$tmpname.'",';
			}
			$thetable .='"notable")';
			$condition .= '`tablename` IN '.$thetable;
		}
	}elseif(!empty($addons['tabletype'])){
		//按数据类型取表
		$tabletype=$addons['tabletype'];
		if(!is_array($tabletype) && (in_array($tabletype,$tabletypelist))){
			$tablename=fmFunc_tables_bytype($tabletype);
			if($tablename){
				$condition .=' AND ';
				$thetable ='(';
				foreach($tablename as $key=> $table){
					$tmpname='fm453_shopping_'.$key;
					$thetable .='"'.$tmpname.'",';
				}
				$thetable .='"notable")';
				$condition .= '`tablename` IN '.$thetable;
			}
		}
	}
	//数据创建时间范围
	if($addons['time']){
		$starttime=intval($addons['time']['start']);
		$endtime=intval($addons['time']['end']);
		if($starttime>0) {
			//有开始时间，则判断条件为数据生成时间晚于开始时间
			$condition .=' AND ';
			$condition .= '`createtime` > :starttime';
			$params[':starttime']=$starttime;
		}
		if($endtime>0) {
			//有结束时间，则判断条件为数据生成时间早于结束时间
			$condition .=' AND ';
			$condition .= '`createtime` < :endtime';
			$params[':endtime']=$endtime;
		}
	}
	$count='SELECT COUNT(id) FROM '.tablename('fm453_shopping_logs').$condition;
	$total=pdo_fetchcolumn($count,$params);

	$sql =$sql.$condition;
	//排序
	if(!empty($addons['orderby'])){
		$displayorder =$addons['orderby'];
		$sql = $sql.$displayorder;
	}
	//数据量范围
	if($limit){
		//$sql='SELECT '.$fields.' FROM ('.$sql.')';
		//$sql = $sql.' a '.$limit;
		$sql = $sql.$limit;
	}
	$result= pdo_fetchall($sql,$params);
	if($result) {
		$return['result']=TRUE;
		$return['msg']='';
		$weekarray=array("日","一","二","三","四","五","六");
		foreach($result as $key=>&$log){
			$log['createtime']= ($log['createtime']==0) ? '未记录' : date('Y-m-d H:i:s',$log['createtime']).'  星期'.$weekarray[date('w',$log['createtime'])];
			$log['tablename']=str_replace('fm453_shopping_','',$log['tablename']);
		}
		$return['data']=$result;
		$return['total']=$total;
	}else {
		$return['result']=FALSE;
		$return['msg']='获取数据失败';
	}
	return $return;
}

//根据多个后台用户读取指定范围的日志
function fmMod_log_query_uids($platid,$uids,$do,$tablename,$sn,$first,$getnum,$addons) {
	global $_GPC;
	global $_W;
	global $_FM;
	fm_load()->fm_func('tables'); //获取数据表函数
	header("Content-type:text/html;charset=utf-8");//加上网页字符集强制设置，避免中文传输乱码
	if(!is_array($uids)){
		$return['result']=FALSE;
		$return['msg']='传入的用户uids序列非法';
		return $return;
	}else{
			$theUid ='(';
		foreach($uids as $key=>$uid){
			$theUid .=  intval($uid);
		}
			$theUid .=','.'0)';
			$uids=$theUid;
	}

	$uniacid=$_W['uniacid'];
	$platid=intval($platid);
	$tablelist=fmFunc_tables_list();
	$tabletypelist=fmFunc_tables_type();
	$tablename=(in_array($tablename,$tablelist)) ? $tablename : '';
	$addons['orderby']=fmMod_log_query_orderby();//强制设定排序规则
	$first=(intval($first)>0) ? intval($first) : 0;
	$getnum=(intval($getnum)>0) ? intval($getnum) : -1;//-1表示取剩余全部
	$limit=' LIMIT '.$first.','.$getnum;//强制设定数据量限制
	$return=array();
	$params =array();
	$fields='`id`,`platid`,`uniacid`,`uid`,`fanid`,`openid`,`tablename`,`do`,`createtime`';	//要查询的字段范围
	$sql='SELECT '.$fields.' FROM '.tablename('fm453_shopping_logs');
	$condition = ' WHERE ';
	$condition .= '`uid` IN '.$uids;
	//传入了平台platid>0时，不考虑uniacid
	if($platid>0) {
		$condition .=' AND ';
		$condition .= '`platid` =:platid';
		$params[':platid']=$platid;
	}else{
		$condition .=' AND ';
		$condition .= '`uniacid` =:uniacid';
		$params[':uniacid']=$uniacid;
	}
	//数据操作类型范围
	if(!empty($do)){
		$condition .=' AND ';
		$thedo ='(';
		if(is_array($do)) {
			foreach($do as $d){
				$thedo .='"'.$d.'",';
			}
		}else{
			$thedo .='"'.$do.'",';
		}
		$thedo .='"nodo")';
		$do=$thedo;
		$condition .= '`do` IN '.$do;
		//$condition .= '`do` IN :do';
		//$params[':do']=$do;
		//$condition .= '`do` in '.$do;
	}
	//数据表范围
	if(empty($addons['tabletype']) && !empty($tablename) ){
		//未传入数据类型附加参数
		if(!is_array($tablename) && ($sn)) {
			//单一表名且传入了$sn
			$condition .=' AND ';
			$condition .= '`tablename` = :tablename';
			$params[':tablename']='fm453_shopping_'.$tablename;
			$condition .=' AND ';
			$thesn ='(';
			if(is_array($sn)) {
				foreach($sn as $s){
					$thesn .=intval($s).',';
				}
			}else{
				$thesn .=intval($sn).',';
			}
			$thesn .='0)';
			$sn = $thesn;
			$condition .= '`sn` IN '.$sn;
		}else{
			//传入表为数组
			$condition .=' AND ';
			$thetable ='(';
			foreach($tablename as $table){
				$tmpname='fm453_shopping_'.$table;
				$thetable .='"'.$tmpname.'",';
			}
			$thetable .='"notable")';
			$condition .= '`tablename` IN '.$thetable;
		}
	}elseif(!empty($addons['tabletype'])){
		//按数据类型取表
		$tabletype=$addons['tabletype'];
		if(!is_array($tabletype) && (in_array($tabletype,$tabletypelist))){
			$tablename=fmFunc_tables_bytype($tabletype);
			if($tablename){
				$condition .=' AND ';
				$thetable ='(';
				foreach($tablename as $key=> $table){
					$tmpname='fm453_shopping_'.$key;
					$thetable .='"'.$tmpname.'",';
				}
				$thetable .='"notable")';
				$condition .= '`tablename` IN '.$thetable;
			}
		}
	}
	//数据创建时间范围
	if($addons['time']){
		$starttime=intval($addons['time']['start']);
		$endtime=intval($addons['time']['end']);
		if($starttime>0) {
			//有开始时间，则判断条件为数据生成时间晚于开始时间
			$condition .=' AND ';
			$condition .= '`createtime` > :starttime';
			$params[':starttime']=$starttime;
		}
		if($endtime>0) {
			//有结束时间，则判断条件为数据生成时间早于结束时间
			$condition .=' AND ';
			$condition .= '`createtime` < :endtime';
			$params[':endtime']=$endtime;
		}
	}
	$count='SELECT COUNT(id) FROM '.tablename('fm453_shopping_logs').$condition;
	$total=pdo_fetchcolumn($count,$params);

	$sql =$sql.$condition;
	//排序
	if(!empty($addons['orderby'])){
		$displayorder =$addons['orderby'];
		$sql = $sql.$displayorder;
	}
	//数据量范围
	if($limit){
		//$sql='SELECT '.$fields.' FROM ('.$sql.')';
		//$sql = $sql.' a '.$limit;
		$sql = $sql.$limit;
	}
	$result= pdo_fetchall($sql,$params);
	if($result) {
		$return['result']=TRUE;
		$return['msg']='';
		$weekarray=array("日","一","二","三","四","五","六");
		foreach($result as $key=>&$log){
			$log['createtime']= ($log['createtime']==0) ? '未记录' : date('Y-m-d H:i:s',$log['createtime']).'  星期'.$weekarray[date('w',$log['createtime'])];
			$log['tablename']=str_replace('fm453_shopping_','',$log['tablename']);
		}
		$return['data']=$result;
		$return['total']=$total;
	}else {
		$return['result']=FALSE;
		$return['msg']='获取数据失败';
	}
	return $return;
}

//根据当前粉丝openid读取指定范围的日志
function fmMod_log_query_openid($platid,$do,$tablename,$sn,$first,$getnum,$addons) {
	global $_GPC;
	global $_W;
	global $_FM;
	fm_load()->fm_func('tables'); //获取数据表函数
	header("Content-type:text/html;charset=utf-8");//加上网页字符集强制设置，避免中文传输乱码
	$uniacid=$_W['uniacid'];
	$openid= !empty($addons['openid']) ? $addons['openid'] : $_W['openid'];
	$platid=intval($platid);
	$tablelist=fmFunc_tables_list();
	$tabletypelist=fmFunc_tables_type();
	$tablename=(in_array($tablename,$tablelist)) ? $tablename : '';
	$first=(intval($first)>0) ? intval($first) : 0;
	$getnum=(intval($getnum)>0) ? intval($getnum) : -1;//-1表示取剩余全部
	$limit=' LIMIT '.$first.','.$getnum;//强制设定数据量限制
	$addons['orderby']=fmMod_log_query_orderby();//强制设定排序规则
	$return=array();
	$params =array();
	$fields='`id`,`platid`,`uniacid`,`uid`,`fanid`,`openid`,`tablename`,`do`,`createtime`';	//要查询的字段范围
	$sql='SELECT '.$fields.' FROM '.tablename('fm453_shopping_logs');
	$condition = ' WHERE ';
	$condition .= '`openid` =:openid';
	$params[':openid']=$openid;
	//传入了平台platid>0时，不考虑uniacid
	if($platid>0) {
		$condition .=' AND ';
		$condition .= '`platid` =:platid';
		$params[':platid']=$platid;
	}else{
		$condition .=' AND ';
		$condition .= '`uniacid` =:uniacid';
		$params[':uniacid']=$uniacid;
	}
	//数据操作类型范围
	if(!empty($do)){
		$condition .=' AND ';
		$thedo ='(';
		if(is_array($do)) {
			foreach($do as $d){
				$thedo .='"'.$d.'",';
			}
		}else{
			$thedo .='"'.$do.'",';
		}
		$thedo .='"nodo")';
		$do=$thedo;
		$condition .= '`do` IN '.$do;
	}
	//数据表范围
	if(empty($addons['tabletype']) && !empty($tablename) ){
		//未传入数据类型附加参数
		if(!is_array($tablename) && ($sn)) {
			//单一表名且传入了$sn
			$condition .=' AND ';
			$condition .= '`tablename` = :tablename';
			$params[':tablename']='fm453_shopping_'.$tablename;
			$condition .=' AND ';
			$thesn ='(';
			if(is_array($sn)) {
				foreach($sn as $s){
					$thesn .=intval($s).',';
				}
			}else{
				$thesn .=intval($sn).',';
			}
			$thesn .='0)';
			$sn = $thesn;
			$condition .= '`sn` IN '.$sn;
		}else{
			//传入表为数组
			$condition .=' AND ';
			$thetable ='(';
			foreach($tablename as $table){
				$tmpname='fm453_shopping_'.$table;
				$thetable .='"'.$tmpname.'",';
			}
			$thetable .='"notable")';
			$condition .= '`tablename` IN '.$thetable;
		}
	}elseif(!empty($addons['tabletype'])){
		//按数据类型取表
		$tabletype=$addons['tabletype'];
		if(!is_array($tabletype) && (in_array($tabletype,$tabletypelist))){
			$tablename=fmFunc_tables_bytype($tabletype);
			if($tablename){
				$condition .=' AND ';
				$thetable ='(';
				foreach($tablename as $key=> $table){
					$tmpname='fm453_shopping_'.$key;
					$thetable .='"'.$tmpname.'",';
				}
				$thetable .='"notable")';
				$condition .= '`tablename` IN '.$thetable;
			}
		}
	}
	//数据创建时间范围
	if($addons['time']){
		$starttime=intval($addons['time']['start']);
		$endtime=intval($addons['time']['end']);
		if($starttime>0) {
			//有开始时间，则判断条件为数据生成时间晚于开始时间
			$condition .=' AND ';
			$condition .= '`createtime` > :starttime';
			$params[':starttime']=$starttime;
		}
		if($endtime>0) {
			//有结束时间，则判断条件为数据生成时间早于结束时间
			$condition .=' AND ';
			$condition .= '`createtime` < :endtime';
			$params[':endtime']=$endtime;
		}
	}
	$count='SELECT COUNT(id) FROM '.tablename('fm453_shopping_logs').$condition;
	$total=pdo_fetchcolumn($count,$params);

	$sql =$sql.$condition;
	//排序
	if(!empty($addons['orderby'])){
		$displayorder =$addons['orderby'];
		$sql = $sql.$displayorder;
	}
	//数据量范围
	if($limit){
		$sql = $sql.$limit;
	}
	$result= pdo_fetchall($sql,$params);
	if($result) {
		$return['result']=TRUE;
		$return['msg']='';
		$weekarray=array("日","一","二","三","四","五","六");
		foreach($result as $key=>&$log){
			$log['createtime']= ($log['createtime']==0) ? '未记录' : date('Y-m-d H:i:s',$log['createtime']).'  星期'.$weekarray[date('w',$log['createtime'])];
			$log['tablename']=str_replace('fm453_shopping_','',$log['tablename']);
		}
		$return['data']=$result;
		$return['total']=$total;
	}else {
		$return['result']=FALSE;
		$return['msg']='获取数据失败';
	}
	return $return;
}

//获取全部指定范围的日志
function fmMod_log_all($platid,$uniacid,$uid,$fanid,$openid,$do,$tablename,$sn,$first,$getnum,$addons) {
	global $_GPC;
	global $_W;
	global $_FM;
	fm_load()->fm_func('tables'); //获取数据表函数
	header("Content-type:text/html;charset=utf-8");//加上网页字符集强制设置，避免中文传输乱码
	$platid=intval($platid);
	$uniacid=intval($uniacid);
	$uid=intval($uid);
	$sn=intval($sn);
	$first=(intval($first)>0) ? intval($first) : 0;
	$getnum=(intval($getnum)>0) ? intval($getnum) : -1;//-1表示取剩余全部
	$limit=' LIMIT '.$first.','.$getnum;//强制设定数据量限制

	$tablelist=fmFunc_tables_list();
	$tabletypelist=fmFunc_tables_type();
	$tablename=(in_array($tablename,$tablelist)) ? $tablename : '';
	$addons['orderby']= !empty($addons['orderby']) ? $addons['orderby'] : fmMod_log_query_orderby();//强制设定排序规则

	$return=array();
	$params =array();
	$fields='`id`,`platid`,`uniacid`,`uid`,`fanid`,`openid`,`tablename`,`do`,`createtime`';	//要查询的字段范围
	$sql='SELECT '.$fields.' FROM '.tablename('fm453_shopping_logs');
	$condition = ' WHERE ';
	//传入了平台platid>0时，不考虑uniacid
	if($platid>0) {
		$condition .= '`platid` =:platid';
		$params[':platid']=$platid;
	}else{
		if($uniacid>0){
			$condition .= '`uniacid` =:uniacid';
			$params[':uniacid']=$uniacid;
		}else{
			if($_W['isfounder']){
				$condition .= '`uniacid` >=0';
			}else{
				$condition .= '`uniacid` =:uniacid';
				$params[':uniacid']=$_W['uniacid'];
			}
		}
	}

	if($uid>0) {
		$condition .=' AND ';
		$condition .= '`uid` =:uid';
		$params[':uid']=$uid;
	}

	if($fanid>0) {
		$condition .=' AND ';
		$condition .= '`fanid` =:fanid';
		$params[':fanid']=$fanid;
	}

	if($openid) {
		$condition .=' AND ';
		$condition .= '`openid` =:openid';
		$params[':openid']=$openid;
	}

	//数据操作类型范围
	if(!empty($do)){
		$condition .=' AND ';
		$thedo ='(';
		if(is_array($do)) {
			foreach($do as $d){
				$thedo .='"'.$d.'",';
			}
		}else{
			$thedo .='"'.$do.'",';
		}
		$thedo .='"nodo")';
		$do=$thedo;
		$condition .= '`do` IN '.$do;
	}
	//数据表范围
	if(empty($addons['tabletype']) && !empty($tablename) ){
		//未传入数据类型附加参数
		if(!is_array($tablename) && ($sn)) {
			//单一表名且传入了$sn
			$condition .=' AND ';
			$condition .= '`tablename` = :tablename';
			$params[':tablename']='fm453_shopping_'.$tablename;
			$condition .=' AND ';
			$thesn ='(';
			if(is_array($sn)) {
				foreach($sn as $s){
					$thesn .=intval($s).',';
				}
			}else{
				$thesn .=intval($sn).',';
			}
			$thesn .='0)';
			$sn = $thesn;
			$condition .= '`sn` IN '.$sn;
		}else{
			//传入表为数组
			$condition .=' AND ';
			$thetable ='(';
			foreach($tablename as $table){
				$tmpname='fm453_shopping_'.$table;
				$thetable .='"'.$tmpname.'",';
			}
			$thetable .='"notable")';
			$condition .= '`tablename` IN '.$thetable;
		}
	}elseif(!empty($addons['tabletype'])){
		//按数据类型取表
		$tabletype=$addons['tabletype'];
		if(!is_array($tabletype) && (in_array($tabletype,$tabletypelist))){
			$tablename=fmFunc_tables_bytype($tabletype);
			if($tablename){
				$condition .=' AND ';
				$thetable ='(';
				foreach($tablename as $key=> $table){
					$tmpname='fm453_shopping_'.$key;
					$thetable .='"'.$tmpname.'",';
				}
				$thetable .='"notable")';
				$condition .= '`tablename` IN '.$thetable;
			}
		}
	}
	//数据创建时间范围
	if($addons['time']){
		$starttime=intval($addons['time']['start']);
		$endtime=intval($addons['time']['end']);
		if($starttime>0) {
			//有开始时间，则判断条件为数据生成时间晚于开始时间
			$condition .=' AND ';
			$condition .= '`createtime` > :starttime';
			$params[':starttime']=$starttime;
		}
		if($endtime>0) {
			//有结束时间，则判断条件为数据生成时间早于结束时间
			$condition .=' AND ';
			$condition .= '`createtime` < :endtime';
			$params[':endtime']=$endtime;
		}
	}
	//按当前查询条件获取数据量条数
	$count='SELECT COUNT(id) FROM '.tablename('fm453_shopping_logs').$condition;
	$total=pdo_fetchcolumn($count,$params);

	$sql =$sql.$condition;
	//排序
	if($addons['orderby']){
		$displayorder =$addons['orderby'];
		$sql = $sql.$displayorder;
	}
	//数据量范围
	if($limit){
		$sql = $sql.$limit;
	}
	$result= pdo_fetchall($sql,$params);
	if($result) {
		$return['result']=TRUE;
		$return['msg']='';
		$weekarray=array("日","一","二","三","四","五","六");
		foreach($result as $key=>&$log){
			if($log['createtime']==2147483647){
				$log['createtime']=0;
			}
			$log['createtime']= ($log['createtime']==0) ? '未记录' : date('Y-m-d H:i:s',$log['createtime']).'  星期'.$weekarray[date('w',$log['createtime'])];
			$log['tablename']=str_replace('fm453_shopping_','',$log['tablename']);
		}
		$return['data']=$result;
		$return['total']=$total;
	}else {
		$return['result']=FALSE;
		$return['msg']='获取数据失败';
	}
	return $return;
}

//按id获取对应的日志(可获取操作的详情details; 为了提高效率，其他方法获取的日志未提供details)
function fmMod_log_query_id($id) {
	global $_GPC;
	global $_W;
	global $_FM;
	header("Content-type:text/html;charset=utf-8");//加上网页字符集强制设置，避免中文传输乱码
	$id=(intval($id)>0) ? intval($id) : FALSE;
	$return=array();
	if(!$id) {
		$return['result']=FALSE;
		$return['msg']='传入的记录id非法';
		return $return;
	}
	$params =array();
	$fields='`id`,`platid`,`uniacid`,`uid`,`fanid`,`openid`,`tablename`,`do`,`details`,`createtime`';	//要查询的字段范围
	$sql='SELECT '.$fields.' FROM '.tablename('fm453_shopping_logs');
	$condition = ' WHERE ';
	$condition .= '`id` =:id';
	$params[':id']=$id;
	$sql=$sql.$condition;
	$result= pdo_fetch($sql,$params);
	if($result) {
		$return['result']=TRUE;
		$return['msg']='';
		$log=$result;
		$log['details']=json_decode($log['details']);	//还原JOSN数据，但仍为stdClass保留类对象
		$log['details']=fm_object2array($log['details']);//PHP stdClass Object转array
		$weekarray=array("日","一","二","三","四","五","六");
		$log['createtime']= ($log['createtime']==0) ? '未记录' : date('Y-m-d H:i:s',$log['createtime']).'  星期'.$weekarray[date('w',$log['createtime'])];
		$log['tablename']=str_replace('fm453_shopping_','',$log['tablename']);
		$return['data']=$log;
	}else {
		$return['result']=FALSE;
		$return['msg']='获取数据失败';
	}
	return $return;
}
