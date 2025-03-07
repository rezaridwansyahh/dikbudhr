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
<?php

if (validation_errors()) :
?>
<div class='alert alert-block alert-error fade in'>
    <a class='close' data-dismiss='alert'>&times;</a>
    <h4 class='alert-heading'>
        <?php echo lang('arsip_digital_errors_message'); ?>
    </h4>
    <?php echo validation_errors(); ?>
</div>
<?php
endif;

$id = isset($arsip_digital->ID) ? $arsip_digital->ID : '';

?>
<div class="box box-warning admin-box">
    <div class="box-body">
        <div class="message"></div>
    <?php echo form_open($this->uri->uri_string(),"id=submit_form","form"); ?>
        <fieldset>
            

            <div class="control-group<?php echo form_error('ID_JENIS_ARSIP') ? ' error' : ''; ?>">
                <?php echo form_label(lang('arsip_digital_field_ID_JENIS_ARSIP'), 'ID_JENIS_ARSIP', array('class' => 'control-label')); ?>
                <div class='controls'>
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

            <div class="control-group<?php echo form_error('NIP') ? ' error' : ''; ?>">
                <?php echo form_label("Pegawai", 'NIP', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select id="NIP" name="NIP" width="100%" class="form-control select2">
                        <?php
                        if($selectedAtasanLangsung){
                            echo "<option selected value='".$selectedAtasanLangsung->PNS_ID."'>".$selectedAtasanLangsung->NAMA."</option>";
                        }
                        ?>
                    </select>
                    <span class='help-inline'><?php echo form_error('NIP'); ?></span>
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
                <?php echo form_label(lang('arsip_digital_field_FILE_BASE64'), 'FILE_BASE64', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id="file_dokumen" name="file_dokumen" class="file" type="file" data-preview-file-type="pdf" title="Silahkan Pilih file pdf">
                    <span class='help-inline'><?php echo form_error('FILE_BASE64'); ?></span>
                </div>
            </div>
        </fieldset>
    </div>
    <div class="box-footer">
        <fieldset class='form-actions'>
            <input type='button' name='save' id="btnsave" class='btn btn-primary' value="Simpan Dokumen" />
            
        </fieldset>
    </div>
    <?php echo form_close(); ?>
</div>
<script src="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/themes/explorer/theme.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/js/fileinput.js" type="text/javascript"></script>
<script type="text/javascript">
   
 $(".select2").select2({});
 $("#NIP").select2({
        placeholder: 'Cari Pemilik Dokumen.....',
        width: '100%',
        minimumInputLength: 3,
        allowClear: true,
        ajax: {
            url: '<?php echo site_url("pegawai/kepegawaian/ajaxnip");?>',
            dataType: 'json',
            data: function(params) {
                return {
                    term: params.term || '',
                    page: params.page || 1
                }
            },
            cache: true
        }
    });
</script>

