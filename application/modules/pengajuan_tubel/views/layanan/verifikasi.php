 
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
<div class='box box-primary'>
    <div class="messages">
        </div>
    <div class="box-body">
        <?php echo form_open($this->uri->uri_string(), 'class="form-horizontal" id="frm"'); ?>
            <fieldset>
                 
                <div class="control-group<?php echo form_error('UNIVERSITAS') ? ' error' : ''; ?>">
                    <?php echo form_label(lang('pengajuan_tubel_field_UNIVERSITAS'), 'UNIVERSITAS', array('class' => 'control-label')); ?>
                    <div class='controls'>
                        <input id='ID' type='hidden' class="form-control" name='ID' maxlength='100' value="<?php echo set_value('ID', isset($pengajuan_tubel->ID) ? $pengajuan_tubel->ID : ''); ?>" />
                        <?php echo set_value('UNIVERSITAS', isset($pengajuan_tubel->UNIVERSITAS) ? $pengajuan_tubel->UNIVERSITAS : ''); ?>
                        <span class='help-inline'><?php echo form_error('UNIVERSITAS'); ?></span>
                    </div>
                </div>

                <div class="control-group<?php echo form_error('FAKULTAS') ? ' error' : ''; ?>">
                    <?php echo form_label(lang('pengajuan_tubel_field_FAKULTAS'), 'FAKULTAS', array('class' => 'control-label')); ?>
                    <div class='controls'>
                        <?php echo set_value('FAKULTAS', isset($pengajuan_tubel->FAKULTAS) ? $pengajuan_tubel->FAKULTAS : ''); ?>
                        <span class='help-inline'><?php echo form_error('FAKULTAS'); ?></span>
                    </div>
                </div>

                <div class="control-group<?php echo form_error('PRODI') ? ' error' : ''; ?>">
                    <?php echo form_label(lang('pengajuan_tubel_field_PRODI'), 'PRODI', array('class' => 'control-label')); ?>
                    <div class='controls'>
                        <?php echo set_value('PRODI', isset($pengajuan_tubel->PRODI) ? $pengajuan_tubel->PRODI : ''); ?>
                        <span class='help-inline'><?php echo form_error('PRODI'); ?></span>
                    </div>
                </div>

                <div class="control-group<?php echo form_error('BEASISWA') ? ' error' : ''; ?>">
                    <?php echo form_label(lang('pengajuan_tubel_field_BEASISWA'), 'BEASISWA', array('class' => 'control-label')); ?>
                    <div class='controls'>
                         
                            <?php echo $pengajuan_tubel->BEASISWA == "1" ? "Beasiswa" : ""; ?>
                            <?php echo $pengajuan_tubel->BEASISWA == "2" ? "Mandiri" : ""; ?>
                         
                    </div>
                </div>

                <div class="control-group<?php echo form_error('PEMBERI_BEASISWA') ? ' error' : ''; ?>">
                    <?php echo form_label(lang('pengajuan_tubel_field_PEMBERI_BEASISWA'), 'PEMBERI_BEASISWA', array('class' => 'control-label')); ?>
                    <div class='controls'>
                        <?php echo set_value('PEMBERI_BEASISWA', isset($pengajuan_tubel->PEMBERI_BEASISWA) ? $pengajuan_tubel->PEMBERI_BEASISWA : ''); ?>
                        <span class='help-inline'>isi jika beasiswa</span>
                    </div>
                </div>

                <div class="control-group<?php echo form_error('JENJANG') ? ' error' : ''; ?>">
                    <?php echo form_label(lang('pengajuan_tubel_field_JENJANG'), 'JENJANG', array('class' => 'control-label')); ?>
                    <div class='controls'>
                            <?php echo $pengajuan_tubel->JENJANG == "S1" ? "S1" : ""; ?>
                            <?php echo $pengajuan_tubel->JENJANG == "S2" ? "S2" : ""; ?>
                            <?php echo $pengajuan_tubel->JENJANG == "S3" ? "S3" : ""; ?>
                        <span class='help-inline'><?php echo form_error('JENJANG'); ?></span>
                    </div>
                </div>

                <div class="control-group<?php echo form_error('NEGARA') ? ' error' : ''; ?>">
                    <?php echo form_label(lang('pengajuan_tubel_field_NEGARA'), 'NEGARA', array('class' => 'control-label')); ?>
                    <div class='controls'>
                        <?php echo set_value('NEGARA', isset($pengajuan_tubel->NEGARA) ? $pengajuan_tubel->NEGARA : ''); ?>
                        <span class='help-inline'><?php echo form_error('NEGARA'); ?></span>
                    </div>
                </div>
                <div class="control-group<?php echo form_error('STATUS') ? ' error' : ''; ?>">
                    <?php echo form_label("STATUS", 'STATUS', array('class' => 'control-label')); ?>
                    <div class='controls'>
                        <select name="STATUS" class="form-control">
                            <option value="">Silahkan Pilih</option>
                            <option value="2" <?php echo $pengajuan_tubel->STATUS == "2" ? "selected" : ""; ?>>Diterima</option>
                            <option value="3" <?php echo $pengajuan_tubel->STATUS == "3" ? "selected" : ""; ?>>Ditolak</option>
                        </select>
                        <span class='help-inline'><?php echo form_error('STATUS'); ?></span>
                    </div>
                </div>
                <br>
                <div class='alert alert-block alert-info fade in'>
                    <a class='close' data-dismiss='alert'>&times;</a>
                    <h4 class='alert-heading'>
                        Perhatian
                    </h4>
                    Jika pengajuan disetujui, data akan masuk pada data riwayat tugas belajar.
                </div>
            </fieldset>
            </div>
            <div class="box-footer">
            <fieldset class='form-actions'>
                <div class="messages">
                </div>
                <input type='submit' name='save' id="btnsave" class='btn btn-primary' value="Simpan" />
                <?php echo lang('bf_or'); ?>
                <input type='button' name='cancel' id="btncancel" class='btn btn-warning' value="Cancel" />
                
            </fieldset>
        <?php echo form_close(); ?>
    </div>
</div>
 <script>
    $("#btnsave").click(function(){
        submitdata();
        return false; 
    }); 
    $("#btncancel").click(function(){
        $("#modal-global").modal("hide");
        return false; 
    }); 
    function submitdata(){
        
        var json_url = "<?php echo base_url() ?>admin/layanan/pengajuan_tubel/save_verifikasi";
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
                }
            }});
        return false; 
    }
</script>