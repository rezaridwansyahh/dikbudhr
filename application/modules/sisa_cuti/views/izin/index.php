
<?php
$checkSegment = $this->uri->segment(4);
$areaUrl = SITE_AREA . '/izin/sisa_cuti';
$num_columns	= 44;
$can_delete	= $this->auth->has_permission('Sisa_cuti.Izin.Delete');
$can_edit		= $this->auth->has_permission('Sisa_cuti.Izin.Edit');

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
					<td width="100px"><label for="example-text-input" class="col-form-label">NAMA</label></td>
					<td colspan=2><input class="form-control" type="text" name="nama_key" value="" ></td>
				</tr>
				<tr>
					<td width="100px"><label for="example-text-input" class="col-form-label">NIP</label></td>
					<td colspan=2><input class="form-control" type="text" name="nip_key" value="" ></td>
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
	<div class="box-body">
		<a href="<?php echo site_url($areaUrl . '/create'); ?>" class="show-modal">
              	<button type="button" class="btn btn-default btn-warning margin pull-right "><i class="fa fa-plus"></i> Tambah</button>
			</a>
		<table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-data table-hover">
			<thead>
			<tr>
				<th style="width:10px" rowspan="2">No</th>
				<th width="20%" rowspan="2">NIP</th>
				<th rowspan="2">NAMA</th>
				<th width="10%" rowspan="2">TAHUN</th>
				<th colspan="6">SISA CUTI</th>
				<th width="70px" rowspan="2" align="center">#</th>
			</tr>
			<tr>
				<th>N</th>
				<th>N-1</th>
				<th>N-2</th>
				<th>JML</th>
				<th>DIPAKAI</th>
				<th>SISA</th>
			</tr>
			</thead>
			<body>
				
			</body>
			
		</table>
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
	
	dom : "<'row'<'col-sm-6'><'col-sm-6'>>" +
	"<'row'<'col-sm-12'tr>>" +
	"<'row'<'col-sm-2'l><'col-sm-3'i><'col-sm-7'p>>",
	processing: true,
	serverSide: true,
	"columnDefs": [
			{"className": "text-center", "targets": [3,4,5,6,7,8,9,10]},
			{ "targets": [0,5], "orderable": false }
		],
	ajax: {
	  url: "<?php echo base_url() ?>admin/izin/sisa_cuti/getdata_sisa_cuti",
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
		text: "Delete data sisa cuti!",
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
					url: "<?php echo base_url() ?>admin/izin/sisa_cuti/delete_sisa_cuti",
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

    function submitdata(){
        
        var json_url = "<?php echo base_url() ?>admin/izin/sisa_cuti/save";
         $.ajax({    
            type: "POST",
            url: json_url,
            data: $("#frm").serialize(),
            dataType: "json",
            success: function(data){ 
                if(data.success){
                    swal("Pemberitahuan!", data.msg, "success");
                    $("#modal-global").modal("hide");
                    $table.ajax.reload(null,true);
                }
                else {
                	swal("Pemberitahuan!", data.msg, "error");
                    $(".messages").empty().append(data.msg);
                }
            }});
        return false; 
    }
</script>