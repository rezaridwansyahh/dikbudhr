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

$id = isset($permen->id) ? $permen->id : '';

?>
<div class='admin-box box box-primary'>
    <div class="box-header">
        <h3>Tambah Data</h3>
    </div>        
        <?php echo form_open($this->uri->uri_string(), ''); ?>
           <input id='id' type='hidden' class="form-control" required='required' name='id' maxlength='100' value="<?php echo set_value('id', isset($permen->id) ? $permen->id : ''); ?>" />
    <div class="box-body">
        <fieldset>
            

            <div class="control-group<?php echo form_error('permen') ? ' error' : ''; ?>">
                <?php echo form_label("No Permen", 'no_peraturan', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='permen' class="form-control" type='text' name='permen' maxlength='100' value="<?php echo set_value('permen', isset($permen->permen) ? $permen->permen : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('permen'); ?></span>
                </div>
            </div>
        </fieldset>
        </div>
        <div class="box-footer">
        <fieldset class='form-actions'>
            <input type='submit' name='save' class='btn btn-primary' value="Save Permen" />
            <?php echo lang('bf_or'); ?>
            <?php echo anchor(SITE_AREA . '/masters/petajabatan/permen', "Cancel", 'class="btn btn-warning"'); ?>
            
        </fieldset>
        </div>
    <?php echo form_close(); ?>
</div>