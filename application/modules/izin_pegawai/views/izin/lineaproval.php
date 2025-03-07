<?php
$span = 5;
$jumlah = count(json_decode($PERSETUJUAN)); 
if($jumlah >= 2){
  $span = 3;
}
?>
<?php if($PERSETUJUAN != ""){
$this->load->library('Convert');
$convert = new Convert;
$this->load->helper('dikbud');      
?>
<div class="row">
      <div class="col-lg-2">
      </div>
      <div class="col-lg-8">
          <div class="row bs-wizard" style="border-bottom:0;">       
              <div class="col-xs-<?php echo $span; ?> bs-wizard-step complete"><!-- complete -->
                <div class="text-center bs-wizard-stepnum"><small class="label text-center bg-green">Pegawai</small></div>
                <div class="progress"><div class="progress-bar"></div></div>
                <a href="#" class="bs-wizard-dot"></a>
                <div class="bs-wizard-info text-center"><?php echo $NAMA_PEGAWAI; ?>
                <br>
                <small class="label text-center bg-green">Diajukan</small>
                <?php echo $izin_pegawai->TGL_DIBUAT ? "<br><i>Tgl : ".$convert->fmtDate($izin_pegawai->TGL_DIBUAT,"dd month yyyy")."</i>" : ""?>
                </div>
              </div>
              <?php
            foreach(json_decode($PERSETUJUAN) as $values)
             {  
                if(isset($verifikasidata[$aatasan[$values]->NIP_ATASAN])){
                ?>
                  <?php $record_verifikasi = $verifikasidata[$aatasan[$values]->NIP_ATASAN]; ?>
                  <div class="col-xs-<?php echo $span; ?> bs-wizard-step <?php echo $record_verifikasi->STATUS_VERIFIKASI  == "3" ? " complete" : ""?>"><!-- complete -->
                    <div class="text-center bs-wizard-stepnum"><?php echo get_pejabat_cuti($values); ?></div>
                    <div class="progress"><div class="progress-bar"></div></div>
                    <a href="#" class="bs-wizard-dot"></a>
                    <div class="bs-wizard-info text-center"><?php echo isset($aatasan[$values]->NAMA_ATASAN) ? $aatasan[$values]->NAMA_ATASAN : ""; ?>
                        <?php echo isset($record_verifikasi->STATUS_VERIFIKASI) ? "<br>".get_status_cuti($record_verifikasi->STATUS_VERIFIKASI) : "<br><small class='label text-center bg-yellow'>Blm Proses</small>"?>
                        <?php echo $record_verifikasi->STATUS_VERIFIKASI  != "" ? "<br><i>Tgl : ".$convert->fmtDate($record_verifikasi->TANGGAL_VERIFIKASI,"dd month yyyy")."</i>" : ""?>
                        <?php echo $record_verifikasi->STATUS_VERIFIKASI  == "6" ? "<br><i><b>Alasan</b> : ".$record_verifikasi->ALASAN_DITOLAK."</i>" : ""?>
                    </div>
                  </div>
                <?php
                }
                ?>
              <?php 
             }
              ?>
          </div>
      </div>
      <div class="col-lg-2">
      </div>
  </div>
<?php }else{ ?>
  <div class="callout callout-info">
     <h4>Tidak perlu persetujuan</h4>
   </div>
<?php } ?>
 