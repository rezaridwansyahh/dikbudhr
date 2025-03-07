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

$id = isset($pegawai->ID) ? $pegawai->ID : '';

?>
<div class='messages'></div>
<div class='box box-primary'>

	<div class="box-body">
    <form role="form" action="#" id="frmprofile">
        <fieldset>
            <legend>Profile</legend>
			 <div class="control-group<?php echo form_error('PNS_ID') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label('PNS ID', 'PNS_ID', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='PNS_ID' type='text' class="form-control" name='PNS_ID' maxlength='32' value="<?php echo set_value('PNS_ID', isset($pegawai->PNS_ID) ? $pegawai->PNS_ID : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('PNS_ID'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('NIP_LAMA') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label(lang('pegawai_field_NIP_LAMA'), 'NIP_LAMA', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='NIP_LAMA' type='text' class="form-control" name='NIP_LAMA' maxlength='9' value="<?php echo set_value('NIP_LAMA', isset($pegawai->NIP_LAMA) ? $pegawai->NIP_LAMA : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('NIP_LAMA'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('NIP_BARU') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label(lang('pegawai_field_NIP_BARU'), 'NIP_BARU', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='NIP_BARU' type='text' class="form-control" name='NIP_BARU' maxlength='18' value="<?php echo set_value('NIP_BARU', isset($pegawai->NIP_BARU) ? $pegawai->NIP_BARU : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('NIP_BARU'); ?></span>
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
                    <input id='NAMA' type='text' class="form-control" name='NAMA' maxlength='50' value="<?php echo set_value('NAMA', isset($pegawai->NAMA) ? $pegawai->NAMA : ''); ?>" />
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
                    <select name="TEMPAT_LAHIR_ID" id="TEMPAT_LAHIR_ID" class="form-control select2">
                        <?php 
                            if($selectedLokasiPegawai){
                                echo "<option selected value='".$selectedLokasiPegawai->ID."'>".$selectedLokasiPegawai->NAMA."</option>";
                            }
                        ?>
                    </select>
                    <span class='help-inline'><?php echo form_error('TEMPAT_LAHIR_ID'); ?></span>
                </div>
            </div>
			<div class="control-group col-sm-3">
				<label for="inputNAMA" class="control-label">Tgl Lahir</label>
				<div class="input-group date">
				  <div class="input-group-addon">
					<i class="fa fa-calendar"></i>
				  </div>
					<input type='text' class="form-control pull-right datepicker" name='TGL_LAHIR'  value="<?php echo set_value('TGL_LAHIR', isset($pegawai->TGL_LAHIR) ? $pegawai->TGL_LAHIR : ''); ?>" />
					<span class='help-inline'><?php echo form_error('TGL_LAHIR'); ?></span>
				</div>
			</div> 
            <div class="control-group<?php echo form_error('JENIS_KELAMIN') ? ' error' : ''; ?> col-sm-6">
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

            <div class="control-group<?php echo form_error('AGAMA_ID') ? ' error' : ''; ?> col-sm-6">
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

            <div class="control-group<?php echo form_error('JENIS_KAWIN_ID') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label(lang('pegawai_field_JENIS_KAWIN_ID'), 'JENIS_KAWIN_ID', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='JENIS_KAWIN_ID' type='text' class="form-control" name='JENIS_KAWIN_ID' maxlength='32' value="<?php echo set_value('JENIS_KAWIN_ID', isset($pegawai->JENIS_KAWIN_ID) ? $pegawai->JENIS_KAWIN_ID : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('JENIS_KAWIN_ID'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('NIK') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label(lang('pegawai_field_NIK'), 'NIK', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='NIK' type='text' class="form-control" name='NIK' maxlength='32' value="<?php echo set_value('NIK', isset($pegawai->NIK) ? $pegawai->NIK : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('NIK'); ?></span>
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

            <div class="control-group<?php echo form_error('JENIS_PEGAWAI_ID') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Jenis Pegawai", 'JENIS_PEGAWAI_ID', array('class' => 'control-label')); ?>
                <div class='controls'>
                	<select name="JENIS_PEGAWAI_ID" id="JENIS_PEGAWAI_ID" class="form-control select2">
						<option value="">-- Silahkan Pilih --</option>
						<?php if (isset($JENIS_PEGAWAIs) && is_array($JENIS_PEGAWAIs) && count($JENIS_PEGAWAIs)):?>
						<?php foreach($JENIS_PEGAWAIs as $record):?>
							<option value="<?php echo $record->ID?>" <?php if(isset($pegawai->JENIS_PEGAWAI_ID))  echo  ($pegawai->JENIS_PEGAWAI_ID==$record->ID) ? "selected" : ""; ?>><?php echo $record->NAMA; ?></option>
							<?php endforeach;?>
						<?php endif;?>
					</select>
                    <span class='help-inline'><?php echo form_error('JENIS_PEGAWAI_ID'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('KEDUDUKAN_HUKUM_ID') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Kedudukan Hukum", 'KEDUDUKAN_HUKUM_ID', array('class' => 'control-label')); ?>
                <div class='controls'>
                	<select name="KEDUDUKAN_HUKUM_ID" id="KEDUDUKAN_HUKUM_ID" class="form-control select2">
						<option value="">-- Silahkan Pilih --</option>
						<?php if (isset($KEDUDUKAN_HUKUMs) && is_array($KEDUDUKAN_HUKUMs) && count($KEDUDUKAN_HUKUMs)):?>
						<?php foreach($KEDUDUKAN_HUKUMs as $record):?>
							<option value="<?php echo $record->ID?>" <?php if(isset($pegawai->KEDUDUKAN_HUKUM_ID))  echo  ($pegawai->KEDUDUKAN_HUKUM_ID==$record->ID) ? "selected" : ""; ?>><?php echo $record->NAMA; ?></option>
							<?php endforeach;?>
						<?php endif;?>
					</select>
                    <span class='help-inline'><?php echo form_error('KEDUDUKAN_HUKUM_ID'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('STATUS_CPNS_PNS') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Status CPNS/PNS", 'STATUS_CPNS_PNS', array('class' => 'control-label')); ?>
                <div class='controls'>
                	<select class="validate[required] text-input form-control" name="STATUS_CPNS_PNS" id="STATUS_CPNS_PNS" class="chosen-select-deselect">
						<option value="">-- Pilih  --</option>
						<option value="P" <?php if(isset($pegawai->STATUS_CPNS_PNS))  echo  ("P"==$pegawai->STATUS_CPNS_PNS) ? "selected" : ""; ?>> PNS</option>
						<option value="C" <?php if(isset($pegawai->STATUS_CPNS_PNS))  echo  ("C"==$pegawai->STATUS_CPNS_PNS) ? "selected" : ""; ?>> CPNS</option>
					</select>
                    <span class='help-inline'><?php echo form_error('STATUS_CPNS_PNS'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('KARTU_PEGAWAI') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label(lang('pegawai_field_KARTU_PEGAWAI'), 'KARTU_PEGAWAI', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='KARTU_PEGAWAI' type='text' class="form-control" name='KARTU_PEGAWAI' maxlength='11' value="<?php echo set_value('KARTU_PEGAWAI', isset($pegawai->KARTU_PEGAWAI) ? $pegawai->KARTU_PEGAWAI : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('KARTU_PEGAWAI'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('NOMOR_SK_CPNS') ? ' error' : ''; ?> col-sm-3">
                <?php echo form_label(lang('pegawai_field_NOMOR_SK_CPNS'), 'NOMOR_SK_CPNS', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='NOMOR_SK_CPNS' type='text' class="form-control" name='NOMOR_SK_CPNS' maxlength='50' value="<?php echo set_value('NOMOR_SK_CPNS', isset($pegawai->NOMOR_SK_CPNS) ? $pegawai->NOMOR_SK_CPNS : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('NOMOR_SK_CPNS'); ?></span>
                </div>
            </div>

           		
            <div class="control-group col-sm-3">
				<label for="inputNAMA" class="control-label">SK CPNS</label>
				<div class="input-group date">
				  <div class="input-group-addon">
					<i class="fa fa-calendar"></i>
				  </div>
				  	<input id='TGL_SK_CPNS' type='text' class="form-control pull-right datepicker" name='TGL_SK_CPNS'  value="<?php echo set_value('TGL_SK_CPNS', isset($pegawai->TGL_SK_CPNS) ? $pegawai->TGL_SK_CPNS : ''); ?>" />
					<span class='help-inline'><?php echo form_error('TGL_SK_CPNS'); ?></span>
				</div>
			</div> 
			<div class="control-group col-sm-3">
				<label for="inputNAMA" class="control-label">TMT CPNS</label>
				<div class="input-group date">
				  <div class="input-group-addon">
					<i class="fa fa-calendar"></i>
				  </div>
				  	<input id='TMT_CPNS' type='text' class="form-control pull-right datepicker" name='TMT_CPNS'  value="<?php echo set_value('TMT_CPNS', isset($pegawai->TMT_CPNS) ? $pegawai->TMT_CPNS : ''); ?>" />
					<span class='help-inline'><?php echo form_error('TMT_CPNS'); ?></span>
				</div>
			</div> 
          	<div class="control-group col-sm-3">
				<label for="inputNAMA" class="control-label">TMT PNS</label>
				<div class="input-group date">
				  <div class="input-group-addon">
					<i class="fa fa-calendar"></i>
				  </div>
				  	<input id='TMT_PNS' type='text' class="form-control pull-right datepicker" name='TMT_PNS'  value="<?php echo set_value('TMT_PNS', isset($pegawai->TMT_PNS) ? $pegawai->TMT_PNS : ''); ?>" />
					<span class='help-inline'><?php echo form_error('TMT_PNS'); ?></span>
				</div>
			</div>
            <div class="control-group<?php echo form_error('GOL_AWAL_ID') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Golongan Awal", 'GOL_AWAL_ID', array('class' => 'control-label')); ?>
                <div class='controls'>
                	<select name="GOL_AWAL_ID" id="GOL_AWAL_ID" class="form-control select2">
						<option value="">-- Silahkan Pilih --</option>
						<?php if (isset($golongans) && is_array($golongans) && count($golongans)):?>
						<?php foreach($golongans as $record):?>
							<option value="<?php echo $record->ID?>" <?php if(isset($pegawai->GOL_AWAL_ID))  echo  ($pegawai->GOL_AWAL_ID==$record->ID) ? "selected" : ""; ?>><?php echo $record->NAMA; ?> | <?php echo $record->NAMA_PANGKAT; ?></option>
							<?php endforeach;?>
						<?php endif;?>
					</select>
                    
                    <span class='help-inline'><?php echo form_error('GOL_AWAL_ID'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('GOL_ID') ? ' error' : ''; ?> col-sm-9">
                <?php echo form_label("Golongan", 'GOL_ID', array('class' => 'control-label')); ?>
                <div class='controls'>	
                	<select name="GOL_ID" id="GOL_ID" class="form-control select2">
						<option value="">-- Silahkan Pilih --</option>
						<?php if (isset($golongans) && is_array($golongans) && count($golongans)):?>
						<?php foreach($golongans as $record):?>
							<option value="<?php echo $record->ID?>" <?php if(isset($pegawai->GOL_ID))  echo  ($pegawai->GOL_ID==$record->ID) ? "selected" : ""; ?>><?php echo $record->NAMA; ?> | <?php echo $record->NAMA_PANGKAT; ?></option>
							<?php endforeach;?>
						<?php endif;?>
					</select>
                    <span class='help-inline'><?php echo form_error('GOL_ID'); ?></span>
                </div>
            </div>
			<div class="control-group col-sm-3">
				<label for="inputNAMA" class="control-label">TMT Golongan</label>
				<div class="input-group date">
				  <div class="input-group-addon">
					<i class="fa fa-calendar"></i>
				  </div>
					<input type='text' class="form-control pull-right datepicker" name='TMT_GOLONGAN'  value="<?php echo set_value('TMT_GOLONGAN', isset($pegawai->TMT_GOLONGAN) ? $pegawai->TMT_GOLONGAN : ''); ?>" />
					<span class='help-inline'><?php echo form_error('TMT_GOLONGAN'); ?></span>
				</div>
			</div> 
			
            <div class="control-group<?php echo form_error('MK_TAHUN') ? ' error' : ''; ?> col-sm-6">
                <?php echo form_label("Masa Kerja Tahun", 'MK_TAHUN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='MK_TAHUN' type='text' class="form-control" name='MK_TAHUN' maxlength='4' value="<?php echo set_value('MK_TAHUN', isset($pegawai->MK_TAHUN) ? $pegawai->MK_TAHUN : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('MK_TAHUN'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('MK_BULAN') ? ' error' : ''; ?> col-sm-6">
                <?php echo form_label("Masa kerja Bulan", 'MK_BULAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='MK_BULAN' type='text' class="form-control" name='MK_BULAN' maxlength='10' value="<?php echo set_value('MK_BULAN', isset($pegawai->MK_BULAN) ? $pegawai->MK_BULAN : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('MK_BULAN'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('JENIS_JABATAN_ID') ? ' error' : ''; ?> col-sm-3">
                <?php echo form_label(lang('pegawai_field_JENIS_JABATAN_ID'), 'JENIS_JABATAN_ID', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select name="JENIS_JABATAN_ID" id="JENIS_JABATAN_ID" class="form-control">
                        <option value="">-- Silahkan Pilih --</option>
                        <?php if (isset($jenis_jabatans) && is_array($jenis_jabatans) && count($jenis_jabatans)):?>
                        <?php foreach($jenis_jabatans as $record):?>
                            <option value="<?php echo $record->ID?>" <?php if(isset($pegawai->JENIS_JABATAN_ID))  echo  ($pegawai->JENIS_JABATAN_ID==$record->ID) ? "selected" : ""; ?>><?php echo $record->NAMA; ?></option>
                            <?php endforeach;?>
                        <?php endif;?>
                    </select>
                    <span class='help-inline'><?php echo form_error('JENIS_JABATAN_ID'); ?></span>
                </div>
            </div>

			<div class="control-group<?php echo form_error('JABATAN_ID') ? ' error' : ''; ?> col-sm-3">
                <?php echo form_label("Jabatan Sesuai Peta", 'KPKN_ID', array('class' => 'control-label')); ?>
                <div class='controls'>
                	<select name="JABATAN_INSTANSI_ID" id="JABATAN_INSTANSI_ID" class="form-control select2">
						<option value="">-- Silahkan Pilih --</option>
						<?php if (isset($jabatans) && is_array($jabatans) && count($jabatans)):?>
						<?php foreach($jabatans as $record):?>
							<option value="<?php echo $record->ID_JABATAN?>" <?php if(isset($pegawai->JABATAN_ID))  echo  ($pegawai->JABATAN_ID==$record->ID_JABATAN) ? "selected" : ""; ?>><?php echo $record->NAMA_JABATAN; ?></option>
							<?php endforeach;?>
						<?php endif;?>
					</select>
                    <span class='help-inline'><?php echo form_error('JABATAN_ID'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('JABATAN_INSTANSI_REAL_ID') ? ' error' : ''; ?> col-sm-3">
                <?php echo form_label("Jabatan Real", 'KPKN_ID', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select name="JABATAN_INSTANSI_REAL_ID" id="JABATAN_INSTANSI_REAL_ID" class="form-control select2">
                        <option value="">-- Silahkan Pilih --</option>
                        <?php if (isset($jabatans) && is_array($jabatans) && count($jabatans)):?>
                        <?php foreach($jabatans as $record):?>
                            <option value="<?php echo $record->ID_JABATAN?>" <?php if(isset($pegawai->JABATAN_ID))  echo  ($pegawai->JABATAN_ID==$record->ID_JABATAN) ? "selected" : ""; ?>><?php echo $record->NAMA_JABATAN; ?></option>
                            <?php endforeach;?>
                        <?php endif;?>
                    </select>
                    <span class='help-inline'><?php echo form_error('JABATAN_INSTANSI_REAL_ID'); ?></span>
                </div>
            </div>
            
			<div class="control-group col-sm-3">
				<label for="inputNAMA" class="control-label">TMT Golongan</label>
				<div class="input-group date">
				  <div class="input-group-addon">
					<i class="fa fa-calendar"></i>
				  </div>
				  	<input id='TMT_JABATAN' type='text' class="form-control pull-right datepicker" name='TMT_JABATAN'  value="<?php echo set_value('TMT_JABATAN', isset($pegawai->TMT_JABATAN) ? $pegawai->TMT_JABATAN : ''); ?>" />
					<span class='help-inline'><?php echo form_error('TMT_GOLONGAN'); ?></span>
				</div>
			</div> 

            <div class="control-group<?php echo form_error('PENDIDIKAN_ID') ? ' error' : ''; ?> col-sm-9">
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

            <div class="control-group<?php echo form_error('KPKN_ID') ? ' error' : ''; ?> col-sm-6">
                <?php echo form_label(lang('pegawai_field_KPKN_ID'), 'KPKN_ID', array('class' => 'control-label')); ?>
                <div class='controls'>
                	<select name="KPKN_ID" id="KPKN_ID" class="form-control select2">
						<option value="">-- Silahkan Pilih --</option>
						<?php if (isset($kpkns) && is_array($kpkns) && count($kpkns)):?>
						<?php foreach($kpkns as $record):?>
							<option value="<?php echo $record->ID?>" <?php if(isset($pegawai->KPKN_ID))  echo  ($pegawai->KPKN_ID==$record->ID) ? "selected" : ""; ?>><?php echo $record->NAMA; ?></option>
							<?php endforeach;?>
						<?php endif;?>
					</select>
                    <span class='help-inline'><?php echo form_error('KPKN_ID'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('LOKASI_KERJA_ID') ? ' error' : ''; ?> col-sm-6">
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

             <div class="control-group<?php echo form_error('Unor_ID') ? ' error' : ''; ?> col-sm-6">
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

            <div class="control-group<?php echo form_error('UNOR_INDUK_ID') ? ' error' : ''; ?> col-sm-6">
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
			<!--
            <div class="control-group<?php echo form_error('INSTANSI_INDUK_ID') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label(lang('pegawai_field_INSTANSI_INDUK_ID'), 'INSTANSI_INDUK_ID', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='INSTANSI_INDUK_ID' type='text' class="form-control" name='INSTANSI_INDUK_ID' maxlength='11' value="<?php echo set_value('INSTANSI_INDUK_ID', isset($pegawai->INSTANSI_INDUK_ID) ? $pegawai->INSTANSI_INDUK_ID : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('INSTANSI_INDUK_ID'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('INSTANSI_KERJA_ID') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label(lang('pegawai_field_INSTANSI_KERJA_ID'), 'INSTANSI_KERJA_ID', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='INSTANSI_KERJA_ID' type='text' class="form-control" name='INSTANSI_KERJA_ID' maxlength='11' value="<?php echo set_value('INSTANSI_KERJA_ID', isset($pegawai->INSTANSI_KERJA_ID) ? $pegawai->INSTANSI_KERJA_ID : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('INSTANSI_KERJA_ID'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('SATUAN_KERJA_INDUK_ID') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label(lang('pegawai_field_SATUAN_KERJA_INDUK_ID'), 'SATUAN_KERJA_INDUK_ID', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='SATUAN_KERJA_INDUK_ID' type='text' class="form-control" name='SATUAN_KERJA_INDUK_ID' maxlength='11' value="<?php echo set_value('SATUAN_KERJA_INDUK_ID', isset($pegawai->SATUAN_KERJA_INDUK_ID) ? $pegawai->SATUAN_KERJA_INDUK_ID : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('SATUAN_KERJA_INDUK_ID'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('SATUAN_KERJA_KERJA_ID') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label(lang('pegawai_field_SATUAN_KERJA_KERJA_ID'), 'SATUAN_KERJA_KERJA_ID', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='SATUAN_KERJA_KERJA_ID' type='text' class="form-control" name='SATUAN_KERJA_KERJA_ID' maxlength='11' value="<?php echo set_value('SATUAN_KERJA_KERJA_ID', isset($pegawai->SATUAN_KERJA_KERJA_ID) ? $pegawai->SATUAN_KERJA_KERJA_ID : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('SATUAN_KERJA_KERJA_ID'); ?></span>
                </div>
            </div>
			-->
            <div class="control-group<?php echo form_error('GOLONGAN_DARAH') ? ' error' : ''; ?> col-sm-12">
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
             <div class="control-group<?php echo form_error('status_pegawai') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Status Pegawai", 'status_pegawai', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select class="validate[required] text-input form-control" name="status_pegawai" id="status_pegawai" class="chosen-select-deselect">
                        <option value="">-- Pilih  --</option>
                        <option value="1" <?php if(isset($pegawai->status_pegawai))  echo  ("1"==$pegawai->status_pegawai) ? "selected" : ""; ?>> PNS</option>
                        <option value="2" <?php if(isset($pegawai->status_pegawai))  echo  ("2"==$pegawai->status_pegawai) ? "selected" : ""; ?>> Bukan PNS</option>
                    </select>
                    <span class='help-inline'><?php echo form_error('status_pegawai'); ?></span>
                </div>
            </div>
        </fieldset>
        </div>
  		<div class="box-footer">
            <div class='messages'></div>
            <input type='submit' name='save' id="btnsaveprofile" class='btn btn-primary' value="Simpan" />
            <?php echo lang('bf_or'); ?>
            <?php echo anchor(SITE_AREA . '/kepegawaian/pegawai', lang('pegawai_cancel'), 'class="btn btn-warning"'); ?>
            
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
<script>
    $("#frmprofile").validate({
        submitHandler: function(form) {
        $("#btnsaveprofile").val('Menyimpan data......').attr('disabled', true).addClass('bt-hud').unbind('click');
        submitdata();
      },
      rules: {
        NOMOR_HP: {
          required: false,
          number: true
        },
        EMAIL: {
            required: false,
            email: true
        },
        BPJS: {
            required: false
        },
        

      },errorElement: "span",
        errorPlacement: function ( error, element ) {
            // Add the `help-block` class to the error element
            error.addClass( "help-block" );

            // Add `has-feedback` class to the parent div.form-group
            // in order to add icons to inputs

            if ( element.prop( "type" ) === "checkbox" ) {
                error.insertAfter( element.parent( "label" ) );
            } else {
                error.insertAfter( element );
            }

            // Add the span element, if doesn't exists, and apply the icon classes to it.
            if ( !element.next( "span" )[ 0 ] ) {
            }
        },
        success: function ( label, element ) {
            // Add the span element, if doesn't exists, and apply the icon classes to it.
            if ( !$( element ).next( "span" )[ 0 ] ) {
            }
        },
        highlight: function ( element, errorClass, validClass ) {
            $( element ).parents( ".control-group" ).addClass( "has-error" ).removeClass( "has-success" );
        },
        unhighlight: function ( element, errorClass, validClass ) {
            $( element ).parents( ".col-sm-5" ).addClass( "has-success" ).removeClass( "has-error" );
        }


    });
    
    $('#TK_PENDIDIKAN').change(function() {
        /* 
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
            */
            //$("#PENDIDIKAN_ID").select2("updateResults");
            return false;
    });
     
    function submitdata(){
        
        var json_url = "<?php echo base_url() ?>admin/kepegawaian/pegawai/save_pegawai";
         $.ajax({    
            type: "post",
            url: json_url,
            data: $("#frmprofile").serialize(),
            dataType: "json",
                  success: function(data){ 
                if(data.success){
                    swal("Pemberitahuan!", data.msg, "success");
                    $("#btnsaveprofile").val('Simpan data').attr('disabled', false).addClass('bt-hud').unbind('click');
                    $(".messages").empty();
                    window.location.href = "<?php echo base_url(); ?>admin/kepegawaian/pegawai/";
                }
                else {

                    $(".messages").empty().append(data.msg);
                    $("#btnsaveprofile").val('Simpan data').attr('disabled', false).addClass('bt-hud').unbind('click');
                }
            }});
        return false; 
    }
</script>
<script>
    $('#JENIS_JABATAN_ID').change(function() {
        var valuejenisjabatan = $('#JENIS_JABATAN_ID').val();

            $("#JABATAN_INSTANSI_ID").empty().append("<option>loading...</option>"); //show loading...
             
            var json_url = "<?php echo base_url(); ?>admin/masters/jabatan/getbyjenis?jenis=" + encodeURIComponent(valuejenisjabatan);
            //alert(json_url);
            $.getJSON(json_url,function(data){
                $("#JABATAN_INSTANSI_ID").empty(); 
                if(data==""){
                    $("#JABATAN_INSTANSI_ID").append("<option value=\"\">Silahkan Pilih </option>");
                }
                else{
                    $("#JABATAN_INSTANSI_ID").append("<option value=\"\">Silahkan Pilih</option>");
                    for(i=0; i<data.id.length; i++){
                        $("#JABATAN_INSTANSI_ID").append("<option value=\"" + data.id[i]  + "\">" + data.nama[i] +"</option>");
                    }
                }
                
            });
            $("#JABATAN_INSTANSI_ID").select2("updateResults");
            return false;
    });
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
    
    $("#TEMPAT_LAHIR_ID").select2({
        placeholder: 'Cari Tempat Lahir...',
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
     
    $("#JABATAN_INSTANSI_ID").select2({
        placeholder: 'Cari Jabatan...',
        width: '100%',
        minimumInputLength: 3,
        allowClear: true,
        ajax: {
            url: '<?php echo site_url("admin/masters/ref_jabatan/ajax");?>',
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
        placeholder: 'Cari Jabatan...',
        width: '100%',
        minimumInputLength: 3,
        allowClear: true,
        ajax: {
            url: '<?php echo site_url("admin/masters/ref_jabatan/ajax");?>',
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
    $("#PENDIDIKAN_ID").select2({
        placeholder: 'Cari Jabatan...',
        width: '100%',
        minimumInputLength: 3,
        allowClear: true,
        ajax: {
            url: '<?php echo site_url("pegawai/masterpendidikan/ajax");?>',
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