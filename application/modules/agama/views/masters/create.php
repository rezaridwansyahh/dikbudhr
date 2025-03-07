<?php

if (validation_errors()) :
?>
<div class='alert alert-block alert-error fade in'>
    <a class='close' data-dismiss='alert'>&times;</a>
    <h4 class='alert-heading'>
        <?php echo lang('agama_errors_message'); ?>
    </h4>
    <?php echo validation_errors(); ?>
</div>
<?php
endif;

$id = isset($agama->ID) ? $agama->ID : '';

?>
<div class='admin-box'>
    <h3>Agama</h3>
    <?php echo form_open($this->uri->uri_string(), 'class="form-horizontal"'); ?>
        <fieldset>
            

            <div class="control-group<?php echo form_error('NAMA') ? ' error' : ''; ?>">
                <?php echo form_label(lang('agama_field_NAMA') . lang('bf_form_label_required'), 'NAMA', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='NAMA' type='text' required='required' name='NAMA' maxlength='20' value="<?php echo set_value('NAMA', isset($agama->NAMA) ? $agama->NAMA : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('NAMA'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('NCSISTIME') ? ' error' : ''; ?>">
                <?php echo form_label(lang('agama_field_NCSISTIME'), 'NCSISTIME', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='NCSISTIME' type='text' name='NCSISTIME' maxlength='30' value="<?php echo set_value('NCSISTIME', isset($agama->NCSISTIME) ? $agama->NCSISTIME : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('NCSISTIME'); ?></span>
                </div>
            </div>
        </fieldset>
        <fieldset class='form-actions'>
            <input type='submit' name='save' class='btn btn-primary' value="<?php echo lang('agama_action_create'); ?>" />
            <?php echo lang('bf_or'); ?>
            <?php echo anchor(SITE_AREA . '/masters/agama', lang('agama_cancel'), 'class="btn btn-warning"'); ?>
            
        </fieldset>
    <?php echo form_close(); ?>
</div>