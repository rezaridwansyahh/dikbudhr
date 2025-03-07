<?php

if (validation_errors()) :
?>
<div class='alert alert-block alert-error fade in'>
    <a class='close' data-dismiss='alert'>&times;</a>
    <h4 class='alert-heading'>
        <?php echo lang('jenis_satker_errors_message'); ?>
    </h4>
    <?php echo validation_errors(); ?>
</div>
<?php
endif;

$id = isset($jenis_satker->id_jenis) ? $jenis_satker->id_jenis : '';

?>
<div class='admin-box box box-primary'>
    <div class="box-header">
        <h3>Tambah Jenis Satker</h3>
    </div>        
        <?php echo form_open($this->uri->uri_string(), ''); ?>
    <div class="box-body">
    <?php echo form_open($this->uri->uri_string(), 'class="form-horizontal"'); ?>
        <fieldset>
            

            <div class="control-group<?php echo form_error('nama_jenis_satker') ? ' error' : ''; ?>">
                <?php echo form_label(lang('jenis_satker_field_nama_jenis_satker'), 'nama_jenis_satker', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='nama_jenis_satker' class="form-control" type='text' name='nama_jenis_satker' maxlength='50' value="<?php echo set_value('nama_jenis_satker', isset($jenis_satker->nama_jenis_satker) ? $jenis_satker->nama_jenis_satker : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('nama_jenis_satker'); ?></span>
                </div>
            </div>
        </fieldset>
    </div>
    <div class="box-footer">
            <input type='submit' name='save' class='btn btn-primary' value="Save" />
            <?php echo lang('bf_or'); ?>
            <?php echo anchor(SITE_AREA . '/masters/jenis_satker', lang('jenis_satker_cancel'), 'class="btn btn-warning"'); ?>
            
    <?php echo form_close(); ?>
    </div>
</div>