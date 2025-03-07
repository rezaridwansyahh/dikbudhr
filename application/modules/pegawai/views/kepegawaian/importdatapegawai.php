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
		 url: "<?php echo base_url() ?>admin/kepegawaian/pegawai/runimport",
		 maxFilesize: 20,
		 parallelUploads : 10,
		 method:"post",
		 acceptedFiles: 'application/xls,application/excel,application/vnd.ms-excel,application/vnd.ms-excel; charset=binary,application/msexcel,application/x-excel,application/x-msexcel,.xls,.xlsx',
		 paramName:"userfile",
		 dictDefaultMessage:"<img src='<?php echo base_url(); ?>assets/images/dropico.png' width='50px'/><br>Drop/Browse file(.xls) pegawai disini",
		 dictInvalidFileType:"Type file ini tidak dizinkan",
		 addRemoveLinks:true,
		 init: function () {
			   	this.on("success", function (file,response) {
			   	var data_n=JSON.parse(response);
			   	if(data_n.namafile!=""){
					swal("Pemberitahuan!", data_n.message, "success");
					//$('#photopegawai').attr('src',"<?php echo base_url(); ?>assets/images/"+data_n.namafile);
					$("#modal-global").modal("hide");
				}
			});
		   }
		 });
		foto_upload.on("sending",function(a,b,c){
			 a.token=Math.random();
			 c.append('token_foto',a.token);
			 c.append('PNS_ID',"<?php echo $PNS_ID; ?>");
			 console.log('mengirim');           
		 });
	foto_upload.processQueue();
</script>


