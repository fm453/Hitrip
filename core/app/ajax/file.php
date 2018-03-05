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
 * @remark AJAX方式处理文件
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;

load()->func('file');
//入口判断
$do=$_GPC['do'];
$ac=$_GPC['ac'];
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'index';
if($op=='delete') {
	//删除文件
	$filename= $_GPC['filename'];
	$filetypes=array('image'=>'images','file'=>'files','qrcode'=>'erweima','other'=>'none');
	$type= ($filetypes[$_GPC['filetype']]) ? $_GPC['filetype'] : 'none';

	$delurl = ($type!='none') ?  ("fm453/".$filetypes[$type]."/".$_FM['member']['info']['uid'].'/'.$filename) :  $filename;
	//$delurl = ATTACHMENT_ROOT.'/'.$delurl;	//微擎系统已自动加上附件目录
	exit(json_encode(1));//不是真的删除文件
	if (file_delete($delurl)) {
		echo json_encode(1);
	} else {
		echo json_encode(0);
	}
}
elseif($op=='upload') {
/*
*上传文件到附件fm453目录; 如果失败，返回错误数组，否则返回保存的文件路径
*/
	$max_size = "5000000";//文件大小 5M以内
	$inputname=$_GPC['inputname'];
	$backid=$_GPC['backid'];
	$filedata=$_FILES[$inputname];
	$returns=array();//要返回的数据
	if ($_FILES[$inputname]["error"] > 0){
  		$returns['error']= "错误: " . $_FILES[$inputname]["error"];
		$returns=json_encode($returns);
		echo $returns;
	}else{
		$fname = $filedata["name"];
		$ftype = strtolower(substr(strrchr($fname, '.'), 1));
		$realtype =$filedata['type'];
		$fsize = $filedata["size"];
		$Storedpath = $filedata["tmp_name"];
 		if ($fsize > $max_size) {
			$returns['error']= " 错误:"."您上传的图片超过了5M，请缩小或重新选择后再上传" . "";
			$returns=json_encode($returns);
			echo $returns;
			exit;
		}
		$imageid=date('YmdHis',TIMESTAMP)."_".rand(1, 10000);
		$imagename=$imageid.".".$ftype;
		$imgurl="fm453/images/".$_FM['member']['info']['uid'].'/'.$imagename;
		file_move($Storedpath, IA_ROOT.'/attachment/'.$imgurl);//将文件从临时文件中复制到附件指定目录内
		chmod(IA_ROOT.'/attachment/'.$imgurl,0777);//修改文件权限
		$returns['initialPreview']=array();
		$returns['initialPreview'][0]="
		<img src='".tomedia($imgurl)."' class='file-preview-image' alt='pic zone by fm453' title='pic zone by fm453' id='".$imageid."'  data-formEle='".$backid."'  data-filename='".$imgurl."' style='height:70px;' >".
		'<div class="file-thumbnail-footer">
    <div class="file-caption-name" title="" style="width: 100px;">'.$imagename.'</div>
    <div class="file-actions">
    	<div class="file-footer-buttons">
			<button type="button" class="fm-file-remove btn btn-xs btn-default" data-src="'.$imagename.'" data-imageid="'.$imageid.'" data-backid="'.$backid.'" title="Remove file"><i class="glyphicon glyphicon-trash text-danger"></i></button>
    	</div>
    	<div class="file-upload-indicator" tabindex="-1" title="uploaded success"><i class="glyphicon glyphicon-ok-sign text-info"></i></div>
    	<div class="clearfix"></div>
	</div>
</div>';
		$returns['backid']=$backid;
		$returns['inputname']=$inputname;
		$returns['imgurl']=$imgurl;
		$returns['imgname']=$imagename;
		$returns['imgid']=$imageid;
		$returns['imgsrc']= tomedia($imgurl);
		//在返回前，对returns进行json encode处理（file-input的ajax上传方式只接收json encode对象的返回）
		$returns=json_encode($returns);

		//写入缓存
		setcookie('uploadedfile', $value =$imgurl, $expire = (15*60), $path = null, $domain = null, $secure = null, $httponly = null);
		cache_write('uploadedfile'.$_FM['member']['info']['uid'],$imgurl);

		echo $returns;//用return不行，必须要echo
		exit();
	}
}
elseif($op=='singleImageupload') {
/*
*上传文件到附件fm453目录; 如果失败，返回错误数组，否则返回保存的文件路径
*/
	$max_size = "5000000";//文件大小 5M以内
	$inputname=$_GPC['inputname'];
	$backid=$_GPC['backid'];
	$filedata=$_FILES[$inputname];
	$returns=array();//要返回的数据

	if ($_FILES[$inputname]["error"] > 0){
  		$returns['error']= "错误: " . $_FILES[$inputname]["error"];
		$returns=json_encode($returns);
		echo $returns;
	}else{
		$fname = $filedata["name"];
		$ftype = strtolower(substr(strrchr($fname, '.'), 1));
		$realtype =$filedata['type'];
		$fsize = $filedata["size"];
		$Storedpath = $filedata["tmp_name"];
 		if ($fsize > $max_size) {
			$returns['error']= " 错误:"."您上传的图片超过了5M，请缩小或重新选择后再上传" . "";
			$returns=json_encode($returns);
			echo $returns;
			exit();
		}
		$imageid=date('YmdHis',TIMESTAMP)."_".rand(1, 10000);
		$imagename=$imageid.".".$ftype;
		$imgurl="fm453/images/".$_FM['member']['info']['uid'].'/'.$imagename;
		file_move($Storedpath, IA_ROOT.'/attachment/'.$imgurl);//将文件从临时文件中复制到附件指定目录内
		chmod(IA_ROOT.'/attachment/'.$imgurl,0777);//修改文件权限
		$returns['initialPreview'] ="<img src='".tomedia($imgurl)."' alt='{$imagename}' title='{$imagename}' id='".$imageid.'style="width:160px"><h6 class="text-muted">点击选择文件</h6>';

		$returns['imgurl']=$imgurl;
		//在返回前，对returns进行json encode处理（file-input的ajax上传方式只接收json encode对象的返回）
		$returns=json_encode($returns);
		echo $returns;//用return不行，必须要echo
		exit();
	}
}
