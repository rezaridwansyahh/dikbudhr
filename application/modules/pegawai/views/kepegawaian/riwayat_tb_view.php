<?php 
    $id = isset($detail_riwayat->ID) ? $detail_riwayat->ID : '';
?>
<div class='box box-warning' id="form-riwayat-tb-add">
	<div class="box-body">
        <div class="messages">
        </div>
    <?php echo form_open($this->uri->uri_string(), 'id="frm"'); ?>
    <fieldset>
            <input id='id' type='hidden' class="form-control" name='id' maxlength='32' value="<?php echo set_value('ID', isset($detail_riwayat->ID) ? trim($detail_riwayat->ID) : ''); ?>" />
            <input id='NIP' type='hidden' class="form-control" name='NIP' maxlength='32' value="<?php echo set_value('NIP_BARU', isset($NIP_BARU) ? trim($NIP_BARU) : ''); ?>" />
            <div class="row">   
                <div class="control-group<?php echo form_error('n_gol_ruang') ? ' error' : ''; ?> col-sm-8">
                    <?php echo form_label("NOMOR SK", 'Golongan Ruang', array('class' => 'control-label')); ?>
                    <div class='controls'>
                        <input id='NOMOR_SK' type='text' class="form-control"  disabled name='NOMOR_SK' maxlength='200' value="<?php echo set_value('NOMOR_SK', isset($detail_riwayat->NOMOR_SK) ? trim($detail_riwayat->NOMOR_SK) : ''); ?>" />
                        <span class='help-inline'><?php echo form_error('NOMOR_SK'); ?></span>
                    </div>
                </div>
                <div class="control-group col-sm-4">
                    <label for="inputNAMA" class="control-label">TANGGAL SK</label>
                    <div class="input-group date">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                        <input type='text' class="form-control pull-right datepicker" disabled name='TANGGAL_SK'  value="<?php echo set_value('TANGGAL_SK', isset($detail_riwayat->TANGGAL_SK) ? $detail_riwayat->TANGGAL_SK : ''); ?>" />
                        <span class='help-inline'><?php echo form_error('TANGGAL_SK'); ?></span>
                    </div>
                </div> 
            </div>
            <div class="row">   
                <div class="control-group<?php echo form_error('UNIVERSITAS') ? ' error' : ''; ?> col-sm-12">
                    <?php echo form_label("UNIVERSITAS", 'UNIVERSITAS', array('class' => 'control-label')); ?>
                    <div class='controls'>
                        <input id='UNIVERSITAS' type='text' class="form-control" disabled name='UNIVERSITAS' maxlength='200' value="<?php echo set_value('UNIVERSITAS', isset($detail_riwayat->UNIVERSITAS) ? trim($detail_riwayat->UNIVERSITAS) : ''); ?>" />
                        <span class='help-inline'><?php echo form_error('UNIVERSITAS'); ?></span>
                    </div>
                </div>
                 
            </div>
            <div class="row">   
                <div class="control-group<?php echo form_error('FAKULTAS') ? ' error' : ''; ?> col-sm-12">
                    <?php echo form_label("FAKULTAS", 'FAKULTAS', array('class' => 'control-label')); ?>
                    <div class='controls'>
                        <input id='FAKULTAS' type='text' class="form-control" name='FAKULTAS' disabled maxlength='200' value="<?php echo set_value('FAKULTAS', isset($detail_riwayat->FAKULTAS) ? trim($detail_riwayat->FAKULTAS) : ''); ?>" />
                        <span class='help-inline'><?php echo form_error('FAKULTAS'); ?></span>
                    </div>
                </div>
                 
            </div>
            <div class="row">   
                <div class="control-group<?php echo form_error('PROGRAM_STUDI') ? ' error' : ''; ?> col-sm-12">
                    <?php echo form_label("PROGRAM STUDI", 'PROGRAM_STUDI', array('class' => 'control-label')); ?>
                    <div class='controls'>
                        <input id='PROGRAM_STUDI' type='text' class="form-control" name='PROGRAM_STUDI' disabled maxlength='200' value="<?php echo set_value('PROGRAM_STUDI', isset($detail_riwayat->PROGRAM_STUDI) ? trim($detail_riwayat->PROGRAM_STUDI) : ''); ?>" />
                        <span class='help-inline'><?php echo form_error('PROGRAM_STUDI'); ?></span>
                    </div>
                </div>
                 
            </div>
            <div class="row"> 
                 
                <div class="control-group col-sm-6">
                    <label for="inputNAMA" class="control-label">DARI TANGGAL</label>
                    <div class="input-group date">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                        <input type='text' class="form-control pull-right datepicker" name='MULAI_BELAJAR' disabled value="<?php echo set_value('MULAI_BELAJAR', isset($detail_riwayat->MULAI_BELAJAR) ? $detail_riwayat->MULAI_BELAJAR : ''); ?>" />
                        <span class='help-inline'><?php echo form_error('MULAI_BELAJAR'); ?></span>
                    </div>
                </div> 
                <div class="control-group col-sm-6">
                    <label class="control-label">SAMPAI TANGGAL</label>
                    <div class="input-group date">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                        <input type='text' class="form-control pull-right datepicker" name='AKHIR_BELAJAR' disabled value="<?php echo set_value('AKHIR_BELAJAR', isset($detail_riwayat->AKHIR_BELAJAR) ? $detail_riwayat->AKHIR_BELAJAR : ''); ?>" />
                        <span class='help-inline'><?php echo form_error('AKHIR_BELAJAR'); ?></span>
                    </div>
                </div>   
            </div>    
            </fieldset>
        </div>
  		 
    <?php echo form_close(); ?>
</div>
 