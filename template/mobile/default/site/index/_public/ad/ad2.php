<?php
if($adboxes[1]){
?>
<div class="mui-content">
	<h5 style="background-color:#efeff4;padding-top: 8px;padding-bottom: 8px;text-indent: 12px;border-top:#fff 3px solid;border-bottom:#fff 3px solid;">
		<?php echo $adboxes[1]['description'];?>
	</h5>
	<div class="mui-slider">
		<div class="mui-slider-group mui-slider-loop">
		<!-- 额外增加的一个节点(循环轮播：第一个节点是最后一个图文表格) -->
		<div class="mui-slider-item mui-slider-item-duplicate">
			<ul class="mui-table-view mui-grid-view">
				<?php foreach($adboxes[1]['last'] as $ad){?>
				<li class="mui-table-view-cell mui-media mui-col-xs-6">
					<a href="<?php echo $ad['link'];?>" onload="UpdateView('ad',<?php echo $ad['id'];?>);" onclick="UpdateClick('ad',<?php echo $ad['id'];?>);">
						<img class="mui-media-object" src="<?php echo $ad['thumb'];?>" style="" onerror="RepairMyImg(this)" >
						<div class="mui-media-body"><?php echo $ad['adname'];?></div>
					</a>
				</li>
				<?php }?>
			</ul>
		</div>

		<?php foreach($adboxes[1]['fornode'] as $adbox){?>
		<div class="mui-slider-item">
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
		</div>
		<?php }?>

		<!-- 额外增加的一个节点(循环轮播：最后一个节点是第一个图文表格) -->
		<div class="mui-slider-item mui-slider-item-duplicate">
			<ul class="mui-table-view mui-grid-view">
				<?php foreach($adboxes[1]['first'] as $ad){?>
				<li class="mui-table-view-cell mui-media mui-col-xs-6">
					<a href="<?php echo $ad['link'];?>" onload="UpdateView('ad',<?php echo $ad['id'];?>);" onclick="UpdateClick('ad',<?php echo $ad['id'];?>);">
						<img class="mui-media-object" src="<?php echo $ad['thumb'];?>" style="" onerror="RepairMyImg(this)" >
						<div class="mui-media-body"><?php echo $ad['adname'];?></div>
					</a>
				</li>
				<?php }?>
			</ul>
		</div>

		</div>

		<div class="mui-slider-indicator" style="position: static;background-color: #fff;">
			<span class="mui-action mui-action-previous mui-icon mui-icon-back" style="display: none;"></span>
			<div class="mui-number">
				<span>1</span> / <?php echo $node_count_1;?>
			</div>
			<span class="mui-action mui-action-next mui-icon mui-icon-forward" style="display: none;"></span>
		</div>

	</div>
</div>
<?php }?>