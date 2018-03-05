<li class="mui-table-view-cell mui-media">
    <a href="<?php echo fm_murl($do,'list','', array('pcate'=>$item['id']));?>">
        <img class="mui-media-object mui-pull-left" src="<?php if($item['thumb']){ echo tomedia($item['thumb']);}else{ echo $_W['attachurl']."/headimg_".$_W['uniacid'].".jpg"; }?>">
        <div class="mui-media-body">
        	<?php echo $item['title'];?>
            <?php if($item['enabled'] == "0"){ ?>
                &nbsp;&nbsp;<label class="label label-default">休息中</label>
             <?php }else{?>
             	&nbsp;&nbsp;<label class="label label-success">营业中</label>
			<?php }?>
            <p class='mui-ellipsis' style="text-indent: 1px;"><i class="mui-icon mui-icon-location"></i>&nbsp;<?php echo $item['dist'];?></p>
        </div>
        <div class="mui-media-body">
        	<p class='mui-ellipsis' style="text-indent: 1px;margin-top:10px;text-align:center;">
        		<label class="mui-btn mui-btn-success"><span style="">热度:</span><?php echo $item['total'];?></label>
        		<label class="mui-btn mui-btn-success"><?php echo $item['sendprice'];?>元起订</label>
        		<?php if($item['isinner'] == "0"){ ?>
                &nbsp;&nbsp;<label class="mui-btn mui-btn-warning">仅堂食</label>
             <?php }else{?>
             	&nbsp;&nbsp;<label class="mui-btn mui-btn-danger">可外带</label>
			<?php }?>
        	</p>
        	<p class='mui-ellipsis' style="text-indent: 1px;margin-top:10px;">
        		<?php echo $item['address'];?>
        	</p>
        </div>
    </a>
</li>