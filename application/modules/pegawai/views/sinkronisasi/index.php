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
					<td><label for="example-text-input" class="col-form-label">Tanggal</label></td>
					<td>
						 <div class="input-group date">
	                          <div class="input-group-addon">
	                            <i class="fa fa-calendar cal1"></i>
	                          </div>
	                            <input type='text' readonly class="form-control pull-right datepicker" id="dari_tanggal" name='dari_tanggal' value="" />
	                            <span class='help-inline'><?php echo form_error('dari_tanggal'); ?></span>
	                      </div>
					</td>
					<td>
						 <div class="input-group date">
	                          <div class="input-group-addon">
	                            <i class="fa fa-calendar cal2"></i>
	                          </div>
	                            <input type='text' readonly class="form-control pull-right datepicker" id="sampai_tanggal" name='sampai_tanggal' value="" />
	                            <span class='help-inline'><?php echo form_error('sampai_tanggal'); ?></span>
	                      </div>
					</td>
				</tr>
				
				<tr>
					<td colspan=4>
						
						<button class="btn btn-warning pull-right " id="btn_proses_sinkron">Sinkron data <i class="fa fa-gear"></i> </button>
						<a href="javascript:;" id="btn_cari" class="btn green btn-primary button-submit pull-right"> 
							Lihat Perubahan data
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
				<th>ID Tabel</th>
				<th>NIP</th>
				<th>No SK</th>
				<th>Tindakan</th>
				<th>Jenis SK</th>
				<th>Jabatan</th>
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
	pageLength: 100,
	"columnDefs": [
					{"className": "text-center", "targets": [0,6]},
					{ "targets": [0,5], "orderable": false }
				],
	ajax: {
	  url: "<?php echo base_url() ?>pegawai/sinkronisasi/sinch_jabatan_harian",
	  type:'POST',
	  "data": function ( d ) {
			d.search['advanced_search_filters']=  $("#form_search_pegawai").serializeArray();
		}
	}
});
function sinkronisasi_data(){
	var the_data = new FormData(document.getElementById("form_search_pegawai"));
	var json_url = "<?php echo base_url() ?>pegawai/sinkronisasi/proses_sinkron";
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
	$('#btn_cari').text('load data..');
    $('#btn_cari').addClass('disabled');
	$table.ajax.reload(null,true);
	$('#btn_cari').removeClass('disabled');
    $('#btn_cari').text('Lihat Perubahan data');
    $('#btn_cari').append(' <i class="fa fa-search"></i>');
    
	return false; 
});	
$("#btn_proses_sinkron").click(function(){
	swal({
		title: "Anda Yakin?",
		text: "Sinkronisasi data dengan aplikasi Mutasi!",
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
$('.datepicker').datepicker({
  autoclose: true,format: 'yyyymmdd'
});
</script>