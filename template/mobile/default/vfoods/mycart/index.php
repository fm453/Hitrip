<style>
  /*点击变蓝色高亮*/
.mui-table-view-cell .mui-active{
  background-color: #b68700;
}
.mui-card{
  margin:10px 0;
}
</style>
<script>
  mui.init();
</script>
<div class="mui-content" style="padding-bottom: 60px;">
  <div class="" style="line-height: 40px;text-align: center;padding-top:10px;margin-top:-10px;">
      <div style="width:220px;height:40px;margin:10px auto;background-image: url(<?php echo $fm453resource.'/vfoods/home-button.png';?>);background-size: cover;">
        <span style="font-size:16px;color:#fff;"><?php echo $pcate2[0]['title'];?></span>
      </div>
  </div>

  <div class="mui-card">
    <!--页眉，放置标题-->
    <div class="mui-hidden mui-card-header">标题</div>
    <!--内容区-->
    <div class="mui-card-content">
        <ul class="mui-table-view order-detail-list">
          <li class="mui-table-view-cell">
            订单确认<span class="mui-pull-right" style="font-weight:400;font-size:20px;color:#b68700;">￥<i class="priceimg"></i></span>
          </li>
          <?php foreach($foods as $item){?>
          <li class="mui-table-view-cell" id="foods-list-li-<?php echo $item['id'];?>">
            <div class="pull-right" style="font-size:25px;">
              <span class="menu-list-button reduce" data-foodsid="<?php echo $item['id'];?>"> <i class="glyphicon glyphicon-minus-sign"></i></span>
              <span class="menu-list-num" id="foods-list-num-<?php echo $item['id'];?>"><?php echo $cart[$item['id']]['total']?></span>
              <span class="menu-list-button add" data-foodsid="<?php echo $item['id'];?>"><i class="glyphicon glyphicon-plus-sign"></i></span>
            </div>
            <div class="pull-left">
              <div class="title"><?php echo $item['title'];?></div>
              <div class="price"><span>
                <?php if($item['preprice']){
                  echo $item['preprice'];
                }else{
                  echo $item['oriprice'];
                }?>
                </span>元<?php if($item['unit']){
                  echo '/'.$item['unit'];
                }?></div>
            </div>
          </li>
          <?php }?>
        </ul>
    </div>
    <!--页脚，放置补充信息或支持的操作-->
    <div class="mui-hidden mui-card-footer">页脚</div>
  </div>

  <div class="mui-card">
    <div class="mui-hidden mui-card-header">订单确认</div>
    <!--内容区-->
    <div class="mui-card-content">
      <?php if($ordertype==1){
          include fmFunc_template_m($do.'/'.$ac.'/form/waimai');
      }elseif($ordertype==2) {
          include fmFunc_template_m($do.'/'.$ac.'/form/tangshi');
      }elseif($ordertype==3) {
          include fmFunc_template_m($do.'/'.$ac.'/form/ziqu');
      }?>
      </div>
    <div class="mui-hidden mui-card-footer">页脚</div>
  </div>

  <div class="" style="text-indent: 20px;">
    <span style="text-align: center;font-weight: 200;">注：带*号的为必填项。</span>
  </div>

  <footer class="">
    <nav class="mui-bar mui-bar-tab">

    <a class="mui-tab-item" style="background:#333;color:#fff;" id="js_clear" >
      <span class="mui-icon mui-icon-trash" style="margin-left: -50%;">清空
      </span>
      <i style="display:none;font-weight: 100;position:relative;left: 70px;">|</i>
    </a>

    <a class="mui-tab-item mui-action-back" style="background:#333;color:#fff;">
      <span class="mui-tab-label">
        <span class="mui-icon mui-icon-spinner-cycle mui-spin"></span>
      再选选</span>
    </a>

    <?php if(!($pricetotal < $pcate2[0]['sendprice'])){?>
    <a class="mui-tab-item" style="background:#b68700;color:#fff;" id="js_submit">
      <span class="mui-tab-label" id="js_submit_span">去付款</span>
    </a>
    <?php }else{ ?>
    <a class="mui-tab-item" style="background:#ddd;color:#fff;">
      <span class="mui-tab-label" id="js_submit_span">还差￥<?php echo $between;?></span>
    </a>
    <?php } ?>

    </nav>
  </footer>
</div>

<script>
  //页面完成后加载初始化事件
  mui.ready(function(){
    $('.priceimg').html('<?php echo $pricetotal;?>');
  });

  //监听事件
  mui('body').on('tap','#js_clear',function () {
    var btnArray = ['不,点错了', '嗯,清空吧'];
    mui.confirm('确认清空点餐记录吗？', "<?php echo $shopname?>提示", btnArray, function(e) {
      if (e.index == 1) {
          mui.openWindow({
          url: "<?php echo fm_murl($do,'clear', 'index', array('pcate'=>$_GPC['pcate'],'ccate'=>$_GPC['ccate']));?>",
          id: 'webviewIdClear',
        });
      }
    });
  });

  mui('body').on('tap','#js_submit',function () {
    formcheck('form');
  });

  mui('.order-detail-list').on("tap",".add", function(){
    var a = $(this).parent().parent();
    var i = $(this).attr('data-foodsid');
    order.add(i);
    // a.find('.menu-list-num').html(function() {
    //   return parseInt($(this).html()) + 1;
    // });
  });

  mui('.order-detail-list').on("tap",".reduce", function(){
    var a = $(this).parent().parent();
    var i = $(this).attr('data-foodsid');
    order.reduce(i);
    // if(a.find('.menu-list-num').html() == 1 || a.find('.menu-list-num').html() < 0) {
    //   var btnArray = ['不,点错了', '嗯,删除'];
    //   mui.confirm('确认要删除吗？', "<?php echo $shopname?>提示", btnArray, function(e) {
    //     if (e.index == 1) {
    //       a.remove();
    //     }else{
    //       return false;
    //     }
    //   });
    // }
    // a.find('.menu-list-num').html(function() {
    //   return parseInt($(this).html()) - 1;
    // });
  });

  //菜品数量加减
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
        alert('请您先选择菜品。');
        return;
      }
      operation = operation ? operation : 'add';
      $.getJSON('<?php echo fm_murl($do,'updatecart','index',array('pcate'=>$_GPC['pcate'],'ccate'=>$_GPC['ccate']));?>', {'foodsid' : foodsid,'op':operation}, function(s){
          if (s.message.status) {
            $('#foodsnum_'+foodsid).html(s.message.total);
            var i = parseInt($('#foods-list-num-'+foodsid).html());
            switch(operation){
              case 'add':
                $('#foods-list-num-'+foodsid).html(i+1);
              break;
              case 'reduce':
                if(i == 1 || i < 0) {
                  var btnArray = ['不,点错了', '嗯,删除'];
                  mui.confirm('确认要删除吗？', "<?php echo $shopname?>提示", btnArray, function(e) {
                    if (e.index == 1) {
                      $('#foods-list-li-'+foodsid).remove();
                    }else{
                      return false;
                    }
                  });
                }
                $('#foods-list-num-'+foodsid).html(i-1);
              break;
            }

            $('.pcateimg').html(s.message.pcatetotal);
            $('.priceimg').html(s.message.pricetotal);
            $('.nav2 input').val(s.message.between);
            if(s.message.target == "#"){
              $('.nav2 input').attr("disabled","disabled");
            }else if(s.message.target == "1"){
              $('.btn-success').removeAttr("disabled");
            }
          } else {
            alert(s.message.message);
          }
      });
    }
  };

  function formcheck(formid) {
    form = $('#'+formid);
    var ordertype = form.attr('data-type');
    formdata = Form_format(formid);
    if (!formdata['mobile']) {
      mui.alert('请输入您的手机号码');
      return false;
    }else{
      var mflag = /^1\d{10}$/ .test(formdata['mobile']);
      if(!mflag){
      mui.alert('请输入正确的手机号码');
        return false;
      }
    }

    if (!formdata['paytype']) {
      mui.alert('请选择付款方式');
      return false;
    }

    document.getElementById(formid).submit();
    // form.submit();
  }
</script>