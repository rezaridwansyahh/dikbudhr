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
<div class='admin-box box box-primary'>
    <div class="box-header">
        <h3>Agama</h3>
    </div>        
        <?php echo form_open($this->uri->uri_string(), 'class="form-horizontal"'); ?>
           
    <div class="box-body">
            <fieldset>
                

                <div class="control-group<?php echo form_error('NAMA') ? ' error' : ''; ?>">
                    <?php echo form_label(lang('agama_field_NAMA') . lang('bf_form_label_required'), 'NAMA', array('class' => 'control-label')); ?>
                    <div class='controls'>
                        <input id='NAMA' type='text' class="form-control" required='required' name='NAMA' maxlength='20' value="<?php echo set_value('NAMA', isset($agama->NAMA) ? $agama->NAMA : ''); ?>" />
                        <span class='help-inline'><?php echo form_error('NAMA'); ?></span>
                    </div>
                </div>
            </fieldset>
             </div>
            <div class="box-footer">
            <fieldset class='form-actions'>
                <input type='submit' name='save' class='btn btn-primary' value="<?php echo lang('agama_action_edit'); ?>" />
                <?php echo lang('bf_or'); ?>
                <?php echo anchor(SITE_AREA . '/masters/agama', lang('agama_cancel'), 'class="btn btn-warning"'); ?>
                
                <?php if ($this->auth->has_permission('Agama.Masters.Delete')) : ?>
                    <?php echo lang('bf_or'); ?>
                    <button type='submit' name='delete' formnovalidate class='btn btn-danger' id='delete-me' onclick="return confirm('<?php e(js_escape(lang('agama_delete_confirm'))); ?>');">
                        <span class='icon-trash icon-white'></span>&nbsp;<?php echo lang('agama_delete_record'); ?>
                    </button>
                <?php endif; ?>
            </fieldset>
            </div>
        <?php echo form_close(); ?>
   
</div>