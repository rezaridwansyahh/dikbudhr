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
                        <td width="200px"><label for="example-text-input" class="col-form-label">Bulan</label></td>
                        <td colspan=2>
                            <select name="bulan" id="bulan" class="form-control">
                                <option value="">-- Silahkan Pilih --</option>
                                <option value="01" <?php echo $bulan == "01" ? "selected" : ""; ?>>Januari</option>
                                <option value="02" <?php echo $bulan == "02" ? "selected" : ""; ?>>Februari</option>
                                <option value="03" <?php echo $bulan == "03" ? "selected" : ""; ?>>Maret</option>
                                <option value="04" <?php echo $bulan == "04" ? "selected" : ""; ?>>April</option>
                                <option value="05" <?php echo $bulan == "05" ? "selected" : ""; ?>>Mei</option>
                                <option value="06" <?php echo $bulan == "06" ? "selected" : ""; ?>>Juni</option>
                                <option value="07" <?php echo $bulan == "07" ? "selected" : ""; ?>>Juli</option>
                                <option value="08" <?php echo $bulan == "08" ? "selected" : ""; ?>>Agustus</option>
                                <option value="09" <?php echo $bulan == "09" ? "selected" : ""; ?>>September</option>
                                <option value="10" <?php echo $bulan == "10" ? "selected" : ""; ?>>Oktober</option>
                                <option value="11" <?php echo $bulan == "11" ? "selected" : ""; ?>>November</option>
                                <option value="12" <?php echo $bulan == "12" ? "selected" : ""; ?>>Desember</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td width="200px"><label for="example-text-input" class="col-form-label">Tahun</label></td>
                        <td colspan=2>
                            <input type="text" class="form-control" name="tahun" value="<?php echo $tahun; ?>">
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
                    <th style="width:10px" rowspan="2">No</th>
                    <th width="30%" rowspan="2">Jenis Dokumen</th>
                    <th rowspan="2">Jumlah</th>
                    <th colspan="5">Proses Dokumen</th>
                </tr>
                <tr>
                    <th>Koreksi</th>
                    <th>Verifikasi</th>
                    <th>Siap TTE</th>
                    <th>Sudah TTE</th>
                    <th>Kirim</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
                
            </table>
        </div>
     
<script type="text/javascript">
    $table = $(".table-data").DataTable({
        
        dom : "<'row'<'col-sm-6'><'col-sm-6'>>" +
        "<'row'<'col-sm-12'tr>>" +
        "<'row'<'col-sm-2'l><'col-sm-3'i><'col-sm-7'p>>",
        processing: true,
        serverSide: true,
        "columnDefs": [
                        {"className": "text-center", "targets": [0,2,3,4,5,6,7]},
                        { "targets": [0,5], "orderable": false }
                    ],
        ajax: {
          url: "<?php echo base_url() ?>admin/arsip/sk_ds/getresumekategori",
          type:'POST',
          "data": function ( d ) {
                d.search['advanced_search_filters'] = $("#form_search_pegawai").serializeArray();
            }
        },
        
    });
    $("#form_search_pegawai").submit(function(){
        $table.ajax.reload(null,true);
        return false;
    });
    function formatNumber(num) {
      return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
    }
</script>
</div>
