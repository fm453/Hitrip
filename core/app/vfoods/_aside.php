<?php
$appNavs[] = array('name'=>'会员中心','url'=>fm_murl('member','index','index',array('state'=>'vfoods')));
$appNavs[] = array('name'=>'我的点餐','url'=>fm_murl('vfoods','myorder','',array()));
$appNavs[] = array('name'=>'点餐首页','url'=>fm_murl('vfoods','index','index',array()));
$appNavs[] = array('name'=>'全部餐厅','url'=>fm_murl($do,'dianjia','', array('typeid' =>0,'order' =>$_GPC['order'])));

if($shoptype){
	foreach($shoptype as $item){
		$appNavs[] = array('name'=>$item['title'],'url'=>fm_murl($do,'dianjia','', array('order' =>$_GPC['order'],'typeid' =>$item['id'])));
	}
	unset($item);
}

