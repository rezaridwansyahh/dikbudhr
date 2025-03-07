<div class="tab-pane active" id="<?php echo $TAB_ID;?>">
	 
	<div class="box-body">
		<?php echo form_open($this->uri->uri_string(), 'id="frm-sign"'); ?> 
		<input type="hidden" name="username" value="<?php echo isset($pegawai_login->NIK) ? trim($pegawai_login->NIK) : ""; ?>">
		<input type="hidden" name="NIP" value="<?php echo isset($pegawai_login->NIP_BARU) ? trim($pegawai_login->NIP_BARU) : ""; ?>">
		<table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-data table-hover">
			<thead>
			<tr>
				<tr><th style="width:10px">No</th>
				<th width="15%">Pemilik SK</th>
				<th width="10%">Kategori SK</th>
				<th>No SK / Tgl SK</th>
				<th>Unit Kerja</th>
			</thead>
		</table>
		</form>
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
	  url: "<?php echo base_url() ?>admin/sk/sk_ds/getdatadash/<?php echo $PNS_ID; ?>",
	  type:'POST',
	  "data": function ( d ) {
			// d.search['advanced_search_filters']=  $("#form_search_pegawai").serializeArray();
		}
	}
});
$("#form_search_pegawai").submit(function(){
	$table.ajax.reload(null,true);
	return false;
});
 
    
</script>
</div>