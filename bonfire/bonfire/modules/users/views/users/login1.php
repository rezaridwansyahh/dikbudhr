<div class="preloader" style="display: none;">
	 <div class="box">
	 <h2>Selamat Datang <small>di</small></h2>
	 <h1>Simpedas</h1>
	 <div class="loading"></div>
		 <img src="<?php echo img_path(); ?>logo.png" alt="Logo" width="70px">
	 </div>
 </div>
	<div class="login-container">
        <div class="login-bg"></div>
        <div class="login-form">
        	<center>
            <img src="<?php echo img_path(); ?>logo.png" alt="Logo" width="70px">
            </center>
            <h1>Simpedas</h1>
            
                           <div id="notification" class="information">
                <p>
                    <?php echo Template::message(); ?>
				   <?php
					   if (validation_errors()) :
				   ?>
						<?php echo validation_errors(); ?>
				   <?php endif; ?>
                </p>
              </div>
            <?php echo form_open(LOGIN_URL, array('autocomplete' => 'off')); ?>
                <div class="control">
                	<input type="text" name="login" class="inputbox" placeholder="Username">
                </div>
                <div class="control">
                	<input type="password" name="password" class="inputbox" placeholder="Password">
                </div>
                <div class="buttonset">
                	<button type="submit" class="button" name="log-me-in" style="background:#e14d24">Sign me in</button>
                </div>
            </form>
        
         <div class="link">
         		<a href="#"><i class="fa fa-download"></i> Panduan Sistem</a>
                <a href="#"><i class="fa fa-comments"></i> Tanya admin</a>
                <a href="#"><i class="fa fa-user"></i> Lupa Password</a>
                <a href="#"><i class="fa fa-video-camera"></i> Tentang Simpedas</a>
            </div>
        </div>
    </div>
