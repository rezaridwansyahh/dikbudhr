
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
<style type="text/css">
	.checked{
		background-color: yellow;
	}
</style>

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
					<td width="20px"><input type="checkbox" name="agama_cb"></td>
					<td width="200px"><label for="example-text-input" class="col-form-label">AGAMA</label></td>
					<td colspan=2>
						<select name="agama_key" class="form-control">
							<option value=''>Pilih Agama</option>
							<?php 
								foreach($agamas as $row){
									echo "<option value='".$row->ID."'>$row->NAMA</option>";
								}
							?>
						</select>
					</td>
				</tr>
				<tr>
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
					<td><input type="checkbox" name="umur_cb"></td>
					<td><label for="example-text-input" class="col-form-label">UMUR</label></td>
					<td style="padding-right:10px;" >
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
				<tr>
					<td width="20px"><input type="checkbox" name="kedudukan_cb"></td>
					<td width="200px"><label for="example-text-input" class="col-form-label">Kedudukan</label></td>
					<td colspan=2>
						<select name="kedudukan" class="form-control">
							<option value=''>Pilih Kedudukan</option>
							<option value='PUSAT'>Pusat</option>
							<option value='UPT'>UPT</option>
						</select>
					</td>
				</tr>
				<tr>
					<td width="20px"><input type="checkbox" name="pendidikan_cb"></td>
					<td width="200px"><label for="example-text-input" class="col-form-label">Pendidikan</label></td>
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
					<td width="200px"><label for="example-text-input" class="col-form-label">Kategori</label></td>
					<td colspan=2>
						<select name="kategori" class="form-control">
							<option value=''>Pilih Kategori</option>
							<option value='1'>Rotasi</option>
							<option value='2'>Promosi</option>
						</select>
					</td>
				</tr>
				<tr>
					<td width="20px"><input type="checkbox" name="tahun_cb"></td>
					<td width="200px"><label for="example-text-input" class="col-form-label">Tahun Pelantikan</label></td>
					<td colspan=2><input class="form-control" type="text" name="tahun_pelantikan" value="" ></td>
				</tr>
				<tr>
					<td width="20px"><input type="checkbox" name="tgl_cb"></td>
					<td width="200px"><label for="example-text-input" class="col-form-label">Tanggal Pelantikan</label></td>
					<td colspan="2">
						<div class="input-group date col-sm-12">
		                  <div class="input-group-addon">
		                    <i class="fa fa-calendar"></i>
		                  </div>
		                    <input type='text' class="form-control pull-right datepicker" name='tanggal_pelantikan'  value="<?php echo set_value('tanggal_pelantikan', isset($kandidat->tanggal_pelantikan) ? $kandidat->tanggal_pelantikan : ''); ?>" />
		                    <span class='help-inline'><?php echo form_error('tanggal_pelantikan'); ?></span>
		                </div>
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
			<div class="messages"></div> 
	</div>
	<div class="box-body">
		<?php echo form_open($this->uri->uri_string(),"id=frm_tabel","form"); ?>
		<table class="filter_pegawai" sborder=0 width='100%' cellpadding="10">
			<tr>
				<td colspan=2>
					<div class="control-group col-sm-12">
						<div class="input-group date">
						  <div class="input-group-addon">
							<i class="fa fa-calendar"></i>
						  </div>
						  	<input id='tgl_pelantikan' type='text' class="form-control pull-right datepicker" name='tgl_pelantikan' maxlength='25' value="" placeholder="Tanggal Pelantikan" />
						</div>
					</div>
				</td>
				<td>
					<button type="submit" class="btn btn-warning pull-left "><i class="fa fa-save"></i> Tetapkan Pelantikan</button>
				</td>
				<td>
					<div class="btn-group pull-right">
						<button class='btn btn-primary download_xls' target="_blank"><i class="fa fa-download" aria-hidden="true"></i> Download .xls</button>
						<button class='btn btn-primary download_absen' target="_blank"><i class="fa fa-download" aria-hidden="true"></i> Download Absen</button>
					</div>
				</td>
			</tr>
			 
		</table>
		<table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-data table-hover">
			<thead>
			<tr>
				<th style="width:10px">#</th>
				<th style="width:10px">No</th>
				<th align="center">Foto</th>
				<th>NIP/<br>NAMA Pegawai</th>
				<th>Golongan</th>
				<th>Unit Kerja</th>
				<th>Jabatan</th>
				<th width="80px" align="center">#</th></tr>
			</thead>
		</table>
		<?php
		echo form_close();    
		?>

	<b>*Ket</b> Nama diblock kuning artinya sudah ada tanggal pelantikan
	</div>
</div>

<script type="text/javascript">

$(".download_xls").click(function(){
	var xyz = $("#form_search_pegawai").serialize();
	window.open("<?php echo base_url('admin/kepegawaian/baperjakat/downloadpenetapanmenteri/'.$periode);?>?"+xyz);
});
$(".download_absen").click(function(){
	var xyz = $("#form_search_pegawai").serialize();
	window.open("<?php echo base_url('admin/kepegawaian/baperjakat/downloadabsen/'.$periode);?>?"+xyz);
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
					{"className": "dt-center", "targets": [0,1,4]},
					{ "targets": [0,2,5,6,7], "orderable": false }
				],
	ajax: {
	  url: "<?php echo base_url() ?>admin/kepegawaian/baperjakat/getdatapenetapanmenteri/<?php echo $periode; ?>",
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
$("#frm_tabel").submit(function(){
	var json_url = "<?php echo base_url() ?>admin/kepegawaian/baperjakat/savepelantikan";
	 $.ajax({    
	 	type: "POST",
		url: json_url,
		data: $("#frm_tabel").serialize(),
	    dataType: "json",
		success: function(data){ 
	        if(data.success){
	            swal("Pemberitahuan!", data.msg, "success");
	            $table.ajax.reload(null,true);
	        }
	        else {
	            $(".messages").empty().append(data.msg);
	        }
		}});
	return false;
});

$(".box-body").on('click','.btn-tetapkan',function(event){
      event.preventDefault();
      var kode =$(this).attr("kode");
        swal({
          title: "Anda Yakin?",
          text: "Tetapkan kandidat menteri!",
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
                url: "<?php echo base_url() ?>admin/kepegawaian/baperjakat/tetapkankandidatmenteri/"+kode,
                dataType: "html",
                timeout:180000,
                success: function (result) {
                  swal("Informasi!", result, "success");
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

$(".box-body").on('click','.btn-batalkan_menteri',function(event){
      event.preventDefault();
      var kode =$(this).attr("kode");
        swal({
          title: "Anda Yakin?",
          text: "Batalkan kandidat menteri!",
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
                url: "<?php echo base_url() ?>admin/kepegawaian/baperjakat/batanlkankandidatmenteri/"+kode,
                dataType: "html",
                timeout:180000,
                success: function (result) {
                  swal("Informasi!", result, "success");
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
<script>
    //Date picker
    $('.datepicker').datepicker({
      autoclose: true,format: 'yyyy-mm-dd'
    });
</script>