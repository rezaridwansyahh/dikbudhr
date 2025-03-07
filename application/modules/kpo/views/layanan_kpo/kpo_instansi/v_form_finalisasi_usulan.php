<?php 
    $id = isset($detail_riwayat->id) ? $detail_riwayat->id : '';
?>
<div class='box box-warning' id="form-diklat-struktural-add">
    <?php echo form_open($this->uri->uri_string(), 'id="frm-usulan-kpo"'); ?> 
	<div class="box-body">
            <div class="callout callout-info">
			   <h4>Keterangan</h4>
			   <p>Jika Tidak Ada perubahan Verifikasi, Maka Data dengan status "Dikirim ke pusat" dan "MS  - Pusat" yang ada di INBOX akan Otomatis menjadi MS ketika Finalisasi Data</p>
			 </div>
  		<div class="box-footer">
            <fieldset class='form-actions'>
                <input type='submit' name='save' id="btnsave" class='btn btn-primary' value="Finalisasi Data Usulan KPO" /> 
                or
                <a class='btn btn-warning' onclick="hideCustomModal();">Cancel</a> 
            </fieldset>    
        </div>
    <?php echo form_close(); ?>
</div>
<script src="<?php echo base_url(); ?>themes/admin/plugins/datepicker/bootstrap-datepicker.js"></script>


<script>
    function hideCustomModal(){
        $("#modal-custom-global").modal("hide");
    }
	$(document).ready(function(){
        $("#frm-usulan-kpo").submit(function(){
            $.post("<?php echo $module_url?>/do_finalisasi_usulan",$(this).serialize(),function(o){
                  if(o.success){
                    swal("Pemberitahuan!", "Transaksi berhasil", "success");
                    $("#modal-custom-global").trigger("sukses-finalisasi-usulan");
					$("#modal-custom-global").modal("hide");
                  }
				  else {
					swal("Pemberitahuan!", o.message, "error");  
				  }
            },'json');
            return false;
        });
		$("#pegawai_id").select2({
			placeholder: 'Pilih pegawai',
			width: '100%',
			minimumInputLength: 3,
			allowClear: true,
			ajax: {
				url: '<?php echo $module_url;?>/select2_list_pegawai',
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
	});
</script>