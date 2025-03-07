<link href="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/css/fileinput.css" media="all" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/themes/explorer/theme.css" media="all" rel="stylesheet" type="text/css"/>
<?php 
    $id = isset($detail_riwayat->ID) ? $detail_riwayat->ID : '';
?>
<div class='box box-warning' id="form-kepangkatan-add">
	<div class="box-body">
        <div class="messages">
        </div>
    <?php echo form_open($this->uri->uri_string(), 'id="frmkepangkatan"'); ?>
        <fieldset>
            <input type='hidden' class="form-control" name='ID' maxlength='32' value="<?php echo set_value('ID', isset($detail_riwayat->ID) ? trim($detail_riwayat->ID) : ''); ?>" />
            <input id='PNS_ID' type='hidden' class="form-control" name='PNS_ID' maxlength='32' value="<?php echo set_value('PNS_ID', isset($PNS_ID) ? trim($PNS_ID) : ''); ?>" />
           
           <div class="control-group<?php echo form_error('GOLONGAN') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label('Jenis KP', 'Jenis KP', array('class' => 'control-label')); ?>
                <div class='controls'>
                	<select name="KODE_JENIS_KP" id="KODE_JENIS_KP" class="form-control">
						<option value="">-- Silahkan Pilih --</option>
						<?php if (isset($jenis_kps) && is_array($jenis_kps) && count($jenis_kps)):?>
						<?php foreach($jenis_kps as $record):?>
							<option value="<?php echo $record->ID?>" <?php if(isset($detail_riwayat->KODE_JENIS_KP))  echo  ($detail_riwayat->KODE_JENIS_KP==$record->ID) ? "selected" : ""; ?>><?php echo $record->ID." | ".$record->NAMA.""; ?></option>
							<?php endforeach;?>
						<?php endif;?>
					</select>
                    <span class='help-inline'><?php echo form_error('KODE_JENIS_KP'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('GOLONGAN') ? ' error' : ''; ?> col-sm-9">
                <?php echo form_label('GOLONGAN', 'GOLONGAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                	<select name="ID_GOLONGAN" id="ID_GOLONGAN" class="form-control">
						<option value="">-- Silahkan Pilih --</option>
						<?php if (isset($golongans) && is_array($golongans) && count($golongans)):?>
						<?php foreach($golongans as $record):?>
							<option value="<?php echo $record->ID?>" <?php if(isset($detail_riwayat->GOLONGAN))  echo  ($detail_riwayat->ID_GOLONGAN==$record->ID) ? "selected" : ""; ?>><?php echo $record->NAMA_PANGKAT." | ".$record->NAMA.""; ?></option>
							<?php endforeach;?>
						<?php endif;?>
					</select>
                    <span class='help-inline'><?php echo form_error('ID_GOLONGAN'); ?></span>
                </div>
            </div>            
			<div class="control-group col-sm-3">
				<label for="inputNAMA" class="control-label">TMT_GOLONGAN</label>
				<div class="input-group date">
				  <div class="input-group-addon">
					<i class="fa fa-calendar"></i>
				  </div>
					<input type='text' class="form-control pull-right datepicker" name='TMT_GOLONGAN'  value="<?php echo set_value('TMT_GOLONGAN', isset($detail_riwayat->TMT_GOLONGAN) ? $detail_riwayat->TMT_GOLONGAN : ''); ?>" />
					<span class='help-inline'><?php echo form_error('TMT_GOLONGAN'); ?></span>
				</div>
			</div> 
            
            <div class="control-group<?php echo form_error('SK_NOMOR') ? ' error' : ''; ?> col-sm-9">
                <?php echo form_label("SK NOMOR", 'SK NOMOR', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='SK_NOMOR' type='text' class="form-control" name='SK_NOMOR' maxlength='32' value="<?php echo set_value('SK_NOMOR', isset($detail_riwayat->SK_NOMOR) ? $detail_riwayat->SK_NOMOR : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('SK_NOMOR'); ?></span>
                </div>
            </div>
            <div class="control-group col-sm-3">
				<label for="inputNAMA" class="control-label">SK TANGGAL</label>
				<div class="input-group date">
				  <div class="input-group-addon">
					<i class="fa fa-calendar"></i>
				  </div>
					<input type='text' class="form-control pull-right datepicker" name='SK_TANGGAL'  value="<?php echo set_value('SK_TANGGAL', isset($detail_riwayat->SK_TANGGAL) ? $detail_riwayat->SK_TANGGAL : ''); ?>" />
					<span class='help-inline'><?php echo form_error('SK_TANGGAL'); ?></span>
				</div>
			</div> 
			<div class="control-group<?php echo form_error('NOMOR BKN') ? ' error' : ''; ?> col-sm-9">
                <?php echo form_label("NOMOR BKN", 'NOMOR BKN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='NOMOR_BKN' type='text' class="form-control" name='NOMOR_BKN' maxlength='32' value="<?php echo set_value('NOMOR_BKN', isset($detail_riwayat->NOMOR_BKN) ? $detail_riwayat->NOMOR_BKN : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('NOMOR_BKN'); ?></span>
                </div>
            </div>
            <div class="control-group col-sm-3">
				<label for="inputNAMA" class="control-label">TANGGAL BKN</label>
				<div class="input-group date">
				  <div class="input-group-addon">
					<i class="fa fa-calendar"></i>
				  </div>
					<input type='text' class="form-control pull-right datepicker" name='TANGGAL_BKN'  value="<?php echo set_value('TANGGAL_BKN', isset($detail_riwayat->TANGGAL_BKN) ? $detail_riwayat->TANGGAL_BKN : ''); ?>" />
					<span class='help-inline'><?php echo form_error('TANGGAL_BKN'); ?></span>
				</div>
			</div> 
            <div class="control-group<?php echo form_error('JUMLAH_ANGKA_KREDIT_UTAMA') ? ' error' : ''; ?> col-sm-6">
                <?php echo form_label("JUMLAH ANGKA KREDIT UTAMA", 'JUMLAH ANGKA KREDIT UTAMA', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='JUMLAH_ANGKA_KREDIT_UTAMA' type='text' class="form-control" name='JUMLAH_ANGKA_KREDIT_UTAMA' maxlength='32' value="<?php echo set_value('JUMLAH_ANGKA_KREDIT_UTAMA', isset($detail_riwayat->JUMLAH_ANGKA_KREDIT_UTAMA) ? $detail_riwayat->JUMLAH_ANGKA_KREDIT_UTAMA : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('JUMLAH_ANGKA_KREDIT_UTAMA'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('JUMLAH ANGKA KREDIT TAMBAHAN') ? ' error' : ''; ?> col-sm-6">
                <?php echo form_label("JUMLAH ANGKA KREDIT TAMBAHAN", 'JUMLAH ANGKA KREDIT TAMBAHAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='JUMLAH_ANGKA_KREDIT_TAMBAHAN' type='text' class="form-control" name='JUMLAH_ANGKA_KREDIT_TAMBAHAN' maxlength='32' value="<?php echo set_value('JUMLAH_ANGKA_KREDIT_TAMBAHAN', isset($detail_riwayat->JUMLAH_ANGKA_KREDIT_TAMBAHAN) ? $detail_riwayat->JUMLAH_ANGKA_KREDIT_TAMBAHAN : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('JUMLAH_ANGKA_KREDIT_TAMBAHAN'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('MK_GOLONGAN_TAHUN') ? ' error' : ''; ?> col-sm-6">
                <?php echo form_label("MASA KERJA GOLONGAN TAHUN", 'MASA KERJA GOLONGAN TAHUN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='MK_GOLONGAN_TAHUN' type='text' class="form-control" name='MK_GOLONGAN_TAHUN' maxlength='32' value="<?php echo set_value('MK_GOLONGAN_TAHUN', isset($detail_riwayat->MK_GOLONGAN_TAHUN) ? $detail_riwayat->MK_GOLONGAN_TAHUN : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('MK_GOLONGAN_TAHUN'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('MK_GOLONGAN_BULAN') ? ' error' : ''; ?> col-sm-6">
                <?php echo form_label("MASA KERJA GOLONGAN BULAN", 'MASA KERJA GOLONGAN BULAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='MK_GOLONGAN_BULAN' type='text' class="form-control" name='MK_GOLONGAN_BULAN' maxlength='32' value="<?php echo set_value('MK_GOLONGAN_BULAN', isset($detail_riwayat->MK_GOLONGAN_BULAN) ? $detail_riwayat->MK_GOLONGAN_BULAN : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('MK_GOLONGAN_BULAN'); ?></span>
                </div>
            </div>
            <div class="control-group col-sm-9">
                <label for="inputNAMA" class="control-label">Berkas</label>
                <div class='controls'>
                    <div id="form_upload">
                      <input id="file_dokumen" name="file_dokumen" class="file" type="file" data-show-upload="false" data-preview-file-type="pdf">
                    </div>
                    
                </div>
            </div> 
            <div class="control-group col-sm-3">
                <label for="inputNAMA" class="control-label"><br></label>
                <div class='controls'>
                    
                    <?php if(isset($detail_riwayat->FILE_BASE64) && $detail_riwayat->FILE_BASE64 != ""){ ?>
                        <a href="<?php echo base_url(); ?>pegawai/riwayatkepangkatan/viewdoc/<?php echo $detail_riwayat->ID; ?>" class="btn btn-warning show-modal" tooltip="Lihat dokumen <?php echo isset($detail_riwayat->SK_NOMOR) ? $detail_riwayat->SK_NOMOR : ''; ?>" ><span class="fa fa-file-o"></span> Lihat</a>
                    <?php } ?>  
                </div>
            </div>
            </fieldset>
        <?php if ($this->auth->has_permission('RiwayatPendidikan.Kepegawaian.VerifikasiS')) { ?>
        <fieldset>
            <legend>Verifikasi Data (SATKER)</legend>
            <div class="callout callout-warning">
               <h4>Checklist "TERIMA DATA" jika data yang dimasukan oleh pegawai diterima oleh satker</h4>
             </div>
            <div class="control-group col-sm-6">
                <label for="inputNAMA" class="control-label">TERIMA DATA?</label>
                <div class='controls'>
                    <input id='STATUS_SATKER' type='checkbox' name='STATUS_SATKER' value="1" <?php echo $detail_riwayat->STATUS_SATKER == "1" ? "checked" : ""; ?> />
                    <span class='help-inline'><?php echo form_error('STATUS_SATKER'); ?></span>
                </div>
            </div> 
            </fieldset>
            <?php } ?>
            <?php if ($this->auth->has_permission('RiwayatPendidikan.Kepegawaian.VerifikasiB')) { ?>
            <fieldset>
                <legend>Verifikasi Data (BIRO)</legend>
                <div class="callout callout-warning">
                   <h4>Checklist "TERIMA DATA" jika data yang dimasukan oleh pegawai diterima oleh BIRO</h4>
                   <P>Checklist "PANGKAT TERAKHIR (Active)" jika data yang dimasukan oleh pegawai diterima oleh BIRO dan menjadi pendidikan terakhir pegawai (Pangkat terakhir akan diupdate pada Profile utama Pegawai)</P>
                 </div>
                <div class="control-group col-sm-6">
                    <label for="inputNAMA" class="control-label">TERIMA DATA?</label>
                    <div class='controls'>
                        <input id='STATUS_BIRO' type='checkbox' name='STATUS_BIRO' value="1" <?php echo $detail_riwayat->STATUS_BIRO == "1" ? "checked" : ""; ?> />
                        <span class='help-inline'><?php echo form_error('STATUS_BIRO'); ?></span>
                    </div>
                </div> 
                <div class="control-group col-sm-6">
                    <label for="inputNAMA" class="control-label">PANGKAT TERAKHIR (Active)?</label>
                    <div class='controls'>
                        <input id='PANGKAT_TERAKHIR' type='checkbox' name='PANGKAT_TERAKHIR' value="1" <?php echo $detail_riwayat->PANGKAT_TERAKHIR == "1" ? "checked" : ""; ?> />
                        <span class='help-inline'><?php echo form_error('PANGKAT_TERAKHIR'); ?></span>
                    </div>
                </div> 
            </div>
            <?php } ?>
        </div>

  		<div class="box-footer">
            <a href="javascript:;" id="btnsavekepangkatan" class="btn green btn-primary button-submit"> Simpan Data
                <i class="fa fa-save"></i>
            </a>
            
        </div>
    <?php echo form_close(); ?>
</div>
<script src="<?php echo base_url(); ?>themes/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/themes/explorer/theme.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/js/fileinput.js" type="text/javascript"></script>
<script>
    var form = $("#form-kepangkatan-add");
    //Date picker
    $('.datepicker').datepicker({
      autoclose: true,format: 'yyyy-mm-dd'
    }).on("input change", function (e) {
        var date = $(this).datepicker('getDate');
        if(date)$("[name=TAHUN]",form).val(date.getFullYear());
    });
</script>
<script>
	$("#btnsavekepangkatan").click(function(){
        $('#btnsavekepangkatan').addClass('disabled');
        $('#btnsavekepangkatan').text('Sedang Mengirim...');
		submitdatakepangkatan();
		return false; 
	});	
	$("#frmkepangkatana").submit(function(){
		submitdata();
		return false; 
	});	
	function submitdatakepangkatan(){
		var the_data = new FormData(document.getElementById("frmkepangkatan"));
		var json_url = "<?php echo base_url() ?>pegawai/riwayatkepangkatan/save";
		 $.ajax({    
		 	type: "POST",
			url: json_url,
			data: the_data,
            dataType: "json",
            processData: false, // tell jQuery not to process the data
            contentType: false, // tell jQuery not to set contentType
			success: function(data){ 
                if(data.success){
                    swal("Pemberitahuan!", data.msg, "success");
                    $("#modal-custom-global").trigger("sukses-tambah-riwayat-kepangkatan");
					$("#modal-custom-global").modal("hide");
                    $('#btnsavekepangkatan').removeClass('disabled');
                     $('#btnsavekepangkatan').text('Simpan Data');
                    $table.ajax.reload(null,true);
                }
                else {
                    $('#btnsavekepangkatan').removeClass('disabled');
                    $('#btnsavekepangkatan').text('Simpan Data');
                    $("#form-kepangkatan-add .messages").empty().append(data.msg);
                }
			}});
		return false; 
	}
</script>