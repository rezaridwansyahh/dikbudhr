<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/css/dropzone/dropzone.min.css">
<script src="<?php echo base_url(); ?>themes/admin/js/dropzone/dropzone.min.js"></script>
<!-- sweet alert -->
<script src="<?php echo base_url(); ?>themes/admin/js/sweetalert.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/css/sweetalert.css">
<div class="box box-info">
  <div class="box-body">
        <div class="control-group<?php echo form_error('name') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Nama Template", 'Nama Template', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='name' type='text' class="form-control" name='name'  value="<?php echo set_value('name', isset($data->name) ? $data->name : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('name'); ?></span>
                </div>
        </div>
	  	<div class="control-group col-sm-12">
            <?php echo form_label("File Template", 'File Template', array('class' => 'control-label')); ?>
		    <div class='controls'>
                <div class="dropzone dropzone-file-dokumen well well-sm">
                </div>
			</div>
	  	</div>
        <div class="">
            <a class="btn btn-sm btn-warning btn-submit" >Simpan</a>
        </div>
	</div> 
</div>  
<script>
Dropzone.autoDiscover = true;
    $(document).ready(function(){
        $(".btn-submit").click(function(){
            console.log(foto_upload);
            foto_upload.processQueue();
        });
    });
    var foto_upload= new Dropzone(".dropzone-file-dokumen",{
    	 autoProcessQueue: false,
		 url: "<?php echo base_url() ?>templates/manage_templates/save",
		 maxFilesize: 10,
         maxFiles :1,
		 parallelUploads : 1,
		 method:"post",
		 acceptedFiles:"application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
		 paramName:"userfile",
		 dictDefaultMessage:"<img src='<?php echo base_url(); ?>assets/images/dropico.png' width='50px'/><br>drop File disini, <br> Ketentuan : Ukuran file Max 1MB Tipe pas foto, file yang di perkenankan .xlsx|.docx)",
		 dictInvalidFileType:"Type file ini tidak dizinkan",
		 addRemoveLinks:true,
		 init: function () {
			   	this.on("success", function (file,response) {
                    var data_n=JSON.parse(response);
                    if(data_n.namafile!=""){
                        swal("Pemberitahuan!", "Upload Selesai", "success");
                        $("#modal-custom-global").modal("hide");
                        $("#modal-custom-global").trigger("sukses-tambah-manage-templates");
                    }
                });
		   }
		 });
		foto_upload.on("sending",function(a,b,c){
			 a.token=Math.random();
             c.append("name",$("[name=name]").val());
			 c.append('id',"<?php echo $data->id; ?>");
		 });
	
</script>