<?php 
    $id = isset($detail_riwayat->ID) ? $detail_riwayat->ID : '';
    $jk = ISSET($pegawai_data->JENIS_KELAMIN) ? $pegawai_data->JENIS_KELAMIN : "";
    $hubungan = ($jk == "M") ? "IBU" : "AYAH";
?>
<div class='box box-warning' id="form-anak-add">
    <div class="box-body">
        <div class="messages">
        </div>
    <?php echo form_open($this->uri->uri_string(), 'id="frmanak"'); ?>
        <fieldset>
            <input id='id_data' type='hidden' class="form-control" name='id_data' maxlength='32' value="<?php echo set_value('id_data', isset($id) ? trim($id) : ''); ?>" />
            <input id='PNS_ID' type='hidden' class="form-control" name='PNS_ID' maxlength='32' value="<?php echo set_value('PNS_ID', isset($PNS_ID) ? trim($PNS_ID) : ''); ?>" />
            <input nama='PNS_NIP' type='hidden' nama="">
            <div class="control-group<?php echo form_error('PASANGAN') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label($hubungan, 'PASANGAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select name="PASANGAN" <?php echo $view ? "":"disabled"; ?> id="PASANGAN" class="form-control">
                        <option value="">-- Silahkan Pilih --</option>
                        <?php if (isset($data_pasangans) && is_array($data_pasangans) && count($data_pasangans)):?>
                        <?php foreach($data_pasangans as $record):?>
                            <option value="<?php echo $record->ID?>" <?php if(isset($detail_riwayat->PASANGAN))  echo  ($detail_riwayat->PASANGAN==$record->ID) ? "selected" : ""; ?>><?php echo $record->NAMA; ?></option>
                            <?php endforeach;?>
                        <?php endif;?>
                    </select>
                    <span class='help-inline'><?php echo form_error('PASANGAN'); ?></span>
                </div>
            </div> 
            <div class="control-group<?php echo form_error('NAMA') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("NAMA", 'NAMA', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='NAMA' <?php echo $view ? "":"disabled"; ?> type='text' class="form-control" name='NAMA' maxlength='32' value="<?php echo set_value('NAMA', isset($detail_riwayat->NAMA) ? $detail_riwayat->NAMA : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('NAMA'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('JENIS_KELAMIN') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label('JENIS_KELAMIN', 'JENIS_KELAMIN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select name="JENIS_KELAMIN" <?php echo $view ? "":"disabled"; ?> id="JENIS_KELAMIN" class="form-control">
                        <option value="">-- Silahkan Pilih --</option>
                        <option value="M" <?php if(isset($detail_riwayat->JENIS_KELAMIN))  echo  ($detail_riwayat->JENIS_KELAMIN=="M") ? "selected" : ""; ?>>Laki-laki</option> 
                        <option value="F" <?php if(isset($detail_riwayat->JENIS_KELAMIN))  echo  ($detail_riwayat->JENIS_KELAMIN=="F") ? "selected" : ""; ?>>Perempuan</option>
                    </select>
                    <span class='help-inline'><?php echo form_error('JENIS_KELAMIN'); ?></span>
                </div>
            </div>    
            <div class="control-group col-sm-3">
                <label for="inputNAMA" class="control-label">TANGGAL LAHIR</label>
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                    <input type='text' <?php echo $view ? "":"disabled"; ?> class="form-control pull-right datepicker" name='TANGGAL_LAHIR'  value="<?php echo set_value('TANGGAL_LAHIR', isset($detail_riwayat->TANGGAL_LAHIR) ? $detail_riwayat->TANGGAL_LAHIR : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('TANGGAL_LAHIR'); ?></span>
                </div>
            </div> 
            <div class="control-group<?php echo form_error('TEMPAT_LAHIR') ? ' error' : ''; ?> col-sm-9">
                <?php echo form_label("TEMPAT LAHIR", 'TEMPAT_LAHIR', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='TEMPAT_LAHIR' <?php echo $view ? "":"disabled"; ?> type='text' class="form-control" name='TEMPAT_LAHIR' maxlength='32' value="<?php echo set_value('TEMPAT_LAHIR', isset($detail_riwayat->TEMPAT_LAHIR) ? $detail_riwayat->TEMPAT_LAHIR : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('TEMPAT_LAHIR'); ?></span>
                </div>
            </div>     
               
            <div class="control-group<?php echo form_error('STATUS') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label('STATUS', 'STATUS', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select name="STATUS_ANAK" <?php echo $view ? "":"disabled"; ?> id="STATUS_ANAK" class="form-control">
                        <option value="">-- Silahkan Pilih --</option>
                        <option value="1" <?php if(isset($detail_riwayat->STATUS_ANAK))  echo  ($detail_riwayat->STATUS_ANAK=="1") ? "selected" : ""; ?>>KANDUNG</option> 
                        <option value="2" <?php if(isset($detail_riwayat->STATUS_ANAK))  echo  ($detail_riwayat->STATUS_ANAK=="2") ? "selected" : ""; ?>>ANGKAT</option>
                    </select>
                    <span class='help-inline'><?php echo form_error('STATUS'); ?></span>
                </div>
            </div>     
        </div>
        <?php if($view){ ?>
        <div class="box-footer">
            <input type='submit' name='save' id="btnsaveanak" class='btn btn-primary' value="Simpan Data" /> 
        </div>
        <?php } ?>
    <?php echo form_close(); ?>
</div>
<script src="<?php echo base_url(); ?>themes/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
<script>
    var form = $("#form-anak-add");
     $('.datepicker').datepicker({
      autoclose: true,format: 'yyyy-mm-dd'
    });
</script>
<script>
    $("#btnsaveanak").click(function(){
        submitdataanak();
        return false; 
    }); 
    $("#frma").submit(function(){
        submitdataanak();
        return false; 
    }); 
    function submitdataanak(){
        
        var json_url = "<?php echo base_url() ?>pegawai/data_keluarga/saveanak";
         $.ajax({    
            type: "POST",
            url: json_url,
            data: $("#frmanak").serialize(),
            dataType: "json",
            success: function(data){ 
                if(data.success){
                    swal("Pemberitahuan!", data.msg, "success");
                    $("#modal-global").trigger("sukses-tambah-anak");
                    $("#modal-global").modal("hide");
                    loadkeluargaall();
                }
                else {
                    $("#form-anak-add .messages").empty().append(data.msg);
                }
            }});
        return false; 
    }
</script>