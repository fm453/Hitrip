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
 * @remark：地理位置处理相关函数
 */
defined('IN_IA') or exit('Access Denied');

function fmFunc_lbs_rad($d) {
    return $d * 3.1415926535898 / 180.0;
}

//距离计算函数
function fmFunc_lbs_getDistance($lat1, $lng1, $lat2, $lng2)
{
	$EARTH_RADIUS = 6378.137;
	$radLat1 = fmFunc_lbs_rad($lat1);
	$radLat2 = fmFunc_lbs_rad($lat2);
	$a = $radLat1 - $radLat2;
	$b = fmFunc_lbs_rad($lng1) - fmFunc_lbs_rad($lng2);
	$s = 2 * asin(sqrt(pow(sin($a/2),2) +
	cos($radLat1)*cos($radLat2)*pow(sin($b/2),2)));
	$s = $s *$EARTH_RADIUS;
	$s = round($s * 10000) / 10000;
	return $s;
}
