<div class="callout callout-info">
   <h4>Informasi!</h4>
   <p>Proyeksi pegawai yang akan pensiun 10 tahun kedepan berdasarkan satker</p>
 </div>
<div class="admin-box box box-primary">

	<div class="box-body">	
		<div class="row col-sm-12">
		 <?php echo form_open($this->uri->uri_string(),"id=form_search_pegawai","form"); ?>
				 
				<table class="filter_pegawai table" sborder=0 width='110%' cellpadding="10">
					<tr>
						<td width="200px"><label for="example-text-input" class="col-form-label">Nama Satker</label></td>
						<td colspan=2><input class="form-control" type="text" name="nama_satker" value="" ></td>
					</tr>
					<tr>
						<td colspan=4>
							<button type="button" class='btn btn-primary download pull-right' style="margin:3px;" target="_blank"><i class="fa fa-download" aria-hidden="true"></i> Download</button>
							<button type="submit" class="btn btn-success pull-right " style="margin:3px;"><i class="fa fa-search"></i> Cari</button>
						</td>
					</tr>
				</table>
			<?php
			echo form_close();    
			?>
		
		<?php echo form_open($this->uri->uri_string()); ?>
			<table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-datatable table-hover">
				<thead>
					<tr>
						<th width="20px">No</th>
						<th>Tahun</th>
						<th>Satker</th>
						<th>Jumlah</th>
					</tr>
				</thead>
				<body></body>	
			</table>
			
		<?php
   		echo form_close();
    	?>
    	</div>
	</div>
</div>

<script type="text/javascript">
$(".download").click(function(){
	var xyz = $("#form_search_pegawai").serialize();
	window.open("<?php echo base_url('admin/reports/proyeksi_pensiun/downloadsatker');?>?"+xyz);
});
$grid_daftar = $(".table-datatable").DataTable({
	dom : "<'row'<'col-sm-6'><'col-sm-6'>>" +
	"<'row'<'col-sm-12'tr>>" +
	"<'row'<'col-sm-2'l><'col-sm-3'i><'col-sm-7'p>>",
	ordering: false,
	processing: true,
	serverSide: true,
	pageLength: 100,
	"columnDefs": [
		{"className": "text-center", "targets": [3]},
		{ "targets": [0,3], "orderable": false }
	],
	ajax: {
	  url: "<?php echo base_url() ?>admin/reports/proyeksi_pensiun/ajax_datapersatker",
	  type:'POST',
	  "data": function ( d ) {
			d.search['advanced_search_filters']=  $("#form_search_pegawai").serializeArray();
		}
	}
});
$("#form_search_pegawai").submit(function(){
	$grid_daftar.ajax.reload(null,true);
	return false;
});
</script>
