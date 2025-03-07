<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/select2/select2.min.css">
<script src="<?php echo base_url(); ?>themes/admin/plugins/select2/select2.full.min.js"></script>
<script src="<?php echo base_url(); ?>themes/admin/js/jqueryvalidation/jquery.validate.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/daterangepicker/daterangepicker.css">
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/datepicker/datepicker3.css">
<?php 
	$this->load->library('convert');
 	$convert = new convert();
    $tab_pendidikan = "tab_pendidikan";//uniqid("tab_pane_pendidikan");
    $tab_pane_personal_id = "tab_pane_personal";//uniqid("tab_pane_personal");
    $tab_pane_penilaian = "tab_pane_penilaian";//uniqid("tab_pane_kepangkatan");
    $tab_pane_riwayat = "tab_pane_riwayat";//uniqid("tab_pane_pindah_unit_kerja");
?>

<?php

if (validation_errors()) :
?>
<div class='alert alert-block alert-error fade in'>
    <a class='close' data-dismiss='alert'>&times;</a>
    <h4 class='alert-heading'>
        <?php echo lang('pegawai_errors_message'); ?>
    </h4>
    <?php echo validation_errors(); ?>
</div>
<?php
endif;
    $id = isset($pegawai->ID) ? $pegawai->ID : '';
    $PNS_ID = isset($pegawai->PNS_ID) ? $pegawai->PNS_ID : '';
?>
<div class="callout callout-success">
   <h4>Perhatian</h4>
   <P>Silahkan pilih dua pegawai yang akan di bandingkan</P>
 </div>
<div class="row">
  <div class="control-group col-sm-6">
      <div class='controls'>
          <select name="NIP1" id="NIP1" class="form-control select2">
            <option value="<?php echo $selectedpegawai->ID; ?>" selected><b><?php echo $selectedpegawai->NAMA; ?></b></option>
          </select>
      </div>
  </div>
  <div class="control-group col-sm-6">
      <div class='controls'>
          <select name="NIP2" id="NIP2" class="form-control select2">
            <option value="">-- Silahkan Pilih --</option>
            <option value="<?php echo $selectedpegawai2->ID; ?>" selected><b><?php echo $selectedpegawai2->NAMA; ?></b></option>
          </select>
      </div>
  </div>
</div>
<br>
<div class="row">
    <!-- /.col -->
    <div class="col-md-12">
    <?php
      $this->load->library('convert');
      $convert = new convert();
    ?>
    <div class="tab-pane active" id="<?php echo $TAB_ID;?>">
    
        <form role="form" action="#" id="frmprofile">
        <input id='ID' type='hidden' class="form-control" name='ID' maxlength='25' value="<?php echo set_value('ID', isset($pegawai->ID) ? $pegawai->ID : ''); ?>" />
        <div class="box box-info">
                  <!-- /.box-header -->
                  <div class="box-body">
                  <fieldset>
                      <div class="control-group col-sm-12">
                           <div class="row">
                              <div class="col-sm-2">
                               </div>
                               <div class="col-sm-5">
                                  <?php if(isset($pegawai->NIP_BARU)!= ""){ ?>
                                   <img src="<?php echo base_url(); ?><?php echo $foto_pegawai; ?>" class="img-responsive pic-bordered" id="photopegawai" alt="Photo" width="200px">
                                  <?php } ?>
                               </div>
                               <div class="col-sm-5">
                                <?php if(isset($pegawai2->NIP_BARU)!= ""){ ?>
                                   <img src="<?php echo base_url(); ?><?php echo $foto_pegawai2; ?>" class="img-responsive pic-bordered" id="photopegawai" alt="Photo" width="200px">
                                <?php } ?>
                               </div>
                           </div>
                       </div>
                      <div class="control-group col-sm-12">
                           <div class="row">
                               <div class="col-sm-2">
                                   NIP
                               </div>
                               <div class="form-group col-sm-5">
                                  <b><?php echo isset($pegawai->NIP_BARU) ? $pegawai->NIP_BARU : ''; ?></b>
                               </div>

                               <div class="form-group col-sm-5">
                                  <b><?php echo isset($pegawai2->NIP_BARU) ? $pegawai2->NIP_BARU : ''; ?></b>
                               </div>
                           </div>
                       </div>
                       <div class="control-group col-sm-12">
                           <div class="row">
                               <div class="col-sm-2">
                                   NAMA
                               </div>
                               <div class="form-group col-sm-5">
                                  <b>
                                    <?php echo isset($pegawai->GELAR_DEPAN) ? $pegawai->GELAR_DEPAN : ''; ?> 
                                    <?php echo isset($pegawai->NAMA) ? $pegawai->NAMA : ''; ?>
                                    <?php echo isset($pegawai->GELAR_BELAKANG) ? $pegawai->GELAR_BELAKANG : ''; ?>
                                  </b>
                               </div>
                               <div class="form-group col-sm-5">
                                  <b>
                                    <?php echo isset($pegawai2->GELAR_DEPAN) ? $pegawai2->GELAR_DEPAN : ''; ?> 
                                    <?php echo isset($pegawai2->NAMA) ? $pegawai2->NAMA : ''; ?>
                                    <?php echo isset($pegawai2->GELAR_BELAKANG) ? $pegawai2->GELAR_BELAKANG : ''; ?>    
                                  </b>
                               </div>
                           </div>
                       </div>
                       <div class="control-group col-sm-12">
                           <div class="row">
                               <div class="col-sm-2">
                                   NIK
                               </div>
                               <div class="form-group col-sm-5">
                                  <b><?php echo isset($pegawai->NIK) ? $pegawai->NIK : ''; ?></b>
                               </div>

                               <div class="form-group col-sm-5">
                                  <b><?php echo isset($pegawai2->NIK) ? $pegawai2->NIK : ''; ?></b>
                               </div>
                           </div>
                       </div>
                      <div class="form-group col-sm-12">
                        <div class="row">
                          <div class="col-sm-2">
                            Tempat/Tanggal Lahir
                          </div>
                          <div class="col-sm-5">
                            <?php echo isset($selectedTempatLahirPegawai->NAMA) ? $selectedTempatLahirPegawai->NAMA : ""; ?> / 
                                <?php echo isset($pegawai->TGL_LAHIR) ? $convert->fmtDate($pegawai->TGL_LAHIR,"dd month yyyy") : 'TGL_LAHIR'; ?>
                          </div> 
                          <div class="col-sm-5">
                                  <?php echo isset($selectedTempatLahirPegawai2->NAMA) ? $selectedTempatLahirPegawai2->NAMA : ""; ?> / 
                                <?php echo isset($pegawai2->TGL_LAHIR) ? $convert->fmtDate($pegawai2->TGL_LAHIR,"dd month yyyy") : 'TGL_LAHIR'; ?>
                          </div>          
                        </div>
                      </div>

                      <div class="form-group col-sm-12">
                        <div class="row">
                          <div class="col-sm-2">
                              Jabatan
                          </div>
                          <div class="col-sm-5">
                              <?php if($pegawai->JENIS_JABATAN_ID == "1") {  ?>
                                 <b><?php if($pegawai->JENIS_JABATAN_ID == "1") { echo isset($NAMA_JABATAN) ? $NAMA_JABATAN  : ""; } ?></b>
                                 <b>TMT <?php if($pegawai->JENIS_JABATAN_ID == "1") { echo isset($pegawai->TMT_JABATAN) ? $convert->fmtDate($pegawai->TMT_JABATAN,"dd month yyyy")  : ""; } ?></b>           
                              <?php } ?>
                              <?php if($pegawai->JENIS_JABATAN_ID == "2") {  ?>
                                  <b><?php if($pegawai->JENIS_JABATAN_ID == "2") { echo isset($NAMA_JABATAN) ? $NAMA_JABATAN  : ""; } ?></b>
                                  <b>TMT <?php if($pegawai->JENIS_JABATAN_ID == "2") { echo isset($pegawai->TMT_JABATAN) ? $convert->fmtDate($pegawai->TMT_JABATAN,"dd month yyyy")  : ""; } ?></b>
                              <?php } ?>
                              <?php if($pegawai->JENIS_JABATAN_ID == "4") { ?>
                                  <b><?php if($pegawai->JENIS_JABATAN_ID == "4") { echo isset($NAMA_JABATAN) ? $NAMA_JABATAN  : ""; }?></b>
                                  <b>TMT <?php if($pegawai->JENIS_JABATAN_ID == "4") { echo isset($pegawai->TMT_JABATAN) ? $convert->fmtDate($pegawai->TMT_JABATAN,"dd month yyyy")  : ""; } ?></b>    
                              <?php } ?>
                          </div> 
                          <div class="col-sm-5">
                                <?php if($pegawai2->JENIS_JABATAN_ID == "1") {  ?>
                                 <b><?php if($pegawai2->JENIS_JABATAN_ID == "1") { echo isset($NAMA_JABATAN2) ? $NAMA_JABATAN2  : ""; } ?></b>
                                 <b>TMT <?php if($pegawai2->JENIS_JABATAN_ID == "1") { echo isset($pegawai2->TMT_JABATAN) ? $convert->fmtDate($pegawai2->TMT_JABATAN,"dd month yyyy")  : ""; } ?></b>           
                              <?php } ?>
                              <?php if($pegawai2->JENIS_JABATAN_ID == "2") {  ?>
                                  <b><?php if($pegawai2->JENIS_JABATAN_ID == "2") { echo isset($NAMA_JABATAN2) ? $NAMA_JABATAN2  : ""; } ?></b>
                                  <b>TMT <?php if($pegawai2->JENIS_JABATAN_ID == "2") { echo isset($pegawai2->TMT_JABATAN) ? $convert->fmtDate($pegawai2->TMT_JABATAN,"dd month yyyy")  : ""; } ?></b>
                              <?php } ?>
                              <?php if($pegawai2->JENIS_JABATAN_ID == "4") { ?>
                                  <b><?php if($pegawai2->JENIS_JABATAN_ID == "4") { echo isset($NAMA_JABATAN2) ? $NAMA_JABATAN2  : ""; }?></b>
                                  <b>TMT <?php if($pegawai2->JENIS_JABATAN_ID == "4") { echo isset($pegawai2->TMT_JABATAN) ? $convert->fmtDate($pegawai2->TMT_JABATAN,"dd month yyyy")  : ""; } ?></b>    
                              <?php } ?>
                          </div>            
                        </div>
                      </div>
             <div class="control-group col-sm-12">
               <div class="row">
                          <div class="col-sm-2">
                   EMAIL
                 </div>
                  <div class="form-group col-sm-5">
                      <?php echo set_value('EMAIL', isset($pegawai->EMAIL) ? $pegawai->EMAIL : ''); ?>
                  </div>
                  <div class="form-group col-sm-5">
                      <?php echo set_value('EMAIL', isset($pegawai2->EMAIL) ? $pegawai2->EMAIL : ''); ?>
                  </div>
             </div>
            </div>
             <div class="control-group col-sm-12">
                 <div class="row">
                     <div class="col-sm-2">
                         ALAMAT
                     </div>
                     <div class="form-group col-sm-5">
                        <?php echo set_value('ALAMAT', isset($pegawai->ALAMAT) ? $pegawai->ALAMAT : '-'); ?>
                     </div>
                     <div class="form-group col-sm-5">
                        <?php echo set_value('ALAMAT', isset($pegawai2->ALAMAT) ? $pegawai2->ALAMAT : '-'); ?>
                     </div>
                 </div>
             </div>
             
             <div class="control-group col-sm-12">
               <div class="row">
                  <div class="col-sm-2">
                   NO HP
                 </div>
                  <div class="form-group col-sm-5">
                  <?php echo set_value('NOMOR_HP', (isset($pegawai->NOMOR_HP) and $pegawai->NOMOR_HP != "") ? $pegawai->NOMOR_HP : '-'); ?>
                  </div>
                  <div class="form-group col-sm-5">
                  <?php echo set_value('NOMOR_HP', (isset($pegawai2->NOMOR_HP) and $pegawai2->NOMOR_HP != "") ? $pegawai2->NOMOR_HP : '-'); ?>
                  </div>
                 
               </div>
             </div>
             <div class="control-group col-sm-12">
                 <div class="row">
                     <div class="form-group col-sm-2">
                         Agama
                     </div>
                     <div class="form-group col-sm-5">
                            <?php if (isset($agamas) && is_array($agamas) && count($agamas)):?>
                            <?php foreach($agamas as $record):?>
                            <?php if(isset($pegawai->AGAMA_ID))  echo  ($pegawai->AGAMA_ID==$record->ID) ? $record->NAMA : ""; ?>
                            <?php endforeach;?>
                            <?php endif;?>
                     </div>
                     <div class="form-group col-sm-5">
                            <?php if (isset($agamas) && is_array($agamas) && count($agamas)):?>
                            <?php foreach($agamas as $record):?>
                            <?php if(isset($pegawai2->AGAMA_ID))  echo  ($pegawai2->AGAMA_ID==$record->ID) ? $record->NAMA : ""; ?>
                            <?php endforeach;?>
                            <?php endif;?>
                     </div>
                 </div>
             </div>
             <div class="control-group col-sm-12">
                 <div class="row">
                     <div class="form-group col-sm-2">
                         Jenis Kelamin
                     </div>
                     <div class="form-group  col-sm-5">
                            <?php if(isset($pegawai->JENIS_KELAMIN))  echo  ("M"==$pegawai->JENIS_KELAMIN) ? "Laki-Laki" : ""; ?>
                            <?php if(isset($pegawai->JENIS_KELAMIN))  echo  ("F"==$pegawai->JENIS_KELAMIN) ? "Perempuan" : ""; ?>
                     </div>
                     <div class="form-group  col-sm-5">
                            <?php if(isset($pegawai2->JENIS_KELAMIN))  echo  ("M"==$pegawai2->JENIS_KELAMIN) ? "Laki-Laki" : ""; ?>
                            <?php if(isset($pegawai2->JENIS_KELAMIN))  echo  ("F"==$pegawai2->JENIS_KELAMIN) ? "Perempuan" : ""; ?>
                     </div>
                 </div>
             </div>
             <div class="control-group col-sm-12">
                      <div class="row">
                          <div class="col-sm-2">
                              Masa Kerja (Tahun/Bulan)
                          </div>
                          <div class="form-group col-sm-5">
                              <b><?php echo isset($recpns_aktif->masa_kerja_th) ? $recpns_aktif->masa_kerja_th  : ""; ?>/<?php echo isset($recpns_aktif->masa_kerja_bl) ? $recpns_aktif->masa_kerja_bl  : ""; ?></b>
                          </div>
                          <div class="form-group col-sm-5">
                              <b><?php echo isset($recpns_aktif2->masa_kerja_th) ? $recpns_aktif2->masa_kerja_th  : ""; ?>/<?php echo isset($recpns_aktif2->masa_kerja_bl) ? $recpns_aktif2->masa_kerja_bl  : ""; ?></b>
                          </div>
                      </div>
                  </div>
             <div class="control-group col-sm-12">
               <div class="row">
                 <div class="control-group col-sm-2">
                   Tingkat Pendidikan
                 </div>
                 <div class="form-group col-sm-5">
                        <?php if (isset($tkpendidikans) && is_array($tkpendidikans) && count($tkpendidikans)):?>
                        <?php foreach($tkpendidikans as $record):?>
                            <?php echo  (TRIM($pegawai->TK_PENDIDIKAN)==$record->ID) ? $record->NAMA : ""; ?>
                            <?php endforeach;?>
                        <?php endif;?>
                 </div>
                 <div class="form-group col-sm-5">
                        <?php if (isset($tkpendidikans) && is_array($tkpendidikans) && count($tkpendidikans)):?>
                        <?php foreach($tkpendidikans as $record):?>
                            <?php echo  (TRIM($pegawai2->TK_PENDIDIKAN)==$record->ID) ? $record->NAMA : ""; ?>
                            <?php endforeach;?>
                        <?php endif;?>
                 </div>
               </div>
             </div>
                   <div class="control-group col-sm-12">
                      <div class="row">
                          <div class="col-sm-2">
                              Pendidikan
                          </div>
                          <div class="form-group col-sm-5">
                              <?php echo $selectedPendidikanID->NAMA; ?>
                          </div>
                          <div class="form-group col-sm-5">
                              <?php echo isset($selectedPendidikanID2->NAMA) ? $selectedPendidikanID2->NAMA : ""; ?>
                          </div>
                      </div>
                  </div>
                  
                  
                  <div class="control-group col-sm-12">
                      <div class="row">
                          <div class="col-sm-2">
                              Lokasi Kerja
                          </div>
                          <div class="form-group col-sm-5">
                              <b><?php echo isset($pegawai->LOKASI_KERJA) ? $pegawai->LOKASI_KERJA  : ""; ?></b>
                          </div>
                          <div class="form-group col-sm-5">
                              <b><?php echo isset($pegawai2->LOKASI_KERJA) ? $pegawai2->LOKASI_KERJA  : ""; ?></b>
                          </div>
                      </div>
                  </div>
                  <div class="control-group col-sm-12">
                      <div class="row">
                          <div class="col-sm-2">
                              Jenis Pegawai
                          </div>
                          <div class="form-group col-sm-5">
                              <b><?php echo isset($pegawai->JENIS_PEGAWAI) ? $pegawai->JENIS_PEGAWAI : ''; ?></b>
                          </div>
                          <div class="form-group col-sm-5">
                              <b><?php echo isset($pegawai2->JENIS_PEGAWAI) ? $pegawai2->JENIS_PEGAWAI : ''; ?></b>
                          </div>
                      </div>
                         
                  </div>
                  <div class="control-group col-sm-12">
                      <div class="row">
                          <div class="col-sm-2">
                              Pangkat Golongan Aktif
                          </div>
                          <div class="form-group col-sm-5">
                              <b><?php echo isset($NAMA_PANGKAT) ? $NAMA_PANGKAT  : "-"; ?></b> <b><?php echo isset($GOLONGAN_AKHIR) ? $GOLONGAN_AKHIR  : "-"; ?></b>
                          </div>
                          <div class="form-group col-sm-5">
                              <b><?php echo isset($NAMA_PANGKAT2) ? $NAMA_PANGKAT2  : "-"; ?></b> <b><?php echo isset($GOLONGAN_AKHIR2) ? $GOLONGAN_AKHIR2  : "-"; ?></b>
                          </div>
                      </div>
                         
                  </div>
               
               <div class="control-group col-sm-12">
                 <div class="row">
                     <div class="form-group col-sm-2">
                         Golongan Ruang Awal
                     </div>
                     <div class="form-group col-sm-5">
                            <b><?php echo isset($GOLONGAN_AWAL) ? $GOLONGAN_AWAL  : "-"; ?></b>
                     </div>
                     <div class="form-group col-sm-5">
                            <b><?php echo isset($GOLONGAN_AWAL2) ? $GOLONGAN_AWAL2  : "-"; ?></b>
                     </div>
                 </div>
             </div>
             <div class="control-group col-sm-12">
                 <div class="row">
                     <div class="form-group col-sm-2">
                         Golongan Ruang Terakhir
                     </div>
                     <div class="form-group  col-sm-5">
                            <b><?php echo isset($GOLONGAN_AKHIR) ? $GOLONGAN_AKHIR  : "-"; ?></b>
                     </div>
                     <div class="form-group  col-sm-5">
                            <b><?php echo isset($GOLONGAN_AKHIR2) ? $GOLONGAN_AKHIR2  : "-"; ?></b>
                     </div>
                 </div>
             </div>
             <div class="control-group col-sm-12">
                 <div class="row">
                     <div class="form-group col-sm-2">
                         TMT Golongan
                     </div>
                     <div class="form-group col-sm-5">
                            <b><?php echo isset($pegawai->TMT_GOLONGAN) ? $convert->fmtDate($pegawai->TMT_GOLONGAN ,"dd month yyyy") : ""; ?></b>
                     </div>
                     <div class="form-group col-sm-5">
                            <b><?php echo isset($pegawai2->TMT_GOLONGAN) ? $convert->fmtDate($pegawai2->TMT_GOLONGAN ,"dd month yyyy") : ""; ?></b>
                     </div>
                 </div>
             </div>
              
              <div class="form-group col-sm-12">
                  <div class="row">
                      <div class="col-sm-2">
                          Status Kepegawaian
                      </div>
                      <div class="col-sm-5">
                          <b><?php echo isset($pegawai->STATUS_CPNS_PNS) ? $pegawai->STATUS_CPNS_PNS == "P" ? "PNS" : "" : ''; ?>   (<?php echo $this->convert->fmtDate($pegawai->TMT_PNS,"dd month yyyy"); ?>)</b>
                      </div>
                      <div class="col-sm-5">
                          <b><?php echo isset($pegawai2->STATUS_CPNS_PNS) ? $pegawai2->STATUS_CPNS_PNS == "P" ? "PNS" : "" : ''; ?>   (<?php echo $this->convert->fmtDate($pegawai2->TMT_PNS,"dd month yyyy"); ?>)</b>
                      </div>
                  </div>
              </div>
              <div class="form-group col-sm-12">
                  <div class="row">
                      <div class="col-sm-2">
                          Kartu Pegawai
                      </div>
                      <div class="col-sm-5">
                          <b><?php echo isset($pegawai->KARTU_PEGAWAI) ? $pegawai->KARTU_PEGAWAI != "" ? $pegawai->KARTU_PEGAWAI : "" : ''; ?></b>
                      </div>
                      <div class="col-sm-5">
                          <b><?php echo isset($pegawai2->KARTU_PEGAWAI) ? $pegawai2->KARTU_PEGAWAI != "" ? $pegawai2->KARTU_PEGAWAI : "" : ''; ?></b>
                      </div>
                  </div>
              </div>

              </fieldset>
                  <fieldset>
                      <legend>Lainnya</legend>
                          <div class="control-group col-sm-12">
                            <div class="row">
                               <div class="col-sm-2">
                                 STATUS PERKAWINAN
                               </div>
                                <div class="form-group col-sm-5">
                                    <?php if (isset($jenis_kawins) && is_array($jenis_kawins) && count($jenis_kawins)):?>
                                     <?php foreach($jenis_kawins as $record):?>
                                         <?php echo  ($pegawai->JENIS_KAWIN_ID==$record->ID) ? $record->NAMA : ""; ?>
                                         <?php endforeach;?>
                                     <?php endif;?>
                                </div>
                               <div class="form-group col-sm-5">
                                    <?php if (isset($jenis_kawins) && is_array($jenis_kawins) && count($jenis_kawins)):?>
                                     <?php foreach($jenis_kawins as $record):?>
                                         <?php echo  ($pegawai2->JENIS_KAWIN_ID==$record->ID) ? $record->NAMA : ""; ?>
                                         <?php endforeach;?>
                                     <?php endif;?>
                                </div>
                             </div>
                              
                          </div>
                          <div class="control-group col-sm-12">
                               <div class="row">
                                   <div class="form-group col-sm-2">
                                       No Surat Keterangan Dokter
                                   </div>
                                   <div class="form-group col-sm-5">
                                        <b><?php echo set_value('NO_SURAT_DOKTER', isset($pegawai->NO_SURAT_DOKTER) ? trim($pegawai->NO_SURAT_DOKTER) : '-'); ?></b>
                                   </div>
                                   <div class="form-group col-sm-5">
                                        <b><?php echo set_value('NO_SURAT_DOKTER', isset($pegawai2->NO_SURAT_DOKTER) ? trim($pegawai2->NO_SURAT_DOKTER) : '-'); ?></b>
                                   </div>
                               </div>
                           </div>
                           <div class="control-group col-sm-12">
                               <div class="row">
                                   <div class="form-group col-sm-2">
                                       Tanggal
                                   </div>
                                   <div class="form-group  col-sm-5">
                                          <b><?php echo set_value('TGL_SURAT_DOKTER', isset($pegawai->TGL_SURAT_DOKTER) ? trim($pegawai->TGL_SURAT_DOKTER) : '-'); ?></b>
                                   </div>
                                   <div class="form-group  col-sm-5">
                                          <b><?php echo set_value('TGL_SURAT_DOKTER', isset($pegawai2->TGL_SURAT_DOKTER) ? trim($pegawai2->TGL_SURAT_DOKTER) : '-'); ?></b>
                                   </div>
                               </div>
                           </div>

                           <div class="control-group col-sm-12">
                               <div class="row">
                                   <div class="form-group col-sm-2">
                                       No Surat Bebas Narkoba
                                   </div>
                                   <div class="form-group col-sm-4">
                                          <b><?php echo set_value('NO_BEBAS_NARKOBA', isset($pegawai->NO_BEBAS_NARKOBA) ? trim($pegawai->NO_BEBAS_NARKOBA) : ''); ?></b>
                                   </div>
                               </div>
                           </div>
                           <div class="control-group col-sm-12">
                               <div class="row">
                                   <div class="form-group col-sm-2">
                                       Tanggal
                                   </div>
                                   <div class="form-group  col-sm-5">
                                        <b>
                                          <?php echo set_value('TGL_BEBAS_NARKOBA', isset($pegawai->TGL_BEBAS_NARKOBA) ? $pegawai->TGL_BEBAS_NARKOBA : '-'); ?>
                                        </b>
                                   </div>
                                   <div class="form-group  col-sm-5">
                                        <b>
                                          <?php echo set_value('TGL_BEBAS_NARKOBA', isset($pegawai2->TGL_BEBAS_NARKOBA) ? $pegawai2->TGL_BEBAS_NARKOBA : '-'); ?>
                                        </b>
                                   </div>
                               </div>
                           </div>
                           <div class="control-group col-sm-12">
                               <div class="row">
                                   <div class="form-group col-sm-2">
                                       No Catatan Kepolisian
                                   </div>
                                   <div class="form-group col-sm-5">
                                      <b><?php echo set_value('NO_CATATAN_POLISI', isset($pegawai->NO_CATATAN_POLISI) ? trim($pegawai->NO_CATATAN_POLISI) : ''); ?></b>
                                   </div>
                                   <div class="form-group col-sm-5">
                                      <b><?php echo set_value('NO_CATATAN_POLISI', isset($pegawai2->NO_CATATAN_POLISI) ? trim($pegawai2->NO_CATATAN_POLISI) : ''); ?></b>
                                   </div>
                               </div>
                           </div>
                           <div class="control-group col-sm-12">
                               <div class="row">
                                   <div class="form-group col-sm-2">
                                       Tanggal
                                   </div>
                                   <div class="form-group  col-sm-5">
                                          <b>
                                          <?php echo set_value('TGL_CATATAN_POLISI', isset($pegawai->TGL_CATATAN_POLISI) ? $pegawai->TGL_CATATAN_POLISI : ''); ?>
                                          </b>
                                   </div>
                                   <div class="form-group  col-sm-5">
                                          <b>
                                          <?php echo set_value('TGL_CATATAN_POLISI', isset($pegawai2->TGL_CATATAN_POLISI) ? $pegawai2->TGL_CATATAN_POLISI : ''); ?>
                                          </b>
                                   </div>
                               </div>
                           </div>

                           <div class="control-group col-sm-12">
                               <div class="row">
                                   <div class="form-group col-sm-2">
                                       AKTE KELAHIRAN
                                   </div>
                                   <div class="form-group col-sm-4">
                                          <b><?php echo set_value('AKTE_KELAHIRAN', isset($pegawai->AKTE_KELAHIRAN) ? trim($pegawai->AKTE_KELAHIRAN) : ''); ?></b>
                                   </div>
                                   <div class="form-group col-sm-4">
                                          <b><?php echo set_value('AKTE_KELAHIRAN', isset($pegawai2->AKTE_KELAHIRAN) ? trim($pegawai2->AKTE_KELAHIRAN) : ''); ?></b>
                                   </div>
                               </div>
                           </div>
                           <div class="control-group col-sm-12">
                               <div class="row">
                                   <div class="form-group col-sm-2">
                                       NO BPJS
                                   </div>
                                   <div class="form-group  col-sm-5">
                                          <b>
                                          <?php echo set_value('BPJS', isset($pegawai->BPJS) ? $pegawai->BPJS : ''); ?>
                                            
                                          </b>
                                   </div>
                                   <div class="form-group  col-sm-5">
                                          <b>
                                          <?php echo set_value('BPJS', isset($pegawai2->BPJS) ? $pegawai2->BPJS : ''); ?>
                                            
                                          </b>
                                   </div>
                               </div>
                           </div>

                           <div class="control-group col-sm-12">
                               <div class="row">
                                   <div class="form-group col-sm-2">
                                       TASPEN
                                   </div>
                                   <div class="form-group col-sm-5">
                                          <b><?php echo set_value('NO_TASPEN', isset($pegawai->NO_TASPEN) ? trim($pegawai->NO_TASPEN) : '-'); ?></b>
                                   </div>
                                   <div class="form-group col-sm-5">
                                          <b><?php echo set_value('NO_TASPEN', isset($pegawai2->NO_TASPEN) ? trim($pegawai2->NO_TASPEN) : '-'); ?></b>
                                   </div>
                               </div>
                           </div>
                           <div class="control-group col-sm-12">
                               <div class="row">
                                   <div class="form-group col-sm-2">
                                       NPWP
                                   </div>
                                   <div class="form-group  col-sm-5">
                                          <b>
                                          <?php echo set_value('NPWP', isset($pegawai->NPWP) ? $pegawai->NPWP : '-'); ?>
                                          </b>
                                   </div>
                                   <div class="form-group  col-sm-5">
                                          <b>
                                          <?php echo set_value('NPWP', isset($pegawai2->NPWP) ? $pegawai2->NPWP : '-'); ?>
                                          </b>
                                   </div>
                               </div>
                           </div>

                           <div class="control-group col-sm-12">
                               <div class="row">
                                   <div class="form-group col-sm-2">
                                       Tanggal NPWP
                                   </div>
                                   <div class="form-group  col-sm-5">
                                          <b>
                                          <?php echo set_value('TGL_NPWP', isset($pegawai->TGL_NPWP) ? $pegawai->TGL_NPWP : '-'); ?>
                                          </b>
                                   </div>
                                   <div class="form-group  col-sm-5">
                                          <b>
                                          <?php echo set_value('TGL_NPWP', isset($pegawai2->TGL_NPWP) ? $pegawai2->TGL_NPWP : '-'); ?>
                                          </b>
                                   </div>
                               </div>
                           </div>
                  </fieldset>
                    <!-- /.table-responsive -->
                   
                  <!-- /.box-body -->
                  
                  <!-- /.box-footer -->
                </div>
          </div>
       
          
          </form>
      </div>
    </div>
    <!-- /.col -->
</div>
 
<script>
$(document).ready(function(){   
    $("#NIP1").select2({
        placeholder: 'Cari Pegawai...',
        width: '100%',
        minimumInputLength: 3,
        allowClear: true,
        ajax: {
            url: '<?php echo site_url("admin/kepegawaian/pegawai/ajaxid");?>',
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
    $("#NIP2").select2({
        placeholder: 'Cari Pegawai...',
        width: '100%',
        minimumInputLength: 3,
        allowClear: true,
        ajax: {
            url: '<?php echo site_url("admin/kepegawaian/pegawai/ajaxid");?>',
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

    $( "#NIP1" ).change(function() {
      bandingkan();
    });
    $( "#NIP2" ).change(function() {
      bandingkan();
    });
    function bandingkan(){
      var varnip1 = $("#NIP1").val();
      var varnip2 = $("#NIP2").val();
      if(varnip1 != "" && varnip2 != ""){
        window.location = "<?php echo base_url(); ?>admin/kepegawaian/pegawai/review/"+varnip1+"/"+varnip2;  
      }
    }
});
</script>