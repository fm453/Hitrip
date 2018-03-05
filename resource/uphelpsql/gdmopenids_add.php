<?php
$gdmopenids = $_GPC['gdmopenids'];
//print_r($gdmopenids);
$uniacid = $_W['uniacid'];
$goods = pdo_fetchall("SELECT id,goodadm FROM " . tablename('fm453_shopping_goods') . " WHERE uniacid = :uniacid", array(':uniacid' => $uniacid));
foreach($goods as $good) {
	$id=$good['id'];
	$goodadms=$good['goodadm'];
	if(!empty($goodadms)) {
	$tmpgoodadms=$goodadms.','.$gdmopenids;
	}else {
	$tmpgoodadms=$gdmopenids;
	}
	//字符串转数组
$oldgoodadms=explode(',',$tmpgoodadms);
$newgoodadms = array();
	//数组去空值
	foreach($oldgoodadms as $key=>$adm){
		if(!empty($adm)) {
			$newgoodadms[]=$adm;
		}
	}
	$newgoodadms=array_unique($newgoodadms);
	//数组拼接成文本，方便存储
	$newgoodadms =implode(',',$newgoodadms);
	//print_r($newgoodadms."<br>");
	pdo_update("fm453_shopping_goods", array("goodadm" => $newgoodadms), array("id" => $id));
	unset($i);
	unset($j);
	unset($id);
}