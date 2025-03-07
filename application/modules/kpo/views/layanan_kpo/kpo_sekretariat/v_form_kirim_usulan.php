<?php 
    $id = isset($detail_riwayat->id) ? $detail_riwayat->id : '';
?>
<div class='box box-warning' id="form-diklat-struktural-add">
    <?php echo form_open($this->uri->uri_string(), 'id="frm-usulan-kpo"'); ?> 
	<div class="box-body">
        <div class="callout callout-info">
            <h4>Keterangan</h4>
            <p>Jika Tidak Ada perubahan Verifikasi, Maka Data yang akan di INBOX akan Otomatis menjadi MS ketika Kirim ke Inbox Instansi</p>
        </div>
        <fieldset>
            <div class="control-group<?php echo form_error('name') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("No Surat Pengantar", 'name', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <?php echo form_input(array('name' => 'no_surat_pengantar_es1','class'=>'form-control', 'id' => 'no_surat_pengantar_es1', 'rows' => '5', 'cols' => '80')); ?>
                    <span class='help-inline'><?php echo form_error('no_surat_pengantar_es1'); ?></span>
                </div>
            </div>
        </div>
  		<div class="box-footer">
            <fieldset class='form-actions'>
                <button type='submit' name='save' id="btnsave" class='btn btn-primary'> 
                    <i class='fa fa-save''></i> 
                    Kirim ke Inbox Instansi
                </button>
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
            $.post("<?php echo $module_url?>/do_kirim_usulan_pending",$(this).serialize(),function(o){
                  if(o.success){
                    swal("Pemberitahuan!", "Transaksi berhasil", "success");
                    $("#modal-custom-global").trigger("sukses-kirim-usulan");
					$("#modal-custom-global").modal("hide");
                  }else{
                    swal("Pemberitahuan!", o.msg, "error");
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