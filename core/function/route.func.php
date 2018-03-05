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
 * @remark	各执行路径汇总： do > ac > op  ；（fm_status > fm_submit）
 */
defined('IN_IA') or exit('Access Denied');
//用于前台关键词规则入口的清单(APP)
function fmFunc_route_list_app(){
	$routes=array('index','search','special','member','order','help','fenxiao','appweb','chat','needs');
	return $routes;
}
//合法路径清单(WEB)
function fmFunc_route_list_web(){
	$files = glob(FM_CORE.'web'.DIRECTORY_SEPARATOR.'*'.DIRECTORY_SEPARATOR.'_routes.php');	//需要PHP v>=4
	foreach($files as $file)
	{
		$file = str_replace(FM_CORE.'web'.DIRECTORY_SEPARATOR,'',$file);
		$file = str_replace(DIRECTORY_SEPARATOR.'_routes.php','',$file);
		$routes[] = trim($file);
	}
	return $routes;
}

/**——————————————————————下面处理后端路径——————————————————————**/
//完整版
function fmFunc_route_web() {
	global $_W;
	$uniacid = $_W['uniacid'];
	$return =array();
	$do=array();
	$ac=array();
	$op=array();
	$files = glob(FM_CORE.'web'.DIRECTORY_SEPARATOR.'*'.DIRECTORY_SEPARATOR.'_routes.php');
	foreach($files as $file){
		// chmod($file,0644);
		$_file = $file;
		$_file = str_replace(FM_CORE.'web'.DIRECTORY_SEPARATOR,'',$_file);
		$_file = str_replace(DIRECTORY_SEPARATOR.'_routes.php','',$_file);
		$route = trim($_file);
		require $file;
		// 添加对某些公众号隐藏或仅显示给某些公众号
		if(isset($do[$route]['hide'])){
			if(is_array($do[$route]['hide'])){
				if(in_array($uniacid,$do[$route]['hide'])  && $_GET['ac']!="showroute"){
					unset($do[$route]);
				}
			}elseif($do[$route]['hide']){
				if($uniacid==$do[$route]['hide']  && $_GET['ac']!="showroute") {
					unset($do[$route]);
				}
			}
		}elseif(isset($do[$route]['show'])){
			if(is_array($do[$route]['show'])){
				if(!in_array($uniacid,$do[$route]['show'])){
					unset($do[$route]);
				}
			}elseif($do[$route]['show']){
				if($uniacid!=$do[$route]['show']) {
					unset($do[$route]);
				}
			}
		}
	}
	$return=$do;
	return $return;
}

// do路径,已排序
function fmFunc_route_web_do() {
	$return =array();
	$do=array();
	$all_do=fmFunc_route_web();
	foreach($all_do as $key => $td){
		$d[$td['sn']]=$key;
	}
	ksort($d, 1);//递增排序，小数在前
	foreach($d as $t){
		$do[]=$t;
	}
	$return=$do;
	return $return;
}

// ac路径,已排序; 指定do
function fmFunc_route_web_ac($do) {
	$return =array();
	$all_do=fmFunc_route_web();
	$all_ac=$all_do[$do]['ac'];
	foreach($all_ac as $key => $td){
		$d[$td['sn']]=$key;
	}
	ksort($d, 1);//递增排序，小数在前
	foreach($d as $t){
		$ac[]=$t;
	}
	$return=$ac;
	return $return;
}

// op列表，简单模式
function fmFunc_route_web_op_single($do,$ac) {
	$return =array();
	$all_do=fmFunc_route_web();
	$all_ac=$all_do[$do]['ac'];
	$all_op=$all_ac[$ac]['op'];
	foreach($all_op as $key => $td){
		$op[]=$key;
	}
	$return=$op;
	return $return;
}

// op列表，完整模式,带title
function fmFunc_route_web_op_multi($do,$ac) {
	$return =array();
	$all_do=fmFunc_route_web();
	$all_ac=$all_do[$do]['ac'];
	$all_op=$all_ac[$ac]['op'];
	foreach($all_op as $key => $td){
		$op[$key]=$td['title'];
	}
	$return=$op;
	return $return;
}

/**——————————————————————下面处理前端路径(简化，不作路径约束，可自行扩展)——————————————————————**/
//完整版
function fmFunc_route_app() {
	$do =array();
	$do['index']=array(
		'title'=>'商城首页',
		'icon'=>'fa fa-home',
		'sn'=>'0',
	);
	$do['order']=array(
		'title'=>'商城订单入口',
		'icon'=>'fa fa-shopping-cart',
		'sn'=>'10',
	);
	$do['search']=array(
		'title'=>'搜索入口',
		'icon'=>'fa fa-search',
		'sn'=>'20',
	);
	$do['member']=array(
		'title'=>'会员入口',
		'icon'=>'fa fa-user',
		'sn'=>'30',
	);
	// $do['special']=array(
	// 	'title'=>'专题入口',
	// 	'icon'=>'fa fa-tags',
	// 	'sn'=>'40',
	// );
	// $do['fenxiao']=array(
	// 	'title'=>'分销入口',
	// 	'icon'=>'fa fa-share-alt',
	// 	'sn'=>'50',
	// );
	// $do['help']=array(
	// 	'title'=>'帮助入口',
	// 	'icon'=>'fa fa-question',
	// 	'sn'=>'60',
	// );
	$do['needs']=array(
		'title'=>'表单入口',
		'icon'=>'fa fa-edit',
		'sn'=>'70',
	);
	// $do['chat']=array(
	// 	'title'=>'微聊入口',
	// 	'icon'=>'fa fa-wechat',
	// 	'sn'=>'80',
	// );
	// $do['appweb']=array(
	// 	'title'=>'核销入口',
	// 	'icon'=>'fa fa-tablet',
	// 	'sn'=>'100',
	// );
	$return=$do;
	return $return;
}


/**——————————————————————下面处理关键词\业务功能\自定义菜单等微擎入口路径——————————————————————**/

function fmFunc_route_entry_get($type) {
	$return =array();
	$entry_type = 'cover,profile,menu,home,mine';
	$entry=array();

//业务功能菜单
	if($type=='menu'){
		$routes=fmFunc_route_web();
		// 将全部路径均显示出来
		// foreach($routes as $key=>$route){
		// 	$entry[$key]=array(
		// 		'icon' => $route['icon'],
		// 		'title' => $route['title']
		// 	);
		// }

		//只将使用一个主入口
		$key = 'index';
		$entry[$key]=array(
			'icon' => 'fa fa-sitemap',
			'title' => '主工作台'
		);
	}
	elseif($type=='cover'){
		//业务功能菜单
		$routes=fmFunc_route_app();
		foreach($routes as $key=>$route){
			$entry[$key]=array(
				'icon' => $route['icon'],
				'title' => $route['title']
			);
		}
	}
	unset($routes);
	return $entry;
}


/*______________________________________路径文件操作______________________________*/
//重写后台路径文件(data目录)
/*
@dos\acs\ops 各路径配置值
@route 对应的路径
*/

function fmFunc_route_fileWrite($dos,$acs,$ops,$route)
{
	fm_load()->fm_func('file');
	global $_FM;
	global $_W;
	$filelink = FM_WEB.$route.DIRECTORY_SEPARATOR;
	$filename = '_routes.php';
	$file = $filelink.$filename;
	$do = array();
	$ac = array();
	$op = array();

	$content = "<?php";
	$content .= "\r\n";
	$content .= "/*";
	$content .= "\r\n";

	$content .= "* @remark 后台路径与权限配置文件";
	$content .= "\r\n";

	$content .= "* @lasttime 更新时间:".date("Y-M-D H:i:s");
	$content .= "\r\n";

	$content .= "*/";
	$content .= "\r\n";

	$temp = "\$ac['".$route."'] = array();";

	if(is_array($acs) && !empty($acs)){
		foreach($acs as $ac_k => $ac){
			unset($ac['op']);

			$temp_op = "\r\n";
			$temp_op .= "\$op['".$route."']['".$ac_k."'] = array();";
			foreach($ops[$ac_k] as $ops_k => $op){
				$temp_op .= "\r\n";
				$temp_op .= "\$op['".$route."']['".$ac_k."']['".$ops_k."'] = array();";
				foreach($op as $k => $v){
					$temp_op .= "\r\n";
					$temp_op .= "\$op['".$route."']['".$ac_k."']['".$ops_k."']['".$k."'] = '".$v."';";
				}
			}

			$tempac = "\r\n";
			$tempac .= $temp_op;
			$tempac .= "\r\n";

			$tempac .= "\$ac['".$route."']['".$ac_k."'] = array();";
			foreach($ac as $k => $v){
				if(is_string($v)){
					$tempac .= "\r\n";
					$tempac .= "\$ac['".$route."']['".$ac_k."']['".$k."'] = '".$v."';";
				}elseif(is_array($v)){
					$tempac .= "\r\n";
					$tempac .= "\$ac['".$route."']['".$ac_k."']['".$k."'] = array();";
					foreach($v as $_k => $_v){
						$tempac .= "\r\n";
						$tempac .= "\$ac['".$route."']['".$ac_k."']['".$k."'] ['".$_k."'] = '".$_v."';";
					}
				}
			}
			$tempac .= "\r\n";
			$tempac .= "\$ac['".$route."']['".$ac_k."']['op'] = \$op['".$route."']['".$ac_k."'];";

			$temp .= $tempac;
			unset($tempac);
		}
	}
	$content .= $temp;

	unset($temp);
	$temp = "\r\n";
	$temp .= "\r\n";
	$temp .= "\$do['".$route."'] = array();";
	unset($dos['ac']);
	foreach($dos as $k => $v){
		if(is_array($v)){
			$temp .= "\r\n";
			$temp .= "\$do['".$route."']['".$k."'] = array();";
			foreach($v as $_k => $_v){
				$temp .= "\r\n";
				if(is_numeric($_k)){
					$temp .= "\$do['".$route."']['".$k."'][".$_k."] = '".$_v."';";
				}else{
					$temp .= "\$do['".$route."']['".$k."']['".$_k."'] = '".$_v."';";
				}

			}
		}elseif(is_string($v)){
			$temp .= "\r\n";
			$temp .= "\$do['".$route."']['".$k."'] = '".$v."';";
		}
	}
	$temp .= "\r\n";
	$temp .= "\$do['".$route."']['ac'] = \$ac['".$route."'];";
	$content .= $temp;

	return fmFunc_file_write($file,$content);
}

/*
@dos\acs\ops 各路径配置值
@route 对应的路径
*/

function fmFunc_route_dataMerge($data,$route)
{
	fm_load()->fm_func('file');
	global $_FM;
	global $_W;
	$filelink = FM_WEB.$route.DIRECTORY_SEPARATOR;
	$filename = '_routes.php';
	$file = $filelink.$filename;
	$do = array();
	$ac = array();
	$op = array();
	if(file_exists($file)){
		require_once $file;
	}

	$temp  = "";
	$_temp = "";
	$_do = array('title','icon','sn');
	$temp .= "\$do['".$route."'] = array(";
	foreach($_do as $k => $v){
		$_v = isset($do[$v]) ? $do[$v] : $data[$route][$v];
		if(is_string($_v)){
			$temp .= "\r\n";
			$temp .= "'".$v."' => ";
			$temp .= "'".$_v."',";

		}
	}
	$temp .= "\r\n";
	$temp .= "'ac' => \$ac['".$route."']";
	$temp .= ");";

	$_temp  = "";
	$_do = array('hide','show');
	foreach($_do as $k => $v){
		$temp .= "\$do['".$route."']['".$v."'] = array(";
		$_v = isset($data[$route][$v]) ? $data[$route][$v] : array();
		if(is_array($_v)){
			foreach($_v as $_k)
			$temp .= "\r\n";
			$temp .= "'".$v."' => ";
			if($k+1 <count($_do)){
				$temp .= "'".$_v."',";
			}else{
				$temp .= "'".$_v."'";
			}

		}
		$temp .= ");";
	}


	$_do = array('title','icon','sn','hide','show','ac');
	$content .= "\$FmApiDataCache = '';";
	$content .= "\r\n";
	$content .= "\$FmApiDataCache = ";
	$content .= "'".$data."'";	//写入前已经进行序列化操作，以便能存储各种类型的数据
	$content .= ";";

	fmFunc_file_write($file,$content);
}
