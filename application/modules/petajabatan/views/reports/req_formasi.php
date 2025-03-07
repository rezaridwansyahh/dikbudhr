
<?php
$checkSegment = $this->uri->segment(4);
$areaUrl = SITE_AREA . '/rekap/satkers';
$num_columns	= 44;
$can_view		= $this->auth->has_permission('Rekap.Reports.Satker');
?>
<div class='alert alert-block alert-info fade in'>
    <a class='close' data-dismiss='alert'>&times;</a>
    <h4 class='alert-heading'>
        Perhatian
    </h4>
    <p>Silahkan klik pada kolom jumlah untuk melihat detil dari pengajuan</p>
</div>
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
			<table class="filter_pegawai" sborder=0 width='100%' cellpadding="10">
				<tr>
					<td width="200px"><label for="example-text-input" class="col-form-label">Satuan Kerja</label></td>
					<td colspan=2><select id="unit_id_key" name="unit_id_key" width="100%" class=" col-md-10 format-control"></select></td>
				</tr>
				<tr>
					<td colspan=4>
						&nbsp;<a href="<?php echo base_url('admin/reports/petajabatan/download_all/?satker_id=');?><?php echo $satker_id; ?>" class='btn btn-info pull-right' target="_blank"><i class="fa fa-download" aria-hidden="true"></i> Download </a>
						&nbsp;<a href="<?php echo base_url(); ?>admin/reports/bezzeting" class='btn btn-warning pull-right'><i class="fa fa-user" aria-hidden="true"></i> Usul Formasi</a>
						<button type="submit" class="btn btn-success pull-right "><i class="fa fa-search"></i> Cari</button>

					</td>
				</tr>
			</table>
		<?php
		echo form_close();    
		?>
	</div>
</div>
<div class="admin-box box box-primary">
	<div class="box-header box-tools">
		<div class="box-tools pull-right">
		<!--	 &nbsp;<button class='btn btn-warning download_xls' target="_blank"><i class="fa fa-download" aria-hidden="true"></i> Download .xls</button> -->
 		</div>
	</div>
	<div class="box-body">
		<table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-data table-hover">
			<thead>
			<tr>
				<th style="width:10px">No</th>
				<th>Satuan Kerja</th>
				<th>Jumlah</th>
			</thead>
		</table>
	</div>
</div>

<script type="text/javascript">
$(".download_xls").click(function(){
	var xyz = $("#form_search_pegawai").serialize();
	window.open("<?php echo base_url('admin/reports/petajabatan/download_reqiest');?>?"+xyz);
}); 

$("#unit_id_key").select2({
	placeholder: 'Cari Unit Kerja...',
	width: '100%',
	minimumInputLength: 4,
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
	"columnDefs": [
					{"className": "text-center", "targets": [2]},
					{ "targets": [0,2], "orderable": false }
				],
	ajax: {
	  url: "<?php echo base_url() ?>admin/reports/petajabatan/getrekap_request",
	  type:'POST',
	  "data": function ( d ) {
			d.search['advanced_search_filters']=  $("#form_search_pegawai").serializeArray();
		}
	}
});
$("#form_search_pegawai").submit(function(){
	$table.ajax.reload(null,true);
	return false;
});
 
</script>