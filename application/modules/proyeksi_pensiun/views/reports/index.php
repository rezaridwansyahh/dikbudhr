<?php
$areaUrl = SITE_AREA . '/masters/golongan';
$num_columns	= 2;
$can_delete	= $this->auth->has_permission('Golongan.Masters.Delete');
$can_edit		= $this->auth->has_permission('Golongan.Masters.Edit');
$has_records	= isset($records) && is_array($records) && count($records);

if ($can_delete) {
    $num_columns++;
}
?>
<div class="callout callout-info">
   <h4>Informasi!</h4>
   <p>Proyeksi pegawai yang akan pensiun 10 tahun kedepan</p>
 </div>
<div class="admin-box box box-primary">
	 
	<div class="box-body">	
		<?php echo form_open($this->uri->uri_string()); ?>
			<table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-datatable table-hover">
				<thead>
					<tr>
						<tr><th style="width:10px">No</th>
						<th>Tahun</th>
						<th>Jumlah Pegawai Pensiun (Orang)</th>
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
	
	dom : "<'row'<'col-sm-6'><'col-sm-6'>>" +
	"<'row'<'col-sm-12'tr>>" +
	"<'row'<'col-sm-2'l><'col-sm-3'i><'col-sm-7'p>>",
	processing: true,
	"pageLength" : 50,
	serverSide: true,
	"columnDefs": [
					{"className": "dt-center", "targets": [0]},
					{ "targets": [0,1,2], "orderable": false }
				],
	ajax: {
	  url: "<?php echo base_url() ?>admin/reports/proyeksi_pensiun/ajax_datapertahun",
	  type:'POST',
	  "data": function ( d ) {
			
		}
	}
});

 

</script>
