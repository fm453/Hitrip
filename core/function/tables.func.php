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
 * remark	数据表清单
 */
defined('IN_IA') or exit('Access Denied');
fm_load()->fm_class('data');

//数据表归类
function fmFunc_tables_types(){
	$types=array(
		'all'=>'全局',
		'goods'=>'产品相关',
		'vfoods'=>'餐饮相关',
		'realty'=>'房产相关',
		'article'=>'文章相关',
		'partner'=>'商户相关',
		'comment'=>'评论相关',
		'site'=>'微站相关',
		'order'=>'订单相关',
		'needs'=>'有求必应相关',
		'user'=>'用户相关',
		'system'=>'系统相关'
	);
	//全局(凑数的，用以凑成便于SQL查询in()的命令)
	return $types;
}
function fmFunc_tables_type(){
	$types=fmFunc_tables_types();
	$type=array();
	foreach($types as $key=>$t){
		$type[]=$key;
	}
	return $type;
}
function fmFunc_tables_group(){
	$groups = array('shopping','duokefu','imms','site','vfoods','api');
	return $groups;
}
//数据表列表
function fmFunc_tables_list(){
	$table_list = array();
	$groups = fmFunc_tables_group();
	foreach($groups as $group){
		$table_list[$group] = array();
		$files = glob(FM_CORE.'tables/'.$group.'/*.php');
		if($files){
			foreach($files as $file)
			{
				$file = str_replace(FM_CORE.'tables/'.$group.'/','',$file);
				$file = str_replace('.php','',$file);
				$table_list[$group][] = trim($file);
			}
		}
	}
	return $table_list;
}
//数据表汇总
function fmFunc_tables_all($dtype=NULL){
	global $_GPC;
	global $_W;
	global $_FM;
	if(empty($dtype)){
		$dtype = 'shopping';
	}
	$list = array();
	$tables=array();
	$files = glob(FM_CORE.'tables/'.$dtype.'/*.php');
	foreach($files as $file){
		require $file;	//不可使用require_once
		$_file = $file;
		$_file = str_replace(FM_CORE.'tables'.DIRECTORY_SEPARATOR.$dtype.DIRECTORY_SEPARATOR,'',$_file);
		$_file = str_replace('.php','',$_file);
		$table = trim($_file);
		if(isset($tables[$table]['disabled']) && $tables[$table]['disabled']==true){
			unset($tables[$table]);
		}
	}
	return $tables;
}

//指定类别的数据表列表
function fmFunc_tables_bytype($type){
	$types=fmFunc_tables_type();
	$tables=fmFunc_tables_all();
	//传入的类别值不能是数组,且必须跟数据表类型里的类型一致，否则返回空值
	$type=(!is_array($type) && in_array($type,$types,TRUE)) ? $type :'';
	if(empty($type)) {
		return FALSE;
	}
	foreach($tables as $key=>$table){
		if(!in_array($type,$table['type'],TRUE)) {
			unset($tables[$key]);
		}
	}
	return $tables;
}

//根据表名获取字段列表,返回数组
function fmFunc_tables_fields($name,$dtype=NULL){
	$tables=array();
	$types=array('shopping','duokefu','pintuan','site');
	$dtype = (in_array($dtype,$types)) ? $dtype : 'shopping';

	require MODULE_ROOT.'/core/tables/'.$dtype.'/'.$name.'.php';
	$columns=$tables[$name]['columns'];
	$fields=array();
	foreach ($columns as $key=>$sql){
		$fields[]=$key;
	}
	//$fields = implode(',',$fields);
	return $fields;
}

//自动修复数据表
function fmFunc_tables_check($dtype=NULL){
	if(empty($dtype)){
		$dtype = 'shopping';
	}
	$tables = fmFunc_tables_all($dtype);
	$needcreate=0;
	$checkresult="";
	$tablelist=array();
	$return=array();
	foreach($tables as $key=>$table){
		$tablename='fm453_'.$dtype.'_'.$key;
		$tablelist[$key] =array(
			'name'=>$tablename,
			'remark'=>$table['name'],
			'sql'=>$table['sql'],
			'columns'=>$table['columns'],
		);
		if(pdo_tableexists($tablename)) {//表存在
			$checkresult .='<i class="fa fa-check-circle-o" style="color:blue;"></i>'.$tablepre.$tablename."存在"."<br>";
			//检查表字段的完整性
			foreach($table['columns'] as $ckey=>$column){
				if(!pdo_fieldexists($tablename, $ckey)) {
					$checkresult .="<pre>".$tablename."表字段".$ckey."不存在";
					$checkresult .='<pre class="cancopy">'.$column."</pre>";
					$result = pdo_query($column);//字段不存在时，按对应的SQL命令修复字段
					if ($result) {
						$checkresult .='<i class="fa fa-check-circle-o"></i>已经修复';
					}else{
						$tablelist[$key]['needcreate']=1;
					}
					unset($result);
					$checkresult .="</pre>";
				}
			}
			unset($column);
			//检查表索引的完整性
			if($table['indexes']){
				foreach($table['indexes'] as $ckey=>$column){
					if($table['columns'][$ckey]){//字段不存在时就不继续
						if(!pdo_indexexists($tablename, $ckey)) {
							$checkresult .="<pre>".$tablename."表索引".$ckey."不存在";
							$checkresult .='<pre class="cancopy">'."ALTER TABLE ".tablename($tablename)." ADD INDEX".$column.";"."</pre>";
							//表索引不存在时，创建新索引
							$result = pdo_query("ALTER TABLE ".tablename($tablename)." ADD INDEX".$column.";");
							if ($result) {
								$checkresult .='<i class="fa fa-check-circle-o"></i>已经修复';
							}else{
								$tablelist[$key]['needcreate']=1;
							}
							unset($result);
							$checkresult .="</pre>";
						}
					}
				}
			}
			unset($column);
		}else{
			$tablelist[$key]['needcreate']=1;
			$checkresult .="<pre>".$tablepre.$tablename.'不存在，正在创建；—（如果失败，可参考以下SQL按要求创建）—';
			$checkresult .='<pre class="cancopy">'.$table['sql'].'</pre>';
			//表不存在时，创建新表(仅有一个字段，需要再次启动检查以完成字段修复)
			$result = pdo_run($table['sql']);
			//var_dump($result);
			if ($result) {
				$checkresult .='<i class="fa fa-check-circle-o"></i>已经创建，请再执行一次检查程序';
			}else{
				$checkresult .='<i class="fa fa fa-times-circle" style="color:red;"></i>'.$tablepre.$tablename.'创建失败，请手动创建';
			}
			unset($result);
			$checkresult .="</pre>";
		}
		$needcreate +=$tablelist[$key]['needcreate'];
		unset($tablename);
	}
	if($needcreate >0) {
		$return['result']=FALSE;
		fmFunc_tables_check();// 循环执行
	}else{
		$return['result']=TRUE;
	}
	$return['tablelist']=$tablelist;
	$return['data']=$needcreate;
	$return['msg']=$checkresult;
	return $return;
}

/**——————备份与导出表结构、数据——————**/
/*
@withData ,是否同时导出数据 TRUE是FALSE否，默认否
*/

function fmFunc_tables_export($withData)
{
	global $_W;
	$list=fmFunc_tables_list();
	$tables=array();
	foreach($list as $dtype=>$names)
	{
		foreach($names as $name)
		{
			$tablename = $_W['config']['db']['master']['tablepre'].'fm453_'.$dtype.'_'.$name;
			$tables[]=$tablename;
		}
	}
	$filename = FM_NAME .'_data_' . date('Y_m_d_H_i_s') . '.sql';
	$classBackUp = new fmClass_data_backup();
	$result = $classBackUp->main($filename,$withData);
	return $result;
}