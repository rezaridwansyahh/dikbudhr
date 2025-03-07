<?php 
    $id = isset($detail_riwayat->ID) ? $detail_riwayat->ID : '';
?>
<div class='box box-warning' id="form-diklat-struktural-add">
	<div class="box-body">
        <div class="messages">
        </div>
    <?php echo form_open($this->uri->uri_string(), 'id="frm"'); ?>
        <fieldset>
            <input id='ID' type='hidden' class="form-control" name='ID' maxlength='32' value="<?php echo set_value('id_data', isset($id) ? trim($id) : ''); ?>" />
            
            <div class="control-group<?php echo form_error('TINGKAT PENDIDIKAN') ? ' error' : ''; ?> col-sm-3">
                <?php echo form_label('TINGKAT PENDIDIKAN', 'TINGKAT PENDIDIKAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                	<select name="TINGKAT_PENDIDIKAN_ID" id="TINGKAT_PENDIDIKAN_ID" class="form-control">
						<option value="">-- Silahkan Pilih --</option>
						<?php foreach($tk_pendididikan_all as $record):?>
							<option value="<?php echo $record->ID?>"> <?php echo $record->NAMA; ?></option>
							<?php endforeach;?>
					</select>
                    <span class='help-inline'><?php echo form_error('PENDIDIKAN_ID'); ?></span>
                </div>
            </div>            
			

            <div class="control-group<?php echo form_error('NAMA') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("NAMA", 'NAMA', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='NAMA' type='text' class="form-control" name='NAMA' maxlength='32'  />
                    <span class='help-inline'><?php echo form_error('NAMA'); ?></span>
                </div>
            </div>
            
        </div>
  		<div class="box-footer">
             <fieldset class='form-actions'>
                <input type='submit' name='save' id="btnsave" class='btn btn-primary' value="Simpan Data" /> 
                or
                <a class='btn btn-warning' href='<?php echo base_url('pegawai/masterpendidikan');?>'>Cancel</a> 
            </fieldset>                
        </div>
    <?php echo form_close(); ?>
</div>
<script src="<?php echo base_url(); ?>themes/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
