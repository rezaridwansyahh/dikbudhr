 
	<div class="control-group <?php echo form_error('pangkat') ? 'error' : ''; ?>">
		<?php echo form_label('Pangkat', 'nip', array('class' => 'control-label') ); ?>
		<div class='controls'>
			<?php echo $datadetil['pangkat']; ?>
			<span class='help-inline'><?php echo form_error('pangkat'); ?></span>
		</div>
	</div>
	<div class="control-group <?php echo form_error('golongan') ? 'error' : ''; ?>">
		<?php echo form_label('Golongan', 'nip', array('class' => 'control-label') ); ?>
		<div class='controls'>
			<?php echo $datadetil['golongan']; ?>
			<span class='help-inline'><?php echo form_error('golongan'); ?></span>
		</div>
	</div>
	<div class="control-group <?php echo form_error('jabatan') ? 'error' : ''; ?>">
		<?php echo form_label('Jabatan', 'jabatan', array('class' => 'control-label') ); ?>
		<div class='controls'>
			<?php echo $datadetil['jabatan']; ?>
			<span class='help-inline'><?php echo form_error('jabatan'); ?></span>
		</div>
	</div>
	 