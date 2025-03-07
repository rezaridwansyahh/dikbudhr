<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/datatables/dataTables.bootstrap.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css">
<!-- sweet alert -->
<script src="<?php echo base_url(); ?>themes/admin/js/sweetalert.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/css/sweetalert.css">
<!-- sweet alert -->
<link href="<?php echo base_url(); ?>themes/admin/plugins/select2/select2.min.css" rel="stylesheet" type="text/css" />
<?php

$checkSegment = $this->uri->segment(4);
$areaUrl = SITE_AREA . '/masters/data_ptp';

$num_columns	= 21;
$can_delete	= $this->auth->has_permission('Data_Ptp.Masters.Delete');
$can_edit		= $this->auth->has_permission('Data_Ptp.Masters.Edit');
$has_records	= isset($records) && is_array($records) && count($records);

if ($can_delete) {
    $num_columns++;
}
?>
<div class='box box-primary'>
        <div class="portlet light box box-primary box-body">
            <div class="portlet-title">
                
                <style>
                    table.filter_pegawai tr td {
                        padding-top: 2px;
                    }
                </style>
            <form action="#" id="form_search_pegawai" method="post" accept-charset="utf-8">
                <table class="filter_pegawai" sborder=0 width='100%' cellpadding="10">
                    
                    <tr>
                        <td width="200px"><label for="example-text-input" class="col-form-label">Role</label></td>
                        <td colspan=2>
                            <select name="role_id" id="role_id"  class="form-control select2" >
                                <option value=""> Pilih Role </option>
                                <?php if (isset($roles) && is_array($roles) && count($roles)):?>
                                <?php foreach($roles as $record):?>
                                    <option value="<?php echo $record->role_id; ?>"><?php echo $record->role_name; ?></option>
                                    <?php endforeach;?>
                                <?php endif;?>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td width="200px"><label for="example-text-input" class="col-form-label">Display Name</label></td>
                        <td colspan=2><input class="form-control" type="text" name="nama_key" value="" ></td>
                    </tr>
                    <tr>
                
                        <td width="200px"><label for="example-text-input" class="col-form-label">Username</label></td>
                        <td colspan=2><input class="form-control" type="text" name="username_key" value="" ></td>
                    </tr>
                    <tr>
                        <td width="200px"><label for="example-text-input" class="col-form-label">Status Aktif</label></td>
                        <td colspan=2>
                            <select name="status_active" id="status_active"  class="form-control select2" >
                                <option value=""> Pilih </option>
                                <option value="1"> Aktif </option>
                                <option value="0"> Tidak Aktif </option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td width="200px"><label for="example-text-input" class="col-form-label">Deleted</label></td>
                        <td colspan=2>
                            <select name="status_deleted" id="status_deleted"  class="form-control select2" >
                                <option value=""> Pilih </option>
                                <option value="1"> Deleted </option>
                                <option value="0"> Aktif </option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td colspan=4>
                            <a href="<?php echo base_url(); ?>admin/settings/users/create" class="btn blue button-submit btn-warning pull-right"> Tambah User
                                <i class="fa fa-plus"></i>
                            </a>
                            &nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;
                            <button type="botton" class="btn btn-success pull-right btn_cari"><i class="fa fa-search"></i> Cari</button>
                            &nbsp;&nbsp;&nbsp;
                        </td>
                    </tr>
                </table>
            </form>
            </div>
            <div class="portlet-body">
                 <table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-data table-hover">
                    <thead>
                    <tr>
                        <th style="width:10px">No</th>
                        <th>DisplayName</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Last Login</th>
                        <th>Status</th>
                        <th width="100px" align="center">#</th></tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
     
</div>

<script src="<?php echo base_url(); ?>themes/admin/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="<?php echo base_url(); ?>themes/admin/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>themes/admin/plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>themes/admin/plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>themes/admin/plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js"></script>
<script src="<?php echo base_url(); ?>themes/admin/plugins/datatables/extensions/Responsive/js/dataTables.responsive.js"></script>
<script language='JavaScript' type='text/javascript' src='<?php echo base_url(); ?>themes/admin/plugins/select2/select2.full.min.js'></script>

 <script type="text/javascript">
      
    $table = $(".table-data").DataTable({
        
        dom : "<'row'<'col-sm-6'><'col-sm-6'>>" +
        "<'row'<'col-sm-12'tr>>" +
        "<'row'<'col-sm-2'l><'col-sm-3'i><'col-sm-7'p>>",
        processing: true,
        serverSide: true,
        "columnDefs": [
                        {"className": "text-center", "targets": [0,5,6]},
                        { "targets": [0,3,6], "orderable": false }
                    ],
        ajax: {
          url: "<?php echo base_url() ?>admin/settings/users/getdataall_user",
          type:'POST',
          "data": function ( d ) {
                d.search['advanced_search_filters']=  $("#form_search_pegawai").serializeArray();
            }
        }
    });
    
    $(".btn_cari").click(function(){
        $table.ajax.reload(null,true);
        return false;
    });


$('body').on('click','.btn-hapus',function () { 
	var kode =$(this).attr("kode");
	swal({
		title: "Anda Yakin?",
		text: "Delete data user!",
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
					url: "<?php echo base_url() ?>admin/settings/users/hapususer/"+kode,
					type:"POST",
					data: post_data,
					dataType: "html",
					timeout:180000,
					success: function (result) {
						 swal("Deleted!", result, "success");
                         $table.ajax.reload(null,true);
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
$('body').on('click','.btl_kembalikan',function () { 
    var kode =$(this).attr("kode");
    swal({
        title: "Anda Yakin?",
        text: "Kembalikan data user!",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: 'btn-success',
        confirmButtonText: 'Ya, Kembalikan!',
        cancelButtonText: "Tidak, Batalkan!",
        closeOnConfirm: false,
        closeOnCancel: false
    },
    function (isConfirm) {
        if (isConfirm) {
            var post_data = "kode="+kode;
            //alert("<?php echo base_url() ?>admin/masters/data_ptp/deletedata"+post_data)
            $.ajax({
                    url: "<?php echo base_url() ?>admin/settings/users/kembalikanuser/"+kode,
                    type:"POST",
                    data: post_data,
                    dataType: "html",
                    timeout:180000,
                    success: function (result) {
                         swal("Deleted!", result, "success");
                         $table.ajax.reload(null,true);
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
$('body').on('click','.btl_aktifkan',function () { 
    var kode =$(this).attr("kode");
    swal({
        title: "Anda Yakin?",
        text: "Aktifkan user!",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: 'btn-success',
        confirmButtonText: 'Ya, Aktifkan!',
        cancelButtonText: "Tidak, Batalkan!",
        closeOnConfirm: false,
        closeOnCancel: false
    },
    function (isConfirm) {
        if (isConfirm) {
            var post_data = "kode="+kode;
            //alert("<?php echo base_url() ?>admin/masters/data_ptp/deletedata"+post_data)
            $.ajax({
                    url: "<?php echo base_url() ?>admin/settings/users/aktifkanuser/"+kode,
                    type:"POST",
                    data: post_data,
                    dataType: "html",
                    timeout:180000,
                    success: function (result) {
                         swal("Deleted!", result, "success");
                         $table.ajax.reload(null,true);
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
$('body').on('click','.btl_nonaktifkan',function () { 
    var kode =$(this).attr("kode");
    swal({
        title: "Anda Yakin?",
        text: "Nonaktifkan user!",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: 'btn-danger',
        confirmButtonText: 'Ya, Nonaktifkan!',
        cancelButtonText: "Tidak, Batalkan!",
        closeOnConfirm: false,
        closeOnCancel: false
    },
    function (isConfirm) {
        if (isConfirm) {
            var post_data = "kode="+kode;
            //alert("<?php echo base_url() ?>admin/masters/data_ptp/deletedata"+post_data)
            $.ajax({
                    url: "<?php echo base_url() ?>admin/settings/users/nonaktifkanuser/"+kode,
                    type:"POST",
                    data: post_data,
                    dataType: "html",
                    timeout:180000,
                    success: function (result) {
                         swal("Deleted!", result, "success");
                         $table.ajax.reload(null,true);
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

$(".select2").select2();
 

</script>
