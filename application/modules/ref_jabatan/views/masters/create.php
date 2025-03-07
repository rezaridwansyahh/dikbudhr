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
        <h3>Tambah Jabatan</h3>
    </div>        
        <?php echo form_open($this->uri->uri_string(), ''); ?>
           
    <div class="box-body">
            <div class="control-group<?php echo form_error('Jenis_Jabatan') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Jenis Jabatan", 'Jenis_Jabatan', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select name="Jenis_Jabatan" id="Jenis_Jabatan" class="form-control select2">
						<option value="">-- Silahkan Pilih --</option>
						<option value="Jabatan Fungsional Umum" <?php if(isset($ref_jabatan->Jenis_Jabatan))  echo  (trim($ref_jabatan->Jenis_Jabatan)=="Jabatan Fungsional Umum") ? "selected" : ""; ?>>Fungsional Umum</option>
						<option value="Jabatan Fungsional Tertentu" <?php if(isset($ref_jabatan->Jenis_Jabatan))  echo  (trim($ref_jabatan->Jenis_Jabatan) == "Jabatan Fungsional Tertentu") ? "selected" : ""; ?>>Fungsional Tertentu</option>
						<option value="Struktural" <?php if(isset($ref_jabatan->Jenis_Jabatan))  echo  ($ref_jabatan->Jenis_Jabatan=="Struktural") ? "selected" : ""; ?>>Struktural</option>
					</select>
                    <span class='help-inline'><?php echo form_error('Jenis_Jabatan'); ?></span>
                </div>
            </div>
                <div class="control-group<?php echo form_error('Nama_Jabatan') ? ' error' : ''; ?> col-sm-12">
                    <?php echo form_label("Nama Jabatan". lang('bf_form_label_required'), 'Nama_Jabatan', array('class' => 'control-label')); ?>
                    <div class='controls'>
                        <input id='Nama_Jabatan' type='text' class="form-control" required='required' name='Nama_Jabatan' maxlength='100' value="<?php echo set_value('Nama_Jabatan', isset($ref_jabatan->Nama_Jabatan) ? $ref_jabatan->Nama_Jabatan : ''); ?>" />
                        <span class='help-inline'><?php echo form_error('Nama_Jabatan'); ?></span>
                    </div>
                </div>
        </div>
            <div class="box-footer">
                <input type='submit' name='save' class='btn btn-primary' value="Simpan" />
                <?php echo lang('bf_or'); ?>
                <?php echo anchor(SITE_AREA . '/masters/ref_jabatan', lang('ref_jabatan_cancel'), 'class="btn btn-warning"'); ?>
        
            </div>
        <?php echo form_close(); ?>
   
</div>