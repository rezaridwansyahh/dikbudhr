<div class="tab-pane" id="<?php echo $TAB_ID;?>">
<?php
$checkSegment = $this->uri->segment(4);
$areaUrl = SITE_AREA . '/izin/izin_pegawai';
$num_columns	= 44;
$can_delete	= $this->auth->has_permission('Izin_pegawai.Izin.Delete');
$can_edit		= $this->auth->has_permission('Izin_pegawai.Izin.Edit');

if ($can_delete) {
    $num_columns++;
}
?>

<div class="admin-box box box-primary">
	<div class="box-body">
		 
			 
		<table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-matrik table-hover">
			<thead>
			<tr>
				<th style="width:5px">No</th>
				<th>Pegawai</th>
				<th>Proses</th>
				<th>Disetujui</th>
				<th>Perubahan</th>
				<th>Ditangguhkan</th>
				<th>Jumlah</th>
			</thead>
		</table>
	</div>
</div>

<script type="text/javascript">
$('.datepicker').datepicker({
  autoclose: true,format: 'yyyy-mm-dd'
});
$table_matrik = $(".table-matrik").DataTable({
	
	dom : "<'row'<'col-sm-6'><'col-sm-6'>>" +
	"<'row'<'col-sm-12'tr>>" +
	"<'row'<'col-sm-2'l><'col-sm-3'i><'col-sm-7'p>>",
	processing: true,
	serverSide: true,
	"columnDefs": [
					{"className": "text-center", "targets": [0,2,3,4,5,6]},
					{ "targets": [0,2,3,4,5,6], "orderable": false }
				],
	ajax: {
	  url: "<?php echo base_url() ?>admin/izin/izin_pegawai/getdata_izin_matrik_satker",
	  type:'POST',
	  "data": function ( d ) {
			d.search['advanced_search_filters']=  $("#form_search_pegawai").serializeArray();
		}
	}
});
$("#form_search_pegawai").submit(function(){
	$table_matrik.ajax.reload(null,true);
	return false;
});

</script>
</div>