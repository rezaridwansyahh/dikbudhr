<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/select2/select2.min.css">

<?php 
    $id = isset($detail_riwayat->ID) ? $detail_riwayat->ID : '';
?>
<div class='box box-warning' id="form-riwayat-pekerjaan-add">
	<div class="box-body">
        <div class="messages">
        </div>
    <?php echo form_open($this->uri->uri_string(), 'id="frm"'); ?>
    <fieldset>
            <input id='ID' type='hidden' disabled class="form-control" name='ID' maxlength='32' value="<?php echo set_value('ID', isset($detail_riwayat->ID) ? trim($detail_riwayat->ID) : ''); ?>" />
            <input id='PNS_ID' type='hidden' disabled class="form-control" name='PNS_ID' maxlength='32' value="<?php echo set_value('PNS_ID', isset($PNS_ID) ? trim($PNS_ID) : ''); ?>" />
            <div class="control-group<?php echo form_error('NAMA_PERUSAHAAN') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("NAMA PERUSAHAAN", 'NAMA_PERUSAHAAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='NAMA_PERUSAHAAN' type='text' disabled class="form-control" name='NAMA_PERUSAHAAN' maxlength='200' value="<?php echo set_value('NAMA_PERUSAHAAN', isset($detail_riwayat->NAMA_PERUSAHAAN) ? trim($detail_riwayat->NAMA_PERUSAHAAN) : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('NAMA_PERUSAHAAN'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('SEBAGAI') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("SEBAGAI", 'SEBAGAI', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='SEBAGAI' type='text' disabled class="form-control" name='SEBAGAI' maxlength='200' value="<?php echo set_value('SEBAGAI', isset($detail_riwayat->SEBAGAI) ? trim($detail_riwayat->SEBAGAI) : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('SEBAGAI'); ?></span>
                </div>
            </div>
			<div class="control-group col-sm-6">
				<label for="inputNAMA" class="control-label">DARI TANGGAL</label>
				<div class="input-group date">
				  <div class="input-group-addon">
					<i class="fa fa-calendar"></i>
				  </div>
					<input type='text' class="form-control pull-right datepicker" name='DARI_TANGGAL'  value="<?php echo set_value('DARI_TANGGAL', isset($detail_riwayat->DARI_TANGGAL) ? $detail_riwayat->DARI_TANGGAL : ''); ?>" />
					<span class='help-inline'><?php echo form_error('DARI_TANGGAL'); ?></span>
				</div>
			</div> 
            
             
            <div class="control-group col-sm-6">
				<label for="inputNAMA" class="control-label">SAMPAI TANGGAL</label>
				<div class="input-group date">
				  <div class="input-group-addon">
					<i class="fa fa-calendar"></i>
				  </div>
					<input type='text' class="form-control pull-right datepicker" name='SAMPAI_TANGGAL'  value="<?php echo set_value('SAMPAI_TANGGAL', isset($detail_riwayat->SAMPAI_TANGGAL) ? $detail_riwayat->SAMPAI_TANGGAL : ''); ?>" />
					<span class='help-inline'><?php echo form_error('SAMPAI_TANGGAL'); ?></span>
				</div>
			</div>   
            </fieldset>
        </div>
  		 
    <?php echo form_close(); ?>
</div>
<script src="<?php echo base_url(); ?>themes/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
 