
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
            <?php if ($this->auth->has_permission('Arsip_digital.Arsip.Create')) : ?>
            <a type="button" class="show-modal btn btn-default btn-warning margin pull-right " href="<?php echo base_url(); ?>admin/arsip/arsip_digital/create/<?php echo $PNS_ID ?>" tooltip="Tambah Arsip Digital">
				<i class="fa fa-plus"></i> Tambah
            </a>
            <?php endif; ?>
            
            <table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-data table-hover table-datatablearsip">
                <thead>
                <tr>
                    <th style="width:5px">No</th>
                    <th>Jenis Arsip</th>
                    <th>Keterangan</th>
                    <th>Status</th>
                    <th width="120px" align="center">#</th></tr>
                </thead>
            </table>
        </div>
        </div>
    </div>
</div>
<!--tab-pane-->
<script type="text/javascript">
  

	(function($){
		var $container = $("#<?php echo $TAB_ID;?>");
		var grid_daftararsip = $(".table-datatablearsip",$container).DataTable({
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
					url: "<?php echo base_url() ?>admin/arsip/arsip_digital/ajax_list",
					type:'POST',
					data : {
						PNS_ID:'<?php echo $PNS_ID;?>'
					}
				}
		});
		$container.on('click','.show-modal-custom',function(event){
			showModalX.call(this,'sukses-tambah-arsip',function(){
				alert("masuk");
				grid_daftararsip.ajax.reload();
			},this);
			event.preventDefault();
		});
		//$container.on('click','.show-modal',showModalX);

		$container.on('click','.btn-hapus_arsip',function(event){
			event.preventDefault();
			var kode =$(this).attr("kode");
				swal({
					title: "Anda Yakin?",
					text: "Hapus berkas digital!",
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
						var urlarsipdel = "<?php echo base_url() ?>admin/arsip/arsip_digital/deletedata";
						$.ajax({
								url: urlarsipdel,
								data: post_data,
								type:"POST",
								dataType: "html",
								timeout:180000,
								success: function (result) {
									grid_daftararsip.ajax.reload(null,true);
									swal("Deleted!", result, "success");
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
		$('body').on('click','#btncancelarsip',function () { 
		  $("#modal-global").modal("hide");
		});  
		$('body').on('click','#btnsavearsip',function () { 
		  submitdatadokumen();
		});
		$('body').on('click','#btnsavevalidasi',function () { 
		  submitvalidasi();
		});
		
		function submitdatadokumen(){
		    $('#btnsavearsip').addClass('disabled');
		    var the_data = new FormData(document.getElementById("formarsip"));
		    $.ajax({
		        url: "<?php echo base_url('admin/arsip/arsip_digital/act_save'); ?>",
		        type: "POST",
		        data: the_data,
		        enctype: 'multipart/form-data',
		        processData: false, // tell jQuery not to process the data
		        contentType: false, // tell jQuery not to set contentType
		        dataType: 'JSON',

		        beforeSend: function (xhr) {
		            //$("#loading-all").show();
		        },
		        success: function (response) {
		            if(response.success){
		                swal("Sukses",response.msg,"success");
		                $("#modal-global").modal("hide");
		                grid_daftararsip.ajax.reload(null,true);
		                $('#btnsavearsip').removeClass('disabled');
		            }else{
		                $(".messagearsip").html(response.msg);
		                $('#btnsavearsip').removeClass('disabled');
		            }
		        }
		    });
		    
		    return false; 
		  }
		  function submitvalidasi(){
		    $('#btnsavevalidasi').addClass('disabled');
		    var the_data = new FormData(document.getElementById("formarsip"));
		    $.ajax({
		        url: "<?php echo base_url('admin/arsip/arsip_digital/act_save_validasi'); ?>",
		        type: "POST",
		        data: the_data,
		        enctype: 'multipart/form-data',
		        processData: false, // tell jQuery not to process the data
		        contentType: false, // tell jQuery not to set contentType
		        dataType: 'JSON',

		        beforeSend: function (xhr) {
		            //$("#loading-all").show();
		        },
		        success: function (response) {
		            if(response.success){
		                swal("Sukses",response.msg,"success");
		                $("#modal-global").modal("hide");
		                grid_daftararsip.ajax.reload(null,true);
		                $('#btnsavevalidasi').removeClass('disabled');
		            }else{
		                $(".messagearsip").html(response.msg);
		                $('#btnsavevalidasi').removeClass('disabled');
		            }
		        }
		    });
		    
		    return false; 
		  }

	})(jQuery);
</script>
