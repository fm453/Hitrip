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
 * @remark：浏览量处理函数
 */
defined('IN_IA') or exit('Access Denied');

//更新浏览量(访客openid)，(浏览对象goods, ads, special, partner, article, vshop, system)，(对象记录ID) ,
function fmFunc_view($setfor=NULL,$id=NULL) {
	global $_GPC;
	global $_W;
	global $_FM;
	header("Content-type:text/html;charset=utf-8");//加上网页字符集强制设置，避免中文传输乱码
	$openid=$_W['openid'];
	$uniacid=$_W['uniacid'];
	$fanid=$_W['fans']['fanid'];
	$id=intval($id);
	$return=array();
	if($setfor==NULL) {
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
		unset($sql);
		unset($params);
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
			unset($addons);
			//写入操作日志END
			return TRUE;
		}
	}
}
