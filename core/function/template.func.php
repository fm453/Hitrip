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
 * @remark：文件模板化，php格式
 */
defined('IN_IA') or exit('Access Denied');

	//前端模板处理
function fmFunc_template_m($filename,$style=null){
	$style = !empty($style) ? $style : FM_APPSTYLE;
	$file1 = FM_TEMPLATE . "mobile/{$style}{$filename}.php";
	$file2 = FM_TEMPLATE . "mobile/{FM_APPSTYLE}{$filename}.php";

	$file = $file1;
	if(!is_file($file1)) {
		if(!is_file($file2)) {
			if(FM_DEBUG){
				trigger_error("模板文件".$file."不存在",E_USER_ERROR);
			}
			return false;
		}
		$file = $file2;
	}
	return $file;
}
