<div id="offCanvasSideScroll" class="mui-scroll-wrapper">
	<div class="mui-scroll">
		<div class="title" style="text-align:center;"><?php echo $_W['account']['name'];?></div>
		<div class="content">
			温馨提示：您可通过左右滑动页面来打开或关闭侧边栏;双击网页标题栏或下拉，可刷新当前前面；在页面底部上拉可加载更多内容。
			<span class="android-only">；您当前使用的是安卓手机，您还可以通过手机的返回键、home或者menu键快速切换页面。
			</span>
			<p style="margin: 10px 15px;">
				<a href="#offCanvasSide" class="mui-btn mui-btn-danger mui-btn-block" style="padding: 5px 20px;">关闭侧边栏</a>
			</p>

		</div>
		<div class="title" style="margin-bottom: 25px;">快捷方式</div>
		<ul class="mui-table-view mui-table-view-chevron mui-table-view-inverted">
			<?php foreach($appNavs as $item){;?>
			<li class="mui-table-view-cell">
				<a class="mui-navigate-right" href="<?php echo $item['url'];?>">
					<?php echo $item['name'];?>
				</a>
			</li>
			<?php } ?>
		</ul>
	</div>
</div>