<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/select2/select2.min.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/css/dropzone/dropzone.min.css">
<script src="<?php echo base_url(); ?>themes/admin/js/dropzone/dropzone.min.js"></script>
<?php 
    $id = isset($detail_riwayat->ID) ? $detail_riwayat->ID : '';
?>
<div class='box box-warning' id="form-riwayat-assesmen-add">
    <div class="box-body">
        <div class="messages">
        </div>
    <?php echo form_open($this->uri->uri_string(), 'id="frm"'); ?>
        <fieldset>
            

            <div class="control-group<?php echo form_error('nip') ? ' error' : ''; ?>">
                <?php echo form_label(lang('daftar_rohaniawan_field_nip'), 'nip', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='id' type='hidden' class="form-control" name='id' maxlength='30' value="<?php echo set_value('id', isset($daftar_rohaniawan->id) ? $daftar_rohaniawan->id : ''); ?>" />
                    <input id='nip' type='text' class="form-control" name='nip' maxlength='30' value="<?php echo set_value('nip', isset($daftar_rohaniawan->nip) ? $daftar_rohaniawan->nip : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('nip'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('nama') ? ' error' : ''; ?>">
                <?php echo form_label(lang('daftar_rohaniawan_field_nama'), 'nama', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='nama' type='text' class="form-control" name='nama' maxlength='100' value="<?php echo set_value('nama', isset($daftar_rohaniawan->nama) ? $daftar_rohaniawan->nama : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('nama'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('jabatan') ? ' error' : ''; ?>">
                <?php echo form_label(lang('daftar_rohaniawan_field_jabatan'), 'jabatan', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='jabatan' type='text' class="form-control" name='jabatan' maxlength='100' value="<?php echo set_value('jabatan', isset($daftar_rohaniawan->jabatan) ? $daftar_rohaniawan->jabatan : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('jabatan'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('agama') ? ' error' : ''; ?>">
                <?php echo form_label(lang('daftar_rohaniawan_field_agama'), 'agama', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select name="agama" id="agama" class="form-control">
                        <option value="">-- Silahkan Pilih --</option>
                        <?php if (isset($agamas) && is_array($agamas) && count($agamas)):?>
                        <?php foreach($agamas as $record):?>
                            <option value="<?php echo $record->ID?>" <?php if(isset($daftar_rohaniawan->agama))  echo  ($daftar_rohaniawan->agama==$record->ID) ? "selected" : ""; ?>><?php echo $record->NAMA; ?></option>
                            <?php endforeach;?>
                        <?php endif;?>
                    </select>
                    <span class='help-inline'><?php echo form_error('agama'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('aktif') ? ' error' : ''; ?>">
                <?php echo form_label(lang('daftar_rohaniawan_field_aktif'), 'aktif', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select name="aktif" id="aktif" class="form-control">
                        <option value="1" <?php if(isset($daftar_rohaniawan->aktif))  echo  ($daftar_rohaniawan->aktif=="1") ? "selected" : ""; ?>>Aktif</option>
                        <option value="0" <?php if(isset($daftar_rohaniawan->aktif))  echo  ($daftar_rohaniawan->aktif=="0") ? "selected" : ""; ?>>Tidak</option>
                    </select>
                    <span class='help-inline'><?php echo form_error('aktif'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('pangkat_gol') ? ' error' : ''; ?>">
                <?php echo form_label(lang('daftar_rohaniawan_field_pangkat_gol'), 'pangkat_gol', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='pangkat_gol' type='text' class="form-control" name='pangkat_gol' maxlength='30' value="<?php echo set_value('pangkat_gol', isset($daftar_rohaniawan->pangkat_gol) ? $daftar_rohaniawan->pangkat_gol : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('pangkat_gol'); ?></span>
                </div>
            </div>
        </div>
        <div class="box-footer">
            <input type='submit' name='save' id="btnsave" class='btn btn-primary' value="Simpan Data" /> 
        </div>
    <?php echo form_close(); ?>
</div>
<script>
    $("#btnsave").click(function(){
        submitdata();
        return false; 
    }); 
    $("#frmA").submit(function(){
        submitdata();
        return false; 
    }); 
    function submitdata(){
        
        var json_url = "<?php echo base_url() ?>admin/masters/daftar_rohaniawan/save";
         $.ajax({    
            type: "POST",
            url: json_url,
            data: $("#frm").serialize(),
            dataType: "json",
            success: function(data){ 
                if(data.success){
                    swal("Pemberitahuan!", data.msg, "success");
                    $("#modal-global").modal("hide");
                    grid_daftar.ajax.reload();
                }
                else {
                    $(".messages").empty().append(data.msg);
                }
            }});
        return false; 
    }
</script>