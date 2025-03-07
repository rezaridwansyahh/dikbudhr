<?php 
	$canKirimVerifikatorInstansi	= $this->auth->has_permission('LayananKpo.KirimInboxInstansi');
	$canEdit	= $this->auth->has_permission('LayananKpo.View.PengelolaKPODeputi');
	$canCetak	= $this->auth->has_permission('LayananKpo.Cetak');

?>  
<div id="manage_unitkerja_container" class="admin-box box box-primary">
	<div class="box-header">
        <div class="btn-group"  style='float:right'>
		
			<?php if ($canKirimVerifikatorInstansi) : ?>
				<a style="margin-right:10px;" class="btn-kirim-usulan btn btn-small btn-warning" href="<?php echo $module_url; ?>/form_kirim_usulan"><i class="fa fa-arrow-right"></i> Kirim ke Verifikator Instansi</a> 	
			<?php endif; ?>
        </div>
    </div>
	<div class="box-body">
            <table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-datatable table-hover">
				<thead>
					<tr>
						<tr><th style="width:10px">No</th>
						<th>Status</th>
						<th>No Surat</th>
						<th>Pegawai</th>
						<th>Informasi Tambahan</th>
						<th>Unit Kerja</th>
						<th width="70px">#</th>
					</tr>
				</thead>
			</table>
    </div>
</div>    
<script type="text/javascript">
var grid_daftar = $(".table-datatable").DataTable({
	ordering: false,
	processing: true,
	serverSide: true,
	ajax: {
	  url: "<?php echo $module_url; ?>/ajax_list",
	  type:'POST',
	}
});
$('body').on('click','.btn-tambah-usulan',function(event){
	showModalX.call(this,'sukses-update-usulan',function(){
		grid_daftar.ajax.reload();
	},this);
	event.preventDefault();
});
$('body').on('click','.btn-kirim-usulan',function(event){
	showModalX.call(this,'sukses-kirim-usulan',function(){
		grid_daftar.ajax.reload();
	},this);
	event.preventDefault();
});
$('body').on('click','.show-modal-custom',function(event){
	showModalX.call(this,'sukses-update-usulan',function(){
		grid_daftar.ajax.reload();
	},this);
	event.preventDefault();
});
$('body').on('click','.btn-hapus',function () { 
	var kode =$(this).attr("kode");
	swal({
		title: "Anda Yakin?",
		text: "Hapus data Pendidikan!",
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
			$.ajax({
					url: "<?php echo $module_url;?>"+"/delete"+kode,
					type:"POST",
					dataType: "html",
					timeout:180000,
					success: function (result) {
						 swal("Data berhasil di hapus!", result, "success");
						 grid_daftar.ajax.reload();
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
