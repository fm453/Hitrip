<li class="mui-table-view-cell mui-media">
    <a href="<?php if($item['isnodetail']){echo fm_murl($do,'list','', array('id'=>$item['id']));}else{ echo fm_murl($do,'shop','', array('id'=>$item['id']));}?>">
        <img class="mui-media-object mui-pull-left" src="<?php if($item['thumb']){ echo tomedia($item['thumb']);}else{ echo $_W['attachurl']."/headimg_".$_W['uniacid'].".jpg"; }?>">
        <div class="mui-media-body">
        	<?php echo $item['title'];?>
            <?php if($item['enabled'] == 0 || $item['isopen']==0){ ?>
                &nbsp;&nbsp;<label class="label label-default">休息中</label>
             <?php }else{?>
             	&nbsp;&nbsp;<label class="label label-success">营业中</label>
			<?php }?>
			<?php if($item['iswaimai'] == 1){ ?>
        		<label class="label label-info"><?php echo $item['sendprice'];?>元起送</label>
        		<?php }?>
            <p class='mui-ellipsis' style="text-indent: 1px;"><i class="mui-icon mui-icon-location"></i>&nbsp;<?php echo $item['dist'];?></p>
        </div>
        <div class="mui-media-body">
         	<p class='mui-ellipsis' style="text-indent: 1px;margin-top:10px;text-align:left;">
        		<label class="label label-success"><span style="">热度:</span><?php echo $item['total'];?></label>

        		<?php if($item['iswaimai'] == 1){ ?>
                &nbsp;&nbsp;<label class="label label-warning">可外卖</label>
            <?php }?>
             <?php if($item['isziqu'] == 1){ ?>
                &nbsp;&nbsp;<label class="label label-warning">可自取</label>
             <?php }?>
             <?php if($item['istangshi'] == 1){?>
             	&nbsp;&nbsp;<label class="label label-danger">可堂食</label>
			<?php }?>
        	</p>
        	<p class='mui-ellipsis' style="text-indent: 1px;margin-top:10px;">
        		<?php echo $item['address'];?>
        	</p>
        </div>
    </a>
</li>