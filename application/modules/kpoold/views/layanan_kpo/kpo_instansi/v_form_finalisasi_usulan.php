<?php 
    $id = isset($detail_riwayat->id) ? $detail_riwayat->id : '';
?>
<div class='box box-warning' id="form-diklat-struktural-add">
    <?php echo form_open($this->uri->uri_string(), 'id="frm-usulan-kpo"'); ?> 
	<div class="box-body">
        <div class="messages">
            Pastikan data telah di verifikasi dengan benar !
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