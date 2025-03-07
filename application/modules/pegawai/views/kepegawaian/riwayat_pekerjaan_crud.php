<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/select2/select2.min.css">
<link href="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/css/fileinput.css" media="all" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/themes/explorer/theme.css" media="all" rel="stylesheet" type="text/css"/>

<?php 
    $id = isset($detail_riwayat->ID) ? $detail_riwayat->ID : '';
?>
<div class='box box-warning' id="form-riwayat-pekerjaan-add">
	<div class="box-body">
        <div class="messages">
        </div>
    <?php echo form_open($this->uri->uri_string(), 'id="frm-pekerjaan"'); ?>
    <fieldset>
            <input id='ID' type='hidden' class="form-control" name='ID' maxlength='32' value="<?php echo set_value('ID', isset($detail_riwayat->ID) ? trim($detail_riwayat->ID) : ''); ?>" />
            <input id='PNS_ID' type='hidden' class="form-control" name='PNS_ID' maxlength='32' value="<?php echo set_value('PNS_ID', isset($PNS_ID) ? trim($PNS_ID) : ''); ?>" />
            <div class="control-group<?php echo form_error('NAMA_PERUSAHAAN') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("NAMA PERUSAHAAN", 'NAMA_PERUSAHAAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='NAMA_PERUSAHAAN' type='text' class="form-control" name='NAMA_PERUSAHAAN' maxlength='200' value="<?php echo set_value('NAMA_PERUSAHAAN', isset($detail_riwayat->NAMA_PERUSAHAAN) ? trim($detail_riwayat->NAMA_PERUSAHAAN) : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('NAMA_PERUSAHAAN'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('SEBAGAI') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("SEBAGAI", 'SEBAGAI', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='SEBAGAI' type='text' class="form-control" name='SEBAGAI' maxlength='200' value="<?php echo set_value('SEBAGAI', isset($detail_riwayat->SEBAGAI) ? trim($detail_riwayat->SEBAGAI) : ''); ?>" />
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
                        <a href="<?php echo base_url(); ?>pegawai/riwayatpekerjaan/viewdoc/<?php echo $detail_riwayat->ID; ?>" class="btn btn-warning show-modal" tooltip="Lihat dokumen <?php echo isset($detail_riwayat->NOMOR_IJASAH) ? $detail_riwayat->NOMOR_IJASAH : ''; ?>" ><span class="fa fa-file-o"></span> Lihat</a>
                    <?php } ?>  
                </div>
            </div>
            </fieldset>
        </div>
  		<div class="box-footer">
            <input type='submit' name='save' id="btnsavepekerjaan" class='btn btn-primary' value="Simpan Data" /> 
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
  
    var form = $("#form-riwayat-pekerjaan-add");
    //Date picker
    $('.datepicker').datepicker({
      autoclose: true,format: 'yyyy-mm-dd'
    }).on("input change", function (e) {
        var date = $(this).datepicker('getDate');
        if(date)$("[name=TAHUN]",form).val(date.getFullYear());
    });
</script>
<script>
	$("#btnsavepekerjaan").click(function(){
		submitdatapekerjaan();
		return false; 
	});	
	$("#frmA").submit(function(){
		submitdatapekerjaan();
		return false; 
	});	
	function submitdatapekerjaan(){
		var the_data = new FormData(document.getElementById("frm-pekerjaan"));
		var json_url = "<?php echo base_url() ?>pegawai/riwayatpekerjaan/save";
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
                    $("#modal-custom-global").trigger("sukses-tambah-riwayat-pekerjaan");
					 $("#modal-custom-global").modal("hide");
                }
                else {
                    $("#form-riwayat-pekerjaan-add .messages").empty().append(data.msg);
                }
			}});
		return false; 
	}
</script>