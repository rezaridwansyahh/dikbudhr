
<div class="tab-pane active" id="<?php echo $TAB_ID;?>">
<?php
$checkSegment = $this->uri->segment(4);
$areaUrl = SITE_AREA . '/sk/sk_ds';
$num_columns	= 44;
$can_delete	= $this->auth->has_permission('Sk_ds.Sk.Delete');
$can_edit		= $this->auth->has_permission('Sk_ds.Sk.Edit');
$has_records	= isset($records) && is_array($records) && count($records);

if ($can_delete) {
    $num_columns++;
}
?>

 
<div class="admin-box box box-primary">
	<div class="box-body">
		<?php echo form_open($this->uri->uri_string(), 'id="frm-koreksi"'); ?> 
		<table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-data table-hover">
			<thead>
				<tr>
					<th style="width:10px">#</th>
					<th width="15%">Pemilik SK</th>
					<th width="10%">Kategori SK</th>
					<th>No SK / Tgl SK</th>
					<th>Unit Kerja</th>
				</tr>
			</thead>
		</table>
		<?php
		echo form_close();    
		?>
		<div id="infokosong">adsada</div>
	</div>
</div>

<script type="text/javascript">
  
$table = $(".table-data").DataTable({
	
	dom : "<'row'<'col-sm-6'><'col-sm-6'>>" +
	"<'row'<'col-sm-12'tr>>" +
	"<'row'<'col-sm-2'l><'col-sm-3'i><'col-sm-7'p>>",
	processing: true,
	serverSide: true,
	stateSave: true,
	"columnDefs": [
					{"className": "text-center", "targets": [0]},
					{ "targets": [0,4], "orderable": false }
				],
	ajax: {
	  url: "<?php echo base_url() ?>admin/sk/sk_ds/getdatavalidasi_dash/<?php echo $PNS_ID; ?>",
	  type:'POST',
	  "data": function ( d ) {
			d.search['advanced_search_filters']=  $("#form_search_pegawai").serializeArray();
		},
		"complete": function(response) {
			 
       }
	}
});
 
</script>
</div>