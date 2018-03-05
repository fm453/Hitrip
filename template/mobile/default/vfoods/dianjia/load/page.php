<!-- 容器内固定数据，刷新替换-->
	<!-- PPT SLIDER START -->
	<?php include fmFunc_template_m($do.'/'.$ac.'/_public/ppt');?>
	<!-- PPT SLIDER END -->
<div style="background-image:url(<?php echo $style['index']['bg_img'];?>);background-size: cover;background-repeat: no-repeat;background-color: transparent;padding-top: 5px;padding-bottom: 10px;">

	<!-- 店铺列表 START,调用店铺分类 -->
	<?php include fmFunc_template_m($do.'/'.$ac.'/_public/shop');?>
	<!-- 店铺列表 END-->

	<!--  广告列表 第一部分，无缝拼接 -->
	<?php //include fmFunc_template_m($do.'/'.$ac.'/_public/ad/ad1');?>
	<!---广告列表第二部分,横向滚动，至少3张图-->
	<?php //include fmFunc_template_m($do.'/'.$ac.'/_public/ad/ad2');?>
	<!---广告列表，第三部分,至少4张图-->
	<?php //include fmFunc_template_m($do.'/'.$ac.'/_public/ad/ad3');?>

</div>