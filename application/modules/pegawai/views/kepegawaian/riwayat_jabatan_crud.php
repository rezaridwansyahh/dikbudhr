<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/select2/select2.min.css">
<link href="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/css/fileinput.css" media="all" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/themes/explorer/theme.css" media="all" rel="stylesheet" type="text/css"/>
<?php 
    $id = isset($detail_riwayat->ID) ? $detail_riwayat->ID : '';
?>
<div class='box box-warning' id="form-riwayat-jabatan-add">
	<div class="box-body">
        <div class="messages">
        </div>
    <?php echo form_open($this->uri->uri_string(), 'id="frmriwayatjabatan"'); ?>
    <fieldset>
            <input type='hidden' class="form-control" name='ID' maxlength='32' value="<?php echo set_value('ID', isset($detail_riwayat->ID) ? trim($detail_riwayat->ID) : ''); ?>" />
            <input id='PNS_ID' type='hidden' class="form-control" name='PNS_ID' maxlength='32' value="<?php echo set_value('PNS_ID', isset($PNS_ID) ? trim($PNS_ID) : ''); ?>" />
           <div class="control-group<?php echo form_error('ID_SATUAN_KERJA') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label('SATUAN KERJA', 'ID_SATUAN_KERJA', array('class' => 'control-label')); ?>
                <div class='controls'>
                	<?php //echo $detail_riwayat->ID_SATUAN_KERJA; ?>
                	<select name="ID_SATUAN_KERJA" id="ID_SATUAN_KERJA" class="form-control select2  col-sm-12" width="100%">
						<option value="">-- Silahkan Pilih --</option>
						<?php if (isset($recsatker) && is_array($recsatker) && count($recsatker)):?>
						<?php foreach($recsatker as $record):?>
							<option value="<?php echo $record->ID?>" <?php if(isset($detail_riwayat->ID_SATUAN_KERJA))  echo  (trim($detail_riwayat->ID_SATUAN_KERJA)==trim($record->ID)) ? "selected" : ""; ?>><?php echo $record->NAMA_UNOR; ?></option>
							<?php endforeach;?>
						<?php endif;?>
						<option value="N">Tidak ada</option>
					</select>
                    <span class='help-inline'><?php echo form_error('ID_SATUAN_KERJA'); ?></span>
                </div>
            </div>
           
           	<div class="control-group<?php echo form_error('GOLONGAN') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label('Jenis', 'Jenis Jabatan', array('class' => 'control-label')); ?>
                <div class='controls'>
                	<select name="ID_JENIS_JABATAN" id="ID_JENIS_JABATAN" class="form-control">
						<option value="">-- Silahkan Pilih --</option>
						<?php if (isset($jenis_jabatans) && is_array($jenis_jabatans) && count($jenis_jabatans)):?>
						<?php foreach($jenis_jabatans as $record):?>
							<option value="<?php echo $record->ID?>" <?php if(isset($detail_riwayat->ID_JENIS_JABATAN))  echo  (trim($detail_riwayat->ID_JENIS_JABATAN)==$record->ID) ? "selected" : ""; ?>  <?php if(isset($jenis_jabatan))  echo  ($jenis_jabatan==$record->ID) ? "selected" : ""; ?>><?php echo $record->NAMA; ?></option>
							<?php endforeach;?>
						<?php endif;?>
					</select>
                    <span class='help-inline'><?php echo form_error('ID_JENIS_JABATAN'); ?></span>
                </div>
            </div>
            <div class="control-group col-sm-12">
				<label for="inputNAMA" class="control-label">UNOR (Unit Organisasi)</label>
				<div class='controls'>
                    <select id="ID_UNOR" name="ID_UNOR" width="100%" class="select2 col-md-10 format-control">
						<option value="">-- Silahkan Pilih --</option>
						<?php if (isset($recunor) && is_array($recunor) && count($recunor)):?>
						<?php foreach($recunor as $record):?>
							<option value="<?php echo $record->ID?>" <?php if(isset($detail_riwayat->ID_UNOR))  echo  (trim($detail_riwayat->ID_UNOR)==trim($record->ID)) ? "selected" : ""; ?> <?php if(isset($detail_riwayat->ID_JENIS_JABATAN))  echo  ($jenis_jabatan == $record->ID) ? "selected" : ""; ?>><?php echo $record->NAMA_UNOR; ?></option>
							<?php endforeach;?>
						<?php endif;?>
					</select>
                </div>
			</div> 
            <div class="control-group<?php echo form_error('ID_JABATAN') ? ' error' : ''; ?> col-sm-10">
                <?php echo form_label('JABATAN', 'ID_JABATAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                	<div class='slcjabatan'>
                	<select name="ID_JABATAN" id="ID_JABATAN" class="form-control select2  col-sm-12 slcjabatan" width="100%">
						<option value="">-- Silahkan Pilih --</option>
						<?php if (isset($jabatans) && is_array($jabatans) && count($jabatans)):?>
						<?php foreach($jabatans as $record):?>
							<option value="<?php echo $record->KODE_JABATAN?>" <?php if(isset($detail_riwayat->ID_JABATAN))  echo  (trim($detail_riwayat->ID_JABATAN)==trim($record->KODE_JABATAN)) ? "selected" : ""; ?>><?php echo $record->NAMA_JABATAN; ?></option>
							<?php endforeach;?>
						<?php endif;?>
					</select>
					</div>
					<span class='divjabatan'><?php echo set_value('NAMA_JABATAN', isset($detail_riwayat->NAMA_JABATAN) ? $detail_riwayat->NAMA_JABATAN : ''); ?></span>
                    <span class='help-inline'><?php echo form_error('ID_JABATAN'); ?></span>
                </div>
            </div>   
            <div class="control-group col-sm-2">
				<label for="inputNAMA" class="control-label">ESELON</label>
				<div class="input-group date">
				   
					<input type='text' class="form-control pull-right" name='ESELON'  value="<?php echo set_value('ESELON', isset($detail_riwayat->ESELON) ? trim($detail_riwayat->ESELON) : ''); ?>" />
					<span class='help-inline'><?php echo form_error('ESELON'); ?></span>
				</div>
			</div>       
			<div class="control-group col-sm-3">
				<label for="inputNAMA" class="control-label">TMT</label>
				<div class="input-group date">
				  <div class="input-group-addon">
					<i class="fa fa-calendar"></i>
				  </div>
					<input type='text' class="form-control pull-right datepicker" name='TMT_JABATAN'  value="<?php echo set_value('TMT_JABATAN', isset($detail_riwayat->TMT_JABATAN) ? $detail_riwayat->TMT_JABATAN : ''); ?>" />
					<span class='help-inline'><?php echo form_error('TMT_JABATAN'); ?></span>
				</div>
			</div> 
            
            <div class="control-group<?php echo form_error('NOMOR_SK') ? ' error' : ''; ?> col-sm-9">
                <?php echo form_label("SK NOMOR", 'SK NOMOR', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='NOMOR_SK' type='text' class="form-control" name='NOMOR_SK' maxlength='32' value="<?php echo set_value('NOMOR_SK', isset($detail_riwayat->NOMOR_SK) ? trim($detail_riwayat->NOMOR_SK) : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('NOMOR_SK'); ?></span>
                </div>
            </div>
            <div class="control-group col-sm-3">
				<label for="inputNAMA" class="control-label">SK TANGGAL</label>
				<div class="input-group date">
				  <div class="input-group-addon">
					<i class="fa fa-calendar"></i>
				  </div>
					<input type='text' class="form-control pull-right datepicker" name='TANGGAL_SK'  value="<?php echo set_value('TANGGAL_SK', isset($detail_riwayat->TANGGAL_SK) ? $detail_riwayat->TANGGAL_SK : ''); ?>" />
					<span class='help-inline'><?php echo form_error('TANGGAL_SK'); ?></span>
				</div>
			</div>   
			<div class="control-group col-sm-9">
				<label for="inputNAMA" class="control-label">TMT PELANTIKAN</label>
				<div class="input-group date">
				  <div class="input-group-addon">
					<i class="fa fa-calendar"></i>
				  </div>
					<input type='text' class="form-control pull-right datepicker" name='TMT_PELANTIKAN'  value="<?php echo set_value('TMT_PELANTIKAN', isset($detail_riwayat->TMT_PELANTIKAN) ? $detail_riwayat->TMT_PELANTIKAN : ''); ?>" />
					<span class='help-inline'><?php echo form_error('TMT_PELANTIKAN'); ?></span>
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
                        <a href="<?php echo base_url(); ?>pegawai/riwayatjabatan/viewdoc/<?php echo $detail_riwayat->ID; ?>" class="btn btn-warning show-modal" tooltip="Lihat dokumen <?php echo isset($detail_riwayat->SK_NOMOR) ? $detail_riwayat->SK_NOMOR : ''; ?>" ><span class="fa fa-file-o"></span> Lihat</a>
                    <?php } ?>  
                </div>
            </div>
			</fieldset>
			<fieldset>
			<legend>Jika tidak ada</legend>
			<div class="control-group<?php echo form_error('eselon1') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label('ESELON 1', 'ESELON1', array('class' => 'control-label')); ?>
                <div class='controls'>
                	<input type='text' class="form-control" name='ESELON1'  value="<?php echo set_value('ESELON1', isset($detail_riwayat->ESELON1) ? $detail_riwayat->ESELON1 : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('ESELON1'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('eselon2') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label('ESELON 2', 'eselon2', array('class' => 'control-label')); ?>
                <div class='controls'>
                	<input type='text' class="form-control" name='ESELON2'  value="<?php echo set_value('ESELON2', isset($detail_riwayat->ESELON2) ? $detail_riwayat->ESELON2 : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('ESELON1'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('ESELON3') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label('ESELON 3', 'ESELON3', array('class' => 'control-label')); ?>
                <div class='controls'>
                	<input type='text' class="form-control" name='ESELON3'  value="<?php echo set_value('ESELON3', isset($detail_riwayat->ESELON3) ? $detail_riwayat->ESELON3 : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('ESELON3'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('ESELON4') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label('ESELON 4', 'ESELON4', array('class' => 'control-label')); ?>
                <div class='controls'>
                	<input type='text' class="form-control" name='ESELON4'  value="<?php echo set_value('ESELON4', isset($detail_riwayat->ESELON4) ? $detail_riwayat->ESELON4 : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('ESELON4'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('NAMA_JABATAN') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label('NAMA JABATAN', 'NAMA_JABATAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                	<input type='text' class="form-control" id='NAMA_JABATAN' name='NAMA_JABATAN'  value="<?php echo set_value('NAMA_JABATAN', isset($detail_riwayat->NAMA_JABATAN) ? $detail_riwayat->NAMA_JABATAN : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('ESELON4'); ?></span>
                </div>
            </div>
            </fieldset>
            <?php if ($this->auth->has_permission('riwayatjabatan.Kepegawaian.VerifikasiS')) { ?>
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
	        <?php if ($this->auth->has_permission('riwayatjabatan.Kepegawaian.VerifikasiB')) { ?>
	        <fieldset>
	            <legend>Verifikasi Data (BIRO)</legend>
	            <div class="callout callout-warning">
	               <h4>Checklist "TERIMA DATA" jika data yang dimasukan oleh pegawai diterima oleh BIRO</h4>
	               <P>Checklist "JABATAN TERAKHIR (Active)" jika data yang dimasukan oleh pegawai diterima oleh BIRO dan menjadi pendidikan terakhir pegawai (Jabatan terakhir akan diupdate pada Profile utama Pegawai)</P>
	             </div>
	            <div class="control-group col-sm-6">
	                <label for="inputNAMA" class="control-label">TERIMA DATA?</label>
	                <div class='controls'>
	                    <input id='STATUS_BIRO' type='checkbox' name='STATUS_BIRO' value="1" <?php echo $detail_riwayat->STATUS_BIRO == "1" ? "checked" : ""; ?> />
	                    <span class='help-inline'><?php echo form_error('STATUS_BIRO'); ?></span>
	                </div>
	            </div> 
		        <div class="control-group col-sm-6">
					<label for="inputNAMA" class="control-label">JABATAN AKTIF?</label>
					<div class='controls'>
	                    <input id='IS_ACTIVE' type='checkbox' name='IS_ACTIVE' value="1" <?php echo $detail_riwayat->IS_ACTIVE == "1" ? "checked" : ""; ?> />
	                    <span class='help-inline'><?php echo form_error('IS_ACTIVE'); ?></span>
	                </div>
				</div> 
	        </div>
	        <?php } ?>
        </div>
  		<div class="box-footer">
  			<a href="javascript:;" id="btnsave_riwayatjabatan" class="btn green btn-primary button-submit"> Kirimkan Data
                <i class="fa fa-save"></i>
            </a>
        </div>
    <?php echo form_close(); ?>
</div>
<script src="<?php echo base_url(); ?>themes/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/themes/explorer/theme.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/js/fileinput.js" type="text/javascript"></script>
<script>
	 $(".select2").select2({
	 	width: '100%',
	 	dropdownParent: $("#modal-custom-global")
	 });
</script>
<script>
<?php if(isset($detail_riwayat->ID_JENIS_JABATAN) and $detail_riwayat->ID_JENIS_JABATAN == "1"){
?>
	 $('.slcjabatan').hide();
	 
<?php
}
?>
	$('#ID_SATUAN_KERJA').change(function() {
		var valuesatker = $('#ID_SATUAN_KERJA').val();
			$("#ID_UNOR").empty().append("<option>loading...</option>"); //show loading...
			 
			var json_url = "<?php echo base_url(); ?>pegawai/manage_unitkerja/getbysatker?satker=" + encodeURIComponent(valuesatker);
			$.getJSON(json_url,function(data){
				$("#ID_UNOR").empty(); 
				if(data==""){
					$("#ID_UNOR").append("<option value=\"\">Silahkan Pilih </option>");
				}
				else{
					$("#ID_UNOR").append("<option value=\"\">Silahkan Pilih</option>");
					for(i=0; i<data.id.length; i++){
						$("#ID_UNOR").append("<option value=\"" + data.id[i]  + "\">" + data.nama[i] +"</option>");
					}
				}
				
			});
			$("#ID_UNOR").select2("updateResults");
			return false;
	});
	$('#ID_UNOR').change(function() {
		var val = $('#ID_UNOR').val();
		
		var json_url = "<?php echo base_url(); ?>pegawai/manage_unitkerja/getnamajabatan_byid?unor=" + encodeURIComponent(val);
		$.ajax({    
		   type: "POST",
		   url: json_url,
		   data: "",
		   dataType: "html",
		   success: function(data){ 
				//alert(data);
				var valuejenisjabatan = $('#ID_JENIS_JABATAN').val();
				// if(valuejenisjabatan == "1"){
				// 	$('.slcjabatan').hide();
				// 	$('.divjabatan').html(data);
				// 	$('#NAMA_JABATAN').val(data) ;
				// }else{
				// 	$('.slcjabatan').show();
				// 	$('.divjabatan').html("");
				// }	
		   }});
		 
	});
/*
	$("#ID_UNOR").select2({
			placeholder: 'Cari Unit Kerja...',
			width: '100%',
			minimumInputLength: 3,
			allowClear: true,
			ajax: {
				url: '<?php echo site_url("pegawai/duk/ajax_unit_list");?>',
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
	*/
	$('#ID_JENIS_JABATAN').change(function() {
		var valuejenisjabatan = $('#ID_JENIS_JABATAN').val();
			$("#ID_JABATAN").empty().append("<option>loading...</option>"); //show loading...
			 
			var json_url = "<?php echo base_url(); ?>admin/masters/jabatan/getbyjenis?jenis=" + encodeURIComponent(valuejenisjabatan);
			//alert(json_url);
			$.getJSON(json_url,function(data){
				$("#ID_JABATAN").empty(); 
				if(data==""){
					$("#ID_JABATAN").append("<option value=\"\">Silahkan Pilih </option>");
				}
				else{
					$("#ID_JABATAN").append("<option value=\"\">Silahkan Pilih</option>");
					for(i=0; i<data.id.length; i++){
						$("#ID_JABATAN").append("<option value=\"" + data.id[i]  + "\">" + data.nama[i] +"</option>");
					}
				}
				
			});
			$("#ID_JABATAN").select2("updateResults");
			return false;
	});
    var form = $("#form-riwayat-jabatan-add");
    //Date picker
    $('.datepicker').datepicker({
      autoclose: true,format: 'yyyy-mm-dd'
    }).on("input change", function (e) {
        var date = $(this).datepicker('getDate');
        if(date)$("[name=TAHUN]",form).val(date.getFullYear());
    });
</script>
<script>
	$("#btnsave_riwayatjabatan").click(function(){
		$('#btnsave_riwayatjabatan').text('Sedang Mengirim...');
        $('#btnsave_riwayatjabatan').addClass('disabled');
		submitdatafrmriwayatjabatan();
		return false; 
	});	
	$("#frmA").submit(function(){
		submitdatafrmriwayatjabatan();
		return false; 
	});	
	function submitdatafrmriwayatjabatan(){
		var the_data = new FormData(document.getElementById("frmriwayatjabatan"));
		var json_url = "<?php echo base_url() ?>pegawai/riwayatjabatan/save";
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
                    $("#modal-custom-global").trigger("sukses-tambah-riwayat-jabatan");
					 $("#modal-custom-global").modal("hide");
					 $("#modal-global").modal("hide");
					 $('#btnsave_riwayatjabatan').removeClass('disabled');
                     $('#btnsave_riwayatjabatan').text('Kirimkan Data');
                }
                else {
                	$('#btnsave_riwayatjabatan').removeClass('disabled');
                    $('#btnsave_riwayatjabatan').text('Kirimkan Data');
                    $("#form-riwayat-jabatan-add .messages").empty().append(data.msg);
                }
			}});
		return false; 
	}
</script>