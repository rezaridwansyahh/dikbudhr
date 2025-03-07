<?php 
    $id = isset($selectedData->id) ? $selectedData->id : '';
?>
<div class='box box-warning' id="form-diklat-struktural-add">
    <?php echo form_open_multipart($this->uri->uri_string(), 'id="form-dokumen-pendukung"'); ?> 
    <input type="hidden" name="perkiraan_id" value="<?php echo $id;?>">
	<div class="box-body">
        <div class="messages">
        </div>
       

     <table id="table-dokumen-pendukung" class="slug-table table table-bordered table-striped table-responsive dt-responsive table-datatable table-hover">
        <thead>
            <tr>
                <tr><th style="width:10px">No</th>
                <th>Nama Dokumen</th>
                <th width="70px">#</th>
            </tr>
        </thead>
    </table>
</div>
<script src="<?php echo base_url(); ?>themes/admin/plugins/datepicker/bootstrap-datepicker.js"></script>

<style>
    #table-dokumen tr > td{
        padding:2px;
    }
    #table-dokumen td input{
        padding:0px;
    }
</style>
<script>
    function hideCustomModal(){
        $("#modal-custom-global").modal("hide");
    }
	$(document).ready(function(){
        $("#form-dokumen-pendukung").submit(function(){
            jQuery.ajax({
                    type: 'POST',
                    url:"<?php echo $module_url?>/save_dokumen_pendukung",
                    data: new FormData($(this)[0]),
                    processData: false, 
                    contentType: false, 
                    success: function(returnval) {
                       hideCustomModal();
                    }
            });
            return false;
        });
        $('body').on('click','.btn-view',function () { 
            window.location = $(this).data('view-url');
        });
        $('body').on('click','.btn-hapus',function () { 
            var $this =$(this);
            swal({
                title: "Anda Yakin?",
                text: "Hapus data dokumen!",
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
                    $.ajax({
                            url: $this.data('remove-url'),
                            type:"POST",
                            dataType: "html",
                            timeout:180000,
                            success: function (result) {
                                swal("Data berhasil di hapus!", result, "success");
                                grid_dokumen.ajax.reload();
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
		var grid_dokumen = $("#table-dokumen-pendukung").DataTable({
            dom : "<'row'<'col-sm-6'><'col-sm-6'>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-2'l><'col-sm-3'i><'col-sm-7'p>>",
            ordering: false,
            processing: true,
            serverSide: true,
            ajax: {
                url: "<?php echo $module_url; ?>/ajax_list_dokumen",
                type:'POST',
                data: function ( d ) {
                    d.perkiraan_id = "<?php echo $id;?>";
                }
            }
        });
	});
</script>