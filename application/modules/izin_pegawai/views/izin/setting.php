
<?php
$checkSegment = $this->uri->segment(4);
$areaUrl = SITE_AREA . '/izin/izin_pegawai/pilihpejabat';
$num_columns	= 44;
$can_delete	= $this->auth->has_permission('Setting_atasan.Izin.Delete');
$can_edit		= $this->auth->has_permission('Setting_atasan.Izin.Add');

if ($can_delete) {
    $num_columns++;
}
?>
<div class="callout callout-info">
   <h4>Perhatian</h4>
   <p>Untuk menentukan "Atasan Langsung" dan Pejabat "PPK" silahkan checklist pegawai yang akan ditentukan, kemudian klik tombol <button type="button" class="btn btn-default btn-warning margin "><i class="fa fa-plus"></i> Pilih Pejabat</button>, kemudian silahkan pilih pejabatnya, dan klik tombol <button type="button" class="btn btn-tetapkan btn-success "><i class="fa fa-save"></i> Simpan/Tetapkan Pejabat</button> untuk menyimpan data</p>
   <p>Jika data belum berubah, silahkan klik tombol "Refresh data" dan tunggu sampai muncul notifikasi selesai</p>
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
					<td width="150px"><label for="example-text-input" class="col-form-label">Satuan Kerja</label></td>
					<td colspan=2><select id="unit_id_key" name="unit_id_key" width="100%" class=" col-md-10 format-control"></select></td>
				</tr>
				<tr>
					<td width="100px"><label for="example-text-input" class="col-form-label">Pegawai</label></td>
					<td colspan=2><select id="pegawai_key" name="pegawai_key[]" multiple="multiple" width="100%" class=" col-md-10 format-control"></select>
					</td>
				</tr>
				<tr>
					<td><label for="example-text-input" class="col-form-label">NAMA</label></td>
					<td colspan=2><input class="form-control" type="text" name="nama_key" value="" ></td>
				</tr>
				<tr>
					<td><label for="example-text-input" class="col-form-label">NIP</label></td>
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
<div class="messages"></div>
<div class="admin-box box box-primary">

	<div class="box-body">
		 <?php echo form_open($this->uri->uri_string(), 'id="frm-usulanpejabat"'); ?> 
		 	<?php if ($can_edit) : ?>
				<a style="margin-right:10px;">
					<!-- <button type="button" class="btn btn-refresh btn-success margin pull-right "><i class="fa fa-refresh"></i> Refresh Data</button>-->
				</a> 	
			<?php endif; ?>
			<!--
			<?php if ($can_edit) : ?>
				<a style="margin-right:10px;">
					<button type="button" class="btn btn-tetapkan btn-success margin pull-right "><i class="fa fa-save"></i> Tetapkan Pejabat</button>
				</a> 	
			<?php endif; ?>
			-->
			<a href="<?php echo site_url($areaUrl . '/create'); ?>" class="show-modal">
              	<button type="button" class="btn btn-default btn-warning margin pull-right "><i class="fa fa-plus"></i> Pilih Pejabat</button>
			</a>
		<div id="divatasan"></div>
		<div id="divppk"></div>
		<input type="hidden" id="txtatasan" name="NIP_ATASAN" value="">
		<input type="hidden" id="txtppk" name="NIP_PPK" value="">
		<input type="hidden" id="txtketerangan" name="KETERANGAN_TAMBAHAN" value="">
		<table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-data table-hover">
			<thead>
			<tr>
				<tr><th style="width:10px"><input type="checkbox" class="group-checkable"></th>
				<th width="20%">PEGAWAI</th>
				<th >UNITKERJA</th>
				<th  width="30%">ATASAN</th>
				<th width="50px" align="center">#</th></tr>
			</thead>
		</table>
		<?php
		echo form_close();    
		?>
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
					{"className": "text-center", "targets": [0,4]},
					{ "targets": [0,4], "orderable": false }
				],
	ajax: {
	  url: "<?php echo base_url() ?>admin/izin/izin_pegawai/getdata_setting",
	  type:'POST',
	  "data": function ( d ) {
	  		d.pegawai  = $("#pegawai_key").val();
			d.search['advanced_search_filters']=  $("#form_search_pegawai").serializeArray();
		}
	}
});
$("#form_search_pegawai").submit(function(){
	$table.ajax.reload(null,true);
	return false;
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
$("#pegawai_key").select2({
        placeholder: 'Silahkan Pilih Pegawai (bisa lebih dari satu)',
        width: '100%',
        minimumInputLength: 3,
        allowClear: true,
        ajax: {
            url: '<?php echo site_url("admin/kepegawaian/pegawai/ajax");?>',
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
$('body').on('click','.btn-hapus',function () { 
	var kode =$(this).attr("kode");
	swal({
		title: "Anda Yakin?",
		text: "Hapus data Setting Pejabat!",
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
					url: "<?php echo base_url() ?>admin/izin/izin_pegawai/deletedata_pejabat",
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
$('body').on('click','.btn-tetapkan',function(event){
	var json_url = "<?php echo base_url() ?>admin/izin/izin_pegawai/savepejabat";
	$.ajax({    
        type: "POST",
        url: json_url,
        data: $("#frm-usulanpejabat").serialize(),
        dataType: "json",
        success: function(data){ 
            if(data.success){
                swal("Pemberitahuan!", data.msg, "success");
                $table.ajax.reload(null,true);
            }
            else {
                $(".messages").empty().append(data.msg);
            }
        },
        error: function (e) {
        	swal("Pemberitahuan!", "Ada kesalahan, sialhkan hubungi admin", "error");
            $(".messages").empty().append("Ada kesalahan, sialhkan hubungi admin");
            //console.log("ERROR : ", e);

        }
    });
    return false; 
	 
});
$('body').on('click','.btn-refresh',function(event){
	var json_url = "<?php echo base_url() ?>admin/izin/izin_pegawai/refreshpejabat";
	$.ajax({    
        type: "POST",
        url: json_url,
        data: "action=refresh",
        dataType: "json",
        success: function(data){ 
            if(data.success){
                swal("Pemberitahuan!", data.msg, "success");
                $table.ajax.reload(null,true);
            }
            else {
                $(".messages").empty().append(data.msg);
            }
        },
        error: function (e) {
        	swal("Pemberitahuan!", "Ada kesalahan, sialhkan hubungi admin", "error");
            $(".messages").empty().append("Ada kesalahan, sialhkan hubungi admin");
            //console.log("ERROR : ", e);

        }
    });
    return false; 
	 
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