<div id="manage_unitkerja_container" class="admin-box box box-primary">
	<div class="box-header">
        <div class="btn-group"  style='float:right'>
			<?php if ($canTambahUsulan) : ?>
				<a style="margin-right:10px;" class="btn-tambah-usulan btn btn-small btn-warning" href="<?php echo $module_url; ?>/create"><i class='fa fa-plus''></i> Tambah Usulan</a> 	
			<?php endif; ?>
			<?php if ($canKirimInboxInstansi) : ?>
				<a style="margin-right:10px;" class="btn-kirim-usulan btn btn-small btn-warning" href="<?php echo $module_url; ?>/form_kirim_usulan"><i class="fa fa-arrow-right"></i> Kirim ke Inbox Instansi</a> 	
			<?php endif; ?>
			
			<?php if ($canCetak) : ?>
			<a title='Cetak Daftar dalam bentuk Xls' style="margin-right:10px;" class="btn-cetak-xls btn btn-small btn-warning" href="<?php echo $module_url; ?>/cetak_xls"><i class="fa fa-print"></i> Cetak</a> 
			<?php endif; ?>
        </div>
    </div>
	<div class="box-body">
			<form id="form-filter">

				<table width="100%">
					<tbody>
						<tr>
							<td width=200>Pegawai</td>	
							<td><input type="text" id="txt_pegawai" class="form-control" name="txt_pegawai"'></input></td>	
						</tr>
						<tr>
							<td width=200>Unit Kerja</td>	
							<td><select id="filter_unit_kerja" class="select2 form-control lazy-select2" name="filter_unit_kerja"data-minimum=0 data-placeholder='Filter Unit Kerja' data-url='<?php echo base_url('pegawai/kepegawaian/ajax_unit_list');?>'></select></td>	
						</tr>
						<tr>
							<td  width=200>Status Usulan</td>	
							<td>
								<select id="filter_status" class="select2 form-control lazy-select2" multiple name="filter_status[]" data-minimum=0 data-placeholder='Filter Status' data-url='<?php echo base_url('kpo/kpo-instansi/list_status');?>'>
									<option value=''>--Semua--</option>
								</select>
							</td>	
						</tr>
						<tr>
							<td colspan=2><a class='btn btn-warning btn-cari'><i class='fa fa-search'></i> Cari</a></td>								
						</tr>
					</tbody>
				</table>
			</form>
			<div class="callout callout-info">
			   <h4>Keterangan</h4>
			   <p>Jika Tidak Ada perubahan Verifikasi, Maka Data yang akan di INBOX akan Otomatis menjadi MS ketika Kirim ke Inbox Sekretariat</p>
			 </div>
			 <?php echo form_open($this->uri->uri_string(), 'id="frm-usulan"'); ?> 
			 <div class="table-group-actions pull-right" style="width: 280px;">
                <select class="input-inline input-sm pull-left" name="status_ms"  id="status_ms" style="width:200px">
                    <option value="">Pilih Aksi</option>
                    <option value="6">MS</option>
                    <option value="5">TMS</option>
                </select>
                <button class="btn btn-sm green table-group-action-submit btn btn-small btn-success pull-right" >
                    <i class="fa fa-check"></i> Submit</button>
                </div>
            <table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-datatable table-hover">
				<thead>
					<tr>
						<tr><th style="width:10px"><input type="checkbox" class="group-checkable"></th>
						
						<th height="30px" width="200px" valign="middle">Pegawai</th>
						<th>Unit Kerja</th>
						<th>Status</th>
						<th>Keterangan</th>
						<th width="70px">#</th>
					</tr>
				</thead>
			</table>
			</form>
    </div>
</div>    
<style>
	#form-filter td {
		padding:2px;
	}
</style>
<script type="text/javascript">
$(document).ready(function(){
	$(".lazy-select2").each(function(i,o){
		$(this).select2(
			{
				placeholder: $(this).data('placeholder'),
				width: '100%',
				minimumInputLength: $(this).data('minimum'),
				allowClear: true,
				ajax: {
					url: $(this).data('url'),
					dataType: 'json',
					data: function(params) {
						return {
							term: params.term || '',
							page: params.page || 1
						}
					},
					cache: true
				}
			}
		);
	});
});
var grid_daftar = $(".table-datatable").DataTable({
	dom : "<'row'<'col-sm-6'><'col-sm-6'>>" +
	"<'row'<'col-sm-12'tr>>" +
	"<'row'<'col-sm-2'l><'col-sm-3'i><'col-sm-7'p>>",
	ordering: false,
	processing: true,
	serverSide: true,
	"columnDefs": [
					{"className": "dt-center", "targets": [0,1]},
					{ "targets": [0,5], "orderable": false }
				],
	ajax: {
	  url: "<?php echo $module_url; ?>/ajax_listall",
	  type:'POST',
	  data: function ( d ) {
			d.filter_unit_kerja  = $("#filter_unit_kerja").val();
			d.filter_status  = $("#filter_status").val();
			d.filter_pegawai  = $("#txt_pegawai").val();
			
	  }
	}
});
$('body').on('click','.table-group-action-submit',function(event){
	var the_data = new FormData(document.getElementById("frm-usulan"));
	//alert("<?php echo $module_url;?>/act_edit");
	var statusms = $("#status_ms").val();
	if(statusms == ""){
		alert("Silahkan tentukan status");
		return false;
	}
    $.ajax({
        url: "<?php echo $module_url;?>/act_edit",
        type: "POST",
        data: the_data,
        enctype: 'multipart/form-data',
        processData: false, // tell jQuery not to process the data
        contentType: false, // tell jQuery not to set contentType
        beforeSend: function (xhr) {
            $("#loading-all").show();
        },
        success: function(result) {
        	//alert(result);
            grid_daftar.ajax.reload();
        },

    });
	//return false;
});
$('body').on('click','.btn-tambah-usulan',function(event){
	showModalX.call(this,'sukses-update-usulan',function(){
		grid_daftar.ajax.reload();
	},this);
	event.preventDefault();
});
$('body').on('click','.btn-kirim-usulan',function(event){
	showModalX.call(this,'sukses-kirim-usulan',function(){
		grid_daftar.ajax.reload();
	},this);
	event.preventDefault();
});
$('body').on('click','.btn-cetak-xls',function(event){
	$.blockUI(); 
	$.fileDownload('<?php echo $module_url;?>/cetak_xls', {
		httpMethod: "POST",
        data: {
			"filter_status":$("#filter_status").val(),
			"filter_unit_kerja":$("#filter_unit_kerja").val()
		},
		successCallback: function (url) {
			$.unblockUI();
		},
		failCallback: function (responseHtml, url) {
			$.unblockUI();
			var responseCode = JSON.parse(responseHtml);
			swal("Informasi", responseCode.message, "error");
		}
	});
	event.preventDefault();
});
$('body').on('click','.btn-cari',function(event){
	grid_daftar.ajax.reload();
});
$('body').on('click','.show-modal-custom',function(event){
	showModalX.call(this,'sukses-update-usulan',function(){
		grid_daftar.ajax.reload();
	},this);
	event.preventDefault();
});
$('body').on('click','.btn-hapus',function () { 
	var kode =$(this).attr("kode");
	swal({
		title: "Anda Yakin?",
		text: "Hapus data Pendidikan!",
		type: "warning",
		showCancelButton: true,
		confirmButtonClass: 'btn-danger',
		confirmButtonText: 'Ya, Hapus!',
		cancelButtonText: "Tidak, Batalkan!",
		closeOnConfirm: false,
		closeOnCancel: false
	},
	function (isConfirm) {
		if (isConfirm) {
			$.ajax({
					url: "<?php echo $module_url;?>"+"/delete"+kode,
					type:"POST",
					dataType: "html",
					timeout:180000,
					success: function (result) {
						 swal("Data berhasil di hapus!", result, "success");
						 grid_daftar.ajax.reload();
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

// handle group checkboxes check/uncheck
$('.group-checkable').change(function() {
    var set = $('body').find('tbody > tr > td:nth-child(1) input[type="checkbox"]');
    var checked = $(this).prop("checked");
    $(set).each(function() {
        $(this).prop("checked", checked);
    });
    //$.uniform.update(set);
    //countSelectedRecords();
});
</script>
