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
 * @remark：文件处理函数
 */
defined('IN_IA') or exit('Access Denied');

//创建目录
/*
@path 文件目录
*/
function fmFunc_file_mkdirs($path){
	if (!is_dir($path)) {
		fmFunc_file_mkdirs(dirname($path));
		mkdir($path);
	}
	return is_dir($path);
}

//写文件
/*
@filename 文件名
@data 要写入的数据
*/

function fmFunc_file_write($filename,$data){
	global $_GPC;
	global $_W;
	global $_FM;
	fmFunc_file_mkdirs(dirname($filename));
	file_put_contents($filename, $data);
	return is_file($filename);
}

//单图片上传
function fmFunc_file_image_upSingle(){
	$max_size = "5000000";//文件大小 5M以内
	$inputname=$_GPC['inputname'];
	$filedata=$_FILES[$inputname];
	$returns=array();//要返回的数据
	if ($_FILES[$inputname]["error"] > 0){
	  	$returns['error']= "错误: " . $_FILES[$inputname]["error"];
		$returns=json_encode($returns);
		echo $returns;//用return不行，必须要echo
	}else{
		$fname = $filedata["name"];
		$ftype = strtolower(substr(strrchr($fname, '.'), 1));
		$realtype =$filedata['type'];
		$fsize = $filedata["size"];
		$Storedpath = $filedata["tmp_name"];
		if ($fsize > $max_size) {
			$returns['error']= " 错误:"."您上传的图片超过了5M，请缩小或重新选择后再上传" . "";
			$returns=json_encode($returns);
			echo $returns;//用return不行，必须要echo
			exit;
		}
		load()->func('file');
		$imageid=date('YmdHis',TIMESTAMP);
		$imagename=$imageid."_".rand(1, 10000).".".$ftype;
		$imgurl="fm453_duokefu/images/".$imagename;
		file_move($Storedpath, IA_ROOT.'/attachment/'.$imgurl);//将文件从临时文件中复制到附件指定目录内
		chmod(IA_ROOT.'/attachment/'.$imgurl,0777);//修改文件权限
		//file_write($_W['attachurl'].'/fm453_duokefu/images',$filedata);
		$returns['initialPreview']=array();
		$returns['initialPreview'][0]="<img src='".tomedia($imgurl)."' class='file-preview-image' alt='pic with comment' title='pic with comment' id='".$imageid."'>";
		$returns['imgurl']=$imgurl;
		//在返回前，对returns进行json encode处理（file-input的ajax上传方式只接收json encode对象的返回）
		$returns=json_encode($returns);
		return $returns;
	}
}

/*
	在线解压ZIP包到模块目录
	@ $filename 要解压的文件名
	@ $workdir 脚本工作目录
	@ $path 解压到的目标路径
*/
function fmFunc_file_unzip($filename,$workdir,$path) {
	global $_GPC;
	$tips = '';
	if(chdir($workdir)){
		$tips.='切换到：'.$workdir.';</br>';
	};
	chmod($filename, 0777);   //修改当前文件权限
	$tips.= "当前解压执行目录：".getcwd().';</br>';// 更好的看清当前工作目录
	//先判断待解压的文件是否存在
	if(!file_exists($filename)){
		message("更新包 {$filename} 不存在！请先获取更新",'',error);
	}
	$starttime = explode(' ',microtime()); //解压开始的时间
	//将文件名和路径转成windows系统默认的gb2312编码，否则将会读取不到
	$filename = iconv("utf-8","gb2312",$filename);
	$path = iconv("utf-8","gb2312",$path);
	//打开压缩包
	$resource = zip_open($filename);
	$i = 1;
	//遍历读取压缩包里面的一个个文件
	while ($dir_resource = zip_read($resource)) {
		//如果能打开则继续
		if (zip_entry_open($resource,$dir_resource)) {
			//获取当前项目的名称,即压缩包里面当前对应的文件名
			$file_name = $path.zip_entry_name($dir_resource);
			//以最后一个“/”分割,再用字符串截取出路径部分
			$file_path = substr($file_name,0,strrpos($file_name, "/"));
			//如果路径不存在，则创建一个目录，true表示可以创建多级目录
			if(!is_dir($file_path)){
				if(!empty($file_path)) {
					$tips.='<li>读取到目录：'.$file_path.';</li>';         //非空目录，即根目录，则不提示创建目录
				}
				if(!file_exists($file_name)){
					if(mkdir($file_path,0777,true)){
						chmod($file_path,0777);
						$tips.='<li><i class="fa fa-check-circle" style="color:#0095f6"></i>目录：'.$file_path.'创建成功;</li>';
					}else {
						$tips.='<li><i class="fa fa-times-circle" style="color:red"></i>目录：'.$file_path.'创建失败;</li>';
					}
				}else{
					$file_path='根目录';
					$tips.='<li><i class="fa fa-check-circle" style="color:#0095f6"></i>：'.$file_name.'根目录'.$file_path.'</li>';
				}
			}
			//如果不是目录，则写入文件
			if(!is_dir($file_name)){
				//读取这个文件
				$file_size = zip_entry_filesize($dir_resource);
				$tips.='<li>读取到文件"'.$file_name.'"大小：'.$file_size.'bytes;<br/>';
				//最大读取20M，如果文件过大，跳过解压，继续下一个
				if($file_size<(1024*1024*20)){
					$file_content = zip_entry_read($dir_resource,$file_size);
					file_put_contents($file_name,$file_content);
					chmod($file_name,0777);
					$tips.='<i class="fa fa-check-circle" style="color:#0095f6"></i>成功:';
					$tips.='创建文件并写入内容：'.$file_name.';</li>';
				}else{
					$tips.= "<li> ".$i++." 此文件已被跳过，请进入空间手动处理，原因：文件过大， -> ".iconv("gb2312","utf-8",$file_name)." </li>";
				}
			}
			//关闭当前
			zip_entry_close($dir_resource);
		}
	}
	//关闭压缩包
	zip_close($resource);
	$endtime = explode(' ',microtime()); //解压结束的时间
	$thistime = $endtime[0]+$endtime[1]-($starttime[0]+$starttime[1]);
	$thistime = round($thistime,3); //保留3为小数
 	chmod($workdir, 0777);   //修改当前文件夹权限
	$tips.= '<li>更新包解压完毕！，本次解压花费：'.$thistime.' 秒。</li>';
	return $tips;
}

/*图片转BASE64码
@image  本地图片路径（暂不支持远程图片）
*/
function fmFunc_file_image_base64Encode($image) {
  $base64_image = '';
  $image_info = getimagesize($image);
  $image_data = fread(fopen($image, 'r'), filesize($image));
  $base64_image = 'data:' . $image_info['mime'] . ';base64,' . chunk_split(base64_encode($image_data));
  return $base64_image;
}
