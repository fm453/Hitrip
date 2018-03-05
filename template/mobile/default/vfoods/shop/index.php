<link rel="stylesheet" type="text/css" href="<?php echo $appsrc;?>jquery/reset.css"/>
<script type="text/javascript" src="//api.map.baidu.com/api?v=2.0&ak=ng0vVZSU3GLhmdhZKR5FZ2gD2oCNyhyG"></script>

<div class="container container-fill">
<link type="text/css" rel="stylesheet" href="<?php echo $htmlsrc.DIRECTORY_SEPARATOR.$do.DIRECTORY_SEPARATOR.'_public'.DIRECTORY_SEPARATOR.'common.css';?>">
<link type="text/css" rel="stylesheet" href="<?php echo $fm453resource;?>components/jquery-fancybox/jquery.fancybox.min.css">
<script type="text/javascript" src="<?php echo $fm453resource;?>components/jquery-fancybox/jquery.fancybox.min.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $('.fancybox').fancybox();
    $('.fancybox-skin').css("padding","0");
  });
</script>

<div class="navbar3">
  <div class="nav2">
    <a class="btn btn2 btn-default" href="<?php echo fm_murl($do,'dianjia','',array());?>"><i class="glyphicon glyphicon-chevron-left"></i>店铺</a>
    <?php if($pricetotal >= $category['sendprice']){ ?>
    <a class="btn btn-warning check" href="<?php echo fm_murl($do,'mycart','',array('pcate'=>$_GPC['pcate'],'ccate'=>$_GPC['ccate']));?>">
    <?php }else{?>
    <a class="btn btn-success check" href="#">
    <?php }?>
    <b class="between">
      <?php if($pricetotal >= $category['sendprice']){?>
      去结算
      <?php }else{?>
      差￥<?php echo $between;?>起送
      <?php }?>
    </b>
    
     </a>
    <span class=""><i class="icon-shopping-cart"></i><b class="img-circle pcateimg"></b><b class="centerimg"><?php if($pcatetotal > 0){?> 份￥<?php }?></b><b class="priceimg"></b></span>

  </div>
</div>



<div class="menu-button menu-button3">
  <ul class="list-unstyled">
    <li><a style="background-color:#F93;color:white;text-align:left; padding:0; padding-left:5px; font-weight:bold; font-size:14px;" class="active" href="#">店家介绍</a></li>
    <div class="menu-pic2">
      <div class="dianjiadiv">
        <?php if($category['thumb']){?><img src="<?php echo tomedia($category['thumb']);?>" class="img-rounded dianjialist">
        <?php }else{?><img src="<?php echo $_W['attachurl'];?>/headimg_<?php echo $_W['uniacid'];?>.jpg" class="img-rounded dianjialist">
        <?php }?></div><br>
        <div class="namelist dianjiadiv"><?php echo $category['title'];?></div>
      </div>

      <li class="dianjiali"  hidden="true">外卖起送价：<?php echo $category['sendprice'];?></li>

      <li class="dianjiali">店家热度：<?php echo $category['total'];?></li>

      <li class="dianjiali">店家手机：<br /><?php echo $category['shouji'];?></li>

      <li class="dianjiali">店家简介：<br /><?php echo $category['description'];?></li>

      <li class="dianjiali">中午送餐时间：<br /><?php echo $ptime1;?>至<?php echo $ptime2;?></li>

      <li class="dianjiali">下午送餐时间：<br /><?php echo $ptime3;?>至<?php echo $ptime4;?></li>
    </ul>
  </div>


  <div class="menu-list menu-list2">
    <div class="panel panel-default" style="margin-top:10px;margin-bottom:0;padding-bottom:10px;">
      <div class="panel-heading">
        <h4 class="panel-title"><?php echo $category['title'];?></h4>
      </div>
      <a class="btn <?php if($_GPC['ccate'] == 0){echo 'btn-success'; }else{echo 'btn-default';} ?>" style="margin: 5px 0 3px 5px;" href="<?php echo fm_murl($do,$ac,'', array('pcate' => $category['id'],'order' =>$_GPC['order']));?>" role="button">全部菜系</a>

    <?php foreach($sort as $citem){?>

    <a class="btn <?php if($citem['id'] == $_GPC['ccate']){echo 'btn-success'; }else{echo 'btn-default';} ?>" style="margin: 5px 0 3px 5px;" href="<?php echo fm_murl($do,$ac,'', array('ccate' => $citem['id'],'order' =>$_GPC['order']));?>" role="button"><?php echo $citem['title'];?>&nbsp;<span class="badge ccatenum_<?php echo $citem['id'];?>"></span></a>

    <?php }?>

  </div>
    <ul class="list-unstyled">
      <?php foreach($list as $item){?>
      <li class="shopli">
        <div class="pull-right">
          <?php if($category['enabled'] == "0"){?>
          <div>&nbsp;&nbsp;</div>
          <div><span class="label label-info">休息中</span></div>
          <?php }elseif($item['status'] == "1"){?>
          <div>&nbsp;&nbsp;</div>
          
          <span class="menu-list-button reduce" id="foodsreduce_<?php echo $item['id'];?>" onclick="order.reduce(<?php echo $item['id'];?>);">
            <?php if($item['sn'][$item['id']]['total'] > 0){?><img src="<?php echo $htmlsrc.DIRECTORY_SEPARATOR.$do.DIRECTORY_SEPARATOR.'_public'.DIRECTORY_SEPARATOR.'reduce.png';?>" height="30px" width="30px" /><?php }?>
          </span>

          <span class="menu-list-num" id="foodsnum_<?php echo $item['id'];?>"><?php echo $item['sn'][$item['id']]['total'];?></span>

          <span class="menu-list-button add" onclick="order.add(<?php echo $item['id'];?>)"><img src="<?php echo $htmlsrc.DIRECTORY_SEPARATOR.$do.DIRECTORY_SEPARATOR.'_public'.DIRECTORY_SEPARATOR.'add.png';?>" height="30px" width="30px" /></span>
        
        <?php }else{?>
          <div>&nbsp;&nbsp;</div>
          <div><span class="label label-info">卖完了</span></div>
        <?php }?>
        </div>

        <div class="pull-left menu-pic">
          <a class="fancybox fancybox.ajax" href="<?php echo fm_murl($do,'detail','', array('id' => $item['id']));?>">

              <?php if($item['thumb']){?><img src="<?php echo $_W['attachurl'].$item['thumb'];?>" class="img-rounded">
              <?php }else{?><img src="" class="img-rounded">
              <?php }?>
              <?php if($item['ishot'] == 1){?>
              <img class="hot" src="<?php echo $htmlsrc.DIRECTORY_SEPARATOR.$do.DIRECTORY_SEPARATOR.'_public'.DIRECTORY_SEPARATOR.'hot.png';?>">
              <?php }?>
          </a>
        </div>

        <div class="pull-left menu-detail">
          <span class="title"><?php echo $item['title'];?></span>
          <span class="price">
            <?php if($item['preprice']){?>
            <i class="label label-success">优惠</i> <?php echo $item['preprice'];?>元
            <?php }else{?>
              <?php echo $item['oriprice'];?>元
            <?php }?>
            <?php if($item['unit']){echo '/'.$item['unit'];}?>
          </span>
          <?php if($item['oriprice']){?>
          <span class="oriprice"><?php echo $item['oriprice'];?>元
          <?php if($item['unit']){echo '/'.$item['unit'];}?>
          </span>
          <?php }?>
          <span class="click"><?php echo $item['hits'];?>人点过</span>
        </div>

      </li>
      <div class="shopfoot"></div>
      <?php }?>
    </ul>

    <?php echo $pager;?>

  </div>

   
<div class="navbar1 navbar2 btn-group btn-group-justified">
  <div class="btn-group btn-group-lg dropup">
    <a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><?php if($_GPC['order'] == 0){ echo "默认排序"; }elseif($_GPC['order'] == 1){ echo "按热度"; }elseif($_GPC['order'] == 2){ echo "按起送价"; }elseif($_GPC['order'] == 3){ echo "营业优先";} ?><span class="caret"></span>
    </a>
    <ul class="dropdown-menu" role="menu">
      <li <?php if($_GPC['order'] == 0){ ?> class="active" <?php } ?> > <a href="<?php echo fm_murl($do,$ac,'', array('pcate'=>$_GPC['pcate'],'ccate'=>$_GPC['ccate'],'order' =>0));?>">默认排序</a></li>
       <li class="divider"></li>
      <li <?php if($_GPC['order'] == 1){ ?> class="active" <?php } ?> ><a href="<?php echo fm_murl($do,$ac,'',  array('pcate'=>$_GPC['pcate'],'ccate'=>$_GPC['ccate'],'order' =>1));?>">按热度</a></li>
       <li class="divider"  hidden="true"></li>
      <li <?php if($_GPC['order'] == 2){ ?> class="active" <?php } ?>   hidden="true"><a href="<?php echo fm_murl($do,$ac,'',  array('pcate'=>$_GPC['pcate'],'ccate'=>$_GPC['ccate'],'order' =>2));?>">按优惠</a></li>
    </ul>
  </div>
  <div class="btn-group btn-group-lg">
    <a href="<?php echo fm_murl($do,'myorder', '', array('pcate'=>$_GPC['pcate'],'ccate'=>$_GPC['ccate']));?>" class="btn btn-default" role="button"><i class="glyphicon glyphicon-th-list"></i>&nbsp;我的订单</a>
  </div>
  <div class="btn-group btn-group-lg">
    <a href="#" class="btn btn-default shopping-type3" role="button">店家介绍</a>
  </div>
</div>


<?php foreach($ccatenum as $row){?>
  <script>
    $('.ccatenum_<?php echo $row["id"];?>').html('<?php echo $row["num"];?>');
  </script>
<?php }?>

<script>

function menuData(a) {

  var a = $(a);

  var e = 0;

  var b = $('.menu-button li a').parent();

  a.parent().parent().find('.menu-list-num').each(function(i) {

    e = parseInt($(this).html()) + e;

  });

  if(b.find('.img-circle').html() == '') b.find('.img-circle').html(0);

  e = 0;

}

if('<?php echo $pcatetotal;?>' != '0'){

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

    href ="<?php echo fm_murl($do,'mycart','',array('pcate'=>$_GPC['pcate'],'ccate'=>$_GPC['ccate']));?>";

    $.getJSON("<?php echo fm_murl($do,'updatecart','',array('pcate'=>$_GPC['pcate'],'ccate'=>$_GPC['ccate']));?>&op="+operation, {'foodsid' : foodsid}, function(s){
      console.log(s);

      if (s.message.status) {

        if(s.message.total > 0){
          $('#foodsnum_'+foodsid).html(s.message.total);
          $('#foodsreduce_'+foodsid).html('<img src="<?php echo $htmlsrc.DIRECTORY_SEPARATOR.$do.DIRECTORY_SEPARATOR.'_public'.DIRECTORY_SEPARATOR.'reduce.png';?>" height="28px" width="28px" />');
        }else{
          $('#foodsnum_'+foodsid).html('');
          $('#foodsreduce_'+foodsid).html('');
        }

        $('.foodsnum_'+foodsid).html(s.message.total);

        if(s.message.pcatetotal > 0){
          $('.pcateimg').html(s.message.pcatetotal);
          $('.priceimg').html(s.message.pricetotal);
          $('.centerimg').html("份¥");
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
          $(".check").removeClass("btn-warning"); 
          $(".check").addClass("btn-success");
        }else if(s.message.target == "1") {
          $('.check').attr("href",href);
          $(".check").removeClass("btn-success"); 
          $(".check").addClass("btn-warning");
        }

      } else {
        alert(s.message.message);
      }

    });

  }

};

</script>
