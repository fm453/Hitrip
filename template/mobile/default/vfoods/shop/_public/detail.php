<h5 style="background-color:#efeff4;text-align:center;padding-top: 8px;padding-bottom: 8px;text-indent: 12px;border-top:#fff 3px solid;border-bottom:#fff 3px solid;">餐厅信息</h5>
<style>
	.mui-table-view.mui-grid-view .mui-table-view-cell .mui-media-body{
		font-size: 15px;
		margin-top:8px;
		color: #333;
	}
</style>
<ul class="mui-table-view">
    <li class="mui-table-view-cell">
        外卖起送价：<?php echo $category['sendprice'];?>元
    </li>
    <li class="mui-table-view-cell">
        餐厅人气：<?php echo $category['total'];?>
    </li>
    <li class="mui-table-view-cell">
        <a class="mui-navigate-right" href="tel:<?php echo $category['shouji'];?>">餐厅电话：<?php echo $category['shouji'];?></a>
    </li>
    <li class="mui-table-view-cell">
        上午营业时间：<?php echo $ptime1;?>至<?php echo $ptime2;?>
    </li>
    <li class="mui-table-view-cell">
        下午营业时间：<?php echo $ptime3;?>至<?php echo $ptime4;?>
    </li>
</ul>