<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/select2/select2.min.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/css/dropzone/dropzone.min.css">
<script src="<?php echo base_url(); ?>themes/admin/js/dropzone/dropzone.min.js"></script>
<?php 
    $id = isset($jenis_izin->ID) ? $jenis_izin->ID : '';
?>
<div class='box box-warning' id="form-riwayat-assesmen-add">
    <div class="box-body">
        <div class="messages">
        </div>
    <?php echo form_open($this->uri->uri_string(), 'id="frm"'); ?>
        <fieldset>
            

            <div class="control-group<?php echo form_error('PNS_NIP') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("PEGAWAI" . lang('bf_form_label_required'), 'PNS_NIP', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select id="PNS_NIP" tabindex="1" name="PNS_NIP" width="100%" class="form-control select2">
                        <?php
                        if($selectedAtasanLangsung){
                            echo "<option selected value='".$selectedAtasanLangsung->PNS_ID."'>".$selectedAtasanLangsung->NAMA."</option>";
                        }
                        ?>
                    </select>
                    <span class='help-inline'><?php echo form_error('PNS_NIP'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('TAHUN') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label(lang('sisa_cuti_field_TAHUN') . lang('bf_form_label_required'), 'TAHUN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='TAHUN' tabindex="2" type='number' class="form-control" required='required' name='TAHUN' maxlength='4' value="<?php echo set_value('TAHUN', isset($sisa_cuti->TAHUN) ? $sisa_cuti->TAHUN : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('TAHUN'); ?></span>
                </div>
            </div>

            <div class="control-group  col-sm-3">
                <?php echo form_label("SISA TAHUN N", 'SISA_N', array('class' => 'control-label')); ?>
                <div class="input-group date">
                    <input id='SISA_N' type='number' name='SISA_N' class="form-control"  value="<?php echo set_value('SISA_N', isset($sisa_cuti->SISA_N) ? $sisa_cuti->SISA_N : $sisa_cuti->SISA_N); ?>" />
                    <span class="input-group-addon">Hari</i></span>
                </div>
            </div>
            <div class="control-group  col-sm-3">
                <?php echo form_label("SISA TAHUN N-1", 'SISA_N_1', array('class' => 'control-label')); ?>
                <div class="input-group date">
                    <input id='SISA_N_1' type='number' name='SISA_N_1' class="form-control"  value="<?php echo set_value('SISA_N_1', isset($sisa_cuti->SISA_N_1) ? $sisa_cuti->SISA_N_1 : $sisa_cuti->SISA_N_1); ?>" />
                    <span class="input-group-addon">Hari</i></span>
                </div>
            </div>
            <div class="control-group  col-sm-3">
                <?php echo form_label("SISA TAHUN N-2", 'SISA_N_2', array('class' => 'control-label')); ?>
                <div class="input-group date">
                    <input id='SISA_N_2' type='number' name='SISA_N_2' class="form-control"  value="<?php echo set_value('SISA_N_2', isset($sisa_cuti->SISA_N_2) ? $sisa_cuti->SISA_N_2 : $sisa_cuti->SISA_N_2); ?>" />
                    <span class="input-group-addon">Hari</i></span>
                </div>
            </div>
            <div class="control-group  col-sm-3">
                <?php echo form_label("JUMLAH CUTI", 'SISA', array('class' => 'control-label')); ?>
                <div class="input-group date">
                    <input id='SISA' type='number' name='SISA' class="form-control"  value="<?php echo set_value('SISA', isset($sisa_cuti->SISA) ? $sisa_cuti->SISA : $sisa_cuti->SISA); ?>" />
                    <span class="input-group-addon">Hari</i></span>
                </div>
            </div>
            <div class="control-group  col-sm-6">
                <?php echo form_label("SUDAH DIAMBIL TAHUN INI", 'SUDAH_DIAMBIL', array('class' => 'control-label')); ?>
                <div class="input-group date">
                    <input id='SUDAH_DIAMBIL' type='number' name='SUDAH_DIAMBIL' class="form-control"  value="<?php echo set_value('SUDAH_DIAMBIL', isset($sisa_cuti->SUDAH_DIAMBIL) ? $sisa_cuti->SUDAH_DIAMBIL : $sisa_cuti->SUDAH_DIAMBIL); ?>" />
                    <span class="input-group-addon">Hari</i></span>
                </div>
            </div>
            <div class="control-group  col-sm-6">
                <?php echo form_label("SISA", 'SUDAH_DIAMBIL', array('class' => 'control-label')); ?>
                <div class="input-group date">
                    <input id='jml_sisa' type='number' name='jml_sisa' class="form-control" disabled value="<?php echo set_value('jml_sisa', isset($sisa_cuti->jml_sisa) ? $sisa_cuti->jml_sisa : $sisa_cuti->jml_sisa); ?>" />
                    <span class="input-group-addon">Hari</i></span>
                </div>
            </div>
 
        </fieldset>
        </div>
        <div class="box-footer">
            <a href="javascript:;" id="btnsave"  class="btn green btn-primary button-submit"> 
                <i class="fa fa-save"></i> 
                Simpan
            </a>
             
        </div>
    <?php echo form_close(); ?>
</div>
<script>
    $("#btnsave").click(function(){
        submitdata();
        return false; 
    }); 
    $("#PNS_NIP").select2({
        placeholder: 'Cari Pegawai.....',
        width: '100%',
        minimumInputLength: 3,
        allowClear: true,
        ajax: {
            url: '<?php echo site_url("pegawai/kepegawaian/ajaxnip");?>',
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
    $("#SISA_N_2").change(function(){
        hitung();
    }); 
    $("#SISA_N_1").change(function(){
        hitung();
    }); 
    $("#SISA_N").change(function(){
        hitung();
    });
    $("#SISA").change(function(){
        hitung();
    }); 
    $("#SUDAH_DIAMBIL").change(function(){
        hitung();
    }); 
    function hitung(){
        var totsisa = 0;
        var sisan = $("#SISA_N").val();
        if(sisan == "")
            sisan = 0;
        var sisan1 = $("#SISA_N_1").val();
        if(sisan1 == "")
            sisan1 = 0;
        var sisan2 = $("#SISA_N_2").val();
        if(sisan2 == "")
            sisan2 = 0;

        var diambil = $("#SUDAH_DIAMBIL").val();
        if(diambil == "")
            diambil = 0;
        $("#SISA").val(parseInt(sisan)+parseInt(sisan1)+parseInt(sisan2));
        totsisa = parseInt(sisan)+parseInt(sisan1)+parseInt(sisan2)-parseInt(diambil);
        $("#jml_sisa").val(totsisa);

        
    }
</script>