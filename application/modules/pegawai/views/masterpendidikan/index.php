<?php 
    $areaUrl = base_url() . '/pegawai/masterpendidikan';
?>  

<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.min.css">
<div id="manage_unitkerja_container" class="admin-box box box-primary">
	<div class="box-header">
        <div class="btn-group">
            <a class="show-modal-custom btn btn-small btn-warning" href="<?php echo $areaUrl; ?>/create" tooltip="Tambah Pendidikan">Tambah Baru</a>
        </div>
    </div>
	<div class="box-body">
            <table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-datatable table-hover">
				<thead>
					<tr>
						<tr><th style="width:10px">No</th>
						<th>TK. PENDIDIKAN</th>
						<th>JURUSAN</th>
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
	  url: "<?php echo base_url() ?>pegawai/masterpendidikan/ajax_list",
	  type:'POST',
	}
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
					url: "<?php echo base_url('pegawai/masterpendidikan/delete');?>"+"/"+kode,
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
