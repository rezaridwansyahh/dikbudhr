<div class="admin-box box box-primary">
	<div class="box-header">
        <h3 class="box-title"><?php echo isset($unit_name) ? $unit_name : ""; ?></h3>
    </div>
	<div class="box-body">
	<?php echo form_open($this->uri->uri_string()); ?>
		<table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-data table-hover">
			<thead>
			<tr>
				<th style="width:10px">No</th>
				<th>NIP</th>
				<th>Nama Pegawai</th>
				<th>Golongan</th>
				<th>Unit Kerja</th>
				<th width="70px" align="center">#</th></tr>
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
	pageLength: 100,
	ajax: {
	  url: "<?php echo base_url() ?>admin/reports/kondisi_pegawai/getdatadetilumur/<?php echo $umur; ?>/<?php echo $unit; ?>/<?php echo $tkpendidikan; ?>",
	  type:'POST',
	}
});
 
$('body').on('click','.sweet-5',function () { 
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