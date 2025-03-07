
<style>
	.dt-center {
		text-align:center;
	}
</style>
<!--tab-pane-->
<div class="tab-pane" id="<?php echo $TAB_ID;?>">
    <div class="form-group">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-21 col-xs-12">
	            <?php if ($this->auth->has_permission('RiwayatKinerja.Kepegawaian.Sinkron')) : ?>
	            	<a class="btn btn-default btn-success margin pull-right" id="lihat_kinerja_bkn" kode="<?php echo $PNS_ID ?>" tooltip="Sinkron dengan BKN">
					<i class="fa fa-refresh"></i> Sinkron BKN
	            	</a>
	            <?php endif; ?>
	            <?php if ($this->auth->has_permission('RiwayatKinerja.Kepegawaian.Create')) : ?>
	            <a type="button" class="show-modal-custom btn btn-default btn-warning margin pull-right " href="<?php echo base_url(); ?>pegawai/riwayatkinerja/add/<?php echo $PNS_ID ?>" tooltip="Tambah Riwayat Penghargaan">
					<i class="fa fa-plus"></i> Tambah
	            </a>
	            <?php endif; ?>
	            <?php if ($this->auth->has_permission('RiwayatKinerja.Kepegawaian.UpdateMandiri')) : ?>
	            <a type="button" class="show-modal-custom btn btn-default btn-info margin pull-right " href="<?php echo base_url(); ?>pegawai/riwayatkinerja/addmandiri/<?php echo $PNS_ID ?>" tooltip="Tambah Riwayat Penghargaan">
					<i class="fa fa-plus"></i> Tambah Mandiri
	            </a>
	            <?php endif; ?>
	            <table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-datatable table-hover">
		            <thead>
		                <tr>
		                    <th width='20px' >No</th>
		                    <th>Tahun</th>
		                    <th>Hasil Kinerja</th>
		                    <th>Perilaku Kerja</th>
		                    <th>Kuadran Kinerja</th>
		                    <th width='10%' align="center">Aksi</th>
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
		$grid_daftar_kinerja = $(".table-datatable",$container).DataTable({
				ordering: false,
				processing: true,
				"bFilter": false,
				"bLengthChange": false,
				serverSide: true,
				"columnDefs": [
					{"className": "dt-center", "targets": [0,2,3,4,5]}
				],
				ajax: {
					url: "<?php echo base_url() ?>pegawai/riwayatkinerja/ajax_list",
					type:'POST',
					data : {
						PNS_ID:'<?php echo $PNS_ID;?>'
					}
				}
		});
		$container.on('click','.show-modal-custom',function(event){
			showModalX.call(this,'sukses-tambah-riwayat-kinerja',function(){
				$grid_daftar_kinerja.ajax.reload();
			},this);
			event.preventDefault();
		});
		// $container.on('click','.show-modal',showModalX);

		$container.on('click','.btn-hapus',function(event){
			event.preventDefault();
			var kode =$(this).attr("kode");
				swal({
					title: "Anda Yakin?",
					text: "Hapus data Riwayat Kinerja!",
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
								url: "<?php echo base_url() ?>pegawai/riwayatkinerja/delete/"+kode,
								dataType: "html",
								timeout:180000,
								success: function (result) {
									swal("Data berhasil di hapus!", result, "success");
									$grid_daftar_kinerja.ajax.reload();
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
		$container.on('click','.btn-upload',function(event){
			event.preventDefault();
			var kode =$(this).attr("kode");
			var nip = '<?=$pegawai->NIP_BARU?>'
				swal({
					title: "Anda Yakin?",
					text: "Upload data Riwayat Kinerja!",
					type: "warning",
					showCancelButton: true,
					confirmButtonClass: 'btn-danger',
					confirmButtonText: 'Ya, Upload!',
					cancelButtonText: "Tidak, Batalkan!",
					closeOnConfirm: false,
					closeOnCancel: false
				},
				function (isConfirm) {
					if (isConfirm) {
						$.ajax({
								url: "<?php echo base_url() ?>pegawai/bkn/uploadNilaiKinerja/"+nip+"/"+kode,
								dataType: "html",
								timeout:180000,
								success: function (result) {
									swal("Data berhasil di upload!", result, "success");
									$grid_daftar_kinerja.ajax.reload();
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
	$container.on('click','#lihat_kinerja_bkn',function(event){
		var kode =$(this).attr("kode");
		swal({
			title: "Anda Yakin?",
			text: "Lihat data riwayat Kinerja berdasarkan data BKN!",
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
						url: "<?php echo base_url() ?>pegawai/bkn/viewkinerja",
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
		                        	$("#modal-body").html(result.konten);
		  							$("#myModalLabel").html("Riwayat Kinerja BKN");
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
