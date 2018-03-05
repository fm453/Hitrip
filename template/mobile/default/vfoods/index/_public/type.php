<?php $key='tangshi';$buytype=$buytypes[$key];?>
<div class="mui-card">
	<!--页眉，放置标题-->
	<div class="mui-hidden mui-card-header">页眉</div>
	<!--内容区-->
	<div class="mui-card-content">
		<ul class="mui-table-view">
		<li class="mui-table-view-cell mui-media" style="height:102px;line-height:102px;vertical-align: middle;background-color: #fff;">
		<a href="<?php echo $buytype['url'];?>">
    		<img class="mui-media-object mui-pull-left" style="width:80px;height:80px;border-radius: 50%;" src="<?php echo tomedia($buytype['icon']);?>" data-src="<?php echo $buytype['icon'];?>">

		<div class="mui-media-body" style="width:auto;height:80px;line-height:40px;color:#fff;">
			<p class='mui-ellipsis' style="text-indent: 0;">
			<button class="mui-btn mui-btn-default mui-btn-outlined"><?php echo $buytype['title'];?></button><br>
			<?php echo $buytype['des'];?>
			</p>
		</div>
		</a>
		</li>
		</ul>
	</div>
	<!--页脚，放置补充信息或支持的操作-->
	<div class="mui-hidden mui-card-footer">页脚</div>
</div>

<?php $key='waimai';$buytype=$buytypes[$key];?>
<div class="mui-card">
	<!--页眉，放置标题-->
	<div class="mui-hidden mui-card-header">页眉</div>
	<!--内容区-->
	<div class="mui-card-content">
		<ul class="mui-table-view">
		<li class="mui-table-view-cell mui-media" style="height:102px;line-height:102px;vertical-align: middle;background-color: #fff;">
		<a href="<?php echo $buytype['url'];?>">
    		<img class="mui-media-object mui-pull-left" style="width:80px;height:80px;border-radius: 50%;" src="<?php echo tomedia($buytype['icon']);?>" data-src="<?php echo $buytype['icon'];?>">

		<div class="mui-media-body" style="width:auto;height:80px;line-height:40px;color:#fff;">
			<p class='mui-ellipsis' style="text-indent: 0;">
			<button class="mui-btn mui-btn-default mui-btn-outlined"><?php echo $buytype['title'];?></button><br>
			<?php echo $buytype['des'];?>
			</p>
		</div>
		</a>
		</li>
		</ul>
	</div>
	<!--页脚，放置补充信息或支持的操作-->
	<div class="mui-hidden mui-card-footer">页脚</div>
</div>

<?php $key='take';$buytype=$buytypes[$key];?>
<div class="mui-card">
	<!--页眉，放置标题-->
	<div class="mui-hidden mui-card-header">页眉</div>
	<!--内容区-->
	<div class="mui-card-content">
		<ul class="mui-table-view">
		<li class="mui-table-view-cell mui-media" style="height:102px;line-height:102px;vertical-align: middle;background-color: #fff;">
		<a href="<?php echo $buytype['url'];?>">
    		<img class="mui-media-object mui-pull-left" style="width:80px;height:80px;border-radius: 50%;" src="<?php echo tomedia($buytype['icon']);?>" data-src="<?php echo $buytype['icon'];?>">

		<div class="mui-media-body" style="width:auto;height:80px;line-height:40px;color:#fff;">
			<p class='mui-ellipsis' style="text-indent: 0;">
			<button class="mui-btn mui-btn-default mui-btn-outlined"><?php echo $buytype['title'];?></button><br>
			<?php echo $buytype['des'];?>
			</p>
		</div>
		</a>
		</li>
		</ul>
	</div>
	<!--页脚，放置补充信息或支持的操作-->
	<div class="mui-hidden mui-card-footer">页脚</div>
</div>