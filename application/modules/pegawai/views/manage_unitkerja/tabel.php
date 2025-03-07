
<?php
$checkSegment = $this->uri->segment(4);
$areaUrl = base_url() . 'pegawai/manage_unitkerja/';
$num_columns    = 44;
$can_delete = $this->auth->has_permission('Petajabatan.Masters.Delete');
$can_edit       = $this->auth->has_permission('Petajabatan.Masters.Edit');
$has_records    = isset($records) && is_array($records) && count($records);

if ($can_delete) {
    $num_columns++;
}
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
                    <td width="200px"><label for="example-text-input" class="col-form-label">Waktu</label></td>
                    <td colspan=2>
                        <select id="waktu" name="waktu" width="100%" class=" col-md-10 form-control">
                            <option value="">Silahkan Pilih</option>
                            <option value="WIB">WIB</option>
                            <option value="WITA">WITA</option>
                            <option value="WIT">WIT</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td><label for="example-text-input" class="col-form-label">Jenis Satker</label></td>
                    <td colspan=2>
                        <select id="jenis" name="jenis" style="width:100%" class="form-control select2">
                        <option value="">Silahkan Pilih</option>
                            <?php 
                            foreach($jenis_satkers as $row){
                            ?>
                                <option <?php echo (isset($data->JENIS_SATKER) and trim($row->nama_jenis_satker) == trim($data->JENIS_SATKER)) ? "selected" : ""; ?> value="<?php echo trim($row->nama_jenis_satker); ?>"><?php echo $row->nama_jenis_satker; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td><label for="example-text-input" class="col-form-label">Status</label></td>
                    <td colspan=2>
                        <select id="status_active" name="status_active" style="width:100%" class="form-control select2">
                        <option value="">Silahkan Pilih</option>
                           <option value="1">Aktif</option>
                           <option value="2">Non Aktif</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td><label for="example-text-input" class="col-form-label">Nama Unit</label></td>
                    <td colspan=2>
                        <input type="text" name="nama_unit" class="form-control">
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
            <div class="btn-group pull-right">
               <?php if ($this->auth->has_permission('Petajabatan.Masters.Create')) : ?>
                    <a class='btn btn-warning show-modal' href="<?php echo $areaUrl . 'createNew'; ?>"><i class="fa fa-plus"></i>Tambah Data</a>
                <?php endif; ?>
             </div>
 
    </div>
    <div class="box-body">
        <table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-data table-hover">
            <thead>
            <tr>
                <th style="width:10px">No</th>
                <th>Nama Unitkerja</th>
                <th>Waktu</th>
                <th>Pimpinan</th>
                <th>Jenis Satker</th>
                <th>Experied date</th>
                <th width="70px" align="center">#</th></tr>
            </thead>
        </table>
    </div>
</div>

<script type="text/javascript">
 
$table = $(".table-data").DataTable({
    
    dom : "<'row'<'col-sm-6'><'col-sm-6'>>" +
    "<'row'<'col-sm-12'tr>>" +
    "<'row'<'col-sm-2'l><'col-sm-3'i><'col-sm-7'p>>",
    processing: true,
    serverSide: true,
    "columnDefs": [
                    {"className": "text-center", "targets": [6]},
                    { "targets": [0,6], "orderable": false }
                ],
    ajax: {
      url: "<?php echo base_url() ?>pegawai/manage_unitkerja/getdata",
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