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
       <P>Dibawah ini merupakan data dari BKN, Silahkan klik <a href="<?php echo base_url(); ?>admin/kepegawaian/pegawai/profilen/<?php echo $pegawai->ID; ?>" class='btn btn-warning' kode="<?php echo $pegawai_bkn->nipBaru; ?>" tooltip="Lihat data dikbudhr" ><i class="fa fa-eye" aria-hidden="true"></i> Lihat Profile dikbudhr</a> untuk melihat data profile pada aplikasi</p>
     </div>
	<form role="form" action="#" id="frmprofile">
	<input id='ID' type='hidden' class="form-control" name='ID' maxlength='25' value="<?php echo set_value('ID', isset($pegawai_bkn->ID) ? $pegawai_bkn->ID : ''); ?>" />
	<div class="box box-info">
      <?php
      // echo "<pre>";
      // echo print_r($pegawai_bkn);
      // echo "</pre>";
      ?>
       <?php
      // echo "<pre>";
      // echo print_r($pegawai);
      // echo "</pre>";
      ?>
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
                            <b><?php echo isset($pegawai_bkn->nipBaru) ? $pegawai_bkn->nipBaru : ''; ?></b>
                            <?php echo $pegawai_bkn->nipBaru != $pegawai->NIP_BARU ? "<i class='fa fa-check-circle text-danger' data-toggle='tooltip' title='Profile ".$pegawai->NIP_BARU."'></i>" : "<i class='fa fa-check-circle text-success' data-toggle='tooltip' title='Data sama'></i>"; ?>
                         </div>
                     </div>
                 </div>
                 <div class="control-group col-sm-12">
                     <div class="row">
                         <div class="col-sm-4">
                             NIK
                         </div>
                         <div class="form-group col-sm-8">
                            <b><?php echo isset($pegawai_bkn->nik) ? $pegawai_bkn->nik : ''; ?></b>
                            <?php echo $pegawai_bkn->nik != $pegawai->NIK ? "<a href='#' class='update_profile' kode='nik' nip='".$pegawai_bkn->nipBaru."'><i class='fa fa-check-circle text-danger' data-toggle='tooltip' title='Profile ".$pegawai->NIK."'></i></a>" : "<i class='fa fa-check-circle text-success'></i>"; ?>
                         </div>
                     </div>
                 </div>
        			  <div class="form-group col-sm-12">
        				  <div class="row">
        					  <div class="col-sm-4">
        						  Tempat/Tanggal Lahir
        					  </div>
        					  <div class="control-group col-sm-8">
                      <?php echo isset($pegawai_bkn->tempatLahir) ? $pegawai_bkn->tempatLahir : ''; ?> / 
                          <?php echo isset($pegawai_bkn->tglLahir) ? $pegawai_bkn->tglLahir : 'TGL_LAHIR'; ?>

                      <?php echo $pegawai_bkn->tempatLahir != $selectedTempatLahirPegawai->NAMA ? "<a href='#' class='update_profile' kode='tempatLahir' nip='".$pegawai_bkn->nipBaru."'><i class='fa fa-check-circle text-danger' data-toggle='tooltip' title='Profile ".$selectedTempatLahirPegawai->NAMA."'></i></a>" : "<i class='fa fa-check-circle text-success' data-toggle='tooltip' title='Data sama'></i>"; ?>
                          
                  </div> 
                              
        				  </div>
        			  </div>
			 <div class="control-group col-sm-12">
				 <div class="row">
					 <div class="col-sm-4">
						 EMAIL
					 </div>
					 <div class="form-group col-sm-8">
                <?php echo set_value('EMAIL', isset($pegawai_bkn->email) ? $pegawai_bkn->email : '-'); ?>
                <?php echo $pegawai_bkn->email != $pegawai->EMAIL ? "<a href='#' class='update_profile' kode='email' nip='".$pegawai_bkn->nipBaru."'><i class='fa fa-check-circle text-danger' data-toggle='tooltip' title='Profile ".$pegawai->EMAIL."'></i></a>" : "<i class='fa fa-check-circle text-success' data-toggle='tooltip' title='Data sama'></i>"; ?>
				 </div>
			 </div>
      </div>
      
       <div class="control-group col-sm-12">
           <div class="row">
               <div class="col-sm-4">
                   ALAMAT
               </div>
               <div class="form-group col-sm-8">
                  <?php echo set_value('ALAMAT', isset($pegawai_bkn->alamat) ? $pegawai_bkn->alamat : '-'); ?>
                  <?php echo $pegawai_bkn->alamat != $pegawai->ALAMAT ? "<a href='#' class='update_profile' kode='alamat' nip='".$pegawai_bkn->nipBaru."'><i class='fa fa-check-circle text-danger' data-toggle='tooltip' title='Profile ".$pegawai->ALAMAT."'></i></a>" : "<i class='fa fa-check-circle text-success' data-toggle='tooltip' title='Data sama'></i>"; ?>
               </div>
           </div>
       </div>
			 
			 <div class="control-group col-sm-12">
				 <div class="row">
					 <div class="col-sm-4">
						 NO HP
					 </div>
            <div class="form-group col-sm-8">
            <?php echo set_value('NOMOR_HP', (isset($pegawai_bkn->noHp) and $pegawai_bkn->noHp != "") ? $pegawai_bkn->noHp : '-'); ?>
            <?php echo $pegawai_bkn->noHp != $pegawai->NOMOR_HP ? "<a href='#' class='update_profile' kode='noHp' nip='".$pegawai_bkn->nipBaru."'><i class='fa fa-check-circle text-danger' data-toggle='tooltip' title='Profile ".$pegawai->NOMOR_HP."'></i></a>" : "<i class='fa fa-check-circle text-success' data-toggle='tooltip' title='Data sama'></i>"; ?>
            </div>
					 
				 </div>
			 </div>
        
       <div class="control-group col-sm-6">
           <div class="row">
               <div class="form-group col-sm-8">
                   Agama
               </div>
               <div class="form-group col-sm-4">
                      <?php echo set_value('agama', isset($pegawai_bkn->agama) ? $pegawai_bkn->agama : '-'); ?>
                      <?php echo $pegawai_bkn->agamaId != $pegawai->AGAMA_ID ? "<a href='#' class='update_profile' kode='agamaId' nip='".$pegawai_bkn->nipBaru."'><i class='fa fa-check-circle text-danger' data-toggle='tooltip' title='Profile ".$pegawai->AGAMA_ID."'></i></a>" : "<i class='fa fa-check-circle text-success' data-toggle='tooltip' title='Data sama'></i>"; ?>
               </div>
           </div>
       </div>
       <div class="control-group col-sm-6">
           <div class="row">
               <div class="form-group col-sm-4">
                   Jenis Kelamin
               </div>
               <div class="form-group  col-sm-8">
                    <?php
                    $jk = $pegawai->JENIS_KELAMIN == "M" ? "Pria" : "Wanita";
                    ?>
                      <?php echo isset($pegawai_bkn->jenisKelamin) ? $pegawai_bkn->jenisKelamin : '-'; ?>
                      <?php echo $pegawai_bkn->jenisKelamin != $jk ? "<a href='#' class='update_profile' kode='jenisKelamin' nip='".$pegawai_bkn->nipBaru."'><i class='fa fa-check-circle text-danger' data-toggle='tooltip' title='Profile ".$jk."'></i></a>" : "<i class='fa fa-check-circle text-success' data-toggle='tooltip' title='Data sama'></i>"; ?>
               </div>
           </div>
       </div>

			 <div class="control-group col-sm-6">
				 <div class="row">
					 <div class="control-group col-sm-8">
						 Tingkat Pendidikan
					 </div>
					 <div class="form-group col-sm-4">
                  <?php echo isset($pegawai_bkn->tkPendidikanTerakhir) ? $pegawai_bkn->tkPendidikanTerakhir : '-'; ?>
                  <?php echo $pegawai_bkn->tkPendidikanTerakhirId != $pegawai->TK_PENDIDIKAN ? "<a href='#' class='update_profile' kode='tkPendidikanTerakhirId' nip='".$pegawai_bkn->nipBaru."'><i class='fa fa-check-circle text-danger' data-toggle='tooltip' title='Profile ".$pegawai->TK_PENDIDIKAN."'></i></a>" : "<i class='fa fa-check-circle text-success' data-toggle='tooltip' title='Data sama'></i>"; ?>
					 </div>
				 </div>
			 </div>
             <div class="control-group col-sm-6">
                <div class="row">
                    <div class="col-sm-4">
                        Pendidikan
                    </div>
                    <div class="form-group col-sm-8">
                        <?php echo isset($pegawai_bkn->pendidikanTerakhirNama) ? $pegawai_bkn->pendidikanTerakhirNama : '-'; ?>
                        <?php echo $pegawai_bkn->pendidikanTerakhirNama != $selectedPendidikanID->NAMA ? "<a href='#' class='update_profile' kode='tkPendidikanTerakhirId' nip='".$pegawai_bkn->nipBaru."'><i class='fa fa-check-circle text-danger' data-toggle='tooltip' title='Profile ".$selectedPendidikanID->NAMA."'></i></a>" : "<i class='fa fa-check-circle text-success' data-toggle='tooltip' title='Data sama'></i>"; ?>
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
                                <b><?php echo isset($pegawai_bkn->masaKerja) ? $pegawai_bkn->masaKerja : '-'; ?></b>
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
                                <b><?php echo isset($pegawai_bkn->lokasiKerja) ? $pegawai_bkn->lokasiKerja : '-'; ?></b>
                                <?php echo $pegawai_bkn->lokasiKerja != $pegawai->LOKASI_KERJA ? "<a href='#' class='update_profile' kode='lokasiKerja' nip='".$pegawai_bkn->nipBaru."'><i class='fa fa-check-circle text-danger' data-toggle='tooltip' title='Profile ".$pegawai->LOKASI_KERJA."'></i></a>" : "<i class='fa fa-check-circle text-success' data-toggle='tooltip' title='Data sama'></i>"; ?>
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
                        <b><?php echo isset($pegawai_bkn->jenisPegawaiNama) ? $pegawai_bkn->jenisPegawaiNama : '-'; ?></b>
                        <?php echo $pegawai_bkn->jenisPegawaiNama != $pegawai->JENIS_PEGAWAI ? "<a href='#' class='update_profile' kode='jenisPegawaiId' nip='".$pegawai_bkn->nipBaru."'><i class='fa fa-check-circle text-danger' data-toggle='tooltip' title='Profile ".$pegawai->JENIS_PEGAWAI."'></i></a>" : "<i class='fa fa-check-circle text-success' data-toggle='tooltip' title='Data sama'></i>"; ?>
                    </div>
                </div>
                   
            </div>
            <div class="control-group col-sm-12">
                <div class="row">
                    <div class="col-sm-4">
                        Pangkat Golongan Aktif
                    </div>
                    <div class="form-group col-sm-8">
                        <b><?php echo isset($pegawai_bkn->pangkatAkhir) ? $pegawai_bkn->pangkatAkhir : '-'; ?></b>
                        <?php echo $pegawai_bkn->pangkatAkhir != $NAMA_PANGKAT ? "<a href='#' class='update_profile' kode='golRuangAkhirId' nip='".$pegawai_bkn->nipBaru."'><i class='fa fa-check-circle text-danger' data-toggle='tooltip' title='Profile ".$NAMA_PANGKAT."'></i></a>" : "<i class='fa fa-check-circle text-success' data-toggle='tooltip' title='Data sama'></i>"; ?>
                    </div>
                </div>
                   
            </div>
         
         <div class="control-group col-sm-6">
           <div class="row">
               <div class="form-group col-sm-8">
                   Golongan Ruang Awal
               </div>
               <div class="form-group col-sm-4">
                      <b><?php echo isset($pegawai_bkn->golRuangAwal) ? $pegawai_bkn->golRuangAwal : '-'; ?></b>
                      <?php echo $pegawai_bkn->golRuangAwal != $GOLONGAN_AWAL ? "<a href='#' class='update_profile' kode='golRuangAwal' nip='".$pegawai_bkn->nipBaru."'><i class='fa fa-check-circle text-danger' data-toggle='tooltip' title='Profile ".$GOLONGAN_AWAL."'></i></a>" : "<i class='fa fa-check-circle text-success' data-toggle='tooltip' title='Data sama'></i>"; ?>
               </div>
           </div>
       </div>
       <div class="control-group col-sm-6">
           <div class="row">
               <div class="form-group col-sm-4">
                   Golongan Ruang Terakhir
               </div>
               <div class="form-group  col-sm-8">
                      <b><?php echo isset($pegawai_bkn->golRuangAkhir) ? $pegawai_bkn->golRuangAkhir : '-'; ?></b>
                      <?php echo $pegawai_bkn->golRuangAkhir != $GOLONGAN_AKHIR ? "<a href='#' class='update_profile' kode='golRuangAkhirId' nip='".$pegawai_bkn->nipBaru."'><i class='fa fa-check-circle text-danger' data-toggle='tooltip' title='Profile ".$GOLONGAN_AKHIR."'></i></a>" : "<i class='fa fa-check-circle text-success' data-toggle='tooltip' title='Data sama'></i>"; ?>
               </div>
           </div>
       </div>
       <div class="control-group col-sm-6">
           <div class="row">
               <div class="form-group col-sm-8">
                   TMT Golongan
               </div>
               <div class="form-group col-sm-4">
                      <b><?php echo isset($pegawai_bkn->tmtGolAkhir) ? $pegawai_bkn->tmtGolAkhir : '-'; ?></b>
                      <?php echo $pegawai_bkn->tmtGolAkhir != $convert->fmtDate($pegawai->TMT_GOLONGAN ,"dd-mm-yyyy") ? "<a href='#' class='update_profile' kode='golRuangAkhirId' nip='".$pegawai_bkn->nipBaru."'><i class='fa fa-check-circle text-danger' data-toggle='tooltip' title='Profile ".$convert->fmtDate($pegawai->TMT_GOLONGAN ,"dd-mm-yyyy")."'></i></a>" : "<i class='fa fa-check-circle text-success' data-toggle='tooltip' title='Data sama'></i>"; ?>
               </div>
           </div>
       </div>
       <div class="control-group col-sm-6">
           <div class="row">
               <div class="form-group col-sm-4">
                   Gaji Pokok
               </div>
               <div class="form-group  col-sm-8">
                    <b><?php echo isset($pegawai_bkn->gajiPokok) ? $pegawai_bkn->gajiPokok : '-'; ?></b>
               </div>
           </div>
       </div>
       <div class="control-group col-sm-6">
           <div class="row">
               <div class="form-group col-sm-8">
                   TMT CPNS
               </div>
               <div class="form-group col-sm-4">
                      <b><?php echo isset($pegawai_bkn->tmtCpns) ? $pegawai_bkn->tmtCpns : '-'; ?></b>
                      <?php echo $pegawai_bkn->tmtCpns != $convert->fmtDate($pegawai->TMT_CPNS ,"dd-mm-yyyy") ? "<a href='#' class='update_profile' kode='tmtCpns' nip='".$pegawai_bkn->nipBaru."'><i class='fa fa-check-circle text-danger' data-toggle='tooltip' title='Profile ".$convert->fmtDate($pegawai->TMT_CPNS ,"dd-mm-yyyy")."'></i></a>" : "<i class='fa fa-check-circle text-success' data-toggle='tooltip' title='Data sama'></i>"; ?>
               </div>
           </div>
       </div>
       <div class="control-group col-sm-6">
           <div class="row">
               <div class="form-group col-sm-4">
                   SK CPNS
               </div>
               <div class="form-group  col-sm-8">
                      <b><?php echo isset($pegawai_bkn->nomorSkCpns) ? $pegawai_bkn->nomorSkCpns : '-'; ?></b>
                      <?php echo $pegawai_bkn->nomorSkCpns != $pegawai->NOMOR_SK_CPNS ? "<a href='#' class='update_profile' kode='nomorSkCpns' nip='".$pegawai_bkn->nipBaru."'><i class='fa fa-check-circle text-danger' data-toggle='tooltip' title='Profile ".$pegawai->NOMOR_SK_CPNS."'></i></a>" : "<i class='fa fa-check-circle text-success' data-toggle='tooltip' title='Data sama'></i>"; ?>
               </div>
           </div>
       </div>
        <div class="form-group col-sm-12">
            <div class="row">
                <div class="col-sm-4">
                    Status Kepegawaian
                </div>
                <div class="col-sm-8">
                  <?php $status_pns =  isset($pegawai->STATUS_CPNS_PNS) ? $pegawai->STATUS_CPNS_PNS == "P" ? "PNS" : "CPNS" : ''; ?>
                    <b><?php echo isset($pegawai_bkn->statusPegawai) ? $pegawai_bkn->statusPegawai : '-'; ?></b>
                    <?php echo $pegawai_bkn->statusPegawai != $status_pns ? "<a href='#' class='update_profile' kode='statusPegawai' nip='".$pegawai_bkn->nipBaru."'><i class='fa fa-check-circle text-danger' data-toggle='tooltip' title='Profile ".$status_pns."'></i></a>" : "<i class='fa fa-check-circle text-success' data-toggle='tooltip' title='Data sama'></i>"; ?>
                </div>
            </div>
        </div>
        <div class="form-group col-sm-12">
            <div class="row">
                <div class="col-sm-4">
                    Kartu Pegawai
                </div>
                <div class="col-sm-8">
                    <b><?php echo isset($pegawai_bkn->noSeriKarpeg) ? $pegawai_bkn->noSeriKarpeg : '-'; ?></b>
                    <?php echo $pegawai_bkn->noSeriKarpeg != $pegawai->KARTU_PEGAWAI ? "<a href='#' class='update_profile' kode='noSeriKarpeg' nip='".$pegawai_bkn->nipBaru."'><i class='fa fa-check-circle text-danger' data-toggle='tooltip' title='Profile ".$pegawai->KARTU_PEGAWAI."'></i></a>" : "<i class='fa fa-check-circle text-success' data-toggle='tooltip' title='Data sama'></i>"; ?>
                </div>
            </div>
        </div>
<div class="control-group col-sm-12">
                      <div class="row">
                         <div class="col-sm-4">
                           Status Perkawinan
                         </div>
                          <div class="form-group col-sm-8">
                              <b><?php echo isset($pegawai_bkn->statusPerkawinan) ? $pegawai_bkn->statusPerkawinan : '-'; ?></b>
                              <?php echo $pegawai_bkn->jenisKawinId != $pegawai->JENIS_KAWIN_ID ? "<a href='#' class='update_profile' kode='jenisKawinId' nip='".$pegawai_bkn->nipBaru."'><i class='fa fa-check-circle text-danger' data-toggle='tooltip' title='Profile ".$pegawai->JENIS_KAWIN_ID."'></i></a>" : "<i class='fa fa-check-circle text-success' data-toggle='tooltip' title='Data sama'></i>"; ?>
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
                                    <b><?php echo set_value('NO_SURAT_DOKTER', isset($pegawai_bkn->noSuratKeteranganDokter) ? trim($pegawai_bkn->noSuratKeteranganDokter) : '-'); ?></b>
                                    <?php echo $pegawai_bkn->noSuratKeteranganDokter != $pegawai->NO_SURAT_DOKTER ? "<a href='#' class='update_profile' kode='noSuratKeteranganDokter' nip='".$pegawai_bkn->nipBaru."'><i class='fa fa-check-circle text-danger' data-toggle='tooltip' title='Profile ".$pegawai->NO_SURAT_DOKTER."'></i></a>" : "<i class='fa fa-check-circle text-success' data-toggle='tooltip' title='Data sama'></i>"; ?>
                             </div>
                         </div>
                     </div>
                     <div class="control-group col-sm-6">
                         <div class="row">
                             <div class="form-group col-sm-4">
                                 Tanggal
                             </div>
                             <div class="form-group  col-sm-8">
                                    <b><?php echo set_value('TGL_SURAT_DOKTER', isset($pegawai_bkn->tglSuratKeteranganDokter) ? trim($pegawai_bkn->tglSuratKeteranganDokter) : '-'); ?></b>
                                    <?php echo $pegawai_bkn->tglSuratKeteranganDokter != $convert->fmtDate($pegawai->TGL_SURAT_DOKTER ,"dd-mm-yyyy") ? "<a href='#' class='update_profile' kode='tglSuratKeteranganDokter' nip='".$pegawai_bkn->nipBaru."'><i class='fa fa-check-circle text-danger' data-toggle='tooltip' title='Profile ".$convert->fmtDate($pegawai->TGL_SURAT_DOKTER ,"dd-mm-yyyy")."'></i></a>" : "<i class='fa fa-check-circle text-success' data-toggle='tooltip' title='Data sama'></i>"; ?>
                             </div>
                         </div>
                     </div>

                     <div class="control-group col-sm-6">
                         <div class="row">
                             <div class="form-group col-sm-8">
                                 No Surat Bebas Narkoba (CPNS)
                             </div>
                             <div class="form-group col-sm-4">
                                    <b><?php echo set_value('NO_BEBAS_NARKOBA', isset($pegawai_bkn->noSuratKeteranganBebasNarkoba) ? trim($pegawai_bkn->noSuratKeteranganBebasNarkoba) : ''); ?></b>
                                    <?php echo $pegawai_bkn->noSuratKeteranganBebasNarkoba != $pegawai->NO_BEBAS_NARKOBA ? "<a href='#' class='update_profile' kode='noSuratKeteranganBebasNarkoba' nip='".$pegawai_bkn->nipBaru."'><i class='fa fa-check-circle text-danger' data-toggle='tooltip' title='Profile ".$pegawai->NO_BEBAS_NARKOBA."'></i></a>" : "<i class='fa fa-check-circle text-success' data-toggle='tooltip' title='Data sama'></i>"; ?>
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
                                    <?php echo set_value('TGL_BEBAS_NARKOBA', isset($pegawai_bkn->tglSuratKeteranganBebasNarkoba) ? $pegawai_bkn->tglSuratKeteranganBebasNarkoba : '-'); ?>
                                    </b>
                                    <?php echo $pegawai_bkn->tglSuratKeteranganBebasNarkoba != $convert->fmtDate($pegawai->TGL_BEBAS_NARKOBA ,"dd-mm-yyyy") && $pegawai->TGL_BEBAS_NARKOBA != "1970-01-01" ? "<a href='#' class='update_profile' kode='tglSuratKeteranganBebasNarkoba' nip='".$pegawai_bkn->nipBaru."'><i class='fa fa-check-circle text-danger' data-toggle='tooltip' title='Profile ".$convert->fmtDate($pegawai->TGL_BEBAS_NARKOBA ,"dd-mm-yyyy")."'></i></a>" : "<i class='fa fa-check-circle text-success' data-toggle='tooltip' title='Data sama'></i>"; ?>
                             </div>
                         </div>
                     </div>
                     <div class="control-group col-sm-6">
                         <div class="row">
                             <div class="form-group col-sm-8">
                                 No Catatan Kepolisian
                             </div>
                             <div class="form-group col-sm-4">
                                    <b><?php echo set_value('skck', isset($pegawai_bkn->skck) ? trim($pegawai_bkn->skck) : ''); ?></b>
                                    <?php echo $pegawai_bkn->skck != $pegawai->NO_CATATAN_POLISI ? "<a href='#' class='update_profile' kode='skck' nip='".$pegawai_bkn->nipBaru."'><i class='fa fa-check-circle text-danger' data-toggle='tooltip' title='Profile ".$pegawai->NO_CATATAN_POLISI."'></i></a>" : "<i class='fa fa-check-circle text-success' data-toggle='tooltip' title='Data sama'></i>"; ?>
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
                                    <?php echo set_value('TGL_CATATAN_POLISI', isset($pegawai_bkn->tglSkck) ? $pegawai_bkn->tglSkck : ''); ?>
                                    </b>
                                    <?php echo $pegawai_bkn->tglSkck != $convert->fmtDate($pegawai->TGL_CATATAN_POLISI ,"dd-mm-yyyy") && $pegawai->TGL_CATATAN_POLISI != "1970-01-01" ? "<a href='#' class='update_profile' kode='tglSkck' nip='".$pegawai_bkn->nipBaru."'><i class='fa fa-check-circle text-danger' data-toggle='tooltip' title='Profile ".$convert->fmtDate($pegawai->TGL_CATATAN_POLISI ,"dd-mm-yyyy")."'></i></a>" : "<i class='fa fa-check-circle text-success' data-toggle='tooltip' title='Data sama'></i>"; ?>
                             </div>
                         </div>
                     </div>

                     <div class="control-group col-sm-6">
                         <div class="row">
                             <div class="form-group col-sm-8">
                                 AKTE KELAHIRAN
                             </div>
                             <div class="form-group col-sm-4">
                                    <b><?php echo set_value('akteKelahiran', isset($pegawai_bkn->akteKelahiran) ? trim($pegawai_bkn->akteKelahiran) : ''); ?></b>
                                    <?php echo $pegawai_bkn->akteKelahiran != $pegawai->AKTE_KELAHIRAN ? "<a href='#' class='update_profile' kode='akteKelahiran' nip='".$pegawai_bkn->nipBaru."'><i class='fa fa-check-circle text-danger' data-toggle='tooltip' title='Profile ".$pegawai->AKTE_KELAHIRAN."'></i></a>" : "<i class='fa fa-check-circle text-success' data-toggle='tooltip' title='Data sama'></i>"; ?>
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
                                    <?php echo set_value('BPJS', isset($pegawai_bkn->bpjs) ? $pegawai_bkn->bpjs : ''); ?>
                                    </b>
                                    <?php echo $pegawai_bkn->bpjs != $pegawai->BPJS ? "<a href='#' class='update_profile' kode='bpjs' nip='".$pegawai_bkn->nipBaru."'><i class='fa fa-check-circle text-danger' data-toggle='tooltip' title='Profile ".$pegawai->BPJS."'></i></a>" : "<i class='fa fa-check-circle text-success' data-toggle='tooltip' title='Data sama'></i>"; ?>
                             </div>
                         </div>
                     </div>

                     <div class="control-group col-sm-12">
                         <div class="row">
                             <div class="form-group col-sm-4">
                                 TASPEN
                             </div>
                             <div class="form-group col-sm-8">
                                    <b><?php echo set_value('NO_TASPEN', isset($pegawai_bkn->noTaspen) ? trim($pegawai_bkn->noTaspen) : '-'); ?></b>
                                    <?php echo $pegawai_bkn->noTaspen != $pegawai->NO_TASPEN ? "<a href='#' class='update_profile' kode='noTaspen' nip='".$pegawai_bkn->nipBaru."'><i class='fa fa-check-circle text-danger' data-toggle='tooltip' title='Profile ".$pegawai->NO_TASPEN."'></i></a>" : "<i class='fa fa-check-circle text-success' data-toggle='tooltip' title='Data sama'></i>"; ?>
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
                                    <?php echo isset($pegawai_bkn->noNpwp) ? $pegawai_bkn->noNpwp : '-'; ?>
                                    </b>
                                    <?php echo $pegawai_bkn->noNpwp != $pegawai->NPWP ? "<a href='#' class='update_profile' kode='noNpwp' nip='".$pegawai_bkn->nipBaru."'><i class='fa fa-check-circle text-danger' data-toggle='tooltip' title='Profile ".$pegawai->NPWP."'></i></a>" : "<i class='fa fa-check-circle text-success' data-toggle='tooltip' title='Data sama'></i>"; ?>
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
                                    <?php echo set_value('TGL_NPWP', isset($pegawai_bkn->tglNpwp) ? $pegawai_bkn->tglNpwp : '-'); ?>
                                    </b>
                                    <?php echo $pegawai_bkn->tglNpwp != $convert->fmtDate($pegawai->TGL_NPWP ,"dd-mm-yyyy") ? "<a href='#' class='update_profile' kode='tglNpwp' nip='".$pegawai_bkn->nipBaru."'><i class='fa fa-check-circle text-danger' data-toggle='tooltip' title='Profile ".$convert->fmtDate($pegawai->TGL_NPWP ,"dd-mm-yyyy")."'></i></a>" : "<i class='fa fa-check-circle text-success' data-toggle='tooltip' title='Data sama'></i>"; ?>
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
<script type="text/javascript">
  $('body').on('click','.update_profile',function () { 
  var kode =$(this).attr("kode");
  var valnip =$(this).attr("nip");
  swal({
    title: "Anda Yakin?",
    text: "Update data "+kode+"!",
    type: "warning",
    showCancelButton: true,
    confirmButtonClass: 'btn-success',
    confirmButtonText: 'Ya!',
    cancelButtonText: "Tidak, Batalkan!",
    closeOnConfirm: false,
    closeOnCancel: false
  },
  function (isConfirm) {
    if (isConfirm) {
      var post_data = "kode="+kode+"&nip="+valnip;
      $.ajax({
          url: "<?php echo base_url() ?>pegawai/bkn/save_data_utama",
          type:"POST",
          data: post_data,
          dataType: "json",
          timeout:180000,
          success: function (result) {
            if(result.success){ 
              swal("Perhatian!", result.msg, "success");
            }else{
              swal("Perhatian!", result.msg, "error");
            }
        },
        error : function(error) {
          alert(error);
        } 
      });        
      
    } else {
      swal("Batal", "", "error");
    }
  });
});
</script>