<style>
	.dataTables_processing{position:absolute;top:1%!important;left:50%;width:100%;height:40px;margin-left:-50%;margin-top:-25px;padding-top:20px;text-align:center;}
</style>
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
					<td width="100px"><label for="example-text-input" class="col-form-label">Jenis</label></td>
					<td>
						<select id="jenis" name="jenis" style="width:100%" class="form-control">
	                        <option value="">Silahkan Pilih</option>
	                            <?php 
	                            foreach($jenis_satkers as $row){
	                            ?>
	                                <option <?php echo (isset($data->JENIS_SATKER) and trim($row->nama_jenis_satker) == trim($data->JENIS_SATKER)) ? "selected" : ""; ?> value="<?php echo trim($row->nama_jenis_satker); ?>"><?php echo $row->nama_jenis_satker; ?></option>
	                            <?php
	                            }
	                            ?>
                        </select>
					</td>
					 
				</tr>
				<tr>
					<td width="100px"><label for="example-text-input" class="col-form-label">Satuan Kerja</label></td>
					<td>
						<select id="unit_id_key" name="unit_id_key" width="100%" class=" col-md-10 form-control"></select>
					</td>
					 
				</tr>
				
				<tr>
					<td colspan="2">
						<button class="btn btn-warning pull-right" style="margin:3px;" id="btn_proses_sinkron">Sinkron data <i class="fa fa-gear"></i> </button>
						<a href="javascript:;" style="margin:3px;" class="btn green btn-info button-submit download_xls pull-right"> 
							Download
							<i class="fa fa-download" aria-hidden="true"></i> 
			            </a>
						<a href="javascript:;" id="btn_cari" style="margin:3px;" class="btn green btn-primary button-submit pull-right"> 
							Cari data
							 <i class="fa fa-search"></i>			                
			            </a>
			            
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
		<table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-data table-hover">
			<thead>
			<tr>
				<th style="width:10px">No</th>
				<th>Satker</th>
				<th>Jumlah Mutasi</th>
				<th>Jumlah Dikbudhr</th>
				<th>Selisih</th>
				<th>#</th>
			</thead>
		</table>
	</div>
</div>
<script type="text/javascript">
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
	pageLength: 100,
	"columnDefs": [
					{"className": "text-center", "targets": [0,2,3,4,5]},
					{ "targets": [0,2,3], "orderable": false }
				],
	ajax: {
	  url: "<?php echo base_url() ?>pegawai/sinkronisasi/getjumlah",
	  type:'POST',
	  "data": function ( d ) {
			d.search['advanced_search_filters']=  $("#form_search_pegawai").serializeArray();
		}
	}
});
function sinkronisasi_data(){
	var the_data = new FormData(document.getElementById("form_search_pegawai"));
	var json_url = "<?php echo base_url() ?>pegawai/sinkronisasi/synchjumlah";
	 $.ajax({    
	 	type: "POST",
		url: json_url,
		data: the_data,
        dataType: "json",
        processData: false, // tell jQuery not to process the data
        contentType: false, // tell jQuery not to set contentType
		success: function(data){ 
            if(data.success){
                swal("Pemberitahuan!", data.msg, "success");
				$('#btn_proses_sinkron').removeClass('disabled');
			    $('#btn_proses_sinkron').text('Sinkron data');
			    $('#btn_proses_sinkron').append(' <i class="fa fa-gear"></i>');
            }
            else {
                swal("Pemberitahuan!", data.msg, "error");
            }
		}});
	return false; 
}
$("#btn_cari").click(function(){
	$table.ajax.reload(null,true);
	return false; 
});	
$("#btn_proses_sinkron").click(function(){
	swal({
		title: "Anda Yakin?",
		text: "Sinkronisasi jumlah pegawai dengan aplikasi Mutasi!",
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
			$('#btn_proses_sinkron').text('Proses sinkron data..');
		    $('#btn_proses_sinkron').addClass('disabled');
			sinkronisasi_data();
		} else {
			swal("Batal", "", "error");
		}
	});
	
	return false; 
});	
$('body').on('click','.btn_synch_one',function () { 
	var kode =$(this).attr("kode");
	swal({
		title: "Anda Yakin?",
		text: "Refresh jumlah pegawai!",
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
			$('#btn_synch_one').text('Proses sinkron data..');
		    $('#btn_synch_one').addClass('disabled');
		    sinkronisasi_data_satker(kode);
		} else {
			swal("Batal", "", "error");
		}
	});
	
	return false; 
});
function sinkronisasi_data_satker(kode){
	var the_data = "satker_id="+kode;
	var json_url = "<?php echo base_url() ?>pegawai/sinkronisasi/synchjumlah";
	 $.ajax({    
	 	type: "POST",
		url: json_url,
		data: the_data,
        dataType: "json",
        timeout:180000,
		success: function(data){ 
            if(data.success){
                swal("Pemberitahuan!", data.msg, "success");
				$('#btn_proses_sinkron').removeClass('disabled');
			    $('#btn_proses_sinkron').text('Sinkron data');
			    $('#btn_proses_sinkron').append(' <i class="fa fa-gear"></i>');
			    $table.ajax.reload(null,true);
            }
            else {
                swal("Pemberitahuan!", data.msg, "error");
            }
		}});
	return false; 
}
$(".download_xls").click(function(){
	var xyz = $("#form_search_pegawai").serialize();
	window.open("<?php echo base_url('pegawai/sinkronisasi/download');?>?"+xyz);
});
</script>