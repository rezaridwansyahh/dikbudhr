<?php
$areaUrl = SITE_AREA . '/masters/jenis_arsip';
$num_columns	= 2;
$can_delete	= $this->auth->has_permission('Jenis_arsip.Masters.Delete');
$can_edit		= $this->auth->has_permission('Jenis_arsip.Masters.Edit');
$has_records	= isset($records) && is_array($records) && count($records);

if ($can_delete) {
    $num_columns++;
}
?>
<div class="admin-box box box-primary">
	<div class="box-header">
		<h3>
			Jenis Arsip Digital
			<?php if ($this->auth->has_permission('Jenis_arsip.Masters.Create')) : ?>
              <a href="<?php echo site_url($areaUrl . '/create'); ?>" class="show-modal">
              	<button type="button" class="btn btn-default btn-warning margin pull-right "><i class="fa fa-plus"></i> Tambah</button>
			</a>
        <?php endif; ?>
		</h3>
		 
	</div>
	<div class="box-body">	
		<?php echo form_open($this->uri->uri_string()); ?>
			<table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-datatable table-hover">
				<thead>
					<tr>
						<tr><th style="width:10px">No</th>
						<th>Jenis Arsip</th>
						<th>Kategori</th>
						<th width="40%">Keterangan</th>
						<th width="100px">#</th>
					</tr>
				</thead>
			</table>
		<?php
   		echo form_close();
    ?>
	</div>
</div>


<script type="text/javascript">
var grid_daftar = $(".table-datatable").DataTable({
	ordering: false,
	processing: true,
	serverSide: true,
	ajax: {
	  url: "<?php echo base_url() ?>admin/masters/jenis_arsip/ajax_data",
	  type:'POST',
	}
});

$('body').on('click','.btn-hapus',function () { 
	var kode =$(this).attr("kode");
	swal({
		title: "Anda Yakin?",
		text: "Hapus data!",
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
			var post_data = "kode="+kode;
			$.ajax({
					url: "<?php echo base_url() ?>admin/masters/jenis_arsip/delete",
					type:"POST",
					data: post_data,
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

</script>
<script>
$('body').on('click','#btnsave',function () { 
  submitdata();
});  
$('body').on('click','#btncancel',function () { 
$("#modal-global").modal("hide");
});  
    
    
    function submitdata(){
        
        var json_url = "<?php echo base_url() ?>admin/masters/jenis_arsip/save";
         $.ajax({    
            type: "POST",
            url: json_url,
            data: $("#frm").serialize(),
            dataType: "json",
            success: function(data){ 
                if(data.success){
                    swal("Pemberitahuan!", data.msg, "success");
                    $("#modal-global").modal("hide");
                    grid_daftar.ajax.reload();
                }
                else {
                    $(".messages").empty().append(data.msg);
                }
            }});
        return false; 
    }
</script>
