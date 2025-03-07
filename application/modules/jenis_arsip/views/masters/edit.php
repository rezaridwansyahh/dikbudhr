<?php

if (validation_errors()) :
?>
<div class='alert alert-block alert-error fade in'>
    <a class='close' data-dismiss='alert'>&times;</a>
    <h4 class='alert-heading'>
        <?php echo lang('jenis_arsip_errors_message'); ?>
    </h4>
    <?php echo validation_errors(); ?>
</div>
<?php
endif;

$id = isset($jenis_arsip->ID) ? $jenis_arsip->ID : '';

?>
<div class='box box-warning' id="form-riwayat-assesmen-add">
    <div class="box-body">
        <div class="messages">
        </div>
    <?php echo form_open($this->uri->uri_string(), 'id="frm"'); ?>
        <fieldset>
            
             <div class="control-group<?php echo form_error('ID_JENIS_ARSIP') ? ' error' : ''; ?>">
                <?php echo form_label("Kategori", 'KATEGORI_ARSIP', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input type="hidden" name="NIP" value="<?php echo set_value('NIP', isset($NIP) ? $NIP : ''); ?>">
                    <select name="KATEGORI_ARSIP" id="KATEGORI_ARSIP" class="form-control select2">
                        <option value="">-- Silahkan Pilih --</option>
                        <?php if (isset($reckategori) && is_array($reckategori) && count($reckategori)):?>
                        <?php foreach($reckategori as $record):?>
                            <option value="<?php echo $record->ID?>"><?php echo $record->KATEGORI; ?></option>
                            <?php endforeach;?>
                        <?php endif;?>
                    </select>
                    <span class='help-inline'><?php echo form_error('KATEGORI_ARSIP'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('NAMA_JENIS') ? ' error' : ''; ?>">
                <?php echo form_label(lang('jenis_arsip_field_NAMA_JENIS'), 'NAMA_JENIS', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='ID' type='hidden' name='ID' maxlength='5' value="<?php echo set_value('ID', isset($jenis_arsip->ID) ? $jenis_arsip->ID : ''); ?>" />
                    <input id='NAMA_JENIS' type='text' class="form-control" name='NAMA_JENIS' maxlength='255' value="<?php echo set_value('NAMA_JENIS', isset($jenis_arsip->NAMA_JENIS) ? $jenis_arsip->NAMA_JENIS : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('NAMA_JENIS'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('KETERANGAN') ? ' error' : ''; ?>">
                <?php echo form_label(lang('jenis_arsip_field_KETERANGAN'), 'KETERANGAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <?php echo form_textarea(array('name' => 'KETERANGAN', 'class'=>'form-control', 'id' => 'KETERANGAN', 'rows' => '5', 'cols' => '80', 'value' => set_value('KETERANGAN', isset($jenis_arsip->KETERANGAN) ? $jenis_arsip->KETERANGAN : ''))); ?>
                    <span class='help-inline'><?php echo form_error('KETERANGAN'); ?></span>
                </div>
            </div>
        </fieldset>
        </div>
        <div class="box-footer">
            <a href="javascript:;" id="btnsave"  class="btn green btn-primary button-submit"> 
                <i class="fa fa-save"></i> 
                Simpan
            </a>
            <?php echo lang('bf_or'); ?>
            <?php echo anchor(SITE_AREA . '/masters/jenis_arsip', lang('jenis_arsip_cancel'), 'class="btn btn-warning"'); ?>
            
        </div>
    <?php echo form_close(); ?>
</div>
 