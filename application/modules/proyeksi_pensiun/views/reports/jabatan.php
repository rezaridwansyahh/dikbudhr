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
   <p>Proyeksi pegawai yang akan pensiun 10 tahun kedepan berdasarkan jabatan</p>
 </div>
<div class="admin-box box box-primary">
	 
	<div class="box-body">	
		<?php echo form_open($this->uri->uri_string()); ?>
			<table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-datatable table-hover">
				<thead>
					<tr>
						<th width="20px">No</th>
						<th>Tahun</th>
						<th>Jabatan</th>
						<th>Jumlah</th>
					</tr>
					 
				</thead>
				<body>
				
			</body>
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
	  url: "<?php echo base_url() ?>admin/reports/proyeksi_pensiun/ajax_dataperjabatan",
	  type:'POST',
	}
});

</script>
