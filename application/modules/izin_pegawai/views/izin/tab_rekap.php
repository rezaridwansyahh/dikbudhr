<div class="tab-pane active" id="<?php echo $TAB_ID;?>">
<?php
$checkSegment = $this->uri->segment(4);
$areaUrl = SITE_AREA . '/izin/izin_pegawai';
$num_columns	= 44;
$can_delete	= $this->auth->has_permission('Izin_pegawai.Izin.Delete');
$can_edit		= $this->auth->has_permission('Izin_pegawai.Izin.Edit');

if ($can_delete) {
    $num_columns++;
}
?>
<div class="admin-box box box-primary expanded-box">
	<div class="box-header">
               
	</div>

	<div class="box-body">
		<?php echo form_open($this->uri->uri_string(),"id=form_search_pegawai","form"); ?>
			<style>
				table.filter_pegawai tr td {
					padding-top: 2px;
				}
			</style>
			<div class="control-group col-sm-4">
                        <label for="inputNAMA" class="control-label">MULAI TANGGAL</label>
                        <div class="input-group date">
                          <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                          </div>
                            <input type='text' class="form-control pull-right datepicker" name='DARI_TANGGAL' id='DARI_TANGGAL' value="<?php echo set_value('DARI_TANGGAL', isset($izin_pegawai->DARI_TANGGAL) ? $izin_pegawai->DARI_TANGGAL : ''); ?>" />
                            <span class='help-inline'><?php echo form_error('DARI_TANGGAL'); ?></span>
                        </div>
                    </div> 
                    <div class="control-group col-sm-4">
                        <label for="inputNAMA" class="control-label">SAMPAI DENGAN</label>
                        <div class="input-group date">
                          <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                          </div>
                            <input type='text' class="form-control pull-right datepicker" name='SAMPAI_TANGGAL' id='SAMPAI_TANGGAL' value="<?php echo set_value('SAMPAI_TANGGAL', isset($izin_pegawai->SAMPAI_TANGGAL) ? $izin_pegawai->SAMPAI_TANGGAL : ''); ?>" />
                            <span class='help-inline'><?php echo form_error('SAMPAI_TANGGAL'); ?></span>
                        </div>
                    </div> 
                    <div class="control-group col-sm-4">
                        <label for="inputNAMA" class="control-label">&nbsp;</label>
                        <div class="input-group date">
                          <button type="submit" class="btn btn-success pull-right "><i class="fa fa-search"></i> Cari</button>
                        </div>
                    </div> 
			 
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
				<th width="10px" style="width:10px">No</th>
				<th>IZIN</th>
				<th>Jumlah Pelaksanaan</th>
				<th>Jumlah Hari</th>
			</thead>
		</table>
	</div>
</div>

<script type="text/javascript">
$('.datepicker').datepicker({
  autoclose: true,format: 'yyyy-mm-dd'
});
$table = $(".table-data").DataTable({
	
	dom : "<'row'<'col-sm-6'><'col-sm-6'>>" +
	"<'row'<'col-sm-12'tr>>" +
	"<'row'<'col-sm-2'l><'col-sm-3'i><'col-sm-7'p>>",
	processing: true,
	serverSide: true,
	"columnDefs": [
					{"className": "text-center", "targets": []},
					{ "targets": [], "orderable": false }
				],
	ajax: {
	  url: "<?php echo base_url() ?>admin/izin/izin_pegawai/getdata_rekap_izin",
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
		text: "Delete pengajuan izin!",
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
					url: "<?php echo base_url() ?>admin/izin/izin_pegawai/deletedata",
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
</div>