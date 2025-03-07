<?php

if (validation_errors()) :
?>
<div class='alert alert-block alert-error fade in'>
    <a class='close' data-dismiss='alert'>&times;</a>
    <h4 class='alert-heading'>
        <?php echo lang('peraturan_otk_errors_message'); ?>
    </h4>
    <?php echo validation_errors(); ?>
</div>
<?php
endif;

$id = isset($peraturan_otk->id_peraturan) ? $peraturan_otk->id_peraturan : '';

?>
<div class='admin-box box box-primary'>
    <div class="box-header">
        <h3>Tambah Jenis Satker</h3>
    </div>        
    <?php echo form_open($this->uri->uri_string(), ''); ?>
    <div class="box-body">
        <fieldset>
            

            <div class="control-group<?php echo form_error('no_peraturan') ? ' error' : ''; ?>">
                <?php echo form_label(lang('peraturan_otk_field_no_peraturan'), 'no_peraturan', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='no_peraturan' type='text' class="form-control" name='no_peraturan' maxlength='100' value="<?php echo set_value('no_peraturan', isset($peraturan_otk->no_peraturan) ? $peraturan_otk->no_peraturan : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('no_peraturan'); ?></span>
                </div>
            </div>
        </fieldset>
    </div>
    <div class="box-footer">
            <input type='submit' name='save' class='btn btn-primary' value="<?php echo lang('peraturan_otk_action_create'); ?>" />
            <?php echo lang('bf_or'); ?>
            <?php echo anchor(SITE_AREA . '/masters/peraturan_otk', lang('peraturan_otk_cancel'), 'class="btn btn-warning"'); ?>
            
    </div>
    <?php echo form_close(); ?>
</div>