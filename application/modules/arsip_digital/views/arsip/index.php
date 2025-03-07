<div class="admin-box box box-primary expanded-box">
        <div class="box-header">
        <?php echo form_open($this->uri->uri_string(),"id=form_search_pegawai","form"); ?>
                <style>
                    table.filter_pegawai tr td {
                        padding-top: 2px;
                    }
                </style>
                <table class="filter_pegawai" sborder=0 width='100%' cellpadding="10">
                     
                    <tr>
                        <td width="20px"><input type="checkbox" name="nama_cb"></td>
                        <td width="200px"><label for="example-text-input" class="col-form-label">NAMA</label></td>
                        <td colspan=2><input class="form-control" type="text" name="nama_key" value="" ></td>
                    </tr>
                    <tr>
                        <td width="20px"><input type="checkbox" name="nip_cb"></td>
                        <td width="200px"><label for="example-text-input" class="col-form-label">NIP</label></td>
                        <td colspan=2><input class="form-control" type="text" name="nip_key" value="" ></td>
                    </tr>
                      
                    <tr>
                        <td width="20px"><input type="checkbox" name="kategori_cb"></td>
                        <td width="200px"><label for="example-text-input" class="col-form-label">Jenis Arsip</label></td>
                        <td colspan=2>
                            <select name="jenis_arsip" id="jenis_arsip" class="form-control">
                                <option value="">-- Silahkan Pilih --</option>
                                <?php if (isset($reckategori) && is_array($reckategori) && count($reckategori)):?>
								<?php foreach($reckategori as $record):?>
									<option value="<?php echo $record->ID?>"><?php echo $record->NAMA_JENIS; ?></option>
									<?php endforeach;?>
								<?php endif;?>
                            </select>
                        </td>
                    </tr>
                     
                    <tr>
                        <td colspan=4>
                            &nbsp;&nbsp;&nbsp;
                            <button type="submit" class="btn btn-success pull-right "><i class="fa fa-search"></i> Cari</button>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <a class='btn btn-warning pull-right show-modal' href="<?php echo site_url('admin/arsip/arsip_digital/createadm'); ?>"tooltip="Upload Dokumen"><i class="fa fa-upload" aria-hidden="true"></i> Upload Dokumen</a>&nbsp;&nbsp;&nbsp;
                        </td>
                    </tr>
                </table>
            <?php
            echo form_close();    
            ?>
         </div>
        <div class="box-body">
            <table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-data table-hover">
                <thead>
                <tr>
                    <th style="width:5px">No</th>
                    <th width="15%">Pegawai</th>
                    <th width="10%">Jenis Arsip</th>
                    <th>Keterangan</th>
                    
                    <th width="120px" align="center">#</th></tr>
                </thead>
            </table>
        </div>
     
    <script type="text/javascript">
      
    $grid_daftararsip = $(".table-data").DataTable({
        
        dom : "<'row'<'col-sm-6'><'col-sm-6'>>" +
        "<'row'<'col-sm-12'tr>>" +
        "<'row'<'col-sm-2'l><'col-sm-3'i><'col-sm-7'p>>",
        processing: true,
        serverSide: true,
        "columnDefs": [
                        {"className": "dt-center", "targets": [4]},
                        { "targets": [0,4], "orderable": false }
                    ],
        ajax: {
          url: "<?php echo base_url() ?>admin/arsip/arsip_digital/getdataall",
          type:'POST',
          "data": function ( d ) {
                d.search['advanced_search_filters']=  $("#form_search_pegawai").serializeArray();
            }
        }
    });
    $("#form_search_pegawai").submit(function(){
        $grid_daftararsip.ajax.reload(null,true);
        return false;
    });

$('body').on('click','.btn-hapus',function () { 
	var kode =$(this).attr("kode");
	swal({
		title: "Anda Yakin?",
		text: "Hapus dokumen digital!",
		type: "warning",
		showCancelButton: true,
		confirmButtonClass: 'btn-danger',
		confirmButtonText: 'Ya, Delete!',
		cancelButtonText: "Tidak, Batalkan!",
		closeOnConfirm: false,
		closeOnCancel: false
	},
	function (isConfirm) {
		if (isConfirm) {
			var post_data = "kode="+kode;
			//alert("<?php echo base_url() ?>admin/masters/data_ptp/deletedata"+post_data)
			$.ajax({
					url: "<?php echo base_url() ?>admin/arsip/arsip_digital/deletedata",
					type:"POST",
					data: post_data,
					dataType: "html",
					timeout:180000,
					success: function (result) {
						 swal("Deleted!", result, "success");
						 $grid_daftararsip.ajax.reload(null,true);
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
</script>
<script type="text/javascript">
  function submitdatadokumen(){
    $('#btnsave').addClass('disabled');
    var the_data = new FormData(document.getElementById("submit_form"));
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
                $grid_daftararsip.ajax.reload(null,true);
                $('#btnsave').removeClass('disabled');
            }else{
                $(".message").html(response.msg);
                $('#btnsave').removeClass('disabled');
            }
        }
    });
    
    return false; 
  }
   
$('body').on('click','#btnsave',function () { 
  submitdatadokumen();
}); 
$('body').on('click','#btnsavevalidasi',function () { 
  submitvalidasi();
});  
</script>