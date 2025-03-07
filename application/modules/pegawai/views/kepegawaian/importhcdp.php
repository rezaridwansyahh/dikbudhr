<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/select2/select2.min.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/css/dropzone/dropzone.min.css">
<script src="<?php echo base_url(); ?>themes/admin/js/dropzone/dropzone.min.js"></script>
<?php 
    $id = isset($detail_riwayat->ID) ? $detail_riwayat->ID : '';
?>
<div class='alert alert-block alert-warning fade in'>
    <a class='close' data-dismiss='alert'>&times;</a>
    <h4 class='alert-heading'>
        Keterangan
    </h4>
    Silahkan masukan tahun kemudian klik tombol ambil data, untuk mengambil data dari aplikasi hcdp
</div>
<div class='box box-warning' id="form-riwayat-assesmen-add">
	<div class="box-body">
        <div class="messages">
        </div>
    <?php echo form_open($this->uri->uri_string(), 'id="frm"'); ?>
    <fieldset>
            <div class="control-group<?php echo form_error('TAHUN') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Masukan TAHUN", 'TAHUN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='TAHUN' type='text' class="form-control" name='TAHUN' maxlength='32' value="<?php echo set_value('TAHUN', isset($detail_riwayat->TAHUN) ? trim($detail_riwayat->TAHUN) : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('TAHUN'); ?></span>
                </div>
            </div>
             

			</fieldset>
			 
        </div>
  		<div class="box-footer">
            <input type='submit' name='save' id="btnsave" class='btn btn-primary' value="Ambil Data" /> 
        </div>
    <?php echo form_close(); ?>
</div>
 
<script>
	$("#btnsave").click(function(){
		submitdata();
		return false; 
	});	
	$("#frmA").submit(function(){
		submitdata();
		return false; 
	});	
	function submitdata(){
		
		var json_url = "<?php echo base_url() ?>pegawai/riwayatassesmen/saveimport";
		 $.ajax({    
		 	type: "POST",
			url: json_url,
			data: $("#frm").serialize(),
            dataType: "json",
			success: function(data){ 
                if(data.success){
                    swal("Pemberitahuan!", data.msg, "success");
                    
                }
                else {
                	swal("Pemberitahuan!", data.msg, "error");
                }
			}});
		return false; 
	}
 
</script>