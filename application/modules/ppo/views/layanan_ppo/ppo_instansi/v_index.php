<?php 
	$canUploadBKN	= $this->auth->has_permission('LayananPpo.UploadDataBKN');
	$canFinalisasiUsulanPpo	= $this->auth->has_permission('LayananPpo.FinalisasiUsulan');
	$canCetak	= $this->auth->has_permission('LayananPpo.Cetak');
?>  
<div id="manage_unitkerja_container" class="admin-box box box-primary">
	<div class="box-header">
		<div style='float:left'>
			Pengelola Ppo Instansi
		</div>
        <div class="btn-group"  style='float:right'>
			<?php if ($canUploadBKN) : ?>
				<a title='Upload Data Perkiraan Ppo BKN' style="margin-right:10px;" class="btn-upload-data-bkn btn btn-small btn-warning" href="<?php echo $module_url; ?>/upload_data_bkn_form" tooltip="Upload Data Ppo BKN"><i class="fa fa-upload"></i> Upload Data PPO BKN</a> 	
			<?php endif; ?>
			<?php if ($canFinalisasiUsulanPpo) : ?>
				<a title='Finalisasi Data Usulan Ppo' style="margin-right:10px;" class="btn-finalisasi-usulan btn btn-small btn-warning" href="<?php echo $module_url; ?>/form_finalisasi_usulan"><i class="fa fa-arrow-right"></i> Finalisasi Usulan PPO</a> 	
			<?php endif; ?>
			<?php if ($canCetak) : ?>
				<a title='Cetak Daftar dalam bentuk Xls' style="margin-right:10px;" class="btn-cetak-xls btn btn-small btn-warning" href="<?php echo $module_url; ?>/cetak_xls"><i class="fa fa-print"></i> Cetak</a> 	
			<?php endif; ?>
        </div>
    </div>
	<div class="box-body">
			<form id="form-filter">
				<table width="100%">
					<tbody>
						<tr>
							<td width=200>Unit Kerja</td>	
							<td><select id="filter_unit_kerja" class="select2 form-control lazy-select2" name="filter_unit_kerja"data-minimum=0 data-placeholder='Filter Unit Kerja' data-url='<?php echo base_url('ppo/ppo-instansi/list_satker');?>'></select></td>	
						</tr>
						<tr>
							<td>Status Usulan</td>	
							<td>
								<select id="filter_status" class="select2 form-control lazy-select2" multiple name="filter_status[]" data-minimum=0 data-placeholder='Filter Status' data-url='<?php echo base_url('ppo/ppo-instansi/list_status');?>'>
									<option value=''>--Semua--</option>
								</select>
							</td>	
						</tr>
						<tr>
							<td colspan=2><a class='btn btn-warning btn-cari'>Cari</a></td>								
						</tr>
					</tbody>
				</table>
			</form>
			<div class="callout callout-info">
			   <h4>Keterangan</h4>
			   <p>Jika Tidak Ada perubahan Verifikasi, Maka Data yang akan di INBOX akan Otomatis menjadi MS ketika Finalisasi Data</p>
			 </div>
            <table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-datatable table-hover">
				<thead>
					<tr>
						<tr><th style="width:10px">No</th>
						<th>Periode Layanan</th>
						<th>Status</th>
						<th>Pegawai</th>
						<th>Unit Kerja</th>
						<th>BUP</th>
						<th>Keterangan</th>
						<th width="70px">#</th>
					</tr>
				</thead>
			</table>
    </div>
</div>    
<style>
	#form-filter td {
		padding:2px;
	}
</style>
<script type="text/javascript">
$(document).ready(function(){
	$(".lazy-select2").each(function(i,o){
		$(this).select2(
			{
				placeholder: $(this).data('placeholder'),
				width: '100%',
				minimumInputLength: $(this).data('minimum'),
				allowClear: true,
				ajax: {
					url: $(this).data('url'),
					dataType: 'json',
					data: function(params) {
						return {
							term: params.term || '',
							page: params.page || 1
						}
					},
					cache: true
				}
			}
		);
	});
});
var grid_daftar = $(".table-datatable").DataTable({
	dom : "<'row'<'col-sm-6'><'col-sm-6'>>" +
	"<'row'<'col-sm-12'tr>>" +
	"<'row'<'col-sm-2'l><'col-sm-3'i><'col-sm-7'p>>",
	ordering: false,
	processing: true,
	serverSide: true,
	ajax: {
	  url: "<?php echo $module_url ?>/ajax_list",
	  type:'POST',
	  data: function ( d ) {
			d.filter_unit_kerja  = $("#filter_unit_kerja").val();
			d.filter_status  = $("#filter_status").val();
	  }
	}
});
$('body').on('click','.btn-cari',function(event){
	grid_daftar.ajax.reload();
});
$('body').on('click','.btn-upload-data-bkn',function(event){
	showModalX.call(this,'sukses-upload-Ppo-bkn',function(){
		grid_daftar.ajax.reload();
	},this);
	event.preventDefault();
});
$('body').on('click','.show-modal-custom',function(event){
	showModalX.call(this,'sukses-update-usulan',function(){
		grid_daftar.ajax.reload();
	},this);
	event.preventDefault();
});
$('body').on('click','.btn-cetak-xls',function(event){
	$.fileDownload($(this).attr("href"), {
		httpMethod: "POST",
        data: {
			x:1,
			y:2
		},
		successCallback: function (url) {
			
		},
		failCallback: function (responseHtml, url) {
			var responseCode = JSON.parse(responseHtml);
			swal("Informasi", responseCode.message, "error");
		}
	});
	event.preventDefault();
});
$('body').on('click','.btn-verifikasi-usulan',function(event){
	showModalX.call(this,'sukses-update-usulan',function(){
		grid_daftar.ajax.reload();
	},this);
	event.preventDefault();
});

$('body').on('click','.btn-finalisasi-usulan',function(event){
	showModalX.call(this,'sukses-finalisasi-usulan',function(){
		grid_daftar.ajax.reload();
	},this);
	event.preventDefault();
});
</script>
