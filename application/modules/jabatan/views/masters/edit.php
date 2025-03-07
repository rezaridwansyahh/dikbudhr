<?php

if (validation_errors()) :
?>
<div class='alert alert-block alert-error fade in'>
    <a class='close' data-dismiss='alert'>&times;</a>
    <h4 class='alert-heading'>
        <?php echo lang('jabatan_errors_message'); ?>
    </h4>
    <?php echo validation_errors(); ?>
</div>
<?php
endif;

$id = isset($jabatan->NO) ? $jabatan->NO : '';

?>
<div class='admin-box box box-primary'>
    <div class="box-header">
        <h3>Edit Jabatan</h3>
    </div>        
        <?php echo form_open($this->uri->uri_string(), ''); ?>
           <input id='NO' type='hidden' class="form-control" required='required' name='NO' maxlength='100' value="<?php echo set_value('NO', isset($jabatan->NO) ? $jabatan->NO : ''); ?>" />
    <div class="box-body">
             
			<div class="control-group<?php echo form_error('KODE_JABATAN') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("KODE JABATAN". lang('bf_form_label_required')."(Sama dengan Aplikasi Mutasi)", 'KODE_JABATAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='KODE_JABATAN' type='text' class="form-control" required='required' name='KODE_JABATAN' maxlength='100' value="<?php echo set_value('KODE_JABATAN', isset($jabatan->KODE_JABATAN) ? $jabatan->KODE_JABATAN : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('KODE_JABATAN'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('KODE_BKN') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("KODE BKN", 'KODE_BKN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='KODE_BKN' type='text' class="form-control" name='KODE_BKN' maxlength='100' value="<?php echo set_value('KODE_BKN', isset($jabatan->KODE_BKN) ? $jabatan->KODE_BKN : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('KODE_BKN'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('NAMA_JABATAN') ? ' error' : ''; ?> col-sm-12">
                    <?php echo form_label("Nama Jabatan". lang('bf_form_label_required'), 'NAMA_JABATAN', array('class' => 'control-label')); ?>
                    <div class='controls'>
                        <input id='NAMA_JABATAN' type='text' class="form-control" required='required' name='NAMA_JABATAN' maxlength='100' value="<?php echo set_value('NAMA_JABATAN', isset($jabatan->NAMA_JABATAN) ? $jabatan->NAMA_JABATAN : ''); ?>" />
                        <span class='help-inline'><?php echo form_error('NAMA_JABATAN'); ?></span>
                    </div>
                </div>
                <div class="control-group<?php echo form_error('NAMA_JABATAN_FULL') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("NAMA JABATAN FULL". lang('bf_form_label_required'), 'NAMA_JABATAN_FULL', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='NAMA_JABATAN_FULL' type='text' class="form-control" required='required' name='NAMA_JABATAN_FULL' maxlength='100' value="<?php echo set_value('NAMA_JABATAN_FULL', isset($jabatan->NAMA_JABATAN_FULL) ? $jabatan->NAMA_JABATAN_FULL : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('NAMA_JABATAN_FULL'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('NAMA_JABATAN_BKN') ? ' error' : ''; ?> col-sm-12">
                    <?php echo form_label("Nama Jabatan BKN". lang('bf_form_label_required'), 'NAMA_JABATAN_BKN', array('class' => 'control-label')); ?>
                    <div class='controls'>
                        <input id='NAMA_JABATAN_BKN' type='text' class="form-control" required='required' name='NAMA_JABATAN_BKN' maxlength='100' value="<?php echo set_value('NAMA_JABATAN_BKN', isset($jabatan->NAMA_JABATAN_BKN) ? $jabatan->NAMA_JABATAN_BKN : ''); ?>" />
                        <span class='help-inline'><?php echo form_error('NAMA_JABATAN_BKN'); ?></span>
                    </div>
                </div>
            <div class="control-group<?php echo form_error('Jenis_Jabatan') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Jenis Jabatan", 'Jenis_Jabatan', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select name="JENIS_JABATAN" id="JENIS_JABATAN" class="form-control select2">
                        <option value="">-- Silahkan Pilih --</option>
                        <option value="4" <?php if(isset($jabatan->JENIS_JABATAN))  echo  (trim($jabatan->JENIS_JABATAN)=="4") ? "selected" : ""; ?>>Fungsional Umum</option>
                        <option value="2" <?php if(isset($jabatan->JENIS_JABATAN))  echo  (trim($jabatan->JENIS_JABATAN) == "2") ? "selected" : ""; ?>>Fungsional Tertentu</option>
                        <option value="1" <?php if(isset($jabatan->JENIS_JABATAN))  echo  ($jabatan->JENIS_JABATAN=="1") ? "selected" : ""; ?>>Struktural</option>
                    </select>
                    <span class='help-inline'><?php echo form_error('JENIS_JABATAN'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('KELAS') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Kelas Jabatan", 'KELAS', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='KELAS' type='text' class="form-control" required='required'  name='KELAS' maxlength='100' value="<?php echo set_value('KELAS', isset($jabatan->KELAS) ? $jabatan->KELAS : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('KELAS'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('BUP_PENSIUN') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("BUP PENSIUN". lang('bf_form_label_required'), 'BUP PENSIUN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='PENSIUN' type='text' class="form-control" required='required' name='PENSIUN' maxlength='100' value="<?php echo set_value('PENSIUN', isset($jabatan->PENSIUN) ? $jabatan->PENSIUN : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('BUP PENSIUN'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('TUNJANGAN') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Rp. TUNJANGAN", 'BUP PENSIUN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='TUNJANGAN' type='text' class="form-control" name='TUNJANGAN' maxlength='100' value="<?php echo set_value('TUNJANGAN', isset($jabatan->TUNJANGAN) ? $jabatan->TUNJANGAN : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('TUNJANGAN'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('KATEGORI_JABATAN') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("KATEGORI JABATAN", 'KATEGORI_JABATAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='NO' type='hidden' class="form-control" required='required' name='NO' maxlength='100' value="<?php echo set_value('NO', isset($jabatan->NO) ? $jabatan->NO : ''); ?>" />
                    <select name="KATEGORI_JABATAN" id="KATEGORI_JABATAN" class="form-control select2">
                        <option value="">-- Silahkan Pilih --</option>
                        <option value="Pelaksana" <?php if(isset($jabatan->KATEGORI_JABATAN))  echo  (trim($jabatan->KATEGORI_JABATAN)=="Pelaksana") ? "selected" : ""; ?>>Pelaksana</option>
                        <option value="Administrator" <?php if(isset($jabatan->KATEGORI_JABATAN))  echo  (trim($jabatan->KATEGORI_JABATAN)=="Administrator") ? "selected" : ""; ?>>Administrator</option>
                        <option value="Fungsional" <?php if(isset($jabatan->KATEGORI_JABATAN))  echo  (trim($jabatan->KATEGORI_JABATAN)=="Fungsional") ? "selected" : ""; ?>>Fungsional</option>
                        <option value="Menteri" <?php if(isset($jabatan->KATEGORI_JABATAN))  echo  (trim($jabatan->KATEGORI_JABATAN)=="Menteri") ? "selected" : ""; ?>>Menteri</option>
                        <option value="JPT Madya" <?php if(isset($jabatan->KATEGORI_JABATAN))  echo  (trim($jabatan->KATEGORI_JABATAN)=="JPT Madya") ? "selected" : ""; ?>>JPT Madya</option>
                        <option value="Pengawas" <?php if(isset($jabatan->KATEGORI_JABATAN))  echo  (trim($jabatan->KATEGORI_JABATAN)=="Pengawas") ? "selected" : ""; ?>>Pengawas</option>
                        <option value="Staf Khusus" <?php if(isset($jabatan->KATEGORI_JABATAN))  echo  (trim($jabatan->KATEGORI_JABATAN)=="Staf Khusus") ? "selected" : ""; ?>>Staf Khusus</option>
                        <option value="JPT Pratama" <?php if(isset($jabatan->KATEGORI_JABATAN))  echo  (trim($jabatan->KATEGORI_JABATAN)=="JPT Pratama") ? "selected" : ""; ?>>JPT Pratama</option>
                    </select>
                    <span class='help-inline'><?php echo form_error('KATEGORI_JABATAN'); ?></span>
                </div>
            </div>
        </div>
            <div class="box-footer">
            <fieldset class='form-actions'>
                <input type='submit' name='save' class='btn btn-primary' value="Simpan" />
                <?php echo lang('bf_or'); ?>
                <?php echo anchor(SITE_AREA . '/masters/jabatan', lang('jabatan_cancel'), 'class="btn btn-warning"'); ?>
        
            </fieldset>
            </div>
        <?php echo form_close(); ?>
   
</div>