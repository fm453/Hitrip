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
 * @remark	数据库处理类
 */
defined('IN_IA') or exit('Access Denied');

class fmClass_data_backup{
	/*生成数据库表sql文件*/
	protected function table2sql($table,$withData)
	{
		$tabledump = 'DROP TABLE IF EXISTS ' . $table . ';' . "\r\n";
		$createtable = pdo_fetch('SHOW CREATE TABLE ' . $table);
		$tabledump .= $createtable['Create Table'] . ';' . "\r\n";
		if($withData)
		{
			$rows = pdo_fetchall('SELECT * FROM ' . $table);
			foreach ($rows as $row) {
				$comma = '';
				$tabledump .= 'INSERT INTO ' . $table . ' VALUES(';

				foreach ($row as $k => $v ) {
					$tabledump .= $comma . '\'' . addslashes($v) . '\'';
					$comma = ',';
				}

				$tabledump .= ');' . "\r\n";
			}
		}

		return $tabledump;
	}

	/*导出到指定的文件
	@withData 是否导出关联数据
	*/
	public function main($filename,$withData)
	{
		global $_W;
		global $_GPC;

		$sqls = '';
		$alltables = array();
		$sql = 'SHOW TABLES LIKE \'%fm453_duokefu%\'';
		$alltables['duokefu'] = pdo_fetchall($sql);
		$sql = 'SHOW TABLES LIKE \'%fm453_shopping%\'';
		$alltables['shopping'] = pdo_fetchall($sql);
		$sql = 'SHOW TABLES LIKE \'%fm453_site%\'';
		$alltables['site'] = pdo_fetchall($sql);
		foreach ($alltables as $tables )
		{
			foreach ($tables as $k => $t )
			{
				$table = array_values($t);
				$tablename = $table[0];
				$sqls .= $this->table2sql($tablename,$withData) . "\r\n\r\n";
			}
		}

		header('Pragma: public');
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
		header('Cache-Control: no-store, no-cache, must-revalidate');
		header('Cache-Control: pre-check=0, post-check=0, max-age=0');
		header('Content-Encoding: UTF8');
		header('Content-type: application/force-download');
		header('Content-Disposition: attachment; filename="' . $filename . '"');
		exit($sqls);
	}
}
