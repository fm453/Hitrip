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
 * @remark：二维码处理函数
 */
defined('IN_IA') or exit('Access Denied');
require  FM_CORE."class/qrcode/phpqrcode.php";//引入PHP QR库文件
//所有二维码文件存在于系统附件目录指定位置
//二维码命名格式
function fmFunc_qrcode_name_w($platid,$do,$ac,$operation,$id){
	global $_GPC;
	global $_W;
	global $_FM;
	$qrcode ='fm453/erweima/';
	$qrcode .= 'web/';
	$qrcode .= intval($platid).'_';
	$qrcode .= $_W['uniacid'].'_';
	$qrcode .= $do.'_';
	$qrcode .= $ac.'_';
	$qrcode .= $operation.'_';
	$qrcode .= intval($id);
	$qrcode .= '.png';
	return $qrcode;
}

function fmFunc_qrcode_name_m($platid,$do,$ac,$operation,$id,$mid,$openid){
	global $_GPC;
	global $_W;
	global $_FM;
	$openid = ($openid) ? $openid : 0;
	$qrcode ='fm453/erweima/';
	$qrcode .= 'app/';
	$qrcode .= intval($platid).'_';
	$qrcode .= $_W['uniacid'].'_';
	$qrcode .= $do.'_';
	$qrcode .= $ac.'_';
	$qrcode .= $operation.'_';
	$qrcode .= intval($id).'_';
	$qrcode .= intval($mid).'_';
	$qrcode .= $openid;
	$qrcode .= '.png';
	return $qrcode;
}

//生成后台二维码
function fmFunc_qrcode_w($platid,$do,$ac,$operation,$id,$value) {
	global $_GPC;
global $_W;
global $_FM;
	chdir(ATTACHMENT_ROOT);//切换到系统附件目录
	load()->func('file');//加载文件处理函数
	file_write('fm453/erweima/'.'share.png', '基础二维码，等待写入新数据');//确保创建一个share.png文件，同时可以二维码目录存在
	//开始文件目录的修改或创建
	$dir ='';

	$_FILE_= 'fm453';  //指定fm453名下模块的专属附件存储目录
	chmod($_FILE_,0777);//修改权限
	chdir($_FILE_);
	$dir .=$_FILE_.'/';

	$_FILE_= 'erweima';  //指定二维码存储目录
	chmod($_FILE_,0777);//修改权限
	chdir($_FILE_);
	$dir .=$_FILE_.'/';

	$_FILE_ = 'web';
	if(!file_exists($_FILE_)) {
			mkdir($_FILE_, 0777);
	}  //不存在就创建一个
	chmod($_FILE_,0777);//修改权限
	chdir($_FILE_);
	$dir .=$_FILE_.'/';

	//二维码图片处理
	$value = !empty($value) ? $value : $_W['siteurl'];//要写入二维码中的数据，默认取当前网址
	$platid = (intval($platid)>0) ? intval($platid) : $_W['uniacid'];
	$id =intval($id);
	$imgname =$platid . '_' . $_W['uniacid'] . '_'. $do . '_' . $ac . '_' . $operation . '_' . $id .  '.png';
	file_write($dir.$imgname, '基础二维码，等待写入新数据');
	chmod($imgname,0777);

	$errorCorrectionLevel = "L"; //容错率低LOW
	$size = 5;//文件尺寸大小
	$margin = 3;
	//图片长宽为 $size*40+$size *$margin;
	$saveandprint=TRUE;
	$imgurl = ATTACHMENT_ROOT .$dir.$imgname;

	$result = QRcode::png($value, $imgurl, $errorCorrectionLevel, $size, $margin,$saveandprint);
	return $dir.$imgname;//返回图片相对于附件目录的位置名，以便其他程序调用
}

//生成前台二维码
function fmFunc_qrcode_m($mid,$id,$value) {
	global $_GPC;
	global $_W;
	global $_FM;
	chdir(ATTACHMENT_ROOT);//切换到系统附件目录
	load()->func('file');//加载文件处理函数
	//开始文件目录的修改或创建
	$dir ='';

	$_FILE_= 'fm453';  //指定fm453名下模块的专属附件存储目录
	chmod($_FILE_,0777);//修改权限
	chdir($_FILE_);
	$dir .=$_FILE_.'/';

	$_FILE_= 'erweima';  //指定二维码存储目录
	chmod($_FILE_,0777);//修改权限
	chdir($_FILE_);
	$dir .=$_FILE_.'/';

	$_FILE_ = 'app';
	if(!file_exists($_FILE_)) {
			mkdir($_FILE_, 0777);
	}  //不存在就创建一个
	chmod($_FILE_,0777);//修改权限
	chdir($_FILE_);
	$dir .=$_FILE_.'/';

	//二维码图片处理
	$value = !empty($value) ? $value : $_W['siteurl'];//要写入二维码中的数据，默认取当前网址
	$mid = intval($mid);
	$mid = ($mid>0) ? $mid : 0;
	$from_user = ($_W['openid']) ? $_W['openid'] : 0;
	$imgname = $mid . '_' . $id . '_' . $from_user .  '.png';
	file_write($dir.$imgname, '基础二维码，等待写入新数据');
	chmod($imgname,0777);

	$errorCorrectionLevel = "L"; //容错率低LOW
	$size = 3;//文件尺寸大小
	$margin = 4;
	//图片长宽为 $size*40+$size *$margin;
	$saveandprint=TRUE;
	$imgurl = ATTACHMENT_ROOT .$dir.$imgname;

	$result = QRcode::png($value, $imgurl, $errorCorrectionLevel, $size, $margin,$saveandprint);
	return $dir.$imgname;//返回图片相对于附件目录的位置名，以便其他程序调用
}

/*更新二维码
@$forceRefresh 是否强制刷新
*/
function fmFunc_qrcode_check($imgname,$value,$forceRefresh=null){
	chdir(ATTACHMENT_ROOT);//切换到系统附件目录
	load()->func('file');//加载文件处理函数
	$pre=substr($imgname, 0,14);
	if($pre !== 'fm453/erweima/') {
		return FALSE;
	}
	if(file_exists($imgname) && !forceRefresh) {
		return TRUE;//存在就直接返回
	}
	$result=file_write($imgname,$value);
	$_FILES_=explode('/',$imgname);
	$lenth=count($_FILES_)-1;
	for($i=0;$i<$lenth;$i++){
		chdir($_FILES_[$i]);
		if(file_exists($_FILES_[$i])) {
			chmod($_FILES_[$i],0777);
		}
	}
	// var_dump(getcwd());//查看当前目录
	$shortname = $_FILES_[$lenth];
	chmod($shortname,0777);//不支持直接将 $_FILES_[$lenth] 放进来
	$imgurl = ATTACHMENT_ROOT .$imgname;
	QRcode::png($value, $imgurl, $errorCorrectionLevel='L', $size=3, $margin=4,$saveandprint=FALSE);
	return TRUE;
}
