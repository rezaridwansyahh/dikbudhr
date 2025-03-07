
<div class='messages'>
</div>
<?php
$can_delete	= $this->auth->has_permission('Petajabatan.Reports.Delete');
$id = isset($kuota_jabatan[0]->ID) ? $kuota_jabatan[0]->ID : '';
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
							<option value="<?php echo $record->KODE_JABATAN?>" <?php if(isset($kuota_jabatan[0]->ID_JABATAN))  echo  (trim($kuota_jabatan[0]->ID_JABATAN)==trim($record->KODE_JABATAN)) ? "selected" : ""; ?>><?php echo $record->NAMA_JABATAN; ?></option>
							<?php endforeach;?>
						<?php endif;?>
					</select>
                    <span class='help-inline'><?php echo form_error('ID_JABATAN'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('JUMLAH_PEMANGKU_JABATAN') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Jumlah Kuota", 'JUMLAH_PEMANGKU_JABATAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='JUMLAH_PEMANGKU_JABATAN' type='text' class="form-control" name='JUMLAH_PEMANGKU_JABATAN' maxlength='255' value="<?php echo set_value('JUMLAH_PEMANGKU_JABATAN', isset($kuota_jabatan[0]->JUMLAH_PEMANGKU_JABATAN) ? $kuota_jabatan[0]->JUMLAH_PEMANGKU_JABATAN : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('JUMLAH_PEMANGKU_JABATAN'); ?></span>
                </div>
            </div>
        </div>
        <div class="box-footer">
            <input type='submit' name='save' id="btnsave" class='btn btn-primary' value="Simpan" />
            <?php if($id!="" and $can_delete){ ?>
            <?php echo lang('bf_or'); ?>
            <a href="#" id="btn-hapus" kode="<?php echo $id; ?>" class='btn btn-danger'>Hapus</a>
            <?php } ?>
        </div>
    <?php echo form_close(); ?>
</div>
 
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
                    showdata();
					$("#modal-global").modal("hide");
                }
                else {
                    $(".messages").empty().append(data.msg);
                }
			}});
		return false; 
	}
$('body').on('click','#btn-hapus',function () { 
	var kode =$(this).attr("kode");
	swal({
		title: "Anda Yakin?",
		text: "Hapus data kuota!",
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
					url: "<?php echo base_url() ?>admin/reports/petajabatan/deletekuota",
					type:"POST",
					data: post_data,
					dataType: "html",
					timeout:180000,
					success: function (result) {
						 swal("Data berhasil di hapus!", result, "success");
						 showdata();
						 $("#modal-global").modal("hide");
						
						 
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