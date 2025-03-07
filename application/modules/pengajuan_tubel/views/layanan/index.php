
<?php
$checkSegment = $this->uri->segment(4);
$areaUrl = SITE_AREA . '/layanan/pengajuan_tubel';
$num_columns	= 6;
$can_delete	= $this->auth->has_permission('Pengajuan_tubel.Layanan.Delete');
$can_edit		= $this->auth->has_permission('Pengajuan_tubel.Layanan.Edit');
$has_records	= isset($records) && is_array($records) && count($records);

if ($can_delete) {
    $num_columns++;
}
?>


<div class="admin-box box box-primary expanded-box">
	<div class="box-body">
		<?php echo form_open($this->uri->uri_string(),"id=form_search_pegawai","form"); ?>
			<style>
				table.filter_pegawai tr td {
					padding-top: 2px;
				}
			</style>
			<table class="filter_pegawai" sborder=0 width='100%' cellpadding="10">
				<tr>
					<td width="200px"><label for="example-text-input" class="col-form-label">NOMOR USUL</label></td>
					<td width="80%"><input class="form-control" type="text" name="NOMOR_USUL" value="" ></td>
					<td>
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
			   <?php if ($this->auth->has_permission('Pengajuan_tubel.Layanan.Create')) : ?>
					<a class='btn btn-warning show-modal' href="<?php echo site_url($areaUrl . '/create'); ?>" tooltip="Ajukan Tugas Belajar"><i class="fa fa-plus"></i> Ajukan Tubel</a>
					
				<?php endif; ?>
				 
			 </div>
 
	</div>
	<div class="box-body">
		<table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-data table-hover">
			<thead>
			<tr>
				<th style="width:10px">No</th>
				<th>Nomor Usul</th>
				<th>Universitas</th>
				<th>Waktu</th>
				<th>Jenjang</th>
				<th width="70px" >Status</th>
				<th width="60px" align="center">#</th></tr>
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
					{"className": "dt-center", "targets": [6]},
					{ "targets": [0,5], "orderable": false }
				],
	ajax: {
	  url: "<?php echo base_url() ?>admin/layanan/pengajuan_tubel/getdata",
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
		text: "Delete data!",
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
					url: "<?php echo base_url() ?>admin/layanan/pengajuan_tubel/deletedata",
					type:"POST",
					data: post_data,
					dataType: "html",
					timeout:180000,
					success: function (result) {
						if(result == "Sukses"){
							swal("Deleted!", result, "success");
							$table.ajax.reload(null,true);	
						}else{
							swal("Peringatan!", result, "error");
						}
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