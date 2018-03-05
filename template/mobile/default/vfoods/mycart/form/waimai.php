<link rel="stylesheet" type="text/css" href="<?php echo $appsrc;?>/css/mui.picker.min.css" />
<?php
//默认人数范围
$default_p_num = array(
  "1"=>"1人",
  "2"=>"2人",
  "3"=>"3人",
  "4"=>"4人",
  "5"=>"5人",
  "6"=>"6人",
  "7"=>"7人",
  "8"=>"8人",
  "0"=>"更多",
);
$p_num="[";
foreach($default_p_num as $i_key=>$i_title){
  $p_num .="{value:'".$i_key."',text:'".$i_title."'},";
}
$p_num = substr($p_num, 0,-1);  //截取字符,从开始位置至倒数第二位
$p_num .="]";

//默认支付方式
$default_paytype = array(
  "1"=>"在线支付",
);
$paytype="[";
foreach($default_paytype as $i_key=>$i_title){
  $paytype .="{value:'".$i_key."',text:'".$i_title."'},";
}
$paytype = substr($paytype, 0,-1);  //截取字符,从开始位置至倒数第二位
$paytype .="]";

//用餐选择组件
$time['birth_beginYear']= !empty($needs['birth_beginYear']) ? $needs['birth_beginYear'] : date('Y',strtotime('-60 years'));
$time['birth_endYear']= !empty($needs['birth_endYear']) ? $needs['birth_endYear'] : date('Y',strtotime('-8 years'));
$time_options='{'.'"type":"date"'. ',' .'"beginYear":'. $time['birth_beginYear'] .','. '"endYear":'.$time['birth_endYear'].'}';

//外卖区域
if($settings['vfoods']['waimai']['fanwei']){
  $fanwei = "[";
  foreach($settings['vfoods']['waimai']['fanwei'] as $i_key=>&$i_title){
    $i_title = trim($i_title);
    $fanwei .="{value:'".$i_key."',text:'".$i_title."'},";
  }
  $fanwei = substr($fanwei,0,-1);
  $fanwei .=']';
  $address = $fanwei;
}

?>
<form action="<?php echo $_W['siteurl']; ?>" method="post" onsubmit="" data-url="" id="form" data-type="waimai">
  <input type="hidden" name="token" value="<?php echo $_W['token'];?>" />

<div class="mui-input-group">
  <div class="mui-input-row">
    <label><span style="color:#f00;"> &nbsp; </span>称呼：</label>
    <input id='name' type="text" name="nickname" class="mui-input-clear" placeholder="<?php if($_FM['member']['info']['nickname']){ echo $_FM['member']['info']['nickname'];}else{ echo '请填写，以便我们与您联系';} ?>" value="<?php echo $profile['nickname'];?>" />
  </div>

  <div class="mui-input-row">
    <label><span style="color:#f00;"> * </span>手机：</label>
    <input id='mobile' type="text" name="mobile" class="mui-input-clear" placeholder="<?php if($_FM['member']['info']['mobile']){ echo $_FM['member']['info']['mobile'];}else{ echo '请填写，方便及时联系到您';} ?>" value="<?php echo $profile['mobile'];?>" />
  </div>

  <div class="mui-input-row">
    <label><span style="color:#f00;"> &nbsp; </span>人数：</label>
    <input id='people' type="text" name="people" class="mui-hidden" value="1" />
    <input id='peoplePicker' type="text" name="peoplePicker" class="mui-input-clear" placeholder="点击选择用餐人数" value="1人" />
  </div>
  <script>
    (function($, doc) {
      $.ready(function() {
        var id="people";
        var idPicker = new $.PopPicker();
        var pickData = <?php echo $p_num;?>;
        idPicker.setData(pickData);
        var showPickerButton = doc.getElementById(id+'Picker');
        var pickResult = doc.getElementById('Result_'+id);
        showPickerButton.addEventListener('tap', function(event) {
          idPicker.show(function(items) {
            showPickerButton.value = JSON.stringify(items[0].text);
            document.getElementById(id).setAttribute('value',items[0].value);
            //返回 false 可以阻止选择框的关闭
            //return false;
          });
        }, false);
      });
    })(mui, document);
  </script>

  <div class="mui-input-row">
    <label><span style="color:#f00;"> * </span>支付：</label>
    <input id='paytype' type="text" name="paytype" class="mui-hidden" value="1" />
    <input id='paytypePicker' type="text" name="paytypePicker" class="mui-input-clear" placeholder="点击支付方式" value="在线支付" />
  </div>
  <script>
    (function($, doc) {
      $.ready(function() {
        var id="paytype";
        var idPicker = new $.PopPicker();
        var pickData = <?php echo $paytype;?>;
        idPicker.setData(pickData);
        var showPickerButton = doc.getElementById(id+'Picker');
        var pickResult = doc.getElementById('Result_'+id);
        showPickerButton.addEventListener('tap', function(event) {
          idPicker.show(function(items) {
            showPickerButton.value = JSON.stringify(items[0].text);
            document.getElementById(id).setAttribute('value',items[0].value);
            //返回 false 可以阻止选择框的关闭
            //return false;
          });
        }, false);
      });
    })(mui, document);
  </script>

  <?php if($settings['vfoods']['waimai']['istimerange']){ ?>
  <div class="mui-input-row">
    <label><span style="color:#f00;"> &nbsp; </span>时间：</label>
    <input id='time0' data-id='time'  data-options='<?php echo $time_options;?>' type="button" class="mui-input-clear choosedate" style="padding:8px 0;font-size:16px;text-align: left;" value="点击选择您的用餐时间">
    <input type="hidden" id="time" name="time" value="" />
  </div>
  <!-- 初始化日期时间组件 -->
  <script>
    (function($) {
      var btns = $('.choosedate');
      btns.each(function(i, btn) {
        btn.addEventListener('tap', function() {
          var optionsJson = this.getAttribute('data-options') || '{}';
          var options = JSON.parse(optionsJson);//字符串转数组
          var id = this.getAttribute('data-id');
          var result = $('#result_'+id)[0];
          var picker = new $.DtPicker(options);
          var chosendate = '';
          var d =new Date();
          picker.show(function(rs) {
                /*
      * rs.value 拼合后的 value
      * rs.text 拼合后的 text
      * rs.y 年，可以通过 rs.y.vaue 和 rs.y.text 获取值和文本
      * rs.m 月，用法同年
      * rs.d 日，用法同年
      * rs.h 时，用法同年
      * rs.i 分（minutes 的第二个字母），用法同年
      */
            //result.innerText = rs.text;
            chosendate = Date.parse(new Date(rs.h.value+':'+rs.i.value));
            //$('#'+id+'0')[0].innerHTML = ''+rs.text+'';   //普通元素使用该方式
            $('#'+id+'0')[0].value = ''+rs.text+'';
            $('#'+id)[0].value = rs.text; //input使用该方式
            picker.dispose();
          });
        }, false);
      });
    })(mui);
  </script>
  <?php } ?>

  <?php if($settings['vfoods']['waimai']['isfanwei'] && is_array($settings['vfoods']['waimai']['fanwei'])){?>
  <div class="mui-input-row">
    <label><span style="color:#f00;"> * </span>地址：</label>
    <input id='address' type="text" name="address" class="mui-hidden" value="" />
    <input id='addressPicker' type="text" name="addressPicker" class="mui-input-clear" placeholder="点击选择配送房间/科室" value="" />
  </div>
  <script>
    (function($, doc) {
      $.ready(function() {
        var id="address";
        var idPicker = new $.PopPicker();
        var pickData = <?php echo $address;?>;
        idPicker.setData(pickData);
        var showPickerButton = doc.getElementById(id+'Picker');
        var pickResult = doc.getElementById('Result_'+id);
        showPickerButton.addEventListener('tap', function(event) {
          idPicker.show(function(items) {
            showPickerButton.value = JSON.stringify(items[0].text);
            document.getElementById(id).setAttribute('value',items[0].value);
            //返回 false 可以阻止选择框的关闭
            //return false;
          });
        }, false);
      });
    })(mui, document);
  </script>
  <?php }else{?>
  <div class="mui-input-row">
    <label><span style="color:#f00;"> &nbsp; </span>地址：</label>
    <input type="text" id="" class="mui-input-clear" name="address" value="<?php echo $profile['address'];?>" />
  </div>
  <?php }?>

  <div class="mui-input-row ">
    <label><span style="color:#f00;"> &nbsp; </span>备注：</label>
    <textarea id='other' name='other' class="mui-input-clear question" placeholder="您可在此备注忌口、口味等。" ></textarea>
  </div>
</div>

</form>
<!-- <script src="<?php echo $appsrc;?>/js/mui.picker.js"></script> -->
<script src="<?php echo $appsrc;?>/js/mui.poppicker.js"></script>
<script src="<?php echo $appsrc;?>/js/mui.picker.min.js"></script>
