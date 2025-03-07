<?php

if (validation_errors()) :
?>
<div class='alert alert-block alert-error fade in'>
    <a class='close' data-dismiss='alert'>&times;</a>
    <h4 class='alert-heading'>
        <?php echo lang('ds_riwayat_errors_message'); ?>
    </h4>
    <?php echo validation_errors(); ?>
</div>
<?php
endif;

$id = isset($ds_riwayat->id_riwayat) ? $ds_riwayat->id_riwayat : '';

?>
<div class='admin-box'>
    <h3>ds riwayat</h3>
    <?php echo form_open($this->uri->uri_string(), 'class="form-horizontal"'); ?>
        <fieldset>
            

            <div class="control-group<?php echo form_error('id_pemroses') ? ' error' : ''; ?>">
                <?php echo form_label(lang('ds_riwayat_field_id_pemroses'), 'id_pemroses', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='id_pemroses' type='text' name='id_pemroses' maxlength='255' value="<?php echo set_value('id_pemroses', isset($ds_riwayat->id_pemroses) ? $ds_riwayat->id_pemroses : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('id_pemroses'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('tindakan') ? ' error' : ''; ?>">
                <?php echo form_label(lang('ds_riwayat_field_tindakan'), 'tindakan', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <?php echo form_textarea(array('name' => 'tindakan', 'id' => 'tindakan', 'rows' => '5', 'cols' => '80', 'value' => set_value('tindakan', isset($ds_riwayat->tindakan) ? $ds_riwayat->tindakan : ''))); ?>
                    <span class='help-inline'><?php echo form_error('tindakan'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('catatan_tindakan') ? ' error' : ''; ?>">
                <?php echo form_label(lang('ds_riwayat_field_catatan_tindakan'), 'catatan_tindakan', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <?php echo form_textarea(array('name' => 'catatan_tindakan', 'id' => 'catatan_tindakan', 'rows' => '5', 'cols' => '80', 'value' => set_value('catatan_tindakan', isset($ds_riwayat->catatan_tindakan) ? $ds_riwayat->catatan_tindakan : ''))); ?>
                    <span class='help-inline'><?php echo form_error('catatan_tindakan'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('waktu_tindakan') ? ' error' : ''; ?>">
                <?php echo form_label(lang('ds_riwayat_field_waktu_tindakan'), 'waktu_tindakan', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='waktu_tindakan' type='text' name='waktu_tindakan'  value="<?php echo set_value('waktu_tindakan', isset($ds_riwayat->waktu_tindakan) ? $ds_riwayat->waktu_tindakan : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('waktu_tindakan'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('akses_pengguna') ? ' error' : ''; ?>">
                <?php echo form_label(lang('ds_riwayat_field_akses_pengguna'), 'akses_pengguna', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='akses_pengguna' type='text' name='akses_pengguna' maxlength='200' value="<?php echo set_value('akses_pengguna', isset($ds_riwayat->akses_pengguna) ? $ds_riwayat->akses_pengguna : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('akses_pengguna'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('id_riwayat') ? ' error' : ''; ?>">
                <?php echo form_label(lang('ds_riwayat_field_id_riwayat'), 'id_riwayat', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='id_riwayat' type='text' name='id_riwayat' maxlength='64' value="<?php echo set_value('id_riwayat', isset($ds_riwayat->id_riwayat) ? $ds_riwayat->id_riwayat : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('id_riwayat'); ?></span>
                </div>
            </div>
        </fieldset>
        <fieldset class='form-actions'>
            <input type='submit' name='save' class='btn btn-primary' value="<?php echo lang('ds_riwayat_action_create'); ?>" />
            <?php echo lang('bf_or'); ?>
            <?php echo anchor(SITE_AREA . '/sk/ds_riwayat', lang('ds_riwayat_cancel'), 'class="btn btn-warning"'); ?>
            
        </fieldset>
    <?php echo form_close(); ?>
</div>