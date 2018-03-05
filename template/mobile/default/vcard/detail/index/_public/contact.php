<div id="contact">
                	<div id="map" style="display:none;"></div>
                	<!-- Contact Info -->
                    <div class="contact-info">
                    <h3 class="main-heading"><span>联系信息</span></h3>
                	<ul>
                        <li><?php echo $user['info']['address'];?><br /><br /></li>
                        <li>电话: <?php echo $user['settings']['phone'];?></li>
                    </ul>
                    <p style="text-align: center;"><img src="<?php echo $qrcode2contact;?>" ></p>
                    <p style="text-align: center;color: #0095f6;font-weight: bold;">(长按/扫描)识别二维码，添加到通讯录</p>
                    </div>
                    <!-- /Contact Info -->
                    
                    <!-- Contact Form -->
                    
                    <!-- /Contact Form -->
                </div>