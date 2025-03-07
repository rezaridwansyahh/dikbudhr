<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="<?php echo base_url(); ?>themes/admin/js/sweetalert.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/css/sweetalert.css">

<?php
	$site_open = $this->settings_lib->item('auth.allow_register');
?>
<div class="wrapper">
      <div class="container">
        <div class="row">
          <div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
          <center>
    			  <img class="img-responsive" src="<?php echo base_url(); ?>assets/images/tutwuri.png" width="80px"/>
            Didukung Oleh
            <img class="img-responsive" src="<?php echo base_url(); ?>assets/images/logo-bsre.png" width="120px"/>
  		   </center>
			<?php echo form_open(LOGIN_URL, array('autocomplete' => 'off')); ?>
            <div class="sign-form">
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
              <h3 class="first-child">Login Pegawai</h3>
              <hr>
              <form role="form">
                <label class="sr-only" for="email">Username/NIP</label>
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-user"></i></span>
                  <input type="text" class="form-control" id="text" name="login" placeholder="Username/NIP">
                </div>
                <br>
                <label class="sr-only" for="exampleInputPassword1">Password</label>
                <div class="input-group">
                  <span class="input-group-addon input-group-addon-password" id="input-group-addon-password"><i class="fa fa-eye"></i></span>
                  <input type="password" class="form-control" name="password" id="passwordanda" placeholder="Password">

                </div>
                <div class="checkbox">
                  <label>
                    <input type="checkbox"> Remember me
                  </label>
                </div>
                <button type="submit" name="log-me-in" class="btn btn-color">Masuk</button>
                <hr>
              </form>
				<?php  
				 if ($this->auth->is_logged_in()) :?>
					  <li><a href="<?php echo site_url('/logout') ?>"><?php e( lang('bf_action_logout')); ?></a></li>
					  <li><a href="<?php echo site_url(SITE_AREA) ?>"><span>Login area</span></a> </li>
				  <?php endif; ?>
              <!-- Lost password form -->
              <p>
                Lupas Password? <a href="#lost-password__form" data-toggle="collapse" aria-expanded="false" aria-controls="lost-password__form">Klik disini untuk mereset.</a>
              </p>
              <div class="collapse" id="lost-password__form">
                <p class="text-muted">
                  Masukan nip anda dibawah, akan dikirimkan link ke email untuk mereset password anda.
                </p>
				      <form class="forget-form" id="frm-forget" action="#" method="post">
                  <div class="form-group">
                    <input type="hidden" name="send" value="send">
                    <label class="sr-only" for="lost-password__email">NIP</label>
                    <input type="text" class="form-control" id="lost-password__email" name="email" placeholder="Enter NIP">
                  </div>
                  <input type="submit" name="send" id="btn_send_password" value="Kirim" class="btn btn-color"/>
                </form>
                
              </div> <!-- lost-password__form -->

            </div>
          </div>
        </div> <!-- / .row -->
      </div> <!-- / .container -->

    </div> <!-- / .wrapper -->
<script src="<?php echo base_url(); ?>themes/admin/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-show-password.js"></script>
<script> 
$('body').on('click','#btn_send_password',function () { 
    var json_url = "<?php echo base_url() ?>users/send_password";
    $.ajax({    
        type: "POST",
        url: json_url,
        data: $( "#frm-forget").serialize(),
        success: function(data){ 
            if(data == "Sukses"){
                swal("Perhatian!", "Link untuk mereset password sudah dikirimkan ke email, silahkan cek email anda", "success");
            }else{
                $(".linkreset").html(data);
                swal("Perhatian!", data, "error");
            }
           

        }});
     return false; 
});     
$('body').on('click','#register-btn',function () { 
    swal("Perhatian!", "Silahkan hubungi sekretariat", "error");

});
</script>
