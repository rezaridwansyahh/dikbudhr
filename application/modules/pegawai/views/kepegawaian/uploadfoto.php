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
		 url: "<?php echo base_url() ?>admin/kepegawaian/pegawai/savefoto",
		 maxFilesize: 1,
		 parallelUploads : 10,
		 method:"post",
		 acceptedFiles:"image/*",
		 paramName:"userfile",
		 dictDefaultMessage:"<img src='<?php echo base_url(); ?>assets/images/dropico.png' width='50px'/><br>drop foto disini, <br> Ketentuan : Ukuran file Max 1MB,Max dimensi 800x1200px, Tipe pas foto, file yang di perkenankan .png|jpg|jpeg)",
		 dictInvalidFileType:"Type file ini tidak dizinkan",
		 addRemoveLinks:true,
		 init: function () {
			   	this.on("success", function (file,response) {
			   	var data_n=JSON.parse(response);
			   	if(data_n.namafile!=""){
					swal("Pemberitahuan!", "Upload Selesai", "success");
					$('#photopegawai').attr('src',"<?php echo base_url(); ?>assets/images/"+data_n.namafile);
					$("#modal-global").modal("hide");
				}
			});
		   }
		 });
		foto_upload.on("sending",function(a,b,c){
			 a.token=Math.random();
			 c.append('token_foto',a.token);
			 c.append('PNS_ID',"<?php echo $PNS_ID; ?>");
			 c.append('PNS_NIP',"<?php echo $PNS_NIP; ?>");
			 console.log('mengirim');           
		 });
	foto_upload.processQueue();
</script>


