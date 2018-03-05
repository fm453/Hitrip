<div class="">
	<h5 style="display:none;background-color:#efeff4;padding-top: 8px;padding-bottom: 8px;text-indent: 12px;border-top:#fff 3px solid;border-bottom:#fff 3px solid;">
	栏目分类
	</h5>
	<?php foreach($allcategory['parent'] as $item){ ?>
	<a href="<?php echo fm_murl('article','list','subcate',array('pcate'=>$item['sn']));?>">
		<div class="mui-card" style="margin-bottom: 5px;">
			<div class="mui-card-header mui-card-media" style="color: #333;"><?php echo $item['name'];?></div>
			<div class="mui-card-content" style="height:40vw;max-height:256px;background-image:url(<?php echo tomedia($item['thumb']);?>);background-repeat: no-repeat; background-size: contain;">
				<div class="mui-card-content-inner">
					<p style="color: #333;"><?php echo $item['description'];?></p>
				</div>
			</div>
		</div>
	</a>
	<?php }?>
</div>
