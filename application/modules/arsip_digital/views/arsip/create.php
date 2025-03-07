<style type="text/css">
    .select2-container {
    z-index: 99999;
}
.select2-container {
    width: 100% !important;
    padding: 0;
}
</style>
<link href="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/css/fileinput.css" media="all" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/themes/explorer/theme.css" media="all" rel="stylesheet" type="text/css"/>
<div class='alert alert-block alert-warning fade in'>
    <a class='close' data-dismiss='alert'>&times;</a>
    <h4 class='alert-heading'>
        Perhatian
    </h4>
    <?php echo $settingarsip['arsip.disclaimer']; ?>
</div>
<?php

$id = isset($arsip_digital->ID) ? $arsip_digital->ID : '';

?>
<div class="box box-warning admin-box">
    <div class="box-body">

        <div class="messagearsip"></div>
    <?php echo form_open($this->uri->uri_string(),"id=formarsip"); ?>
        <fieldset>
            

            <div class="control-group<?php echo form_error('ID_JENIS_ARSIP') ? ' error' : ''; ?>">
                <?php echo form_label(lang('arsip_digital_field_ID_JENIS_ARSIP'), 'ID_JENIS_ARSIP', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input type="hidden" name="NIP" value="<?php echo set_value('NIP', isset($NIP) ? $NIP : ''); ?>">
                    <select name="ID_JENIS_ARSIP" id="ID_JENIS_ARSIP" class="form-control select2">
                        <option value="">-- Silahkan Pilih --</option>
                        <?php if (isset($reckategori) && is_array($reckategori) && count($reckategori)):?>
                        <?php foreach($reckategori as $record):?>
                            <option value="<?php echo $record->ID?>"><?php echo $record->NAMA_JENIS; ?></option>
                            <?php endforeach;?>
                        <?php endif;?>
                    </select>
                    <span class='help-inline'><?php echo form_error('ID_JENIS_ARSIP'); ?></span>
                </div>
            </div>
 
            <div class="control-group<?php echo form_error('KETERANGAN') ? ' error' : ''; ?>">
                <?php echo form_label(lang('arsip_digital_field_KETERANGAN'), 'KETERANGAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <textarea name="KETERANGAN" class="form-control"><?php echo set_value('KETERANGAN', isset($arsip_digital->KETERANGAN) ? $arsip_digital->KETERANGAN : ''); ?></textarea>
                    <span class='help-inline'><?php echo form_error('KETERANGAN'); ?></span>
                </div>
            </div>
 
            <div class="control-group<?php echo form_error('FILE_BASE64') ? ' error' : ''; ?>">
                <?php echo form_label("FIle", 'FILE_BASE64', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id="file_dokumen" name="file_dokumen" class="file" type="file" data-show-upload="false" data-preview-file-type="pdf" title="Silahkan Pilih file pdf">
                    <span class='help-inline'>Silahkan pilih file dengan format .pdf</span>
                </div>
            </div>
        </fieldset>
    </div>
    <div class="box-footer">
        <fieldset class='form-actions'>
            <input type='button' name='save' id="btnsavearsip" class='btn btn-primary' value="Simpan Dokumen" />
            
        </fieldset>
    </div>
    <?php echo form_close(); ?>
</div>
<script src="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/themes/explorer/theme.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/js/fileinput.js" type="text/javascript"></script>
<script type="text/javascript">
   
 $(".select2").select2({}); 
</script>

