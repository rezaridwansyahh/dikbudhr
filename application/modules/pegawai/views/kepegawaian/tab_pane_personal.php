<script src="<?php echo base_url(); ?>themes/admin/js/jqueryvalidation/jquery.validate.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/daterangepicker/daterangepicker.css">
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/datepicker/datepicker3.css">
<?php
	$this->load->library('convert');
 	$convert = new convert();
?>
<div class="tab-pane active" id="<?php echo $TAB_ID;?>">
    <div class="callout callout-success">
       <h4>Perhatian</h4>
       <P>Silahkan lakukan ajukan perubahan data, jika ada kesalahan atau ada perubahan data diri anda</P>
       <p>Permintaan Perubahan data yang anda lakukan akan diverifikasi oleh kepegawaian Satker/Upt anda, setelah itu data akan diverifikasi ulang oleh Biro SDM</p>
     </div>
	<form role="form" action="#" id="frmprofile">
	<input id='ID' type='hidden' class="form-control" name='ID' maxlength='25' value="<?php echo set_value('ID', isset($pegawai->ID) ? $pegawai->ID : ''); ?>" />
	<div class="box box-info">
            <div class="box-header no-border">
              <h3 class="box-title">Data Pribadi</h3>

              <div class="box-tools pull-right">
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            <fieldset>
                <div class="control-group col-sm-12">
                     <div class="row">
                         <div class="col-sm-4">
                             NIP
                         </div>
                         <div class="form-group col-sm-8">
                            <b><?php echo isset($pegawai->NIP_BARU) ? $pegawai->NIP_BARU : ''; ?></b>
                         </div>
                     </div>
                 </div>
                 <div class="control-group col-sm-12">
                     <div class="row">
                         <div class="col-sm-4">
                             NIK
                         </div>
                         <div class="form-group col-sm-8">
                            <b><?php echo isset($pegawai->NIK) ? $pegawai->NIK : ''; ?></b>
                         </div>
                     </div>
                 </div>

                 <div class="control-group col-sm-12">
                     <div class="row">
                         <div class="col-sm-4">
                             KK
                         </div>
                         <div class="form-group col-sm-8">
                            <b><?php echo isset($pegawai->KK) ? $pegawai->KK : ''; ?></b>
                         </div>
                     </div>
                 </div>

                 
        			  <div class="form-group col-sm-12">
        				  <div class="row">
        					  <div class="col-sm-4">
        						  Tempat/Tanggal Lahir
        					  </div>
        					  <div class="control-group col-sm-8">
                      <?php echo isset($selectedTempatLahirPegawai->NAMA) ? $selectedTempatLahirPegawai->NAMA : ""; ?> / 
                          <?php echo isset($pegawai->TGL_LAHIR) ? $convert->fmtDate($pegawai->TGL_LAHIR,"dd month yyyy") : 'TGL_LAHIR'; ?>
                  </div> 
                              
        				  </div>
        			  </div>
        <div class="control-group col-sm-12">
         <div class="row">
           <div class="col-sm-4">
             EMAIL DIKBUD
           </div>
           <div class="form-group col-sm-8">
                <?php echo set_value('EMAIL', isset($pegawai->EMAIL_DIKBUD) ? $pegawai->EMAIL_DIKBUD : '-'); ?>
         </div>
       </div>
      </div>
			 <div class="control-group col-sm-12">
				 <div class="row">
					 <div class="col-sm-4">
						 EMAIL LAIN
					 </div>
					 <div class="form-group col-sm-8">
                <?php echo set_value('EMAIL', isset($pegawai->EMAIL) ? $pegawai->EMAIL : '-'); ?>
				 </div>
			 </div>
      </div>
      
       <div class="control-group col-sm-12">
           <div class="row">
               <div class="col-sm-4">
                   ALAMAT
               </div>
               <div class="form-group col-sm-8">
                  <?php echo set_value('ALAMAT', isset($pegawai->ALAMAT) ? $pegawai->ALAMAT : '-'); ?>
               </div>
           </div>
       </div>
			 
			 <div class="control-group col-sm-12">
				 <div class="row">
					 <div class="col-sm-4">
						 NO HP
					 </div>
            <div class="form-group col-sm-8">
            <?php echo set_value('NOMOR_HP', (isset($pegawai->NOMOR_HP) and $pegawai->NOMOR_HP != "") ? $pegawai->NOMOR_HP : '-'); ?>

            </div>
					 
				 </div>
			 </div>
        
       <div class="control-group col-sm-6">
           <div class="row">
               <div class="form-group col-sm-8">
                   Agama
               </div>
               <div class="form-group col-sm-4">
                      <?php if (isset($agamas) && is_array($agamas) && count($agamas)):?>
                      <?php foreach($agamas as $record):?>
                      <?php if(isset($pegawai->AGAMA_ID))  echo  ($pegawai->AGAMA_ID==$record->ID) ? $record->NAMA : ""; ?>
                      <?php endforeach;?>
                      <?php endif;?>
               </div>
           </div>
       </div>
       <div class="control-group col-sm-6">
           <div class="row">
               <div class="form-group col-sm-4">
                   Jenis Kelamin
               </div>
               <div class="form-group  col-sm-8">
                      <?php if(isset($pegawai->JENIS_KELAMIN))  echo  ("M"==$pegawai->JENIS_KELAMIN) ? "Laki-Laki" : ""; ?>
                      <?php if(isset($pegawai->JENIS_KELAMIN))  echo  ("F"==$pegawai->JENIS_KELAMIN) ? "Perempuan" : ""; ?>
               </div>
           </div>
       </div>

			 <div class="control-group col-sm-6">
				 <div class="row">
					 <div class="control-group col-sm-8">
						 Tingkat Pendidikan
					 </div>
					 <div class="form-group col-sm-4">
                  <?php if (isset($tkpendidikans) && is_array($tkpendidikans) && count($tkpendidikans)):?>
                  <?php foreach($tkpendidikans as $record):?>
                      <?php echo  (TRIM($pegawai->TK_PENDIDIKAN)==$record->ID) ? $record->NAMA : ""; ?>
                      <?php endforeach;?>
                  <?php endif;?>
					 </div>
				 </div>
			 </div>
             <div class="control-group col-sm-6">
                <div class="row">
                    <div class="col-sm-4">
                        Pendidikan
                    </div>
                    <div class="form-group col-sm-8">
                        <?php echo $selectedPendidikanID->NAMA; ?>
                    </div>
                </div>
            </div>
            <div class="control-group col-sm-12">
                <div class="row">
                     <div class="form-group col-sm-6">
                        <div class="row">
                            <div class="col-sm-8">
                                Masa Kerja (Tahun/Bulan)
                            </div>
                            <div class="form-group col-sm-4">
                                <b><?php echo isset($recpns_aktif->masa_kerja_th) ? $recpns_aktif->masa_kerja_th  : ""; ?>/<?php echo isset($recpns_aktif->masa_kerja_bl) ? $recpns_aktif->masa_kerja_bl  : ""; ?></b>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="control-group col-sm-12">
                <div class="row">
                     <div class="form-group col-sm-6">
                        <div class="row">
                            <div class="col-sm-8">
                                Lokasi Kerja
                            </div>
                            <div class="form-group col-sm-4">
                                <b><?php echo isset($pegawai->LOKASI_KERJA) ? $pegawai->LOKASI_KERJA  : ""; ?></b>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="control-group col-sm-12">
                <div class="row">
                    <div class="col-sm-4">
                        Jenis Pegawai
                    </div>
                    <div class="form-group col-sm-8">
                        <b><?php echo isset($pegawai->JENIS_PEGAWAI) ? $pegawai->JENIS_PEGAWAI : ''; ?></b>
                    </div>
                </div>
                   
            </div>
            <div class="control-group col-sm-12">
                <div class="row">
                    <div class="col-sm-4">
                        Pangkat Golongan Aktif
                    </div>
                    <div class="form-group col-sm-8">
                        <b><?php echo isset($NAMA_PANGKAT) ? $NAMA_PANGKAT  : "-"; ?></b> <b><?php echo isset($GOLONGAN_AKHIR) ? $GOLONGAN_AKHIR  : "-"; ?></b>
                    </div>
                </div>
                   
            </div>
         
         <div class="control-group col-sm-6">
           <div class="row">
               <div class="form-group col-sm-8">
                   Golongan Ruang Awal
               </div>
               <div class="form-group col-sm-4">
                      <b><?php echo isset($GOLONGAN_AWAL) ? $GOLONGAN_AWAL  : "-"; ?></b>
               </div>
           </div>
       </div>
       <div class="control-group col-sm-6">
           <div class="row">
               <div class="form-group col-sm-4">
                   Golongan Ruang Terakhir
               </div>
               <div class="form-group  col-sm-8">
                      <b><?php echo isset($GOLONGAN_AKHIR) ? $GOLONGAN_AKHIR  : "-"; ?></b>
               </div>
           </div>
       </div>
       <div class="control-group col-sm-6">
           <div class="row">
               <div class="form-group col-sm-8">
                   TMT Golongan
               </div>
               <div class="form-group col-sm-4">
                      <b><?php echo isset($pegawai->TMT_GOLONGAN) ? $convert->fmtDate($pegawai->TMT_GOLONGAN ,"dd month yyyy") : ""; ?></b>
               </div>
           </div>
       </div>
       <div class="control-group col-sm-6">
           <div class="row">
               <div class="form-group col-sm-4">
                   Gaji Pokok
               </div>
               <div class="form-group  col-sm-8">
                      <b>-</b>
               </div>
           </div>
       </div>
       <div class="control-group col-sm-6">
           <div class="row">
               <div class="form-group col-sm-8">
                   TMT CPNS
               </div>
               <div class="form-group col-sm-4">
                      <b><?php echo isset($pegawai->TMT_CPNS) ? $convert->fmtDate($pegawai->TMT_CPNS ,"dd month yyyy") : ""; ?></b>
               </div>
           </div>
       </div>
       <div class="control-group col-sm-6">
           <div class="row">
               <div class="form-group col-sm-4">
                   SK CPNS
               </div>
               <div class="form-group  col-sm-8">
                      <b><?php echo isset($pegawai->NOMOR_SK_CPNS) ? $pegawai->NOMOR_SK_CPNS != "" ? $pegawai->NOMOR_SK_CPNS : "" : ''; ?></b>
               </div>
           </div>
       </div>
        <div class="form-group col-sm-12">
            <div class="row">
                <div class="col-sm-4">
                    Status Kepegawaian
                </div>
                <div class="col-sm-8">
                    <b><?php echo isset($pegawai->STATUS_CPNS_PNS) ? $pegawai->STATUS_CPNS_PNS == "P" ? "PNS" : "" : ''; ?>   (<?php echo $this->convert->fmtDate($pegawai->TMT_PNS,"dd month yyyy"); ?>)</b>
                </div>
            </div>
        </div>
        <div class="form-group col-sm-12">
            <div class="row">
                <div class="col-sm-4">
                    Kartu Pegawai
                </div>
                <div class="col-sm-8">
                    <b><?php echo isset($pegawai->KARTU_PEGAWAI) ? $pegawai->KARTU_PEGAWAI != "" ? $pegawai->KARTU_PEGAWAI : "" : ''; ?></b>
                </div>
            </div>
        </div>
<div class="control-group col-sm-12">
                      <div class="row">
                         <div class="col-sm-4">
                           Status Perkawinan
                         </div>
                          <div class="form-group col-sm-8">
                              <?php if (isset($jenis_kawins) && is_array($jenis_kawins) && count($jenis_kawins)):?>
                               <?php foreach($jenis_kawins as $record):?>
                                   <?php echo  ($pegawai->JENIS_KAWIN_ID==$record->ID) ? $record->NAMA : ""; ?>
                                   <?php endforeach;?>
                               <?php endif;?>
                          </div>

                       </div>

                    </div>


        </fieldset>
            <fieldset>
                <legend>Data Pendukung</legend>
                   
                        
                    <div class="control-group col-sm-6">
                         <div class="row">
                             <div class="form-group col-sm-8">
                                 No Surat Keterangan Dokter (CPNS)
                             </div>
                             <div class="form-group col-sm-4">
                                    <b><?php echo set_value('NO_SURAT_DOKTER', isset($pegawai->NO_SURAT_DOKTER) ? trim($pegawai->NO_SURAT_DOKTER) : '-'); ?></b>
                             </div>
                         </div>
                     </div>
                     <div class="control-group col-sm-6">
                         <div class="row">
                             <div class="form-group col-sm-4">
                                 Tanggal
                             </div>
                             <div class="form-group  col-sm-8">
                                    <b><?php echo set_value('TGL_SURAT_DOKTER', isset($pegawai->TGL_SURAT_DOKTER) ? trim($pegawai->TGL_SURAT_DOKTER) : '-'); ?></b>
                             </div>
                         </div>
                     </div>

                     <div class="control-group col-sm-6">
                         <div class="row">
                             <div class="form-group col-sm-8">
                                 No Surat Bebas Narkoba (CPNS)
                             </div>
                             <div class="form-group col-sm-4">
                                    <b><?php echo set_value('NO_BEBAS_NARKOBA', isset($pegawai->NO_BEBAS_NARKOBA) ? trim($pegawai->NO_BEBAS_NARKOBA) : ''); ?></b>
                             </div>
                         </div>
                     </div>
                     <div class="control-group col-sm-6">
                         <div class="row">
                             <div class="form-group col-sm-4">
                                 Tanggal
                             </div>
                             <div class="form-group  col-sm-8">
                                    <b>
                                    <?php echo set_value('TGL_BEBAS_NARKOBA', isset($pegawai->TGL_BEBAS_NARKOBA) ? $pegawai->TGL_BEBAS_NARKOBA : '-'); ?>
                                      
                                    </b>
                             </div>
                         </div>
                     </div>
                     <div class="control-group col-sm-6">
                         <div class="row">
                             <div class="form-group col-sm-8">
                                 No Catatan Kepolisian
                             </div>
                             <div class="form-group col-sm-4">
                                    <b><?php echo set_value('NO_CATATAN_POLISI', isset($pegawai->NO_CATATAN_POLISI) ? trim($pegawai->NO_CATATAN_POLISI) : ''); ?></b>
                             </div>
                         </div>
                     </div>
                     <div class="control-group col-sm-6">
                         <div class="row">
                             <div class="form-group col-sm-4">
                                 Tanggal
                             </div>
                             <div class="form-group  col-sm-8">
                                    <b>
                                    <?php echo set_value('TGL_CATATAN_POLISI', isset($pegawai->TGL_CATATAN_POLISI) ? $pegawai->TGL_CATATAN_POLISI : ''); ?>
                                      
                                    </b>
                             </div>
                         </div>
                     </div>

                     <div class="control-group col-sm-6">
                         <div class="row">
                             <div class="form-group col-sm-8">
                                 AKTE KELAHIRAN
                             </div>
                             <div class="form-group col-sm-4">
                                    <b><?php echo set_value('AKTE_KELAHIRAN', isset($pegawai->AKTE_KELAHIRAN) ? trim($pegawai->AKTE_KELAHIRAN) : ''); ?></b>
                             </div>
                         </div>
                     </div>
                     <div class="control-group col-sm-6">
                         <div class="row">
                             <div class="form-group col-sm-4">
                                 NO BPJS
                             </div>
                             <div class="form-group  col-sm-8">
                                    <b>
                                    <?php echo set_value('BPJS', isset($pegawai->BPJS) ? $pegawai->BPJS : ''); ?>
                                      
                                    </b>
                             </div>
                         </div>
                     </div>

                     <div class="control-group col-sm-12">
                         <div class="row">
                             <div class="form-group col-sm-4">
                                 TASPEN
                             </div>
                             <div class="form-group col-sm-8">
                                    <b><?php echo set_value('NO_TASPEN', isset($pegawai->NO_TASPEN) ? trim($pegawai->NO_TASPEN) : '-'); ?></b>
                             </div>
                         </div>
                     </div>
                     <div class="control-group col-sm-6">
                         <div class="row">
                             <div class="form-group col-sm-8">
                                 NPWP
                             </div>
                             <div class="form-group  col-sm-4">
                                    <b>
                                    <?php echo set_value('NPWP', isset($pegawai->NPWP) ? $pegawai->NPWP : '-'); ?>
                                      
                                    </b>
                             </div>
                         </div>
                     </div>

                     <div class="control-group col-sm-6">
                         <div class="row">
                             <div class="form-group col-sm-4">
                                 Tanggal NPWP
                             </div>
                             <div class="form-group  col-sm-8">
                                    <b>
                                    <?php echo set_value('TGL_NPWP', isset($pegawai->TGL_NPWP) ? $pegawai->TGL_NPWP : '-'); ?>
                                      
                                    </b>
                             </div>
                         </div>
                     </div>
                     <div class="control-group col-sm-6">
                         <div class="row">
                             <div class="form-group col-sm-8">
                                 NO DARURAT
                             </div>
                             <div class="form-group  col-sm-4">
                                    <b>
                                    <?php echo set_value('NOMOR_DARURAT', isset($pegawai->NOMOR_DARURAT) ? $pegawai->NOMOR_DARURAT : '-'); ?>
                                      
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
  
