
<style>
	.dt-center {
		text-align:center;
	}
</style>



<style>
	#button {
		display: block;
		margin: 20px auto;
		padding: 10px 30px;
		background-color: #eee;
		border: solid #ccc 1px;
		cursor: pointer;
	}

	#overlay {
		position: fixed;
		top: 0;
		z-index: 9999 !important;
		width: 100%;
		height: 100%;
		display: none;
		background: rgba(0, 0, 0, 0.6);
	}

	.cv-spinner {
		height: 100%;
		display: flex;
		justify-content: center;
		align-items: center;
	}

	.spinner {
		width: 40px;
		height: 40px;
		border: 4px #ddd solid;
		border-top: 4px #2e93e6 solid;
		border-radius: 50%;
		animation: sp-anime 0.8s infinite linear;
	}
@keyframes sp-anime {
  100% { 
    transform: rotate(360deg); 
  }
}
.is-hide{
  display:none;
}
</style>

<?php
$year = date('Y');
?>



<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div id="overlay">
		<div class="cv-spinner">
			<span class="spinner"></span>
		</div>
	</div>
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
						aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Daftar Hasil Asesmen</h4>
			</div>
			<div class="modal-body">
				
				
				<div class="form-group">
					<label>Tahun Asesmen</label>
					<select class="form-control" id="tahun_asesmen">
						<?php
							$firstYear=2018;
							while ($firstYear<=$year){
								echo "<option value='$firstYear'>$firstYear</option>";
								$firstYear++;
							}
						?>
					</select>
					
					<input type="hidden" id="satker_induk_id" name="satker_induk_id" value="<?=$pegawai->SATUAN_KERJA_KERJA_INDUK_ID?>">
					<input type="hidden" id="satker_id" name="satker_id" value="<?=$pegawai->SATUAN_KERJA_KERJA_ID?>">
					<input type="hidden" id="unor_id" name="unor_id" value="<?=$pegawai->UNOR_ID?>">
					<input type="hidden" id="unor_induk_id" name="unor_induk_id" value="<?=$pegawai->UNOR_INDUK_ID?>">
				</div>

				

				<div class="form-group">
					<button id="searchHasil" class="btn btn-primary">Cari</button>
				</div>

				<div id="tableAssesmen">
					<table class="table table-datatable">
						<thead>
							<tr>
								<td>NIP</td>
								<td>Nama</td>
								<td>Nama Unor</td>
								<td>Report Pribadi</td>
								<td>Report Pimpinan</td>
								<td>Report Feedback (Old 2019)</td>
							</tr>
						</thead>
						<tbody id="bodyTable">

						</tbody>
					</table>

				</div>

				

			</div>

		</div>
	</div>
</div>

<!--tab-pane-->
<div class="tab-pane" id="<?php echo $TAB_ID;?>">
    <div class="form-group">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-21 col-xs-12">
            <?php if ($this->auth->has_permission('Riwayatassesmen.Kepegawaian.Create')) : ?>
            <a type="button" class="show-modal-custom btn btn-default btn-warning margin pull-right " href="<?php echo base_url(); ?>pegawai/riwayatassesmen/add/<?php echo $PNS_ID ?>" tooltip="Tambah Riwayat Assesmen" title="Tambah Riwayat Assesmen">
				<i class="fa fa-plus"></i> Tambah
            </a>

			
            <?php endif; ?>
			<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#myModal">Hasil Asesmen Pegawai</button>
            <table class="table table-datatable">
            <thead>
                <tr>
                    <th width='20px' >No</th>
                    <th>Tahun</th>
					<?php if ($this->auth->has_permission('Riwayatassesmen.Kepegawaian.ViewNilai')) { ?>
                    <th>Nilai Potensi</th>
                    <th>Nilai Kinerja</th>
                    <?php } ?>
                    <?php if ($this->auth->has_permission('Riwayatassesmen.Kepegawaian.P_feedback')) { ?>
                    	<th>LAPORAN P F</th>
                	<?php } ?>
                	<?php if ($this->auth->has_permission('Riwayatassesmen.Kepegawaian.P_Lengkap')) { ?>
                    	<th>LAPORAN P L</th>
                    <?php } ?>
                    <?php if ($this->auth->has_permission('Riwayatassesmen.Kepegawaian.L_feedback')) { ?>
                    	<th>LAPORAN L F</th>
                    <?php } ?>
                    <?php if ($this->auth->has_permission('Riwayatassesmen.Kepegawaian.L_Lengkap')) { ?>
                    	<th>LAPORAN L L</th>
                    <?php } ?>
                    <th>Saran dan Pengembangan</th>
                    <th>#</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                        
                </tr>
            </tfoot>
            <tbody>
               
            </tbody>
        </table>  
        </div>
        </div>
    </div>
</div>
<!--tab-pane-->


<script type="text/javascript">

	(function($){
		var $container = $("#<?php echo $TAB_ID;?>");
		var grid_daftar = $(".table-datatable",$container).DataTable({
				ordering: false,
				processing: true,
				"bFilter": false,
				"bLengthChange": false,
				serverSide: true,
				"columnDefs": [
					//{"className": "dt-center", "targets": "_all"}
					{"className": "dt-center", "targets": [0]}
				],
				ajax: {
					url: "<?php echo base_url() ?>pegawai/riwayatassesmen/ajax_list_admin",
					type:'POST',
					data : {
						PNS_ID:'<?php echo $PNS_ID;?>'
					}
				}
		});
		$container.on('click','.show-modal-custom',function(event){
			showModalX.call(this,'sukses-tambah-riwayat-assesmen',function(){
				grid_daftar.ajax.reload();
			},this);
			event.preventDefault();
		});
		$container.on('click','.show-modal',showModalX);

		$container.on('click','.btn-hapus',function(event){
			event.preventDefault();
			var kode =$(this).attr("kode");
				swal({
					title: "Anda Yakin?",
					text: "Hapus data Riwayat Assesmen!",
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
						var post_data = "kode="+kode;
						$.ajax({
								url: "<?php echo base_url() ?>pegawai/riwayatassesmen/delete/"+kode,
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
				
	})(jQuery);
</script>


<script type="text/javascript">
	$(document).ready(function(){
		//$('.table-datatable',"#tableAssesmen").DataTable({});
		
		$("#searchHasil").click(function(){
			var satker_id = $("#satker_id").val();
			var unor_id = $("#unor_id").val();
			var unor_induk_id = $("#unor_induk_id").val();
			var tahun_asemen = $('#tahun_asesmen').find(":selected").val();;
			$("#overlay").fadeIn(300);　


			var table = {
                ajax: "<?php echo base_url() ?>pegawai/riwayatassesmen/list_by_satker?satker_id="+satker_id+"&unor_id="+unor_id+"&tahun="+tahun_asemen+"&unor_induk_id="+unor_induk_id,
                scrollX: true,
                columns: [
                    {
                        data: "PNS_NIP"
                    },
                    {
                        data: "NAMA"
                    },
                    {
                        data: "NAMA_UNOR_PEGAWAI",
                    },
                    {
                        
                        data: null,
                        render: function (data, type, row) { 
							let visibility = '';
							if(data.FILE_UPLOAD_FB_POTENSI=='' || data.FILE_UPLOAD_FB_POTENSI==null){
								visibility ="style='visibility:hidden'";
							}
							
							return "<a "+visibility+" class='btn btn-primary' href='"+data.FILE_UPLOAD_FB_POTENSI+"' target='_blank'>Report Pribadi</a>"
						},
                        orderable: false
                    },
					{
                        
                        data: null,
                        render: function (data, type, row) { 

							let visibility = '';
							if(data.FILE_UPLOAD=='' || data.FILE_UPLOAD==null){
								visibility ="style='visibility:hidden'";
							}

							return "<a "+visibility+" class='btn btn-primary' href='"+data.FILE_UPLOAD+"' target='_blank'>Report Pimpinan</a>"
						},
                        orderable: false
                    },
					{
                        
                        data: null,
                        render: function (data, type, row) { 
							let visibility = '';
							if(data.FILE_UPLOAD_FB_PT==null || data.FILE_UPLOAD_FB_PT==''){
								visibility ="style='visibility:hidden'";
							}

							return "<a "+visibility+" class='btn btn-primary' href='"+data.FILE_UPLOAD_FB_PT+"' target='_blank'>Report Feedback Lengkap (Old)</a>"
						},
                        orderable: false
                    }
					
                ],
				initComplete: function () {
					$("#overlay").fadeOut(300);　
				}
            };
			
			//$('.table-datatable',"#tableAssesmen").dataTable().fnClearTable();
            //$('.table-datatable',"#tableAssesmen").dataTable().fnDestroy();
			$('.table-datatable',"#tableAssesmen").DataTable().clear().destroy();
			$('.table-datatable',"#tableAssesmen").DataTable(table);
			//$('#tableAssesmen').DataTable(table);
			


			
			
		});

		

	});
</script>
