<?php

if (validation_errors()) :
?>
<div class='alert alert-block alert-error fade in'>
    <a class='close' data-dismiss='alert'>&times;</a>
    <h4 class='alert-heading'>
        <?php echo lang('ref_jabatan_errors_message'); ?>
    </h4>
    <?php echo validation_errors(); ?>
</div>
<?php
endif;

$id = isset($ref_jabatan->ID) ? $ref_jabatan->ID : '';

?>
<div class='admin-box box box-primary'>
    <div class="box-header">
        <h3>Edit Jabatan</h3>
    </div>        
        <?php echo form_open($this->uri->uri_string(), ''); ?>
           
    <div class="box-body">
            <div class="control-group<?php echo form_error('JENIS_JABATAN') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Jenis Jabatan", 'JENIS_JABATAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select name="JENIS_JABATAN" id="JENIS_JABATAN" class="form-control select2">
						<option value="">-- Silahkan Pilih --</option>
						<option value="Jabatan Fungsional Umum" <?php if(isset($ref_jabatan->JENIS_JABATAN))  echo  (trim($ref_jabatan->JENIS_JABATAN)=="Jabatan Fungsional Umum") ? "selected" : ""; ?>>Fungsional Umum</option>
						<option value="Jabatan Fungsional Tertentu" <?php if(isset($ref_jabatan->JENIS_JABATAN))  echo  (trim($ref_jabatan->JENIS_JABATAN) == "Jabatan Fungsional Tertentu") ? "selected" : ""; ?>>Fungsional Tertentu</option>
						<option value="Struktural" <?php if(isset($ref_jabatan->JENIS_JABATAN))  echo  ($ref_jabatan->JENIS_JABATAN=="Struktural") ? "selected" : ""; ?>>Struktural</option>
					</select>
                    <span class='help-inline'><?php echo form_error('JENIS_JABATAN'); ?></span>
                </div>
            </div>
			<div class="control-group<?php echo form_error('NAMA_JABATAN') ? ' error' : ''; ?> col-sm-12">
				<?php echo form_label("Nama Jabatan". lang('bf_form_label_required'), 'NAMA_JABATAN', array('class' => 'control-label')); ?>
				<div class='controls'>
					<input id='NAMA_JABATAN' type='text' class="form-control" required='required' name='NAMA_JABATAN' maxlength='100' value="<?php echo set_value('NAMA_JABATAN', isset($ref_jabatan->NAMA_JABATAN) ? $ref_jabatan->NAMA_JABATAN : ''); ?>" />
					<span class='help-inline'><?php echo form_error('NAMA_JABATAN'); ?></span>
				</div>
			</div>
			<div class="control-group<?php echo form_error('BUP_PENSIUN') ? ' error' : ''; ?> col-sm-12">
				<?php echo form_label("BUP PENSIUN". lang('bf_form_label_required'), 'BUP PENSIUN', array('class' => 'control-label')); ?>
				<div class='controls'>
					<input id='NAMA_JABATAN' type='text' class="form-control" required='required' name='PENSIUN' maxlength='100' value="<?php echo set_value('PENSIUN', isset($ref_jabatan->PENSIUN) ? $ref_jabatan->PENSIUN : ''); ?>" />
					<span class='help-inline'><?php echo form_error('BUP PENSIUN'); ?></span>
				</div>
			</div>
            <div class="control-group<?php echo form_error('BUP_PENSIUN') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Rp. TUNJANGAN", 'BUP PENSIUN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='TUNJANGAN' type='text' class="form-control" name='TUNJANGAN' maxlength='100' value="<?php echo set_value('TUNJANGAN', isset($ref_jabatan->TUNJANGAN) ? $ref_jabatan->TUNJANGAN : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('TUNJANGAN'); ?></span>
                </div>
            </div>
        </div>
            <div class="box-footer">
            <fieldset class='form-actions'>
                <input type='submit' name='save' class='btn btn-primary' value="Simpan" />
                <?php echo lang('bf_or'); ?>
                <?php echo anchor(SITE_AREA . '/masters/ref_jabatan', lang('ref_jabatan_cancel'), 'class="btn btn-warning"'); ?>
        
            </fieldset>
            </div>
        <?php echo form_close(); ?>
   
</div>