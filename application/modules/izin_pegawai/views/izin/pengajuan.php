<?php
    $this->load->library('Convert');
    $convert = new Convert;
?>
<div class="tab-pane active" id="<?php echo $TAB_ID;?>">
    <div class="row">
         
        <div class="col-md-6">
            <h5><b>Jenis Pengajuan</b></h5>
            <select id="filter_request_catalog" class="select2" style="width:100%">
                <option value="">SILAHKAN PILIH</option>
                <?php
                if(isset($jenis_izin) && is_array($jenis_izin) && count($jenis_izin)):
                        foreach ($jenis_izin as $record) {
                    ?>
                        <option value="<?php echo $record->ID; ?>" <?php echo $id_jenis_izin == $record->ID ? "selected" : ""; ?>><?php echo $record->NAMA_IZIN; ?></option>
                    <?php 
                        }
                    endif;
                    ?>
                
            </select>
        </div>
        <!-- /.col -->
        <div class="col-md-6">
            
            <h5><b>Hari Libur bulan <?php echo $nama_bulan; ?></b></h5>
            <!-- /.box-header -->
            <?php
            if(isset($record_hari_libur_bulan_ini) && is_array($record_hari_libur_bulan_ini) && count($record_hari_libur_bulan_ini)):
                $index = 1;
                foreach ($record_hari_libur_bulan_ini as $record) {
                    $tanggal_indo = "";
                    if($record->START_DATE == $record->END_DATE){
                        $tanggal_indo = $convert->fmtDate($record->START_DATE,"dd month yyyy");
                    }else{
                        $tanggal_indo = $convert->fmtDate($record->START_DATE,"dd month yyyy")." - ".$convert->fmtDate($record->END_DATE,"dd month yyyy");
                    }
                    echo $index.". ".$record->INFO." <i>Tanggal</i> ". $tanggal_indo." <br>";
                    $index++;
                }
            // hapus data lama terlebih dahulu yang tahun dan bulan yang sama
            ?>
            <?php
            else:
            ?>
            Tidak ada libur pada bulan ini
            <?php
            endif;
            ?>
        </div>
        <!-- /.col -->
    </div>

    <hr>

    <div class="row">

        <div class="col-md-12 form-content">
             
        </div>
    </div>
</div>
<script>
$(".select2").select2();
$("#filter_request_catalog").change(function(){
    var kode = $("#filter_request_catalog").val();
    if(kode!=""){
        $(".form-content").load("<?php echo base_url(); ?>admin/izin/izin_pegawai/create/"+kode);    
    }else{
        $(".form-content").html("");
    }
    
});
</script>
<script type="text/javascript">
 
$table = $(".table-data").DataTable({
    
    dom : "<'row'<'col-sm-6'><'col-sm-6'>>" +
    "<'row'<'col-sm-12'tr>>" +
    "<'row'<'col-sm-2'l><'col-sm-3'i><'col-sm-7'p>>",
    processing: true,
    serverSide: true,
    "columnDefs": [
                    {"className": "text-center", "targets": [0,5,6]},
                    { "targets": [0,4], "orderable": false }
                ],
    ajax: {
      url: "<?php echo base_url() ?>admin/izin/izin_pegawai/getdata_izin",
      type:'POST',
      "data": function ( d ) {
            //d.search['advanced_search_filters']=  $("#form_search_pegawai").serializeArray();
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
        text: "Delete pengajuan izin!",
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
                    url: "<?php echo base_url() ?>admin/izin/izin_pegawai/deletedata",
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