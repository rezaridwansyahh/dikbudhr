<?php 
    $id = isset($detail_riwayat->id) ? $detail_riwayat->id : '';
?>
<div class='box box-warning' id="form-diklat-struktural-add">
    <?php echo form_open($this->uri->uri_string(), 'id="frm-usulan-kpo"'); ?> 
	<div class="box-body">
        <div class="messages">
        </div>
        <fieldset>
            <input id='id' type='hidden' class="form-control" name='id' maxlength='32' value="<?php echo set_value('id', isset($selectedData) ? trim($selectedData->id) : ''); ?>" />
            <div class="control-group<?php echo form_error('name') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("NIP", 'name', array('class' => 'control-label')); ?>
                <div class='controls'>
					<?php 
						$readOnly = "";
						if($selectedData){
							$readOnly = "READONLY";
						}
					?>
					<select <?php echo $readOnly;?> name="pegawai_id" id="pegawai_id" class="form-control select2">
						<?php 
							if($selectedData){
								echo "<option selected value='{$selectedData->NIP_BARU}'>{$selectedData->NAMA}</option>";
							}
						?>
                    </select>   
                    <span class='help-inline'></span>
                </div>
            </div>
           
            <div class="control-group<?php echo form_error('name') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Status", 'name', array('class' => 'control-label')); ?>
                <div class='controls'>
                <select name="status"   class="form-control">
                    <option value="">-- Silahkan Pilih --</option>
                    <?php foreach($list_status as $record):?>
                        <option value="<?php echo $record['value'];?>" <?php echo (isset($selectedData->status) && $selectedData->status == $record['value'])  ? "selected" : ""; ?>><?php echo $record['value']." | ".$record['name'].""; ?></option>
                    <?php endforeach;?>
                </select>
                    <span class='help-inline'><?php echo form_error('name'); ?></span>
                </div>
            </div><br>
            <div class="control-group<?php echo form_error('name') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Alasan", 'name', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <?php echo form_textarea(array('name' => 'alasan','class'=>'form-control', 'id' => 'alasan', 'rows' => '5', 'cols' => '80', 'value' => set_value('alasan', isset($selectedData->alasan) ? $selectedData->alasan : ''))); ?>
                    <span class='help-inline'><?php echo form_error('alasan'); ?></span>
                </div>
            </div>
        </div>
  		<div class="box-footer">
            <fieldset class='form-actions'>
                <button type='submit' name='save' id="btnsave" class='btn btn-primary'> 
					<i class='fa fa-save''></i> 
					Simpan Data
				</button>
                or
                <a class='btn btn-warning' onclick="hideCustomModal();">Cancel</a> 
            </fieldset>    
        </div>
    <?php echo form_close(); ?>
</div>
<script src="<?php echo base_url(); ?>themes/admin/plugins/datepicker/bootstrap-datepicker.js"></script>


<script>
    function hideCustomModal(){
        $("#modal-custom-global").modal("hide");
    }
	$(document).ready(function(){
        $("#frm-usulan-kpo").submit(function(){
            $.post("<?php echo $module_url?>/save_usulan",$(this).serialize(),function(o){
                  if(o.success){
                    swal("Pemberitahuan!", "Transaksi berhasil", "success");
                    $("#modal-custom-global").trigger("sukses-update-usulan");
					$("#modal-custom-global").modal("hide");
                  }
            },'json');
            return false;
        });
		$("#pegawai_id").select2({
			placeholder: 'Pilih pegawai',
			width: '100%',
			minimumInputLength: 3,
			allowClear: true,
			ajax: {
				url: '<?php echo $module_url;?>/select2_list_pegawai',
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
	});
</script>