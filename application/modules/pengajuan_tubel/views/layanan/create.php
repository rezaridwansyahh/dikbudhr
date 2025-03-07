 
<?php

if (validation_errors()) :
?>
<div class='alert alert-block alert-error fade in'>
    <a class='close' data-dismiss='alert'>&times;</a>
    <h4 class='alert-heading'>
        <?php echo lang('baperjakat_errors_message'); ?>
    </h4>
    <?php echo validation_errors(); ?>
</div>
<?php
endif;

$id = isset($pengajuan_tubel->ID) ? $pengajuan_tubel->ID : '';

?>
<div class="messages">
        </div>
<div class='box box-primary'>
    
    <div class="box-body">
        <?php echo form_open($this->uri->uri_string(), 'class="form-horizontal" id="frm"'); ?>
            <fieldset>
                 
                <div class="control-group<?php echo form_error('UNIVERSITAS') ? ' error' : ''; ?> col-sm-12">
                    <?php echo form_label(lang('pengajuan_tubel_field_UNIVERSITAS'), 'UNIVERSITAS', array('class' => 'control-label')); ?>
                    <div class='controls'>
                        <input id='UNIVERSITAS' type='text' class="form-control" name='UNIVERSITAS' maxlength='100' value="<?php echo set_value('UNIVERSITAS', isset($pengajuan_tubel->UNIVERSITAS) ? $pengajuan_tubel->UNIVERSITAS : ''); ?>" />
                        <span class='help-inline'>Ditulis dengan lengkap(tidak menggunakan akronim), contoh: "Universitas Indonesia"</span>
                    </div>
                </div>

                <div class="control-group<?php echo form_error('FAKULTAS') ? ' error' : ''; ?> col-sm-12">
                    <?php echo form_label(lang('pengajuan_tubel_field_FAKULTAS'), 'FAKULTAS', array('class' => 'control-label')); ?>
                    <div class='controls'>
                        <input id='FAKULTAS' type='text' class="form-control" name='FAKULTAS' maxlength='100' value="<?php echo set_value('FAKULTAS', isset($pengajuan_tubel->FAKULTAS) ? $pengajuan_tubel->FAKULTAS : ''); ?>" />
                        <span class='help-inline'>Ditulis dengan lengkap(tidak menggunakan akronim)</span>
                    </div>
                </div>

                <div class="control-group<?php echo form_error('PRODI') ? ' error' : ''; ?> col-sm-12">
                    <?php echo form_label(lang('pengajuan_tubel_field_PRODI'), 'PRODI', array('class' => 'control-label')); ?>
                    <div class='controls'>
                        <input id='PRODI' type='text' class="form-control" name='PRODI' maxlength='100' value="<?php echo set_value('PRODI', isset($pengajuan_tubel->PRODI) ? $pengajuan_tubel->PRODI : ''); ?>" />
                        <span class='help-inline'>Ditulis dengan lengkap(tidak menggunakan akronim)</span>
                    </div>
                </div>
 
                <div class="control-group<?php echo form_error('PEMBERI_BEASISWA') ? ' error' : ''; ?> col-sm-12">
                    <?php echo form_label(lang('pengajuan_tubel_field_PEMBERI_BEASISWA'), 'PEMBERI_BEASISWA', array('class' => 'control-label')); ?>
                    <div class='controls'>
                        <input id='PEMBERI_BEASISWA' type='text' class="form-control" name='PEMBERI_BEASISWA' maxlength='100' value="<?php echo set_value('PEMBERI_BEASISWA', isset($pengajuan_tubel->PEMBERI_BEASISWA) ? $pengajuan_tubel->PEMBERI_BEASISWA : ''); ?>" />
                        <span class='help-inline'>Ditulis dengan lengkap(tidak menggunakan akronim)</span>
                    </div>
                </div>
                <div class="control-group<?php echo form_error('PEMBERI_BEASISWA') ? ' error' : ''; ?> col-sm-12">
                    <?php echo form_label(lang('pengajuan_tubel_field_PEMBERI_BEASISWA'), 'PEMBERI_BEASISWA', array('class' => 'control-label')); ?>
                    <div class='controls'>
                        <input id='PEMBERI_BEASISWA' type='text' class="form-control" name='PEMBERI_BEASISWA' maxlength='100' value="<?php echo set_value('PEMBERI_BEASISWA', isset($pengajuan_tubel->PEMBERI_BEASISWA) ? $pengajuan_tubel->PEMBERI_BEASISWA : ''); ?>" />
                    </div>
                </div>

                <div class="control-group<?php echo form_error('JENJANG') ? ' error' : ''; ?> col-sm-12">
                    <?php echo form_label(lang('pengajuan_tubel_field_JENJANG'), 'JENJANG', array('class' => 'control-label')); ?>
                    <div class='controls'>
                        <select name="JENJANG" id="JENJANG" class="form-control select2">
                            <option value="">-- Silahkan Pilih --</option>
                            <?php if (isset($tk_pendidikans) && is_array($tk_pendidikans) && count($tk_pendidikans)):?>
                            <?php foreach($tk_pendidikans as $record):?>
                                <option value="<?php echo $record->ID?>" <?php if(isset($pengajuan_tubel->JENJANG))  echo  (TRIM($pengajuan_tubel->JENJANG)==$record->ID) ? "selected" : ""; ?>><?php echo $record->NAMA; ?></option>
                                <?php endforeach;?>
                            <?php endif;?>
                        </select>
                         
                    </div>
                </div>

                <div class="control-group<?php echo form_error('NEGARA') ? ' error' : ''; ?> col-sm-12 col-sm-12">
                    <?php echo form_label(lang('pengajuan_tubel_field_NEGARA'), 'NEGARA', array('class' => 'control-label')); ?>
                    <div class='controls'>
                        <input id='NEGARA' type='text' class="form-control" name='NEGARA' maxlength='50' value="<?php echo set_value('NEGARA', isset($pengajuan_tubel->NEGARA) ? $pengajuan_tubel->NEGARA : ''); ?>" />
                        <span class='help-inline'><?php echo form_error('NEGARA'); ?></span>
                    </div>
                </div>
                <div class="control-group col-sm-6">
                    <label for="inputNAMA" class="control-label">DARI TANGGAL</label>
                    <div class="input-group date">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                        <input type='text' class="form-control pull-right datepicker" name='MULAI_BELAJAR'  value="<?php echo set_value('MULAI_BELAJAR', isset($detail_riwayat->MULAI_BELAJAR) ? $detail_riwayat->MULAI_BELAJAR : ''); ?>" />
                        <span class='help-inline'><?php echo form_error('MULAI_BELAJAR'); ?></span>
                    </div>
                </div> 
                <div class="control-group col-sm-6">
                    <label class="control-label">SAMPAI TANGGAL</label>
                    <div class="input-group date">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                        <input type='text' class="form-control pull-right datepicker" name='AKHIR_BELAJAR'  value="<?php echo set_value('AKHIR_BELAJAR', isset($detail_riwayat->AKHIR_BELAJAR) ? $detail_riwayat->AKHIR_BELAJAR : ''); ?>" />
                        <span class='help-inline'><?php echo form_error('AKHIR_BELAJAR'); ?></span>
                    </div>
                </div>   
               
            </fieldset>
            </div>
            <div class="box-footer">
            <fieldset class='form-actions'>
                <div class="messagesshortmsg">
                </div>
                <input type='submit' name='save' id="btnsave" class='btn btn-primary' value="Ajukan" />
                <?php echo lang('bf_or'); ?>
                <?php echo anchor(SITE_AREA . '/layanan/pengajuan_tubel', lang('pengajuan_tubel_cancel'), 'class="btn btn-warning" id="btncancel"'); ?>
                
            </fieldset>
        <?php echo form_close(); ?>
    </div>
</div>
 <script>
    $('.datepicker').datepicker({
      autoclose: true,format: 'yyyy-mm-dd'
    }).on("input change", function (e) {
        var date = $(this).datepicker('getDate');
    });
    $("#btnsave").click(function(){
        submitdata();
        return false; 
    }); 
    $("#btncancel").click(function(){
        $("#modal-global").modal("hide");
        return false; 
    }); 
     
    function submitdata(){
        
        var json_url = "<?php echo base_url() ?>admin/layanan/pengajuan_tubel/save_pengajuan_tubel";
         $.ajax({    
            type: "POST",
            url: json_url,
            data: $("#frm").serialize(),
            dataType: "json",
            success: function(data){ 
                if(data.success){
                    swal("Pemberitahuan!", data.msg, "success");
                    $("#modal-global").modal("hide");
                    $table.ajax.reload(null,true);
                }
                else {
                    $(".messages").empty().append(data.msg);
                    $(".messagesshortmsg").empty().append(data.shortmsg);
                    
                }
            }});
        return false; 
    }
</script>