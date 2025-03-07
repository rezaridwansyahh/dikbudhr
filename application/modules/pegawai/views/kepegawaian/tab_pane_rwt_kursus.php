
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
            <?php if ($this->auth->has_permission('Riwayatkursus.Kepegawaian.Create')) : ?>
            <a type="button" class="show-modal-custom btn btn-default btn-warning margin pull-right " href="<?php echo base_url(); ?>pegawai/Riwayatkursus/add/<?php echo $PNS_ID ?>" tooltip="Tambah Riwayat Kursus" title="Tambah Riwayat Kursus">
				<i class="fa fa-plus"></i> Tambah
            </a>
            <?php endif; ?>
            <table class="table table-datatable">
            <thead>
                <tr>
                    <th width='20px' >No</th>
                    <th>Tipe Diklat</th>
                    <th>Jenis Diklat</th>
                    <th width='100px' >Nama Diklat</th>
                    <th width='100px' >Jumlah Jam</th>
					<th width='100px' >Tanggal Diklat</th>
					<th width='100px' >No Sertifikat</th>
                    <th width='100px' align="center">Aksi</th>
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
					{"className": "dt-center", "targets": [0,2,3,4,5]}
				],
				ajax: {
					url: "<?php echo base_url() ?>pegawai/Riwayatkursus/ajax_list",
					type:'POST',
					data : {
						PNS_ID:'<?php echo $PNS_ID;?>'
					}
				}
		});
		$container.on('click','.show-modal-custom',function(event){
			showModalX.call(this,'sukses-tambah-riwayat-kursus',function(){
				grid_daftar.ajax.reload();
			},this);
			event.preventDefault();
		});
		//$container.on('click','.show-modal',showModalX);

		$container.on('click','.btn-kirim-siasn',function(event){
			event.preventDefault();
			var kode =$(this).attr("kode");
			
			swal({
					title: "Anda Yakin?",
					text: "Mengirim data ke SIASN!",
					type: "warning",
					showCancelButton: true,
					confirmButtonClass: 'btn-success',
					confirmButtonText: 'Ya, Kirim!',
					cancelButtonText: "Tidak, Batalkan!",
					closeOnConfirm: false,
					closeOnCancel: false,
					showLoaderOnConfirm: true
				},
				function (isConfirm) {
					if (isConfirm) {
						sendToSIASN(kode);     
						
					} else {
						swal("Batal", "", "error");
					}
				});
			
		});

		$container.on('click','.btn-hapus',function(event){
			event.preventDefault();
			var kode =$(this).attr("kode");
				swal({
					title: "Anda Yakin?",
					text: "Hapus data Riwayat Kursus!",
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
								url: "<?php echo base_url() ?>pegawai/Riwayatkursus/delete/"+kode,
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

		function sendToSIASN(id) {
			var formData = new FormData();
			formData.append("id", id);
			var json_url = "<?php echo base_url() ?>pegawai/Riwayatkursus/send_siasn";
			

			$.ajax({
				type: "POST",
				url: json_url,
				data: formData,
				dataType: "json",
				processData: false, // tell jQuery not to process the data
				contentType: false, // tell jQuery not to set contentType
				success: function (data) {
					console.log(data);
					swal({
						title: "Sukses!",
						text: data.msg,
						type: "success",
						timer: 4000,
						showConfirmButton: true
					}, function () {
						grid_daftar.ajax.reload();
					});
				}
			});
		}
				
	})(jQuery);
</script>
