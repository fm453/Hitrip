<div class="container container-fill">
<link type="text/css" rel="stylesheet" href="<?php echo $htmlsrc.$do.DIRECTORY_SEPARATOR.'_statics'.DIRECTORY_SEPARATOR.'common.css';?>">
<style>
.table{font-size:12px;}
.table td{padding:5px 0;}
.table .title{border-top:0; font-weight:600; color:#51a351;}
.table .price{}
.table .price .total{font-size:14px; font-weight:bold; display:inline-block; width:100px;}
.table .price .btn{margin-top:5px;}
.table .price .payover{font-size:14px; font-weight:bold;}
.btn{padding:3px 6px;}
</style>

<div class="order-main">
  <div class="order-hd">
    <span>我的订单</span>
  </div>
      <?php foreach($list as $item){?>
              <div class="order-detail">

                <div class="order-detail-list">
      <table class="table">
            <tr>
          <td class="title suoshudianjia" colspan="3">
            <span class="pull-left">所属餐厅：<i><?php echo $item['pcate3'][0]['title'];?></i></span>
          </td>
        </tr>
                 <tr>
          <td class="title dd" colspan="3">
            <span class="pull-left">联系电话：</span>
                        <span class="pull-right"><b><?php echo $item['mobile'];?></b></span>
          </td>
        </tr>
                 <tr>
          <td class="title dd" colspan="3">
            <span class="pull-left">送餐时间：</span>
                        <span class="pull-right"><b><?php echo $item['time'];?></b></span>
          </td>
        </tr>
                 <tr  hidden="true">
          <td class="title dd" colspan="3">
            <span class="pull-left">送餐地址：</span>
                        <span class="pull-right"><b><?php echo $item['address'];?></b></span>
          </td>
        </tr>
                <tr>
          <td class="title dd" colspan="3">
            <span class="pull-left">支付方式：</span>
                        <span class="pull-right"><b><?php if($item['paytype'] == 1){?>在线支付<?php }else{?>当面付款<?php }?></b></span>
          </td>
        </tr>
                <?php if($item['other']){?>
                 <tr>
          <td class="title dd" colspan="3">
            <span class="pull-left">订单备注：</span>
                        <span class="pull-right"><b><?php echo $item['other'];?></b></span>
          </td>
        </tr>
                <?php }?>
        <tr>
          <td class="title" colspan="3">
            <span class="pull-right"><?php echo date('m-d H:i', $item['createtime']);?></span>
            <span class="pull-left">★订单号：<?php echo $item['ordersn'];?></span>
          </td>
        </tr>

        <?php foreach($item['foods'] as $foods){?>
        <tr>
          <td style="width:40%;"><?php echo $foods['title'];?></td>
          <td style="width:60%; text-align:right;">数量：<?php echo $item['total'][$foods['id']]['total'];?>，单价：<?php if($foods['preprice']){echo $foods['preprice'];}else{echo $foods['oriprice'];}?>元 / <?php echo $foods['unit'];?></td>
        </tr>
        <?php } ?>

        <tr>
          <td class="price" colspan="3">
            <div class="pull-right">
              <span class="total">总计：<?php echo $item['price'];?>元</span>
                <?php if ($item['status'] == 1 && $item['paytype'] == 1){?>
                <span class="text-success payover btn btn-warning"><a href="<?php echo fm_murl($do,'pay', '', array('orderid' => $item['id']));?>">立即付款</a></span>
                <span class="text-success payover btn btn-danger"><a href="tel:<?php echo $item['pcate3'][0]['shouji'];?>" mce_href="tel:<?php echo $item['pcate3'][0]['shouji'];?>">餐厅电话</a></span>
                <?php }elseif ($item['status'] == '2'){?>
                <span class="text-success payover">已下单</span>
                <span class="text-success payover btn btn-danger"><a href="tel:<?php echo $item['pcate3'][0]['shouji'];?>" mce_href="tel:<?php echo $item['pcate3'][0]['shouji'];?>">餐厅电话</a></span>
                <?php }elseif ($item['status'] == '3'){?>
                <span class="text-success payover">已确认</span>
                <span class="text-success payover btn btn-danger"><a href="tel:<?php echo $item['pcate3'][0]['shouji'];?>" mce_href="tel:<?php echo $item['pcate3'][0]['shouji'];?>">餐厅电话</a></span>
                <?php }elseif ($item['status'] == '0'){?>
                <span class="text-success payover">已完成</span>
                <span class="text-success payover btn btn-danger">
                 <a href="<?php echo fm_murl($do,$ac, 'shanchu',array('id' => $item['id'],'pcate'=>$_GPC['pcate'],'ccate'=>$_GPC['ccate']));?>" onclick="return confirm('确认删除？');return false;">删除</a>
                 </span>
                <?php }elseif ($item['status'] == '-1'){?>
                <span class="text-success payover">已取消</span>
                <span class="text-success payover btn btn-danger">
                 <a href="<?php echo fm_murl($do,$ac, 'shanchu',array('id' => $item['id'],'pcate'=>$_GPC['pcate'],'ccate'=>$_GPC['ccate']));?>" onclick="return confirm('确认删除？');return false;">删除</a>
                 </span>
                <span class="text-success payover btn btn-danger"><a href="tel:<?php echo $item['pcate3'][0]['shouji'];?>" mce_href="tel:<?php echo $item['pcate3'][0]['shouji'];?>">餐厅电话</a></span>
                <?php }?>
            </div>
          </td>
        </tr>
      </table>
            </div>
          </div>
      <?php } ?>

      <div class="order-detail">
          <div class="order-detail-list" style="text-align: center;font-weight: 200;font-size:12px;">
            请向工作人员出示此二维码，以便核销
            <img src="<?php echo $qrcode_preview;?>" data-src="<?php echo $link_preview;?>" style="width:200px;height:200px;margin-top:10px;margin-bottom:50px;">
          </div>
        </div>
</div>
<div class="navbar1 navbar2 btn-group btn-group-justified">
      <div class="btn-group btn-group-lg">
      <a href="<?php echo fm_murl($do,'index','index',array());?>" class="btn btn-default" role="button"><i class="glyphicon glyphicon-chevron-left"></i>&nbsp;选择餐厅</a>
      </div>
      <div class="btn-group btn-group-lg">
      <a href="<?php echo fm_murl($do,$ac,'index',array());?>" class="btn btn-default" role="button"><i class="fa fa-bars"></i>&nbsp;全部订单</a>
      </div>
      <div class="btn-group btn-group-lg">
      <a href="<?php echo fm_murl('member','index','index',array('state'=>'vfoods'));?>" class="btn btn-default" role="button"><i class="glyphicon glyphicon-user"></i>&nbsp;会员中心</a>
      </div>
    </div>
<script>
$(function() {
  $('.order-detail-list li:last').css("border-bottom", 0);
});
</script>
