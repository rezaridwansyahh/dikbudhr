<div class="tab-pane" id="<?php echo $TAB_ID;?>">
<?php
$checkSegment = $this->uri->segment(4);
$areaUrl = SITE_AREA . '/izin/izin_pegawai';
$num_columns	= 44;
$can_delete	= $this->auth->has_permission('Izin_pegawai.Izin.Delete');
$can_edit		= $this->auth->has_permission('Izin_pegawai.Izin.Edit');

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
		<?php echo form_open($this->uri->uri_string(),"id=form_search_pegawai_lain","form"); ?>
			<style>
				table.filter_pegawai tr td {
					padding-top: 2px;
				}
			</style>
			<table class="filter_pegawai" sborder=0 width='100%' cellpadding="10">
				 <tr>
					<td width="100px"><label for="example-text-input" class="col-form-label">Pegawai</label></td>
					<td colspan=2>
						<input type="text" class="form-control" name="nama_key">
					</td>
				</tr>
				<tr>
					<td width="200px"><label for="example-text-input" class="col-form-label">Jenis Pengajuan</label></td>
					<td colspan=2>
						<select name="jenis_izin" class="form-control">
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
					</td>
				</tr>
				<tr>
					<td><label for="example-text-input" class="col-form-label">Tanggal</label></td>
					<td>
						 <div class="input-group date">
	                          <div class="input-group-addon">
	                            <i class="fa fa-calendar"></i>
	                          </div>
	                            <input type='text' readonly class="form-control pull-right datepicker" name='dari_tanggal' value="<?php echo set_value('dari_tanggal', isset($izin_pegawai->DARI_TANGGAL) ? $izin_pegawai->DARI_TANGGAL : ''); ?>" />
	                            <span class='help-inline'><?php echo form_error('dari_tanggal'); ?></span>
	                      </div>
					</td>
					<td>
						 <div class="input-group date">
	                          <div class="input-group-addon">
	                            <i class="fa fa-calendar"></i>
	                          </div>
	                            <input type='text' readonly class="form-control pull-right datepicker" name='sampai_tanggal' value="<?php echo set_value('sampai_tanggal', isset($izin_pegawai->DARI_TANGGAL) ? $izin_pegawai->DARI_TANGGAL : ''); ?>" />
	                            <span class='help-inline'><?php echo form_error('sampai_tanggal'); ?></span>
	                      </div>
					</td>
				</tr>
				<tr>
					<td><label for="example-text-input" class="col-form-label">No Surat</label></td>
					<td colspan=2>
						<input type="text" class="form-control" name="no_surat">
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
	<div class="box-body">
		 
		<table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-data-lainnya table-hover">
            <thead>
            <tr>
                <th style="width:10px">No</th>
                <th>Pegawai</th>
                <th>JENIS PENGAJUAN</th>
                <th>TANGGAL PENGAJUAN</th>
                <th>TANGGAL PELAKSANAAN</th>
                <th>ALASAN/<BR>KETERANGAN</th>
                <th>STATUS</th>
                <th width="100px" align="center">#</th></tr>
            </thead>
        </table>	
	</div>
</div>

<script type="text/javascript">

$table = $(".table-data-lainnya").DataTable({
    
    dom : "<'row'<'col-sm-6'><'col-sm-6'>>" +
    "<'row'<'col-sm-12'tr>>" +
    "<'row'<'col-sm-2'l><'col-sm-3'i><'col-sm-7'p>>",
    processing: true,
    serverSide: true,
    "columnDefs": [
                    {"className": "text-center", "targets": [0,5,7]},
                    { "targets": [0,4], "orderable": false }
                ],
    ajax: {
      url: "<?php echo base_url() ?>admin/izin/izin_pegawai/getdata_izin_pegawai",
      type:'POST',
      "data": function ( d ) {
            d.search['advanced_search_filters']=  $("#form_search_pegawai_lain").serializeArray();
        }
    }
});
$("#form_search_pegawai_lain").submit(function(){
	$table.ajax.reload(null,true);
	return false;
});

</script>
</div>