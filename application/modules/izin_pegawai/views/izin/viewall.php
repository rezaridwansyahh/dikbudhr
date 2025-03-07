
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
		<?php echo form_open($this->uri->uri_string(),"id=form_search_pegawai","form"); ?>
			<style>
				table.filter_pegawai tr td {
					padding-top: 2px;
				}
			</style>
			<table class="filter_pegawai" sborder=0 width='100%' cellpadding="10">
				 
				<tr>
					<td width="150px"><label for="example-text-input" class="col-form-label">NAMA</label></td>
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
					<td><label for="example-text-input" class="col-form-label">Kirim eKehadiran</label></td>
					<td colspan=2>
						<select name="status_kirim" class="form-control">
                            <option value="">Silahkan Pilih</option>
                            <option value="1">Sudah kirim</option>
                            <option value="-">Blm Kirim</option>
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
		 
			<div class="btn-group  pull-right">
              
              <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                <span class="caret"></span>
                <span class="sr-only">Toggle Dropdown</span>
              </button>
              
              	<button type="button" class="btn btn-warning pull-right"><a href="<?php echo site_url($areaUrl . '/createselect/'); ?>">Ajukan Baru</a></button>
              <ul class="dropdown-menu" role="menu">
              	<?php
              	if(isset($jenis_izin) && is_array($jenis_izin) && count($jenis_izin)):
            		foreach ($jenis_izin as $record) {
            	?>
            		<li><a href="<?php echo site_url($areaUrl . '/create/'); ?>/<?php echo $record->ID; ?>"><?php echo $record->NAMA_IZIN; ?></a></li>
            	<?php 
            		}
            	endif;
            	?>
              </ul>
            </div>
            <a title='Upload Data Cuti' style="margin-right:10px;" class="btn-upload-data-bkn btn btn-small btn-success show-modal pull-right" href="<?php echo base_url(); ?>admin/izin/izin_pegawai/upload_data_cuti" tooltip="Upload Data Cuti"><i class="fa fa-upload"></i> Upload data Cuti</a> 	
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
					{ "targets": [0,5], "orderable": false }
				],
	ajax: {
	  url: "<?php echo base_url() ?>admin/izin/izin_pegawai/getdata_izin_all",
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
					dataType: "json",
					timeout:180000,
					success: function (result) {
						 if(result.success){
							swal("Deleted!", "Berhasil hapus", "success");
						 	$table.ajax.reload(null,true);	
						}else{
							swal("Perhatian!", result.msg, "error");
						 	$table.ajax.reload(null,true);	
						}
				},
				error : function(error) {
					alert(error);
				} 
			});        
			
		} else {
			swal("Batal", "Aksi dibatalkan", "error");
		}
	});
});
$('body').on('click','.send-kehadiran',function () { 
	var kode =$(this).attr("kode");
	swal({
		title: "Anda Yakin?",
		text: "Kirim data ke e-kehadiran!",
		type: "warning",
		showCancelButton: true,
		confirmButtonClass: 'btn-success',
		confirmButtonText: 'Ya, Kirimkan!',
		cancelButtonText: "Tidak, Batalkan!",
		closeOnConfirm: false,
		showLoaderOnConfirm: true,
		closeOnCancel: false
	},
	function (isConfirm) {
		if (isConfirm) {
			var post_data = "kode="+kode;
			$.ajax({
					url: "<?php echo base_url() ?>admin/izin/izin_pegawai/resendekehadiran",
					type:"POST",
					data: post_data,
					dataType: "json",
					timeout:180000,
					success: function (result) {
						 if(result.success){
							swal("Success!", result.msg, "success");
						 	$table.ajax.reload(null,true);	
						}else{
							swal("Perhatian!", result.msg, "error");
						 	$table.ajax.reload(null,true);	
						}
				},
				error : function(error) {
					swal("Error", error, "error");
				} 
			});        
			
		} else {
			swal("Batal", "Aksi dibatalkan", "error");
		}
	});
});

</script>