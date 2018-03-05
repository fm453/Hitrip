<?php if($shop){?>
	<h5 style="background-color:#efeff4;text-align:center;padding-top: 8px;padding-bottom: 8px;text-indent: 12px;border-top:#fff 3px solid;border-bottom:#fff 3px solid;">餐厅列表</h5>
	<style>
		.mui-table-view.mui-grid-view .mui-table-view-cell .mui-media-body{
			font-size: 15px;
			margin-top:8px;
			color: #333;
		}
	</style>
	<ul class="mui-table-view">
	<?php
	foreach($shop as $k=>$item){
		include fmFunc_template_m($do.'/'.$ac.'/_public/shop/item');
	}
	?>
	</ul>
<?php }?>