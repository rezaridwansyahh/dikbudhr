
<div class='messages'>
</div>
<?php
$can_delete	= $this->auth->has_permission('Petajabatan.Reports.Delete');
$id = isset($kuota_jabatan->ID) ? $kuota_jabatan->ID : '';
?>
<div class='admin-box box box-warning'>
    <?php echo form_open($this->uri->uri_string(), 'id="frm"'); ?>
		 <div class="box-body">
		 	<div class="control-group col-sm-12">
                <?php echo form_label("Peraturan", 'PERATURAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select id="PERATURAN" name="PERATURAN" width="100%" class=" col-md-10 form-control">
                        <option value="">-- Silahkan Pilih --</option>
                        <?php if (isset($record_permens) && is_array($record_permens) && count($record_permens)):?>
                        <?php foreach($record_permens as $record):?>
                            <option value="<?php echo trim($record->permen);?>" <?php echo $kuota_jabatan->PERATURAN == trim($record->permen) ? "selected" : ""; ?> ><?php echo trim($record->permen); ?></option>
                            <?php endforeach;?>
                        <?php endif;?>
                    </select>
                </div>
            </div>
            <div class="control-group<?php echo form_error('JUMLAH_PEMANGKU_JABATAN') ? ' error' : ''; ?> col-sm-10">
                <?php echo form_label("Nama Struktur", 'JUMLAH_PEMANGKU_JABATAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                	<input id='ID' type='hidden' class="form-control" name='ID' maxlength='10' value="<?php echo isset($id) ? $id : ''; ?>" />
                    <input type="hidden" name="KODE_UNIT_KERJA" id="KODE_UNIT_KERJA" value="<?php echo set_value('KODE_UNIT_KERJA', isset($kuota_jabatan->KODE_UNIT_KERJA) ? $kuota_jabatan->KODE_UNIT_KERJA : ''); ?>">
                    <input type="text" class="form-control" readonly name="NAMA_UNIT_KERJA" id="NAMA_UNIT_KERJA" value="<?php echo $data_unit->NAMA_UNOR ? $data_unit->NAMA_UNOR : ""; ?>">
                </div>
            </div>
            <div class="control-group<?php echo form_error('JUMLAH_PEMANGKU_JABATAN') ? ' error' : ''; ?> col-sm-2">
                <?php echo form_label("&nbsp;", 'JUMLAH_PEMANGKU_JABATAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <a href="<?php echo base_url(); ?>/pegawai/manage_unitkerja/pilihunitkerja" class="btn btn-primary show-modal" tooltip="Pilih Struktur">Pilih Unit</a>
                </div>
            </div>
            <div class="control-group<?php echo form_error('ID_JABATAN') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Jabatan", 'ID_JABATAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                	
                	<select name="ID_JABATAN" id="ID_JABATAN" class="form-control select2">
						<option value="">-- Silahkan Pilih --</option>
						<?php if (isset($jabatans) && is_array($jabatans) && count($jabatans)):?>
						<?php foreach($jabatans as $record):?>
							<option value="<?php echo $record->KODE_JABATAN?>" <?php if(isset($kuota_jabatan->ID_JABATAN))  echo  (trim($kuota_jabatan->ID_JABATAN)==trim($record->KODE_JABATAN)) ? "selected" : ""; ?>><?php echo $record->NAMA_JABATAN; ?></option>
							<?php endforeach;?>
						<?php endif;?>
					</select>
                    <span class='help-inline'><?php echo form_error('ID_JABATAN'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('JUMLAH_PEMANGKU_JABATAN') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Jumlah Kuota", 'JUMLAH_PEMANGKU_JABATAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='JUMLAH_PEMANGKU_JABATAN' type='number' class="form-control" name='JUMLAH_PEMANGKU_JABATAN' maxlength='255' value="<?php echo set_value('JUMLAH_PEMANGKU_JABATAN', isset($kuota_jabatan->JUMLAH_PEMANGKU_JABATAN) ? $kuota_jabatan->JUMLAH_PEMANGKU_JABATAN : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('JUMLAH_PEMANGKU_JABATAN'); ?></span>
                </div>
            </div>
        </div>
        <div class="box-footer">
            <input type='submit' name='save' id="btnsave" class='btn btn-primary' value="Simpan" />
            <?php if($id!="" and $can_delete){ ?>
            <?php echo lang('bf_or'); ?>
            <a href="#" id="btn-hapus" class='btn btncancel btn-warning'>Cancel</a>
            <?php } ?>
            <a href="http://localhost/dikbudhrd/pegawai/manage_unitkerja/pilihunitkerja" class="modal">test</a>
        </div>
    <?php echo form_close(); ?>
</div>
 
<script>
	$("#btnsave").click(function(){
		submitdata();
		return false; 
	});	
	$(".btncancel").click(function(){
		$("#modal-global").modal("hide");
	});	
	function submitdata(){
		var json_url = "<?php echo base_url() ?>admin/masters/petajabatan/savekuota";
		//alert(json_url);
		 $.ajax({    
		 	type: "POST",
			url: json_url,
			data: $("#frm").serialize(),
            dataType: "json",
			success: function(data){ 
				//alert(data.success);
                if(data.success){
                    swal("Pemberitahuan!", data.msg, "success");
                    window.history.back();
                    // $table.ajax.reload(null,true);
					$("#modal-global").modal("hide");

                }
                else {
                    $(".messages").empty().append(data.msg);
                }
			}});
		return false; 
	} 

	$("#ID_JABATAN").select2({
	    placeholder: 'Cari Nama Jabatan...',
	    width: '100%',
	    minimumInputLength: 0,
	    allowClear: true,
	    ajax: {
	        url: '<?php echo site_url("admin/masters/jabatan/ajax");?>',
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
</script>