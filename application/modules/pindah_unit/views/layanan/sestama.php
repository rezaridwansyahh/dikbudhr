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
   <p>Dibawah ini adalah seluruh data pengajuan perpindahan tempat kerja dari Unit kerja anda</p>
 </div>
<div class="admin-box box box-primary expanded-box">
	 
	<div class="box-body">
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
	processing: true,
	serverSide: true,
	"columnDefs": [
		{"className": "text-center", "targets": [5,6]},
		{ "targets": [0], "orderable": false }
	],
	ajax: {
	  url: "<?php echo base_url() ?>admin/layanan/pindah_unit/getdatasestama",
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