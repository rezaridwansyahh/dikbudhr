
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
            <?php if ($this->auth->has_permission('Riwayatjabatan.SyncBkn.View')) : ?>
            	<a class="btn btn-default btn-success margin pull-right" id="lihat_jabatan_bkn" kode="<?php echo $PNS_ID ?>" tooltip="Sinkron dengan BKN">
				<i class="fa fa-refresh"></i> Sinkron BKN
            	</a>
            <?php endif; ?>
            <?php if ($this->auth->has_permission('riwayatjabatan.Kepegawaian.Create')) : ?>
            <a type="button" class="show-modal-custom btn btn-default btn-warning margin pull-right " href="<?php echo base_url(); ?>pegawai/riwayatjabatan/add/<?php echo $PNS_ID ?>" tooltip="Tambah Riwayat Jabatan">
				<i class="fa fa-plus"></i> Tambah
            </a>
            <?php endif; ?>
            <?php if ($this->auth->has_permission('riwayatjabatan.Kepegawaian.Updmandiri')) : ?>
	            <a type="button" class="show-modal-custom btn btn-default btn-warning margin pull-right " href="<?php echo base_url(); ?>pegawai/riwayatjabatan/addmandiri/<?php echo $PNS_ID ?>" tooltip="Tambah Riwayat Jabatan (Mandiri)">
					<i class="fa fa-plus"></i> Tambah Mandiri
	            </a>
            <?php endif; ?>
            <table class="table table-datatable">
            <thead>
                <tr>
                    <th width='15px' >No</th>
                    <th>Jabatan</th>
                    <th>Unit Kerja</th>
                    <th width='100px' >TMT</th>
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
		$grid_daftar_jabatan = $(".table-datatable",$container).DataTable({
				ordering: false,
				processing: true,
				"bFilter": false,
				"bLengthChange": false,
				serverSide: true,
				"columnDefs": [
					//{"className": "dt-center", "targets": "_all"}
					{"className": "text-center", "targets": [0,3,4]}
				],
				ajax: {
					url: "<?php echo base_url() ?>pegawai/riwayatjabatan/ajax_list",
					type:'POST',
					data : {
						PNS_ID:'<?php echo $PNS_ID;?>'
					}
				}
		});
		$container.on('click','.show-modal-custom',function(event){
			showModalX.call(this,'sukses-tambah-riwayat-jabatan',function(){
				$grid_daftar_jabatan.ajax.reload();
			},this);
			event.preventDefault();
		});
		//$container.on('click','.show-modal',showModalX);

		$container.on('click','.btn-hapus-jabatan',function(event){
			event.preventDefault();
			var kode =$(this).attr("kode");
				swal({
					title: "Anda Yakin?",
					text: "Hapus data Riwayat jabatan!",
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
								url: "<?php echo base_url() ?>pegawai/riwayatjabatan/delete/"+kode,
								dataType: "html",
								timeout:180000,
								success: function (result) {
									swal("Data berhasil di hapus!", result, "success");
									$grid_daftar_jabatan.ajax.reload();
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
	$container.on('click','#lihat_jabatan_bkn',function(event){
		var kode =$(this).attr("kode");
		swal({
			title: "Anda Yakin?",
			text: "Lihat data riwayat jabatan berdasarkan data BKN!",
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
						url: "<?php echo base_url() ?>pegawai/bkn/viewjabatan",
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
		  							$("#myModalLabel").html("Riwayat Jabatan BKN");
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
