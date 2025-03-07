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
            
            <div class="control-group<?php echo form_error('app_name') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Nama Aplikasi", 'Nama Aplikasi', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='app_name' type='text' class="form-control" name='app_name'  value="<?php echo set_value('name', isset($data->app_name) ? $data->app_name : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('app_name'); ?></span>
                </div>
            </div>
           <div class="control-group<?php echo form_error('key') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("API KEY", 'API KEY', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='key' type='text' readonly class="form-control" name='key'  value="<?php echo set_value('key', isset($data->key) ? $data->key : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('key'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('Controllers_ID') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Unor", 'Controllers_ID', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select name="Controllers_ID[]" multiple="multiple" id="Controllers_ID" class="form-control select2">
                        <?php 
                            if($data->selectedControllers && sizeof($data->selectedControllers)>0){
                                foreach($data->selectedControllers as $row){
                                    echo "<option selected value='".$row->id."'>".$row->name." | ".$row->url."</option>";
                                }
                            }
                        ?>
					</select>
                    <span class='help-inline'><?php echo form_error('Controllers_ID'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('Satker_Auth') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Satker Auth", 'Satker_Auth', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select name="Satker_Auth[]" multiple="multiple" id="Satker_Auth" class="form-control select2">
                        <?php 
                            if($data->selectedSatkers && sizeof($data->selectedSatkers)>0){
                                foreach($data->selectedSatkers as $row){
                                    echo "<option selected value='".$row->ID."'>".$row->NAMA_UNOR."</option>";
                                }
                            }
                        ?>
					</select>
                    <span class='help-inline'><?php echo form_error('Controllers_ID'); ?></span>
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
    $("#Controllers_ID").select2({
        placeholder: 'Cari Hak Akses...',
        width: '100%',
        minimumInputLength: 0,
        multiple:true,
        allowClear: true,
        ajax: {
            url: '<?php echo site_url("api/manage_application/get_controller_list");?>',
            dataType: 'json',
            data: function(params) {
                return {
                    term: params.term || '',
                    page: params.page || 1
                }
            },
            cache: true
        }
    });
     $("#Satker_Auth").select2({
        placeholder: 'Cari Hak Akses Unor...',
        width: '100%',
        minimumInputLength: 0,
        multiple:true,
        allowClear: true,
        ajax: {
            url: '<?php echo site_url("api/manage_application/get_satker_list");?>',
            dataType: 'json',
            data: function(params) {
                return {
                    term: params.term || '',
                    page: params.page || 1
                }
            },
            cache: true
        }
    });
	$("#btnsave").click(function(){
		submitdata();
		return false; 
	});	
	$("#frma").submit(function(){
		submitdata();
		return false; 
	});	
	function submitdata(){
		
		var json_url = "<?php echo base_url() ?>api/manage_application/save";
		 $.ajax({    
		 	type: "POST",
			url: json_url,
			data: $("#frm").serialize(),
            dataType: "json",
			success: function(data){ 
                if(data.success){
                    swal("Pemberitahuan!", data.msg, "success");
                    $("#modal-custom-global").trigger("sukses-tambah-manage-application");
					$("#modal-custom-global").modal("hide");
                }
                else {
                    $("#form-pendidikan-add .messages").empty().append(data.msg);
                }
			}});
		return false; 
	}
</script>