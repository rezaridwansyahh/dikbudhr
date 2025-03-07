<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/iCheck/all.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<script src="<?php echo base_url(); ?>themes/admin/js/jqueryvalidation/jquery.validate.js"></script>
<!-- InputMask -->
<script src="<?php echo base_url(); ?>themes/admin/plugins/iCheck/icheck.min.js"></script>

<script src="<?php echo base_url(); ?>themes/admin/js/jqueryvalidation/jquery.validate.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/css/dropzone/dropzone.min.css">
<script src="<?php echo base_url(); ?>themes/admin/js/dropzone/dropzone.min.js"></script>
<?php
$verifikasibiro = $this->auth->has_permission('Pindah_unit.Layanan.Biro');
if (validation_errors()) :
?>
<div class='alert alert-block alert-error fade in'>
    <a class='close' data-dismiss='alert'>&times;</a>
    <h4 class='alert-heading'>
        <?php echo lang('pindah_unit_errors_message'); ?>
    </h4>
    <?php echo validation_errors(); ?>
</div>
<?php
endif;

$id = isset($pindah_unit->ID) ? $pindah_unit->ID : '';

?>
 
<div class='messages'></div>
<div class='box box-primary float-e-margins'>
    <div class="box-body ibox-content">
        <?php echo form_open($this->uri->uri_string(), 'id="frmpindah"'); ?>
            <fieldset>
                <div class="control-group<?php echo form_error('NIP') ? ' error' : ''; ?> col-sm-2">
                    <div class='controls'>
                     <b>NIP :</b>
                    </div>
                </div>
                <div class="control-group<?php echo form_error('NIP') ? ' error' : ''; ?> col-sm-10">
                    <div class='controls'>
                      <b><?php echo $selectedpegawai->NIP_BARU; ?></b>
                    </div>
                </div>
                <div class="control-group<?php echo form_error('NIP') ? ' error' : ''; ?> col-sm-2">
                    <div class='controls'>
                     <b>NAMA :</b>
                    </div>
                </div>
                <div class="control-group<?php echo form_error('NIP') ? ' error' : ''; ?> col-sm-10">
                    <div class='controls'>
                      <b><?php echo $selectedpegawai->NAMA; ?></b>
                    </div>
                </div>
                <div class="control-group<?php echo form_error('UNIT_TUJUAN') ? ' error' : ''; ?> col-sm-2">
                    <div class='controls'>
                        <b>UNIT ASAL : </b>
                    </div>
                </div>
                <div class="control-group<?php echo form_error('UNIT_TUJUAN') ? ' error' : ''; ?> col-sm-10">
                    <div class='controls'>
                      <b>
                        <?php echo $selectedUnitAsal->NAMA_UNOR_ESELON_4; ?>, 
                        <?php echo $selectedUnitAsal->NAMA_UNOR_ESELON_3; ?>, 
                        <?php echo $selectedUnitAsal->NAMA_UNOR_ESELON_2; ?>, 
                        <?php echo $selectedUnitAsal->NAMA_UNOR_ESELON_1; ?>
                      </b>
                    </div>
                </div>
                <div class="control-group<?php echo form_error('UNIT_TUJUAN') ? ' error' : ''; ?> col-sm-2">
                    <div class='controls'>
                        <b>UNIT TUJUAN : </b>
                    </div>
                </div>
                <div class="control-group<?php echo form_error('UNIT_TUJUAN') ? ' error' : ''; ?> col-sm-10">
                    <div class='controls'>
                      <b>
                        <?php echo $selectedUnitTujuan->NAMA_UNOR_ESELON_4; ?>, 
                        <?php echo $selectedUnitTujuan->NAMA_UNOR_ESELON_3; ?>, 
                        <?php echo $selectedUnitTujuan->NAMA_UNOR_ESELON_2; ?>, 
                        <?php echo $selectedUnitTujuan->NAMA_UNOR_ESELON_1; ?>
                      </b>
                    </div>
                </div>
                <div class="control-group<?php echo form_error('SURAT_PERMOHONAN_PINDAH') ? ' error' : ''; ?> col-sm-4">
                    <?php echo form_label("SURAT PERMOHONAN PINDAH", 'SURAT_PERMOHONAN_PINDAH', array('class' => 'control-label')); ?>
                    <?php 
                    if($pindah_unit->SURAT_PERMOHONAN_PINDAH != ''){
                      $urlpermohonanpindah = base_url().trim($this->settings_lib->item('site.urluploaded'))."layanan/".trim($pindah_unit->SURAT_PERMOHONAN_PINDAH);
                    }else{
                      $urlpermohonanpindah = "#";
                    }
                    //echo $urlpermohonanpindah;
                    ?>
                    <div class="controls">
                        <input type="checkbox" name="SURAT_PERMOHONAN_PINDAH" value="1" class="minimal" <?php echo $pindah_unit->SURAT_PERMOHONAN_PINDAH == "1" ? "checked":""; ?>>
                   </div>
                </div>
                <div class="control-group<?php echo form_error('SURAT_PERMOHONAN_PINDAH') ? ' error' : ''; ?> col-sm-4">
                    <?php echo form_label(lang('pindah_unit_field_SURAT_PERNYATAAN_MELEPAS'), 'SURAT_PERNYATAAN_MELEPAS', array('class' => 'control-label')); ?>
                    <?php 
                    if($pindah_unit->SURAT_PERNYATAAN_MELEPAS != ''){
                      $SURAT_PERNYATAAN_MELEPAS = base_url().trim($this->settings_lib->item('site.urluploaded'))."layanan/".trim($pindah_unit->SURAT_PERNYATAAN_MELEPAS);
                    }else{
                      $SURAT_PERNYATAAN_MELEPAS = "#";
                    }
                    //echo $urlpermohonanpindah;
                    ?>
                    <div class="controls">
                        <input type="checkbox" name="SURAT_PERNYATAAN_MELEPAS" value="1" class="minimal" <?php echo $pindah_unit->SURAT_PERNYATAAN_MELEPAS == "1" ? "checked":""; ?>>
                   </div>
                </div>
                <div class="control-group<?php echo form_error('SK_KP_TERAKHIR') ? ' error' : ''; ?> col-sm-4">
                    <?php echo form_label(lang('pindah_unit_field_SK_KP_TERAKHIR'), 'SK_KP_TERAKHIR', array('class' => 'control-label')); ?>
                    <?php 
                    if($pindah_unit->SK_KP_TERAKHIR != ''){
                      $SK_KP_TERAKHIR = base_url().trim($this->settings_lib->item('site.urluploaded'))."layanan/".trim($pindah_unit->SK_KP_TERAKHIR);
                    }else{
                      $SK_KP_TERAKHIR = "#";
                    }
                    //echo $urlpermohonanpindah;
                    ?>
                    <div class="controls">
                        <input type="checkbox" name="SK_KP_TERAKHIR" value="1" class="minimal" <?php echo $pindah_unit->SK_KP_TERAKHIR == "1" ? "checked":""; ?>>
                   </div>
                </div>
                <div class="control-group<?php echo form_error('SK_JABATAN') ? ' error' : ''; ?> col-sm-4">
                    <?php echo form_label(lang('pindah_unit_field_SK_JABATAN'), 'SK_JABATAN', array('class' => 'control-label')); ?>
                    <?php 
                    if($pindah_unit->SK_JABATAN != ''){
                      $SK_JABATAN = base_url().trim($this->settings_lib->item('site.urluploaded'))."layanan/".trim($pindah_unit->SK_JABATAN);
                    }else{
                      $SK_JABATAN = "#";
                    }
                    //echo $urlpermohonanpindah;
                    ?>
                    <div class="controls">
                      <input type="checkbox" name="SK_JABATAN" value="1" class="minimal" <?php echo $pindah_unit->SK_JABATAN == "1" ? "checked":""; ?>>
                   </div>
                </div>

                <div class="control-group<?php echo form_error('SK_TUNKIN') ? ' error' : ''; ?> col-sm-4">
                    <?php echo form_label(lang('pindah_unit_field_SK_TUNKIN'), 'SK_TUNKIN', array('class' => 'control-label')); ?>
                    <?php 
                    if($pindah_unit->SK_TUNKIN != ''){
                      $SK_TUNKIN = base_url().trim($this->settings_lib->item('site.urluploaded'))."layanan/".trim($pindah_unit->SK_TUNKIN);
                    }else{
                      $SK_TUNKIN = "#";
                    }
                    //echo $urlpermohonanpindah;
                    ?>
                    <div class="controls">
                      <input type="checkbox" name="SK_TUNKIN" value="1" class="minimal" <?php echo $pindah_unit->SK_TUNKIN == "1" ? "checked":""; ?>>
                   </div>
                </div>
                <div class="control-group<?php echo form_error('SURAT_PERNYATAAN_MENERIMA') ? ' error' : ''; ?> col-sm-4">
                    <?php echo form_label(lang('pindah_unit_field_SURAT_PERNYATAAN_MENERIMA'), 'SURAT_PERNYATAAN_MENERIMA', array('class' => 'control-label')); ?>
                    <?php 
                    if($pindah_unit->SURAT_PERNYATAAN_MENERIMA != ''){
                      $SURAT_PERNYATAAN_MENERIMA = base_url().trim($this->settings_lib->item('site.urluploaded'))."layanan/".trim($pindah_unit->SURAT_PERNYATAAN_MENERIMA);
                    }else{
                      $SURAT_PERNYATAAN_MENERIMA = "#";
                    }
                    ?>
                    <div class="controls">
                      <input type="checkbox" name="SURAT_PERNYATAAN_MENERIMA" value="1" class="minimal" <?php echo $pindah_unit->SURAT_PERNYATAAN_MENERIMA == "1" ? "checked":""; ?>>
                   </div>
                </div>
                <div class="control-group<?php echo form_error('SKP') ? ' error' : ''; ?> col-sm-12">
                    <?php echo form_label("PPK", 'SKP', array('class' => 'control-label')); ?>
                    
                    <div class="controls">
                          <select name="SKP" id="SKP" class="form-control">
                            <option value="">-- Silahkan Pilih --</option>
                            <option value="1" <?php echo $pindah_unit->SKP == "1" ? "selected":""; ?> >Lengkap</option>
                            <option value="2" <?php echo $pindah_unit->SKP == "2" ? "selected":""; ?> >Tidak Lengkap</option>
                            <option value="0" <?php echo $pindah_unit->SKP == "0" ? "selected":""; ?> >Tidak Ada</option>
                          </select>
                   </div>
                </div>
         
            </fieldset>
            
            <!--
            <?php if ($this->auth->has_permission('Pindah_unit.Layanan.UnitTujuan')) { ?>
            <fieldset>
                <legend>Verifikasi (UNIT ESELON 1)</legend>
                <div class="callout callout-warning">
                   <h4>Checklist "TERIMA PENGAJUAN" jika pengajuan perpindahan unitkerja diterima</h4>
                 </div>
                <div class="control-group col-sm-6">
                    <label for="inputNAMA" class="control-label">TERIMA PENGAJUAN?</label>
                    <div class='controls'>
                        <input id='STATUS_SATKER' type='checkbox' name='STATUS_SATKER' value="1" <?php echo $pindah_unit->STATUS_SATKER == "1" ? "checked" : ""; ?> />
                        <span class='help-inline'><?php echo form_error('STATUS_SATKER'); ?></span>
                    </div>
                </div> 
            </fieldset>
          <?php } ?>
        -->
            <?php if ($this->auth->has_permission('Pindah_unit.Layanan.Biro')) { ?>
            <fieldset>
            <legend>Verifikasi (BIRO)</legend>
            <?php
                 $nomor_urut=1;
                 if(isset($kuotajabatan) && is_array($kuotajabatan) && count($kuotajabatan)):
                  foreach ($kuotajabatan as $record) {
                    $NAMA_JABATAN = $record->NAMA_JABATAN;
                    
                      $JML_KUOTA = (int)$record->JUMLAH_PEMANGKU_JABATAN;
                      $selisih = $countpegawai_jabatan - $JML_KUOTA;
                       
                    $nomor_urut++;
                  }
                endif;
              ?>
              <?php if($selisih<0){ ?>
                <div class="callout callout-success">
                   <P>
                    Kuota Jabatan "<?php echo $NAMA_JABATAN; ?>" Tersedia pada unit kerja tujuan
                   </P>
                 </div>
                <?php }else{ ?>
                  <div class="callout callout-danger">
                   <P>
                    Kuota Jabatan "<?php echo $NAMA_JABATAN; ?>" tidak Tersedia
                   </P>
                 </div>
                <?php }?>
                <div class="control-group<?php echo form_error('NO_SK_PINDAH') ? ' error' : ''; ?> col-sm-4">
                    <?php echo form_label(lang('pindah_unit_field_NO_SK_PINDAH'), 'NO_SK_PINDAH', array('class' => 'control-label')); ?>
                    <div class='controls'>
                        <input id='NO_SK_PINDAH' class="form-control" type='text' name='NO_SK_PINDAH' maxlength='100' value="<?php echo set_value('NO_SK_PINDAH', isset($pindah_unit->NO_SK_PINDAH) ? $pindah_unit->NO_SK_PINDAH : ''); ?>" />
                        <span class='help-inline'><?php echo form_error('NO_SK_PINDAH'); ?></span>
                    </div>
                </div>
                <div class="control-group col-sm-4">
                    <label for="inputNAMA" class="control-label">TANGGAL SK PINDAH</label>
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                        <input type='text' class="form-control pull-right datepicker" name='TANGGAL_SK_PINDAH'  value="<?php echo set_value('TANGGAL_SK_PINDAH', isset($pindah_unit->TANGGAL_SK_PINDAH) ? $pindah_unit->TANGGAL_SK_PINDAH : ''); ?>" />
                        <span class='help-inline'><?php echo form_error('TANGGAL_SK_PINDAH'); ?></span>
                    </div>
                </div> 
                <div class="control-group col-sm-4">
                    <label for="inputNAMA" class="control-label">TMT PINDAH</label>
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                        <input type='text' class="form-control pull-right datepicker" name='TANGGAL_TMT_PINDAH'  value="<?php echo set_value('TANGGAL_TMT_PINDAH', isset($pindah_unit->TANGGAL_TMT_PINDAH) ? $pindah_unit->TANGGAL_TMT_PINDAH : ''); ?>" />
                        <span class='help-inline'><?php echo form_error('TANGGAL_TMT_PINDAH'); ?></span>
                    </div>
                </div> 
                 
                <div class="control-group col-sm-8">
                    <label for="inputNAMA" class="control-label">TERIMA PENGAJUAN?</label>
                    <div class='controls'>
                      <input type="hidden" name="FILE_SK" value="<?php echo $pindah_unit->FILE_SK;?>" id="FILE_SK" class="form-control just-upload-field" />
                        <select name="STATUS_BIRO" id="STATUS_BIRO" class="form-control">
                          <option value="">-- Silahkan Pilih --</option>
                          <option value="1" <?php echo $pindah_unit->STATUS_BIRO == "1" ? "selected":""; ?> >Diterima</option>
                          <option value="2" <?php echo $pindah_unit->STATUS_BIRO == "2" ? "selected":""; ?> >Proses</option>
                          <option value="3" <?php echo $pindah_unit->STATUS_BIRO == "3" ? "selected":""; ?> >Tidak Diterima</option>
                          <option value="4" <?php echo $pindah_unit->STATUS_BIRO == "4" ? "selected":""; ?> >Berkas Belum Lengkap</option>
                        </select>
                        <br>
                        <b>Pesan</b><br>
                        <textarea name="KETERANGAN" id="KETERANGAN" class="form-control"><?php echo $pindah_unit->KETERANGAN != "" ? TRIM($pindah_unit->KETERANGAN):""; ?></textarea>
                    </div>
                </div> 
                 
                <div class="control-group col-sm-4">
                    <label></label>
                    <div class='controls'>
                        <div class="callout callout-success">
                          <h4>Download SK</h4>
                          <a href="<?php echo base_url(); ?>admin/layanan/pindah_unit/cetak_sk/<?php echo $pindah_unit->ID; ?>" class="btn btn-warning btn-xs pull-right" target="_blank">Download File SK <i class="fa fa-cloud-download"></i></a>
                          <br>
                        </div>
                        <BR>
                        <input type="checkbox" name="UPDATE_PROFILE" value="1" class="minimal"> <b>Update Profile Utama</b>
                    </div>
                </div> 
                
            </fieldset>
          <?php } ?>
            </div>
            <?php if ($this->auth->has_permission('Pindah_unit.Layanan.Biro') or $this->auth->has_permission('Pindah_unit.Layanan.UnitTujuan')) { ?>
            <div class="box-footer">
                <input type='submit' name='save' id="btnsave" class='btn btn-primary btnsave' value="SIMPAN" />
                
            </div>
          <?php } ?>
        <?php echo form_close(); ?>
    </div>
</div>
<script src="<?php echo base_url(); ?>themes/admin/plugins/select2/select2.full.min.js"></script>

<script>
     $(".select2").select2();
</script>
<script>
    $('.datepicker').datepicker({
      autoclose: true,format: 'yyyy-mm-dd'
    });
    

    $("#NIP").select2({
        placeholder: 'Cari Pegawai...',
        width: '100%',
        minimumInputLength: 3,
        allowClear: true,
        ajax: {
            url: '<?php echo site_url("admin/kepegawaian/pegawai/ajax");?>',
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
    $("#UNIT_TUJUAN").select2({
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
    <?php if ($this->auth->has_permission('Pindah_unit.Layanan.Biro') or $this->auth->has_permission('Pindah_unit.Layanan.UnitTujuan')) { ?>
    $("#frmpindah").validate({
        submitHandler: function(form) {
        $("#btnsave").val('Menyimpan Data......').attr('disabled', true).addClass('bt-hud').unbind('click');
        submitdata();
    },
      rules: {
        NIP: {
          required: false
        },
        UNIT_TUJUAN: {
            required: true
        },
        

      } 


    });
    function submitdata(){
        
        var json_url = "<?php echo base_url() ?>admin/layanan/pindah_unit/verifikasiajuan/<?php echo $id; ?>";
         $.ajax({    
            type: "post",
            url: json_url,
            data: $("#frmpindah").serialize(),
            dataType: "json",
            success: function(data){ 
                if(data.success){
                    swal("Pemberitahuan!", data.msg, "success");
                    $("#btnsave").val('SIMPAN').attr('disabled', false).addClass('bt-hud').unbind('click');
                }
                else {
                    $(".messages").empty().append(data.msg);
                }
            }});
        return false; 
    }
  <?php } ?>
</script>
<script type="text/javascript">
$('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass: 'iradio_minimal-blue'
    });
$('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass: 'iradio_flat-green'
    });
$('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
      checkboxClass: 'icheckbox_minimal-red',
      radioClass: 'iradio_minimal-red'
    });
</script>
