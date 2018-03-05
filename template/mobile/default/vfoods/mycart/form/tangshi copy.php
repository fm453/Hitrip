<div class="order-detail-hd">
  <span class="pull-left">订单信息(食堂就餐)</span>
</div>
<form action="" method="post" onsubmit="return checkform(this); return false;">
  <table class="table table1">
    <tr>
      <th><label for="" style="font-weight: 200;">称呼</label></th>
      <td><input type="text" id="" class="form-control1" name="nickname" value="<?php echo $profile['nickname'];?>" /></td>
    </tr>
    <tr>
      <th><label for="" style="font-weight: 200;">手机</label></th>
      <td><input type="text" id="" class="form-control1" name="mobile" value="<?php echo $profile['mobile'];?>" /></td>
      <td>*</td>
    </tr>
    <tr style="display:none;">
      <th><label for="" style="font-weight: 200;">桌号</label></th>
      <td><input type="text" id="" class="form-control1" name="desknum" value=""/></td>
      <?php if($settings['vfoods']['tangshi']['isdesknum']){?>
      <td>*</td>
      <?php }?>
    </tr>
    <tr>
      <th><label for="" style="font-weight: 200;">人数</label></th>
      <td>
        <select name="guests" class="form-control1" >
          <option value="">请选择就餐人数</option>
          <?php for($o_i=1;$o_i<=10;$o_i++){?>
          <option value="<?php echo $o_i;?>"><?php echo $o_i;?>位</option>
          <?php }?>
      </td>
      <?php if($settings['vfoods']['tangshi']['isguestnum']){?>
      <td>*</td>
      <?php }?>
    </tr>
    <tr style="display:none;">
      <th><label for="" style="font-weight: 200;">时间</label></th>
      <td class="datetime"><input type="text" class="form-control1" data-field="time" name="time" placeholder="请选择用餐时间" readonly /></td>
      <?php if($settings['vfoods']['tangshi']['istimerange']){?>
      <td>*</td>
      <?php }?>
    </tr>
    <tr>
      <th><label for="" style="font-weight: 200;">支付</label></th>
      <td>
        <select name="paytype" class="form-control1" >
          <option value="1">在线支付</option>
          <?php if($settings['vfoods']['basic']['isxianfu']){?>
          <option value="2">当面付款</option>
          <?php }?>
        </select>
      </td>
      <td>*</td>
    </tr>
    <tr class="beizhutr">
      <th><label for="" style="font-weight: 200;">备注</label></th>
      <td><textarea id="" class="form-control1" name="other" placeholder="您可在此备注忌口、口味等" ></textarea></td>
    </tr>
  </table>
  <span style="text-align: center;font-weight: 200;">注：带*号的为必填项。</span>
  <input type="hidden" name="token" value="<?php echo $_W['token'];?>" />
  <div class="navbar1 navbar2">
    <div class="nav3">
      <a class="btn btn2 btn-default btn-sm" href="<?php echo fm_murl($do,'list','index',array('pcate'=>$_GPC['pcate'],'ccate'=>$_GPC['ccate']));?>"><i class="glyphicon glyphicon-chevron-left"></i>返回</a>
      <input type="submit" value="<?php if(!($pricetotal < $pcate2[0]['sendprice'])){?>去结算<?php }else{?>差￥<?php echo $between;?>起售<?php }?>" name="submit" class="btn btn-success btn-sm">
      <span class=""><i class="icon-shopping-cart"></i><b class="img-circle pcateimg"></b>份￥<b class="priceimg"></b></span>
    </div>
  </div>
</form>
