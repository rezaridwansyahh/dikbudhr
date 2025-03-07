
<?php 
    $id = isset($detail_riwayat->ID) ? $detail_riwayat->ID : '';
?>
<div class='box box-warning' id="form-riwayat-jabatan-add">
	<div class="box-body">
        <div class="messages">
        </div>
    <?php echo form_open($this->uri->uri_string(), 'id="frm"'); ?>
    <fieldset>
            <input id='ID' type='hidden' disabled class="form-control"" name='ID' maxlength='32' value="<?php echo set_value('ID', isset($detail_riwayat->ID) ? trim($detail_riwayat->ID) : ''); ?>" />
            <input id='PNS_ID' type='hidden' disabled class="form-control"" name='PNS_ID' maxlength='32' value="<?php echo set_value('PNS_ID', isset($PNS_ID) ? trim($PNS_ID) : ''); ?>" />
           <div class="control-group<?php echo form_error('ID_SATUAN_KERJA') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label('SATUAN KERJA', 'ID_SATUAN_KERJA', array('class' => 'control-label')); ?>
                <div class='controls'>
                	<select name="ID_SATUAN_KERJA" id="ID_SATUAN_KERJA" disabled class="form-control" select2  col-sm-12" width="100%">
						<option value="">-- Silahkan Pilih --</option>
						<?php if (isset($recsatker) && is_array($recsatker) && count($recsatker)):?>
						<?php foreach($recsatker as $record):?>
							<option value="<?php echo $record->ID?>" <?php if(isset($detail_riwayat->ID_SATUAN_KERJA))  echo  (trim($detail_riwayat->ID_SATUAN_KERJA)==trim($record->ID)) ? "selected" : ""; ?>><?php echo $record->NAMA_UNOR; ?></option>
							<?php endforeach;?>
						<?php endif;?>
						<option value="N">Tidak ada</option>
					</select>
                    <span class='help-inline'><?php echo form_error('ID_SATUAN_KERJA'); ?></span>
                </div>
            </div>
        	<div class="control-group<?php echo form_error('GOLONGAN') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label('Jenis', 'Jenis Jabatan', array('class' => 'control-label')); ?>
                <div class='controls'>
                	<select name="ID_JENIS_JABATAN" id="ID_JENIS_JABATAN" disabled class="form-control"">
						<option value="">-- Silahkan Pilih --</option>
						<?php if (isset($jenis_jabatans) && is_array($jenis_jabatans) && count($jenis_jabatans)):?>
						<?php foreach($jenis_jabatans as $record):?>
							<option value="<?php echo $record->ID?>" <?php if(isset($detail_riwayat->ID_JENIS_JABATAN))  echo  ($detail_riwayat->ID_JENIS_JABATAN==$record->ID) ? "selected" : ""; ?>  <?php if(isset($jenis_jabatan))  echo  ($jenis_jabatan==$record->ID) ? "selected" : ""; ?>><?php echo $record->NAMA; ?></option>
							<?php endforeach;?>
						<?php endif;?>
					</select>
                    <span class='help-inline'><?php echo form_error('ID_JENIS_JABATAN'); ?></span>
                </div>
            </div>
           <div class="control-group col-sm-12">
				<label for="inputNAMA" class="control-label">UNOR</label>
				<div class='controls'>
                    <select id="ID_UNOR" name="ID_UNOR" width="100%" disabled class="select2 col-md-10 form-control">
						<option value="">-- Silahkan Pilih --</option>
						<?php if (isset($recunor) && is_array($recunor) && count($recunor)):?>
						<?php foreach($recunor as $record):?>
							<option value="<?php echo $record->KODE_INTERNAL?>" <?php if(isset($detail_riwayat->ID_UNOR))  echo  (trim($detail_riwayat->ID_UNOR)==trim($record->KODE_INTERNAL)) ? "selected" : ""; ?> <?php if(isset($detail_riwayat->ID_JENIS_JABATAN))  echo  ($jenis_jabatan == $record->ID) ? "selected" : ""; ?>><?php echo $record->NAMA_UNOR; ?></option>
							<?php endforeach;?>
						<?php endif;?>
					</select>
                </div>
			</div> 
           

            <div class="control-group<?php echo form_error('ID_JABATAN') ? ' error' : ''; ?> col-sm-9">
                <?php echo form_label('JABATAN', 'ID_JABATAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                	<div class='slcjabatan'>
                	<select name="ID_JABATAN" id="ID_JABATAN" disabled class="form-control" select2  col-sm-12 slcjabatan" width="100%">
						<option value="">-- Silahkan Pilih --</option>
						<?php if (isset($jabatans) && is_array($jabatans) && count($jabatans)):?>
						<?php foreach($jabatans as $record):?>
							<option value="<?php echo $record->KODE_JABATAN?>" <?php if(isset($detail_riwayat->ID_JABATAN))  echo  (trim($detail_riwayat->ID_JABATAN)==trim($record->KODE_JABATAN)) ? "selected" : ""; ?>><?php echo $record->NAMA_JABATAN; ?></option>
							<?php endforeach;?>
						<?php endif;?>
					</select>
					</div>
					<span class='divjabatan'></span>
                    <span class='help-inline'><?php echo form_error('ID_JABATAN'); ?></span>
                </div>
            </div>   
            <div class="control-group col-sm-3">
				<label for="inputNAMA" class="control-label">ESELON</label>
				<div class="input-group date">
				   
					<input type='text' disabled class="form-control" pull-right" name='ESELON'  value="<?php echo set_value('ESELON', isset($detail_riwayat->ESELON) ? trim($detail_riwayat->ESELON) : ''); ?>" />
					<span class='help-inline'><?php echo form_error('ESELON'); ?></span>
				</div>
			</div>          
			<div class="control-group col-sm-3">
				<label for="inputNAMA" class="control-label">TMT</label>
				<div class="input-group date">
				  <div class="input-group-addon">
					<i class="fa fa-calendar"></i>
				  </div>
					<input type='text' disabled class="form-control" pull-right datepicker" name='TMT_JABATAN'  value="<?php echo set_value('TMT_JABATAN', isset($detail_riwayat->TMT_JABATAN) ? $detail_riwayat->TMT_JABATAN : ''); ?>" />
					<span class='help-inline'><?php echo form_error('TMT_JABATAN'); ?></span>
				</div>
			</div> 
            
            <div class="control-group<?php echo form_error('NOMOR_SK') ? ' error' : ''; ?> col-sm-9">
                <?php echo form_label("SK NOMOR", 'SK NOMOR', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='NOMOR_SK' type='text' disabled class="form-control"" name='NOMOR_SK' maxlength='32' value="<?php echo set_value('NOMOR_SK', isset($detail_riwayat->NOMOR_SK) ? trim($detail_riwayat->NOMOR_SK) : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('NOMOR_SK'); ?></span>
                </div>
            </div>
            <div class="control-group col-sm-3">
				<label for="inputNAMA" class="control-label">SK TANGGAL</label>
				<div class="input-group date">
				  <div class="input-group-addon">
					<i class="fa fa-calendar"></i>
				  </div>
					<input type='text' disabled class="form-control" pull-right datepicker" name='TANGGAL_SK'  value="<?php echo set_value('TANGGAL_SK', isset($detail_riwayat->TANGGAL_SK) ? $detail_riwayat->TANGGAL_SK : ''); ?>" />
					<span class='help-inline'><?php echo form_error('TANGGAL_SK'); ?></span>
				</div>
			</div>   
			<div class="control-group col-sm-9">
				<label for="inputNAMA" class="control-label">TMT PELANTIKAN</label>
				<div class="input-group date">
				  <div class="input-group-addon">
					<i class="fa fa-calendar"></i>
				  </div>
					<input type='text' disabled class="form-control" pull-right datepicker" name='TMT_PELANTIKAN'  value="<?php echo set_value('TMT_PELANTIKAN', isset($detail_riwayat->TMT_PELANTIKAN) ? $detail_riwayat->TMT_PELANTIKAN : ''); ?>" />
					<span class='help-inline'><?php echo form_error('TMT_PELANTIKAN'); ?></span>
				</div>
			</div> 
			<div class="control-group col-sm-3">
				<label for="inputNAMA" class="control-label">JABATAN AKTIF?</label>
				<div class='controls'>
                    <input id='IS_ACTIVE' type='checkbox' name='IS_ACTIVE' value="1" <?php echo $detail_riwayat->IS_ACTIVE == "1" ? "checked" : ""; ?> />
                    <span class='help-inline'><?php echo form_error('IS_ACTIVE'); ?></span>
                </div>
			</div> 
			</fieldset>
			<fieldset>
			<legend>Jika tidak ada</legend>
			<div class="control-group<?php echo form_error('eselon1') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label('ESELON 1', 'ESELON1', array('class' => 'control-label')); ?>
                <div class='controls'>
                	<input type='text' disabled class="form-control"" name='ESELON1'  value="<?php echo set_value('ESELON1', isset($detail_riwayat->ESELON1) ? $detail_riwayat->ESELON1 : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('ESELON1'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('eselon2') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label('ESELON 2', 'eselon2', array('class' => 'control-label')); ?>
                <div class='controls'>
                	<input type='text' disabled class="form-control"" name='ESELON2'  value="<?php echo set_value('ESELON2', isset($detail_riwayat->ESELON2) ? $detail_riwayat->ESELON2 : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('ESELON1'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('ESELON3') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label('ESELON 3', 'ESELON3', array('class' => 'control-label')); ?>
                <div class='controls'>
                	<input type='text' disabled class="form-control"" name='ESELON3'  value="<?php echo set_value('ESELON3', isset($detail_riwayat->ESELON3) ? $detail_riwayat->ESELON3 : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('ESELON3'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('ESELON4') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label('ESELON 4', 'ESELON4', array('class' => 'control-label')); ?>
                <div class='controls'>
                	<input type='text' disabled class="form-control"" name='ESELON4'  value="<?php echo set_value('ESELON4', isset($detail_riwayat->ESELON4) ? $detail_riwayat->ESELON4 : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('ESELON4'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('NAMA_JABATAN') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label('NAMA JABATAN', 'NAMA_JABATAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                	<input type='text' disabled class="form-control"" name='NAMA_JABATAN'  value="<?php echo set_value('NAMA_JABATAN', isset($detail_riwayat->NAMA_JABATAN) ? $detail_riwayat->NAMA_JABATAN : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('ESELON4'); ?></span>
                </div>
            </div>
            </fieldset>
        </div>
  		 
</div>
 