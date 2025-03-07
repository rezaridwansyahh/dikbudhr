<script src="<?php echo base_url(); ?>themes/admin/js/jqueryvalidation/jquery.validate.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/select2/select2.min.css">
<!-- daterange picker -->
<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/daterangepicker/daterangepicker.css">
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/datepicker/datepicker3.css">

<?php

if (validation_errors()) :
?>
<div class='alert alert-block alert-error fade in'>
    <a class='close' data-dismiss='alert'>&times;</a>
    <h4 class='alert-heading'>
        <?php echo lang('pegawai_errors_message'); ?>
    </h4>
    <?php echo validation_errors(); ?>
</div>
<?php
endif;

$kode = isset($pegawai->ID) ? $pegawai->ID : '';
//die($kode."asd");
?>
<div class="callout callout-info">
   <h4>Perhatian</h4>
   <p><b>Kolom KODE</b> Silahkan isi dengan nomor NIK</p>
 </div>
<div class='messages'></div>
<div class='box box-primary'>
	<div class="box-body">
    <?php echo form_open($this->uri->uri_string(),'id="frm"'); ?>
        <fieldset>
            <legend>Profile</legend>
			 <div class="control-group<?php echo form_error('PNS_ID') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label('KODE', 'PNS_ID', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='id' type='hidden' class="form-control" name='id' maxlength='10' value="<?php echo isset($kode) ? trim($kode) : ""; ?>" />
                    <input id='status_pegawai' type='hidden' class="form-control" name='status_pegawai' maxlength='1' value="3" />
                    <input id='PNS_ID' type='text' required class="form-control" name='PNS_ID' maxlength='32' value="<?php echo set_value('PNS_ID', isset($pegawai->PNS_ID) ? $pegawai->PNS_ID : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('PNS_ID'); ?></span>
                </div>
            </div>
			 <div class="control-group<?php echo form_error('GELAR_DEPAN') ? ' error' : ''; ?> col-sm-2">
                <?php echo form_label(lang('pegawai_field_GELAR_DEPAN'), 'GELAR_DEPAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='GELAR_DEPAN' type='text' class="form-control" name='GELAR_DEPAN' maxlength='11' value="<?php echo set_value('GELAR_DEPAN', isset($pegawai->GELAR_DEPAN) ? $pegawai->GELAR_DEPAN : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('GELAR_DEPAN'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('NAMA') ? ' error' : ''; ?> col-sm-7">
                <?php echo form_label(lang('pegawai_field_NAMA'), 'NAMA', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='NAMA' type='text' class="form-control" required name='NAMA' maxlength='50' value="<?php echo set_value('NAMA', isset($pegawai->NAMA) ? $pegawai->NAMA : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('NAMA'); ?></span>
                </div>
            </div>

           

            <div class="control-group<?php echo form_error('GELAR_BELAKANG') ? ' error' : ''; ?> col-sm-3">
                <?php echo form_label(lang('pegawai_field_GELAR_BELAKANG'), 'GELAR_BELAKANG', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='GELAR_BELAKANG' type='text' class="form-control" name='GELAR_BELAKANG' maxlength='11' value="<?php echo set_value('GELAR_BELAKANG', isset($pegawai->GELAR_BELAKANG) ? $pegawai->GELAR_BELAKANG : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('GELAR_BELAKANG'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('TEMPAT_LAHIR_ID') ? ' error' : ''; ?> col-sm-9">
                <?php echo form_label("Tempat Lahir", 'TEMPAT_LAHIR_ID', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='TEMPAT_LAHIR_ID' type='text' required class="form-control" name='TEMPAT_LAHIR_ID' maxlength='11' value="<?php echo set_value('TEMPAT_LAHIR_ID', isset($pegawai->TEMPAT_LAHIR_ID) ? $pegawai->TEMPAT_LAHIR_ID : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('TEMPAT_LAHIR_ID'); ?></span>
                </div>
            </div>
			<div class="control-group col-sm-3">
				<label for="inputNAMA" class="control-label">Tgl Lahir</label>
				<div class="input-group date">
				  <div class="input-group-addon">
					<i class="fa fa-calendar"></i>
				  </div>
					<input type='text' class="form-control pull-right datepicker" required name='TGL_LAHIR'  value="<?php echo set_value('TGL_LAHIR', isset($pegawai->TGL_LAHIR) ? $pegawai->TGL_LAHIR : ''); ?>" />
					<span class='help-inline'><?php echo form_error('TGL_LAHIR'); ?></span>
				</div>
			</div> 
            <div class="control-group<?php echo form_error('JENIS_KELAMIN') ? ' error' : ''; ?> col-sm-4">
                <?php echo form_label(lang('pegawai_field_JENIS_KELAMIN'), 'JENIS_KELAMIN', array('class' => 'control-label')); ?>
                <div class='controls'>
                	<select class="validate[required] text-input form-control" name="JENIS_KELAMIN" id="JENIS_KELAMIN" class="chosen-select-deselect">
						<option value="">-- Pilih  --</option>
						<option value="M" <?php if(isset($pegawai->JENIS_KELAMIN))  echo  ("M"==$pegawai->JENIS_KELAMIN) ? "selected" : ""; ?>> Laki-laki</option>
						<option value="F" <?php if(isset($pegawai->JENIS_KELAMIN))  echo  ("F"==$pegawai->JENIS_KELAMIN) ? "selected" : ""; ?>> Perempuan</option>
					</select>
                    <span class='help-inline'><?php echo form_error('JENIS_KELAMIN'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('AGAMA_ID') ? ' error' : ''; ?> col-sm-4">
                <?php echo form_label("Agama", 'AGAMA_ID', array('class' => 'control-label')); ?>
                <div class='controls'>
                	<select name="AGAMA_ID" id="AGAMA_ID" class="form-control">
						<option value="">-- Silahkan Pilih --</option>
						<?php if (isset($agamas) && is_array($agamas) && count($agamas)):?>
						<?php foreach($agamas as $record):?>
							<option value="<?php echo $record->ID?>" <?php if(isset($pegawai->AGAMA_ID))  echo  ($pegawai->AGAMA_ID==$record->ID) ? "selected" : ""; ?>><?php echo $record->NAMA; ?></option>
							<?php endforeach;?>
						<?php endif;?>
					</select>
                    <span class='help-inline'><?php echo form_error('AGAMA_ID'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('GOLONGAN_DARAH') ? ' error' : ''; ?> col-sm-4">
                <?php echo form_label(lang('pegawai_field_GOLONGAN_DARAH'), 'GOLONGAN_DARAH', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select class="validate[required] text-input form-control" name="GOLONGAN_DARAH" id="GOLONGAN_DARAH" class="chosen-select-deselect">
                        <option value="">-- Pilih  --</option>
                        <option value="O" <?php if(isset($pegawai->GOLONGAN_DARAH))  echo  ("O"==$pegawai->GOLONGAN_DARAH) ? "selected" : ""; ?>>O</option>
                        <option value="A" <?php if(isset($pegawai->GOLONGAN_DARAH))  echo  ("A"==$pegawai->GOLONGAN_DARAH) ? "selected" : ""; ?>>A</option>
                        <option value="B" <?php if(isset($pegawai->GOLONGAN_DARAH))  echo  ("B"==$pegawai->GOLONGAN_DARAH) ? "selected" : ""; ?>>B</option>
                        <option value="AB" <?php if(isset($pegawai->GOLONGAN_DARAH))  echo  ("AB"==$pegawai->GOLONGAN_DARAH) ? "selected" : ""; ?>>AB</option>
                    </select>
                    <span class='help-inline'><?php echo form_error('GOLONGAN_DARAH'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('NOMOR_DARURAT') ? ' error' : ''; ?> col-sm-6">
                <?php echo form_label(lang('pegawai_field_NOMOR_DARURAT'), 'NOMOR_DARURAT', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='NOMOR_DARURAT' type='text' class="form-control" name='NOMOR_DARURAT' maxlength='32' value="<?php echo set_value('NOMOR_DARURAT', isset($pegawai->NOMOR_DARURAT) ? $pegawai->NOMOR_DARURAT : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('NOMOR_DARURAT'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('NOMOR_HP') ? ' error' : ''; ?> col-sm-6">
                <?php echo form_label(lang('pegawai_field_NOMOR_HP'), 'NOMOR_HP', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='NOMOR_HP' type='text' class="form-control" name='NOMOR_HP' maxlength='32' value="<?php echo set_value('NOMOR_HP', isset($pegawai->NOMOR_HP) ? $pegawai->NOMOR_HP : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('NOMOR_HP'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('EMAIL') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label(lang('pegawai_field_EMAIL'), 'EMAIL', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='EMAIL' type='text' class="form-control" name='EMAIL' maxlength='200' value="<?php echo set_value('EMAIL', isset($pegawai->EMAIL) ? $pegawai->EMAIL : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('EMAIL'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('ALAMAT') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label(lang('pegawai_field_ALAMAT'), 'ALAMAT', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <?php echo form_textarea(array('name' => 'ALAMAT', 'id' => 'ALAMAT', 'rows' => '5', 'cols' => '80', 'value' => set_value('ALAMAT', isset($pegawai->ALAMAT) ? $pegawai->ALAMAT : ''))); ?>
                    <span class='help-inline'><?php echo form_error('ALAMAT'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('NPWP') ? ' error' : ''; ?> col-sm-6">
                <?php echo form_label(lang('pegawai_field_NPWP'), 'NPWP', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='NPWP' type='text' class="form-control" name='NPWP' maxlength='25' value="<?php echo set_value('NPWP', isset($pegawai->NPWP) ? $pegawai->NPWP : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('NPWP'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('BPJS') ? ' error' : ''; ?> col-sm-6">
                <?php echo form_label(lang('pegawai_field_BPJS'), 'BPJS', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='BPJS' type='text' class="form-control" name='BPJS' maxlength='25' value="<?php echo set_value('BPJS', isset($pegawai->BPJS) ? $pegawai->BPJS : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('BPJS'); ?></span>
                </div>
            </div>  
            <div class="control-group<?php echo form_error('TK_PENDIDIKAN') ? ' error' : ''; ?> col-sm-3">
                <?php echo form_label("TINGKAT PENDIDIKAN", 'TK_PENDIDIKAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select name="TK_PENDIDIKAN" id="TK_PENDIDIKAN" class="form-control select2">
                        <option value="">-- Silahkan Pilih --</option>
                        <?php if (isset($tkpendidikans) && is_array($tkpendidikans) && count($tkpendidikans)):?>
                        <?php foreach($tkpendidikans as $record):?>
                            <option value="<?php echo $record->ID?>" <?php if(isset($pegawai->TK_PENDIDIKAN))  echo  (TRIM($pegawai->TK_PENDIDIKAN)==$record->ID) ? "selected" : ""; ?>><?php echo $record->NAMA; ?></option>
                            <?php endforeach;?>
                        <?php endif;?>
                    </select>
                    <span class='help-inline'><?php echo form_error('TK_PENDIDIKAN'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('PENDIDIKAN_ID') ? ' error' : ''; ?> col-sm-6">
                <?php echo form_label(lang('pegawai_field_PENDIDIKAN_ID'), 'PENDIDIKAN_ID', array('class' => 'control-label')); ?>
                <div class='controls'>
                	<select name="PENDIDIKAN_ID" id="PENDIDIKAN_ID" class="form-control select2">
						<option value="">-- Silahkan Pilih --</option>
						<?php if (isset($pendidikans) && is_array($pendidikans) && count($pendidikans)):?>
						<?php foreach($pendidikans as $record):?>
							<option value="<?php echo $record->ID?>" <?php if(isset($pegawai->PENDIDIKAN_ID))  echo  ($pegawai->PENDIDIKAN_ID==$record->ID) ? "selected" : ""; ?>><?php echo $record->NAMA; ?></option>
							<?php endforeach;?>
						<?php endif;?>
					</select>
                    <span class='help-inline'><?php echo form_error('PENDIDIKAN_ID'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('TAHUN_LULUS') ? ' error' : ''; ?> col-sm-3">
                <?php echo form_label(lang('pegawai_field_TAHUN_LULUS'), 'TAHUN_LULUS', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='TAHUN_LULUS' type='text' class="form-control" name='TAHUN_LULUS' maxlength='4' value="<?php echo set_value('TAHUN_LULUS', isset($pegawai->TAHUN_LULUS) ? $pegawai->TAHUN_LULUS : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('TAHUN_LULUS'); ?></span>
                </div>
            </div>
 

            <div class="control-group<?php echo form_error('LOKASI_KERJA_ID') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label(lang('pegawai_field_LOKASI_KERJA_ID'), 'LOKASI_KERJA_ID', array('class' => 'control-label')); ?>
                <div class='controls'>
                	<select name="LOKASI_KERJA_ID" id="LOKASI_KERJA_ID" class="form-control select2">
                        <?php 
                            if($selectedLokasiPegawai){
                                echo "<option selected value='".$selectedLokasiPegawai->ID."'>".$selectedLokasiPegawai->NAMA."</option>";
                            }
                        ?>
					</select>
                    <span class='help-inline'><?php echo form_error('LOKASI_KERJA_ID'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('UNOR_INDUK_ID') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Unor Induk", 'UNOR_INDUK_ID', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select name="UNOR_INDUK_ID" id="UNOR_INDUK_ID" class="form-control select2">
                        <?php 
                            if($selectedUnorindukid){
                                echo "<option selected value='".$selectedUnorindukid->ID."'>".$selectedUnorindukid->NAMA_ESELON_II."</option>";
                            }
                        ?>
                    </select>
                    <span class='help-inline'><?php echo form_error('UNOR_INDUK_ID'); ?></span>
                </div>
            </div> 
            <div class="control-group<?php echo form_error('Unor_ID') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Unor", 'Unor_ID', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select name="UNOR_ID" id="Unor_ID" class="form-control select2">
                        <?php 
                            if($selectedUnorid){
                                echo "<option selected value='".$selectedUnorid->ID."'>".$selectedUnorid->NAMA_ESELON_II."</option>";
                            }
                        ?>
					</select>
                    <span class='help-inline'><?php echo form_error('Unor_ID'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('JABATAN_PPNPN') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("JABATAN PPNPN", 'JABATAN_PPNPN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='JABATAN_PPNPN' type='text' class="form-control" name='JABATAN_PPNPN' maxlength='50' value="<?php echo set_value('JABATAN_PPNPN', isset($pegawai->JABATAN_PPNPN) ? $pegawai->JABATAN_PPNPN : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('JABATAN_PPNPN'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('JABATAN_INSTANSI_REAL_ID') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("JABATAN REAL", 'JABATAN_INSTANSI_REAL_ID', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select name="JABATAN_REAL_ID" id="JABATAN_INSTANSI_REAL_ID" class="form-control select2">
                        
						
                    </select>
                    <span class='help-inline'><?php echo form_error('UNOR_INDUK_ID'); ?></span>
                </div>
            </div> 
        </fieldset>
        </div>
  		<div class="box-footer">
            <div class='messages'></div>
            <input type='submit' name='save' id="btnsave" class='btn btn-primary' value="Simpan" />
            <?php echo lang('bf_or'); ?>
            <?php echo anchor(SITE_AREA . '/kepegawaian/pegawai/ppnpn', lang('pegawai_cancel'), 'class="btn btn-warning"'); ?>
            
        </div>
    <?php echo form_close(); ?>
</div>
<script src="<?php echo base_url(); ?>themes/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
<script>
    //Date picker
    $('.datepicker').datepicker({
      autoclose: true,format: 'yyyy-mm-dd'
    });
</script>
<!-- Select2 -->
<script src="<?php echo base_url(); ?>themes/admin/plugins/select2/select2.full.min.js"></script>

<script>
	 $(".select2").select2();
</script>
<script>
$("#frm").validate({
  submitHandler: function(form) {
    $("#btnsave").val('Menyimpan data......').attr('disabled', true).addClass('bt-hud').unbind('click');
    submitdata();
  }
});
function submitdata(){
        var json_url = "<?php echo base_url() ?>admin/kepegawaian/pegawai/createdata";
         $.ajax({    
            type: "POST",
            url: json_url,
            data: $("#frm").serialize(),
            dataType: "json",
            success: function(data){ 
                if(data.success){
                   swal("Pemberitahuan!", data.msg, "success");
                   url = "<?php echo base_url(); ?>admin/kepegawaian/pegawai/ppnpn";
                    $(location).attr("href", url);
                }
                else {
                    $("#btnsave").val('Simpan').attr('disabled', false).addClass('bt-hud').unbind('click');
                    $(".messages").empty().append(data.msg);
                }
            }});
        return false; 
    }

    $('#TK_PENDIDIKAN').change(function() {
        var valuetingkat = $('#TK_PENDIDIKAN').val();
            $("#PENDIDIKAN_ID").empty().append("<option>loading...</option>"); //show loading...
             
            var json_url = "<?php echo base_url(); ?>pegawai/pendidikan/getbytingkat?tingkat=" + encodeURIComponent(valuetingkat);
            //alert(json_url);
            $.getJSON(json_url,function(data){
                $("#PENDIDIKAN_ID").empty(); 
                if(data==""){
                    $("#PENDIDIKAN_ID").append("<option value=\"\">Silahkan Pilih </option>");
                }
                else{
                    $("#PENDIDIKAN_ID").append("<option value=\"\">Silahkan Pilih</option>");
                    for(i=0; i<data.id.length; i++){
                        $("#PENDIDIKAN_ID").append("<option value=\"" + data.id[i]  + "\">" + data.nama[i] +"</option>");
                    }
                }
                
            });
            $("#PENDIDIKAN_ID").select2("updateResults");
            return false;
    });
    
    $("#LOKASI_KERJA_ID").select2({
        placeholder: 'Cari Lokasi Kerja...',
        width: '100%',
        minimumInputLength: 3,
        allowClear: true,
        ajax: {
            url: '<?php echo site_url("admin/lokasi/pegawai/ajax");?>',
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
	 $("#Unor_ID").select2({
        placeholder: 'Cari Unit Kerja...',
        width: '100%',
        minimumInputLength: 3,
        allowClear: true,
        ajax: {
            url: '<?php echo site_url("pegawai/manage_unitkerja/ajax");?>',
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

    $("#JABATAN_INSTANSI_REAL_ID").select2({
        placeholder: 'Cari Jabatan ...',
        width: '100%',
        minimumInputLength: 3,
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

    $("#UNOR_INDUK_ID").select2({
        placeholder: 'Cari Unit Kerja...',
        width: '100%',
        minimumInputLength: 3,
        allowClear: true,
        ajax: {
            url: '<?php echo site_url("pegawai/manage_unitkerja/ajax");?>',
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
