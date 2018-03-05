<?php if($appNavs){?>
<style>
	.site-index .mui-grid-view {
		padding:0;
	}
	.site-index .mui-grid-view .mui-table-view-cell{
		margin-right: 0;
	}

	.site-index >ul>li{
		border-right:solid 1px #eee;
		border-bottom:solid 1px #eee;
		text-align: left;
		height:60px;
	}
	.site-index .mui-grid-view >li>a {
		text-align: left;
		height:100%;
	}
	.site-index .mui-grid-view >li>a .li-a-left{
		text-align: center;
		vertical-align: middle;
		overflow: hidden;
		width: 40px;
		position: absolute;
		left:2px;
	}
	.site-index .mui-grid-view >li >a .li-a-left .fa{
		font-size: 35px;
		color:#aaa;
	}
	.site-index .mui-grid-view >li >a .li-a-left >img{
		width: 35px;
		height:35px;
	}

	.site-index .mui-grid-view >li>a .li-a-right{
		text-align: left;
		vertical-align: middle;
		overflow: hidden;
		position: absolute;
		left:45px;
	}

	.site-index .mui-grid-view >li>a .li-a-right .nav-title{
		font-size: 13px;
		color:#333;
	}

	.site-index .mui-grid-view >li>a .li-a-right .nav-des{
		font-size: 12px;
		color:#aaa;
		width: 100px;
	}

</style>

<div class="site-index">
	<ul class="mui-table-view mui-grid-view" style="">
		<?php
		$nav_i=0;
		foreach($appNavs as $nav){
			$nav_i +=1;
			if($nav_i>10){break;}
		?>
		<li class="mui-table-view-cell mui-col-xs-6 mui-col-sm-6 site-nav-<?php echo $nav_i;?>" style="">
			<a  href="<?php echo $nav['url'];?>">
				<span class="li-a-left">
					<?php if(empty($nav['icon'])){ ?>
						<span class="<?php echo $nav['css']['icon']['icon'];?>" style="padding-right: 0;margin-left: 0; ">
						</span>
				<?php }else{?>
					<img src="<?php echo tomedia($nav['icon']);?>" alt="" style="">
				<?php }?>
				</span>
				<span class="li-a-right">
					<div class="nav-title mui-ellipsis" style=""><?php echo $nav['name'];?></div>
					<div class="nav-des mui-ellipsis" style=""><?php echo $nav['description'];?></div>
				</span>
			</a>
		</li>
		<?php }?>
	</ul>
</div>
<?php }?>
