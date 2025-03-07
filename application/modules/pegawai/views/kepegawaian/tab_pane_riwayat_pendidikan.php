
<style>
	.dt-center {
		text-align:center;
	}
</style>
<!--tab-pane-->
<div class="tab-pane active" id="<?php echo $TAB_ID;?>">
    <div class="form-group">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-21 col-xs-12">
            <?php if ($this->auth->has_permission('Pegawai.ViewDataBkn.View')) : ?>
            <a class="btn btn-default btn-success margin pull-right" id="lihat_pendidikan_bkn" kode="<?php echo $PNS_ID ?>" tooltip="Sinkron dengan BKN">
				<i class="fa fa-refresh"></i> Sinkron BKN
            </a>
            <?php endif; ?>
            <?php if ($this->auth->has_permission('RiwayatPendidikan.Kepegawaian.Create')) : ?>
            <a type="button" class="show-modal-custom btn btn-default btn-warning margin pull-right " href="<?php echo base_url(); ?>pegawai/riwayatpendidikan/add/<?php echo $PNS_ID ?>" tooltip="Tambah Riwayat Pendidikan">
				<i class="fa fa-plus"></i> Tambah
            </a>
            <?php endif; ?>
            <?php if ($this->auth->has_permission('RiwayatPendidikan.Kepegawaian.Updmandiri')) : ?>
	            <a type="button" class="show-modal-custom btn btn-default btn-warning margin pull-right " href="<?php echo base_url(); ?>pegawai/riwayatpendidikan/addmandiri/<?php echo $PNS_ID ?>" tooltip="Tambah Riwayat Pendidikan">
				<i class="fa fa-plus"></i> Tambah Mandiri
	            </a>
            <?php endif; ?>
            <table class="table table-datatable">
            <thead>
                <tr>
                    <th width="15px">No</th>
                    <th>Jenjang Pendidikan</th>
                    <th>NAMA Sekolah</th>
                    <th>Jurusan</th>
                    <th width='100px'>Tahun Lulus</th>
                    <th>Nomor Ijazah</th>
                    <th width='120px' align="center">Aksi</th>
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
		$grid_daftar_pendidikan = $(".table-datatable",$container).DataTable({
				ordering: false,
				processing: true,
				"bFilter": false,
				"bLengthChange": false,
				serverSide: true,
				"columnDefs": [
					//{"className": "dt-center", "targets": "_all"}
					{"className": "dt-center", "targets": [0,4,5,6]}
				],
				ajax: {
					url: "<?php echo base_url() ?>pegawai/riwayatpendidikan/ajax_list",
					type:'POST',
					data : {
						PNS_ID:'<?php echo $PNS_ID;?>'
					}
				}
		});
		
		$container.on('click','.show-modal-custom',function(event){
			showModalX.call(this,'sukses-tambah-riwayat-pendidikan',function(){
				$grid_daftar_pendidikan.ajax.reload();
			},this);
			event.preventDefault();
		});
		//$container.on('click','.show-modal',showModalX);

		$container.on('click','.btn-hapus',function(event){
			event.preventDefault();
			var kode =$(this).attr("kode");
				swal({
					title: "Anda Yakin?",
					text: "Hapus data Riwayat Pendidikan!",
					type: "warning",
					showCancelButton: true,
					confirmButtonClass: 'btn-danger',
					confirmButtonText: 'Ya, Hapus!',
					cancelButtonText: "Tidak, Batalkan!",
					closeOnConfirm: false,
					showLoaderOnConfirm: true,
					closeOnCancel: false
				},
				function (isConfirm) {
					if (isConfirm) {
						var post_data = "kode="+kode;
						$.ajax({
								url: "<?php echo base_url() ?>pegawai/riwayatpendidikan/delete/"+kode,
								dataType: "html",
								timeout:180000,
								success: function (result) {
									swal("Data berhasil di hapus!", result, "success");
									$grid_daftar_pendidikan.ajax.reload();
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
	$container.on('click','#lihat_pendidikan_bkn',function(event){
		var kode =$(this).attr("kode");
		swal({
			title: "Anda Yakin?",
			text: "Lihat data pendidikan berdasarkan data BKN!",
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
						url: "<?php echo base_url() ?>pegawai/bkn/viewpendidikan",
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
		  							$("#myModalLabel").html("Riwayat Pendidikan BKN");
		                            $("#modal-global").modal('show');
		                        });
							}else{
								swal("Perhatian", result.msg, "error");
							}
							
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
