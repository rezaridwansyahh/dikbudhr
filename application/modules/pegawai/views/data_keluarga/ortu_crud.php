<?php 
    $id = isset($detail_riwayat->ID) ? $detail_riwayat->ID : '';
?>
<div class='box box-warning' id="form-ortu-add">
    <div class="box-body">
        <div class="messages">
        </div>
    <?php echo form_open($this->uri->uri_string(), 'id="frmortu"'); ?>
        <fieldset>
            <input id='id_data' type='hidden' class="form-control" name='id_data' maxlength='32' value="<?php echo set_value('id_data', isset($id) ? trim($id) : ''); ?>" />
            <input id='PNS_ID' type='hidden' class="form-control" name='PNS_ID' maxlength='32' value="<?php echo set_value('PNS_ID', isset($PNS_ID) ? trim($PNS_ID) : ''); ?>" />
            <input nama='PNS_NIP' type='hidden' nama="">
            
            <div class="control-group<?php echo form_error('HUBUNGAN') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label('HUBUNGAN', 'HUBUNGAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select name="HUBUNGAN" id="HUBUNGAN" class="form-control">
                        <option value="">-- Silahkan Pilih --</option>
                        <option value="1" <?php if(isset($detail_riwayat->HUBUNGAN))  echo  ($detail_riwayat->HUBUNGAN=="1") ? "selected" : ""; ?>>AYAH</option>
                        <option value="2" <?php if(isset($detail_riwayat->HUBUNGAN))  echo  ($detail_riwayat->HUBUNGAN=="2") ? "selected" : ""; ?>>IBU</option> 
                    </select>
                    <span class='help-inline'><?php echo form_error('HUBUNGAN'); ?></span>
                </div>
            </div>     
            <div class="control-group<?php echo form_error('GELAR_DEPAN') ? ' error' : ''; ?> col-sm-3">
                <?php echo form_label("GELAR DEPAN", 'GELAR_DEPAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='GELAR_DEPAN' type='text' class="form-control" name='GELAR_DEPAN' maxlength='32' value="<?php echo set_value('GELAR_DEPAN', isset($detail_riwayat->GELAR_DEPAN) ? $detail_riwayat->GELAR_DEPAN : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('GELAR_DEPAN'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('NAMA') ? ' error' : ''; ?> col-sm-6">
                <?php echo form_label("NAMA", 'NAMA', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='NAMA' type='text' class="form-control" name='NAMA' maxlength='32' value="<?php echo set_value('NAMA', isset($detail_riwayat->NAMA) ? $detail_riwayat->NAMA : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('NAMA'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('GELAR_BELAKANG') ? ' error' : ''; ?> col-sm-3">
                <?php echo form_label("GELAR BELAKANG", 'GELAR_BELAKANG', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='GELAR_BELAKANG' type='text' class="form-control" name='GELAR_BELAKANG' maxlength='32' value="<?php echo set_value('GELAR_BELAKANG', isset($detail_riwayat->GELAR_BELAKANG) ? $detail_riwayat->GELAR_BELAKANG : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('GELAR_BELAKANG'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('TEMPAT LAHIR') ? ' error' : ''; ?> col-sm-9">
                <?php echo form_label("TEMPAT_LAHIR", 'TEMPAT_LAHIR', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='TEMPAT_LAHIR' type='text' class="form-control" name='TEMPAT_LAHIR' maxlength='32' value="<?php echo set_value('TEMPAT_LAHIR', isset($detail_riwayat->TEMPAT_LAHIR) ? $detail_riwayat->TEMPAT_LAHIR : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('TEMPAT_LAHIR'); ?></span>
                </div>
            </div>     
            <div class="control-group col-sm-3">
                <label for="inputNAMA" class="control-label">TANGGAL LAHIR</label>
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                    <input type='text' class="form-control pull-right datepicker" name='TANGGAL_LAHIR'  value="<?php echo set_value('TANGGAL_LAHIR', isset($detail_riwayat->TANGGAL_LAHIR) ? $detail_riwayat->TANGGAL_LAHIR : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('TANGGAL_LAHIR'); ?></span>
                </div>
            </div> 
            

            <div class="control-group<?php echo form_error('AGAMA') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label('AGAMA', 'AGAMA', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select name="AGAMA" id="AGAMA" class="form-control">
                        <option value="">-- Silahkan Pilih --</option>
                        <?php if (isset($agamas) && is_array($agamas) && count($agamas)):?>
                        <?php foreach($agamas as $record):?>
                            <option value="<?php echo $record->ID?>" <?php if(isset($detail_riwayat->AGAMA))  echo  ($detail_riwayat->AGAMA==$record->ID) ? "selected" : ""; ?>><?php echo $record->NAMA; ?></option>
                            <?php endforeach;?>
                        <?php endif;?>
                    </select>
                    <span class='help-inline'><?php echo form_error('AGAMA'); ?></span>
                </div>
            </div> 
            
            <div class="control-group<?php echo form_error('EMAIL') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("EMAIL", 'EMAIL', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='EMAIL' type='text' class="form-control" name='EMAIL' maxlength='32' value="<?php echo set_value('EMAIL', isset($detail_riwayat->EMAIL) ? $detail_riwayat->EMAIL : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('EMAIL'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('JENISDOKUMEN_ID') ? ' error' : ''; ?> col-sm-6">
                <?php echo form_label('JENIS DOKUMEN ID', 'JENIS DOKUMEN_ID', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select name="JENIS_DOKUMEN_ID" id="JENIS_DOKUMEN_ID" class="form-control">
                        <option value="">-- Silahkan Pilih --</option>
                        <?php if (isset($JENIS_DOKUMEN_IDs) && is_array($JENIS_DOKUMEN_IDs) && count($JENIS_DOKUMEN_IDs)):?>
                        <?php foreach($JENIS_DOKUMEN_IDs as $record):?>
                            <option value="<?php echo $record->ID?>" <?php if(isset($detail_riwayat->JENIS_DOKUMEN_ID))  echo  ($detail_riwayat->JENIS_DOKUMEN_ID==$record->ID) ? "selected" : ""; ?>><?php echo $record->NAMA; ?></option>
                            <?php endforeach;?>
                        <?php endif;?>
                    </select>
                    <span class='help-inline'><?php echo form_error('JENIS_DOKUMEN_ID'); ?></span>
                </div>
            </div> 
            <div class="control-group<?php echo form_error('NO_DOKUMEN_ID') ? ' error' : ''; ?> col-sm-6">
                <?php echo form_label("NO DOKUMEN ID", 'NO_DOKUMEN_ID', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='NO_DOKUMEN_ID' type='text' class="form-control" name='NO_DOKUMEN_ID' maxlength='32' value="<?php echo set_value('NO_DOKUMEN_ID', isset($detail_riwayat->NO_DOKUMEN_ID) ? $detail_riwayat->NO_DOKUMEN_ID : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('NO_DOKUMEN_ID'); ?></span>
                </div>
            </div>
        </div>
        <div class="box-footer">
            <input type='submit' name='save' id="btnsaveortu" class='btn btn-primary' value="Simpan Data" /> 
        </div>
    <?php echo form_close(); ?>
</div>
<script src="<?php echo base_url(); ?>themes/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
<script>
    var form = $("#form-ortu-add");
     $('.datepicker').datepicker({
      autoclose: true,format: 'yyyy-mm-dd'
    });
</script>
<script>
    $("#btnsaveortu").click(function(){
        submitdataortu();
        return false; 
    }); 
    $("#frma").submit(function(){
        submitdataortu();
        return false; 
    }); 
    function submitdataortu(){
        //alert("asd");
        var json_url = "<?php echo base_url() ?>pegawai/data_keluarga/saveortu";
         $.ajax({    
            type: "POST",
            url: json_url,
            data: $("#frmortu").serialize(),
            dataType: "json",
            success: function(data){ 
                if(data.success){
                    swal("Pemberitahuan!", data.msg, "success");
                    $("#modal-global").trigger("sukses-tambah-ortu");
                    $("#modal-global").modal("hide");
                    loadkeluargaall();
                }
                else {
                    $("#form-ortu-add .messages").empty().append(data.msg);
                }
            }});
        return false; 
    }
</script>