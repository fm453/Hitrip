
<li class="mui-table-view-cell mui-media mui-col-xs-6">
	<a href="<?php echo fm_murl('article','detail','index',array('id'=>$row['id'],'sn'=>''));?>">
		<div style="height:140px;overflow-y: hidden;">
		<img class="mui-media-object" src="<?php echo tomedia($row['thumb']);?>" >
		</div>
		<div class="mui-media-body"><?php echo $row['title'];?></div>
	</a>
</li>
