<?php 
    $id = isset($detail_riwayat->ID) ? $detail_riwayat->ID : '';
?>
<div class='box box-warning' id="form-istri-add">
    <div class="box-body">
        <div class="messages">
        </div>
    <?php echo form_open($this->uri->uri_string(), 'id="frmistri"'); ?>
        <fieldset>
            <input id='id_data' type='hidden' class="form-control" name='id_data' maxlength='32' value="<?php echo set_value('id_data', isset($id) ? trim($id) : ''); ?>" />
            <input id='PNS_ID' type='hidden' class="form-control" name='PNS_ID' maxlength='32' value="<?php echo set_value('PNS_ID', isset($PNS_ID) ? trim($PNS_ID) : ''); ?>" />
            <input nama='PNS_NIP' type='hidden' nama="">
            <div class="control-group<?php echo form_error('PNS') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label('PNS', 'PNS', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input type="checkbox" <?php echo $view ? "":"disabled"; ?> value="1" name="PNS" <?php if(isset($detail_riwayat->PNS))  echo  ($detail_riwayat->PNS=="1") ? "checked" : ""; ?>>
                     
                </div>
            </div>   
             
             
            <div class="control-group<?php echo form_error('NAMA') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("NAMA", 'NAMA', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='NAMA' type='text' <?php echo $view ? "":"disabled"; ?> class="form-control" name='NAMA' maxlength='32' value="<?php echo set_value('NAMA', isset($detail_riwayat->NAMA) ? $detail_riwayat->NAMA : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('NAMA'); ?></span>
                </div>
            </div>
            <div class="control-group col-sm-3">
                <label for="inputNAMA" class="control-label">TANGGAL NIKAH</label>
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                    <input type='text' <?php echo $view ? "":"disabled"; ?> class="form-control pull-right datepicker" name='TANGGAL_MENIKAH'  value="<?php echo set_value('TANGGAL_MENIKAH', isset($detail_riwayat->TANGGAL_MENIKAH) ? $detail_riwayat->TANGGAL_MENIKAH : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('TANGGAL_MENIKAH'); ?></span>
                </div>
            </div> 
            <div class="control-group<?php echo form_error('AKTE_NIKAH') ? ' error' : ''; ?> col-sm-9">
                <?php echo form_label("AKTE_NIKAH", 'AKTE_NIKAH', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='AKTE_NIKAH' <?php echo $view ? "":"disabled"; ?> type='text' class="form-control" name='AKTE_NIKAH' maxlength='32' value="<?php echo set_value('AKTE_NIKAH', isset($detail_riwayat->AKTE_NIKAH) ? $detail_riwayat->AKTE_NIKAH : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('AKTE_NIKAH'); ?></span>
                </div>
            </div>     
            
            

            <div class="control-group col-sm-3">
                <label for="inputNAMA" class="control-label">TANGGAL MENINGGAL</label>
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                    <input type='text' <?php echo $view ? "":"disabled"; ?> class="form-control pull-right datepicker" name='TANGGAL_MENINGGAL'  value="<?php echo set_value('TANGGAL_MENINGGAL', isset($detail_riwayat->TANGGAL_MENINGGAL) ? $detail_riwayat->TANGGAL_MENINGGAL : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('TANGGAL_MENINGGAL'); ?></span>
                </div>
            </div> 
            <div class="control-group<?php echo form_error('AKTE_MENINGGAL') ? ' error' : ''; ?> col-sm-9">
                <?php echo form_label("AKTE_MENINGGAL", 'AKTE_MENINGGAL', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='AKTE_MENINGGAL' <?php echo $view ? "":"disabled"; ?> type='text' class="form-control" name='AKTE_MENINGGAL' maxlength='32' value="<?php echo set_value('AKTE_MENINGGAL', isset($detail_riwayat->AKTE_MENINGGAL) ? $detail_riwayat->AKTE_MENINGGAL : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('AKTE_MENINGGAL'); ?></span>
                </div>
            </div>
            
            <div class="control-group col-sm-3">
                <label for="inputNAMA" class="control-label">TANGGAL CERAI</label>
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                    <input type='text' <?php echo $view ? "":"disabled"; ?> class="form-control pull-right datepicker" name='TANGGAL_CERAI'  value="<?php echo set_value('TANGGAL_CERAI', isset($detail_riwayat->TANGGAL_CERAI) ? $detail_riwayat->TANGGAL_CERAI : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('TANGGAL_CERAI'); ?></span>
                </div>
            </div> 
            <div class="control-group<?php echo form_error('AKTE_CERAI') ? ' error' : ''; ?> col-sm-9">
                <?php echo form_label("AKTE_CERAI", 'AKTE_CERAI', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='AKTE_CERAI' <?php echo $view ? "":"disabled"; ?> type='text' class="form-control" name='AKTE_CERAI' maxlength='32' value="<?php echo set_value('AKTE_CERAI', isset($detail_riwayat->AKTE_CERAI) ? $detail_riwayat->AKTE_CERAI : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('AKTE_CERAI'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('KARSUS') ? ' error' : ''; ?> col-sm-6">
                <?php echo form_label("KARSUS", 'KARSUS', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='KARSUS' <?php echo $view ? "":"disabled"; ?> type='text' class="form-control" name='KARSUS' maxlength='32' value="<?php echo set_value('KARSUS', isset($detail_riwayat->KARSUS) ? $detail_riwayat->KARSUS : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('KARSUS'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('STATUS') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label('STATUS', 'STATUS', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select name="STATUS" <?php echo $view ? "":"disabled"; ?> id="STATUS" class="form-control">
                        <option value="">-- Silahkan Pilih --</option>
                        <option value="1" <?php if(isset($detail_riwayat->STATUS))  echo  ($detail_riwayat->STATUS=="1") ? "selected" : ""; ?>>MENIKAH</option> 
                        <option value="2" <?php if(isset($detail_riwayat->STATUS))  echo  ($detail_riwayat->STATUS=="2") ? "selected" : ""; ?>>CERAI</option>
                        <option value="3" <?php if(isset($detail_riwayat->STATUS))  echo  ($detail_riwayat->STATUS=="3") ? "selected" : ""; ?>>JANDA/DUDA</option>
                    </select>
                    <span class='help-inline'><?php echo form_error('STATUS'); ?></span>
                </div>
            </div>     
        </div>
        <?php if($view){ ?>
        <div class="box-footer">
            <input type='submit' name='save' id="btnsaveistri" class='btn btn-primary' value="Simpan Data" /> 
        </div>
        <?php } ?>
    <?php echo form_close(); ?>
</div>
<script src="<?php echo base_url(); ?>themes/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
<script>
    var form = $("#form-istri-add");
     $('.datepicker').datepicker({
      autoclose: true,format: 'yyyy-mm-dd'
    });
</script>
<script>
    $("#btnsaveistri").click(function(){
        submitdataistri();
        return false; 
    }); 
    $("#frma").submit(function(){
        submitdataistri();
        return false; 
    }); 
    function submitdataistri(){
        
        var json_url = "<?php echo base_url() ?>pegawai/data_keluarga/saveistri";
         $.ajax({    
            type: "POST",
            url: json_url,
            data: $("#frmistri").serialize(),
            dataType: "json",
            success: function(data){ 
                if(data.success){
                    swal("Pemberitahuan!", data.msg, "success");
                    $("#modal-global").trigger("sukses-tambah-istri");
                    $("#modal-global").modal("hide");
                    loadkeluargaall();
                }
                else {
                    $("#form-istri-add .messages").empty().append(data.msg);
                }
            }});

        return false; 
    }
</script>