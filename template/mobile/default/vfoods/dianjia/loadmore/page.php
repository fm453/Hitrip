<div class="mui-content">
	<div class="mui-content-padded" style="margin: 5px;text-align: center;">
		<a href="#offCanvasSide"  class="mui-btn mui-btn-primary mui-btn-outlined" style="display: block;width: 220px;margin: 10px auto;">
			当前第<?php echo $rpindex;?>页,共有<?php echo $maxpages;?>页
		</a>
		<div id="info">
		</div>
	</div>
</div>
<?php
foreach($allarticle as $id=>$row){
	include fmFunc_template_m($do.'/'.$ac.'/_public/article/item_rec');
}
?>
