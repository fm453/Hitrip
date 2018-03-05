	<div class="mui-content">
			<div class="mui-card">
				<div class="mui-card-header mui-card-media">
					<div class="mui-media-body">
						<?php
						echo $row['title'];
						if($row['author']){
							echo 'By '.$row['author'];
						}
						?>
					</div>
				</div>
				<div class="mui-card-content-inner">
					<p style="color: #333;"><?php echo $row['description'];?></p>
					<p> <?php if($row['createtime']){?> —— <?php echo date('Y年 m月 d日 H:i',$row['createtime']);?> <?php }?></p>
				</div>
				<div class="mui-card-footer">
					<a class="mui-card-link"><i class="mui-icon-extra mui-icon-extra-heart"></i>  <span class="mui-badge mui-badge-warning mui-badge-inverted"><?php echo $row['viewcount'];?></span></a>
					<a class="mui-card-link"><i class="mui-icon mui-icon-eye"></i>  <span class="mui-badge mui-badge-warning mui-badge-inverted"><?php echo $row['click'];?></span></a>
					<a class="mui-card-link" href="<?php echo fm_murl('article','detail','index',array('id'=>$row['id'],'sn'=>''));?>"><i class="mui-icon mui-icon-more"></i>详情</a>
				</div>
			</div>
		</div>
