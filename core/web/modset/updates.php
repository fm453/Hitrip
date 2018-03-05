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
 * @remark 在线更新
 * 支持php远程下载FTP文件、解压缩包
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;
if(!$_W['isfounder']) {
	message('您不是站长，无权进行此操作！',referer(),'error');
}
if(!FM_DEBUG) {
	message('请您移步平台的云更新功能！',referer(),'info');
}
if($_W['username'] !=$settings['mainuser'] && !$_W['isfounder']) {
	message('本链接仅作为临时应急调试使用，请移步平台的云更新功能或联系开发者！',referer(),'info');
}
load()->func('tpl');
load()->func('file');
fm_load()->fm_func('file');

$shopname=$settings['brands']['shopname'];
$shopname = !empty($shopname) ? $shopname :FM_NAME_CN;
$_W['page']['title'] = $shopname.' 检查更新';

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

$_FILE_ = MODULE_ROOT.'/newversions';		//这里填写本机的绝对地址，结尾不能要“/”
/*
如果参数 $ext 不为空，则返回对应的文件类型，最后返回由文件名组成的数组
array file_lists(string $filepath, int $subdir, [string $ext], [int $isdir], [int $md5], [int $enforcement]);
 $filepath  string  目录名称
$subdir 	int 	是否搜索子目录
$ext 	string 	搜索扩展名称
$isdir 	int 	是否只搜索目录
$md5 	int 	是否生成MD5验证码
$enforcement 	int 	是否开启强制模式
*/
$localPatches = file_lists($_FILE_, 1, '.zip', 0, 0, 1); //搜索模块目录内更新包根目录里的版本文件夹
$localversions=array();
$sub_start= strlen('addons/'.FM_NAME.'/newversions/');
$sub_end= 0-(strlen(FM_NAME.'.zip')+1);
foreach($localPatches as $key=>$patch){
	$temp_version=substr($patch,$sub_start,$sub_end);
	$localversions[$key]=$temp_version;
}
$currentversion = FM_VERSION;
$versions = fmFunc_server_getVersions();
$serversion=array();
foreach ($versions  as $k=> $row) {
	$serversion[$k]=strval($row['version']);
}
$newversion=max($serversion);
if($operation=="check") {
	$version=$serversion[$_GPC['version']];
	//重新检查一次新版本
	if(checksubmit('checkversion')) {

	}
	elseif(empty($version)) {
		message('请选择一个版本,',fm_wurl($do,$ac,'display',array()),'error');
	}

	//获取下载地址
	if(checksubmit('getDownLink')) {
		if($version<$currentversion){
			message("版本不可降级回退","referer","error");
		}
		$_FILE_ = MODULE_ROOT.'/newversions/'.$version;		//这里填写本机的绝对地址，结尾不能要“/”
		$forupgrade= $version; //定义version版本号参数作为服务器对应更新文件所在的文件夹
		$nlen=strlen($forupgrade); //计算文件夹路径字符数
		$tips='<div class="main">
			<div class="panel panel-default">
			<div class="panel-heading">
			此次下载的版本：'.$version;
		$tips.='<a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=393213759&site=qq&menu=yes"><img border="0" src="http://wpa.qq.com/pa?p=2:393213759:51" alt="点击这里寻求帮助" title="QQ技术支持"/></a>';
		$tips.='</div>
		<div class="panel-body">
		<ol>';
		if(!empty($version)) {
			$downUrl =!empty($versions[$_GPC['version']]['downurl']) ? $versions[$_GPC['version']]['downurl'] : "未提供";
			$tips.='<li>下载地址：<a href="'.$downUrl.'" target="_blank" >'.$downUrl.'</a>；</li>';
			$dir= ($dir==$forupgrade) ? $dir : ('/'.$dir); //更新文件所在的目录
			$_FILE_ = $_FILE_.$dir; //本地的更新文件保存地址
			$tips.='<li>下载后，请自行使用ftp工具上传至您的服务器，储存地址为：'.$_FILE_.'；</li>';
			$tips.='<li>下载的文件不得进行重命名，直接上传即可</li>';
			$tips.='<li>更新前，请确保文件已经正确下载、上传</li>';
			$tips.='<li>确认无误后，请严格遵照顺序进行升级操作：（1）使用本应用的&nbsp;&nbsp;《检查数据表》功能先处理可能的数据表维护；（2）再使用&nbsp;&nbsp;《自助升级》功能对相关的业务进行更新；</li>';
			$tips.='<li>如果您的网络稳定，您也可使用直接在线升级的功能，由系统自动为您远程下载更新包并在线执行数据表维护；执行完毕后，您需要手动使用一次&nbsp;&nbsp;《自助升级》功能</li>';
		}else {
			message('请选择一个版本,',fm_wurl('modset',$ac,'display',array()),'error');
		}
	}

	//远程下载
	if(checksubmit('downOnline')) {
		if($version<$currentversion){
			message("版本不可降级回退","referer","error");
		}
		$hostname="dev.hiluker.com";
		$hostport="21";
		// $suapi=$_FM['settings']['shouquan']['suapi'];
		$loginname="daemon";
		// $susecret=$_FM['settings']['shouquan']['susecret'];
		$password="daemon";
		$fc = ftp_connect($hostname,$hostport) or die(message('远程服务器连接受限(失败)，请联系您的网站管理员或开发者','','error'));
		$fc_rw = ftp_login($fc,$loginname,$password) or die(message('远程服务器登陆受限(失败)，请联系您的网站管理员或开发者','','error'));
		ftp_set_option($fc,FTP_TIMEOUT_SEC,120);        //设置超时时间
		ftp_pasv($fc,TRUE);     //开启被动传输模式；因为开启了防火墙

		$tips='<div class="main">
			<div class="panel panel-default">
			<div class="panel-heading">
			此次下载的版本：'.$version;
		$tips.='<a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=393213759&site=qq&menu=yes"><img border="0" src="http://wpa.qq.com/pa?p=2:393213759:51" alt="点击这里寻求帮助" title="QQ技术支持"/></a>';
		$tips.='</div>
		<div class="panel-body">
		<ol>';

		//进入本地更新包存放目录
		$_FILE_ = MODULE_ROOT.'/newversions';		//这里填写本机更新包存放目录，结尾不能用“/”
		if(!file_exists($_FILE_)) {
			mkdir($_FILE_);	//如果不存在，则创建
			chmod($_FILE_,0777);
		}
		chdir($_FILE_);
		if(!file_exists($version)) {
			//本地机器上指定目录，不存在就创建一个
			$tips.="<li>在本地创建新版本目录：".$version."</li>";
			//mkdir($version,0777);	//该方法不能修改文件权限
			mkdir($version);
			chmod($version, 0777);
		}
		//进入本地机新版本目录
		$_FILE_ =$_FILE_ .'/'.$version;
		chdir($_FILE_);
		$tips.='<li>更新包储存地址为：'.$_FILE_.'；</li>';

		//定义version版本号参数作为服务器对应更新文件所在的文件夹
		$forupgrade= $version;
		$nlen=strlen($forupgrade); //计算文件夹路径字符数
		$tips.='<li>远程更新文件夹目录：'.$forupgrade.'，字符串长度校验：'.($nlen+1).'；</li>';

		//切换到版本目录；
		ftp_chdir($fc,FM_NAME);
		ftp_chdir($fc,$forupgrade);
		ftp_cdup($fc);//将版本号对应的目录设置为FTP根目录
		$fraw = ftp_rawlist($fc,$forupgrade,false);//列出该目录的文件名（含子目录）详细情况，存储在数组中；注意 ，文件名中包含了路径名; 以空格分隔(最后为true时将列出全部子目录文件)
		$size = sizeof($fraw);
		//为更新方便，统一设定使用zip包更新，每个版本目录下有且仅有一个zip包文件
		if($size >0) {
			$tips.="<li>";
			$tips.="<ol>";
			$tips.="<li>进入服务器";
			$tips.='一共有'. $size.'个文件(夹);其中的文件将被下载';
			$tips.="</li>";

			for($i=0;$i<$size;$i++) {
				$temp_array=explode(" ",$fraw[$i]);
				$fn[$i]=end($temp_array);	//取最后一个数组
				//$newfn[$i]=substr($fn[$i],$nlen+1);//截取字符串的第$nlen+2个之后的字符作为要比对的新文件名
				$newfn[$i]=$fn[$i];
				//提取文件和目录，规定开头格式为字母或数字，以剔除 .,.. 这两个目录
				if(preg_match('/^[a-zA-Z0-9]/',$newfn[$i])) {
					$tips.="<li>正在下载：".$newfn[$i].'；';
					$tips.="<ol><li>服务器文件：".$fraw[$i].'；</li>';
				 	//是文件时直接下载 ；
					if(preg_match('/^-r/',$fraw[$i])) {
						$tips.="当前本地目录是：".getcwd();
						//if(ftp_get($fc,$newfn[$i],$fn[$i],FTP_BINARY)) {
						if(ftp_get($fc,$newfn[$i],$forupgrade.'/'.$fn[$i],FTP_BINARY)) {
							$tips.="<li>下载".$newfn[$i]."成功</li>";
						} else {
							$tips.="<li>下载".$newfn[$i]."失败</li>";
						} 						//以上，文件下载结束
					}else{
						$tips.="<li>".$newfn[$i]."为限制目录，跳过</li>";
					}
					$tips.='</ol></li>';
				}
				//提取文件，目录结束
			}
			$tips.="</ol>";
			$tips.="</li>";
			ftp_quit($fc);
		}
		$tips.='<li>恭喜，更新包已经下载完成，请使用<a href="'. fm_wurl($do,$ac,'modify',array()).'" class="btn btn-primary " />查看本地更新包</a></li>';
		$tips.='</ol></div></div>';
	}
}
elseif($operation=="modify") {
	$opp=$_GPC['opp'];
	$version=$_GPC['version'];

	if($opp=='clearDir') {
		//清空当前版本的本地包目录
		if(empty($version)) {
			message('请选择一个版本,',fm_wurl('modset',$ac,'display',array()),'error');
		}
		$result=rmdirs($_FILE_.'/'.$version,false);	//删除版本对应目录;
		if($result) {
			message('该版本目录已成功清空；如果需要再次使用该版本,请重新下载或上传',fm_wurl($do,$ac,'display',array()),'success');
		}
	}
	if($opp=='unzip'){
		if(empty($version)) {
			message('请选择一个版本,',fm_wurl($do,$ac,'display',array()),'error');
		}
		elseif($version<$currentversion){
			message("版本不可降级回退","referer","error");
		}
		$path=IA_ROOT .'/addons/';    //不要加/，不然会提示路径错误
		$filename=FM_NAME.'.zip';
		$workdir=MODULE_ROOT.'/newversions/'.$version;

		$tips ='<div class="main">
			<div class="panel panel-default">
			<div class="panel-heading">
			此次更新的版本：'.$version;
		$tips.='<a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=393213759&site=qq&menu=yes"><img border="0" src="http://wpa.qq.com/pa?p=2:393213759:51" alt="点击这里寻求帮助" title="QQ技术支持"/></a>';
		$tips.='</div>
		<div class="panel-body">
		<ol>';

		$result =fmFunc_file_unzip($filename,$workdir,$path);
		if($result) {
			$tips .=$result;
			$tips.='<li>恭喜，更新包已经下载完成，请使用<a href="'. fm_wurl($do,$ac,'display',array()).'" class="btn btn-primary " />重新检查版本</a></li>';
		}
		$tips.='</ol></div></div>';
	}
}
include $this->template('modset/updates');
