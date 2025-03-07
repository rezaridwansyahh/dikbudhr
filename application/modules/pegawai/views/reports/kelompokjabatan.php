
<?php
$checkSegment = $this->uri->segment(4);
$areaUrl = SITE_AREA . '/kepegawaian/pegawai';
$num_columns	= 44;
$can_delete	= $this->auth->has_permission('Pegawai.Kepegawaian.Delete');
$can_edit		= $this->auth->has_permission('Pegawai.Kepegawaian.Edit');
$has_records	= isset($records) && is_array($records) && count($records);

if ($can_delete) {
    $num_columns++;
}
?>
<div class="admin-box box box-primary">
	<div class="box-header">
              <h3 class="box-title">Data Pegawai Kelompok Jabatan</h3>
              
            </div>
	<div class="box-body">
	<?php echo form_open($this->uri->uri_string()); ?>
		<table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-data table-hover">
				 <thead>
				 <tr><th style="width:10px">No</th>
				 <th>NIP</th><th>NAMA Pegawai</th><th>Kelompok Jabatan</th><th width="70px">#</th></tr>
				 </thead>
				 </table>
	<?php
    echo form_close();
    
    ?>
</div>
</div>


<script type="text/javascript">
$(".table-data").DataTable({
	ordering: false,
	processing: true,
	serverSide: true,
	ajax: {
	  url: "<?php echo base_url() ?>admin/reports/pegawai/getkelompokjabatan",
	  type:'POST',
	}
});

$('body').on('click','.btn-hapus',function () { 
	var kode =$(this).attr("kode");
	swal({
		title: "Anda Yakin?",
		text: "Delete data Pegawai!",
		type: "warning",
		showCancelButton: true,
		confirmButtonClass: 'btn-danger',
		confirmButtonText: 'Ya, Delete!',
		cancelButtonText: "Tidak, Batalkan!",
		closeOnConfirm: false,
		closeOnCancel: false
	},
	function (isConfirm) {
		if (isConfirm) {
			var post_data = "kode="+kode;
			$.ajax({
					url: "<?php echo base_url() ?>admin/kepegawaian/pegawai/deletedata",
					type:"POST",
					data: post_data,
					dataType: "html",
					timeout:180000,
					success: function (result) {
						 swal("Deleted!", result, "success");
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