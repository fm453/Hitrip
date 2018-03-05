<?php
$gdmopenids = $_GPC['gdmopenids'];
//print_r($gdmopenids);
$uniacid = $_W['uniacid'];
$goods = pdo_fetchall("SELECT id,goodadm FROM " . tablename('fm453_shopping_goods') . " WHERE uniacid = :uniacid", array(':uniacid' => $uniacid));
foreach($goods as $good) {
	$id=$good['id'];
	$goodadms=$good['goodadm'];
	if(!empty($goodadms)) {
		if(!empty($gdmopenids)) {
			//字符串转数组
			$delgoodadms=explode(',',$gdmopenids);
			$goodadms=explode(',',$goodadms);
			$tmpgoodadms = array();
			$newgoodadms = array();
			//数组去空值
			foreach($delgoodadms as $adm){
				if(!empty($adm)) {
					$tmpgoodadms[]=$adm;
				}
			}
			unset($adm);
			$tmpgoodadms=array_unique($tmpgoodadms);//得到新的待删除openid序列
			//从产品专员列表中查找匹配待删除的openid
			foreach($goodadms as $gdm){
				foreach($tmpgoodadms as $tmpadm){
					if($tmpadm ===$gdm){
						$gdm='';//与待删openid匹配时，将对应的openid项设为空值，方便下一步移除；
					};
				}
				if(!empty($gdm)){
					$newgoodadms[]=$gdm;
				}
			}
			//数组拼接成文本，方便存储
			$newgoodadms =implode(',',$newgoodadms);
			//print_r($newgoodadms."<br>");
			pdo_update("fm453_shopping_goods", array("goodadm" => $newgoodadms), array("id" => $id));
			unset($i);
			unset($j);
			unset($id);
		}
	}
}