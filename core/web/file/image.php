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
 * @remark 图片在线编辑；
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;

load()->model('account');//加载公众号函数
load()->func('tpl');
load()->func('file');
fm_load()->fm_class('cropimage'); //图片剪切类
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

$file = htmlspecialchars($_GPC['file']);
$file = trim($file);

if(stripos('http://', $file) == 0 || stripos('https://', $file) == 0) {
	$old_file = $file;
	$file = (stripos($_W['attachurl'], $file) === false) ? str_replace($_W['attachurl'],'',$file) : false ;
	if(!$file)
	{
		message("暂不支持对外部图片进行编辑！",referer(),'error');
	}
}
$file = (stripos(ATTACHMENT_ROOT, $file) === false) ? str_replace(ATTACHMENT_ROOT,'',$file) : $file ;
//$file = (stripos('/', $file) == 0) ? substr($file, 1) : $file ;
$type = exif_imagetype(ATTACHMENT_ROOT .$file);	//文件类型
$ext =  image_type_to_extension($type);	//文件类型对应的文件名后缀 //不一定是本文件后缀

$paras = array(
	'x'=>'横向起点',
	'y'=>'纵向起点',
	'width'=>'新宽度',
	'height'=>'新高度',
	'rotate'=>'旋转角度'
	);

if ($operation == 'display') {
	$filename = $file;
	//include $this->template($fm453style.$do.'/453');
	include $this->template($fm453style.$do.'/'.$ac.'/crop');
}elseif($operation == 'modify') {
	//安全起见，不直接接收客户端上传文件、指定文件， 只接收要裁剪的参数，转成数组
	if($_POST['newImageData'])
	{
		//有上传了新图片
		$base64_url = $_POST['newImageData'];
		$base64_body = substr(strstr($base64_url,','),1);	//去除头部
		$newimageData= base64_decode($base64_body );	//解码
		file_write($file,$newimageData);	//写入到对应的图片
	}
	$data=array();
	foreach($paras as $key => $para)
	{
		$data[$key]=$_POST[$key];
	}
	$result = fmFunc_image_crop(IA_ROOT.'/attachment/'. $file, $data);
	if($result['result']) {
		message('图片剪切成功,如页面图片缓存未更新，请刷新即可',fm_wurl($do,$ac,'display',array('file'=>$file)),'success');
	}else{
		message($result['message'],fm_wurl($do,$ac,'display',array('file'=>$file)),'error');
	}
	//echo json_encode($result);
}

?>