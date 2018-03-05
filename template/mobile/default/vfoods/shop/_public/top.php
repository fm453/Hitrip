<ul class="mui-table-view">
	<li class="mui-table-view-cell mui-media" style="height:102px;line-height:102px;vertical-align: middle;background-color: #0095f6;">
		<?php if($category['thumb']){?>
		<img class="mui-media-object mui-pull-left" style="width:80px;height:80px;border-radius: 50%;" src="<?php echo tomedia($category['thumb']);?>">
    	<?php }else{?>
    	<img class="mui-media-object mui-pull-left" style="width:80px;height:80px;border-radius: 50%;" src="<?php echo $_W['attachurl'];?>/headimg_<?php echo $_W['uniacid'];?>.jpg">
    	<?php }?>
		<div class="mui-media-body" style="width:auto;height:80px;line-height:40px;color:#fff;">
			<p class='mui-ellipsis' style="color:#fff;text-indent: 0;">
			<?php echo $category['title'];?><br>
			<?php echo $category['description'];?>
			</p>
		</div>
	</li>
</ul>