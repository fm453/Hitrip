<a class="mui-tab-item" href="#offCanvasSide">
	<span class="mui-tab-label mui-icon mui-icon-home">
	</span>
	<span class="mui-tab-label">快捷按钮</span>
</a>

<a class="mui-tab-item" href="<?php echo fm_murl($do,$ac,$op,array('ordertype'=>'waimai'))?>">
	<span class="mui-icon mui-icon-plus">
		<span class="mui-badge" id="num-total-waimai"><?php echo intval($alltotal['waimai']);?></span>
	</span>
	<span class="mui-tab-label">外卖</span>
</a>

<a class="mui-tab-item" href="<?php echo fm_murl($do,$ac,$op,array('ordertype'=>'tangshi'))?>">
	<span class="mui-icon mui-icon-plus">
		<span class="mui-badge" id="num-total-tangshi"><?php echo intval($alltotal['tangshi']);?></span>
	</span>
	<span class="mui-tab-label">堂食</span>
</a>

<a class="mui-tab-item" href="<?php echo fm_murl($do,$ac,$op,array('ordertype'=>'ziqu'))?>">
	<span class="mui-icon mui-icon-plus">
		<span class="mui-badge" id="num-total-ziqu"><?php echo intval($alltotal['ziqu']);?></span>
	</span>
	<span class="mui-tab-label">自取</span>
</a>

<a class="mui-tab-item" href="<?php echo fm_murl($do,$ac,$op,array())?>">
	<span class="mui-icon mui-icon-list">
		<span class="mui-badge" id="num-total-all"><?php echo intval($alltotal['all']);?></span>
	</span>
	<span class="mui-tab-label">全部订单</span>
</a>