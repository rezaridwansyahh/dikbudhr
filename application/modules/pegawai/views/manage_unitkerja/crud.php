<?php 
    $id = isset($detail_riwayat->ID) ? $detail_riwayat->ID : '';
?>
<div class='box box-warning' id="form-pendidikan-add">
	<div class="box-body">
        <div class="messages">
        </div>
    <?php echo form_open($this->uri->uri_string(), 'id="frm"'); ?>
        <fieldset>
			<!-- 
			<input id='PARENT_ID' type='hidden' class="form-control" name='PARENT_ID'  value="<?php echo set_value('PARENT_ID', isset($PARENT_ID) ? $PARENT_ID : ''); ?>" />
			-->
			 <div class="control-group<?php echo form_error('PARENT_ID') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("PARENT_ID", 'PARENT UNOR', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select id="PARENT_ID" name="PARENT_ID" width="100%" class="form-control select2">
                        <?php
                        if($selectedParentUnor){
                            echo "<option selected value='".$selectedParentUnor->ID."'>".$selectedParentUnor->NAMA_UNOR."</option>";
                        }
                        ?>
                    </select>
                    <span class='help-inline'><?php echo form_error('PARENT_ID'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('ID') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("ID (ID dari BKN)", 'ID', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='ID' type='text' class="form-control" name='ID'  value="<?php echo set_value('ID', isset($data->ID) ? $data->ID : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('ID'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('NAMA_UNOR') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("NAMA UNOR", 'NAMA_UNOR', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='NAMA_UNOR' type='text' class="form-control" name='NAMA_UNOR'  value="<?php echo set_value('NAMA_UNOR', isset($data->NAMA_UNOR) ? $data->NAMA_UNOR : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('NAMA_UNOR'); ?></span>
                </div>
            </div>
            <!--ADD BY BANA-->
             <div class="control-group<?php echo form_error('KODE_INTERNAL') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("KODE INTERNAL", 'KODE_INTERNAL', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='KODE_INTERNAL' type='text' class="form-control" name='KODE_INTERNAL'  value="<?php echo set_value('KODE_INTERNAL', isset($data->KODE_INTERNAL) ? $data->KODE_INTERNAL : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('KODE_INTERNAL'); ?></span>
                </div>
            </div>
            <!--END BY BANA-->
            <div class="control-group<?php echo form_error('NAMA_JABATAN') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("NAMA JABATAN", 'NAMA JABATAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='NAMA_JABATAN' type='text' class="form-control" name='NAMA_JABATAN'  value="<?php echo set_value('NAMA_JABATAN', isset($data->NAMA_JABATAN) ? $data->NAMA_JABATAN : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('NAMA_JABATAN'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('PEMIMPIN_PNS_ID') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("NAMA PEJABAT", 'NAMA PEJABAT', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <!--<input id='NAMA_PEJABAT' type='text' class="form-control" name='NAMA_PEJABAT'  
                    value="<?php //echo set_value('NAMA_PEJABAT', isset($data->NAMA_PEJABAT) ? $data->NAMA_PEJABAT : ''); ?>" />
                    <span class='help-inline'><?php //echo form_error('NAMA_PEJABAT'); ?></span>-->
                    <select id="PEMIMPIN_PNS_ID" name="PEMIMPIN_PNS_ID" width="100%" class="form-control select2">
                        <?php
                        if($selectedAtasanLangsung){
                            echo "<option selected value='".$selectedAtasanLangsung->PNS_ID."'>".$selectedAtasanLangsung->NAMA."</option>";
                        }
                        ?>
                    </select>
                    <span class='help-inline'><?php echo form_error('PEMIMPIN_PNS_ID'); ?></span>
                </div>
            </div>
           <div class="control-group<?php echo form_error('IS_SATKER') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("SATKER ?", 'SATKER ?', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='IS_SATKER' type='checkbox' name='IS_SATKER'  <?php echo set_value('IS_SATKER', isset($data->IS_SATKER)&& $data->IS_SATKER=='1'? "CHECKED" : ''); ?> />
                    <span class='help-inline'><?php echo form_error('IS_SATKER'); ?></span>
                </div>
            </div>

	    <!--Add BY BANA-->

		<div class="control-group<?php echo form_error('UNOR_INDUK') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("SATUAN KERJA", 'SATUAN KERJA', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select id="UNOR_INDUK" name="UNOR_INDUK" width="100%" class="form-control select2">
                        <?php
                        if($selectedUnorInduk){
                            echo "<option selected value='".$selectedUnorInduk->ID."'>".$selectedUnorInduk->NAMA_UNOR."</option>";
                        }
                        ?>
                    </select>
                    <span class='help-inline'><?php echo form_error('UNOR_INDUK'); ?></span>
                </div>
            </div>

	    <!--END--->
			<div class="control-group col-sm-3">
				<label for="inputNAMA" class="control-label">Expired Date</label>
				<div class="input-group date">
				  <div class="input-group-addon">
					<i class="fa fa-calendar"></i>
				  </div>
					<input type='text' class="form-control pull-right datepicker" name='EXPIRED_DATE'  value="<?php echo set_value('EXPIRED_DATE', isset($data->EXPIRED_DATE) ? $data->EXPIRED_DATE : ''); ?>" />
					<span class='help-inline'><?php echo form_error('EXPIRED_DATE'); ?></span>
				</div>
			</div> 
			<div class="control-group<?php echo form_error('KETERANGAN') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("KETERANGAN", 'KETERANGAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='KETERANGAN' type='text' class="form-control" name='KETERANGAN'  value="<?php echo set_value('KETERANGAN', isset($data->KETERANGAN) ? $data->KETERANGAN : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('KETERANGAN'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('Singkatan') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Singkatan", 'Singkatan', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='ABBREVIATION' type='text' class="form-control" name='ABBREVIATION'  value="<?php echo set_value('ABBREVIATION', isset($data->ABBREVIATION) ? $data->ABBREVIATION : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('ABBREVIATION'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('WAKTU') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("WAKTU", 'WAKTU', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select id="WAKTU" name="WAKTU" style="width:100%" class="form-control select2">
                        <option value="">Silahkan Pilih</option>
                            <option <?php echo (isset($data->WAKTU) and $data->WAKTU == "WIB") ? "selected" : ""; ?> value="WIB">WIB</option>
                            <option <?php echo (isset($data->WAKTU) and $data->WAKTU == "WITA") ? "selected" : ""; ?> value="WITA">WITA</option>
                            <option <?php echo (isset($data->WAKTU) and $data->WAKTU == "WIT") ? "selected" : ""; ?> value="WIT">WIT</option>
                        </select>
                    <span class='help-inline'><?php echo form_error('PARENT_ID'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('JENIS') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("JENIS SATKER", 'JENIS', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select id="JENIS_SATKER" name="JENIS_SATKER" style="width:100%" class="form-control select2">
                        <option value="">Silahkan Pilih</option>
                            <?php 
                            foreach($jenis_satkers as $row){
                            ?>
                            <option <?php echo (isset($data->JENIS_SATKER) and trim($row->nama_jenis_satker) == trim($data->JENIS_SATKER)) ? "selected" : ""; ?> value="<?php echo trim($row->nama_jenis_satker); ?>"><?php echo trim($row->nama_jenis_satker); ?></option>
                            <?php
                            }
                            ?>
                        </select>
                </div>
            </div>
            <div class="control-group<?php echo form_error('Peraturan') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Peraturan", 'Peraturan', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select id="PERATURAN" name="PERATURAN" style="width:100%" class="form-control select2">
                        <option value="">Silahkan Pilih</option>
                            <?php 
                            foreach($peraturan_otks as $row){
                            ?>
                            <option <?php echo (isset($data->PERATURAN) and trim($row->id_peraturan) == trim($data->PERATURAN)) ? "selected" : ""; ?> value="<?php echo trim($row->id_peraturan); ?>"><?php echo trim($row->no_peraturan); ?></option>
                            <?php
                            }
                            ?>
                        </select>
                </div>
            </div>
        </fieldset>
        </div>
  		<div class="box-footer">
            <input type='submit' name='save' id="btnsave" class='btn btn-primary' value="Simpan Data" /> 
        </div>
    <?php echo form_close(); ?>
</div>
<script src="<?php echo base_url(); ?>themes/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
<script>
    var form = $("#form-pendidikan-add");
    //Date picker
    $('.datepicker').datepicker({
      autoclose: true,format: 'yyyy-mm-dd'
    }).on("input change", function (e) {
        var date = $(this).datepicker('getDate');
        if(date)$("[name=TAHUN_LULUS]",form).val(date.getFullYear());
    });
</script>
<script>
	 $(".select2").select2({
        width: '100%',
        dropdownParent: $("#modal-custom-global")
     });
    $.fn.modal.Constructor.prototype.enforceFocus = function() {};
</script>
<script>
	$("#btnsave").click(function(){
		submitdata();
		return false; 
	});	
	$("#frma").submit(function(){
		submitdata();
		return false; 
	});	
	function submitdata(){
		
		var json_url = "<?php echo base_url() ?>pegawai/manage_unitkerja/save";
		 $.ajax({    
		 	type: "POST",
			url: json_url,
			data: $("#frm").serialize(),
            dataType: "json",
			success: function(data){ 
                if(data.success){
                    swal("Pemberitahuan!", data.msg, "success");
                    $("#modal-custom-global").trigger("sukses-crud-unitkerja");
					$("#modal-custom-global").modal("hide");
                    $table.ajax.reload(null,true);
                }
                else {
                    $("#form-pendidikan-add .messages").empty().append(data.msg);
                }
			}});
		return false; 
	}
    
    $("#PEMIMPIN_PNS_ID").select2({
        placeholder: 'Cari Nama.....',
        width: '100%',
        minimumInputLength: 3,
        allowClear: true,
        ajax: {
            url: '<?php echo site_url("pegawai/kepegawaian/ajax_nama_pejabat");?>',
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
     $("#PARENT_ID").select2({
        placeholder: 'Cari Parent Unit.....',
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
    $("#UNOR_INDUK").select2({
        placeholder: 'Cari Unit Kerja...',
        width: '100%',
        minimumInputLength: 3,
        allowClear: true,
        ajax: {
            url: '<?php echo site_url("pegawai/manage_unitkerja/ajaxall");?>',
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