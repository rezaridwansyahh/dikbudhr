<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/select2/select2.min.css">
<div class="callout callout-info">
   <h4>Bezetting!</h4>
   <p>Silahkan cari Unitkerja untuk melihat bezzeting pegawai pada unit tersebut.</p>
 </div>
<div class="admin-box box box-primary">
	<div class="box-header">
	  <div class="control-group<?php echo form_error('JUMLAH_PEMANGKU_JABATAN') ? ' error' : ''; ?> col-sm-10">
                <?php echo form_label("Nama Unit", 'JUMLAH_PEMANGKU_JABATAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input type="hidden" name="Unit_Kerja_ID" id="KODE_UNIT_KERJA" value="<?php echo $unit_kerja->ID ? trim($unit_kerja->ID) : ""; ?>">
                    <input type="text" class="form-control" readonly name="NAMA_UNIT_KERJA" id="NAMA_UNIT_KERJA" value="<?php echo $unit_kerja->NAMA_UNOR ? $unit_kerja->NAMA_UNOR : ""; ?>">
                    <span class='help-inline'><?php echo form_error('JUMLAH_PEMANGKU_JABATAN'); ?></span>
                </div>
            </div>
            <?php if($this->auth->has_permission("Bezzeting.Pilihsatker.View")){ ?>
                <div class="control-group<?php echo form_error('JUMLAH_PEMANGKU_JABATAN') ? ' error' : ''; ?> col-sm-2">
                    <?php echo form_label("&nbsp;", 'JUMLAH_PEMANGKU_JABATAN', array('class' => 'control-label')); ?>
                    <div class='controls'>
                        <a href="<?php echo base_url(); ?>/pegawai/manage_unitkerja/pilihunitkerja" class="btn btn-primary show-modal" tooltip="Pilih Struktur"><i class="fa fa-eye"> </i> Pilih Unit</a>
                    </div>
                </div>
            <?php } ?>
	</div>
	<div class="box-body" id="divcontent">
	
	</div>
</div>
<script>
    showdata();
	$("#NAMA_UNIT_KERJA").focus(function(){
        $('.btn').focus();
        showdata();
	 }); 
	//alert('<?php echo site_url("admin/masters/unitkerja/ajaxkodeinternal");?>');
    $("#Unit_Kerja_ID").select2({
        placeholder: 'Cari Unit Kerja...',
        width: '100%',
        minimumInputLength: 3,
        allowClear: true,
        ajax: {
            url: '<?php echo site_url("pegawai/manage_unitkerja/ajaxkodeinternal");?>',
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
    function showdata(){
    	 var ValUnit_Kerja_ID = $("#KODE_UNIT_KERJA").val();
		 var json_url = "<?php echo base_url() ?>admin/reports/bezzeting/viewdata?unitkerja="+ValUnit_Kerja_ID;
		 $.ajax({    type: "GET",
			url: json_url,
			data: "unitkerja="+ValUnit_Kerja_ID,
			success: function(data){ 
				$('#divcontent').html(data);
			}});
		 return false; 
	  return false;
    }
</script>