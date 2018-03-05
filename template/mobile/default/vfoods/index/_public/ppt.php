<?php if($advs){?>

<div class="" style="">
	<div id="slider" class="mui-slider">
		<div class="mui-slider-group mui-slider-loop">
			<!-- 额外增加的一个节点(循环轮播：第一个节点是最后一张轮播) -->
			<div class="mui-slider-item mui-slider-item-duplicate">
				<a href="<?php echo $lastadv['link'];?>">
					<img src="<?php echo $lastadv['thumb'];?>">
				</a>
			</div>

			<?php foreach($advs as $adv){?>
			<div class="mui-slider-item">
				<a href="<?php echo $adv['link'];?>">
					<img src="<?php echo $adv['thumb'];?>">
				</a>
			</div>
			<?php }?>

			<!-- 额外增加的一个节点(循环轮播：最后一个节点是第一张轮播) -->
			<div class="mui-slider-item mui-slider-item-duplicate">
				<a href="<?php echo $firstadv['link'];?>">
					<img src="<?php echo $firstadv['thumb'];?>">
				</a>
			</div>

		</div>
		<div class="mui-slider-indicator mui-text-center">
			<?php foreach($advs as $a_k=>$adv){ ?>
			<div class="mui-indicator <?php if($_k==0) { ?>mui-active<?php }?>"></div>
			<?php }?>
		</div>
	</div>
</div>
<?php }else{ ?>
<div class="mui-content">
	<div id="slider" class="mui-slider">
		<div class="mui-slider-group mui-slider-loop">

			<div class="mui-slider-item mui-slider-item-duplicate">
				<a href="#">
					<img src="<?php echo $htmlsrc.'/'.$do.'/_statics/dc.jpg';?>">
				</a>
			</div>

		</div>

	</div>
</div>
<?php }?>
