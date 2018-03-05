<?php
defined('IN_IA') or exit('Access Denied');
?>

<div class="form-group">
	<div class="col-xs-12 col-sm-6 col-md-3 col-lg-2">
		 <label class='control-label'>
			 <input type='checkbox' name='np[kefuphone][status]' value='1' <?php if($needs_params['kefuphone']['status']==1){ ?> checked <?php } ?> /> 
		 </label>
		 <label class="control-label">客服电话</label>
	</div>
	<div class="col-xs-12 col-sm-6 col-md-9 col-lg-10">
		<input type="text" name="np[kefuphone][value]" class="form-control" value="<?php echo $needs_params['kefuphone']['value']; ?>" />
	</div>
</div>

<div class="form-group">
	<div class="col-xs-12 col-sm-6 col-md-3 col-lg-2">
		 <label class='control-label'>
			 <input type='checkbox' name='np[pagetitle][status]' value='1' <?php if($needs_params['pagetitle']['status']==1){ ?> checked <?php } ?> /> 
		 </label>
		 <label class="control-label">自定义页面标题</label>
	</div>
	<div class="col-xs-12 col-sm-6 col-md-9 col-lg-10">
		<input type="text" name="np[pagetitle][value]" class="form-control" value="<?php echo $needs_params['pagetitle']['value']; ?>" />
	</div>
</div>

<div class="form-group">
	<div class="col-xs-12 col-sm-6 col-md-3 col-lg-2">
		 <label class='control-label'>
			 <input type='checkbox' name='np[tips][status]' value='1' <?php if($needs_params['tips']['status']==1){ ?> checked <?php } ?> /> 
		 </label>
		 <label class="control-label">温馨提示</label>
	</div>
	<div class="col-xs-12 col-sm-6 col-md-9 col-lg-10">
		<textarea type="text" name="np[tips][value]" class="form-control" ><?php echo $needs_params['tips']['value']; ?></textarea>
	</div>
</div>

<div class="form-group">
	<div class="col-xs-12 col-sm-6 col-md-3 col-lg-2">
		 <label class='control-label'>
			 <input type='checkbox' name='np[zhuohaotips][status]' value='1' <?php if($needs_params['zhuohaotips']['status']==1){ ?> checked <?php } ?> /> 
		 </label>
		 <label class="control-label">桌号填写提示</label>
	</div>
	<div class="col-xs-12 col-sm-6 col-md-9 col-lg-10">
		<textarea type="text" name="np[zhuohaotips][value]" class="form-control" ><?php echo $needs_params['zhuohaotips']['value']; ?></textarea>
	</div>
</div>

<div class="form-group">
	<div class="col-xs-12 col-sm-6 col-md-3 col-lg-2">
		 <label class='control-label'>
			 <input type='checkbox' name='np[dropdown1][status]' value='1' <?php if($needs_params['dropdown1']['status']==1){ ?> checked <?php } ?> /> 
		 </label>
		 <label class="control-label">下拉选项框一</label>
	</div>
	<div class="col-xs-12 col-sm-6 col-md-9 col-lg-10">
		<textarea type="text" name="np[dropdown1][value]" class="form-control" ><?php echo $needs_params['dropdown1']['value']; ?></textarea>
		<span class="help-block">填写方式：每行填写一个选项；</span>
	</div>
</div>