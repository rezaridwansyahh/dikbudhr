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
        <?php echo lang('baperjakat_errors_message'); ?>
    </h4>
    <?php echo validation_errors(); ?>
</div>
<?php
endif;

$id = isset($baperjakat->ID) ? $baperjakat->ID : '';

?>
<div class='box box-primary'>
    
    <div class="box-body">
    <?php echo form_open($this->uri->uri_string(), 'class="form-horizontal"'); ?>
        <fieldset>
            <div class="control-group col-sm-12">
                <label for="inputNAMA" class="control-label">TGL PELAKSANAAN</label>
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                    <input type='text' class="form-control pull-right datepicker" name='TANGGAL'  value="<?php echo set_value('TANGGAL', isset($baperjakat->TANGGAL) ? $baperjakat->TANGGAL : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('TANGGAL'); ?></span>
                </div>
            </div> 

            

            <div class="control-group<?php echo form_error('KETERANGAN') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label(lang('baperjakat_field_KETERANGAN'), 'KETERANGAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='KETERANGAN' class="form-control" type='text' name='KETERANGAN' maxlength='50' value="<?php echo set_value('KETERANGAN', isset($baperjakat->KETERANGAN) ? $baperjakat->KETERANGAN : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('KETERANGAN'); ?></span>
                </div>
            </div>
             <div class="control-group col-sm-12">
                <label for="inputNAMA" class="control-label">TGL PENETAPAN</label>
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                    <input type='text' class="form-control pull-right datepicker" name='TANGGAL_PENETAPAN'  value="<?php echo set_value('TANGGAL_PENETAPAN', isset($baperjakat->TANGGAL_PENETAPAN) ? $baperjakat->TANGGAL_PENETAPAN : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('TANGGAL_PENETAPAN'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('NO_SK_PENETAPAN') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label(lang('baperjakat_field_NO_SK_PENETAPAN'), 'NO_SK_PENETAPAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='NO_SK_PENETAPAN' class="form-control" type='text' name='NO_SK_PENETAPAN' maxlength='20' value="<?php echo set_value('NO_SK_PENETAPAN', isset($baperjakat->NO_SK_PENETAPAN) ? $baperjakat->NO_SK_PENETAPAN : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('NO_SK_PENETAPAN'); ?></span>
                </div>
            </div>
            <div class="control-group col-sm-12">
                <label for="inputNAMA" class="control-label">TGL PELANTIKAN</label>
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                    <input type='text' class="form-control pull-right datepicker" name='TANGGAL_PELANTIKAN'  value="<?php echo set_value('TANGGAL_PELANTIKAN', isset($baperjakat->TANGGAL_PELANTIKAN) ? $baperjakat->TANGGAL_PELANTIKAN : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('TANGGAL_PELANTIKAN'); ?></span>
                </div>
            </div> 

            <div class="control-group<?php echo form_error('STATUS_AKTIF') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label(lang('baperjakat_field_STATUS_AKTIF'), 'STATUS_AKTIF', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input name="STATUS_AKTIF" type="checkbox"<?php echo (isset($baperjakat->STATUS_AKTIF) and $baperjakat->STATUS_AKTIF == "1") ? "checked" : ''; ?>  value="1">
                    <span class='help-inline'><?php echo form_error('STATUS_AKTIF'); ?></span>
                </div>
            </div>
         </div>
        <div class="box-footer">
            <input type='submit' name='save' class='btn btn-primary' value="Simpan" />
            <?php echo lang('bf_or'); ?>
            <?php echo anchor(SITE_AREA . '/kepegawaian/baperjakat', lang('baperjakat_cancel'), 'class="btn btn-warning"'); ?>
            
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
</script>
