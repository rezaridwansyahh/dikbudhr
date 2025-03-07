<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/select2/select2.min.css">
<link href="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/css/fileinput.css" media="all" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/themes/explorer/theme.css" media="all" rel="stylesheet" type="text/css"/>
<?php 
    $id = isset($detail_riwayat->ID) ? $detail_riwayat->ID : '';
?>
<div class='box box-warning' id="form-riwayat-hukdis-add">
	<div class="box-body">
        <div class="messages">
        </div>
    <?php echo form_open($this->uri->uri_string(), 'id="frmhukdis"'); ?>
    <fieldset>
            <input id='ID' type='hidden' class="form-control" name='ID' maxlength='32' value="<?php echo set_value('ID', isset($detail_riwayat->ID) ? trim($detail_riwayat->ID) : ''); ?>" />
            <input id='PNS_ID' type='hidden' class="form-control" name='PNS_ID' maxlength='32' value="<?php echo set_value('PNS_ID', isset($PNS_ID) ? trim($PNS_ID) : ''); ?>" />
        	<div class="control-group<?php echo form_error('GOLONGAN') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label('JENIS HUKUMAN', 'Jenis ', array('class' => 'control-label')); ?>
                <div class='controls'>
                	<select name="ID_JENIS_HUKUMAN" id="ID_JENIS_HUKUMAN" class="form-control">
						<option value="">-- Silahkan Pilih --</option>
						<?php if (isset($jenis_hukumans) && is_array($jenis_hukumans) && count($jenis_hukumans)):?>
						<?php foreach($jenis_hukumans as $record):?>
							<option value="<?php echo $record->ID?>" <?php if(isset($detail_riwayat->ID_JENIS_HUKUMAN))  echo  ($detail_riwayat->ID_JENIS_HUKUMAN==$record->ID) ? "selected" : ""; ?>  <?php if(isset($detail_riwayat->ID_JENIS_HUKUMAN))  echo  ($detail_riwayat->ID_JENIS_HUKUMAN==$record->ID) ? "selected" : ""; ?>><?php echo $record->NAMA; ?></option>
							<?php endforeach;?>
						<?php endif;?>
					</select>
                    <span class='help-inline'><?php echo form_error('ID_JENIS_HUKUMAN'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('GOLONGAN') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label('GOLONGAN', 'Jenis ', array('class' => 'control-label')); ?>
                <div class='controls'>
                	<select name="ID_GOLONGAN" id="ID_GOLONGAN" class="form-control">
						<option value="">-- Silahkan Pilih --</option>
						<?php if (isset($golongans) && is_array($golongans) && count($golongans)):?>
						<?php foreach($golongans as $record):?>
							<option value="<?php echo $record->ID?>" <?php if(isset($detail_riwayat->ID_GOLONGAN))  echo  ($detail_riwayat->ID_JENIS_HUKUMAN==$record->ID) ? "selected" : ""; ?>  <?php if(isset($detail_riwayat->ID_GOLONGAN))  echo  ($detail_riwayat->ID_GOLONGAN==$record->ID) ? "selected" : ""; ?>><?php echo $record->NAMA_PANGKAT; ?> | <?php echo $record->NAMA; ?></option>
							<?php endforeach;?>
						<?php endif;?>
					</select>
                    <span class='help-inline'><?php echo form_error('ID_GOLONGAN'); ?></span>
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
            
            <div class="control-group<?php echo form_error('SK_NOMOR') ? ' error' : ''; ?> col-sm-9">
                <?php echo form_label("SK NOMOR", 'SK NOMOR', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='SK_NOMOR' type='text' class="form-control" name='SK_NOMOR' maxlength='32' value="<?php echo set_value('SK_NOMOR', isset($detail_riwayat->SK_NOMOR) ? trim($detail_riwayat->SK_NOMOR) : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('SK_NOMOR'); ?></span>
                </div>
            </div>
            <div class="control-group col-sm-3">
				<label for="inputNAMA" class="control-label">TANGGAL MULAI</label>
				<div class="input-group date">
				  <div class="input-group-addon">
					<i class="fa fa-calendar"></i>
				  </div>
					<input type='text' class="form-control pull-right datepicker" name='TANGGAL_MULAI_HUKUMAN'  value="<?php echo set_value('TANGGAL_MULAI_HUKUMAN', isset($detail_riwayat->TANGGAL_MULAI_HUKUMAN) ? $detail_riwayat->TANGGAL_MULAI_HUKUMAN : ''); ?>" />
					<span class='help-inline'><?php echo form_error('TANGGAL_MULAI_HUKUMAN'); ?></span>
				</div>
			</div> 
			 <div class="control-group<?php echo form_error('MASA_TAHUN') ? ' error' : ''; ?> col-sm-3">
                <?php echo form_label("MASA TAHUN", 'MASA_TAHUN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='MASA_TAHUN' type='text' class="form-control" name='MASA_TAHUN' maxlength='32' value="<?php echo set_value('MASA_TAHUN', isset($detail_riwayat->MASA_TAHUN) ? trim($detail_riwayat->MASA_TAHUN) : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('MASA_TAHUN'); ?></span>
                </div>
            </div>
             <div class="control-group<?php echo form_error('MASA_BULAN') ? ' error' : ''; ?> col-sm-3">
                <?php echo form_label("MASA BULAN", 'MASA BULAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='MASA_BULAN' type='text' class="form-control" name='MASA_BULAN' maxlength='32' value="<?php echo set_value('MASA_BULAN', isset($detail_riwayat->MASA_BULAN) ? trim($detail_riwayat->MASA_BULAN) : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('MASA_BULAN'); ?></span>
                </div>
            </div>
            <div class="control-group col-sm-3">
				<label for="inputNAMA" class="control-label">TANGGAL AKHIR</label>
				<div class="input-group date">
				  <div class="input-group-addon">
					<i class="fa fa-calendar"></i>
				  </div>
					<input type='text' class="form-control pull-right datepicker" name='TANGGAL_AKHIR_HUKUMAN'  value="<?php echo set_value('TANGGAL_AKHIR_HUKUMAN', isset($detail_riwayat->TANGGAL_AKHIR_HUKUMAN) ? $detail_riwayat->TANGGAL_AKHIR_HUKUMAN : ''); ?>" />
					<span class='help-inline'><?php echo form_error('TANGGAL_AKHIR_HUKUMAN'); ?></span>
				</div>
			</div> 
			<div class="control-group<?php echo form_error('NO_PP') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("NO PP", 'NO_PP', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='NO_PP' type='text' class="form-control" name='NO_PP' maxlength='32' value="<?php echo set_value('NO_PP', isset($detail_riwayat->NO_PP) ? trim($detail_riwayat->NO_PP) : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('NO_PP'); ?></span>
                </div>
            </div>
             <div class="control-group col-sm-3">
				<label for="inputNAMA" class="control-label">TANGGAL SK PEMBATALAN</label>
				<div class="input-group date">
				  <div class="input-group-addon">
					<i class="fa fa-calendar"></i>
				  </div>
					<input type='text' class="form-control pull-right datepicker" name='TANGGAL_SK_PEMBATALAN'  value="<?php echo set_value('TANGGAL_SK_PEMBATALAN', isset($detail_riwayat->TANGGAL_SK_PEMBATALAN) ? $detail_riwayat->TANGGAL_SK_PEMBATALAN : ''); ?>" />
					<span class='help-inline'><?php echo form_error('TANGGAL_SK_PEMBATALAN'); ?></span>
				</div>
			</div> 
            <div class="control-group<?php echo form_error('NO_SK_PEMBATALAN') ? ' error' : ''; ?> col-sm-9">
                <?php echo form_label("SK PEMBATALAN", 'NO_SK_PEMBATALAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='NO_SK_PEMBATALAN' type='text' class="form-control" name='NO_SK_PEMBATALAN' maxlength='32' value="<?php echo set_value('NO_SK_PEMBATALAN', isset($detail_riwayat->NO_SK_PEMBATALAN) ? trim($detail_riwayat->NO_SK_PEMBATALAN) : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('NO_SK_PEMBATALAN'); ?></span>
                </div>
            </div>
            <div class="control-group col-sm-9">
                <label for="inputNAMA" class="control-label">Berkas</label>
                <div class='controls'>
                    <div id="form_upload">
                      <input id="file_dokumen" name="file_dokumen" class="file" type="file" data-preview-file-type="pdf">
                    </div>
                    
                </div>
            </div> 
            <div class="control-group col-sm-3">
                <label for="inputNAMA" class="control-label"><br></label>
                <div class='controls'>
                    
                    <?php if(isset($detail_riwayat->FILE_BASE64) && $detail_riwayat->FILE_BASE64 != ""){ ?>
                        <a href="<?php echo base_url(); ?>pegawai/riwayathukdis/viewdoc/<?php echo $detail_riwayat->ID; ?>" class="btn btn-warning show-modal" tooltip="Lihat dokumen <?php echo isset($detail_riwayat->SK_NOMOR) ? $detail_riwayat->SK_NOMOR : ''; ?>" ><span class="fa fa-file-o"></span> Lihat</a>
                    <?php } ?>  
                </div>
            </div>
			</fieldset>
			 
        </div>
  		<div class="box-footer">
            <input type='submit' name='save' id="btnsavehukdis" class='btn btn-primary' value="Simpan Data" /> 
        </div>
    <?php echo form_close(); ?>
</div>
<script src="<?php echo base_url(); ?>themes/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/themes/explorer/theme.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/js/fileinput.js" type="text/javascript"></script>
<script>
	 $(".select2").select2({width: '100%'});
</script>
<script>
 	  
	$('#ID_JENIS_JABATAN').change(function() {
		var valuejenisjabatan = $('#ID_JENIS_JABATAN').val();
			$("#ID_JABATAN").empty().append("<option>loading...</option>"); //show loading...
			 
			var json_url = "<?php echo base_url(); ?>admin/masters/ref_jabatan/getbyjenis?jenis=" + encodeURIComponent(valuejenisjabatan);
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
    var form = $("#form-riwayat-hukdis-add");
    //Date picker
    $('.datepicker').datepicker({
      autoclose: true,format: 'yyyy-mm-dd'
    }).on("input change", function (e) {
        var date = $(this).datepicker('getDate');
        if(date)$("[name=TAHUN]",form).val(date.getFullYear());
    });
</script>
<script>
	$("#btnsavehukdis").click(function(){
		submitdatahukdis();
		return false; 
	});	
	$("#frmA").submit(function(){
		submitdatahukdis();
		return false; 
	});	
	function submitdatahukdis(){
		var the_data = new FormData(document.getElementById("frmhukdis"));
		var json_url = "<?php echo base_url() ?>pegawai/riwayathukdis/save";
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
                    $("#modal-custom-global").trigger("sukses-tambah-riwayat-hukdis");
					$("#modal-custom-global").modal("hide");
                }
                else {
                    $("#form-riwayat-hukdis-add .messages").empty().append(data.msg);
                }
			}});
		return false; 
	}
</script>