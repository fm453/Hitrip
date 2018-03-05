
<div class="order-detail">
			<table class="table">
				<thead style='background-color:rgba(204,204,204,1);color:#fff;'>
				<tr>
					<th class="name">菜品</th>
					<th class="num">数量</th>
					<th class="total">单价</th>
				</tr>
				</thead>
				<tbody  style='background-color:rgba(255,255,255,1);line-height:28px;'>
				<?php foreach($item['foods'] as $foods){?>
				<tr>
					<td class="name">
						<span  style="float:left;">
							<?php echo $foods['title'];?>

						</span>
					</td>
					<td class="num">
						<?php echo $item['total'][$foods['id']]['total'];?><?php echo $foods['unit'];?>
					</td>
					<td class="total">
						<span class='goodsprice'><?php if($foods['preprice']){echo $foods['preprice'];}else{echo $foods['oriprice'];}?>元 / <?php echo $foods['unit'];?></span>
					</td>
				</tr>
				<?php } ?>
				</tbody>
			</table>
</div>