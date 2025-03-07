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
                        <td width="200px"><label for="example-text-input" class="col-form-label">NIK</label></td>
                        <td colspan=2><input class="form-control" type="text" name="nik_key" value="" ></td>
                    </tr>
                    <tr>
                        <td width="200px"><label for="example-text-input" class="col-form-label">Status</label></td>
                        <td colspan=2>
                            <select name="status_ttd" class="form-control">
                                <option value=''>Silahakan Pilih</option>
                                <option value='1'>Gagal</option>
                                <option value='2'>Berhasil</option>
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
                    <th width="10%">Status</th>
                    <th>Keterangan</th>
                    <th width="15%">id file</th>
                    <th>Tanggal</th>
                    <th>User</th>
                </thead>
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
                        {"className": "dt-center", "targets": [2]},
                        { "targets": [0,3], "orderable": false }
                    ],
        ajax: {
          url: "<?php echo base_url() ?>admin/sk/sk_ds/getdatalog",
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
 
     

    </script>
</div>
