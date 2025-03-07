<div class="admin-box box box-primary expanded-box">
        <div class="box-header">
        <?php echo form_open($this->uri->uri_string(),"id=form_search_pegawai","form"); ?>
                <style>
                    table.filter_pegawai tr td {
                        padding-top: 2px;
                    }
                </style>
                <div class='alert alert-block alert-info fade in'>
                    <a class='close' data-dismiss='alert'>&times;</a>
                    <h4 class='alert-heading'>
                        Perhatian
                    </h4>
                    <p>Halaman ini untuk melihat seluruh daftar draft dokumen yang pernah dibuat</p>
                    <p>Pada Kolom DS menandakan bahwa dokumen tersebut sudah ada atau belum di daftar SK dokumen digital</p>
                    <p>Jika draft SK sudah di tandatangan maka, dokumen tersebut tidak bisa dihapus atau diedit lagi</p>
                </div>
                <table class="filter_pegawai" sborder=0 width='100%' cellpadding="10">
                    <tr>
                        <td width="20px"><input type="checkbox" name="unit_id_cb"></td>
                        <td width="200px"><label for="example-text-input" class="col-form-label">Satuan Kerja</label></td>
                        <td colspan=2><select id="unit_id_key" name="unit_id_key" width="100%" class=" col-md-10 format-control"></select></td>
                    </tr>
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
                        <td width="20px"><input type="checkbox" name="chkpenandatangan"></td>
                        <td width="200px"><label for="example-text-input" class="col-form-label">ID Penandatangan (ID Pegawai)</label></td>
                        <td colspan=2><input class="form-control" type="text" name="id_pegawai_ttd" value="" ></td>
                    </tr>
                    <tr>
                        <td width="20px"><input type="checkbox" name="no_dok"></td>
                        <td width="200px"><label for="example-text-input" class="col-form-label">Nomor Dokumen/Nomor SK</label></td>
                        <td colspan=2><input class="form-control" type="text" name="nomor_sk" value="" ></td>
                    </tr>
                    <tr>
                        <td width="20px"><input type="checkbox" name="kategori_cb"></td>
                        <td width="200px"><label for="example-text-input" class="col-form-label">Kategori SK</label></td>
                        <td colspan=2>
                            <select name="kategori_sk" id="kategori_sk" class="form-control">
                                <option value="">-- Silahkan Pilih --</option>
                                <?php 
                                    foreach ($reckategori_ds as $record) {
                                ?>
                                        <option value='<?php echo $record->id; ?>'><?php echo $record->nama_proses; ?></option>
                                <?php
                                    }
                                ?>
                            </select>
                        </td>
                    </tr>
                      
                    <tr>
                        <td colspan=4>
                            &nbsp;&nbsp;&nbsp;
                            <button type="submit" class="btn btn-success pull-right "><i class="fa fa-search"></i> Cari</button>
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
                    <th style="width:10px">No</th>
                    <th width="15%">Proses</th>
                    <th width="30%">Pegawai</th>
                    <th>Penandatangan</th>
                    <th>No SK / Tgl SK</th>
                    <th width="20px">DS</th>
                    <th width="60px" align="center">#</th></tr>
                </thead>
            </table>
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
                        {"className": "text-center", "targets": [5]},
                        { "targets": [0,5], "orderable": false }
                    ],
        ajax: {
          url: "<?php echo base_url() ?>admin/tte/tte/ajax_data",
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
    $("#linkqr").click(function(){
        $( ".chkqr" ).prop( "checked", true );
    });
     
     
    $( "#textqrcode" ).focus(function() {
      $( ".chkqr" ).prop( "checked", true );
      $( "#textqrcode" ).val("");
    });

    $('body').on('click','.popupurl',function () { 
        var url1 =$(this).attr("url");
        popitup1(url1,"draftsk");
    });
     
    function popitup1(url,title = "View draft SK",w = 700,h=600) {
        var left = (screen.width/2)-(w/2);
        var top = (screen.height/2)-(h/2);

          return window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);

    }
    function popitup2(url,title = "View SK ttd",w = 700,h=600) {
        var left = (screen.width/0)-(w/0);
        var top = (screen.height/2)-(h/2);

          return window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);

    }


$('body').on('click','.btn-hapus',function () { 
    var kode =$(this).attr("kode");
    swal({
        title: "Anda Yakin?",
        text: "Hapus draft dokumen ini akan menghapus file di daftar dokumen digital juga, anda yakin akan menghapusnya",
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
                    url: "<?php echo base_url() ?>admin/tte/tte/delete",
                    type:"POST",
                    data: post_data,
                    dataType: "html",
                    timeout:180000,
                    success: function (result) {
                         swal("Data berhasil di hapus!", result, "success");
                         $table.ajax.reload();
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
</div>
