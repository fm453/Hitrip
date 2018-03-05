<?php if($rec_articles){?>
<h5 style="background-color:#efeff4;padding-top: 8px;padding-bottom: 8px;text-indent: 12px;border-top:#fff 3px solid;border-bottom:#fff 3px solid;">推荐阅读</h5>
<?php
foreach($alldetail as $id=>$row){
	include fmFunc_template_m($do.'/'.$ac.'/_public/article/item_rec');
}
?>
<?php }?>