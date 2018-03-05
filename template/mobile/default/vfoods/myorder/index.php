<style>
  /*点击变蓝色高亮*/
.mui-table-view-cell .mui-active{
  background-color: #b68700;
}
.mui-card{
  margin:10px 0;
}
</style>

<div class="mui-content" style="padding-bottom: 60px;">
  <div class="" style="line-height: 40px;text-align: center;padding-top:10px;margin-top:-10px;">
      <div style="width:220px;height:40px;margin:10px auto;background-image: url(<?php echo $fm453resource.'/vfoods/home-button.png';?>);background-size: cover;">
        <span style="font-size:16px;color:#fff;">我的订单</span>
      </div>
  </div>

  <div class="mui-card">
    <!--页眉，放置标题-->
    <div class="mui-hidden mui-card-header">标题</div>
    <!--内容区-->
    <div class="mui-card-content">
        <ul class="mui-table-view order-detail-list">
          <li class="mui-table-view-cell">
            所属餐厅<span class="mui-pull-right" style="color:#053458;"><?php echo $item['shop']['title'];?></span>
          </li>
          <li class="mui-table-view-cell">
            餐厅电话<span class="mui-pull-right" style="color:#053458;"><?php echo $item['shop']['shouji'];?></span>
          </li>
          <li class="mui-table-view-cell">
            --用餐人<span class="mui-pull-right" style="color:#053458;"><?php echo $item['username'];?></span>
          </li>
          <li class="mui-table-view-cell">
            --电  话<span class="mui-pull-right" style="color:#053458;"><?php echo $item['mobile'];?></span>
          </li>
          <?php if($item['time']){?>
          <li class="mui-table-view-cell">
            送餐时间<span class="mui-pull-right" style="color:#053458;"><?php echo $item['time'];?></span>
          </li>
          <?php }?>
          <?php if($item['address']){?>
          <li class="mui-table-view-cell">
            用餐地址<span class="mui-pull-right" style="color:#053458;"><?php echo $item['address'];?></span>
          </li>
          <?php }?>
          <?php if($item['desknum']){?>
          <li class="mui-table-view-cell">
            就餐桌号<span class="mui-pull-right" style="color:#053458;"><?php echo $item['desknum'];?></span>
          </li>
          <?php }?>
          <li class="mui-table-view-cell">
            支付方式<span class="mui-pull-right" style="color:#053458;"><?php if($item['paytype'] == 1){?>在线支付<?php }else{?>当面付款<?php }?></span>
          </li>
          <li class="mui-table-view-cell">
            订单编号<span class="mui-pull-right" style="color:#053458;"><?php echo $item['ordersn'];?></span>
          </li>
          <li class="mui-table-view-cell">
            创建时间<span class="mui-pull-right" style="color:#ccc;"><?php echo date('m-d H:i', $item['createtime']);?></span>
          </li>
        </ul>
    </div>
    <!--页脚，放置补充信息或支持的操作-->
    <div class="mui-hidden mui-card-footer">页脚</div>
  </div>

  <div class="mui-card">
    <!--页眉，放置标题-->
    <div class="mui-hidden mui-card-header">标题</div>
    <!--内容区-->
    <div class="mui-card-content">
        <ul class="mui-table-view order-detail-list">
          <?php foreach($item['foods'] as $foods){?>
          <li class="mui-table-view-cell" id="foods-list-li-<?php echo $item['id'];?>">
            <div class="pull-left">
              <div class="title"><?php echo $foods['title'];?></div>
            </div>

            <div class="pull-right" style="margin-left:10px;">
              <div class="price">￥<?php if($foods['preprice']){echo $foods['preprice'];}else{echo $foods['oriprice'];}?> </div>
            </div>
            <div class="pull-right" style="">
              <div class="num">x<?php echo $item['total'][$foods['id']]['total'];?></div>
            </div>
          </li>
          <?php } ?>
          <li class="mui-table-view-cell" style="font-weight:bold;">
            <?php if ($item['status'] == 1 && $item['paytype'] == 1){?>
                <span class="text-success payover "><a href="<?php echo fm_murl($do,'pay', 'index', array('orderid' => $item['id']));?>">立即付款</a></span>
                <?php }elseif ($item['status'] == '2'){?>
                <span class="text-success payover">已下单</span>
                <?php }elseif ($item['status'] == '3'){?>
                <span class="text-success payover">已确认</span>
                <?php }elseif ($item['status'] == '0'){?>
                <span class="text-success payover">已完成</span>
                <span class="text-success payover ">
                 <a href="<?php echo fm_murl($do,$ac, 'delete',array('id' => $item['id'],'pcate'=>$_GPC['pcate'],'ccate'=>$_GPC['ccate']));?>" onclick="return confirm('确认删除？');return false;">删除</a>
                 </span>
                <?php }elseif ($item['status'] == '-1'){?>
                <span class="text-success payover">已取消</span>
                <span class="text-success payover ">
                 <a href="<?php echo fm_murl($do,$ac, 'delete',array('id' => $item['id'],'pcate'=>$_GPC['pcate'],'ccate'=>$_GPC['ccate']));?>" onclick="return confirm('确认删除？');return false;">删除</a>
                 </span>
                <?php }?>

            <span class="mui-pull-right" style="color:#b68700;">总计: <?php echo $item['price'];?>元</span>
          </li>
        </ul>
    </div>
    <!--页脚，放置补充信息或支持的操作-->
    <div class="mui-hidden mui-card-footer">页脚</div>
  </div>

  <div class="" style="text-align: center;">
    <span style="text-align: center;font-weight: 200;">请向工作人员出示此二维码,以便核销</span>
    <img src="<?php echo $qrcode_preview;?>" data-src="<?php echo $link_preview;?>" style="width:200px;height:200px;margin-top:10px;margin-bottom:50px;">
  </div>

  <div class="tel tel-float mui-pull-right">
    <a href="tel:<?php echo $item['shop']['shouji'];?>">
    <img src="<?php echo $fm453resource.'/vfoods/tel.png';?>" data-src="<?php echo $fm453resource.'/vfoods/home-button.png';?>" style="width:60px;height:60px;position: fixed;bottom: 100px;right: 10px;">
  </a>
  </div>

  <footer class="">
    <nav class="mui-bar mui-bar-tab">

    <a class="mui-tab-item mui-action-back" style="background:#333;color:#fff;" >
      <span class="mui-tab-label">
        <span class="mui-icon mui-icon-back" style=""></span>返回选择
      </span>
    </a>

    <a class="mui-tab-item" style="background:#333;color:#fff;" href="<?php echo fm_murl($do,$ac,'index',array());?>">
      <span class="mui-tab-label">
        <span class="mui-icon mui-icon-list"></span>全部订单
      </span>
    </a>

    <a class="mui-tab-item" style="background:#333;color:#fff;" href="<?php echo fm_murl('member','index','index',array('state'=>'vfoods'));?>">
      <span class="mui-tab-label">
        <span class="mui-icon mui-icon-contact"></span>会员中心
      </span>
    </a>
    </nav>
  </footer>
</div>

<script>
  mui.init();
</script>