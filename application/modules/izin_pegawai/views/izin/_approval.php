<?php if($level == 1){ ?>
<div class="row">
      <div class="col-lg-2">
      </div>
      <div class="col-lg-8">
          <div class="row bs-wizard" style="border-bottom:0;">
                           
              <div class="col-xs-6 bs-wizard-step complete"><!-- complete -->
                <div class="text-center bs-wizard-stepnum">Pegawai</div>
                <div class="progress"><div class="progress-bar"></div></div>
                <a href="#" class="bs-wizard-dot"></a>
                <div class="bs-wizard-info text-center"><?php echo $NAMA_PEGAWAI; ?></div>
              </div>
      
              <div class="col-xs-6 bs-wizard-step <?php echo $nostatus >= 3 ? "complete" : "disabled" ?> <?php echo $nostatus == 3 ? "active" : "" ?>  <?php echo $status_atasan ? "complete" : "disabled" ?>"><!-- complete -->
                <div class="text-center bs-wizard-stepnum">Atasan Langsung</div>
                <div class="progress"><div class="progress-bar"></div></div>
                <a href="#" class="bs-wizard-dot"></a>
                <div class="bs-wizard-info text-center"><?php echo $NAMA_ATASAN; ?></div>
              </div>
          </div>
      </div>
      <div class="col-lg-2">
      </div>
  </div>
<?php } ?>
<?php if($level == 2){ ?>
<div class="row">
      <div class="col-lg-2">
      </div>
      <div class="col-lg-8">
          <div class="row bs-wizard" style="border-bottom:0;">
                           
              <div class="col-xs-4 bs-wizard-step complete"><!-- complete -->
                <div class="text-center bs-wizard-stepnum">Pegawai</div>
                <div class="progress"><div class="progress-bar"></div></div>
                <a href="#" class="bs-wizard-dot"></a>
                <div class="bs-wizard-info text-center"><?php echo $NAMA_PEGAWAI; ?></div>
              </div>
      
              <div class="col-xs-4 bs-wizard-step <?php echo $nostatus >= 3 ? "complete" : "" ?> <?php echo $nostatus == 3 ? "active" : "" ?> <?php echo $status_atasan ? "complete" : "disabled" ?>"><!-- complete -->
                <div class="text-center bs-wizard-stepnum">Atasan Langsung</div>
                <div class="progress"><div class="progress-bar"></div></div>
                <a href="#" class="bs-wizard-dot"></a>
                <div class="bs-wizard-info text-center"><?php echo $NAMA_ATASAN; ?></div>
              </div>
      
              <div class="col-xs-4 bs-wizard-step <?php echo $nostatus >= 4 ? "complete" : "disabled" ?> <?php echo $nostatus == 4 ? "active" : "" ?>"><!-- active -->
                <div class="text-center bs-wizard-stepnum">PYBMC</div>
                <div class="progress"><div class="progress-bar"></div></div>
                <a href="#" class="bs-wizard-dot"></a>
                <div class="bs-wizard-info text-center"><?php echo $NAMA_PPK; ?></div>
              </div>  
          </div>
      </div>
      <div class="col-lg-2">
      </div>
  </div>
<?php } ?>