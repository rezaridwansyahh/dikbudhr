
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
<div class='alert alert-block alert-info fade in'>
	<h5 class='alert-heading'>
		Silahkan verifikasi usulan izin dari bawahan anda
		dengan cara klik pada kolom action (#) -> Klik icon edit (pensil), silahkan tentukan status persetujuan dan klik tombol simpan dan kirim<br>
	</h5>
	
</div>
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
					<td width="100px"><label for="example-text-input" class="col-form-label">NAMA</label></td>
					<td colspan=2><input class="form-control" type="text" name="nama_key" value="" ></td>
				</tr>
				<tr>
					<td width="100px"><label for="example-text-input" class="col-form-label">NIP</label></td>
					<td colspan=2><input class="form-control" type="text" name="nip_key" value="" ></td>
				</tr>
				<tr>
					<td><label for="example-text-input" class="col-form-label">Status</label></td>
					<td colspan=2>
						<select name="STATUS" id="STATUS" class="form-control">
                            <option value="">Silahkan Pilih</option>
                            <?php
                            foreach($data_status_izin as $row){
							?>
								<option value="<?php echo $row['id']; ?>"><?php echo $row['value']; ?></option>
							<?php
							}
                            ?> 
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
<div class="admin-box box ">
	<div class="box-body">
		  
		<table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-data table-hover">
			<thead>
			<tr>
				<th style="width:10px">No</th>
				<th width="50px">FOTO</th>
				<th width="20%">PEGAWAI</th>
				<th>IZIN</th>
				<th>TANGGAL</th>
				<th>KETERANGAN</th>
				<th>STATUS</th>
				<th width="100px" align="center">#</th></tr>
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
					{"className": "text-center", "targets": [0,6,7]},
					{ "targets": [0,1,5,7], "orderable": false }
				],
	ajax: {
	  url: "<?php echo base_url() ?>admin/izin/izin_pegawai/getdata_verifikasi_izin",
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