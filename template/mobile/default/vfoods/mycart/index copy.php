<div class="container container-fill">
<link type="text/css" rel="stylesheet" href="<?php echo $htmlsrc.$do.DIRECTORY_SEPARATOR.'_statics'.DIRECTORY_SEPARATOR.'common.css';?>">
<div class="dish-main">
  <div class="order-detail">
    <div class="order-hd">
      <span class="pcate2name"><a href="<?php echo fm_murl($do,'list','index', array('pcate'=>$_GPC['pcate'],'ccate'=>$_GPC['ccate']));?>">餐厅：<?php echo $pcate2[0]['title'];?></a></span>
    </div>
    <div class="order-detail-hd">
      <span class="pull-right">
        <a class="btn btn-success btn-sm" href="<?php echo fm_murl($do,'list','index', array('pcate'=>$_GPC['pcate'],'ccate'=>$_GPC['ccate']));?>"><i class="glyphicon glyphicon-plus"></i> 再选选</a>
        <a class="btn btn-danger btn-sm" href="<?php echo fm_murl($do,'clear', 'index', array('pcate'=>$_GPC['pcate'],'ccate'=>$_GPC['ccate']));?>" onclick="return confirm('此操作不可恢复，确认清空菜单吗？'); return false;"><i class="glyphicon glyphicon-trash"></i> 清空</a>
      </span>
      <span class="pull-left">确认订单：</span>
    </div>
    <div class="order-detail-list">
      <ul class="list-unstyled">
        <?php foreach($foods as $item){?>
        <li>
          <div class="pull-right">
            <span class="menu-list-button reduce" onclick="order.reduce(<?php echo $item['id'];?>)"><i class="glyphicon glyphicon-minus-sign"></i></span>
            <span class="menu-list-num"><?php echo $cart[$item['id']]['total']?></span>
            <span class="menu-list-button add" onclick="order.add(<?php echo $item['id'];?>)"><i class="glyphicon glyphicon-plus-sign"></i></span>
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
  </div>
  <div class="order-detail myinfo">
    <?php if($ordertype==1){
    		include fmFunc_template_m($do.'/'.$ac.'/form/waimai');
    }elseif($ordertype==2) {
    		include fmFunc_template_m($do.'/'.$ac.'/form/tangshi');
    	}elseif($ordertype==3) {
    		include fmFunc_template_m($do.'/'.$ac.'/form/ziqu');
    	}?>
</div>
<div id="dtBox"></div>
<link type="text/css" rel="stylesheet" href="<?php echo $htmlsrc.$do.DIRECTORY_SEPARATOR.'_statics'.DIRECTORY_SEPARATOR.'DateTimePicker.css';?>">
<script type="text/javascript" src="<?php echo $htmlsrc.$do.DIRECTORY_SEPARATOR.'_statics'.DIRECTORY_SEPARATOR.'DateTimePicker.js';?>" /></script>

<script type="text/javascript">
     $("#dtBox").DateTimePicker(
       {
         dateFormat: "yyyy-MM-dd"
       }
    );
    $('.pcateimg').html('<?php echo $pcatetotal;?>');
    $('.priceimg').html('<?php echo $pricetotal;?>');
    $('.order-hd .total').html('<?php echo $pricetotal;?>');

$('.order-detail-list').delegate(".add", "click", function(){
  var a = $(this).parent().parent();
  a.find('.menu-list-num').html(function() {
    return parseInt($(this).html()) + 1;
  });
});
$('.order-detail-list').delegate(".reduce", "click", function(){
  var a = $(this).parent().parent();
  if(a.find('.menu-list-num').html() == 1 || a.find('.menu-list-num').html() < 0) {
    if(confirm("确定要删除吗？")) {
      a.remove();
    } else {
      return false;
    }
  }
  a.find('.menu-list-num').html(function() {
    return parseInt($(this).html()) - 1;
  });
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
      alert('请您先选择菜品。');
      return;
    }
    operation = operation ? operation : 'add';
    $.getJSON('<?php echo fm_murl($do,'updatecart','',array('pcate'=>$_GPC['pcate'],'ccate'=>$_GPC['ccate']));?>', {'foodsid' : foodsid,'op':operation}, function(s){
      if (s.message.status) {
        $('#foodsnum_'+foodsid).html(s.message.total);
        $('.pcateimg').html(s.message.pcatetotal);
        $('.priceimg').html(s.message.pricetotal);
        $('.order-hd .total').html(s.message.pricetotal);
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

function checkform(form) {
  if (!form['mobile'].value) {
    alert('请输入您的手机号码');
    return false;
  }
  else{
	var mflag = /^1\d{10}$/ .test(form['mobile'].value);
	if(!mflag){
	alert('请输入正确的手机号码');
    return false;
  }
    }

  // if (!form['time'].value) {
  //   alert('请输入送餐时间');
  //   return false;
  // }
/** if (!form['address'].value) {
    alert('请输入送餐地址');
    return false;
  }
**/
  if (!form['paytype'].value) {
    alert('请选择付款方式');
    return false;
  }

}
</script>
