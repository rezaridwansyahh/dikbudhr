<div class="preloader" style="display: none;">
	 <div class="box">
	 <h2>Selamat Datang <small>di</small></h2>
	 <h1>JOGJAPLAN</h1>
	 <div class="loading"></div>
		 <img src="./Jogjaplan_files/jogja-logo.png" alt="" height="80">
	 </div>
 </div>
	<div class="login-container">
        <div class="login-bg"></div>
        <div class="login-form">
            <h1>SIMPEDAS</h1>
                           <div id="notification" class="information">
                <p>
                    <?php echo Template::message(); ?>
			 <?php
				 if (validation_errors()) :
			 ?>
			 <div class="row-fluid">
				 <div class="span12">
					 <div class="alert alert-error fade in">
						 <?php echo validation_errors(); ?>
					 </div>
				 </div>
			 </div>
			 <?php endif; ?>
                </p>
              </div>
                        <form id="loginform" action="http://www.jogjaplan.com/2018/login/process" method="post">
                <input type="hidden" name="guest" value="0">
                <div class="control">
                    <input type="text" name="username" class="inputbox" placeholder="Username..">
                </div>
                <div class="control">
                    <input type="password" name="password" class="inputbox" placeholder="Password..">
                </div>
                <div class="buttonset">
                    <button type="submit" class="button" style="background:#e14d24">Login</button>
                </div>
            </form>
         
			<img src="<?php echo img_path(); ?>logo.png" alt="Logo" width="70px">
             
		</center> 
		<div class="divlogin">
            <?php echo form_open(LOGIN_URL, array('autocomplete' => 'off')); ?>
                <input type="text" name="login" class="username" placeholder="Username">
                <input type="password" name="password" class="password" placeholder="Password">
                <button type="submit" name="log-me-in">Sign me in</button>
                <div class="error"><span>+</span></div>
            </form>
            <div class="connect">
				 <?php if ( $site_open ) : ?>
					 <?php echo anchor(REGISTER_URL, lang('us_sign_up')); ?>
				 <?php endif; ?>
		 			<a href="<?php echo base_url(); ?>forgot_password" class="show-modal" tooltip="Reset Password">Lupa Password ? </a>
				 <?php  
				 if ($this->auth->is_logged_in()) :?>
			  
					  <li <?php echo check_method('profile'); ?>><a href="<?php echo site_url('/admin/settings/users/edit'); ?>"> <?php e(lang('bf_user_settings')); ?> </a></li>
					  <li><a href="<?php echo site_url('/logout') ?>"><?php e( lang('bf_action_logout')); ?></a></li>
					  <li><a href="<?php echo site_url(SITE_AREA) ?>"><span>Login area</span></a> </li>
				  <?php endif; ?>
	 			
            </div>
        </div>
         
			<br>
        <br>
        
            <p>2017 © Pusat Penelitian Metlurgi - LIPI</p>
        </div>
		<br>
        <!-- Javascript -->
</div>
