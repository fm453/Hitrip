<?php
defined('IN_IA') or exit('Access Denied');
?>

<div class="form-group">
	<div class="col-xs-12 col-sm-6 col-md-3 col-lg-1">
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
	<div class="col-xs-12 col-sm-6 col-md-3 col-lg-1">
		 <label class='control-label'>
			 <input type='checkbox' name='np[addrule][status]' value='1' <?php if($needs_params['addrule']['status']==1){ ?> checked <?php } ?> />
		 </label>
		 <label class="control-label">队员填录规范</label>
	</div>
	<div class="col-xs-12 col-sm-6 col-md-9 col-lg-10">
		<textarea type="text" name="np[addrule][value]" class="form-control" ><?php echo $needs_params['addrule']['value']; ?></textarea>
	</div>
</div>

<div class="form-group">
	<div class="col-xs-12 col-sm-6 col-md-3 col-lg-1">
		 <label class='control-label'>
			 <input type='checkbox' name='np[whyIdcard][status]' value='1' <?php if($needs_params['whyIdcard']['status']==1){ ?> checked <?php } ?> />
		 </label>
		 <label class="control-label">身份证信息填录原因</label>
	</div>
	<div class="col-xs-12 col-sm-6 col-md-9 col-lg-10">
		<textarea type="text" name="np[whyIdcard][value]" class="form-control" ><?php echo $needs_params['whyIdcard']['value']; ?></textarea>
	</div>
</div>

<div class="form-group">
	<div class="col-xs-12 col-sm-6 col-md-3 col-lg-1">
		 <label class='control-label'>
			 <input type='checkbox' name='np[mustknow][status]' value='1' <?php if($needs_params['mustknow']['status']==1){ ?> checked <?php } ?> />
		 </label>
		 <label class="control-label">参赛须知</label>
	</div>
	<div class="col-xs-12 col-sm-6 col-md-9 col-lg-10">
		<textarea type="text" name="np[mustknow][value]" class="form-control" ><?php echo $needs_params['mustknow']['value']; ?></textarea>
	</div>
</div>

<div class="form-group">
	<div class="col-xs-12 col-sm-6 col-md-3 col-lg-1">
		 <label class='control-label'>
			 <input type='checkbox' name='np[shengming][status]' value='1' <?php if($needs_params['shengming']['status']==1){ ?> checked <?php } ?> />
		 </label>
		 <label class="control-label">参赛声明</label>
	</div>
	<div class="col-xs-12 col-sm-6 col-md-9 col-lg-10">
		<textarea type="text" name="np[shengming][value]" class="form-control" ><?php echo $needs_params['shengming']['value']; ?></textarea>
	</div>
</div>