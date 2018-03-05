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
 * @remark：数组操作函数
 */
defined('IN_IA') or exit('Access Denied');

//PHP stdClass Object转array
function fmFunc_array_object2($array) {
    if(is_object($array)) {
        $array = (array)$array;
     }
    if(is_array($array)) {
        foreach($array as $key=>$value) {
            $array[$key] = fmFunc_array_object2($value);
        }
    }
    return $array;
}

//多维数组排序
function fmFunc_array_multisort($multi_array,$sort_key,$sort=SORT_ASC){
	if(is_array($multi_array)){
		foreach ($multi_array as $row_array){
			if(is_array($row_array)){
				$key_array[] = $row_array[$sort_key];
			}else{
				return false;
			}
		}
	}else{
		return false;
	}
	array_multisort($key_array,$sort,$multi_array);
	return $multi_array;
}
