<div class="container container-fill">
    <div class="mobile-img" style="margin-top:10px;">
        <?php if(!empty($foods['thumb'])){?>
            <img src="<?php echo $_W['attachurl'].$foods['thumb'];?>" width="100%"/>
        <?php }else{ ?>
            <img src="<?php echo $_W['attachurl'];?>/headimg_<?php echo $_W['uniacid'];?>.jpg" />
        <?php }?>
    </div>
    <div class="mobile-div img-rounded" style="margin:10px 0;text-align:left;">
        <div class="mobile-hd" style="text-align:center;margin-bottom:5px;">
            <?php echo $foods['title'];?>
            <!-- <span class="menu-list-button reduce" onclick="opener.order.reduce(<?php echo $foods['id'];?>);">
                <img src="<?php echo $htmlsrc.DIRECTORY_SEPARATOR.$do.DIRECTORY_SEPARATOR.'_statics'.DIRECTORY_SEPARATOR.'reduce-red.png';?>" height="40px" width="40px" />
            </span>
            <span class="menu-list-num foodsnum_<?php echo $foods['id'];?>">
                <?php if($foodscart['total']){ echo $foodscart['total'];}else{echo 0;}?>
            </span>
            <span class="menu-list-button add" onclick="order.add(<?php echo $foods['id'];?>);">
                <img src="<?php echo $htmlsrc.DIRECTORY_SEPARATOR.$do.DIRECTORY_SEPARATOR.'_statics'.DIRECTORY_SEPARATOR.'add-red.png';?>" height="40px" width="40px" />
            </span> -->
        </div>
        <div class="mobile-hd" style="margin:0 auto;">
            单价：<span style="color: #f00;">¥<?php echo $foods['preprice'];?></span>
            <?php if($foods['preprice'] < $foods['oriprice']){?>
                <span style="text-decoration:line-through;font-weight: lighter;font-style: italic;font-size:14px;">¥<?php echo $foods['oriprice'];?></span>
            <?php }?>
            /<?php echo $foods['unit'];?>
        </div>
        <div class="mobile-hd" style="margin:0 auto;">
            热度：<?php echo $foods['hits'];?>人点过
        </div>
        <div class="mobile-hd" style="margin:0 auto;display: none;">
            余量：充足
        </div>
    </div>
</div>