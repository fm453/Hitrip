<?php include $this->template($appstyle.'common/header');?>
<script src="<?php echo $appsrc;?>js/mui.min.js"></script>
<?php
switch($ac)
{
	case 'index':
		include fmFunc_template_m($do.'/'.$ac.'/index');
		include $this->template($appstyle.'common/js_url');
		include $this->template($appstyle.'common/js_pic');
		break;
	case 'dianjia':
	case 'shop':
	case 'tangshi':
	case 'take':
	case 'waimai':
		if($op == 'index')
		{
			include fmFunc_template_m($do.'/'.$ac.'/mainpage');
		}elseif($op == 'load'){
			include fmFunc_template_m($do.'/'.$ac.'/load/index');
		}elseif($op == 'loadmore'){
			include fmFunc_template_m($do.'/'.$ac.'/loadmore/page');
		}
		include $this->template($appstyle.'common/js_url');
		include $this->template($appstyle.'common/js_pic');
		include $this->template($appstyle.'common/js_array');
		include $this->template($appstyle.'common/js_form');
		break;

	case 'myorder':
		if($op=='detail')
		{
			include fmFunc_template_m($do.'/'.$ac.'/index');
			include $this->template($appstyle.'common/js_url');
			include $this->template($appstyle.'common/js_pic');
		}else{
			if($op == 'index'){
				include fmFunc_template_m($do.'/'.$ac.'/mainpage');
			}elseif($op == 'load'){
				include fmFunc_template_m($do.'/'.$ac.'/load/index');
			}elseif($op == 'loadmore'){
				include fmFunc_template_m($do.'/'.$ac.'/loadmore/page');
			}
			include $this->template($appstyle.'common/js_url');
			include $this->template($appstyle.'common/js_pic');
			include $this->template($appstyle.'common/js_array');
			include $this->template($appstyle.'common/js_form');
		}
		break;

	case 'list':
		include fmFunc_template_m($do.'/'.$ac.'/index');
		break;

	case 'mycart':
		include fmFunc_template_m($do.'/'.$ac.'/index');
		include $this->template($appstyle.'common/js_url');
		include $this->template($appstyle.'common/js_pic');
		include $this->template($appstyle.'common/js_array');
		include $this->template($appstyle.'common/js_form');
		break;

	case 'detail':
		include fmFunc_template_m($do.'/'.$ac.'/index');
		break;

	case 'pay':
		include fmFunc_template_m($do.'/'.$ac.'/index');
		break;

	default:
		include fmFunc_template_m($do.'/'.$ac.'/index');
		break;
}
?>

<?php include $this->template($appstyle.'common/footer');?>
