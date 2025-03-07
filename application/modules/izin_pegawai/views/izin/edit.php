<?php

if (validation_errors()) :
?>
<div class='alert alert-block alert-error fade in'>
    <a class='close' data-dismiss='alert'>&times;</a>
    <h4 class='alert-heading'>
        <?php echo lang('izin_pegawai_errors_message'); ?>
    </h4>
    <?php echo validation_errors(); ?>
</div>
<?php
endif;

$id = isset($izin_pegawai->ID) ? $izin_pegawai->ID : '';

?>
<div class='admin-box'>
    <h3>izin pegawai</h3>
    <?php echo form_open($this->uri->uri_string(), 'class="form-horizontal"'); ?>
        <fieldset>
            

            <div class="control-group<?php echo form_error('NIP_PNS') ? ' error' : ''; ?>">
                <?php echo form_label(lang('izin_pegawai_field_NIP_PNS') . lang('bf_form_label_required'), 'NIP_PNS', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='NIP_PNS' type='text' required='required' name='NIP_PNS' maxlength='18' value="<?php echo set_value('NIP_PNS', isset($izin_pegawai->NIP_PNS) ? $izin_pegawai->NIP_PNS : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('NIP_PNS'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('NAMA') ? ' error' : ''; ?>">
                <?php echo form_label(lang('izin_pegawai_field_NAMA'), 'NAMA', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='NAMA' type='text' name='NAMA' maxlength='100' value="<?php echo set_value('NAMA', isset($izin_pegawai->NAMA) ? $izin_pegawai->NAMA : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('NAMA'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('JABATAN') ? ' error' : ''; ?>">
                <?php echo form_label(lang('izin_pegawai_field_JABATAN'), 'JABATAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='JABATAN' type='text' name='JABATAN' maxlength='100' value="<?php echo set_value('JABATAN', isset($izin_pegawai->JABATAN) ? $izin_pegawai->JABATAN : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('JABATAN'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('UNIT_KERJA') ? ' error' : ''; ?>">
                <?php echo form_label(lang('izin_pegawai_field_UNIT_KERJA'), 'UNIT_KERJA', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='UNIT_KERJA' type='text' name='UNIT_KERJA' maxlength='100' value="<?php echo set_value('UNIT_KERJA', isset($izin_pegawai->UNIT_KERJA) ? $izin_pegawai->UNIT_KERJA : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('UNIT_KERJA'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('MASA_KERJA_TAHUN') ? ' error' : ''; ?>">
                <?php echo form_label(lang('izin_pegawai_field_MASA_KERJA_TAHUN'), 'MASA_KERJA_TAHUN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='MASA_KERJA_TAHUN' type='text' name='MASA_KERJA_TAHUN'  value="<?php echo set_value('MASA_KERJA_TAHUN', isset($izin_pegawai->MASA_KERJA_TAHUN) ? $izin_pegawai->MASA_KERJA_TAHUN : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('MASA_KERJA_TAHUN'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('MASA_KERJA_BULAN') ? ' error' : ''; ?>">
                <?php echo form_label(lang('izin_pegawai_field_MASA_KERJA_BULAN'), 'MASA_KERJA_BULAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='MASA_KERJA_BULAN' type='text' name='MASA_KERJA_BULAN'  value="<?php echo set_value('MASA_KERJA_BULAN', isset($izin_pegawai->MASA_KERJA_BULAN) ? $izin_pegawai->MASA_KERJA_BULAN : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('MASA_KERJA_BULAN'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('GAJI_POKOK') ? ' error' : ''; ?>">
                <?php echo form_label(lang('izin_pegawai_field_GAJI_POKOK'), 'GAJI_POKOK', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='GAJI_POKOK' type='text' name='GAJI_POKOK' maxlength='10' value="<?php echo set_value('GAJI_POKOK', isset($izin_pegawai->GAJI_POKOK) ? $izin_pegawai->GAJI_POKOK : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('GAJI_POKOK'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('KODE_IZIN') ? ' error' : ''; ?>">
                <?php echo form_label(lang('izin_pegawai_field_KODE_IZIN') . lang('bf_form_label_required'), 'KODE_IZIN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='KODE_IZIN' type='text' required='required' name='KODE_IZIN' maxlength='5' value="<?php echo set_value('KODE_IZIN', isset($izin_pegawai->KODE_IZIN) ? $izin_pegawai->KODE_IZIN : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('KODE_IZIN'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('DARI_TANGGAL') ? ' error' : ''; ?>">
                <?php echo form_label(lang('izin_pegawai_field_DARI_TANGGAL'), 'DARI_TANGGAL', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='DARI_TANGGAL' type='text' name='DARI_TANGGAL'  value="<?php echo set_value('DARI_TANGGAL', isset($izin_pegawai->DARI_TANGGAL) ? $izin_pegawai->DARI_TANGGAL : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('DARI_TANGGAL'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('SAMPAI_TANGGAL') ? ' error' : ''; ?>">
                <?php echo form_label(lang('izin_pegawai_field_SAMPAI_TANGGAL'), 'SAMPAI_TANGGAL', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='SAMPAI_TANGGAL' type='text' name='SAMPAI_TANGGAL'  value="<?php echo set_value('SAMPAI_TANGGAL', isset($izin_pegawai->SAMPAI_TANGGAL) ? $izin_pegawai->SAMPAI_TANGGAL : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('SAMPAI_TANGGAL'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('TAHUN') ? ' error' : ''; ?>">
                <?php echo form_label(lang('izin_pegawai_field_TAHUN'), 'TAHUN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='TAHUN' type='text' name='TAHUN' maxlength='4' value="<?php echo set_value('TAHUN', isset($izin_pegawai->TAHUN) ? $izin_pegawai->TAHUN : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('TAHUN'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('JUMLAH') ? ' error' : ''; ?>">
                <?php echo form_label(lang('izin_pegawai_field_JUMLAH'), 'JUMLAH', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='JUMLAH' type='text' name='JUMLAH'  value="<?php echo set_value('JUMLAH', isset($izin_pegawai->JUMLAH) ? $izin_pegawai->JUMLAH : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('JUMLAH'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('SATUAN') ? ' error' : ''; ?>">
                <?php echo form_label(lang('izin_pegawai_field_SATUAN'), 'SATUAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='SATUAN' type='text' name='SATUAN' maxlength='10' value="<?php echo set_value('SATUAN', isset($izin_pegawai->SATUAN) ? $izin_pegawai->SATUAN : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('SATUAN'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('KETERANGAN') ? ' error' : ''; ?>">
                <?php echo form_label(lang('izin_pegawai_field_KETERANGAN'), 'KETERANGAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='KETERANGAN' type='text' name='KETERANGAN' maxlength='255' value="<?php echo set_value('KETERANGAN', isset($izin_pegawai->KETERANGAN) ? $izin_pegawai->KETERANGAN : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('KETERANGAN'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('ALAMAT_SELAMA_CUTI') ? ' error' : ''; ?>">
                <?php echo form_label(lang('izin_pegawai_field_ALAMAT_SELAMA_CUTI'), 'ALAMAT_SELAMA_CUTI', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='ALAMAT_SELAMA_CUTI' type='text' name='ALAMAT_SELAMA_CUTI' maxlength='255' value="<?php echo set_value('ALAMAT_SELAMA_CUTI', isset($izin_pegawai->ALAMAT_SELAMA_CUTI) ? $izin_pegawai->ALAMAT_SELAMA_CUTI : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('ALAMAT_SELAMA_CUTI'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('TLP_SELAMA_CUTI') ? ' error' : ''; ?>">
                <?php echo form_label(lang('izin_pegawai_field_TLP_SELAMA_CUTI'), 'TLP_SELAMA_CUTI', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='TLP_SELAMA_CUTI' type='text' name='TLP_SELAMA_CUTI' maxlength='20' value="<?php echo set_value('TLP_SELAMA_CUTI', isset($izin_pegawai->TLP_SELAMA_CUTI) ? $izin_pegawai->TLP_SELAMA_CUTI : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('TLP_SELAMA_CUTI'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('TGL_DIBUAT') ? ' error' : ''; ?>">
                <?php echo form_label(lang('izin_pegawai_field_TGL_DIBUAT'), 'TGL_DIBUAT', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='TGL_DIBUAT' type='text' name='TGL_DIBUAT'  value="<?php echo set_value('TGL_DIBUAT', isset($izin_pegawai->TGL_DIBUAT) ? $izin_pegawai->TGL_DIBUAT : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('TGL_DIBUAT'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('LAMPIRAN_FILE') ? ' error' : ''; ?>">
                <?php echo form_label(lang('izin_pegawai_field_LAMPIRAN_FILE'), 'LAMPIRAN_FILE', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='LAMPIRAN_FILE' type='text' name='LAMPIRAN_FILE' maxlength='50' value="<?php echo set_value('LAMPIRAN_FILE', isset($izin_pegawai->LAMPIRAN_FILE) ? $izin_pegawai->LAMPIRAN_FILE : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('LAMPIRAN_FILE'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('SISA_CUTI_TAHUN_N2') ? ' error' : ''; ?>">
                <?php echo form_label(lang('izin_pegawai_field_SISA_CUTI_TAHUN_N2'), 'SISA_CUTI_TAHUN_N2', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='SISA_CUTI_TAHUN_N2' type='text' name='SISA_CUTI_TAHUN_N2'  value="<?php echo set_value('SISA_CUTI_TAHUN_N2', isset($izin_pegawai->SISA_CUTI_TAHUN_N2) ? $izin_pegawai->SISA_CUTI_TAHUN_N2 : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('SISA_CUTI_TAHUN_N2'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('SISA_CUTI_TAHUN_N1') ? ' error' : ''; ?>">
                <?php echo form_label(lang('izin_pegawai_field_SISA_CUTI_TAHUN_N1'), 'SISA_CUTI_TAHUN_N1', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='SISA_CUTI_TAHUN_N1' type='text' name='SISA_CUTI_TAHUN_N1'  value="<?php echo set_value('SISA_CUTI_TAHUN_N1', isset($izin_pegawai->SISA_CUTI_TAHUN_N1) ? $izin_pegawai->SISA_CUTI_TAHUN_N1 : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('SISA_CUTI_TAHUN_N1'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('SISA_CUTI_TAHUN_N') ? ' error' : ''; ?>">
                <?php echo form_label(lang('izin_pegawai_field_SISA_CUTI_TAHUN_N'), 'SISA_CUTI_TAHUN_N', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='SISA_CUTI_TAHUN_N' type='text' name='SISA_CUTI_TAHUN_N'  value="<?php echo set_value('SISA_CUTI_TAHUN_N', isset($izin_pegawai->SISA_CUTI_TAHUN_N) ? $izin_pegawai->SISA_CUTI_TAHUN_N : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('SISA_CUTI_TAHUN_N'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('ANAK_KE') ? ' error' : ''; ?>">
                <?php echo form_label(lang('izin_pegawai_field_ANAK_KE'), 'ANAK_KE', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='ANAK_KE' type='text' name='ANAK_KE' maxlength='1' value="<?php echo set_value('ANAK_KE', isset($izin_pegawai->ANAK_KE) ? $izin_pegawai->ANAK_KE : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('ANAK_KE'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('LAMA_KERJA_TAHUN') ? ' error' : ''; ?>">
                <?php echo form_label(lang('izin_pegawai_field_LAMA_KERJA_TAHUN'), 'LAMA_KERJA_TAHUN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='LAMA_KERJA_TAHUN' type='text' name='LAMA_KERJA_TAHUN' maxlength='2' value="<?php echo set_value('LAMA_KERJA_TAHUN', isset($izin_pegawai->LAMA_KERJA_TAHUN) ? $izin_pegawai->LAMA_KERJA_TAHUN : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('LAMA_KERJA_TAHUN'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('NIP_ATASAN') ? ' error' : ''; ?>">
                <?php echo form_label(lang('izin_pegawai_field_NIP_ATASAN'), 'NIP_ATASAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='NIP_ATASAN' type='text' name='NIP_ATASAN' maxlength='25' value="<?php echo set_value('NIP_ATASAN', isset($izin_pegawai->NIP_ATASAN) ? $izin_pegawai->NIP_ATASAN : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('NIP_ATASAN'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('STATUS_ATASAN') ? ' error' : ''; ?>">
                <?php echo form_label(lang('izin_pegawai_field_STATUS_ATASAN'), 'STATUS_ATASAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='STATUS_ATASAN' type='text' name='STATUS_ATASAN'  value="<?php echo set_value('STATUS_ATASAN', isset($izin_pegawai->STATUS_ATASAN) ? $izin_pegawai->STATUS_ATASAN : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('STATUS_ATASAN'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('CATATAN_ATASAN') ? ' error' : ''; ?>">
                <?php echo form_label(lang('izin_pegawai_field_CATATAN_ATASAN'), 'CATATAN_ATASAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='CATATAN_ATASAN' type='text' name='CATATAN_ATASAN' maxlength='255' value="<?php echo set_value('CATATAN_ATASAN', isset($izin_pegawai->CATATAN_ATASAN) ? $izin_pegawai->CATATAN_ATASAN : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('CATATAN_ATASAN'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('NIP_PYBMC') ? ' error' : ''; ?>">
                <?php echo form_label(lang('izin_pegawai_field_NIP_PYBMC'), 'NIP_PYBMC', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='NIP_PYBMC' type='text' name='NIP_PYBMC' maxlength='25' value="<?php echo set_value('NIP_PYBMC', isset($izin_pegawai->NIP_PYBMC) ? $izin_pegawai->NIP_PYBMC : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('NIP_PYBMC'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('STATUS_PYBMC') ? ' error' : ''; ?>">
                <?php echo form_label(lang('izin_pegawai_field_STATUS_PYBMC'), 'STATUS_PYBMC', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='STATUS_PYBMC' type='text' name='STATUS_PYBMC'  value="<?php echo set_value('STATUS_PYBMC', isset($izin_pegawai->STATUS_PYBMC) ? $izin_pegawai->STATUS_PYBMC : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('STATUS_PYBMC'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('CATATAN_PYBMC') ? ' error' : ''; ?>">
                <?php echo form_label(lang('izin_pegawai_field_CATATAN_PYBMC'), 'CATATAN_PYBMC', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='CATATAN_PYBMC' type='text' name='CATATAN_PYBMC' maxlength='255' value="<?php echo set_value('CATATAN_PYBMC', isset($izin_pegawai->CATATAN_PYBMC) ? $izin_pegawai->CATATAN_PYBMC : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('CATATAN_PYBMC'); ?></span>
                </div>
            </div>
        </fieldset>
        <fieldset class='form-actions'>
            <input type='submit' name='save' class='btn btn-primary' value="<?php echo lang('izin_pegawai_action_edit'); ?>" />
            <?php echo lang('bf_or'); ?>
            <?php echo anchor(SITE_AREA . '/izin/izin_pegawai', lang('izin_pegawai_cancel'), 'class="btn btn-warning"'); ?>
            
            <?php if ($this->auth->has_permission('Izin_pegawai.Izin.Delete')) : ?>
                <?php echo lang('bf_or'); ?>
                <button type='submit' name='delete' formnovalidate class='btn btn-danger' id='delete-me' onclick="return confirm('<?php e(js_escape(lang('izin_pegawai_delete_confirm'))); ?>');">
                    <span class='icon-trash icon-white'></span>&nbsp;<?php echo lang('izin_pegawai_delete_record'); ?>
                </button>
            <?php endif; ?>
        </fieldset>
    <?php echo form_close(); ?>
</div>