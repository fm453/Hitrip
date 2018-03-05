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
 * @remark 统计处理函数(流量统计、订单统计等)
 */

//更新浏览量(访客openid)，(浏览对象goods, ads, special, partner, article, vshop, system)，(对象记录ID) ,
function fmFunc_stat_view($setfor=NULL,$id=NULL) {
	global $_GPC;
	global $_W;
	global $_FM;
	$openid=$_W['openid'];
	$uniacid=$_W['uniacid'];
	$fanid=$_W['fans']['fanid'];
	$uid=$_FM['member']['info']['uid'];
	$id=intval($id);
	$return=array();

	if($setfor=='goods')
	{
		if(empty($id)) {
			$return['result']=FALSE;
			$return['msg']='未传入产品编号';
			return $return;
		}
		//增加一次浏览次数
		pdo_update('fm453_shopping_goods', array('viewcount +=' => 1), array('id' => $id));
		//判断是否有访问记录，如没有，则判断为独立访客
		$params=array();
		$fields='createtime';
		$sql="select ".$fields." from" . tablename('fm453_shopping_logs') . "where uniacid=:uniacid and openid=:openid and tablename=:tablename and sn=:sn and do=:do";
		$params[':uniacid']=$uniacid;
		$params[':openid']=$openid;
		$params[':tablename']='fm453_shopping_goods';
		$params[':sn']=$id;
		$params[':do']='view';
		$view_time=pdo_fetch($sql,$params);
		if(empty($view_time)){
			//增加一个独立访客
			pdo_update('fm453_shopping_goods', array('uv +=' => 1), array('id' => $id));
		}
		//写入操作日志START
		$addons=array(
			'openid'=>$_W['openid'],
			'uid'=>$_W['member']['uid'],
		);
		$dologs=array(
			'url'=>$_W['siteurl'],
			'description'=>'浏览产品',
			'addons'=>$addons,
		);
		fmMod_log_record($_GPC['fromplatid'],$_W['uniacid'],$uid,$fanid,$openid,'fm453_shopping_goods',$id,'view',$dologs);
		unset($dologs);
		//写入操作日志END
		return TRUE;
	}elseif($setfor=='article') {
		if(empty($id)) {
			$return['result']=FALSE;
			$return['msg']='未传入文章编号';
			return $return;
		}
		//增加一次浏览次数
		pdo_update('fm453_site_article', array('viewcount +=' => 1), array('id' => $id));
		//判断是否有访问记录，如没有，则判断为独立访客
		$params=array();
		$fields='createtime';
		$sql="select ".$fields." from" . tablename('fm453_shopping_logs') . "where uniacid=:uniacid and openid=:openid and tablename=:tablename and sn=:sn and do=:do";
		$params[':uniacid']=$uniacid;
		$params[':openid']=$openid;
		$params[':tablename']='fm453_site_article';
		$params[':sn']=$id;
		$params[':do']='view';
		$view_time=pdo_fetch($sql,$params);
		if(empty($view_time)){
			//增加一个独立访客
			pdo_update('fm453_site_article', array('uv +=' => 1), array('id' => $id));
		}
		//写入操作日志START
		$addons=array(
			'openid'=>$_W['openid'],
			'uid'=>$_W['member']['uid'],
		);
		$dologs=array(
			'url'=>$_W['siteurl'],
			'description'=>'浏览文章',
			'addons'=>$addons,
		);
		fmMod_log_record($_GPC['fromplatid'],$_W['uniacid'],$uid,$fanid,$openid,'fm453_shopping_goods',$id,'view',$dologs);
		unset($dologs);
		//写入操作日志END
		return TRUE;
	}
	elseif($setfor==NULL) {
		//判断访问记录间隔，强制规定超过30s才再次记录
		$nowtime = TIMESTAMP;
		$params=array();
		$fields='createtime';
		$sql="select ".$fields." from" . tablename('fm453_shopping_logs') . "where uniacid=:uniacid and openid=:openid and tablename=:tablename  and do=:do order by createtime DESC";
		$params[':uniacid']=$uniacid;
		$params[':openid']=$openid;
		$params[':tablename']='fm453_shopping_logs';
		$params[':do']='view';
		$view_time=pdo_fetch($sql,$params);
		$time_limit = $nowtime - intval($view_time);
		if($time_limit>30){
			//写入操作日志START
			$addons=array(
				'openid'=>$_W['openid'],
				'fanid'=>$_W['fans']['fanid'],
				'ip'=>$_W['clientip'],
				'url'=>$_W['script_name']
			);
			$dologs=array(
				'url'=>$_W['siteurl'],
				'description'=>'访问商城',
				'addons'=>$addons,
			);
			$id = $sn;
			fmMod_log_record($_GPC['fromplatid'],$_W['uniacid'],$uid,$fanid,$openid,'fm453_shopping',0,'view',$dologs);
			unset($dologs);
			//写入操作日志END
			return TRUE;
		}
	}
}
