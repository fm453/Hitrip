<!-- 容器内固定数据，刷新替换-->
	<?php if(count($list)<=0){ ?>
<div class="hero-unit well " style="margin-top:30%;">
	<h1>空空如也</h1>
	<p>
		亲，您还没有已经等待支付的订单，换其他状态的试试看看呢！
	</p>

</div>
<?php }else{?>
<div class="alert alert-info" style="margin-bottom:1px;">
	<button type="button" class="close" data-dismiss="alert" style="width: 20px;height: 20px;border: 1px solid;border-radius: 10px;">×</button>
		<h4>
			温馨提示!
		</h4>
		<strong>亲!</strong>为了方便您操作，您可以左右滑动一下各个订单试试，有小功能哦！
</div>
<?php }?>

<?php foreach($list as $item){?>
<div class="mui-content-padded">
	<form action="" method="post" id="form<?php echo $item['id'];?>" onsubmit="">
		<input type="hidden" name="id" id="id<?php echo $item['id'];?>" value="<?php echo $item['id'];?>" />
		<input type="hidden" name="token" value="<?php echo $_W['token'];?>" />
		<div style='margin-bottom:20px;'>
			<ul data-id="<?php echo $item['id'];?>" class="mui-table-view huadongcaozong">
				<li class="mui-table-view-cell" >
					<div class="mui-slider-left mui-disabled">
						<a href="<?php echo fm_murl($do,$ac,'detail',array('id'=>$item['id']));?>" class="mui-btn mui-btn-success" date-swipe2do="link"><span  style="font-size: 14px;">详情</span></a>
					</div>
					<div class="mui-slider-right mui-disabled">
						<a class="mui-btn mui-btn-red mui-icon mui-icon-close" date-swipe2do="delete" data-id="<?php echo $item['id'];?>"><span  style="font-size: 12px;">删除</span></a>
					</div>
					<div class="mui-slider-handle">
						<?php include fmFunc_template_m($do.'/'.$ac.'/_public/orderitem');?>
						<h5 style="text-align: center;background-color: rgba(230,230,230,.3);margin-left:-15px;margin-right:-15px;">
						<?php echo date('Y年m月d日 H:i', $item['createtime']);?><br>
						</h5>
					</div>
				</li>
			</ul>
		</div>
	</form>
</div>
<?php }?>