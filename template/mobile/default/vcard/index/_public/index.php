{template $appstyle.'index/home/css'}
<div id="offCanvasWrapper" class="mui-off-canvas-wrap mui-draggable mui-scalable">
	<!--侧滑菜单部分-->
	{template $appstyle.'index/home/index/aside'}
	<!--主界面部分-->
	<div  class="mui-inner-wrap" >
		<!-- 左右滑动页面主体部分 -->
		{template $appstyle.'index/home/titlebar'}
		<div id="offCanvasContentScroll" class="mui-content" style="background:#efeff4 url({$fm453resource}images/default/background-logo.png)  no-repeat fixed center center;">
		<!--下拉刷新容器-->
			<div id="pullrefresh" class="mui-content mui-scroll-wrapper" style="margin-top: 44px;" >
				<div class="mui-scroll" >
					<div class="mui-content" id="pullrefresh-table"><!-- 该ID必须存在且不允许更改 -->
					</div>
				</div>
				</div>
				<!-- 下拉容器结束 -->
				<!-- 模态框 -->
					{template $appstyle.'index/home/index/modal/buy'}
				<!-- off-canvas backdrop -->
				<div class="mui-off-canvas-backdrop"></div>
			</div>
		<!---底部菜单栏-->
		{template $appstyle.'index/home/footerbar'}
		</div>
</div>
{template $appstyle.'index/home/footmenus'}
{template $appstyle.'index/home/index/js'}