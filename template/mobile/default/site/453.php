<?php include $this->template($appstyle.'common/header');?>
<script src="<?php echo $appsrc;?>js/mui.min.js"></script>

<?php
if($ac == 'index'){
	if($op == 'index'){
		include fmFunc_template_m($do.'/'.$ac.'/mainpage');
	}
	if($op == 'load'){
		include fmFunc_template_m($do.'/'.$ac.'/load/index');
	}
	if($op == 'loadmore'){
		include fmFunc_template_m($do.'/'.$ac.'/loadmore/page');
	}
}

?>

<?php include $this->template($appstyle.'common/js_url');?>
<?php include $this->template($appstyle.'common/js_pic');?>
<?php include $this->template($appstyle.'common/js_array');?>
<?php include $this->template($appstyle.'common/js_form');?>
<?php include $this->template($appstyle.'common/footer');?>
