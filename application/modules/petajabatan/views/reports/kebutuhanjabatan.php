
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
$tahunini = date("Y");
$batastahun = $tahunini + 5;
?>
<div class="admin-box box box-primary expanded-box">
    <div class="box-header">
              <h3 class="box-title">Pencarian Lanjut</h3>
               <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool btn-default btn-advanced-search" data-widget="collapse">
                        <i class="fa fa-minus"></i> Tampilkan
                    </button>
              </div>
    </div>

    <div class="box-body">
        
        <?php echo form_open($this->uri->uri_string(),"id=form_search_pegawai","form"); ?>
            <style>
                table.filter_pegawai tr td {
                    padding-top: 2px;
                }
            </style>
            <table class="filter_pegawai" sborder=0 width='100%' cellpadding="10">
                <tr>
                    <td width="20px"></td>
                    <td width="200px"><label for="example-text-input" class="col-form-label">Satuan Kerja</label></td>
                    <td colspan=2><select id="unit_id_key" name="unit_id_key" width="100%" class=" col-md-10 format-control"></select></td>
                </tr>
                <tr>
                    <td></td>
                    <td><label for="example-text-input" class="col-form-label">Nama Jabatan</label></td>
                    <td colspan=2>
                        <input type="text" name="nama_jabatan" class="form-control">
                    </td>
                </tr>
                 
                <tr>
                    <td colspan=4>
                        <button type="submit" class="btn btn-success pull-right "><i class="fa fa-search"></i> Cari</button>
                    </td>
                </tr>
            </table>
        <?php
        echo form_close();    
        ?>
    </div>
</div>
<div class="admin-box box box-primary">
    <div class="box-header box-tools">
             
 
    </div>
    <div class="box-body">
        <table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-data table-hover">
            <thead>
            <tr>
                <th style="width:10px" rowspan="2">No</th>
                <th rowspan="2">Unitkerja</th>
                <th rowspan="2">Jabatan</th>
                <th rowspan="2">ABK</th>
                <th rowspan="2">Riil</th>
                
                <th colspan = "6">Pensiun</th>
            </tr>
            <tr>
                <?php
                for($i=$tahunini;$i<=$batastahun;$i++){
                ?>
                <th><?php e($i); ?></th>
                <?php } ?>
            </tr>
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
$table = $(".table-data").DataTable({
    
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
      url: "<?php echo base_url() ?>admin/reports/petajabatan/getdata",
      type:'POST',
      "data": function ( d ) {
            d.search['advanced_search_filters']=  $("#form_search_pegawai").serializeArray();
        }
    }
});
$("#form_search_pegawai").submit(function(){
    $table.ajax.reload(null,true);
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