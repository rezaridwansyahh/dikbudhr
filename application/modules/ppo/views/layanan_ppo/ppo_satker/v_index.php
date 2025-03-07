<div class="row">
	<div class="col-md-12">
		<!-- Custom Tabs -->
		<div class="nav-tabs-custom">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#tab_perkiraan_pensiun" data-toggle="tab">Perkiraan Pensiun</a></li>
				<li><a href="#tab_usulan_pensiun" data-toggle="tab">Usulan Pensiun</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane" id="tab_usulan_pensiun">
					<div id="manage_unitkerja_container" class="admin-box  ">
						<div class="box-header">
							<div class="btn-group"  style='float:right'>
								<?php if ($canTambahUsulan) : ?>
									<a style="margin-right:10px;" class="btn-tambah-usulan btn btn-small btn-warning" href="<?php echo $module_url; ?>/create"><i class='fa fa-plus''></i> Tambah Usulan</a> 	
								<?php endif; ?>
								<?php if ($canKirimInboxSekretariat || true) : ?>
									<a style="margin-right:10px;" class="btn-kirim-usulan btn btn-small btn-warning" href="<?php echo $module_url; ?>/form_kirim_usulan"><i class="fa fa-arrow-right"></i> Kirim ke Biro Kepegawaian</a> 	
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
												<td  width=200>Status Usulan</td>	
												<td>
													<select id="filter_status" class="select2 form-control lazy-select2" multiple name="filter_status[]" data-minimum=0 data-placeholder='Filter Status' data-url='<?php echo base_url('kpo/kpo-instansi/list_status');?>'>
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
								   <p>Jika Tidak Ada perubahan Verifikasi, Maka Data yang akan di INBOX akan Otomatis menjadi MS ketika Kirim ke Inbox Sekretariat</p>
								 </div>
								<table id="table-usulan-pensiun" class="slug-table table table-bordered table-striped table-responsive dt-responsive table-datatable table-hover">
									<thead>
										<tr>
											<tr><th style="width:10px">No</th>
											<th>Status</th>
											<th>Pegawai</th>
											<th>BUP</th>
											<th>Unit Kerja</th>
											<th width="70px">#</th>
										</tr>
									</thead>
								</table>
						</div>
					</div>  
				
				</div>	
				<div class="tab-pane active" id="tab_perkiraan_pensiun">
					<div  class="admin-box ">
						<div class="box-header">
						</div>
						<div class="box-body">
							<form id="form-filter">
									<table width="100%">
										<tbody>
											<tr>
												<td  width=200>Tahun Pensiun</td>	
												<td>
													<select name="filter-tahun-pensiun" class="form-control">
														<?php 
															$curr_year = date('Y');
															for($y = $curr_year+1;$y>=$curr_year-5;$y--){
																echo "<option value='$y'>$y</option>";
															}
														?>
													</select>
												</td>	
											</tr>
											<tr>
												<td colspan=2><a class='btn btn-warning btn-cari-perkiraan-pensiun'>Cari</a></td>								
											</tr>
										</tbody>
									</table>
								</form>
							<table id="table-perkiraan-pensiun" class="slug-table table table-bordered table-striped table-responsive dt-responsive table-datatable table-hover">
									<thead>
										<tr>
											<tr><th style="width:10px">No</th>
											<th>Pegawai</th>
											<th>Umur</th>
											<th>JABATAN</th>
											<th>BUP</th>
											<th>TAHUN PENSIUN</th>
											<th>Unit Kerja</th>
											<th width="70px">#</th>
										</tr>
									</thead>
								</table>
						</div>
					</div>	
				</div>
			</div>	
		</div>		
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
	$("[name=filter-tahun-pensiun]").change(function(){
		grid_perkiraan_pensiun.ajax.reload();
	});
	var grid_perkiraan_pensiun = $("#table-perkiraan-pensiun").DataTable({
		dom : "<'row'<'col-sm-6'><'col-sm-6'>>" +
		"<'row'<'col-sm-12'tr>>" +
		"<'row'<'col-sm-2'l><'col-sm-3'i><'col-sm-7'p>>",
		ordering: false,
		processing: true,
		serverSide: true,
		ajax: {
		  url: "<?php echo $module_url; ?>/ajax_list_perkiraan_pensiun",
		  type:'POST',
		  data: function ( d ) {
				d.filter_tahun  = $("[name=filter-tahun-pensiun]").val();
		  }
		}
	});
	grid_perkiraan_pensiun.on('click','.btn-usulkan',function(e){
		e.preventDefault();
		$.post($(this).attr("href"),{},function(o){
			if(o.success){
				swal("Informasi", o.message, "success");
				grid_perkiraan_pensiun.ajax.reload();
				grid_daftar.ajax.reload();
			}
			else {
				swal("Informasi", o.message, "error");
			}
		},'json');
	});
	var grid_daftar = $("#table-usulan-pensiun").DataTable({
		dom : "<'row'<'col-sm-6'><'col-sm-6'>>" +
		"<'row'<'col-sm-12'tr>>" +
		"<'row'<'col-sm-2'l><'col-sm-3'i><'col-sm-7'p>>",
		ordering: false,
		processing: true,
		serverSide: true,
		ajax: {
		  url: "<?php echo $module_url; ?>/ajax_list",
		  type:'POST',
		  data: function ( d ) {
				d.filter_unit_kerja  = $("#filter_unit_kerja").val();
				d.filter_status  = $("#filter_status").val();
		  }
		}
	});
	$('body').on('click','.btn-tambah-usulan',function(event){
		showModalX.call(this,'sukses-update-usulan',function(){
			grid_daftar.ajax.reload();
		},this);
		event.preventDefault();
	});
	$('body').on('click','.btn-kirim-usulan',function(event){
		showModalX.call(this,'sukses-kirim-usulan',function(){
			grid_daftar.ajax.reload();
		},this);
		event.preventDefault();
	});
	$('body').on('click','.btn-cari',function(event){
		grid_daftar.ajax.reload();
	});
	$('body').on('click','.btn-cari-perkiraan-pensiun',function(event){
		grid_perkiraan_pensiun.ajax.reload();
	});
	$('body').on('click','.show-modal-custom',function(event){
		showModalX.call(this,'sukses-update-usulan',function(){
			grid_daftar.ajax.reload();
		},this);
		event.preventDefault();
	});
	$('body').on('click','.btn-hapus',function () { 
		var kode =$(this).attr("kode");
		swal({
			title: "Anda Yakin?",
			text: "Hapus data Pendidikan!",
			type: "warning",
			showCancelButton: true,
			confirmButtonClass: 'btn-danger',
			confirmButtonText: 'Ya, Hapus!',
			cancelButtonText: "Tidak, Batalkan!",
			closeOnConfirm: false,
			closeOnCancel: false
		},
		function (isConfirm) {
			if (isConfirm) {
				$.ajax({
						url: "<?php echo $module_url;?>"+"/delete"+kode,
						type:"POST",
						dataType: "html",
						timeout:180000,
						success: function (result) {
							 swal("Data berhasil di hapus!", result, "success");
							 grid_daftar.ajax.reload();
					},
					error : function(error) {
						alert(error);
					} 
				});        
				
			} else {
				swal("Batal", "", "error");
			}
		});
	});

});

</script>
