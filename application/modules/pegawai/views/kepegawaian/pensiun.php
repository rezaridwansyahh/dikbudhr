
<?php
$checkSegment = $this->uri->segment(4);
$areaUrl = SITE_AREA . '/kepegawaian/pegawai';
$num_columns	= 44;
$can_delete	= $this->auth->has_permission('Pegawai.Kepegawaian.Delete');
$can_edit		= $this->auth->has_permission('Pegawai.Kepegawaian.Edit');
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
		<?php echo form_open($this->uri->uri_string(),"id=form_search_pegawai","form"); ?>
			<style>
				table.filter_pegawai tr td {
					padding-top: 2px;
				}
			</style>
			<table class="filter_pegawai" sborder=0 width='100%' cellpadding="10">
				<tr>
					<td width="20px"><input type="checkbox" name="unit_id_cb"></td>
					<td width="200px"><label for="example-text-input" class="col-form-label">Satuan Kerja</label></td>
					<td colspan=2><select id="unit_id_key" name="unit_id_key" width="100%" class=" col-md-10 format-control"></select></td>
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
					<td width="20px"><input type="checkbox" name="umur_cb"></td>
					<td width="200px"><label for="example-text-input" class="col-form-label">UMUR</label></td>
					<td style="padding-right:10px;" width="200px" >
						<select class="form-control" name="umur_operator">
							<option value="=">Sama dengan</option>
							<option value=">">Lebih dari</option>
							<option value=">=">Lebih dari atau sama dengan</option>
							<option value="<">Kurang dari</option>
							<option value="<=">Kurang dari atau sama dengan</option>
							<option value="!=">Tidak sama dengan</option>
							<option value="in">Diantara</option>
						</select>
					</td>
					<td ><input class="form-control" type="text" name="umur_key" value="" ></td>
				</tr>
				<tr class="hide">
					<td width="20px"><input type="checkbox" name="eselon_cb"></td>
					<td width="200px"><label for="example-text-input" class="col-form-label">Eselon</label></td>
					<td colspan=2>
						<select class="form-control" name="eselon_key">
							<option value="1">I</option>
							<option value="2">II</option>
							<option value="3">III</option>
							<option value="4">IV</option>
							<option value="5">V</option>
						</select>
					</td>
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
					<td width="20px"><input type="checkbox" name="tkp_cb"></td>
					<td width="200px"><label for="example-text-input" class="col-form-label">Tingkat Pendidikan</label></td>
					<td colspan=2>
						<select name="tingkat_pendidikan" class="form-control">
							<?php 
								foreach($tkpendidikans as $row){
									echo "<option value='".$row->ID."'>$row->NAMA</option>";
								}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<td width="20px"><input type="checkbox" name="kategori_cb"></td>
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
				&nbsp;<button class='btn btn-primary download_xls' target="_blank"><i class="fa fa-download" aria-hidden="true"></i> Download .xls</button>
			 </div>
 
	</div>
	<div class="box-body">
		<table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-data table-hover">
			<thead>
			<tr>
				<th style="width:10px">No</th>
				<th>NIP</th>
				<th>Nama Pegawai</th>
				<th>Golongan</th>
				<th>Unit Kerja</th>
				<th width="70px" align="center">#</th></tr>
			</thead>
		</table>
	</div>
</div>

<script type="text/javascript">

$(".download_xls").click(function(){
	var xyz = $("#form_search_pegawai").serialize();
	window.open("<?php echo base_url('admin/kepegawaian/pegawai/downloadpensiun');?>?"+xyz);
});
$(".download_nominatif").click(function(){
	var xyz = $("#form_search_pegawai").serialize();
	window.open("<?php echo base_url('admin/kepegawaian/pegawai/downloadnominatifpensiun');?>?"+xyz);
});
$(".download_foto").click(function(){
	var xyz = $("#form_search_pegawai").serialize();
	window.open("<?php echo base_url('admin/kepegawaian/pegawai/downloadphotopensiun');?>?"+xyz);
});

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
	
	dom : "<'row'<'col-sm-6'><'col-sm-6'>>" +
	"<'row'<'col-sm-12'tr>>" +
	"<'row'<'col-sm-2'l><'col-sm-3'i><'col-sm-7'p>>",
	processing: true,
	serverSide: true,
	"columnDefs": [
					{"className": "dt-center", "targets": [4]},
					{ "targets": [0,5], "orderable": false }
				],
	ajax: {
	  url: "<?php echo base_url() ?>admin/kepegawaian/pegawai/getpensiundata",
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

$('body').on('click','.generatedatabkn',function () { 
	var kode =$(this).attr("kode");
	swal({
		title: "Anda Yakin?",
		text: "Sinkronisasi data Pegawai!",
		type: "warning",
		showCancelButton: true,
		confirmButtonClass: 'btn-danger',
		confirmButtonText: 'Ya!',
		cancelButtonText: "Tidak, Batalkan!",
		closeOnConfirm: false,
		closeOnCancel: false
	},
	function (isConfirm) {
		if (isConfirm) {
			var post_data = "kode="+kode;
			$.ajax({
					url: "<?php echo base_url() ?>admin/kepegawaian/pegawai/getpegawaibkn",
					type:"POST",
					data: post_data,
					dataType: "html",
					timeout:180000,
					success: function (result) {
						 swal("Perhatian!", result, "success");
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
$('body').on('click','.btn-hapus',function () { 
	var kode =$(this).attr("kode");
	swal({
		title: "Anda Yakin?",
		text: "Delete data Pegawai!",
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
					url: "<?php echo base_url() ?>admin/kepegawaian/pegawai/deletedata",
					type:"POST",
					data: post_data,
					dataType: "html",
					timeout:180000,
					success: function (result) {
						 swal("Deleted!", result, "success");
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