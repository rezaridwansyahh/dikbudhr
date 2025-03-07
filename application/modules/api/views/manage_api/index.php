<?php $TAB_ID = 'manage_api';?>

<style>
	.dt-center {
		text-align:center;
	}
</style>
<!--tab-pane-->
<div class="admin-box box box-primary">
	<div class="box-header">
              <h3 class="box-title">Data API</h3>
	</div>
	<div class="box-body">
		<div class="tab-pane" id="<?php echo $TAB_ID;?>">
				<div class="form-group">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-21 col-xs-12">
						<?php if ($this->auth->has_permission('ManageApi.Masters.Create')) : ?>
						<a type="button" class="show-modal-custom btn btn-default btn-warning margin pull-right " href="<?php echo base_url(); ?>api/manage_api/crud" tooltip="Tambah API">
							<i class="fa fa-plus"></i> Tambah
						</a>
						<?php endif; ?>
						<table class="table table-datatable">
						<thead>
							<tr>
								<th>No</th>
								<th>API</th>
								<th width='50%'>Keterangan</th>
								<th width='100px'>Url</th>
								<th width='50px' align="center">#</th>
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
	</div>
</div>	



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
					{"className": "dt-center", "targets": [0,3,4]}
				],
				ajax: {
					url: "<?php echo base_url() ?>api/manage_api/ajax_list",
					type:'POST',
					data : {
					}
				}
		});
		
		$container.on('click','.show-modal-custom',function(event){
			showModalX.call(this,'sukses-tambah-manage-api',function(){
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
					text: "Hapus data Riwayat Pendidikan!",
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
								url: "<?php echo base_url() ?>api/manage_api/delete/"+kode,
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
