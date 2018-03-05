<?php include $this->template($appstyle.'common/header');?>
<?php
if($ac == 'order'){
	include fmFunc_template_m('appweb/'.$_do.'/'.$ac.'/'.$op);
}elseif($ac == 'shop'){
	include fmFunc_template_m('appweb/'.$_do.'/'.$ac.'/'.$op);
}
?>

<?php include $this->template($appstyle.'common/footer_noshare');?>
