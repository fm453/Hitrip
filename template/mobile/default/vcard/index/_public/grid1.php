<?php if($appNavs){?>
<style>
	.mui-grid-view >li>a .fa{
		font-size: 35px;
	}
	.mui-grid-view.mui-grid-9 .mui-media {
		color: #fff;
	}
	.mui-table-view.mui-grid-view .mui-table-view-cell .mui-media-body{
		color: #fff;
	}
	.mui-table-view.mui-grid-view .site-nav-1{
		background-color:#ff697a;
		color:#fff;
	}
	.mui-table-view.mui-grid-view .site-nav-2{
		background-color:#3d98ff;
		color:#fff;
	}
	.mui-table-view.mui-grid-view .site-nav-3{
		background-color:#fd3a51;
		color:#fff;
	}
	.mui-table-view.mui-grid-view .site-nav-4{
		background-color:#fc9720;
		color:#fff;
	}
	.mui-table-view.mui-grid-view .site-nav-5{
		background-color:#a369ff;
		color:#fff;
	}
	.mui-table-view.mui-grid-view .site-nav-6{
		background-color:#44c522;
		color:#fff;
	}
	.mui-table-view.mui-grid-view .site-nav-7{
		background-color:#ff697a;
		color:#fff;
	}
	.mui-table-view.mui-grid-view .site-nav-8{
		background-color:#3d98ff;
		color:#fff;
	}
	.mui-table-view.mui-grid-view .site-nav-9{
		background-color:#fd3a51;
		color:#fff;
	}
</style>

<div class="">
	<ul class="mui-table-view mui-grid-view mui-grid-9" style="margin-top:0;border:0;">
		<?php
		$nav_i=0;
		foreach($appNavs as $nav){
			$nav_i +=1;
			if($nav_i>9){break;}
		?>
		<li class="mui-table-view-cell mui-media mui-col-xs-4 mui-col-sm-4 site-nav-<?php echo $nav_i;?>" style="border:0;">
			<a  href="<?php echo $nav['url'];?>">
				<span class="<?php echo $nav['css']['icon']['icon'];?>" style="padding-right: 0;margin-left: 0; ">
				</span>
				<div class="mui-media-body" style="padding-right: 0;margin-left: 0; "><?php echo $nav['name'];?></div>
			</a>
		</li>
		<?php }?>
	</ul>
</div>
<?php }?>