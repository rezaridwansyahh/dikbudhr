<style>
	.dataTables_processing{position:absolute;top:1%!important;left:50%;width:100%;height:40px;margin-left:-50%;margin-top:-25px;padding-top:20px;text-align:center;}
</style>

<div class="admin-box box box-primary">
	<div class="box-body">
		<table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-data-detil-mutasi table-hover">
			<thead>
			<tr>
				<th style="width:10px">No</th>
				<th>Pegawai</th>
				<th>Unit</th>
				<th>Nama Jabatan</th>
			</thead>
		</table>
	</div>
</div>
<script type="text/javascript">
$tabledetilmutasi = $(".table-data-detil-mutasi").DataTable({
	
	dom : "<'row'<'col-sm-6'><'col-sm-6'>>" +
	"<'row'<'col-sm-12'tr>>" +
	"<'row'<'col-sm-2'l><'col-sm-3'i><'col-sm-7'p>>",
	processing: true,
	serverSide: true,
	pageLength: 100,
	"columnDefs": [
					{"className": "text-center", "targets": [0]},
					{ "targets": [0,2], "orderable": false }
				],
	ajax: {
	  url: "<?php echo base_url() ?>pegawai/sinkronisasi/getdetilmutasi",
	  type:'POST',
	  "data": function ( d ) {
			d.search['satker'] =  "<?php echo  $satker; ?>";
		}
	}
});
</script>