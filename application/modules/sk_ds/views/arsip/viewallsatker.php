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
                    <th width="15%">Pegawai</th>
                    <th width="10%">Kategori SK</th>
                    <th>No SK / Tgl SK</th>
                    <th>Unit Kerja</th>
                    <th>TTD</th>
                    <th width="50px" align="center">#</th></tr>
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
                        {"className": "dt-center", "targets": [4]},
                        { "targets": [0,5], "orderable": false }
                    ],
        ajax: {
          url: "<?php echo base_url() ?>admin/arsip/sk_ds/getdataall_satker",
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
    </script>
</div>
