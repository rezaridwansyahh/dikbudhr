
<style>
	.dt-center {
		text-align: center;
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

	.is-hide {
		display: none;
	}
</style>
<!--tab-pane-->
<div id="overlay">
  <div class="cv-spinner">
    <span class="spinner"></span>
  </div>
</div>
<div class="tab-pane active" id="<?php echo $TAB_ID;?>">
    <div class="form-group">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-21 col-xs-12">
            <?php if ($this->auth->has_permission('riwayatprestasikerja.Kepegawaian.Create')) : ?>
            <a type="button" class="show-modal-custom btn btn-default btn-warning margin pull-right " href="<?php echo base_url(); ?>pegawai/riwayatprestasikerja/add/<?php echo $PNS_ID ?>" tooltip="Tambah Riwayat Diklat">
				<i class="fa fa-plus"></i> Tambah
            </a>
			
            <?php endif; ?>

			<?php if ($this->auth->has_permission('RiwayatKinerja.Kepegawaian.Sync')) : ?>
            
			<button id="syncSKP2021" type="button" class="btn btn-primary" tooltip="Sinkronisasi SKP 2021 ke SIASN" nip="<?=$pegawai->NIP_BARU?>">
				<i class="fa fa-sync"></i> Kirim SKP 2021 ke SIASN
            </button>
            <?php endif; ?>

			
            <?php if ($this->auth->has_permission('RiwayatPrestasiKerja.Kepegawaian.Sinkron')) : ?>
	            	<a class="btn btn-default btn-success margin pull-right" id="lihat_skp_bkn" kode="<?php echo $PNS_ID ?>" tooltip="Sinkron dengan BKN">
					<i class="fa fa-refresh"></i> Sinkron BKN
	            	</a>
	            <?php endif; ?>
            <table class="table table-datatable">
            <thead>
                <tr>
                    <th width='20px' >No</th>
                    <th>Tahun</th>
                    <th>Nilai PPK</th>
                    <th width='100px'>Nilai SKP</th>
                    <th width='100px'>Nilai Perilaku</th>
					<th width='100px'>Jabatan</th>
                    <th width='100px' align="center">AKSI</th>
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
		$grid_daftar_skp = $(".table-datatable",$container).DataTable({
				ordering: false,
				processing: true,
				"bFilter": false,
				"bLengthChange": false,
				serverSide: true,
				"columnDefs": [
					//{"className": "dt-center", "targets": "_all"}
					{"className": "dt-center", "targets": [0,2,3,4,5]}
				],
				ajax: {
					url: "<?php echo base_url() ?>pegawai/riwayatprestasikerja/ajax_list",
					type:'POST',
					data : {
						PNS_ID:'<?php echo $PNS_ID;?>'
					}
				}
		});
		$container.on('click','.show-modal-custom',function(event){
			showModalX.call(this,'sukses-tambah-riwayat-pindah_unit_kerja',function(){
				$grid_daftar_skp.ajax.reload();
			},this);
			event.preventDefault();
		});
		$container.on('click','.show-modal',showModalX);

		$container.on('click','.btn-hapus',function(event){
			event.preventDefault();
			var kode =$(this).attr("kode");
				swal({
					title: "Anda Yakin?",
					text: "Hapus data Riwayat Prestasi Kerja!",
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
								url: "<?php echo base_url() ?>pegawai/riwayatprestasikerja/delete/"+kode,
								dataType: "html",
								timeout:180000,
								success: function (result) {
									swal("Data berhasil di hapus!", result, "success");
									$grid_daftar_skp.ajax.reload();
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
		$container.on('click','#lihat_skp_bkn',function(event){
		var kode =$(this).attr("kode");
		swal({
			title: "Anda Yakin?",
			text: "Lihat data riwayat SKP berdasarkan data BKN!",
			type: "warning",
			showCancelButton: true,
			confirmButtonClass: 'btn-success',
			confirmButtonText: 'Ya!',
			cancelButtonText: "Tidak, Batalkan!",
			closeOnConfirm: false,
			showLoaderOnConfirm: true,
			closeOnCancel: false
		},
		function (isConfirm) {
			if (isConfirm) {
				var post_data = "kode="+kode;
				$.ajax({
						url: "<?php echo base_url() ?>pegawai/bkn/viewskp",
						type:"POST",
						data: post_data,
						dataType: "json",
						timeout:180000,
						success: function (result) {
							if(result.success){
								swal({
		                            title: "Selamat!",
		                            text: result.msg,
		                            type: "success",
		                            timer: 4000,
		                            showConfirmButton: true
		                        }, function () {
		                        	$grid_daftar_skp.ajax.reload();
		                        	$("#modal-body").html(result.konten);
		  							$("#myModalLabel").html("Riwayat Prestasi Kerja BKN");
		                            $("#modal-global").modal('show');
		                        });
							}else{
								swal("Perhatian", result.msg, "error");
							}
							
					},
					error : function(error) {
						swal("Perhatian", "Ada masalah koneksi", "error");
					} 
				});        
				
			} else {
				swal("Batal", "", "error");
			}
		});
	});	
	})(jQuery);
</script>


<script>
	$(document).ready(function(){
		$("#syncSKP2021").click(function(){
			$("#overlay").fadeIn(300);
			let nip = $(this).attr("nip");
			$.ajax({
				url: "<?php echo base_url() ?>pegawai/bkn/sync_skp_2021",
				type:"POST",
				data: {"nip":nip},
				dataType: "json",
				timeout:180000,
				success: function (result) {
					console.log(result);
					$("#overlay").fadeOut(300);
					if(result.size<0){
						alert("SKP TIDAK DITEMUKAN");
					}else{
						for (let i = 0; i < result.length; i++) {
							alert(result[i].peraturan+" "+result[i].response.message);
						}
					}
				},
				error : function(error) {
					$("#overlay").fadeOut(300);
					alert("error, connection lost");
				} 
			});    
		});
	});
</script>
