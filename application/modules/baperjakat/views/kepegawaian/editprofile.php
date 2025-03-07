<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/select2/select2.min.css">
<!-- daterange picker -->
<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/daterangepicker/daterangepicker.css">
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/datepicker/datepicker3.css">

<?php

if (validation_errors()) :
?>
<div class='alert alert-block alert-error fade in'>
    <a class='close' data-dismiss='alert'>&times;</a>
    <h4 class='alert-heading'>
        <?php echo lang('pegawai_errors_message'); ?>
    </h4>
    <?php echo validation_errors(); ?>
</div>
<?php
endif;
$id = isset($kandidat->ID) ? $kandidat->ID : '';

?>
<div class='alert alert-block alert-warning fade in'>
                    <a class='close' data-dismiss='alert'>&times;</a>
                    <h4 class='alert-heading'>
                        Perhatian
                    </h4>
                    Silahkan pilih jabatan dibawah, untuk mengisi data petajabatan <br>
                    data akan otomatis masuk pada tabel riwayat jabatan pegawai tersebut
                </div>
<div class="messages"></div>
<div class='box box-primary'>
    
    <div class="box-body">
    <?php echo form_open($this->uri->uri_string(), 'id="frm"'); ?>
        <fieldset>
            <div class="control-group<?php echo form_error('NAMA_JABATAN') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Jabatan", 'NAMA_JABATAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <?php echo set_value('NAMA_JABATAN', isset($kandidat->NAMA_JABATAN) ? $kandidat->NAMA_JABATAN : ''); ?>
                    <span class='help-inline'><?php echo form_error('NAMA_JABATAN'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('NO_SK_PELANTIKAN') ? ' error' : ''; ?> col-sm-6">
                <?php echo form_label("NO SK", 'NO_SK_PELANTIKAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='NIP' class="form-control" type='hidden' name='NIP' maxlength='20' value="<?php echo set_value('NIP', isset($kandidat->NIP) ? $kandidat->NIP : ''); ?>" />
                    <input id='UNOR_ID' class="form-control" type='hidden' name='UNOR_ID' maxlength='20' value="<?php echo set_value('UNOR_ID', isset($kandidat->UNOR_ID) ? $kandidat->UNOR_ID : ''); ?>" />
                    <input id='NO_SK_PELANTIKAN' class="form-control" type='text' name='NO_SK_PELANTIKAN' maxlength='20' value="<?php echo set_value('NO_SK_PELANTIKAN', isset($kandidat->NO_SK_PELANTIKAN) ? $kandidat->NO_SK_PELANTIKAN : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('NO_SK_PELANTIKAN'); ?></span>
                </div>
            </div>
            <div class="control-group col-sm-6">
                <label for="inputNAMA" class="control-label">TGL SK</label>
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                    <input type='text' class="form-control pull-right datepicker" name='TGL_SK'  value="<?php echo set_value('TGL_SK', isset($kandidat->TGL_SK) ? $kandidat->TGL_SK : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('TGL_PELANTIKAN'); ?></span>
                </div>
            </div> 
            <div class="control-group col-sm-6">
                <label for="inputNAMA" class="control-label">TGL PELANTIKAN</label>
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                    <input type='text' class="form-control pull-right datepicker" name='TGL_PELANTIKAN'  value="<?php echo set_value('TGL_PELANTIKAN', isset($kandidat->TGL_PELANTIKAN) ? $kandidat->TGL_PELANTIKAN : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('TGL_PELANTIKAN'); ?></span>
                </div>
            </div> 
            <div class="control-group col-sm-6">
                <label for="inputNAMA" class="control-label">TMT JABATAN</label>
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                    <input type='text' class="form-control pull-right datepicker" name='TMT_JABATAN'  value="<?php echo set_value('TMT_JABATAN', isset($kandidat->TMT_JABATAN) ? $kandidat->TMT_JABATAN : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('TMT_JABATAN'); ?></span>
                </div>
            </div> 
        </fieldset>
            <fieldset>
                <legend>Petajabatan</legend>
                
                <div class="control-group<?php echo form_error('ID_JABATAN') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label('JABATAN', 'ID_JABATAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <div class='slcjabatan'>
                        <select name="ID_JABATAN" id="ID_JABATAN" class="form-control select2  col-sm-12 slcjabatan" width="100%">
                            <option value="">-- Silahkan Pilih --</option>
                            <?php if (isset($jabatans) && is_array($jabatans) && count($jabatans)):?>
                            <?php foreach($jabatans as $record):?>
                                <option value="<?php echo $record->KODE_JABATAN?>" <?php if(isset($detail_riwayat->ID_JABATAN))  echo  (trim($detail_riwayat->ID_JABATAN)==trim($record->KODE_JABATAN)) ? "selected" : ""; ?>><?php echo $record->NAMA_JABATAN; ?></option>
                                <?php endforeach;?>
                            <?php endif;?>
                        </select>
                    </div>
                </div>
            </div>   
            </fieldset>
         </div>
        <div class="box-footer">
            <input type='button' name='save' id="btnsave" class='btn btn-primary' value="Simpan" />
            <?php echo lang('bf_or'); ?>
            <input type='button' name='cancel' class='btn btn-warning btn-cancel' value="Cancel" />
            
        </div>
    <?php echo form_close(); ?>
</div>
<script src="<?php echo base_url(); ?>themes/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
<script>
    //Date picker
    $('.datepicker').datepicker({
      autoclose: true,format: 'yyyy-mm-dd'
    });
    $("#btnsave").click(function(){
        submitdata();
        return false; 
    }); 
    function submitdata(){
        
        var json_url = "<?php echo base_url() ?>admin/kepegawaian/baperjakat/saveprofile/<?php echo $id; ?>";
         $.ajax({    
            type: "POST",
            url: json_url,
            data: $("#frm").serialize(),
            dataType: "json",
            success: function(data){ 
                if(data.success){
                    swal("Pemberitahuan!", data.msg, "success");
                     $("#modal-global").modal("hide");
                }
                else {
                    $(".messages").empty().append(data.msg);
                }
            }});
        return false; 
    }
</script>
