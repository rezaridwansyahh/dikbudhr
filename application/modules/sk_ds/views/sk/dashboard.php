<div class="tab-pane" id="<?php echo $TAB_ID;?>">
<?php
$checkSegment = $this->uri->segment(4);
?>
<div class="admin-box box box-warning">
	
	<div class="box-body">
		<?php echo form_open($this->uri->uri_string(),"id=form_search_pegawai","form"); ?>
			<style>
				table.filter_pegawai tr td {
					padding-top: 2px;
				}
			</style>
			<table class="filter_pegawai" sborder=0 width='100%' cellpadding="10">
				<tr>
					<td width="200px"><label for="example-text-input" class="col-form-label">NAMA PENANDATANGAN : </label></td>
					<td colspan=2><input class="form-control" type="text" name="nama_key" value="" ></td>
				</tr>
				<tr>
					<td colspan=4>
			            <button type="submit" class="btn btn-success pull-right "><i class="fa fa-search"></i> Cari</button>
					</td>
				</tr>
			</table>
		<?php
		echo form_close();    
		?>
	 
		<table class="table table-bordered table-striped table-responsive dt-responsive table-hover table-dashboard">
			<thead>
			<tr>
				<th style="width:10px">No</th>
				<th>Penandatangan</th>
				<th>Siap TTD</th>
				<th>Siap Koreksi</th>
				<th width="100px">Jumlah Antrian</th>
			</thead>
		</table>
	</div>
</div>

<script type="text/javascript">
  
$tableresume = $(".table-dashboard").DataTable({
	
	dom : "<'row'<'col-sm-6'><'col-sm-6'>>" +
	"<'row'<'col-sm-12'tr>>" +
	"<'row'<'col-sm-2'l><'col-sm-3'i><'col-sm-7'p>>",
	processing: true,
	serverSide: true,
	stateSave: true,
	"columnDefs": [
					{"className": "text-center", "targets": [0,2,3,4]},
					{ "targets": [0,1], "orderable": false }
				],
	ajax: {
	  url: "<?php echo base_url() ?>admin/sk/sk_ds/getdata_resume",
	  type:'POST',
	  "data": function ( d ) {
			d.search['advanced_search_filters']=  $("#form_search_pegawai").serializeArray();
		}
	}
});
 
 $("#form_search_pegawai").submit(function(){
	$tableresume.ajax.reload(null,true);
	return false;
});
</script>
</div>