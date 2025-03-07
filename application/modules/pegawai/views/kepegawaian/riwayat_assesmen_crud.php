<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/select2/select2.min.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/css/dropzone/dropzone.min.css">
<script src="<?php echo base_url(); ?>themes/admin/js/dropzone/dropzone.min.js"></script>
<?php 
    $id = isset($detail_riwayat->ID) ? $detail_riwayat->ID : '';
?>
<div class='box box-warning' id="form-riwayat-assesmen-add">
    <?php echo form_open($this->uri->uri_string(), 'id="frm"'); ?>
	<div class="box-body">
        <div class="messages">
        </div>
            
        <fieldset>
        <legend>Asesment Potensi</legend>   
        <input id='ID' type='hidden' class="form-control" name='ID' maxlength='32' value="<?php echo set_value('ID', isset($detail_riwayat->ID) ? trim($detail_riwayat->ID) : ''); ?>" />
            <input id='PNS_ID' type='hidden' class="form-control" name='PNS_ID' maxlength='32' value="<?php echo set_value('PNS_ID', isset($PNS_ID) ? trim($PNS_ID) : ''); ?>" />
             
            <div class="control-group<?php echo form_error('TAHUN') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("TAHUN", 'TAHUN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='TAHUN' type='text' class="form-control" name='TAHUN' maxlength='32' value="<?php echo set_value('TAHUN', isset($detail_riwayat->TAHUN) ? trim($detail_riwayat->TAHUN) : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('TAHUN'); ?></span>
                </div>
            </div>  
            <div class="control-group<?php echo form_error('FILE_UPLOAD') ? ' error' : ''; ?> col-lg-6">
                <?php echo form_label("FILE LAP LENGKAP 1", 'FILE_UPLOAD', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <div class="dropzone well well-sm dropfile">
                    </div>
                    <span class='help-inline'><?php echo form_error('FILE_UPLOAD'); ?></span>
                </div>
                <div class="filecontenttriwulan2">
                    <div class="input-group divtriwulan2">
                       <div class="input-group-addon">File</div>
                       <input id='FILE_UPLOAD' readonly type='text' name='FILE_UPLOAD' maxlength='100' class="form-control just-upload-field" value="<?php echo set_value('FILE_UPLOAD', isset($detail_riwayat->FILE_UPLOAD) ? trim($detail_riwayat->FILE_UPLOAD) : ''); ?>" />
                       <div class="input-group-addon"><a href="<?php echo base_url().trim($this->settings_lib->item('site.urluploaded')); ?><?php echo isset($detail_riwayat->FILE_UPLOAD) ? trim($detail_riwayat->FILE_UPLOAD) : ''; ?>" target="_blank"><i class="fa fa-mail-forward"></i></a></div>
                       <span class="input-group-btn">
                         <button type="button" kode="<?php echo isset($detail_riwayat->ID) ? trim($detail_riwayat->ID) : ''; ?>" class="btn btn-info btn-flat deletefile">X</button>
                       </span>
                    </div>
               </div>
            </div>

            <div class="control-group<?php echo form_error('FILE_UPLOAD') ? ' error' : ''; ?> col-lg-6">
                <?php echo form_label("FILE LAP FEEDBACK 1", 'FILE_UPLOAD', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <div class="dropzone well well-sm dropfileFILE_UPLOAD_FB_POTENSI">
                    </div>
                    <span class='help-inline'><?php echo form_error('FILE_UPLOAD'); ?></span>
                </div>
                <div class="filecontenttriwulan2">
                    <div class="input-group divtriwulan2">
                       <div class="input-group-addon">File</div>
                       <input id='FILE_UPLOAD_FB_POTENSI' readonly type='text' name='FILE_UPLOAD_FB_POTENSI' maxlength='100' class="form-control just-upload-field" value="<?php echo set_value('FILE_UPLOAD_FB_POTENSI', isset($detail_riwayat->FILE_UPLOAD_FB_POTENSI) ? trim($detail_riwayat->FILE_UPLOAD) : ''); ?>" />
                       <div class="input-group-addon"><a href="<?php echo base_url().trim($this->settings_lib->item('site.urluploaded')); ?><?php echo isset($detail_riwayat->FILE_UPLOAD_FB_POTENSI) ? trim($detail_riwayat->FILE_UPLOAD_FB_POTENSI) : ''; ?>" target="_blank"><i class="fa fa-mail-forward"></i></a></div>
                       <span class="input-group-btn">
                         <button type="button" kode="<?php echo isset($detail_riwayat->ID) ? trim($detail_riwayat->ID) : ''; ?>" class="btn btn-info btn-flat deletefileFILE_UPLOAD_FB_POTENSI">X</button>
                       </span>
                    </div>
               </div>
            </div>
        </fieldset>

            <fieldset>
                <legend>Asesment Lanjutan</legend>     
                <div class="control-group<?php echo form_error('FILE_UPLOAD_LENGKAP_PT') ? ' error' : ''; ?> col-lg-6">
                    <?php echo form_label("FILE LAP LENGKAP", 'FILE_UPLOAD_LENGKAP_PT', array('class' => 'control-label')); ?>
                    <div class='controls'>
                        <div class="dropzone well well-sm dropfileFILE_UPLOAD_LENGKAP_PT">
                        </div>
                        <span class='help-inline'><?php echo form_error('FILE_UPLOAD_LENGKAP_PT'); ?></span>
                    </div>
                    <div class="filecontenttriwulan2">
                        <div class="input-group divtriwulan2">
                           <div class="input-group-addon">File</div>
                           <input id='FILE_UPLOAD_LENGKAP_PT' readonly type='text' name='FILE_UPLOAD_LENGKAP_PT' maxlength='100' class="form-control just-upload-field" value="<?php echo set_value('FILE_UPLOAD_LENGKAP_PT', isset($detail_riwayat->FILE_UPLOAD_LENGKAP_PT) ? trim($detail_riwayat->FILE_UPLOAD_LENGKAP_PT) : ''); ?>" />
                           <div class="input-group-addon"><a href="<?php echo base_url().trim($this->settings_lib->item('site.urluploaded')); ?><?php echo isset($detail_riwayat->FILE_UPLOAD_LENGKAP_PT) ? trim($detail_riwayat->FILE_UPLOAD_LENGKAP_PT) : ''; ?>" target="_blank"><i class="fa fa-mail-forward"></i></a></div>
                           <span class="input-group-btn">
                             <button type="button" kode="<?php echo isset($detail_riwayat->ID) ? trim($detail_riwayat->ID) : ''; ?>" class="btn btn-info btn-flat deletefileFILE_UPLOAD_LENGKAP_PT">X</button>
                           </span>
                        </div>
                   </div>
                </div>

                <div class="control-group<?php echo form_error('FILE_UPLOAD_FB_PT') ? ' error' : ''; ?> col-lg-6">
                    <?php echo form_label("FILE LAP FEEDBACK", 'FILE_UPLOAD_FB_PT', array('class' => 'control-label')); ?>
                    <div class='controls'>
                        <div class="dropzone well well-sm dropfileFILE_UPLOAD_FB_PT">
                        </div>
                        <span class='help-inline'><?php echo form_error('FILE_UPLOAD_FB_PT'); ?></span>
                    </div>
                    <div class="filecontenttriwulan2">
                        <div class="input-group divtriwulan2">
                           <div class="input-group-addon">File</div>
                           <input id='FILE_UPLOAD_FB_PT' readonly type='text' name='FILE_UPLOAD_FB_PT' maxlength='100' class="form-control just-upload-field" value="<?php echo set_value('FILE_UPLOAD_FB_PT', isset($detail_riwayat->FILE_UPLOAD_FB_POTENSI) ? trim($detail_riwayat->FILE_UPLOAD_FB_PT) : ''); ?>" />
                           <div class="input-group-addon"><a href="<?php echo base_url().trim($this->settings_lib->item('site.urluploaded')); ?><?php echo isset($detail_riwayat->FILE_UPLOAD_FB_PT) ? trim($detail_riwayat->FILE_UPLOAD_FB_POTENSI) : ''; ?>" target="_blank"><i class="fa fa-mail-forward"></i></a></div>
                           <span class="input-group-btn">
                             <button type="button" kode="<?php echo isset($detail_riwayat->ID) ? trim($detail_riwayat->ID) : ''; ?>" class="btn btn-info btn-flat deletefileFILE_UPLOAD_FB_PT">X</button>
                           </span>
                        </div>
                   </div>
                </div>
            </fieldset>
        </div>
  		<div class="box-footer">
            <input type='submit' name='save' id="btnsave" class='btn btn-primary' value="Simpan Data" /> 
        </div>
    <?php echo form_close(); ?>
</div>
<script>
 	 
    var form = $("#form-riwayat-assesmen-add");
    //Date picker
    $('.datepicker').datepicker({
      autoclose: true,format: 'yyyy-mm-dd'
    }).on("input change", function (e) {
        var date = $(this).datepicker('getDate');
        if(date)$("[name=TAHUN]",form).val(date.getFullYear());
    });
</script>
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
		
		var json_url = "<?php echo base_url() ?>pegawai/riwayatassesmen/save";
		 $.ajax({    
		 	type: "POST",
			url: json_url,
			data: $("#frm").serialize(),
            dataType: "json",
			success: function(data){ 
                if(data.success){
                    swal("Pemberitahuan!", data.msg, "success");
                    $("#modal-custom-global").trigger("sukses-tambah-riwayat-assesmen");
					$("#modal-custom-global").modal("hide");
                }
                else {
                    $("#form-riwayat-assesmen-add .messages").empty().append(data.msg);
                }
			}});
		return false; 
	}

    Dropzone.autoDiscover = true;
    var drtriwulan1 = new Dropzone(".dropfile",{
         autoProcessQueue: true,
         url: "<?php echo base_url() ?>pegawai/riwayatassesmen/uploadberkas",
         maxFilesize: 20,
         parallelUploads : 10,
         method:"post",
         acceptedFiles:"application/pdf",
         paramName:"userfile",
         dictDefaultMessage:"<img src='<?php echo base_url(); ?>assets/images/dropico.png' width='50px'/><br>Drop Berkas assesmen disini",
         dictInvalidFileType:"Type file ini tidak dizinkan",
         addRemoveLinks:true
         });
         drtriwulan1.on("sending",function(a,b,c){
             a.token=Math.random();
             console.log('mengirim');           
         });
         drtriwulan1.on("success",function(a,b){
            swal("Pemberitahuan!", "Upload berhasil", "success");
            $("#FILE_UPLOAD").val(b);
    });
    Dropzone.autoDiscover = true;
    var drtriwulan1 = new Dropzone(".dropfileFILE_UPLOAD_FB_POTENSI",{
         autoProcessQueue: true,
         url: "<?php echo base_url() ?>pegawai/riwayatassesmen/uploadberkas",
         maxFilesize: 20,
         parallelUploads : 10,
         method:"post",
         acceptedFiles:"application/pdf",
         paramName:"userfile",
         dictDefaultMessage:"<img src='<?php echo base_url(); ?>assets/images/dropico.png' width='50px'/><br>Drop Berkas assesmen disini",
         dictInvalidFileType:"Type file ini tidak dizinkan",
         addRemoveLinks:true
         });
         drtriwulan1.on("sending",function(a,b,c){
             a.token=Math.random();
             console.log('mengirim');           
         });
         drtriwulan1.on("success",function(a,b){
            swal("Pemberitahuan!", "Upload berhasil", "success");
            $("#FILE_UPLOAD_FB_POTENSI").val(b);
    });

    Dropzone.autoDiscover = true;
    var div3 = new Dropzone(".dropfileFILE_UPLOAD_LENGKAP_PT",{
         autoProcessQueue: true,
         url: "<?php echo base_url() ?>pegawai/riwayatassesmen/uploadberkas",
         maxFilesize: 20,
         parallelUploads : 10,
         method:"post",
         acceptedFiles:"application/pdf",
         paramName:"userfile",
         dictDefaultMessage:"<img src='<?php echo base_url(); ?>assets/images/dropico.png' width='50px'/><br>Drop Berkas assesmen disini",
         dictInvalidFileType:"Type file ini tidak dizinkan",
         addRemoveLinks:true
         });
         div3.on("sending",function(a,b,c){
             a.token=Math.random();
             //console.log('mengirim');           
         });
         div3.on("success",function(a,b){
            swal("Pemberitahuan!", "Upload berhasil", "success");
            $("#FILE_UPLOAD_LENGKAP_PT").val(b);
    });
    Dropzone.autoDiscover = true;
    var div4 = new Dropzone(".dropfileFILE_UPLOAD_FB_PT",{
         autoProcessQueue: true,
         url: "<?php echo base_url() ?>pegawai/riwayatassesmen/uploadberkas",
         maxFilesize: 20,
         parallelUploads : 10,
         method:"post",
         acceptedFiles:"application/pdf",
         paramName:"userfile",
         dictDefaultMessage:"<img src='<?php echo base_url(); ?>assets/images/dropico.png' width='50px'/><br>Drop Berkas assesmen disini",
         dictInvalidFileType:"Type file ini tidak dizinkan",
         addRemoveLinks:true
         });
         div4.on("sending",function(a,b,c){
             a.token=Math.random();
             console.log('mengirim');           
         });
         div4.on("success",function(a,b){
        swal("Pemberitahuan!", "Upload berhasil", "success");
        $("#FILE_UPLOAD_FB_PT").val(b);
    });
    $("body").on('click','.deletefile',function(event){
            event.preventDefault();
            var kode =$(this).attr("kode");
                swal({
                    title: "Anda Yakin?",
                    text: "Hapus File!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: 'btn-danger',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: "Tidak, Batalkan!",
                    closeOnConfirm: false,
                    closeOnCancel: false
                },
                function (isConfirm) {
                    if (isConfirm) {
                        var post_data = "kode="+kode;
                        $.ajax({
                                url: "<?php echo base_url() ?>pegawai/riwayatassesmen/deletefile/"+kode,
                                dataType: "html",
                                timeout:180000,
                                success: function (result) {
                                    if(result="success"){
                                        swal("FIle berhasil di hapus!", result, "success");
                                        $("#FILE_UPLOAD").val("");    
                                    }else{
                                        swal("file gagal dihapus!", result, "error");
                                    }
                                    
                            },
                            error : function(error) {
                                alert(error);
                            } 
                        });        
                        
                    } else {
                        swal("Batal", "", "error");
                    }
                });
        });
</script>