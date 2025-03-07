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
<div class="callout callout-info">
   <h4>Perhatian!</h4>
   <p>Halaman ini untuk melihat/verifikasi data detil usulan perpindahan unitkerja</p>
 </div>
<div class='messages'></div>
<div class='box box-primary float-e-margins'>
    <div class="box-body ibox-content">
        <?php echo form_open($this->uri->uri_string(), 'id="frmpindah"'); ?>
            <fieldset>
                <div class="control-group<?php echo form_error('NIP') ? ' error' : ''; ?> col-sm-2">
                    <div class='controls'>
                     <b>PEGAWAI :</b>
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
                <div class="control-group<?php echo form_error('SURAT_PERMOHONAN_PINDAH') ? ' error' : ''; ?> col-sm-6">
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
                          <select name="SURAT_PERMOHONAN_PINDAH" id="SURAT_PERMOHONAN_PINDAH" class="form-control">
                            <option value="">-- Silahkan Pilih --</option>
                            <option value="1" <?php echo $pindah_unit->SURAT_PERMOHONAN_PINDAH == "1" ? "selected":""; ?> >Ada</option>
                            <option value="0" <?php echo $pindah_unit->SURAT_PERMOHONAN_PINDAH == "0" ? "selected":""; ?> >Tidak</option>
                          </select>
                   </div>
                </div>
                <div class="control-group<?php echo form_error('SURAT_PERMOHONAN_PINDAH') ? ' error' : ''; ?> col-sm-6">
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
                          <select name="SURAT_PERNYATAAN_MELEPAS" id="SURAT_PERNYATAAN_MELEPAS" class="form-control">
                            <option value="">-- Silahkan Pilih --</option>
                            <option value="1" <?php echo $pindah_unit->SURAT_PERNYATAAN_MELEPAS == "1" ? "selected":""; ?> >Ada</option>
                            <option value="0" <?php echo $pindah_unit->SURAT_PERNYATAAN_MELEPAS == "0" ? "selected":""; ?> >Tidak</option>
                          </select>
                   </div>
                </div>
                <div class="control-group<?php echo form_error('SK_KP_TERAKHIR') ? ' error' : ''; ?> col-sm-6">
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
                          <select name="SK_KP_TERAKHIR" id="SK_KP_TERAKHIR" class="form-control">
                            <option value="">-- Silahkan Pilih --</option>
                            <option value="1" <?php echo $pindah_unit->SK_KP_TERAKHIR == "1" ? "selected":""; ?> >Ada</option>
                            <option value="0" <?php echo $pindah_unit->SK_KP_TERAKHIR == "0" ? "selected":""; ?> >Tidak</option>
                          </select>
                   </div>
                </div>
                <div class="control-group<?php echo form_error('SK_JABATAN') ? ' error' : ''; ?> col-sm-6">
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
                          <select name="SK_JABATAN" id="SK_JABATAN" class="form-control">
                            <option value="">-- Silahkan Pilih --</option>
                            <option value="1" <?php echo $pindah_unit->SK_JABATAN == "1" ? "selected":""; ?> >Ada</option>
                            <option value="0" <?php echo $pindah_unit->SK_JABATAN == "0" ? "selected":""; ?> >Tidak</option>
                          </select>
                   </div>
                </div>

                <div class="control-group<?php echo form_error('SK_TUNKIN') ? ' error' : ''; ?> col-sm-6">
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
                          <select name="SK_TUNKIN" id="SK_TUNKIN" class="form-control">
                            <option value="">-- Silahkan Pilih --</option>
                            <option value="1" <?php echo $pindah_unit->SK_TUNKIN == "1" ? "selected":""; ?> >Ada</option>
                            <option value="0" <?php echo $pindah_unit->SK_TUNKIN == "0" ? "selected":""; ?> >Tidak</option>
                          </select>
                   </div>
                </div>
                <div class="control-group<?php echo form_error('SURAT_PERNYATAAN_MENERIMA') ? ' error' : ''; ?> col-sm-6">
                    <?php echo form_label(lang('pindah_unit_field_SURAT_PERNYATAAN_MENERIMA'), 'SURAT_PERNYATAAN_MENERIMA', array('class' => 'control-label')); ?>
                    <?php 
                    if($pindah_unit->SURAT_PERNYATAAN_MENERIMA != ''){
                      $SURAT_PERNYATAAN_MENERIMA = base_url().trim($this->settings_lib->item('site.urluploaded'))."layanan/".trim($pindah_unit->SURAT_PERNYATAAN_MENERIMA);
                    }else{
                      $SURAT_PERNYATAAN_MENERIMA = "#";
                    }
                    ?>
                    <div class="controls">
                          <select name="SURAT_PERNYATAAN_MENERIMA" id="SURAT_PERNYATAAN_MENERIMA" class="form-control">
                            <option value="">-- Silahkan Pilih --</option>
                            <option value="1" <?php echo $pindah_unit->SURAT_PERNYATAAN_MENERIMA == "1" ? "selected":""; ?> >Ada</option>
                            <option value="0" <?php echo $pindah_unit->SURAT_PERNYATAAN_MENERIMA == "0" ? "selected":""; ?> >Tidak</option>
                          </select>
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
                        <input type='text' class="form-control pull-right datepicker" name='TANGGAL_SK_PINDAH'  value="<?php echo set_value('TANGGAL_SK_PINDAH', isset($detail_riwayat->TANGGAL_SK_PINDAH) ? $detail_riwayat->TANGGAL_SK_PINDAH : ''); ?>" />
                        <span class='help-inline'><?php echo form_error('TANGGAL_SK_PINDAH'); ?></span>
                    </div>
                </div> 
                <div class="control-group col-sm-4">
                    <label for="inputNAMA" class="control-label">TMT PINDAH</label>
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                        <input type='text' class="form-control pull-right datepicker" name='TANGGAL_TMT_PINDAH'  value="<?php echo set_value('TANGGAL_TMT_PINDAH', isset($detail_riwayat->TANGGAL_TMT_PINDAH) ? $detail_riwayat->TANGGAL_TMT_PINDAH : ''); ?>" />
                        <span class='help-inline'><?php echo form_error('TANGGAL_TMT_PINDAH'); ?></span>
                    </div>
                </div> 
                <div class="control-group<?php echo form_error('FILE_SK') ? ' error' : ''; ?> col-sm-12">
                    <?php echo form_label(lang('pindah_unit_field_FILE_SK'), 'SURAT_PERNYATAAN_MENERIMA', array('class' => 'control-label')); ?>
                    <div class='controls'>
                        <div class="dropzone well well-sm drfilesk">
                        </div>
                    </div>
                    <div class="filefilesk">
                      <?php 
                      if($pindah_unit->FILE_SK != ''){
                        $FILE_SK = base_url().trim($this->settings_lib->item('site.urluploaded'))."layanan/".trim($pindah_unit->FILE_SK);
                      }else{
                        $FILE_SK = "#";
                      }
                      ?>
                        <div class="input-group divfilesk">
                           <div class="input-group-addon">File</div>
                           <input type="text" name="FILE_SK" value="<?php echo $pindah_unit->FILE_SK;?>" id="FILE_SK" class="form-control just-upload-field" />
                           <div class="input-group-addon"><a href="<?php echo $FILE_SK;?>" target="_blank"><i class="fa fa-mail-forward"></i></a></div>
                           <span class="input-group-btn">
                             <button type="button" kode="<?php echo $id; ?>" kolom="FILE_SK" kodediv="divfilesk" class="btn btn-danger btn-flat alertdelete">X</button>
                           </span>
                        </div>
                   </div>
                </div>
                 
                 
            </fieldset>
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
            <?php if ($this->auth->has_permission('Pindah_unit.Layanan.Biro')) { ?>
            <fieldset>
            <legend>Verifikasi (BIRO)</legend>
                <div class="callout callout-warning">
                   <h4>Checklist "TERIMA PENGAJUAN" jika pengajuan perpindahan unitkerja pegawai diterima oleh BIRO Kepegawaian</h4>
                   <P>Silahkan Cek ketersediaan formasi pada instansi yang dituju</P>
                   <P>
                    Silahkan isi pesan jika ingin memberikan pesan kepada unit pengusul, notifikasi pesan akan diberikan kepada unit pengusul dan unit asal si pegawai.
                   </P>
                 </div>
                <div class="control-group col-sm-4">
                    <label for="inputNAMA" class="control-label">TERIMA PENGAJUAN?</label>
                    <div class='controls'>
                        <select name="STATUS_BIRO" id="STATUS_BIRO" class="form-control">
                          <option value="">-- Silahkan Pilih --</option>
                          <option value="1" <?php echo $pindah_unit->STATUS_BIRO == "1" ? "selected":""; ?> >Diterima</option>
                          <option value="2" <?php echo $pindah_unit->STATUS_BIRO == "2" ? "selected":""; ?> >Proses</option>
                          <option value="3" <?php echo $pindah_unit->STATUS_BIRO == "3" ? "selected":""; ?> >Tidak Diterima</option>
                        </select>
                        <br>
                        <b>Pesan</b><br>
                        <textarea name="KETERANGAN" id="KETERANGAN" class="form-control"><?php echo $pindah_unit->KETERANGAN != "" ? TRIM($pindah_unit->KETERANGAN):""; ?></textarea>
                    </div>
                </div> 
                <div class="control-group col-sm-8">
                    <label for="inputNAMA" class="control-label">FORMASI JABATAN</label>
                    <div class='controls'>
                      <div class="callout callout-success">
                       <p>Dibawah ini adalah formasi jabatan pada unit kerja "<?php echo $selectedUnitTujuan->NAMA_UNOR; ?>" sesuai dengan jabatan yang dipilih yaitu jabatan "<?php echo ISSET($detiljabatan->NAMA_JABATAN) ? $detiljabatan->NAMA_JABATAN : ""; ?>" </p>
                     </div>
                        <table class="table table-datatable table-bordered">
                          <thead>
                              <tr>
                                  <th width='20px' >No</th>
                                  <th>JABATAN</th>
                                  <th>KELAS</th>
                                  <th width='100px' >BEZET</th>
                                  <th width='100px' >KBTHN</th>
                                  <th width='100px' >SELISIH</th>
                              </tr>
                          </thead>
                          
                          <tbody>
                             <?php
                             $nomor_urut=1;
                             if(isset($kuotajabatan) && is_array($kuotajabatan) && count($kuotajabatan)):
                              foreach ($kuotajabatan as $record) {
                                ?>
                                <tr>
                                    <td><?php echo $nomor_urut; ?></td>
                                    <td><?php echo $record->NAMA_JABATAN; ?></td>
                                    <td align="center"><?php echo $record->KELAS; ?></td>
                                    <td align="center"><?php echo $countpegawai_jabatan; ?></td>
                                    <td align="center"><?php echo $record->JUMLAH_PEMANGKU_JABATAN; 
                                      $JML_KUOTA = (int)$record->JUMLAH_PEMANGKU_JABATAN;
                                      $selisih = $countpegawai_jabatan - $JML_KUOTA;
                                      ?></td>
                                    <td valign="top" align="center" class="<?php echo $selisih<0 ? 'bg-blue' : ''; ?> <?php echo $selisih>=0 ? 'bg-red' : ''; ?>">
                                    <?php 
                                      
                                      echo $selisih; ?>
                                      
                                    </td>
                                </tr>
                              <?php
                                $nomor_urut++;
                              }
                            else:
                            ?>
                            <tfoot>
                              <tr>
                                 <td colspan="5">
                                  Tidak ada data Kuota Jabatan untuk jabatan "<?php echo ISSET($detiljabatan->NAMA_JABATAN) ? $detiljabatan->NAMA_JABATAN : ""; ?>"/ Jabatan "<?php echo ISSET($detiljabatan->NAMA_JABATAN) ? $detiljabatan->NAMA_JABATAN : ""; ?>" tidak tersedia pada unit kerja tujuan
                                 </td>     
                              </tr>
                          </tfoot>
                            <?php
                            endif;
                            ?>
                          </tbody>
                      </table>  
                    </div>
                </div> 
                <div class="control-group col-sm-4">
                    <div class="callout callout-success">
                      <h4>Download SK</h4>
                      <p>Silahkan masukan No SK Pindah dan Tanggal SK Pindah jika sudah di ketahui</p>
                      <a href="<?php echo base_url(); ?>admin/layanan/pindah_unit/cetak_sk/<?php echo $pindah_unit->ID; ?>" class="btn btn-warning btn-xs pull-right" target="_blank">Download File SK <i class="fa fa-cloud-download"></i></a>
                      <br>
                    </div>
                    <div class='controls'>
                        
                    </div>
                </div> 
                <div class="control-group col-sm-8">
                    <div class='controls'>
                      <div class="callout callout-success">
                         <p>Dibawah ini adalah riwayat SKP <?php echo $selectedpegawai->NAMA; ?></p>
                       </div>
                        <table class="table table-datatable table-bordered">
                          <thead>
                              <tr>
                                  <th width='20px' >No</th>
                                  <th>Tahun</th>
                                  <th>Nilai PPK</th>
                                  <th width='100px' >Nilai SKP</th>
                                  <th width='100px' >Nilai Perilaku</th>
                                  <th width='100px' >Jabatan</th>
                              </tr>
                          </thead>
                          <tfoot>
                              <tr>
                                      
                              </tr>
                          </tfoot>
                          <tbody>
                             <?php
                             $nomor_urut=1;
                             if(isset($recordppk) && is_array($recordppk) && count($recordppk)):
                              foreach ($recordppk as $record) {
                                        $row = array();
                                ?>
                                <tr>
                                    <td><?php echo $nomor_urut; ?></td>
                                    <td><?php echo $record->TAHUN; ?></td>
                                    <td><?php echo $record->NILAI_PPK; ?></td>
                                    <td><?php echo $record->NILAI_SKP; ?></td>
                                    <td><?php echo $record->NILAI_PERILAKU; ?></td>
                                    <td><?php echo $record->JABATAN_NAMA; ?></td>
                                </tr>
                              <?php
                                $nomor_urut++;
                              }
                            endif;
                            ?>
                          </tbody>
                      </table>  
                    </div>
                </div>
            </fieldset>
          <?php } ?>
            </div>
            <?php if ($this->auth->has_permission('Pindah_unit.Layanan.Biro') or $this->auth->has_permission('Pindah_unit.Layanan.UnitTujuan')) { ?>
            <div class="box-footer">
                <input type='submit' name='save' id="btnsave" class='btn btn-primary' value="SIMPAN" />
                
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
    <?php if ($this->auth->has_permission('Pindah_unit.Layanan.Biro') ) { ?>
    Dropzone.autoDiscover = false;
     

     var drfilesk = new Dropzone(".drfilesk",{
     autoProcessQueue: true,
     url: "<?php echo base_url() ?>admin/layanan/pindah_unit/uploadberkas",
     maxFilesize: 20,
     parallelUploads : 10,
     method:"post",
     acceptedFiles:"application/pdf",
     paramName:"userfile",
     dictDefaultMessage:"<img src='<?php echo base_url(); ?>assets/images/dropico.png' width='50px'/><br>Drop File SK pindah unit (jika diterima)",
     dictInvalidFileType:"Type file ini tidak dizinkan",
     addRemoveLinks:true
     });
     drfilesk.on("sending",function(a,b,c){
         a.token=Math.random();
         c.append('kolom',"FILE_SK");
         c.append('kode',"<?php echo isset($id) ? $id : ''; ?>");
         //console.log('mengirim');           
     });
     drfilesk.on("success",function(a,response){
        var obj = jQuery.parseJSON(response)
        $("#FILE_SK").val(obj.namafile);
        $('#FILE_SK').attr('readonly', true);

         drfilesk.removeAllFiles(true);
     });
   <?php } ?>

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