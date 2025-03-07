<style>
	.dataTables_processing{position:absolute;top:1%!important;left:50%;width:100%;height:40px;margin-left:-50%;margin-top:-25px;padding-top:20px;text-align:center;}
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
					<td>
						<label class="col-form-label">NIP</label>
					</td>
					<td colspan="2">
						<input placeholder="nip" type='text' class='form-control' id="nip" name="nip" value="">
					</td>
					
				</tr>
				
				<tr>
					<td colspan=4>
						<div class="pull-right">
							<button class="btn btn-warning" type="button" id="download_xls">Download <i class="fa fa-download"></i> </button>
							<a href="javascript:;" id="btn_cari" class="btn green btn-primary button-submit"> 
								Lihat Data
								 <i class="fa fa-search"></i>			                
				            </a>
			        	</div>
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
				<th>Pegawai</th>
				<th>Tahun</th>
				<th>Nilai SKP</th>
				<th>Nilai PPK</th>
				<th>Atasan Langsung</th>
				<th>Atasan Atasan Langsung</th>
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
	  url: "<?php echo base_url() ?>rekap/getdataskp",
	  type:'POST',
	  "data": function ( d ) {
			d.search['advanced_search_filters']=  $("#form_search_pegawai").serializeArray();
		}
	}
});
$("#btn_cari").click(function(){
	$('#btn_cari').text('load data..');
    $('#btn_cari').addClass('disabled');
	$table.ajax.reload(null,true);
	$('#btn_cari').removeClass('disabled');
    $('#btn_cari').text('Lihat Data');
    $('#btn_cari').append(' <i class="fa fa-search"></i>');
    
	return false; 
});
$('.datepicker').datepicker({
  autoclose: true,format: 'yyyy-mm-dd'
});
$("#download_xls").click(function(){
	var xyz = $("#form_search_pegawai").serialize();
	window.open("<?php echo base_url('rekap/download_skp');?>?"+xyz);
});
</script>