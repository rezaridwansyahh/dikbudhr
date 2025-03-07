<link href="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/css/fileinput.css" media="all" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/themes/explorer/theme.css" media="all" rel="stylesheet" type="text/css"/>
<?php 
    $id = isset($detail_riwayat->ID) ? $detail_riwayat->ID : '';
?>
<div class='box box-warning' id="form-diklat-fungsional-add">
	<div class="box-body">
        <div class="messages">
        </div>
    <?php echo form_open($this->uri->uri_string(), 'id="frmpindahunit"'); ?>
        <fieldset>
            <input id='ID' type='hidden' class="form-control" name='ID' maxlength='32' value="<?php echo set_value('ID', isset($detail_riwayat->ID) ? trim($detail_riwayat->ID) : ''); ?>" />
            <input id='PNS_ID' type='hidden' class="form-control" name='PNS_ID' maxlength='32' value="<?php echo set_value('PNS_ID', isset($PNS_ID) ? trim($PNS_ID) : ''); ?>" />
            <div class="control-group<?php echo form_error('ID_SATUAN_KERJA') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label('SATUAN KERJA', 'ID_SATUAN_KERJA', array('class' => 'control-label')); ?>
                <div class='controls'>
                	<select name="ID_SATUAN_KERJA" id="ID_SATUAN_KERJA" class="form-control select2 " width="100%">
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
           <div class="control-group<?php echo form_error('ID_UNOR_BARU') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label('Unit Organisasi Baru', 'Unit Organisasi Baru', array('class' => 'control-label')); ?>
                <div class='controls'>
                	<select name="ID_UNOR_BARU" id="ID_UNOR_BARU" class="form-control">
						<option value="">-- Silahkan Pilih --</option>
						<?php if (isset($recunor) && is_array($recunor) && count($recunor)):?>
						<?php foreach($recunor as $record):?>
							<option value="<?php echo $record->ID?>" <?php if(isset($detail_riwayat->ID_UNOR_BARU))  echo  (trim($detail_riwayat->ID_UNOR_BARU)==trim($record->ID)) ? "selected" : ""; ?>><?php echo $record->NAMA_UNOR; ?></option>
							<?php endforeach;?>
						<?php endif;?>
					</select>
                    <span class='help-inline'><?php echo form_error('ID_UNOR_BARU'); ?></span>
                </div>
            </div>
            
            <div class="control-group<?php echo form_error('ID_INSTANSI') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label('INSTANSI BARU', 'ID INSTANSI', array('class' => 'control-label')); ?>
                <div class='controls'>
                	<select name="ID_INSTANSI" id="ID_INSTANSI" class="form-control">
						<?php 
                            if($selectedInstansiBaru){
                                echo "<option selected value='".$selectedInstansiBaru->ID."'>".$selectedInstansiBaru->NAMA."</option>";
                            }
                        ?>
					</select>
                    <span class='help-inline'><?php echo form_error('ID_INSTANSI'); ?></span>
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
                        <a href="<?php echo base_url(); ?>pegawai/riwayatkepangkatan/viewdoc/<?php echo $detail_riwayat->ID; ?>" class="btn btn-warning show-modal" tooltip="Lihat dokumen <?php echo isset($detail_riwayat->SK_NOMOR) ? $detail_riwayat->SK_NOMOR : ''; ?>" ><span class="fa fa-file-o"></span> Lihat</a>
                    <?php } ?>  
                </div>
            </div>
        </div>
  		<div class="box-footer">
            <input type='submit' name='save' id="btnsavefrmpindahunit" class='btn btn-primary' value="Simpan Data" /> 
        </div>
    <?php echo form_close(); ?>
</div>
<script src="<?php echo base_url(); ?>themes/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/themes/explorer/theme.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/js/fileinput.js" type="text/javascript"></script>
<script>
    var form = $("#form-diklat-fungsional-add");
    //Date picker
    $('.datepicker').datepicker({
      autoclose: true,format: 'yyyy-mm-dd'
    }).on("input change", function (e) {
        var date = $(this).datepicker('getDate');
        if(date)$("[name=TAHUN]",form).val(date.getFullYear());
    });
</script>
<script>
	 $(".select2").select2({width: '100%'});
</script>
<script>
	$('#ID_SATUAN_KERJA').change(function() {
		var valuesatker = $('#ID_SATUAN_KERJA').val();
			$("#ID_UNOR_BARU").empty().append("<option>loading...</option>"); //show loading...
			 
			var json_url = "<?php echo base_url(); ?>pegawai/manage_unitkerja/getbysatker?satker=" + encodeURIComponent(valuesatker);
			$.getJSON(json_url,function(data){
				$("#ID_UNOR_BARU").empty(); 
				if(data==""){
					$("#ID_UNOR_BARU").append("<option value=\"\">Silahkan Pilih </option>");
				}
				else{
					$("#ID_UNOR_BARU").append("<option value=\"\">Silahkan Pilih</option>");
					for(i=0; i<data.id.length; i++){
						$("#ID_UNOR_BARU").append("<option value=\"" + data.id[i]  + "\">" + data.nama[i] +"</option>");
					}
				}
				
			});
			$("#ID_UNOR_BARU").select2("updateResults");
			return false;
	});
    $("#ID_UNOR_BARU1").select2({
        placeholder: 'Cari Unit Organisasi Baru...',
        width: '100%',
        minimumInputLength: 3,
        allowClear: true,
        ajax: {
            url: '<?php echo site_url("pegawai/riwayatpindahunitkerja/get_unor_ajax");?>',
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
    $("#ID_INSTANSI").select2({
        placeholder: 'Cari Data Instansi..',
        width: '100%',
        minimumInputLength: 3,
        allowClear: true,
        ajax: {
            url: '<?php echo site_url("pegawai/riwayatpindahunitkerja/get_instansi_ajax");?>',
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
	$("#btnsavefrmpindahunit").click(function(){
		submitdatafrmpindahunit();
		return false; 
	});	
	$("#frma").submit(function(){
		submitdatafrmpindahunit();
		return false; 
	});	
	function submitdatafrmpindahunit(){
		var the_data = new FormData(document.getElementById("frmpindahunit"));
		var json_url = "<?php echo base_url() ?>pegawai/riwayatpindahunitkerja/save";
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
                    $("#modal-custom-global").trigger("sukses-tambah-riwayat-pindah_unit_kerja");
					$("#modal-custom-global").modal("hide");
                }
                else {
                    $("#form-diklat-fungsional-add .messages").empty().append(data.msg);
                }
			}});
		return false; 
	}
</script>