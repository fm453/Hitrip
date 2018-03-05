<?php
if($adboxes[0]){
?>
<div class="mui-content" id="listbanner" style="min-height: 10px;">
	<?php foreach($ads[0] as $ad){?>
	<div class="banner-img" style="margin-bottom: -1px;">
		<a href="<?php echo $ad['link'];?>" onload="UpdateView('ad',<?php echo $ad['id'];?>);" onclick="UpdateClick('ad',<?php echo $ad['id'];?>);">
			<img alt="900×225" class="list-img-2"  src="<?php echo $ad['thumb'];?>" width="100%" height="auto" style="" onerror="RepairMyImg(this)" >
		</a>
	</div>
	<?php }?>
</div>
<?php }?>
