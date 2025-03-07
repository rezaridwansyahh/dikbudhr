<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/select2/select2.min.css">

<?php 
    $id = isset($detail_riwayat->ID) ? $detail_riwayat->ID : '';
?>
<div class='box box-warning' id="form-riwayat-kursus-add">
	<div class="box-body">
        <div class="messages">
        </div>
    <?php echo form_open($this->uri->uri_string(), 'id="frm"'); ?>
    <fieldset>
            <input id='ID' type='hidden' class="form-control" name='ID' maxlength='32' value="<?php echo set_value('ID', isset($detail_riwayat->ID) ? trim($detail_riwayat->ID) : ''); ?>" />
            <input id='PNS_ID' type='hidden' class="form-control" name='PNS_ID' maxlength='32' value="<?php echo set_value('PNS_ID', isset($PNS_ID) ? trim($PNS_ID) : ''); ?>" />
        	<div class="control-group<?php echo form_error('tipe_kursus') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label('Tipe Kursus', 'Jenis ', array('class' => 'control-label')); ?>
                <div class='controls'>
                	<select name="TIPE_KURSUS" id="TIPE_KURSUS" class="form-control">
						<option value="">-- Silahkan Pilih --</option>
						<option value="Kursus" <?php if(isset($detail_riwayat->TIPE_KURSUS))  echo  (TRIM($detail_riwayat->TIPE_KURSUS)=="Kursus") ? "selected" : ""; ?> >Kursus</option>
						<option value="Sertifikat" <?php if(isset($detail_riwayat->TIPE_KURSUS))  echo  (TRIM($detail_riwayat->TIPE_KURSUS)=="Sertifikat") ? "selected" : ""; ?> >Sertifikat</option>
					</select>
                    <span class='help-inline'><?php echo form_error('TIPE_KURSUS'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('JENIS_KURSUS') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("JENIS KURSUS", 'JENIS_KURSUS', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='JENIS_KURSUS' type='text' class="form-control" name='JENIS_KURSUS' maxlength='32' value="<?php echo set_value('JENIS_KURSUS', isset($detail_riwayat->JENIS_KURSUS) ? trim($detail_riwayat->JENIS_KURSUS) : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('JENIS_KURSUS'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('NAMA_KURSUS') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("NAMA KURSUS", 'SK NOMOR', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='NAMA_KURSUS' type='text' class="form-control" name='NAMA_KURSUS' maxlength='32' value="<?php echo set_value('NAMA_KURSUS', isset($detail_riwayat->NAMA_KURSUS) ? trim($detail_riwayat->NAMA_KURSUS) : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('NAMA_KURSUS'); ?></span>
                </div>
            </div>
			<div class="control-group col-sm-9">
				<label for="inputNAMA" class="control-label">TANGGAL</label>
				<div class="input-group date">
				  <div class="input-group-addon">
					<i class="fa fa-calendar"></i>
				  </div>
					<input type='text' class="form-control pull-right datepicker" name='TANGGAL_KURSUS'  value="<?php echo set_value('TANGGAL_KURSUS', isset($detail_riwayat->TANGGAL_KURSUS) ? $detail_riwayat->TANGGAL_KURSUS : ''); ?>" />
					<span class='help-inline'><?php echo form_error('TANGGAL_KURSUS'); ?></span>
				</div>
			</div> 
            
            <div class="control-group<?php echo form_error('LAMA_KURSUS') ? ' error' : ''; ?> col-sm-3">
                <?php echo form_label("LAMA (JAM)", 'SK NOMOR', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='LAMA_KURSUS' type='text' class="form-control" name='LAMA_KURSUS' maxlength='5' value="<?php echo set_value('LAMA_KURSUS', isset($detail_riwayat->LAMA_KURSUS) ? trim($detail_riwayat->LAMA_KURSUS) : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('LAMA_KURSUS'); ?></span>
                </div>
            </div>
             
            <div class="control-group<?php echo form_error('NO_SERTIFIKAT') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("NO SERTIFIKAT", 'MASA BULAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='NO_SERTIFIKAT' type='text' class="form-control" name='NO_SERTIFIKAT' maxlength='100' value="<?php echo set_value('NO_SERTIFIKAT', isset($detail_riwayat->NO_SERTIFIKAT) ? trim($detail_riwayat->NO_SERTIFIKAT) : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('NO_SERTIFIKAT'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('INSTANSI') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("INSTANSI", 'MASA BULAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='INSTANSI' type='text' class="form-control" name='INSTANSI' maxlength='200' value="<?php echo set_value('INSTANSI', isset($detail_riwayat->INSTANSI) ? trim($detail_riwayat->INSTANSI) : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('INSTANSI'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('INSTITUSI_PENYELENGGARA') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("INSTITUSI PENYELENGGARA", 'INSTITUSI_PENYELENGGARA', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='INSTITUSI_PENYELENGGARA' type='text' class="form-control" name='INSTITUSI_PENYELENGGARA' maxlength='200' value="<?php echo set_value('INSTITUSI_PENYELENGGARA', isset($detail_riwayat->INSTITUSI_PENYELENGGARA) ? trim($detail_riwayat->INSTITUSI_PENYELENGGARA) : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('INSTITUSI_PENYELENGGARA'); ?></span>
                </div>
            </div>
			</fieldset>
			 
        </div>
  		 
    <?php echo form_close(); ?>
</div>
<script src="<?php echo base_url(); ?>themes/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
<script>
	 $(".select2").select2({width: '100%'});
</script>
<script>
 	 
    var form = $("#form-riwayat-kursus-add");
    //Date picker
    $('.datepicker').datepicker({
      autoclose: true,format: 'yyyy-mm-dd'
    }).on("input change", function (e) {
        var date = $(this).datepicker('getDate');
        if(date)$("[name=TAHUN]",form).val(date.getFullYear());
    });
</script>
