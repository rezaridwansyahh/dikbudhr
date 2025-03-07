<?php 
    $id = isset($detail_riwayat->ID) ? $detail_riwayat->ID : '';
?>
<div class='box box-warning' id="form-pendidikan-add">
	<div class="box-body">
        <div class="messages">
        </div>
    <?php echo form_open($this->uri->uri_string(), 'id="frm"'); ?>
        <fieldset>
             <input id='id_data' type='hidden' readonly class="form-control" name='id_data' maxlength='32' value="<?php echo set_value('id_data', isset($id) ? trim($id) : ''); ?>" />
            <input id='PNS_ID' type='hidden' readonly class="form-control" name='PNS_ID' maxlength='32' value="<?php echo set_value('PNS_ID', isset($PNS_ID) ? trim($PNS_ID) : ''); ?>" />
            
            <div class="control-group<?php echo form_error('TINGKAT_PENDIDIKAN_ID') ? ' error' : ''; ?> col-sm-3">
                <?php echo form_label('Jenjang Pendidikan', 'TINGKAT_PENDIDIKAN_ID', array('class' => 'control-label')); ?>
                <div class='controls'>
                	<select name="TINGKAT_PENDIDIKAN_ID" disabled id="TINGKAT_PENDIDIKAN_ID" readonly class="form-control">
						<option value="">-- Silahkan Pilih --</option>
						<?php if (isset($tk_pendidikans) && is_array($tk_pendidikans) && count($tk_pendidikans)):?>
						<?php foreach($tk_pendidikans as $record):?>
							<option value="<?php echo $record->ID?>" <?php if(isset($detail_riwayat->TINGKAT_PENDIDIKAN_ID))  echo  ($detail_riwayat->TINGKAT_PENDIDIKAN_ID==$record->ID) ? "selected" : ""; ?>><?php echo $record->NAMA; ?></option>
							<?php endforeach;?>
						<?php endif;?>
					</select>
                    <span class='help-inline'><?php echo form_error('TINGKAT_PENDIDIKAN_ID'); ?></span>
                </div>
            </div>
           <div class="control-group<?php echo form_error('NAMA_SEKOLAH') ? ' error' : ''; ?> col-sm-9">
                <?php echo form_label("NAMA Sekolah", 'NAMA_SEKOLAH', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='NAMA_SEKOLAH' type='text' readonly class="form-control" name='NAMA_SEKOLAH' maxlength='200' value="<?php echo set_value('NAMA_SEKOLAH', isset($detail_riwayat->NAMA_SEKOLAH) ? $detail_riwayat->NAMA_SEKOLAH : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('NAMA_SEKOLAH'); ?></span>
                </div>
            </div>
            
			<div class="control-group col-sm-3">
				<label for="inputNAMA" class="control-label">Tgl Lulus</label>
				<div class="input-group date">
				  <div class="input-group-addon">
					<i class="fa fa-calendar"></i>
				  </div>
					<input type='text' readonly class="form-control pull-right datepicker" name='TANGGAL_LULUS'  value="<?php echo set_value('TANGGAL_LULUS', isset($detail_riwayat->TANGGAL_LULUS) ? $detail_riwayat->TANGGAL_LULUS : ''); ?>" />
					<span class='help-inline'><?php echo form_error('TANGGAL_LULUS'); ?></span>
				</div>
			</div> 
            <div class="control-group<?php echo form_error('TAHUN_LULUS') ? ' error' : ''; ?> col-sm-9">
                <?php echo form_label("Tahun Lulus", 'GELAR_BELAKANG', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='TAHUN_LULUS' type='text' readonly class="form-control" name='TAHUN_LULUS' maxlength='11' value="<?php echo set_value('TAHUN_LULUS', isset($detail_riwayat->TAHUN_LULUS) ? $detail_riwayat->TAHUN_LULUS : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('TAHUN_LULUS'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('NOMOR_IJASAH') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Nomor Ijasah", 'NOMOR_IJASAH', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='NOMOR_IJASAH' type='text' readonly class="form-control" name='NOMOR_IJASAH' maxlength='32' value="<?php echo set_value('NOMOR_IJASAH', isset($detail_riwayat->NOMOR_IJASAH) ? $detail_riwayat->NOMOR_IJASAH : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('NOMOR_IJASAH'); ?></span>
                </div>
            </div>

            
             <div class="control-group<?php echo form_error('GELAR_DEPAN') ? ' error' : ''; ?> col-sm-6">
                <?php echo form_label('Gelar Depan', 'GELAR_DEPAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='GELAR_DEPAN' type='text' readonly class="form-control" name='GELAR_DEPAN' maxlength='11' value="<?php echo set_value('GELAR_DEPAN', isset($detail_riwayat->GELAR_DEPAN) ? $detail_riwayat->GELAR_DEPAN : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('GELAR_DEPAN'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('GELAR_BELAKANG') ? ' error' : ''; ?> col-sm-6">
                <?php echo form_label("Gelar Belakang", 'GELAR_BELAKANG', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='GELAR_BELAKANG' type='text' readonly class="form-control" name='GELAR_BELAKANG' maxlength='11' value="<?php echo set_value('GELAR_BELAKANG', isset($detail_riwayat->GELAR_BELAKANG) ? $detail_riwayat->GELAR_BELAKANG : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('GELAR_BELAKANG'); ?></span>
                </div>
            </div>
        </div>
  		 
    <?php echo form_close(); ?>
</div>
<script src="<?php echo base_url(); ?>themes/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
 