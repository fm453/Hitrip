<link rel="stylesheet" type="text/css" href="<?php echo $appsrc;?>jquery/reset.css"/>
<script type="text/javascript" src="//api.map.baidu.com/api?v=2.0&ak=<?php echo $settings['api']['baidu_map_api'];?>"></script>

<div id="allmap"></div>
<div class="container container-fill">
<link type="text/css" rel="stylesheet" href="<?php echo $htmlsrc.DIRECTORY_SEPARATOR.$do.DIRECTORY_SEPARATOR.'_public'.DIRECTORY_SEPARATOR.'common.css';?>">
<link type="text/css" rel="stylesheet" href="<?php echo $fm453resource;?>components/jquery-fancybox/jquery.fancybox.min.css">
<script type="text/javascript" src="<?php echo $fm453resource;?>components/jquery-fancybox/jquery.fancybox.min.js"></script>
<script type="text/javascript">
  wx.ready(function(){
    wx.getLocation({
      success: function (res) {
        var latitude = res.latitude; // 纬度，浮点数，范围为90 ~ -90
        var longitude = res.longitude; // 经度，浮点数，范围为180 ~ -180。
        var speed = res.speed; // 速度，以米/每秒计
        var accuracy = res.accuracy; // 位置精度
    		$.getJSON("{php echo fm_murl($do,$ac,'locate',array());}", {'loc_x' : res.latitude, 'loc_y' : res.longitude}, function(s){
    			if (s.message == 'refresh') {
    				location.href = "{php echo fm_murl($do,$ac,'',array());}";
    			}
    		});//getJSON
      }
    });
  });
  $(document).ready(function() {
    $('.fancybox').fancybox();
    $('.fancybox-skin').css("padding","0");
  });
</script>

	<div class="menu-list">
    <div class="panel panel-default" style="margin-top:10px;margin-bottom:0;padding-bottom:10px;">
    <a class="btn <?php if($_GPC['typeid'] == 0){ echo 'btn-success'; }else{ echo 'btn-default';} ?>" style="margin: 5px 0 3px 5px;" href="{php echo fm_murl($do,$ac,'', array('typeid' =>0,'order' =>$_GPC['order']));}" role="button">全部</a>
    <?php foreach($shoptype as $item){ ?>
    <a class="btn <?php if($_GPC['typeid'] == 0){ echo 'btn-success'; }else{ echo 'btn-default';} ?>" style="margin: 5px 0 3px 5px;" href="{php echo fm_murl($do,$ac,'', array('order' =>$_GPC['order'],'typeid' =>$item['id']));}" role="button"><?php echo $item['title'];?></a>
    <?php }?>
	</div>
		<ul class="list-unstyled">
			<?php foreach($shop as $item){ ?>
			<li class="shopli">
             <div class="pull-right">  
             <?php if($item['enabled'] == "0"){ ?>
                     <div><span class="label label-default">休息中</span></div>
             <?php }?>
                       <div>&nbsp;&nbsp;</div>
                    <div class="hidden"><button type="button" class="btn btn-sm btn-default" onClick="javascript:document.location.href='http://apis.map.qq.com/uri/v1/geocoder?coord=<?php echo $item['loc_x'];?>,<?php echo $item['loc_y'];?>]}';"><i class="glyphicon glyphicon-map-marker"></i>&nbsp;<?php echo $item['dist'];?></button></div>
                    
			</div>
				 <a href="<?php echo fm_murl($do,'list','', array('pcate'=>$item['id']));?>">
				<div class="pull-left menu-pic">
					<?php if($item['thumb']){ ?>
          <img src="<?php echo tomedia($item['thumb']);?>" class="img-rounded">
          <?php }else{ ?>
            <img src="<?php echo $_W['attachurl'];?>/headimg_<?php echo $_W['uniacid'];?>.jpg" class="img-rounded">
          <?php }?>
				</div>
				<div class="pull-left menu-detail">
					<span class="title"><?php echo $item['title'];?></span>
                    <span class="click">热度：<?php echo $item['total'];?></span>
                     <span class="click"><?php echo $item['sendprice'];?>元起订</span>
                     <span class="click"><?php echo $item['address'];?></span>
				</div>
                </a>
			</li>
             <div class="shopfoot"></div>
			 <?php }?>
		</ul>
		<?php echo $pager; ?>
	</div>


<div class="navbar1 navbar2 btn-group btn-group-justified">
  <div class="btn-group btn-group-lg dropup">
    <a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><?php if($_GPC['order'] == 0){ echo "默认排序"; }elseif($_GPC['order'] == 1){ echo "按热度"; }elseif($_GPC['order'] == 2){ echo "按起送价"; }elseif($_GPC['order'] == 3){ echo "营业优先";} ?><span class="caret"></span>
    </a>
    <ul class="dropdown-menu" role="menu">
      <li <?php if($_GPC['order'] == 0){ ?> class="active" <?php } ?> > <a href="<?php echo fm_murl($do,$ac,'', array('typeid' => $_GPC['typeid'],'order' =>0));?>">默认排序</a></li>
       <li class="divider"></li>
      <li <?php if($_GPC['order'] == 1){ ?> class="active" <?php } ?> ><a href="<?php echo fm_murl($do,$ac,'',  array('typeid' => $_GPC['typeid'],'order' =>1));?>">按热度</a></li>
       <li class="divider"  hidden="true"></li>
      <li <?php if($_GPC['order'] == 2){ ?> class="active" <?php } ?>   hidden="true"><a href="<?php echo fm_murl($do,$ac,'',  array('typeid' => $_GPC['typeid'],'order' =>2));?>">按起送价</a></li>
      <li class="divider"></li>
      <li <?php if($_GPC['order'] == 3){ ?> class="active" <?php } ?> ><a href="<?php echo fm_murl($do,$ac,'',  array('typeid' => $_GPC['typeid'],'order' =>3));?>">营业优先</a></li>
    </ul>
  </div>
  <div class="btn-group btn-group-lg">
    <a href="<?php echo fm_murl($do,'myorder', '', array('pcate'=>$_GPC['pcate'],'ccate'=>$_GPC['ccate']));?>" class="btn btn-default" role="button"><i class="glyphicon glyphicon-th-list"></i>&nbsp;我的订单</a>
  </div>
  <div class="btn-group btn-group-lg">
    <a href="<?php echo url('mc'); ?>" class="btn btn-default" role="button"><i class="glyphicon glyphicon-user"></i>&nbsp;会员中心</a>
  </div>
</div>
