<div class="admin-box box box-primary">
	<div class="box-header">
        <h3 class="box-title">Estimasi Pegawai Pensiun Tahun <?php echo $tahun; ?> Satker  <?php echo isset($nama_satker) ? $nama_satker : ""; ?></h3>
        <div class="box-tools pull-right">
	        	<button type="button" class='btn btn-primary download pull-right' style="margin:3px;" target="_blank"><i class="fa fa-download" aria-hidden="true"></i> Download</button>
	      </div>
    </div>
	<div class="box-body">
	<?php echo form_open($this->uri->uri_string()); ?>
		<table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-data table-hover">
				 <thead>
				 <tr><th style="width:10px">No</th>
				 <th>NIP</th>
				 <th>NAMA Pegawai</th>
				 <th width="20%">Jabatan <br>(BUP)</th>
				 <th>Tgl Lahir</th>
				 <th>Umur</th>
				 <th>Tahun Pensiun</th>
				 <th width="30%">Unit Kerja</th>
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
	  url: "<?php echo base_url() ?>admin/reports/proyeksi_pensiun/getdatapensiunsatker/<?php echo $tahun; ?>/<?php echo $satker_id; ?>",
	  type:'POST',
	}
});
$(".download").click(function(){
	var xyz = $("#form_search_pegawai").serialize();
	window.open("<?php echo base_url('admin/reports/proyeksi_pensiun/downloadsatkerdetil/');?>/<?php echo $tahun; ?>/<?php echo $satker_id; ?>?"+xyz);
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