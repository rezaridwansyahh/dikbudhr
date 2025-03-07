<?php 
	$canUploadBKN	= $this->auth->has_permission('LayananKpo.UploadDataBKN');
	$canFinalisasiUsulanKpo	= $this->auth->has_permission('LayananKpo.FinalisasiUsulan');
	
	$canCetak	= $this->auth->has_permission('LayananKpo.Cetak');
?>  
<div id="manage_unitkerja_container" class="admin-box box box-primary">
	<div class="box-header">
		<div style='float:left'>
			Pengelola KPO Instansi
		</div>
        <div class="btn-group"  style='float:right'>
			<?php if ($canUploadBKN) : ?>
				<a title='Upload Data Perkiraan KPO BKN' style="margin-right:10px;" class="btn-upload-data-bkn btn btn-small btn-warning" href="<?php echo $module_url; ?>/upload_data_bkn_form" tooltip="Upload Data KPO BKN"><i class="fa fa-upload"></i> Upload Data KPO BKN</a> 	
			<?php endif; ?>
			<?php if ($canFinalisasiUsulanKpo) : ?>
				<a title='Finalisasi Data Usulan KPO' style="margin-right:10px;" class="btn-finalisasi-usulan btn btn-small btn-warning" href="<?php echo $module_url; ?>/form_finalisasi_usulan"><i class="fa fa-arrow-right"></i> Finalisasi Usulan KPO</a> 	
			<?php endif; ?>
			<?php if ($canCetak) : ?>
				<a title='Cetak Daftar dalam bentuk Xls' style="margin-right:10px;" class="btn-cetak-xls btn btn-small btn-warning" href="<?php echo $module_url; ?>/cetak_xls"><i class="fa fa-print"></i> Cetak</a> 	
			<?php endif; ?>
        </div>
    </div>
	<div class="box-body">
            <table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-datatable table-hover">
				<thead>
					<tr>
						<tr><th style="width:10px">No</th>
						<th>Periode Layanan</th>
						<th>Status</th>
						<th>Pegawai</th>
						<th>Unit Kerja</th>
						<th>Keterangan</th>
						<th width="70px">#</th>
					</tr>
				</thead>
			</table>
    </div>
</div>    
<script type="text/javascript">
var grid_daftar = $(".table-datatable").DataTable({
	ordering: false,
	processing: true,
	serverSide: true,
	ajax: {
	  url: "<?php echo $module_url ?>/ajax_list",
	  type:'POST',
	}
});
$('body').on('click','.btn-upload-data-bkn',function(event){
	showModalX.call(this,'sukses-upload-kpo-bkn',function(){
		grid_daftar.ajax.reload();
	},this);
	event.preventDefault();
});
$('body').on('click','.btn-cetak-xls',function(event){
	$.fileDownload(BASE_URL+'kpo/kpo-instansi/cetak_xls', {
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
