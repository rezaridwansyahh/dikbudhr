<?php 
    $id = isset($data->id) ? $data->id : '';
?>
<div class='box box-warning' id="form-pendidikan-add">
    <?php echo form_open($module_url.'/do_proses_kgb', 'id="form-proses-kgb"'); ?>
	<div class="box-body">
        <div class="messages">
        </div>
        <fieldset>
            <input id='id' type='hidden' class="form-control" name='id' maxlength='32' value="<?php echo set_value('id', isset($selectMv) ? trim($selectMv) : ''); ?>" />
            
            <div class="control-group<?php echo form_error('no_sk') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("No SK", 'No SK', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='no_sk' type='text' class="form-control" name='no_sk'  value="<?php echo set_value('no_sk', isset($selectedData->no_sk) ? $selectedData->no_sk : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('no_sk'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('pejabat') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("NIP Pejabat", 'Pejabat', array('class' => 'control-label')); ?>
                <div class='controls'>

                    <input id='pejabat' type='text' class="form-control" name='pejabat'  value="<?php echo set_value('pejabat', $pejabat); ?>" />
                    <span class='help-inline'><?php echo form_error('pejabat'); ?>Contoh : 196203111984031004</span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('KPPN') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("KPPN", 'KPPN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select name="KPKN_ID" id="KPKN_ID" class="form-control select2">
                        <option value="">-- Silahkan Pilih --</option>
                        <?php if (isset($kpkns) && is_array($kpkns) && count($kpkns)):?>
                        <?php foreach($kpkns as $record):?>
                            <option value="<?php echo $record->ID?>" <?php if(isset($pegawai->KPKN_ID))  echo  ($pegawai->KPKN_ID==$record->ID) ? "selected" : ""; ?>><?php echo $record->NAMA; ?></option>
                            <?php endforeach;?>
                        <?php endif;?>
                    </select>
                    <span class='help-inline'><?php echo form_error('kppn'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('alasan') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Keterangan", 'Alasan', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='alasan' type='text' class="form-control" name='alasan'  value="<?php echo set_value('alasan', isset($selectedData->alasan) ? $selectedData->alasan : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('alasan'); ?></span>
                </div>
            </div>
        </div>
  		<div class="box-footer">
            <input type='submit' name='save' id="btnsave" class='btn btn-primary' value="Simpan Data" /> 
        </div>
    <?php echo form_close(); ?>
</div>
<script src="<?php echo base_url(); ?>themes/admin/plugins/datepicker/bootstrap-datepicker.js"></script>

<script>
   
	$("#form-proses-kgb").submit(function(){
		$.post($(this).attr("action"),$(this).serialize(),function(o){
            if(o.success){
                swal("Pemberitahuan!", o.message, "success");
                $("#modal-custom-global").trigger("sukses-proses-kgb");
                $("#modal-custom-global").modal("hide");
            }
            else {
                swal("Pemberitahuan!", o.message, "error");
            }
        },'json');
		return false; 
	});	
</script>
<script>
     $(".select2").select2({
        placeholder: 'Silahkan Pilih',
        width: "100%"
    });
</script>