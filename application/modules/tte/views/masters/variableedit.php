<link href="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/css/fileinput.css" media="all" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/themes/explorer/theme.css" media="all" rel="stylesheet" type="text/css"/>


<?php

if (validation_errors()) :
?>
<div class='alert alert-block alert-error fade in'>
    <a class='close' data-dismiss='alert'>&times;</a>
    <h4 class='alert-heading'>
        <?php echo lang('tte_errors_message'); ?>
    </h4>
    <?php echo validation_errors(); ?>
</div>
<?php
endif;

$id = isset($tte->id) ? $tte->id : '';

?>
<div class='admin-box box box-primary'>
    <div class="box-header">
        <h3>Edit Variable</h3>
        <hr>
    </div>        
    <div class="box-body">
    <?php echo form_open($this->uri->uri_string(),"id=submit_form"); ?>
        
            <div class="control-group<?php echo form_error('label_variable') ? ' error' : ''; ?>">
                <?php echo form_label("Label Variable". lang('bf_form_label_required'), 'label_variable', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='id' type='hidden' class="form-control" name='id' maxlength='100' value="<?php echo set_value('id', isset($variable->id) ? $variable->id : ''); ?>" />
                    <input id='label_variable' type='text' required='required' class="form-control" name='label_variable' maxlength='100' value="<?php echo set_value('label_variable', isset($variable->label_variable) ? $variable->label_variable : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('label_variable'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('nama_variable') ? ' error' : ''; ?>">
                <?php echo form_label("Nama Variable". lang('bf_form_label_required'), 'nama_variable', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='nama_variable' type='text' required='required' class="form-control" name='nama_variable' maxlength='100' value="<?php echo set_value('nama_variable', isset($variable->nama_variable) ? $variable->nama_variable : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('nama_variable'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('tipe') ? ' error' : ''; ?>">
                <?php echo form_label("Tipe Variable". lang('bf_form_label_required'), 'nama_variable', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select name="tipe" class="form-control">
                        <option value="">Silahkan Pilih</option>
                        <option value="Number" <?php echo trim($variable->tipe) == "Number" ? "selected" : ""; ?> >Number</option>
                        <option value="Char" <?php echo trim($variable->tipe) == "Char" ? "selected" : ""; ?>>Char</option>
                        <option value="Date" <?php echo trim($variable->tipe) == "Date" ? "selected" : ""; ?>>Date</option>
                        <option value="Pilihan" <?php echo trim($variable->tipe) == "Pilihan" ? "selected" : ""; ?>>Pilihan</option>
                    </select>
                    <span class='help-inline'><?php echo form_error('nama_variable'); ?></span>
                </div>
            </div>
             
            <div class="control-group<?php echo form_error('keterangan') ? ' error' : ''; ?>">
                <?php echo form_label("Keterangan", 'keterangan', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <textarea name='keterangan' class="form-control"><?php echo set_value('keterangan', isset($variable->keterangan) ? $variable->keterangan : ''); ?></textarea> 
                    <span class='help-inline'><?php echo form_error('keterangan'); ?></span>
                </div>
            </div>
             
        </div>
            <div class="box-footer">
            <input type='button' name='save' id="btn_save" class='btn btn-primary' value="Simpan Variable" />
            <?php echo lang('bf_or'); ?>
            <?php echo anchor(SITE_AREA . '/masters/tte/variable', lang('tte_cancel'), 'class="btn btn-warning"'); ?>
            
        </div>
    <?php echo form_close(); ?>
</div>
<script src="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/themes/explorer/theme.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/js/fileinput.js" type="text/javascript"></script>

<script type="text/javascript">
  function submitdata(){
    
    var the_data = new FormData(document.getElementById("submit_form"));
    $.ajax({
        url: "<?php echo base_url('admin/masters/tte/act_savevariable'); ?>",
        type: "POST",
        data: the_data,
        enctype: 'multipart/form-data',
        processData: false, // tell jQuery not to process the data
        contentType: false, // tell jQuery not to set contentType
        dataType: 'JSON',

        beforeSend: function (xhr) {
            //$("#loading-all").show();
        },
        success: function (response) {
            if(response.status){
                swal("Sukses",response.msg,"success");
                window.location.href = "<?php echo base_url(); ?>admin/masters/tte/variable";
            }else{
                swal("Ada kesalahan",response.msg,"error");
            }
        }
    });
    
    return false; 
  } 
$('body').on('click','#btn_save',function () { 
  submitdata();
});
</script>