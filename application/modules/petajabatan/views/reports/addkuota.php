
<div class='messages'>
</div>
<?php
$id = isset($kuota_jabatan->ID) ? $kuota_jabatan->ID : '';

?>
<div class='admin-box box box-warning'>
    <?php echo form_open($this->uri->uri_string(), 'id="frm"'); ?>
		 <div class="box-body">
            <div class="control-group<?php echo form_error('ID_JABATAN') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Jabatan", 'ID_JABATAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                	<input id='KODE_UNIT_KERJA' type='hidden' class="form-control" name='KODE_UNIT_KERJA' maxlength='255' value="<?php echo isset($kode_satker) ? $kode_satker : ''; ?>" />
                	<input id='ID' type='hidden' class="form-control" name='ID' maxlength='10' value="<?php echo isset($id) ? $id : ''; ?>" />
                	<select name="ID_JABATAN" id="ID_JABATAN" class="form-control select2">
						<option value="">-- Silahkan Pilih --</option>
						<?php if (isset($jabatans) && is_array($jabatans) && count($jabatans)):?>
						<?php foreach($jabatans as $record):?>
							<option value="<?php echo $record->KODE_JABATAN?>" <?php if(isset($kuota_jabatan->ID_JABATAN))  echo  ($kuota_jabatan->ID_JABATAN==$record->KODE_JABATAN) ? "selected" : ""; ?>><?php echo $record->NAMA_JABATAN; ?></option>
							<?php endforeach;?>
						<?php endif;?>
					</select>
                    <span class='help-inline'><?php echo form_error('ID_JABATAN'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('JUMLAH_PEMANGKU_JABATAN') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Jumlah Kuota", 'JUMLAH_PEMANGKU_JABATAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='JUMLAH_PEMANGKU_JABATAN' type='text' class="form-control" name='JUMLAH_PEMANGKU_JABATAN' maxlength='255' value="<?php echo set_value('JUMLAH_PEMANGKU_JABATAN', isset($kuota_jabatan->JUMLAH_PEMANGKU_JABATAN) ? $kuota_jabatan->JUMLAH_PEMANGKU_JABATAN : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('JUMLAH_PEMANGKU_JABATAN'); ?></span>
                </div>
            </div>
        </div>
        <div class="box-footer">
            <input type='submit' name='save' id="btnsave" class='btn btn-primary' value="Simpan" />
        </div>
    <?php echo form_close(); ?>
</div>
<script>
    var form = $("#form-diklat-fungsional-add");
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
	 
	function submitdata(){
		
		var json_url = "<?php echo base_url() ?>admin/reports/petajabatan/savekuota";
		//alert(json_url);
		 $.ajax({    
		 	type: "POST",
			url: json_url,
			data: $("#frm").serialize(),
            dataType: "json",
			success: function(data){ 
				//alert(data.success);
                if(data.success){
                    swal("Pemberitahuan!", data.msg, "success");
					$("#modal-global").modal("hide");
					showdata();
                }
                else {
                    $(".messages").empty().append(data.msg);
                }
			}});
		return false; 
	}
</script>