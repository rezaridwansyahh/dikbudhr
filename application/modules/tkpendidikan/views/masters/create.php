<?php

if (validation_errors()) :
?>
<div class='alert alert-block alert-error fade in'>
    <a class='close' data-dismiss='alert'>&times;</a>
    <h4 class='alert-heading'>
        <?php echo lang('tkpendidikan_errors_message'); ?>
    </h4>
    <?php echo validation_errors(); ?>
</div>
<?php
endif;

$id = isset($tkpendidikan->ID) ? $tkpendidikan->ID : '';

?>
<div class='admin-box box box-primary'>
    <div class="box-header">
        <h3>Tambah Tingkat Pendidikan</h3>
    </div>      
    <?php echo form_open($this->uri->uri_string(), ''); ?>
            
		 <div class="box-body">
            <div class="control-group<?php echo form_error('GOLONGAN_ID') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label(lang('tkpendidikan_field_GOLONGAN_ID'), 'GOLONGAN_ID', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='GOLONGAN_ID' type='text' class="form-control" name='GOLONGAN_ID' maxlength='255' value="<?php echo set_value('GOLONGAN_ID', isset($tkpendidikan->GOLONGAN_ID) ? $tkpendidikan->GOLONGAN_ID : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('GOLONGAN_ID'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('NAMA') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label(lang('tkpendidikan_field_NAMA'), 'NAMA', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='NAMA' type='text' class="form-control" name='NAMA' maxlength='255' value="<?php echo set_value('NAMA', isset($tkpendidikan->NAMA) ? $tkpendidikan->NAMA : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('NAMA'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('GOLONGAN_AWAL_ID') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label(lang('tkpendidikan_field_GOLONGAN_AWAL_ID'), 'GOLONGAN_AWAL_ID', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='GOLONGAN_AWAL_ID' type='text' class="form-control" name='GOLONGAN_AWAL_ID' maxlength='255' value="<?php echo set_value('GOLONGAN_AWAL_ID', isset($tkpendidikan->GOLONGAN_AWAL_ID) ? $tkpendidikan->GOLONGAN_AWAL_ID : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('GOLONGAN_AWAL_ID'); ?></span>
                </div>
            </div>
        </div>
        <div class="box-footer">
            <input type='submit' name='save' class='btn btn-primary' value="Simpan" />
            <?php echo lang('bf_or'); ?>
            <?php echo anchor(SITE_AREA . '/masters/tkpendidikan', lang('tkpendidikan_cancel'), 'class="btn btn-warning"'); ?>
            
        </div>
    <?php echo form_close(); ?>
</div>