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
 * @remark 附件图片等文件管理；
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;

load()->model('account');//加载公众号函数
load()->func('tpl');
load()->func('file');
fm_load()->fm_func('route'); //获取路径函数
fm_load()->fm_func('tpl'); //获取自定义表单模板函数

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
$direct_url=fm_wurl($do,$ac,$operation,array());

if ($operation == 'display') {
	$pindex =max(1,intval($_GPC['page']));
	$psize = 30;
	$files=array();
	$list=array();
	$fileExts = array('jpg','gif','png');
	$fileDirs = array('/images/'.$_W['uniacid']);
	if($_W['isfounder'])
	{
		$fileDirs = array('/images/'.$_W['uniacid'],'fm453/images','fm453_shopping/image','fm453_shopping/file');
	}
	$list = cache_load('filelist_'.$_W['uniacid'].'_'.$_W['uid']);
	if(!$list)
	{
		$list=array();
		foreach($fileDirs as $dir)
		{
			$files[$dir]=array();
			foreach($fileExts as $ext)
			{
				$files = file_lists(ATTACHMENT_ROOT . $dir , 1, '.'.$ext, 0, 0);
				foreach($files as $file)
				{
					$filenames = explode('/',$file);
					$filename = $filenames[(count($filenames)-1)];
					$_list[$file]=array(
						'ext'=>$ext,
						'file'=>$file,
						'link'=>str_replace('attachment/','',$file),
						'title'=>$filename
					);
				}
				unset($file);
			}
			unset($files);
			unset($ext);
		}
		unset($dir);
		foreach($_list as $item)
		{
			$list[]=$item;
		}
		unset($item);
		cache_write('filelist_'.$_W['uniacid'].'_'.$_W['uid'],iserializer($list));
	}else{
		$list = iunserializer($list);
	}
	$total = count($list);

	$pager = pagination($total, $pindex, $psize);
	$nowList = array();
	$limit = (($pindex*$psize-$total) > 0) ? ($total - ($pindex-1)*$psize) : $psize ;
	for($i=0;$i<$limit;$i++)
		{
			$key = ($pindex-1)*$psize + $i;
			$nowList[] = $list[$key];
		}
	include $this->template($fm453style.$do.'/453');
}

?>