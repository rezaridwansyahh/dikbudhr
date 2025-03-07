<body class="gray-bg">

    <div class="loginColumns animated fadeInDown">
        <div class="row">

            <div class="col-md-6" style="padding-top: 20%">
                
                <h2 class="font-bold">Welcome to DikhudDS</h2>

            </div>
            <div class="col-md-6">
                <div class="ibox-content">
                	<center><img src="<?php echo base_url(); ?>assets/images/logo.png" width="70px"/></center>
                	<br>
                	<h2><?php echo lang('us_login'); ?></h2>
					<?php echo Template::message(); ?>

					<?php
						if (validation_errors()) :
					?>
					<div class="row-fluid">
						<div class="span12">
							<div class="alert alert-error fade in">
							  <a data-dismiss="alert" class="close">&times;</a>
								<?php echo validation_errors(); ?>
							</div>
						</div>
					</div>
					<?php endif; ?>

                    <?php echo form_open(LOGIN_URL, array('autocomplete' => 'off')); ?>
                        <div class="form-group">
                        	<input class="form-control" type="text" name="login" id="login_value" value="<?php echo set_value('login'); ?>" tabindex="1" placeholder="<?php echo $this->settings_lib->item('auth.login_type') == 'both' ? lang('bf_username') .'/'. lang('bf_email') : ucwords($this->settings_lib->item('auth.login_type')) ?>" />
                        </div>
                        <div class="form-group">
                        	<input type="password" class="form-control" name="password" id="password" value="" tabindex="2" placeholder="<?php echo lang('bf_password'); ?>" />
                        </div>
                        <button type="submit" name="log-me-in" class="btn btn-primary block full-width m-b">Login</button>

                        <a href="<?php echo base_url(); ?>forgot_password">
                            <small>Forgot password?</small>
                        </a>

                        <p class="text-muted text-center">
                            <small>anda tidak punya akun?</small><br>
                            Silahkan hubungi administrator
                        </p>
                        
                    </form>
                     
                </div>
            </div>
        </div>
        <hr/>
        <div class="row">
            <div class="col-md-6">
                Copyright Kementerian Pendidikan dan Kebudayaan
            </div>
            <div class="col-md-6 text-right">
               <small>© 2019</small>
            </div>
        </div>
    </div>

</body>