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
			 <input type='checkbox' name='np[addrule][status]' value='1' <?php if($needs_params['addrule']['status']==1){ ?> checked <?php } ?> /> 
		 </label>
		 <label class="control-label">队员填录规范</label>
	</div>
	<div class="col-xs-12 col-sm-6 col-md-9 col-lg-10">
		<textarea type="text" name="np[addrule][value]" class="form-control" ><?php echo $needs_params['addrule']['value']; ?></textarea>
	</div>
</div>