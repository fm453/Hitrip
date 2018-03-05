<!-- 容器内固定数据，刷新替换-->
	<!-- PPT SLIDER START -->
	<?php include fmFunc_template_m($do.'/'.$ac.'/_public/ppt');?>
	<!-- PPT SLIDER END -->
<div style="background-image:url(<?php echo $style['index']['bg_img'];?>);background-size: cover;background-repeat: no-repeat;background-color: transparent;padding-top: 5px;padding-bottom: 10px;">
	<!-- SEARCH START -->
	<?php include fmFunc_template_m($do.'/'.$ac.'/_public/search');?>
	<!-- SEARCH END -->

	<!-- 9宫格 START,调用微站第10区块的导航栏目 -->
	<?php //include fmFunc_template_m($do.'/'.$ac.'/_public/grid2');?>
	<!-- 9宫格 END-->

	<!-- 栏目列表 START,调用文章分类 -->
	<?php include fmFunc_template_m($do.'/'.$ac.'/_public/category');?>
	<!-- 栏目列表 END-->

	<!--  广告列表 第一部分，无缝拼接 -->
	<?php include fmFunc_template_m($do.'/'.$ac.'/_public/ad/ad1');?>
	<!---广告列表第二部分,横向滚动，至少3张图-->
	<?php include fmFunc_template_m($do.'/'.$ac.'/_public/ad/ad2');?>
	<!---广告列表，第三部分,至少4张图-->
	<?php include fmFunc_template_m($do.'/'.$ac.'/_public/ad/ad3');?>
	<!---公告栏目-->
	<?php include fmFunc_template_m($do.'/'.$ac.'/_public/notice');?>
	<!---文章推荐栏目-->
	<?php include fmFunc_template_m($do.'/'.$ac.'/_public/article');?>

</div>