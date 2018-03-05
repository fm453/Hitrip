<?php
$_GPC['fromplats'] = empty($_GPC['fromplats']) ? 'all' : $_GPC['fromplats'];
	if (isset($_GPC['fromplats'])) {
		if($_GPC['fromplats']=='all') {
			if($platids=='-1'){
				$condition.= "uniacid > :uniacid";
				$params[':uniacid'] = $platids;
			}else {
				if($supplydianids){
					$condition.= "uniacid in ({$supplydianids})";
				}else{
					$condition.= "uniacid = :uniacid";//总店没有供应商店铺
					$params[':uniacid'] = $uniacid;
				}
			}
		}else{
			$condition .= ' `uniacid` = :uniacid';
			$params[':uniacid'] = intval($_GPC['fromplats']);
		}
	}else{
		if($platids=='-1'){
				$condition.= "uniacid > :uniacid";
				$params[':uniacid'] = $platids;
		}else {
			if($supplydianids){
				$condition.= "uniacid in ({$supplydianids})";
			}else{
				$condition.= "uniacid = :uniacid";//总店没有供应商店铺
				$params[':uniacid'] = $uniacid;
			}
		}
	}