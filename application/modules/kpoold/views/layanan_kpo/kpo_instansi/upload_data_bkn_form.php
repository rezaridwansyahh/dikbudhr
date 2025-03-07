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
		 url: "<?php echo $module_url ?>/do_upload",
		 maxFilesize: 1,
		 parallelUploads : 10,
		 method:"post",
		 acceptedFiles:"application/vnd.ms-excel",
		 paramName:"userfile",
		 dictDefaultMessage:"<img src='<?php echo base_url(); ?>assets/images/dropico.png' width='50px'/><br>drop file disini, <br> Ketentuan : Ukuran file Max 1MB,file yang di perkenankan .xls)",
		 dictInvalidFileType:"Type file ini tidak dizinkan",
		 addRemoveLinks:true,
		 init: function () {
			   	this.on("success", function (file,response) {
			   	var data_n=JSON.parse(response);
			   	if(data_n.namafile!=""){
					swal("Pemberitahuan!", "Transaksi berhasil", "success");
                    $("#modal-custom-global").trigger("sukses-upload-kpo-bkn");
					$("#modal-custom-global").modal("hide");
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