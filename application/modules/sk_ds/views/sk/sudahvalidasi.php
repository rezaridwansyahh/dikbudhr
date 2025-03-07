<div class="tab-pane" id="<?php echo $TAB_ID;?>">
<?php
$checkSegment = $this->uri->segment(4);
$areaUrl = SITE_AREA . '/sk/sk_ds';
$num_columns	= 44;
$can_delete	= $this->auth->has_permission('Sk_ds.Sk.Delete');
$can_edit		= $this->auth->has_permission('Sk_ds.Sk.Edit');
$has_records	= isset($records) && is_array($records) && count($records);

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
		<?php echo form_open($this->uri->uri_string(),"id=form_search_pegawai_sudah","form"); ?>
			<style>
				table.filter_pegawai tr td {
					padding-top: 2px;
				}
			</style>
			<table class="filter_pegawai" sborder=0 width='100%' cellpadding="10">
				<tr>
					<td width="20px"><input type="checkbox" name="unit_id_cb"></td>
					<td width="200px"><label for="example-text-input" class="col-form-label">Satuan Kerja</label></td>
					<td colspan=2><select id="unit_id_key_sudah" name="unit_id_key" width="100%" class=" col-md-10 format-control"></select></td>
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
					<td width="20px"><input type="checkbox" name="kategori_jb"></td>
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
                            <select name="kategori_sk" id="kategori_skblm" class="form-control">
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
                            <a href="<?php echo base_url(); ?>admin/sk/sk_ds/scanqr/?text=textqrcodesdh&tabel=tablesudah" data-toggle="tooltip" title="" id="linkqrsudah" class="btn btn-sm btn-info show-modal" data-original-title="Scan QR"><i class="glyphicon glyphicon-eye-open"></i> Scan Qrcode </a>
                            <input type="text" id="textqrcodesdh" name="textqrcode" class="form-control">
                            
                        </select>
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
		<table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-hover table-datasudah">
			<thead>
			<tr>
				<th style="width:10px">No</th>
				<th>Pemilik SK</th>
				<th>Kategori SK</th>
				<th>No SK / Tgl SK</th>
				<th>Unit Kerja</th>
				<th width="70px" align="center">#</th></tr>
			</thead>
		</table>
	</div>
</div>

<script type="text/javascript">
 
$("#unit_id_key_sudah").select2({
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
$tablesudah = $(".table-datasudah").DataTable({
	
	dom : "<'row'<'col-sm-6'><'col-sm-6'>>" +
	"<'row'<'col-sm-12'tr>>" +
	"<'row'<'col-sm-2'l><'col-sm-3'i><'col-sm-7'p>>",
	processing: true,
	serverSide: true,
	stateSave: true,
	"columnDefs": [
					{"className": "dt-center", "targets": [4]},
					{ "targets": [0,5], "orderable": false }
				],
	ajax: {
	  url: "<?php echo base_url() ?>admin/sk/sk_ds/getdata_sudah_validasi",
	  type:'POST',
	  "data": function ( d ) {
			d.search['advanced_search_filters']=  $("#form_search_pegawai_sudah").serializeArray();
		}
	}
});
$("#form_search_pegawai_sudah").submit(function(){
	$tablesudah.ajax.reload(null,true);
	return false;
});
$("#linkqrsudah").click(function(){
        $( ".chkqr" ).prop( "checked", true );
    });

$( "#textqrcodesdh" ).focus(function() {
  $( ".chkqr" ).prop( "checked", true );
  $( "#textqrcodesdh" ).val("");
});
</script>
</div>