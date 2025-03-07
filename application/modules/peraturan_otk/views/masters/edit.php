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
        <h3>Edit Data</h3>
    </div>        
        <?php echo form_open($this->uri->uri_string(), ''); ?>
           <input id='id_peraturan' type='hidden' class="form-control" required='required' name='id_peraturan' maxlength='100' value="<?php echo set_value('id_peraturan', isset($peraturan_otk->id_peraturan) ? $peraturan_otk->id_peraturan : ''); ?>" />
    <div class="box-body">
        <fieldset>
            

            <div class="control-group<?php echo form_error('no_peraturan') ? ' error' : ''; ?>">
                <?php echo form_label(lang('peraturan_otk_field_no_peraturan'), 'no_peraturan', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='no_peraturan' class="form-control" type='text' name='no_peraturan' maxlength='100' value="<?php echo set_value('no_peraturan', isset($peraturan_otk->no_peraturan) ? $peraturan_otk->no_peraturan : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('no_peraturan'); ?></span>
                </div>
            </div>
        </fieldset>
        </div>
        <div class="box-footer">
        <fieldset class='form-actions'>
            <input type='submit' name='save' class='btn btn-primary' value="<?php echo lang('peraturan_otk_action_edit'); ?>" />
            <?php echo lang('bf_or'); ?>
            <?php echo anchor(SITE_AREA . '/masters/peraturan_otk', lang('peraturan_otk_cancel'), 'class="btn btn-warning"'); ?>
            
            <?php if ($this->auth->has_permission('Peraturan_otk.Masters.Delete')) : ?>
                <?php echo lang('bf_or'); ?>
                <button type='submit' name='delete' formnovalidate class='btn btn-danger' id='delete-me' onclick="return confirm('<?php e(js_escape(lang('peraturan_otk_delete_confirm'))); ?>');">
                    <span class='icon-trash icon-white'></span>&nbsp;<?php echo lang('peraturan_otk_delete_record'); ?>
                </button>
            <?php endif; ?>
        </fieldset>
        </div>
    <?php echo form_close(); ?>
</div>