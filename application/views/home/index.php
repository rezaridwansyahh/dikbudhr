  
<div class="row-fluid">
	<center>
	 <br> <br> <br>
	 <div class="span11">
	 	<center><h2>Sistem Dupak Elektronik</h2></center>
	 </div>
	<?php if (isset($current_user->email)) : ?>
		<center>
			<br> <br><br>
		Anda Sudah login 
		<br>
		<a href="<?php echo site_url(SITE_AREA) ?>" class="btn btn-large btn-success">Go to the Login area</a>
		</center>
	<?php else :?>
		<a href="<?php echo site_url(LOGIN_URL); ?>"> 
		<div class="span11">
			<center>
			<img src="<?php echo base_url(); ?>assets/images/user.png" width="160px"/>
			<br>Administrator<center>
		</div>
		</a>
		<a href="<?php echo site_url(LOGIN_URL); ?>"> 
	
		<div class="span3">
			<center>
			<img src="<?php echo base_url(); ?>assets/images/user.png" width="160px"/>
			<br>Pengendali Pusat<center>
		</div>
		</a>
		<a href="<?php echo site_url(LOGIN_URL); ?>"> 
	
		<div class="span3">
			<center>
			<img src="<?php echo base_url(); ?>assets/images/user.png" width="160px"/>
			<br>PJB<center>
		</div>
		</a>
		<a href="<?php echo site_url(LOGIN_URL); ?>"> 
	
		<div class="span3">
			<center>
			<img src="<?php echo base_url(); ?>assets/images/user.png" width="160px"/>
			<br>Verifikator<center>
		</div>
		</a>
		<a href="<?php echo site_url(LOGIN_URL); ?>"> 
	
		<div class="span3">
			<center>
			<img src="<?php echo base_url(); ?>assets/images/user.png" width="160px"/>
			<br>Penilai<center>
		</div>
		</a>
		<a href="<?php echo site_url(LOGIN_URL); ?>"> 
	
		<div class="span3">
			<center>
			<img src="<?php echo base_url(); ?>assets/images/user.png" width="160px"/>
			<br>Pengendali Unit<center>
		</div>
		</a>
		<a href="<?php echo site_url(LOGIN_URL); ?>"> 
	
		<div class="span3">
			<center>
			<img src="<?php echo base_url(); ?>assets/images/user.png" width="160px"/>
			<br>Kepala<center>
		</div>
		</a>
		<a href="<?php echo site_url(LOGIN_URL); ?>"> 
	
		<div class="span3">
			<center>
			<img src="<?php echo base_url(); ?>assets/images/user.png" width="160px"/>
			<br>Atasan Langsung<center>
		</div>
		</a>
		<a href="<?php echo site_url(LOGIN_URL); ?>"> 
	
		<div class="span3">
			<center>
			<img src="<?php echo base_url(); ?>assets/images/user.png" width="160px"/>
			<br>PTP<center>
		</div>
		</a>
	<?php endif;?>
	</center> 
</div> 

 