<?php if($allnotice){?>
<h5 style="background-color:#efeff4;padding-top: 8px;padding-bottom: 8px;text-indent: 12px;border-top:#fff 3px solid;border-bottom:#fff 3px solid;">
	公告
	</h5>
<?php
foreach($allnotice as $id=>$row){
	include fmFunc_template_m($do.'/'.$ac.'/_public/article/item_notice');
}
?>
<?php }?>