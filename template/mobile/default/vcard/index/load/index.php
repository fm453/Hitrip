<link href="<?php echo $htmlsrc.$do.'/'.$ac.'/css.css';?>" rel="stylesheet" media="screen">
<div id="offCanvasWrapper" class="mui-off-canvas-wrap mui-draggable mui-scalable">
	<!--侧滑菜单部分-->
	<aside id="offCanvasSide" class="mui-off-canvas-left">
		<?php include fmFunc_template_m($do.'/aside');?>
	</aside>
	<!--主界面部分-->
	<div  class="mui-inner-wrap" >
		<!-- 左右滑动页面主体部分 -->
		<header class="mui-bar mui-bar-nav" >
			<?php include fmFunc_template_m($do.'/'.$ac.'/titlebar');?>
		</header>
		<div id="offCanvasContentScroll" class="mui-content" style="">
		<!--下拉刷新容器-->
			<div id="pullrefresh" class="mui-content mui-scroll-wrapper">
				<div class="mui-scroll" >
					<div class="mui-content" id="pullrefresh-table" style=""><!-- 该ID必须存在且不允许更改 -->
					<?php
					if(!isset($settings['index']['site_style']) || $settings['index']['site_style']==0){
						include fmFunc_template_m($do.'/'.$ac.'/'.$op.'/page');
					}elseif($settings['index']['site_style']==1){
						include fmFunc_template_m($do.'/'.$ac.'/'.$op.'/page-1');
					}elseif($settings['index']['site_style']==2){
						include fmFunc_template_m($do.'/'.$ac.'/'.$op.'/page-2');
					}
					?>
					</div>
				</div>
				</div>
				<!-- 下拉容器结束 -->
				<!-- off-canvas backdrop -->
				<div class="mui-off-canvas-backdrop"></div>
			</div>
		<!---底部栏-->
		<footer class="">
		</footer>
		<!---NAV导航栏-->
		<nav class="mui-bar mui-bar-tab" style="display: none;">
			<?php include fmFunc_template_m($do.'/'.$ac.'/navbar');?>
		</nav>
	</div>
</div>
<?php include fmFunc_template_m($do.'/'.$ac.'/'.$op.'/js');?>
