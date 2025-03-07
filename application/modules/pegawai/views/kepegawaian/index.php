
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

<div class="modal fade" id="modal-pensiun" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div id="overlay">
			<div class="cv-spinner">
				<span class="spinner"></span>
			</div>
		</div>
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span></button>
				<h4 class="modal-title">Ubah status pegawai menjadi pensiun/meninggal/berhenti</h4>
			</div>
			<div class="modal-body">
				<div class='box box-warning' id="form-riwayat-assesmen-add">
					<div class="box-body">
						<form id="pensiunForm">
							<div class="form-group">
								<label>NIP</label>
								<input type="text" class="form-control" name="nip" id="nip_pensiun">
							</div>
							<div class="form-group">
								<label>TMT PENSIUN</label>
								<input type="date" class="form-control" name="tmt_pensiun" id="tmt_pensiun">
							</div>
							<div class="form-group">
								<label>Status</label>
								<select class="form-control" name="status_pensiun" id="status_pensiun">
									<option value="99">Pensiun</option>
									<option value="99x">Meninggal</option>
									<option value="100">Pindah Instansi</option>
									<option value="77">Pemberhentian tanpa hak pensiun</option>
									<option value="88">Pemberhentian dengan hak pensiun</option>
									<option value="66">Diberhentikan</option>
								</select>
							</div>
						</form>
					</div>

					<input type='button' name='save' id="btnpensiun" class='btn btn-primary' value="Ubah Data" />

				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
				
			</div>
		</div>

	</div>

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
					<td width="20px"><input type="checkbox" name="kedudukan_hukum_cb"></td>
					<td width="200px"><label for="example-text-input" class="col-form-label">Status</label></td>
					<td colspan=2>
						<select name="kedudukan_hukum" id="kedudukan_hukum" class="form-control select2">
	                        <option value="">-- Silahkan Pilih --</option>
	                        <?php if (isset($KEDUDUKAN_HUKUMs) && is_array($KEDUDUKAN_HUKUMs) && count($KEDUDUKAN_HUKUMs)):?>
							<?php foreach($KEDUDUKAN_HUKUMs as $record):?>
								<option value="<?php echo $record->ID?>" <?php if(isset($pegawai->KEDUDUKAN_HUKUM_ID))  echo  ($pegawai->KEDUDUKAN_HUKUM_ID==$record->ID) ? "selected" : ""; ?>><?php echo $record->NAMA; ?></option>
								<?php endforeach;?>
							<?php endif;?>
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
			   <?php if ($this->auth->has_permission('Pegawai.Kepegawaian.Create')) : ?>
					<a class='btn btn-warning' href="<?php echo site_url($areaUrl . '/create'); ?>"><i class="fa fa-plus"></i>Tambah Pegawai</a>
				<?php endif; ?>
				<?php if ($this->auth->has_permission('Pegawai.Downloadxls.View')) : ?>
				&nbsp;<button class='btn btn-primary download_xls' target="_blank"><i class="fa fa-download" aria-hidden="true"></i> Download .xls</button>
				<?php endif; ?>
				<?php if ($this->auth->has_permission('Pegawai.DownloadNominatif.View')) : ?>
				&nbsp;<button class='btn btn-success download_nominatif' target="_blank"><i class="fa fa-download" aria-hidden="true"></i> Download Nominatif</button>
				<?php endif; ?>
				<?php if ($this->auth->has_permission('Pegawai.DownloadFoto.View')) : ?>
				&nbsp;<button class='btn btn-primary download_foto' target="_blank"><i class="fa fa-download" aria-hidden="true"></i> Download Foto</button>
				<?php endif; ?>
				<?php if ($this->auth->has_permission('Pegawai.Importxls.View')) : ?>
				&nbsp;<a class='btn btn-default show-modal' href="<?php echo site_url($areaUrl . '/importdatapegawai'); ?>" target="_blank" tooltip="Import data pegawai"><i class="fa fa-upload" aria-hidden="true"></i>Upload data</a>
				<?php endif; ?>
				<?php if ($this->auth->has_permission('Pegawai.Importhcdp.View')) : ?>
				&nbsp;<a class='btn btn-warning show-modal' href="<?php echo site_url($areaUrl . '/importhcdp'); ?>" target="_blank" tooltip="Import data Assesment" ><i class="fa fa-upload" aria-hidden="true"></i> Import hcdp</a>
				<?php endif; ?>
				<?php if ($this->auth->has_permission('Pegawai.ImportBkn.View')) : ?>
				&nbsp;<a class='btn btn-success show-modal' href="<?php echo site_url($areaUrl . '/importdatabkn'); ?>" target="_blank" tooltip="Import data dari BKN" ><i class="fa fa-upload" aria-hidden="true"></i> Import Data BKN</a>
				<?php endif; ?>
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
	window.open("<?php echo base_url('admin/kepegawaian/pegawai/download');?>?"+xyz);
});
$(".download_nominatif").click(function(){
	var xyz = $("#form_search_pegawai").serialize();
	window.open("<?php echo base_url('admin/kepegawaian/pegawai/downloadnominatif');?>?"+xyz);
});
$(".download_foto").click(function(){
	var xyz = $("#form_search_pegawai").serialize();
	window.open("<?php echo base_url('admin/kepegawaian/pegawai/downloadphoto');?>?"+xyz);
});

$("#unit_id_key").select2({
	placeholder: 'Cari Unit Kerja...',
	width: '100%',
	minimumInputLength: 5,
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
	  url: "<?php echo base_url() ?>admin/kepegawaian/pegawai/getdata",
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
		text: "Pastikan data anda sudah update di SIASN BKN, Batalkan jika belum update!",
		type: "warning",
		showCancelButton: true,
		confirmButtonClass: 'btn-danger',
		confirmButtonText: 'Ya!',
		cancelButtonText: "Tidak, Batalkan!",
		closeOnConfirm: false,
		showLoaderOnConfirm: true,
		closeOnCancel: false
	},
	function (isConfirm) {
		if (isConfirm) {
			var post_data = "nip_bkn="+kode;
			$.ajax({
					url: "<?php echo base_url() ?>pegawai/bkn/getpegawaibknnew",
					type:"POST",
					data: post_data,
					dataType: "json",
					timeout:180000,
					success: function (result) {
						if(result.success)
						 	swal("Perhatian!", result.msg, "success");
						else
							swal("Perhatian!", result.msg, "error");
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
$('body').on('click','.viewdatabkn',function () { 
	var kode =$(this).attr("kode");
	swal({
		title: "Anda Yakin?",
		text: "Sinkron data pribadi dan riwayat berdasarkan data BKN!",
		type: "warning",
		showCancelButton: true,
		confirmButtonClass: 'btn-success',
		confirmButtonText: 'Ya!',
		cancelButtonText: "Tidak, Batalkan!",
		closeOnConfirm: false,
		showLoaderOnConfirm: true,
		closeOnCancel: false
	},
	function (isConfirm) {
		if (isConfirm) {
			var post_data = "kode="+kode;
			$.ajax({
					url: "<?php echo base_url() ?>pegawai/bkn/viewpegawaibkn",
					type:"POST",
					data: post_data,
					dataType: "json",
					timeout:180000,
					success: function (result) {
						if(result.success){
							swal({
	                            title: "Selamat!",
	                            text: result.msg,
	                            type: "success",
	                            timer: 4000,
	                            showConfirmButton: true
	                        }, function () {
	                            url = "<?php echo base_url(); ?>pegawai/bkn/profile_bkn/"+result.id;
	                            $(location).attr("href", url);
	                        });
						}else{
							swal("Perhatian", result.msg, "error");
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
$('body').on('click','.generate_user',function () { 
	var kode =$(this).attr("kode");
	swal({
		title: "Anda Yakin?",
		text: "Buat akun Pegawai!",
		type: "warning",
		showCancelButton: true,
		confirmButtonClass: 'btn-success',
		confirmButtonText: 'Ya!',
		cancelButtonText: "Tidak, Batalkan!",
		closeOnConfirm: false,
		closeOnCancel: false
	},
	function (isConfirm) {
		if (isConfirm) {
			var post_data = "kode="+kode;
			$.ajax({
					url: "<?php echo base_url() ?>admin/kepegawaian/pegawai/createakun",
					type:"POST",
					data: post_data,
					dataType: "json",
					timeout:180000,
					success: function (result) {
						if(result.success){	
						 	swal("Perhatian!", result.msg, "success");
						}else{
							swal("Perhatian!", result.msg, "error");
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
$('body').on('click','.reset_password_user',function () { 
	var kode =$(this).attr("kode");
	swal({
        title: "Reset Password",
        text: "Silahkan masukan password baru dengan ketentuan minimal 8 karakter, terdiri dari huruf kecil, huruf  besar, simbol dan angka. contoh: p4sword.Login",
        inputType: "password",
        type: "input",
        showCancelButton: true,
        closeOnConfirm: false,
        animation: "slide-from-top",
        inputPlaceholder: "Password baru....."
      },
      function(inputValue){
        if (inputValue === false) return false;

        if (inputValue === "") {
          swal.showInputError("Silahkan masukan Pasphrase!");
          return false
        }
        swal({
			title: "Anda Yakin?",
			text: "Reset Password!",
			type: "warning",
			showCancelButton: true,
			confirmButtonClass: 'btn-success',
			confirmButtonText: 'Ya!',
			cancelButtonText: "Tidak, Batalkan!",
			closeOnConfirm: false,
			closeOnCancel: false
		},
		function (isConfirm) {
			if (isConfirm) {
				var post_data = "kode="+kode+"&newpassword="+inputValue;
				$.ajax({
						url: "<?php echo base_url() ?>admin/kepegawaian/pegawai/reset_password",
						type:"POST",
						data: post_data,
						dataType: "json",
						timeout:180000,
						success: function (result) {
							if(result.success){	
							 	swal("Perhatian!", result.msg, "success");
							}else{
								swal("Perhatian!", result.msg, "error");
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
	
});

$('body').on('click','.setpensiunmeninggal',function () { 
	var kode =$(this).attr("kode");

	$("#modal-pensiun").modal('show');

	$("#nip_pensiun").val(kode)	
	
});

$("#btnpensiun").click(function(){
	updateStatusPegawai();
})

function updateStatusPegawai(){
	let pensiun = $("#pensiunForm").serializeArray();
	let error_message = "";
	pensiun.forEach((item) => {
		if(item.value==""){
			error_message = error_message + item.name +" harus diisi ";
		}
	})

	if(error_message==""){
		let base_url = "<?php echo base_url();?>";
		let json_url = base_url + "admin/kepegawaian/pegawai/updatepensiun";
		let nip = $("#nip").val();
		$.ajax({
			type: "POST",
			url: json_url,
			data: $("#pensiunForm").serialize(),
			dataType: "json",
			success: function (data) {
				console.log(data);
				swal("Success Update Data");
			}
		});

	}else{
		alert(error_message)
	}
	
}

$('body').on('click','.viewdatamutasi',function () { 
	var kode =$(this).attr("kode");
	swal({
		title: "Anda Yakin?",
		text: "Lihat data Pegawai berdasarkan data Aplikasi mutasi!",
		type: "warning",
		showCancelButton: true,
		confirmButtonClass: 'btn-success',
		confirmButtonText: 'Ya!',
		cancelButtonText: "Tidak, Batalkan!",
		closeOnConfirm: false,
		showLoaderOnConfirm: true,
		closeOnCancel: false
	},
	function (isConfirm) {
		if (isConfirm) {
			var post_data = "nip="+kode;
			$.ajax({
					url: "<?php echo base_url() ?>pegawai/sinkronisasi/viewpersonal",
					type:"POST",
					data: post_data,
					dataType: "json",
					success: function (result) {
						if(result.success){
							swal({
	                            title: "Sukses!",
	                            text: result.msg,
	                            type: "success",
	                            timer: 4000,
	                            showConfirmButton: true
	                        }, function () {
	                        	$("#modal-body").html(result.konten);
	  							$("#myModalLabel").html("Data Mutasi");
	                            $("#modal-global").modal('show');
	                        });
						}else{
							swal("Perhatian", result.msg, "error");
						}
						
				},
				error : function(error) {
					swal("Perhatian", error, "error");
				} 
			}); 
		} else {
			swal("Batal", "", "error");
		}
	});



});
</script>
