<?php

if($plattype=='os') {
	$platids="-1";
	$uniaccounts = pdo_fetchall("SELECT * FROM " . tablename('uni_account'));
	$accounts = array();
	if(!empty($uniaccounts)) {
		$supplydianids="";
		foreach($uniaccounts as $key => $uniaccount) {
			$del_account=pdo_fetch('SELECT `acid`,`isdeleted` FROM '.tablename('account').' WHERE `uniacid`= :uniacid',array(':uniacid'=>$uniaccount['uniacid']));
			if($del_account['isdeleted']==1) {
				unset($uniaccounts[$key]);
			}else{
				$supplydianids .=$uniaccount['uniacid'].",";
				$accountlist = uni_accounts($uniaccount['uniacid']);
				if(!empty($accountlist)) {
					foreach($accountlist as $account) {
						$accounts[$account['uniacid']] = $account['name'];
					}
				}
			}
		}
	}
}else{
	$uniaccounts = pdo_fetchall("SELECT * FROM " . tablename('uni_account'));
	$accounts = array();
	if(!empty($uniaccounts)) {
		foreach($uniaccounts as $uniaccount) {
			$accountlist = uni_accounts($uniaccount['uniacid']);
			if(!empty($accountlist)) {
				foreach($accountlist as $account) {
					$accounts[$account['uniacid']] = $account['name'];
				}
			}
		}
	}
}
$accounts['all']="全部";