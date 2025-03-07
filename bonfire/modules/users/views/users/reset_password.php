	<div class="page-header">
		<h1>Reset Password Anda</h1>
	</div>

	<div class="alert alert-danger fade in">
		<h4 class="alert-heading">Masukan Password Baru anda, pada form dibawah</h4>
	</div>


<?php if (validation_errors()) : ?>
	<div class="alert alert-error fade in">
		<?php echo validation_errors(); ?>
	</div>
<?php endif; ?>

<div class="row-fluid">
	<div class="span12">

<?php echo form_open($this->uri->uri_string(), 'class="form-horizontal"'); ?>

	<input type="hidden" name="user_id" value="<?php echo $user->id ?>" />

	<div class="control-form <?php echo iif( form_error('password') , 'error') ;?>">
		<label class="control-label" for="password"><?php echo lang('bf_password'); ?></label>
		<div class="controls">
			<input class="span6 form-control" type="password" name="password" id="password" value="" placeholder="" />
			<p class="help-block"><?php echo lang('us_password_mins'); ?></p>
		</div>
	</div>

	<div class="control-form <?php echo iif( form_error('pass_confirm') , 'error') ;?>">
		<label class="control-label" for="pass_confirm"><?php echo lang('bf_password_confirm'); ?></label>
		<div class="controls">
			<input class="span6 form-control" type="password" name="pass_confirm" id="pass_confirm" value="" placeholder="" />
		</div>
	</div>

	<div class="control-form">
		<br>
		<div class="controls">
			<input class="btn btn-primary" type="submit" name="set_password" id="submit" value="Simpan Password Baru"  />
		</div>
	</div>

<?php echo form_close(); ?>

	</div>
</div>
