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
    Silahkan masukan NIP untuk import data dari BKN<br>
    dengan syarat data NIP tersebut adalah pegawai dari kementerian Pendidikan dan Kebudayaan
</div>
<div class='box box-warning' id="form-riwayat-assesmen-add">
	<div class="box-body">
        <div class="messages">
        </div>
    <?php echo form_open($this->uri->uri_string(), 'id="frm"'); ?>
    <fieldset>
            <div class="control-group<?php echo form_error('NIP') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Masukan NIP", 'NIP', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='nip_bkn' type='text' class="form-control" name='nip_bkn' maxlength='32' value="" />
                    <span class='help-inline'><?php echo form_error('NIP'); ?></span>
                </div>
            </div>
             

			</fieldset>
			 
        </div>
  		<div class="box-footer">
            <input type='submit' name='save' id="btnsinkron" class='btn btn-primary' value="Ambil Data" /> 
        </div>
    <?php echo form_close(); ?>
</div>
 
<script>
	$("#btnsinkron").click(function(){
		submitdata();
		return false; 
	});	
	$("#frmA").submit(function(){
		submitdata();
		return false; 
	});	
	function submitdata(){
		
		var json_url = "<?php echo base_url() ?>admin/kepegawaian/pegawai/getpegawaibknnew";
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