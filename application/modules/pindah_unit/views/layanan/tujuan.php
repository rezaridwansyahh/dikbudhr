<?php
$areaUrl = SITE_AREA . '/layanan/pindah_unit';
$num_columns	= 14;
$can_delete	= $this->auth->has_permission('Pindah_unit.Layanan.Delete');
$can_edit		= $this->auth->has_permission('Pindah_unit.Layanan.Edit');
$has_records	= isset($records) && is_array($records) && count($records);

if ($can_delete) {
    $num_columns++;
}
?>
<div class="callout callout-info">
   <h4>Keterangan</h4>
   <p>Dibawah ini adalah seluruh data pengajuan Perpindahan Tempat Kerja ke dalam Unit Anda</p>
 </div>
<div class="admin-box box box-primary expanded-box">
	<div class="box-header box-tools">
		<a href="<?php echo site_url($areaUrl . '/create'); ?>" class="btn btn-warning pull-right"><i class="fa fa-plus"></i> Buat Pengajuan Pindah</a>
	</div>
	<div class="box-body">
		<form id="form-filter">
			<table class="table table-bordered ">
				<tbody>
					<tr>
						<td width="10%">Keywoard</td>	
						<td>
							<input type="text" id="keyword" name="keyword" class="form-control">
						</td>	
					</tr>
					<tr>
						<td>Status Usulan</td>	
						<td>
							<select name="filter_status" id="filter_status" class="form-control">
	                          <option value="">-- Silahkan Pilih --</option>
	                          <option value="1" <?php echo $pindah_unit->STATUS_BIRO == "1" ? "selected":""; ?> >Diterima</option>
	                          <option value="2" <?php echo $pindah_unit->STATUS_BIRO == "2" ? "selected":""; ?> >Proses</option>
	                          <option value="3" <?php echo $pindah_unit->STATUS_BIRO == "3" ? "selected":""; ?> >Tidak Diterima</option>
	                          <option value="4" <?php echo $pindah_unit->STATUS_BIRO == "4" ? "selected":""; ?> >Berkas Belum Lengkap</option>
	                        </select>

						</td>	
					</tr>
					<tr>
						<td></td>								
						<td><a class='btn btn-warning btn-cari'>Cari</a></td>								
					</tr>
				</tbody>
			</table>
		</form>
	<?php echo form_open($this->uri->uri_string()); ?>
		<table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-data table-hover">
			<thead>
				<tr>
					<th width="10px">No</th>
					<th>Pegawai</th>
					<th>Unit Asal</th>
					<th>Unit Tujuan</th>
					<th> Status Berkas </th>
					<th>STATUS</th>
					<th width="80px">#</th>
				</tr>
				 
			</thead>
			<?php if ($has_records) : ?>
			<tfoot>
				 
			</tfoot>
			<?php endif; ?>
			 
		</table>
	<?php
    echo form_close();
    
    ?>
    </div>
</div>
<script type="text/javascript">
 $(".select2").select2();

$table = $(".table-data").DataTable({
	dom : "<'row'<'col-sm-6'><'col-sm-6'>>" +
	"<'row'<'col-sm-12'tr>>" +
	"<'row'<'col-sm-2'l><'col-sm-3'i><'col-sm-7'p>>",
	ordering: false,
	processing: true,
	serverSide: true,
	"columnDefs": [
		{"className": "text-center", "targets": [5,6]},
		{ "targets": [0], "orderable": false }
	],
	ajax: {
	  url: "<?php echo base_url() ?>admin/layanan/pindah_unit/getdatatujuan",
	  type:'POST',
	  data: function ( d ) {
			d.keyword  = $("#keyword").val();
			d.filter_status  = $("#filter_status").val();
	  }
	}
});
$('body').on('click','.btn-cari',function(event){
	$table.ajax.reload(null,true);
});
$("#form-filter").submit(function(){
	$table.ajax.reload(null,true);
	return false;
});

$('body').on('click','.btn-hapus',function () { 
	var kode =$(this).attr("kode");
	swal({
		title: "Anda Yakin?",
		text: "Delete Pengajuan pindah unit kerja!",
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
					url: "<?php echo base_url() ?>admin/layanan/pindah_unit/deletedata",
					type:"POST",
					data: post_data,
					dataType: "html",
					timeout:180000,
					success: function (result) {
						 swal("Deleted!", result, "success");
						 $table.ajax.reload(null,true);
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