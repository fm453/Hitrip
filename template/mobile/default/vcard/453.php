<?php include $this->template($appstyle.'common/header');?>

<?php
if($ac == 'detail'){
	if($op == 'index'){
		include fmFunc_template_m($do.'/'.$ac.'/'.$op.'/index');
	}
}else{
?>
<script src="<?php echo $appsrc;?>js/mui.min.js"></script>
<?php include $this->template($appstyle.'common/js_url');?>
<?php include $this->template($appstyle.'common/js_pic');?>
<?php include $this->template($appstyle.'common/js_array');?>
<?php include $this->template($appstyle.'common/js_form');?>

<?php }?>

<?php include $this->template($appstyle.'common/footer');?>
