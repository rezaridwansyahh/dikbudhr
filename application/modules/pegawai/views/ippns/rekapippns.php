<style>
	.dataTables_processing{position:absolute;top:1%!important;left:50%;width:100%;height:40px;margin-left:-50%;margin-top:-25px;padding-top:20px;text-align:center;}
</style>
<div class="admin-box box box-primary expanded-box">
	<div class="box-header">
              <h3 class="box-title">Pencarian Lanjut</h3>
			   <div class="box-tools pull-right">
                	<button type="button" class="btn btn-box-tool btn-default btn-advanced-search" data-widget="collapse">
						<i class="fa fa-minus"></i> Tampilkan
					</button>
                	
              </div>
	</div>

	<div class="box-body">
		<?php echo form_open($this->uri->uri_string(),"id=form_search_pegawai","form"); ?>
			<style>
				table.filter_pegawai tr td {
					padding-top: 2px;
				}
			</style>
			<table class="filter_pegawai" sborder=0 width='100%'>
				<tr>
					<td width="10%"><label for="example-text-input" class="col-form-label">Satker</label></td>
					<td>
						<select id="unit_id_key" name="unit_id_key" width="100%" class=" col-md-10 form-control"></select>
					</td>
				</tr>
				<tr>
					<td><label for="example-text-input" class="col-form-label">Nama</label></td>
					<td>
						<input type="text" name="nama" class="form-control">
					</td>
				</tr>
				<tr>
					<td><label for="example-text-input" class="col-form-label">Tahun</label></td>
					<td>
						<input type="text" name="tahun" class="form-control">
					</td>
				</tr>
				
				<tr>
					<td colspan="2">
						<a href="javascript:;" id="btn_cari" style="margin:3px;" class="btn green btn-primary button-submit pull-right"> 
							Cari data
							 <i class="fa fa-search"></i>			                
			            </a> 
						<a href="javascript:;" style="margin:3px;" class="btn green btn-info button-submit download_xls pull-right"> 
							Download
							<i class="fa fa-download" aria-hidden="true"></i> 
			            </a>
						
					</td>
				</tr>
			</table>
		<?php
		echo form_close();    
		?>
	</div>
</div>
<div class="admin-box box box-primary">
	<div class="box-body">
		<table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-data table-hover">
			<thead>
			<tr>
				<th style="width:10px">No</th>
				<th>Pegawai</th>
				<th>Jumlah Kursus</th>
			</thead>
		</table>
	</div>
</div>

<script type="text/javascript">
$("#unit_id_key").select2({
	placeholder: 'Cari Unit Kerja...',
	width: '100%',
	minimumInputLength: 0,
	allowClear: true,
	ajax: {
		url: '<?php echo site_url("pegawai/kepegawaian/ajax_unit_list");?>',
		dataType: 'json',
		data: function(params) {
			return {
				term: params.term || '',
				page: params.page || 1
			}
		},
		cache: true
	}
}); 
$table = $(".table-data").DataTable({
	
	dom : "<'row'<'col-sm-6'><'col-sm-6'>>" +
	"<'row'<'col-sm-12'tr>>" +
	"<'row'<'col-sm-2'l><'col-sm-3'i><'col-sm-7'p>>",
	processing: true,
	serverSide: true,
	pageLength: 25,
	"columnDefs": [
					{"className": "text-center", "targets": [2]},
					{ "targets": [0,1], "orderable": false }
				],
	ajax: {
	  url: "<?php echo base_url() ?>pegawai/ippns/belum_kursus",
	  type:'POST',
	  "data": function ( d ) {
			d.search['advanced_search_filters']=  $("#form_search_pegawai").serializeArray();
		}
	}
});
$("#btn_cari").click(function(){
	$table.ajax.reload(null,true);
	return false; 
});
$("#form_search_pegawai").submit(function(){
	$table.ajax.reload(null,true);
	return false;
});
$(".download_xls").click(function(){
	var xyz = $("#form_search_pegawai").serialize();
	window.open("<?php echo base_url('pegawai/ippns/download');?>?"+xyz);
});
</script>