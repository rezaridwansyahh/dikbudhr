
<?php
$checkSegment = $this->uri->segment(4);
$areaUrl = SITE_AREA . '/rekap/satkers';
$num_columns	= 44;
$can_view		= $this->auth->has_permission('Rekap.Reports.Satker');
?>
<div class="admin-box box box-primary">
	<div class="box-header">
		<center><h4><?php echo $detil_satker->NAMA_UNOR; ?></h4></center>
	</div>
	<div class="box-body">
				&nbsp;<a href="<?php echo base_url('admin/reports/petajabatan/download/?satker_id=');?><?php echo $satker_id; ?>" class='btn btn-success pull-right' target="_blank"><i class="fa fa-download" aria-hidden="true"></i> Download </a>
		<table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-data-detil table-hover">
			<thead>
			<tr>
				<th style="width:10px">No</th>
				<th>Jabatan</th>
				<th>Kualifikasi Pendidikan</th>
				<th>Jumlah</th>
			</thead>
		</table>
	</div>
</div>

<script type="text/javascript">
 
$tabledetil = $(".table-data-detil").DataTable({
	
	dom : "<'row'<'col-sm-6'><'col-sm-6'>>" +
	"<'row'<'col-sm-12'tr>>" +
	"<'row'<'col-sm-2'l><'col-sm-3'i><'col-sm-7'p>>",
	processing: true,
	serverSide: true,
	"columnDefs": [
					{"className": "text-center", "targets": [3]},
					{ "targets": [0,3], "orderable": false }
				],
	ajax: {
	  url: "<?php echo base_url() ?>admin/reports/petajabatan/getrekap_request_detil",
	  type:'POST',
	  "data": function ( d ) {
			d.satker_id =  "<?php echo $satker_id; ?>";
		}
	}
}); 
</script>