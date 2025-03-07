
<div class='messages'>
</div>
<?php
$id = isset($kuota_jabatan->ID) ? $kuota_jabatan->ID : '';

?>
<div class='alert alert-block alert-info fade in'>
    <a class='close' data-dismiss='alert'>&times;</a>
    <h4 class='alert-heading'>
        Perhatian
    </h4>
    <p>Ajuan Tahun <?php echo set_value('tahun', isset($request_formasi->tahun) ? $request_formasi->tahun : trim($this->settings_lib->item('peta_tahun'))); ?></p>
    <p>Pada Unit <?php echo $unit_kerja->NAMA_UNOR; ?></p>
</div>
<div class='admin-box box box-warning'>
    <?php echo form_open($this->uri->uri_string(), 'id="frm"'); ?>
    <input id='unit_id' type='hidden' class="form-control" name='unit_id' maxlength='255' value="<?php echo set_value('unit_id', isset($unit_kerja->ID) ? $unit_kerja->ID : ''); ?>" />
    <input id='satker_id' type='hidden' class="form-control" name='satker_id' maxlength='255' value="<?php echo set_value('satker_id', isset($unit_kerja->UNOR_INDUK) ? $unit_kerja->UNOR_INDUK : ''); ?>" />
    <input id='tahun' type='hidden' class="form-control" name='tahun' maxlength='4' value="<?php echo set_value('tahun', isset($request_formasi->tahun) ? $request_formasi->tahun : trim($this->settings_lib->item('peta_tahun'))); ?>" />
    <input id='id' type='hidden' class="form-control" name='id' maxlength='10' value="<?php echo isset($request_formasi->id) ? $request_formasi->id : ''; ?>" />
         <div class="box-body">
            <div class="control-group<?php echo form_error('ID_JABATAN') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Jabatan", 'ID_JABATAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select name="id_jabatan" class="form-control select2">
                        <?php 
                            if($jabatans){
                                echo "<option selected value='".$jabatans->KODE_JABATAN."'>".$jabatans->NAMA_JABATAN."</option>";
                            }
                        ?>
                    </select>
                    <span class='help-inline'><?php echo form_error('ID_JABATAN'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('kualifikasi_pendidikan') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Kualifikasi Pendidikan", 'kualifikasi_pendidikan', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <textarea name="kualifikasi_pendidikan" class="form-control"><?php echo isset($request_formasi->kualifikasi_pendidikan) ? $request_formasi->kualifikasi_pendidikan : ''; ?></textarea>
                    <span class='help-inline'><?php echo form_error('kualifikasi_pendidikan'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('kebutuhan') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Usul Kebutuhan", 'kebutuhan', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='jumlah_ajuan' type='number' class="form-control" name='jumlah_ajuan' maxlength='10' value="<?php echo set_value('jumlah_ajuan', isset($request_formasi->jumlah_ajuan) ? $request_formasi->jumlah_ajuan : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('kebutuhan'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('skala_prioritas') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Skala Prioritas", 'skala_prioritas', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select name="skala_prioritas" class="form-control select2">
                       <option value=""> Silahkan Pilih </option>
                       <option value="1" <?php echo $request_formasi->skala_prioritas == "1" ? "selected":""; ?>> 1 </option>
                       <option value="2" <?php echo $request_formasi->skala_prioritas == "2" ? "selected":""; ?>> 2 </option>
                    </select>
                    <span class='help-inline'><?php echo form_error('skala_prioritas'); ?></span>
                </div>
            </div>
        </div>
        <div class="box-footer">
            <a href="javascript:;" id="btnsave" class="btn green btn-primary button-submit"> Simpan
                <i class="fa fa-save"></i>
            </a>
        </div>
    <?php echo form_close(); ?>
</div>
<script>
    $("#btnsave").click(function(){
        $('#btnsave').text('Sedang Mengirim...');
        $('#btnsave').addClass('disabled');
        submitdata();
        return false; 
    }); 
    

$("#ID_JABATAN").select2({
    placeholder: 'Cari Nama Jabatan...',
    width: '100%',
    minimumInputLength: 0,
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
    function submitdata(){
        var json_url = "<?php echo base_url() ?>admin/masters/petajabatan/save_ajuan";
         $.ajax({    
            type: "POST",
            url: json_url,
            data: $("#frm").serialize(),
            dataType: "json",
            success: function(data){ 
                if(data.success){
                    swal("Pemberitahuan!", data.msg, "success");
                    $("#modal-global").modal("hide");
                    showdata();
                }
                else {
                    $(".messages").empty().append(data.msg);
                }
                $('#btnsave').removeClass('disabled');
                $('#btnsave').text('Simpan');
            }});
        return false; 
    }
</script>