<?php
$span = 5;
$jumlah = count(json_decode($PERSETUJUAN)); 
if($jumlah >= 2){
  $span = 3;
}
?>
<?php if($jumlah > 0){

      
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
                <div class="bs-wizard-info text-center"><?php echo $NAMA_PEGAWAI; ?></div>
              </div>
              <?php
              foreach(json_decode($PERSETUJUAN) as $values)
             {
                  
                  ?>
                  <div class="col-xs-<?php echo $span; ?> bs-wizard-step"><!-- complete -->
                    <div class="text-center bs-wizard-stepnum"><?php echo get_pejabat_cuti($values); ?></div>
                    <div class="progress"><div class="progress-bar"></div></div>
                    <a href="#" class="bs-wizard-dot"></a>
                    <div class="bs-wizard-info text-center"><?php echo isset($aatasan[$values]->NAMA_ATASAN) ? $aatasan[$values]->NAMA_ATASAN : ""; ?></div>
                  </div>
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