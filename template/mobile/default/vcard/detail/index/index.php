<link rel="stylesheet" type="text/css" href="<?php echo $appsrc;?>jquery/reset.css"/>
<!-- <link rel="stylesheet" type="text/css" href="<?php echo $htmlsrc;?>/<?php echo $do;?>/<?php echo $ac;?>/<?php echo $op;?>/css/reset.css"/> -->
<!-- <link rel="stylesheet" type="text/css" href="<?php echo $htmlsrc;?>/<?php echo $do;?>/<?php echo $ac;?>/<?php echo $op;?>/css/fancybox.css"/> -->
<link rel="stylesheet" type="text/css" href="<?php echo $htmlsrc;?>/<?php echo $do;?>/<?php echo $ac;?>/<?php echo $op;?>/css/style.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo $fm453resource;?>components/jquery-fancybox/jquery.fancybox.min.css"/>

    <!-- <script type="text/javascript" src="js/jquery.min.js"></script> -->
    <script type="text/javascript" src="<?php echo $appsrc;?>jquery/jquery.easytabs.min.js"></script>
    <script type="text/javascript" src="<?php echo $appsrc;?>jquery/respond.min.js"></script>
    <script type="text/javascript" src="<?php echo $appsrc;?>jquery/jquery.easytabs.min.js"></script>   
	<script type="text/javascript" src="<?php echo $appsrc;?>jquery/jquery.adipoli.min.js"></script>
    <script type="text/javascript" src="<?php echo $fm453resource;?>components/jquery-fancybox/jquery.fancybox.min.js"></script>
    <!-- <script type="text/javascript" src="<?php echo $appsrc;?>jquery/jquery.isotope.min.js"></script> -->
    <script type="text/javascript" src="<?php echo $htmlsrc;?>/<?php echo $do;?>/<?php echo $ac;?>/<?php echo $op;?>/js/custom.js"></script>

        <!-- Container -->
        <div id="container">
        
            <!-- Top -->
			<div class="top"> 
            	<!-- Logo -->
            	<div id="logo">
                	<h2><?php echo $user['info']['nickname'];?></h2>
                    <h4>电子档案</h4>
                </div>
                <!-- /Logo -->

            </div>
            <!-- /Top -->
            
            <!-- Content -->
            <div id="content" >
            
                <!-- Profile -->
                <div id="profile"> 
                 	<!-- About section -->
                	<div class="about">
                    	<div class="photo-inner" style="width:212px;height:212px;padding:15px 0 0 15px;"><img src="<?php echo tomedia($user['settings']['thumb']);?>" height="180" width="180" style="width:180px;height:180px;" /></div>
                        <h1><?php echo $user['info']['nickname'];?></h1>
                        <h3><?php echo $user['settings']['company'];?></h3>
                        <p><?php echo $user['settings']['sign'];?></p>
                    </div>
                    <!-- /About section -->
                     
                    <!-- Personal info section -->
                	<ul class="personal-info">
						<li class="realname" style=""><label>姓名</label><span><?php echo $user['info']['realname'];?></span></li>
						<li class="gender"><label>性别</label><span><?php if($user['settings']['sex']==1){echo '女';}elseif($user['settings']['sex']==2){echo '男';}?></span></li>
                        <li class="birthday"><label>生日</label><span><?php if($user['settings']['birthday']){echo $user['settings']['birthday'];}?></span></li>
                        <li><label>地址</label><span><?php echo $user['settings']['now_address'];?></span></li>
                        <a href="tel:<?php echo $user['settings']['phone'];?>"><li><label>电话</label><span><?php echo $user['settings']['phone'];?></span></li></a>
                        <li class="mobile-only"><label>工作</label><span><?php echo $user['settings']['job'];?></span></li>
                    </ul>
                    <!-- /Personal info section -->
                </div>        
                <!-- /Profile --> 

                <!-- Menu -->
                <div class="menu">
                	<ul class="tabs">
                    	<li><a href="#profile" class="tab-profile">基础资料</a></li>
                    	<li><a href="#resume" class="tab-resume">详细介绍</a></li>
                    	<!-- <li><a href="#portfolio" class="tab-portfolio" style="display: none;">荣誉/案例</a></li> -->
                    	<li><a href="#contact" class="tab-contact">保存到手机</a></li>
                    </ul>
                </div>
                <!-- /Menu --> 
                <div  id="toapply">
                    <a href="<?php echo $reg_url;?>" class="" target="_blank">生成名片</a>
                </div>
                
                <!-- Resume -->
                <?php include fmFunc_template_m($do.'/'.$ac.'/'.$op.'/_public/resume');?>
                <!-- {template $appstyle.$do.'/'.$ac.'/'.$operation.'/_public/resume'} -->
                <!-- /Resume --> 
                                        
                <!-- Portfolio -->
                <?php include fmFunc_template_m($do.'/'.$ac.'/'.$op.'/_public/portfolio');?>
                <!-- {template $appstyle.$do.'/'.$ac.'/'.$operation.'/_public/portfolio'} -->
                <!-- /Portfolio -->   
                
                <!-- Contact -->
                <?php include fmFunc_template_m($do.'/'.$ac.'/'.$op.'/_public/contact');?>
                <!-- {template $appstyle.$do.'/'.$ac.'/'.$operation.'/_public/contact'} -->
                <!-- /contact -->  

            </div>
            <!-- /Content -->
            
            <!-- Footer -->
			<div class="footer">
            	<div class="copyright">Desiged by fm453</div>
            </div>
            <!-- /Footer --> 
            
</div>
<!-- /Container -->
