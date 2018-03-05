<?php
if($adboxes[2]){
?>
<div class="mui-content" style="background-color:#fff">
	<h5 style="background-color:#efeff4;padding-top: 8px;padding-bottom: 8px;text-indent: 12px;border-top:#fff 3px solid;border-bottom:#fff 3px solid;">
	<?php echo $adboxes[2]['description'];?>
	</h5>
	<?php foreach($adboxes[2]['fornode'] as $adbox){?>
	<ul class="mui-table-view mui-grid-view">
		<?php foreach($adbox as $ad){?>
		<li class="mui-table-view-cell mui-media mui-col-xs-6">
			<a href="<?php echo $ad['link'];?>" onload="UpdateView('ad',<?php echo $ad['id'];?>);" onclick="UpdateClick('ad',<?php echo $ad['id'];?>);">
				<img class="mui-media-object" src="<?php echo $ad['thumb'];?>" style="" onerror="RepairMyImg(this)" >
				<div class="mui-media-body"><?php echo $ad['adname'];?></div>
			</a>
		</li>
		<?php }?>
	</ul>
	<?php }?>
</div>
<?php }?>