<div class="tab-pane" id="<?php echo $TAB_ID;?>">
<?php
$checkSegment = $this->uri->segment(4);
?>
<div class="admin-box box box-warning">
	
	<div class="box-body">
		<table class="table table-bordered table-striped table-responsive dt-responsive table-hover table-antriankoreksi">
			<thead>
			<tr>
				<th style="width:10px">No</th>
				<th>Kategori</th>
				<th width="100px">Jumlah</th>
			</thead>
		</table>
	</div>
</div>

<script type="text/javascript">
  
$tableantriankoreksi = $(".table-antriankoreksi").DataTable({
	
	dom : "<'row'<'col-sm-6'><'col-sm-6'>>" +
	"<'row'<'col-sm-12'tr>>" +
	"<'row'<'col-sm-2'l><'col-sm-3'i><'col-sm-7'p>>",
	processing: true,
	serverSide: true,
	stateSave: true,
	"columnDefs": [
					{"className": "text-center", "targets": [0,2]},
					{ "targets": [0,1], "orderable": false }
				],
	ajax: {
	  url: "<?php echo base_url() ?>admin/sk/sk_ds/getdata_antrian_ttd",
	  type:'POST',
	  "data": function ( d ) {
			// d.search['advanced_search_filters']=  $("#form_search_pegawai_sudah").serializeArray();
		}
	}
});
 
 
</script>
</div>