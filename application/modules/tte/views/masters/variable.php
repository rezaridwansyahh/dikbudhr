<?php
$areaUrl = SITE_AREA . '/masters/tte';
$num_columns	= 2;
$can_delete	= $this->auth->has_permission('Tte.Masters.Delete');
$can_edit		= $this->auth->has_permission('Tte.Masters.Edit');
$has_records	= isset($records) && is_array($records) && count($records);

if ($can_delete) {
    $num_columns++;
}
?>
<div class="admin-box box box-primary">
	<div class="box-header">
		<h3>
			Master Variable
			
			 <?php if ($this->auth->has_permission('Tte.Masters.Create')) : ?>
              <a href="<?php echo site_url($areaUrl . '/createvariable'); ?>">
              	<button type="button" class="btn btn-default btn-warning margin pull-right "><i class="fa fa-plus"></i> Tambah Variable</button>
			</a>
              <?php endif; ?>
		</h3>
	</div>
	<div class="box-body">	
		<?php echo form_open($this->uri->uri_string()); ?>
			<table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-datatable table-hover">
				<thead>
					<tr>
						<tr><th style="width:10px">No</th>
						<th>Label Variable</th>
						<th>Nama Var</th>
						<th>Tipe</th>
						<th>Keterangan</th>
						<th width="70px">#</th>
					</tr>
				</thead>
			</table>
		<?php
   		echo form_close();
    ?>
	</div>
</div>


<script type="text/javascript">
var grid_daftar = $(".table-datatable").DataTable({
	ordering: false,
	processing: true,
	serverSide: true,
	ajax: {
	  url: "<?php echo base_url() ?>admin/masters/tte/ajax_datavariable",
	  type:'POST',
	}
});

$('body').on('click','.btn-hapus',function () { 
	var kode =$(this).attr("kode");
	swal({
		title: "Anda Yakin?",
		text: "Hapus data!",
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
					url: "<?php echo base_url() ?>admin/masters/tte/deletevariable",
					type:"POST",
					data: post_data,
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
