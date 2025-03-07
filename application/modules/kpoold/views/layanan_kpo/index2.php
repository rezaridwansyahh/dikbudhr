<?php 
	$canUploadBKN	= $this->auth->has_permission('LayananKpo.Masters.UploadBKN');
	$canTambahUsulan	= $this->auth->has_permission('LayananKpo.Masters.TambahUsulan');
	$canEdit	= $this->auth->has_permission('LayananKpo.Masters.Edit');
	$canCetak	= $this->auth->has_permission('LayananKpo.Cetak');

    $areaUrl = base_url() . '/kpo/layanan_kpo';
?>  
<div id="manage_unitkerja_container" class="admin-box box box-primary">
	<div class="box-header">
        <div class="btn-group">
		<?php if ($canUploadBKN) : ?>
		<a style="margin-right:10px;" class="show-modal-custom btn btn-small btn-warning" href="<?php echo $areaUrl; ?>/upload_data_bkn_form" tooltip="Upload Data KPO BKN">Upload Data KPO BKN</a> 	
		<?php endif; ?>
        </div>
    </div>
	<div class="box-body">
            <table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-datatable table-hover">
				<thead>
					<tr>
						<tr><th style="width:10px">No</th>
						<th>Status</th>
						<th>NIP</th>
						<th>NAMA</th>
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
	  url: "<?php echo base_url() ?>kpo/layanan_kpo/ajax_list",
	  type:'POST',
	}
});
$('body').on('click','.show-modal-custom',function(event){
	showModalX.call(this,'sukses-upload-kpo-bkn',function(){
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
					url: "<?php echo base_url('kpo/layanan_kpo/delete');?>"+"/"+kode,
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
