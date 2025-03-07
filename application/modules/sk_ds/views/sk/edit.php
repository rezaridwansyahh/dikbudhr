<div class='alert alert-block alert-warning'>
    <a class='close' data-dismiss='alert'>&times;</a>
    <h4 class='alert-heading'>
        Baca SK
    </h4>
    <?php echo validation_errors(); ?>
</div>
<?php

if (validation_errors()) :
?>
<div class='alert alert-block alert-error fade in'>
    <a class='close' data-dismiss='alert'>&times;</a>
    <h4 class='alert-heading'>
        <?php echo lang('sk_ds_errors_message'); ?>
    </h4>
    <?php echo validation_errors(); ?>
</div>
<?php
endif;

$id = isset($sk_ds->id_file) ? $sk_ds->id_file : '';

?>
<div class='admin-box'>
    <h3>sk ds</h3>
    <?php echo form_open($this->uri->uri_string(), 'class="form-horizontal"'); ?>
        <fieldset>
            

            <div class="control-group<?php echo form_error('waktu_buat') ? ' error' : ''; ?>">
                <?php echo form_label(lang('sk_ds_field_waktu_buat'), 'waktu_buat', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='waktu_buat' type='text' name='waktu_buat'  value="<?php echo set_value('waktu_buat', isset($sk_ds->waktu_buat) ? $sk_ds->waktu_buat : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('waktu_buat'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('kategori') ? ' error' : ''; ?>">
                <?php echo form_label(lang('sk_ds_field_kategori'), 'kategori', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='kategori' type='text' name='kategori' maxlength='100' value="<?php echo set_value('kategori', isset($sk_ds->kategori) ? $sk_ds->kategori : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('kategori'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('teks_base64') ? ' error' : ''; ?>">
                <?php echo form_label(lang('sk_ds_field_teks_base64'), 'teks_base64', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <?php echo form_textarea(array('name' => 'teks_base64', 'id' => 'teks_base64', 'rows' => '5', 'cols' => '80', 'value' => set_value('teks_base64', isset($sk_ds->teks_base64) ? $sk_ds->teks_base64 : ''))); ?>
                    <span class='help-inline'><?php echo form_error('teks_base64'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('id_pegawai_ttd') ? ' error' : ''; ?>">
                <?php echo form_label(lang('sk_ds_field_id_pegawai_ttd'), 'id_pegawai_ttd', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='id_pegawai_ttd' type='text' name='id_pegawai_ttd' maxlength='255' value="<?php echo set_value('id_pegawai_ttd', isset($sk_ds->id_pegawai_ttd) ? $sk_ds->id_pegawai_ttd : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('id_pegawai_ttd'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('is_signed') ? ' error' : ''; ?>">
                <?php echo form_label(lang('sk_ds_field_is_signed'), 'is_signed', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='is_signed' type='text' name='is_signed' maxlength='16' value="<?php echo set_value('is_signed', isset($sk_ds->is_signed) ? $sk_ds->is_signed : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('is_signed'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('nip_sk') ? ' error' : ''; ?>">
                <?php echo form_label(lang('sk_ds_field_nip_sk'), 'nip_sk', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='nip_sk' type='text' name='nip_sk' maxlength='50' value="<?php echo set_value('nip_sk', isset($sk_ds->nip_sk) ? $sk_ds->nip_sk : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('nip_sk'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('nomor_sk') ? ' error' : ''; ?>">
                <?php echo form_label(lang('sk_ds_field_nomor_sk'), 'nomor_sk', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='nomor_sk' type='text' name='nomor_sk' maxlength='50' value="<?php echo set_value('nomor_sk', isset($sk_ds->nomor_sk) ? $sk_ds->nomor_sk : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('nomor_sk'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('tgl_sk') ? ' error' : ''; ?>">
                <?php echo form_label(lang('sk_ds_field_tgl_sk'), 'tgl_sk', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='tgl_sk' type='text' name='tgl_sk'  value="<?php echo set_value('tgl_sk', isset($sk_ds->tgl_sk) ? $sk_ds->tgl_sk : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('tgl_sk'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('tmt_sk') ? ' error' : ''; ?>">
                <?php echo form_label(lang('sk_ds_field_tmt_sk'), 'tmt_sk', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='tmt_sk' type='text' name='tmt_sk'  value="<?php echo set_value('tmt_sk', isset($sk_ds->tmt_sk) ? $sk_ds->tmt_sk : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('tmt_sk'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('lokasi_file') ? ' error' : ''; ?>">
                <?php echo form_label(lang('sk_ds_field_lokasi_file'), 'lokasi_file', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <?php echo form_textarea(array('name' => 'lokasi_file', 'id' => 'lokasi_file', 'rows' => '5', 'cols' => '80', 'value' => set_value('lokasi_file', isset($sk_ds->lokasi_file) ? $sk_ds->lokasi_file : ''))); ?>
                    <span class='help-inline'><?php echo form_error('lokasi_file'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('is_corrected') ? ' error' : ''; ?>">
                <?php echo form_label(lang('sk_ds_field_is_corrected'), 'is_corrected', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='is_corrected' type='text' name='is_corrected' maxlength='16' value="<?php echo set_value('is_corrected', isset($sk_ds->is_corrected) ? $sk_ds->is_corrected : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('is_corrected'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('catatan') ? ' error' : ''; ?>">
                <?php echo form_label(lang('sk_ds_field_catatan'), 'catatan', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <?php echo form_textarea(array('name' => 'catatan', 'id' => 'catatan', 'rows' => '5', 'cols' => '80', 'value' => set_value('catatan', isset($sk_ds->catatan) ? $sk_ds->catatan : ''))); ?>
                    <span class='help-inline'><?php echo form_error('catatan'); ?></span>
                </div>
            </div>
        </fieldset>
        <fieldset class='form-actions'>
            <input type='submit' name='save' class='btn btn-primary' value="<?php echo lang('sk_ds_action_edit'); ?>" />
            <?php echo lang('bf_or'); ?>
            <?php echo anchor(SITE_AREA . '/sk/sk_ds', lang('sk_ds_cancel'), 'class="btn btn-warning"'); ?>
            
            <?php if ($this->auth->has_permission('Sk_ds.Sk.Delete')) : ?>
                <?php echo lang('bf_or'); ?>
                <button type='submit' name='delete' formnovalidate class='btn btn-danger' id='delete-me' onclick="return confirm('<?php e(js_escape(lang('sk_ds_delete_confirm'))); ?>');">
                    <span class='icon-trash icon-white'></span>&nbsp;<?php echo lang('sk_ds_delete_record'); ?>
                </button>
            <?php endif; ?>
        </fieldset>
    <?php echo form_close(); ?>
</div>