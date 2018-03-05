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
 * @remark 自定义加载器 function > model > class
*/

defined('IN_IA') or exit('Access Denied');

function fm_load() {
	static $loader;
	if(empty($loader)) {
		$fm_loader = new fm_loader();
	}
	return $fm_loader;
}

class fm_loader {
	private $cache = array();

	function fm_func($name) {
		if (isset($this->cache['fmfunc']['"'.$name.'"'])) {
			return true;
		}
		$file = FM_CORE . 'function'.DIRECTORY_SEPARATOR. $name . '.func.php';
		if (file_exists($file)) {
			include_once $file;
			$this->cache['fmfunc']['"'.$name.'"'] = true;
			return true;
		} else {
			$name=strtolower($name);
			$file = FM_CORE . 'function'.DIRECTORY_SEPARATOR. $name . '.func.php';
			if (file_exists($file)) {
				include_once $file;
				$this->cache['fmfunc']['"'.$name.'"'] = true;
				return true;
			} else {
				if(FM_DEBUG){
					trigger_error('Function File "'.FM_CORE.'function'.DIRECTORY_SEPARATOR. $name . '.func.php" Not Exist', E_USER_ERROR);
				}
				return false;
			}
		}
	}

	function fm_model($name) {
		if (isset($this->cache['fmmodel']['"'.$name.'"'])) {
			return true;
		}
		$file = FM_CORE . 'model'.DIRECTORY_SEPARATOR. $name . '.mod.php';
		if (file_exists($file)) {
			include_once $file;
			$this->cache['fmmodel']['"'.$name.'"'] = true;
			return true;
		} else {
			$name=strtolower($name);
			$file = FM_CORE . 'model'.DIRECTORY_SEPARATOR. $name . '.mod.php';
			if (file_exists($file)) {
				include_once $file;
				$this->cache['fmmodel']['"'.$name.'"'] = true;
				return true;
			} else {
				if(FM_DEBUG){
					trigger_error('Model File "'.FM_CORE.'model'.DIRECTORY_SEPARATOR. $name . '.mod.php" Not Exist', E_USER_ERROR);
				}
				return false;
			}
		}
	}

	function fm_class($name) {
		if (isset($this->cache['fmclass']['"'.$name.'"'])) {
			return true;
		}
		$file = FM_CORE . 'class'.DIRECTORY_SEPARATOR. $name . '.class.php';
		if (file_exists($file)) {
			include_once $file;
			$this->cache['fmclass']['"'.$name.'"'] = true;
			return true;
		} else {
			$name=strtolower($name);
			$file = FM_CORE . 'class'.DIRECTORY_SEPARATOR. $name . '.class.php';
			if (file_exists($file)) {
				include_once $file;
				$this->cache['fmclass']['"'.$name.'"'] = true;
				return true;
			} else {
				if(FM_DEBUG){
					trigger_error('Class File "'.FM_CORE.'class'.DIRECTORY_SEPARATOR. $name . '.class.php" Not Exist', E_USER_ERROR);
				}
				return false;
			}
		}
	}

	function fm_vars($name) {
		$file = FM_VAR. $name . '.php';
		if (file_exists($file)) {
			return $file;
		} else {
			return false;
		}
	}

	function fm_plugin($model_name,$action) {
		$file=  FM_PLUGIN.$model_name.DIRECTORY_SEPARATOR.$action.'.php';
		if (file_exists($file)) {
			return $file;
		} else {
			return false;
		}
	}

	function fm_app($model_name,$action) {
		$file=  FM_APP.$model_name.DIRECTORY_SEPARATOR.$action.'.php';
		if (file_exists($file)) {
			return $file;
		} else {
			return false;
		}
	}

	function fm_web($model_name,$action) {
		$file=  FM_WEB.$model_name.DIRECTORY_SEPARATOR.$action.'.php';
		if (file_exists($file)) {
			return $file;
		} else {
			return false;
		}
	}

	function fm_wxapp($model_name,$action) {
		$file=  FM_WXAPP.$model_name.DIRECTORY_SEPARATOR.$action.'.php';
		if (file_exists($file)) {
			return $file;
		} else {
			return false;
		}
	}

	function fm_appweb($model_name,$action) {
		$file=  FM_CORE.'appweb'.DIRECTORY_SEPARATOR.$model_name.DIRECTORY_SEPARATOR.$action.'.php';
		if (file_exists($file)) {
			return $file;
		} else {
			return false;
		}
	}

}
