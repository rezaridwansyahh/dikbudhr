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
                        <td width="20px"><input type="checkbox" name="chkidfile"></td>
                        <td width="200px"><label for="example-text-input" class="col-form-label">ID File DS</label></td>
                        <td colspan=2><input class="form-control" type="text" name="id_file" value="" ></td>
                    </tr>
                    <tr>
                        <td width="20px"><input type="checkbox" name="ch_nosk"></td>
                        <td width="200px"><label for="example-text-input" class="col-form-label">Nomor SK</label></td>
                        <td colspan=2><input class="form-control" type="text" name="nomor_sk" value="" ></td>
                    </tr>
                    <tr>
                        <td width="20px"><input type="checkbox" name="golongan_cb"></td>
                        <td width="200px"><label for="example-text-input" class="col-form-label">Golongan</label></td>
                        <td colspan=2>
                            <select name="golongan_key" class="form-control">
                                <?php 
                                    foreach($golongans as $row){
                                        echo "<option value='".$row->ID."'>$row->NAMA_PANGKAT $row->NAMA</option>";
                                    }
                                ?>
                            </select>
                        </td>
                    </tr>
                    
                    <tr>
                        <td width="20px"><input type="checkbox" name="kategori_cb"></td>
                        <td width="200px"><label for="example-text-input" class="col-form-label">Jabatan</label></td>
                        <td colspan=2>
                            <select name="kategori_jabatan" id="kategori_jabatan" class="form-control select2">
                            <option value="">-- Silahkan Pilih --</option>
                            <option value="Pelaksana" <?php if(isset($jabatan->KATEGORI_JABATAN))  echo  (trim($jabatan->KATEGORI_JABATAN)=="Pelaksana") ? "selected" : ""; ?>>Pelaksana</option>
                            <option value="Administrator" <?php if(isset($jabatan->KATEGORI_JABATAN))  echo  (trim($jabatan->KATEGORI_JABATAN)=="Administrator") ? "selected" : ""; ?>>Administrator</option>
                            <option value="Fungsional" <?php if(isset($jabatan->KATEGORI_JABATAN))  echo  (trim($jabatan->KATEGORI_JABATAN)=="Fungsional") ? "selected" : ""; ?>>Fungsional</option>
                            <option value="Menteri" <?php if(isset($jabatan->KATEGORI_JABATAN))  echo  (trim($jabatan->KATEGORI_JABATAN)=="Menteri") ? "selected" : ""; ?>>Menteri</option>
                            <option value="JPT Madya" <?php if(isset($jabatan->KATEGORI_JABATAN))  echo  (trim($jabatan->KATEGORI_JABATAN)=="JPT Madya") ? "selected" : ""; ?>>JPT Madya</option>
                            <option value="Pengawas" <?php if(isset($jabatan->KATEGORI_JABATAN))  echo  (trim($jabatan->KATEGORI_JABATAN)=="Pengawas") ? "selected" : ""; ?>>Pengawas</option>
                            <option value="Staf Khusus" <?php if(isset($jabatan->KATEGORI_JABATAN))  echo  (trim($jabatan->KATEGORI_JABATAN)=="Staf Khusus") ? "selected" : ""; ?>>Staf Khusus</option>
                            <option value="JPT Pratama" <?php if(isset($jabatan->KATEGORI_JABATAN))  echo  (trim($jabatan->KATEGORI_JABATAN)=="JPT Pratama") ? "selected" : ""; ?>>JPT Pratama</option>
                        </select>
                        </td>
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
                                        <option value='<?php echo $record->kategori_ds; ?>'><?php echo $record->kategori_ds; ?></option>
                                <?php
                                    }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td width="20px"><input type="checkbox" disabled name="jenis"></td>
                        <td width="200px"><label for="example-text-input" class="col-form-label">Jenis</label></td>
                        <td colspan=2>
                            <select name="ds_ok" id="ds_ok" class="form-control select2">
                            <option value="">-- Silahkan Pilih --</option>
                            <option value="1">Tanda tangan Elektronik</option>
                            <option value="-">Tanda tangan Manual</option>
                        </select>
                        </td>
                    </tr>
                    <tr>
                        <td width="20px"><input type="checkbox" name="chkstatus"></td>
                        <td width="200px"><label for="example-text-input" class="col-form-label">Status TTD</label></td>
                        <td colspan=2>
                            <select name="is_signed" id="is_signed" class="form-control select2">
                            <option value="">-- Silahkan Pilih --</option>
                            <option value="1">Sudah Tandatangan</option>
                            <option value="0">Belum Tandatangan</option>
                        </select>
                        </td>
                    </tr>

                    <tr>
                        <td width="20px"><input type="checkbox" name="chkstatuskoreksi"></td>
                        <td width="200px"><label for="example-text-input" class="col-form-label">Status Koreksi</label></td>
                        <td colspan=2>
                            <select name="is_corrected" id="is_corrected" class="form-control select2">
                            <option value="">-- Silahkan Pilih --</option>
                            <option value="1">Sudah Koreksi</option>
                            <option value="0">Belum Koreksi</option>
                            <option value="3">Dikembalikan</option>
                        </select>
                        </td>
                    </tr>


                    
                    <tr>
                        <td width="20px"><input type="checkbox" class="chkqr" name="chkqr"></td>
                        <td width="200px"><label for="example-text-input" class="col-form-label">Qr</label></td>
                        <td colspan=2>
                            <a href="<?php echo base_url(); ?>admin/sk/sk_ds/scanqr" id="linkqr" data-toggle="tooltip" class="btn btn-sm btn-info show-modal" data-original-title="Scan QR"><i class="glyphicon glyphicon-eye-open"></i> Scan Qrcode </a>
                            <input type="text" id="textqrcode" name="textqrcode" class="form-control">
                            
                        </select>
                        </td>
                    </tr>
                    <tr>
                        <td colspan=4>
                            &nbsp;&nbsp;&nbsp;
                            <button type="submit" class="btn btn-success pull-right "><i class="fa fa-search"></i> Cari</button>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <a class='btn btn-warning pull-right' href="<?php echo site_url('admin/arsip/sk_ds/uploadsk'); ?>"tooltip="Upload SK baru"><i class="fa fa-upload" aria-hidden="true"></i> Upload SK</a>&nbsp;&nbsp;&nbsp;
                            <button class='btn btn-danger pull-right downloadzip' href="<?php echo site_url('admin/sk/sk_ds/downloadskall_ttd'); ?>" data-toggle="tooltip" data-original-title="Download SK yang sudah ditandatangan" tooltip="Download File SK yang sudah ditandatangan"><i class="fa fa-download" aria-hidden="true"></i> Download SK</button>&nbsp;&nbsp;&nbsp;
                            <button class='btn btn-info pull-right downloadxls' href="<?php echo site_url('admin/sk/sk_ds/downloadxls'); ?>" data-toggle="tooltip" data-original-title="Download daftar dalam bentuk xls" tooltip="Download xls"><i class="fa fa-download" aria-hidden="true"></i> Download xls</button>&nbsp;&nbsp;&nbsp;
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
                    <th width="15%">Pemilik SK</th>
                    <th width="10%">Kategori SK</th>
                    <th>No SK / Tgl SK</th>
                    <th>Unit Kerja</th>
                    <th>TTD</th>
                    <th width="120px" align="center">#</th></tr>
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
        stateSave: true,
        "columnDefs": [
                        {"className": "text-center", "targets": [6]},
                        { "targets": [0,5], "orderable": false }
                    ],
        ajax: {
          url: "<?php echo base_url() ?>admin/sk/sk_ds/getdataall",
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
    $('body').on('click','.generatedatabkn',function () { 
        var kode =$(this).attr("kode");
        swal({
            title: "Anda Yakin?",
            text: "Sinkronisasi data Pegawai!",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: 'btn-danger',
            confirmButtonText: 'Ya!',
            cancelButtonText: "Tidak, Batalkan!",
            closeOnConfirm: false,
            closeOnCancel: false
        },
        function (isConfirm) {
            if (isConfirm) {
                var post_data = "kode="+kode;
                $.ajax({
                        url: "<?php echo base_url() ?>admin/kepegawaian/pegawai/getpegawaibkn",
                        type:"POST",
                        data: post_data,
                        dataType: "html",
                        timeout:180000,
                        success: function (result) {
                             swal("Perhatian!", result, "success");
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
     
    $( "#textqrcode" ).focus(function() {
      $( ".chkqr" ).prop( "checked", true );
      $( "#textqrcode" ).val("");
    });

    $('body').on('click','.popupurl',function () { 
        var url1 =$(this).attr("url1");
        var urls =$(this).attr("urls");
        popitup1(url1,"draftsk");
        popitup2(urls,"Sign SK");
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
    $(".downloadzip").click(function(){
        var xyz = $("#form_search_pegawai").serialize();
        window.open("<?php echo base_url('admin/arsip/sk_ds/downloadskall_ttd');?>?"+xyz);
    });
    $(".downloadxls").click(function(){
        var xyz = $("#form_search_pegawai").serialize();
        window.open("<?php echo base_url('admin/arsip/sk_ds/downloadxls');?>?"+xyz);
    });
    </script>
</div>
