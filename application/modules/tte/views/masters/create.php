<link href="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/css/fileinput.css" media="all" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/themes/explorer/theme.css" media="all" rel="stylesheet" type="text/css"/>


<?php

if (validation_errors()) :
?>
<div class='alert alert-block alert-error fade in'>
    <a class='close' data-dismiss='alert'>&times;</a>
    <h4 class='alert-heading'>
        <?php echo lang('tte_errors_message'); ?>
    </h4>
    <?php echo validation_errors(); ?>
</div>
<?php
endif;

$id = isset($tte->id) ? $tte->id : '';

?>
<div class='admin-box box box-primary'>
    <div class="box-header">
        <h3>Tambah Master Proses TTE</h3>
    </div>        
    <div class="box-body">
    <?php echo form_open($this->uri->uri_string(),"id=submit_form","form"); ?>
        <fieldset>
            

            <div class="control-group<?php echo form_error('nama_proses') ? ' error' : ''; ?>">
                <?php echo form_label(lang('tte_field_nama_proses') . lang('bf_form_label_required'), 'nama_proses', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='nama_proses' type='text' required='required' class="form-control" name='nama_proses' maxlength='100' value="<?php echo set_value('nama_proses', isset($tte->nama_proses) ? $tte->nama_proses : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('nama_proses'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('penandatangan_sk') ? ' error' : ''; ?>">
                <?php echo form_label(lang('tte_field_penandatangan_sk'), 'penandatangan_sk', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select id="penandatangan_sk" name="penandatangan_sk" width="100%" class="form-control select2">
                        <?php
                        if($selectedAtasanLangsung){
                            echo "<option selected value='".$selectedAtasanLangsung->PNS_ID."'>".$selectedAtasanLangsung->NAMA."</option>";
                        }
                        ?>
                    </select>
                    <span class='help-inline'>Default Penandatangan</span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('keterangan_proses') ? ' error' : ''; ?>">
                <?php echo form_label(lang('tte_field_keterangan_proses'), 'keterangan_proses', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <textarea name='keterangan_proses' class="form-control"><?php echo set_value('keterangan_proses', isset($tte->keterangan_proses) ? $tte->keterangan_proses : ''); ?></textarea> 
                    <span class='help-inline'><?php echo form_error('keterangan_proses'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('template_sk') ? ' error' : ''; ?>">
                <?php echo form_label(lang('tte_field_template_sk'), 'template_sk', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <div id="form_upload">
                        <input id="template_sk" name="template_sk" class="file" type="file" data-preview-file-type="pdf" title="Silahkan Pilih file">
                      </div>
                    
                    <span class='help-inline'>Template file dokumen (.doc)</span>
                </div>
            </div>
        </fieldset>
        </div>
            <div class="box-footer">
            <input type='button' name='save' id="btn_save" class='btn btn-primary' value="Simpan Proses TTE" />
            <?php echo lang('bf_or'); ?>
            <?php echo anchor(SITE_AREA . '/masters/tte', lang('tte_cancel'), 'class="btn btn-warning"'); ?>
            
        </div>
    <?php echo form_close(); ?>
</div>
<script src="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/themes/explorer/theme.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/js/fileinput.js" type="text/javascript"></script>

<script type="text/javascript">
  function submitdata(){
    
    var the_data = new FormData(document.getElementById("submit_form"));
    $.ajax({
        url: "<?php echo base_url('admin/masters/tte/act_saveproses'); ?>",
        type: "POST",
        data: the_data,
        enctype: 'multipart/form-data',
        processData: false, // tell jQuery not to process the data
        contentType: false, // tell jQuery not to set contentType
        dataType: 'JSON',

        beforeSend: function (xhr) {
            //$("#loading-all").show();
        },
        success: function (response) {
            if(response.status){
                swal("Sukses",response.msg,"success");
                window.location.href = "<?php echo base_url(); ?>admin/masters/tte";
            }else{
                swal("Ada kesalahan",response.msg,"error");
            }
        }
    });
    
    return false; 
  } 
$('body').on('click','#btn_save',function () { 
  submitdata();
});
 $("#penandatangan_sk").select2({
        placeholder: 'Cari Penandatangan.....',
        width: '100%',
        minimumInputLength: 3,
        allowClear: true,
        ajax: {
            url: '<?php echo site_url("pegawai/kepegawaian/ajax_nama_pejabat");?>',
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