<?php 
    $id = isset($data->id) ? $data->id : '';
?>
<div class='box box-warning' id="form-pendidikan-add">
	<div class="box-body">
        <div class="messages">
        </div>
    <?php echo form_open($this->uri->uri_string(), 'id="frm"'); ?>
        <fieldset>
            <input id='id' type='hidden' class="form-control" name='id' maxlength='32' value="<?php echo set_value('id', isset($id) ? trim($id) : ''); ?>" />
            
            <div class="control-group<?php echo form_error('name') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Nama API", 'Nama API', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='name' type='text' class="form-control" name='name'  value="<?php echo set_value('name', isset($data->name) ? $data->name : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('name'); ?></span>
                </div>
            </div>
           <div class="control-group<?php echo form_error('url') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Alamat metode", 'Alamat metode', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='url' type='text' class="form-control" name='url'  value="<?php echo set_value('url', isset($data->url) ? $data->url : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('url'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('description') ? ' description' : ''; ?> col-sm-12">
                <?php echo form_label("Keterangan", 'Keterangan', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='description' type='text' class="form-control" name='description'  value="<?php echo set_value('description', isset($data->description) ? $data->description : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('description'); ?></span>
                </div>
            </div>
            
        </div>
  		<div class="box-footer">
            <input type='submit' name='save' id="btnsave" class='btn btn-primary' value="Simpan Data" /> 
        </div>
    <?php echo form_close(); ?>
</div>
<script src="<?php echo base_url(); ?>themes/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
<script>
    var form = $("#form-pendidikan-add");
    
</script>
<script>
	$("#btnsave").click(function(){
		submitdata();
		return false; 
	});	
	$("#frma").submit(function(){
		submitdata();
		return false; 
	});	
	function submitdata(){
		
		var json_url = "<?php echo base_url() ?>api/manage_api/save";
		 $.ajax({    
		 	type: "POST",
			url: json_url,
			data: $("#frm").serialize(),
            dataType: "json",
			success: function(data){ 
                if(data.success){
                    swal("Pemberitahuan!", data.msg, "success");
                    $("#modal-custom-global").trigger("sukses-tambah-manage-api");
					$("#modal-custom-global").modal("hide");
                }
                else {
                    $("#form-pendidikan-add .messages").empty().append(data.msg);
                }
			}});
		return false; 
	}
</script>