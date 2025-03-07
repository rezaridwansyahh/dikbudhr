<?php 
    $id = isset($data->id) ? $data->id : '';
?>
<div class='box box-warning' id="form-pendidikan-add">
    <?php echo form_open($module_url.'/save_form_settings', 'id="form-settings"'); ?>
	<div class="box-body">
        <div class="messages">
        </div>
        <fieldset>
            <div class="control-group<?php echo form_error('pejabat') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Pejabat", 'Pejabat', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='pejabat' type='text' class="form-control" name='pejabat'  value="<?php echo set_value('pejabat', isset($pejabat) ? $pejabat : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('pejabat'); ?></span>
                </div>
            </div>
        </div>
  		<div class="box-footer">
            <button type='submit' name='save' id="btnsave" class='btn btn-primary'><i class='fa fa-save'></i> Simpan </button>
        </div>
    <?php echo form_close(); ?>
</div>
<script src="<?php echo base_url(); ?>themes/admin/plugins/datepicker/bootstrap-datepicker.js"></script>

<script>
   
	$("#form-settings").submit(function(){
		$.post($(this).attr("action"),$(this).serialize(),function(o){
            if(o.success){
                swal("Pemberitahuan!", o.message, "success");
                $("#modal-custom-global").trigger("sukses-settings-kgb");
                $("#modal-custom-global").modal("hide");
            }
            else {
                swal("Pemberitahuan!", o.message, "error");
            }
        },'json');
		return false; 
	});	
</script>