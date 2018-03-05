	<!-- 容器内固定数据，刷新替换-->
	<!-- PPT SLIDER START -->
	<?php include fmFunc_template_m($do.'/'.$ac.'/_public/ppt');?>
	<!-- PPT SLIDER END -->



	<!-- 9宫格 START,调用微站第10区块的导航栏目 -->
	<?php include fmFunc_template_m($do.'/'.$ac.'/_public/grid2');?>
	<!-- 9宫格 END-->

	<!--  广告列表 第一部分，无缝拼接 -->
	<?php include fmFunc_template_m($do.'/'.$ac.'/_public/ad1');?>
	<!---广告列表第二部分,横向滚动，至少3张图-->
	<?php include fmFunc_template_m($do.'/'.$ac.'/_public/ad2');?>
	<!---广告列表，第三部分,至少4张图-->
	<?php include fmFunc_template_m($do.'/'.$ac.'/_public/ad3');?>
	<!---公告栏目-->
	<?php include fmFunc_template_m($do.'/'.$ac.'/_public/notice');?>
	<!---文章推荐栏目-->
	<?php include fmFunc_template_m($do.'/'.$ac.'/_public/article');?>
