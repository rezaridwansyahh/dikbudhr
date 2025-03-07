<?php 
    $id = isset($data->id) ? $data->id : '';
?>
<div class='box box-warning' id="form-pendidikan-add">
    <?php echo form_open($module_url.'/do_tunda_kgb', 'id="form-proses-kgb"'); ?>
	<div class="box-body">
        <div class="messages">
        </div>
        <fieldset>
            <input id='id' type='hidden' class="form-control" name='id' maxlength='32' value="<?php echo set_value('id', isset($selectMv) ? trim($selectMv) : ''); ?>" />
            
            <div class="control-group<?php echo form_error('no_sk') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Tunda Selama (tahun)", 'Tunda Selama (tahun)', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select name="num">
                        <option value='1'>1</option>
                        <option value='2'>2</option>
                        <option value='3'>3</option>
                    </select>
                    <span class='help-inline'><?php echo form_error('no_sk'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('no_sk') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Alasan", 'Alasan', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='alasan' type='text' class="form-control" name='alasan'  value="<?php echo set_value('no_sk', isset($selectedData->no_sk) ? $selectedData->no_sk : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('no_sk'); ?></span>
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