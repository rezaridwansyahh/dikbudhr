
<?php
$checkSegment = $this->uri->segment(4);
$areaUrl = SITE_AREA . '/masters/petajabatan';
$num_columns    = 44;
$can_delete = $this->auth->has_permission('Petajabatan.Masters.Delete');
$can_edit       = $this->auth->has_permission('Petajabatan.Masters.Edit');
$has_records    = isset($records) && is_array($records) && count($records);

if ($can_delete) {
    $num_columns++;
}
?>
<div class="admin-box box box-primary">
    <div class="box-header box-tools">
            <center><h4><?php echo $data_unit->NAMA_UNOR; ?></h2></center>
            <div class="btn-group pull-right">
               <?php if ($this->auth->has_permission('Petajabatan.Masters.Create')) : ?>
                    <a class='btn btn-warning' href="<?php echo site_url($areaUrl . '/addkuota'); ?>/<?php echo $data_unit->ID; ?>"><i class="fa fa-plus"></i>Tambah Data</a>
                <?php endif; ?>
             </div>
    </div>
    <div class="box-body">
        <table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-data-detil table-hover">
            <thead>
            <tr>
                <th style="width:10px">No</th>
                <th>Unitkerja</th>
                <th>Jabatan</th>
                <th width="20px">Kuota</th>
                <th width="50px">Peraturan</th>
                <th width="50px" align="center">#</th></tr>
            </thead>
        </table>
    </div>
</div>

<script type="text/javascript">

$("#unit_id_key").select2({
    placeholder: 'Cari Unit Kerja...',
    width: '100%',
    minimumInputLength: 0,
    allowClear: true,
    ajax: {
        url: '<?php echo site_url("pegawai/kepegawaian/ajax_unit_list");?>',
        dataType: 'json',
        data: function(params) {
            return {
                term: params.term || '',
                page: params.page || 1
            }
        },
        cache: true
    }
});
$table_detil = $(".table-data-detil").DataTable({
    
    dom : "<'row'<'col-sm-6'><'col-sm-6'>>" +
    "<'row'<'col-sm-12'tr>>" +
    "<'row'<'col-sm-2'l><'col-sm-3'i><'col-sm-7'p>>",
    processing: true,
    serverSide: true,
    "columnDefs": [
                    {"className": "text-center", "targets": [0,3,5]},
                    { "targets": [0,4], "orderable": false }
                ],
    ajax: {
      url: "<?php echo base_url() ?>admin/masters/petajabatan/getdata",
      type:'POST',
      "data": function ( d ) {
            d.id_satker = "<?php echo $id_satker; ?>";
            d.permen = "<?php echo $permen; ?>";
        }
    }
});
$("#form_search_pegawai").submit(function(){
    $table_detil.ajax.reload(null,true);
    return false;
});
 
$('body').on('click','.btn-hapus',function () { 
    var kode =$(this).attr("kode");
    swal({
        title: "Anda Yakin?",
        text: "Delete data Petajabatan!",
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
            $.ajax({
                    url: "<?php echo base_url() ?>admin/masters/petajabatan/deletekuota",
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

</script>