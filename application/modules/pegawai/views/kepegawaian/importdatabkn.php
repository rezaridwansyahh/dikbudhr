<link href="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/css/fileinput.css" media="all" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/themes/explorer/theme.css" media="all" rel="stylesheet" type="text/css"/>
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
    <?php echo form_open($this->uri->uri_string(), 'id="frm"'); ?>
	   <div class="box-body">
            <div class="messages">
            </div>
            <fieldset>
                <div class="control-group<?php echo form_error('NIP') ? ' error' : ''; ?>">
                    <?php echo form_label("Masukan NIP", 'NIP', array('class' => 'control-label')); ?>
                    <div class='controls'>
                        <input id='nip_bkn' type='text' class="form-control" name='nip_bkn' value="" />
                        <span class='help-inline'>Jika lebih dari satu nip, pisahkan dengan karakter "koma"</span>
                    </div>
                </div>
			</fieldset>
			 
        </div>
  		<div class="box-footer">
            <input type='submit' name='save' id="btnsinkron" class='btn btn-primary' value="Ambil Data" /> 
        </div>
    <?php echo form_close(); ?>
    <center><b>Atau dari template excell</b></center>
    <?php echo form_open($this->uri->uri_string(), 'id="frm-excell"'); ?>
       <div class="box-body">
            <div class="messages">
            </div>
            <fieldset>
                <div class="control-group<?php echo form_error('NIP') ? ' error' : ''; ?>">
                    <?php echo form_label("Browse file excell NIP", 'NIP', array('class' => 'control-label')); ?>
                    <div class='controls'>
                        <input id="file_nips" name="file_nips" class="file" type="file" data-preview-file-type="xls" data-show-upload="false" title="Silahkan Pilih file NIP">
                    <span class='help-inline'></span>
                    </div>
                </div>
            </fieldset>
             
        </div>
        <div class="box-footer">
            <input type='submit' name='save' id="btnsinkron" class='btn btn-primary' value="Ambil Data" /> 
        </div>
    <?php echo form_close(); ?>
    
</div>
<script src="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/themes/explorer/theme.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/js/fileinput.js" type="text/javascript"></script>
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
	
	var json_url = "<?php echo base_url() ?>pegawai/bkn/getpegawaibknnew";
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
$("#frm-excell").submit(function(){
    submitfile();
    return false; 
}); 
function submitfile(){
    var the_data = new FormData(document.getElementById("frm-excell"));
    var json_url = "<?php echo base_url() ?>pegawai/bkn/getdatabyexcell";
     $.ajax({    
        type: "POST",
        url: json_url,
        data: the_data,
        dataType: "json",
        processData: false, // tell jQuery not to process the data
        contentType: false, // tell jQuery not to set contentType
        success: function(data){ 
            if(data.success){
                swal({
                    title: "Pemberitahuan!",
                    text: data.msg,
                    type: "success",
                    timer: 4000,
                    showConfirmButton: true
                }, function () {
                    location.reload();
                });
                $("#modal-global").modal("hide");
                $('#btnsinkron').removeClass('disabled');
                $('#btnsinkron').text('Simpan Data');
                $table.ajax.reload(null,true);
            }
            else {
                $('#btnsinkron').removeClass('disabled');
                $('#btnsinkron').text('Simpan Data');
                $(".messages").empty().append(data.notes);
                swal("Pemberitahuan!", data.msg, "error");
            }
        }});
    return false; 
}
</script>