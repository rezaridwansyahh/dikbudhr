<?php 
    $id = isset($detail_riwayat->id) ? $detail_riwayat->id : '';
?>
<div class='box box-warning' id="form-diklat-struktural-add">
	<div class="box-body">
        <div class="messages">
        </div>
    <?php echo form_open($this->uri->uri_string(), 'id="frm"'); ?>
        <fieldset>
            <input id='id' type='hidden' class="form-control" name='id' maxlength='32' value="<?php echo set_value('id', isset($selectedData) ? trim($selectedData->id) : ''); ?>" />
            <div class="control-group<?php echo form_error('name') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Pegawai", 'name', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select id="filter_pegawai" name="filter_pegawai_id" width="100%" class=" col-md-10 format-control">
                    </select>
                    <span class='help-inline'><?php echo form_error('name'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('alasan') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Alasan", 'alasan', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <?php echo form_textarea(array('name' => 'alasan', 'id' => 'alasan', 'rows' => '5', 'cols' => '80', 'value' => set_value('alasan', isset($selectedData->alasan) ? $selectedData->alasan : ''))); ?>
                    <span class='help-inline'><?php echo form_error('alasan'); ?></span>
                </div>
            </div>
        </div>
  		<div class="box-footer">
            <fieldset class='form-actions'>
                <input type='submit' name='save' id="btnsave" class='btn btn-primary' value="Simpan Data" /> 
                or
                <a class='btn btn-warning' href='<?php echo base_url('pegawai/layanan_kpo');?>'>Cancel</a> 
            </fieldset>    
        </div>
    <?php echo form_close(); ?>
</div>
<script src="<?php echo base_url(); ?>themes/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
<script type="text/javascript">

$("#filter_pegawai").select2({
	placeholder: 'Cari Pegawai...',
	width: '100%',
	minimumInputLength: 3,
	allowClear: true,
	ajax: {
		url: '<?php echo site_url("kpo/layanan_kpo/select2_list_pegawai");?>',
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
</script>