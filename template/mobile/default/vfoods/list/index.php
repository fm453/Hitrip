<div class="container container-fill">
<link type="text/css" rel="stylesheet" href="<?php echo $htmlsrc.$do.DIRECTORY_SEPARATOR.'_statics'.DIRECTORY_SEPARATOR.'common.css';?>">
<link type="text/css" rel="stylesheet" href="<?php echo $fm453resource;?>components/jquery-fancybox/jquery.fancybox.min.css">
<script type="text/javascript" src="<?php echo $fm453resource;?>components/jquery-fancybox/jquery.fancybox.min.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $('.fancybox').fancybox();
    $('.fancybox-skin').css("padding","0");
  });
</script>

<!-- 头部位置 -->
<div class="titlebar" style="width:100%;height:50px;background-color: #3b3b3b;position: fixed;top: 0;left: 0;text-align: center;z-index: 999;">
  <div class="go-back" style="float:left;line-height:50px;">
    <a class="" style="color:#fff;margin-left:30px;" href="<?php echo fm_murl($do,'index','index',array('ordertype'=>$ordertype));?>"><i class="glyphicon glyphicon-chevron-left"></i></a>
  </div>
  <span class="page-title" style="margin-left:-45px;color:#fff;line-height:50px;"><?php echo $category['title'];?></span>
</div>

<!-- 菜品搜索 -->
<div class="" style="display:none;margin-top:50px;margin-bottom:0;padding-bottom:10px;background: #eee;height:60px;line-height:50px;text-align: center;vertical-align: middle;">
  <form action="<?php echo $_W['siteurl'];?>" class="form-horizontal" method="post">
    <div class="controls" style="vertical-align: middle; padding-top: 5px;">
      <input type="text" placeholder="  🔍  搜索菜品" class="input-xlarge search-query" style="width:200px;border-radius: 50px;" name="keywords" value="<?php echo $keywords;?>">
      <button class="btn btn-default" style="margin-left:10px;border-radius: 25px;background-color: #777;color:#fff;" type="submit">搜索</button>
    </div>
  </form>
</div>

<!-- 菜品区域 -->
<div class="menu-list menu-list2" style="margin-top:0;margin-bottom:55px;padding-bottom: 50px;position: relative;overflow-x:hidden;">

  <div class="" style="margin-top: 0;margin-bottom: 0;width: 80px;position: fixed;top: 50px;bottom: 50px;left: 0;border-left: 0;overflow-x: hidden;background-color: #ddd;border-radius:0;">
      <a class="btn <?php if($_GPC['ccate'] == 0){echo 'btn-success'; }else{echo 'btn-default';} ?>" style="padding:5px;border-radius:0;width: 80px;height:60px;line-height: 50px;white-space: normal;" href="<?php echo fm_murl($do,$ac,'', array('pcate' => $category['id'],'order' =>$_GPC['order']));?>" role="button">
        全部菜系
    </a>

    <?php if($sort){foreach($sort as $citem){?>

    <a class="btn <?php if($citem['id'] == $_GPC['ccate']){echo 'btn-success'; }else{echo 'btn-default';} ?>" style="padding:5px;border-radius:0;width: 80px;height:60px;line-height: 50px;white-space: normal;" href="<?php echo fm_murl($do,$ac,'', array('ccate' => $citem['id'],'order' =>$_GPC['order']));?>" role="button"><?php echo $citem['title'];?>&nbsp;<span class="badge ccatenum_<?php echo $citem['id'];?>"></span></a>

    <?php }}?>

  </div>

  <ul class="list-unstyled" style="position: relative;right: 0;left: 80px;top: 50px;bottom: 50px;background: #fff;padding-left: 2px;overflow-y: scroll;">
      <?php if($list){foreach($list as $item){?>
      <li class="shopli">
        <div class="pull-right" style="position: absolute;right: 90px;z-index: 900;">
          <?php if($category['enabled'] == "0"){?>
          <div>&nbsp;&nbsp;</div>
          <div><span class="label label-info">休息中</span></div>
          <?php }elseif($item['status'] == "1"){?>
          <div>&nbsp;&nbsp;</div>

          <span class="menu-list-button reduce" id="foodsreduce_<?php echo $item['id'];?>" onclick="order.reduce(<?php echo $item['id'];?>);">
            <?php if($item['sn'][$item['id']]['total'] > 0){?><img src="<?php echo $htmlsrc.$do.DIRECTORY_SEPARATOR.'_statics'.DIRECTORY_SEPARATOR.'reduce-red.png';?>" height="35px" width="35px" /><?php }?>
          </span>
          <span class="menu-list-num" id="foodsnum_<?php echo $item['id'];?>" style="padding:1px;"><?php echo $item['sn'][$item['id']]['total'];?></span>
          <span class="menu-list-button add" id="foodsadd_<?php echo $item['id'];?>" onclick="order.add(<?php echo $item['id'];?>)" style="margin-right:5px;"><img src="<?php echo $htmlsrc.$do.DIRECTORY_SEPARATOR.'_statics'.DIRECTORY_SEPARATOR.'add.png';?>" height="35px" width="35px" /></span>

        <?php }else{?>
          <div>&nbsp;&nbsp;</div>
          <div><span class="label label-info">卖完了</span></div>
        <?php }?>
        </div>

        <div class="pull-left menu-pic" style="width: 75px;height: 75px;text-align: center;margin: 5px auto;">
          <a class="fancybox fancybox.ajax" href="<?php echo fm_murl($do,'detail','index', array('id' => $item['id']));?>">
              <?php if($item['thumb']){?><img src="<?php echo $_W['attachurl'].$item['thumb'];?>" class="img-rounded" style="width:65px;height:65px;">
              <?php }else{?><img src="" class="img-rounded" style="width:65px;height:65px;">
              <?php }?>
              <?php if($item['ishot'] == 1){?>
              <img class="hot" src="<?php echo $htmlsrc.$do.DIRECTORY_SEPARATOR.'_statics'.DIRECTORY_SEPARATOR.'hot.png';?>" style="left: 40px;top: 0;">
              <?php }?>
          </a>
        </div>

        <div class="pull-left menu-detail">
          <span class="title"><?php echo $item['title'];?></span>
          <span class="click"><?php if($item['stock']){?>余量 <?php echo $item['stock'];?><?php if($item['unit']){echo $item['unit'];}else{echo "份";}?>&nbsp;&nbsp;<del><i style="display: none;">点击量:</i><?php }else{?>&nbsp;&nbsp;共售出<del ><?php }?><?php echo $item['hits'];?></del></span>
          <span class="price" style="font-weight: bold;font-size:20px;">￥<?php if($item['preprice']){ echo $item['preprice'];}else{echo $item['oriprice'];}?>
            <i style="color:#ccc;font-weight:normal;font-size:12px;"><?php if($item['unit']){echo '/'.$item['unit'];}?>&nbsp;&nbsp;<?php if($item['oriprice']){?><del>￥<?php echo $item['oriprice'];?></del><?php }?></i>
          </span>


        </div>

      </li>
      <div class="shopfoot"></div>
      <?php }}?>
    </ul>

    <?php echo $pager;?>

</div>

<!-- 底部位置 -->
<div class="footbar" style="width:100%;height:50px;background-color: #3b3b3b;position: fixed;bottom: 0;left: 0;z-index:999;">
<?php if($pricetotal >= $category['sendprice']){ ?>
  <a class="btn btn-danger check" href="<?php echo fm_murl($do,'mycart','index',array('pcate'=>$shopid,'ccate'=>$_GPC['ccate']));?>">
<?php }else{?>
  <a class="btn btn-success check" href="#">
<?php }?>
    <b class="between">
      <?php if($pricetotal >= $category['sendprice']){?>
      去结算
      <?php }else{?>
      差￥<?php echo $between;?>起售
      <?php }?>
    </b>
  </a>
  <span class="btn btn-default" style="margin-left:10px;margin-top:5px;width:40px;height:40px;border-radius: 50%;padding:0;font-size: 30px;"><i class="icon-shopping-cart"></i><b class="img-circle pcateimg"  style="color: #fff;background-color: #f00;width: 18px;height: 18px;font-size: 15px;position: absolute;top: 5px;left: 40px;line-height: 18px;"></b></span>
  <span style="margin-left:10px;color:#fff;font-size:30px;vertical-align: middle;font-weight: lighter;"><span class="centerimg"><?php if($pcatetotal > 0){?> ￥<?php }?></span><span class="priceimg"></span></span>
</div>

<!-- 写入各菜品已点购数量 -->
<?php foreach($ccatenum as $row){?>
  <script>
    $('.ccatenum_<?php echo $row["id"];?>').html('<?php echo $row["num"];?>');
  </script>
<?php }?>

<!-- 其他JS事件 -->
<script>
  function menuData(a) {
    var a = $(a);
    var e = 0;
    var b = $('.menu-button li a').parent();
    a.parent().parent().find('.menu-list-num').each(function(i) {
      e = parseInt($(this).html()) + e;
    });

    if(b.find('.img-circle').html() == ''){
      b.find('.img-circle').html(0);
      e = 0;
    }

  }

  var pcatetotal = '<?php echo $pcatetotal;?>';
  if(pcatetotal != '0'){
      $('.pcateimg').html('<?php echo $pcatetotal;?>');
      $('.priceimg').html('<?php echo $pricetotal;?>');
  }

  $('.menu-button3').css({"display": "none"});
  $('.shopping-type3').click(function() {
    var a = $(this).attr("switch");
    if(a == 1) {
      $('.menu-button3').css({"display": "none"});
      $('.menu-list').css({"margin-left": "10px"});
      $(this).attr("switch", 0);
    } else {
      $('.menu-button3').css({"display": "block"});
      $('.menu-list').css({"margin-left": "10px"});
      $(this).attr("switch", 1);
    }
    return false;
  });

  var order = {
    'add' : function(foodsid) {
      var $this = this;
      $this.cart(foodsid, 'add');
    },

    'reduce' : function(foodsid) {
      var $this = this;
      $this.cart(foodsid, 'reduce');
    },

    'cart' : function(foodsid, operation) {
      if (!foodsid) {
        alert('请选择菜品!');
        return;
      }

      operation = operation ? operation : 'add';
      href ="<?php echo fm_murl($do,'mycart','index',array('pcate'=>$shopid,'ccate'=>$_GPC['ccate']));?>";
      shopid = "<?php echo $shopid;?>";

      $.getJSON("<?php echo fm_murl($do,'updatecart','',array('pcate'=>$shopid,'ccate'=>$_GPC['ccate']));?>&op="+operation, {'foodsid' : foodsid,'shopid':shopid}, function(s){
        console.log(s);
        if (s.message.status) {
          if(s.message.total > 0){
            $('#foodsnum_'+foodsid).html(s.message.total);
            $('#foodsreduce_'+foodsid).html("<img src='<?php echo $htmlsrc.$do.DIRECTORY_SEPARATOR.'_statics'.DIRECTORY_SEPARATOR.'reduce-red.png';?>' height='35px' width='35px' />");
            $('#foodsadd_'+foodsid).html("<img src='<?php echo $htmlsrc.$do.DIRECTORY_SEPARATOR.'_statics'.DIRECTORY_SEPARATOR.'add-red.png';?>' height='35px' width='35px' />");
          }else{
            $('#foodsnum_'+foodsid).html('');
            $('#foodsreduce_'+foodsid).html('');
            $('#foodsadd_'+foodsid).html("<img src='<?php echo $htmlsrc.$do.DIRECTORY_SEPARATOR.'_statics'.DIRECTORY_SEPARATOR.'add.png';?>' height='35px' width='35px' />");
          }

          $('.foodsnum_'+foodsid).html(s.message.total);

          if(s.message.pcatetotal > 0){
            $('.pcateimg').html(s.message.pcatetotal);
            $('.priceimg').html(s.message.pricetotal);
            $('.centerimg').html("¥");
          }else{
            $('.pcateimg').html("");
            $('.priceimg').html("");
            $('.centerimg').html("");
          }

          if(s.message.ccatenum > 0){
            $('.ccatenum_'+s.message.ccate).html(s.message.ccatenum);
          }else{
            $('.ccatenum_'+s.message.ccate).html("");
          }

          menuData('#foodsnum_'+foodsid);

          $('.between').html(s.message.between);

          if(s.message.target == "#"){
            $('.check').attr("href","#");
            $(".check").removeClass("btn-danger");
            $(".check").addClass("btn-success");
          }else if(s.message.target == "1") {
            $('.check').attr("href",href);
            $(".check").removeClass("btn-success");
            $(".check").addClass("btn-danger");
          }

          if (s.message.status>1) {
              alert(s.message.message);
          }

        } else {
          alert(s.message.message);
        }
      });
    }

  };
</script>