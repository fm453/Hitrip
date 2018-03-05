<div class="myoder img-rounded">
	<div class="myoder-hd">
		<span class="pull-left">订单编号：<?php echo $item['ordersn'];?></span>
		<span class="pull-right">
		<?php if($item['paytype'] == 2){?>
			<?php if($item['status'] == -1){?>
			<span class="text-muted">已取消</span>
			<?php }elseif($item['status'] > 0){?>
			<span class="text-danger">未完成</span>
			<?php }else{?>
			<span class="text-success">已失效</span>
			<?php }?>
		<?php }else{?>
			<?php if($item['status'] ==-1){?>
			<span class="text-muted">已取消</span>
			<?php }elseif($item['status'] ==0){?>
			<span class="text-danger">已完成</span>
			<?php }elseif($item['status'] ==1){?>
			<span class="text-warning">待支付</span>
			<?php }elseif($item['status'] ==2){?>
			<span class="text-warning">已下单</span>
			<?php }else{?>
			<span class="text-success">已确认</span>
			<?php }?>
		<?php }?>
		</span>
	</div>

		<?php include fmFunc_template_m($do.'/'.$ac.'/_public/goodsinfo');?>

	<div class="myoder-total">
		<span>
			共计：<span class="false">
				<?php echo $item['price'];?> 元</span>
		</span>
	</div>

		<div style="height:50px;">
		<?php if($item['paytype'] == 1 && $item['status'] == 1){?>
			<a href="<?php echo fm_murl($do,'pay', '', array('orderid' => $item['id']));?>" class="mui-btn mui-btn-danger mui-pull-right mui-btn-sm" style="margin-left:5px;">支付</a>
		<?php }?>
			<a href="<?php echo fm_murl($do,$ac,'detail',array('id'=>$item['id']));?>" class="mui-btn mui-btn-success mui-pull-left mui-btn-sm" style="margin-right: 5px;">详情</a>
			<a href="tel:<?php echo $item['pcate3'][0]['shouji'];?>" mce_href="tel:<?php echo $item['pcate3'][0]['shouji'];?>" class="mui-btn mui-btn-success mui-pull-left mui-btn-sm">电话</a>
		</div>

</div>