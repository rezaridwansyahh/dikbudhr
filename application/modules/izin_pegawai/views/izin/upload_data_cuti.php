<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/css/dropzone/dropzone.min.css">
<script src="<?php echo base_url(); ?>themes/admin/js/dropzone/dropzone.min.js"></script>
<!-- sweet alert -->
<script src="<?php echo base_url(); ?>themes/admin/js/sweetalert.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/css/sweetalert.css">
<div class="box box-info">
  <div class="box-body">
	  	<div class="control-group col-sm-12">
		  <div class='controls'>
			 <div class="dropzone well well-sm">
			 </div>
			</div>
	  	</div>
      
	</div> 
</div>  
<script>
Dropzone.autoDiscover = true;
    var foto_upload= new Dropzone(".dropzone",{
    	 autoProcessQueue: true,
		 url: "<?php echo base_url(); ?>admin/izin/izin_pegawai/do_upload",
		 maxFilesize: 5,
		 parallelUploads : 10,
		 method:"post",
		 acceptedFiles:"text/csv,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel,.xls",
		 paramName:"userfile",
		 dictDefaultMessage:"<img src='<?php echo base_url(); ?>assets/images/dropico.png' width='50px'/><br>drop file disini, <br> Ketentuan : Ukuran file Max 2MB,file yang di perkenankan .xls), <br>Untuk mengunduh format excell, silahkan klik <a href='<?php echo base_url(); ?>assets/templates/template_upload_cuti.xlsx'>link ini</a>",
		 dictInvalidFileType:"Type file ini tidak dizinkan",
		 addRemoveLinks:true,
		 init: function () {
			   	this.on("success", function (file,response) {
			   	var data_n = JSON.parse(response);
			   	if(data_n.success){
					swal("Pemberitahuan!", "Transaksi berhasil", "success");
					$table.ajax.reload(null,true);	
					$("#modal-global").modal("hide");
				}else{
					swal("Pemberitahuan!", data_n.message, "error");
				}
			});
		   }
		 });
		foto_upload.on("sending",function(a,b,c){
			 a.token=Math.random();
			 c.append('token_foto',a.token);
			 console.log('mengirim');           
		 });
	foto_upload.processQueue();
</script>