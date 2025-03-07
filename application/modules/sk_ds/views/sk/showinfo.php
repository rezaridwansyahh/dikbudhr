
<?php
$this->load->library('convert');
    $convert = new convert();
?>
        <div class="col-md-3">
        </div>
        <div class="col-md-6">
          <!-- Profile Image -->
          <div class="box box-warning">
            <div class="box-body box-profile">
                <h3 class="profile-username text-center">
                    <?php echo isset($pegawai->GELAR_DEPAN) ? $pegawai->GELAR_DEPAN : ''; ?>  <?php echo isset($pegawai->NAMA) ? $pegawai->NAMA : ''; ?> <?php echo isset($pegawai->GELAR_BELAKANG) ? $pegawai->GELAR_BELAKANG : ''; ?>
                </h3>
              <p class="text-muted text-center">
                    <?php if($pegawai->JENIS_JABATAN_ID == "1") {  ?>
                       <b><?php if($pegawai->JENIS_JABATAN_ID == "1") { echo isset($NAMA_JABATAN_REAL) ? $NAMA_JABATAN_REAL  : ""; } ?></b>
                       <b>TMT <?php if($pegawai->JENIS_JABATAN_ID == "1") { echo isset($pegawai->TMT_JABATAN) ? $convert->fmtDate($pegawai->TMT_JABATAN,"dd month yyyy")  : ""; } ?></b>           
                    <?php } ?>
                    <?php if($pegawai->JENIS_JABATAN_ID == "2") {  ?>
                        <b><?php if($pegawai->JENIS_JABATAN_ID == "2") { echo isset($NAMA_JABATAN_REAL) ? $NAMA_JABATAN_REAL  : ""; } ?></b>
                        <b>TMT <?php if($pegawai->JENIS_JABATAN_ID == "2") { echo isset($pegawai->TMT_JABATAN) ? $convert->fmtDate($pegawai->TMT_JABATAN,"dd month yyyy")  : ""; } ?></b>
                    <?php } ?>
                    <?php if($pegawai->JENIS_JABATAN_ID == "4") { ?>
                        <b><?php if($pegawai->JENIS_JABATAN_ID == "4") { echo isset($NAMA_JABATAN_REAL) ? $NAMA_JABATAN_REAL  : ""; }?></b>
                        <b>TMT <?php if($pegawai->JENIS_JABATAN_ID == "4") { echo isset($pegawai->TMT_JABATAN) ? $convert->fmtDate($pegawai->TMT_JABATAN,"dd month yyyy")  : ""; } ?></b>    
                    <?php } ?>
              </p>

                 
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

          <!-- About Me Box -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Keterangan SK</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <strong><i class="fa fa-user margin-r-5"></i> Penandatangan </strong>
              <p class="text-muted">
                  <?php echo isset($pejabat->GELAR_DEPAN) ? $pejabat->GELAR_DEPAN : "-"; ?>
                  <?php echo isset($pejabat->NAMA) ? $pejabat->NAMA : "-"; ?>
                  <?php echo isset($pejabat->GELAR_BELAKANG) ? $pejabat->GELAR_BELAKANG : "-"; ?>
              </p>
              <hr>
              <strong><i class="fa fa-user margin-r-5"></i> Status Koreksi </strong>
              <p class="text-muted">
                  <?php echo $data->is_corrected == "1" ? "<span class='label label-success'>Sudah Koreksi</span>" : ""; ?>
                  <?php echo $data->is_corrected == "2" ? "<span class='label label-warning'>Proses Koreksi</span>" : ""; ?>
                  <?php echo $data->is_corrected == "3" ? "<span class='label label-danger'>Di Kembalikan</span>" : ""; ?>

                  <?php 
                  if($data->is_corrected == "3"){
                    echo "<br><b>Catatan</b><br>".$data->catatan;   
                  }
                  ?>
              </p>
              <hr>
              <strong><i class="fa fa-user margin-r-5"></i> Status Tandatangan </strong>
              <p class="text-muted">
                  <?php echo $data->is_signed == "1" ? "<span class='label label-success'>Sudah Tandatangan</span>" : ""; ?>
                  <?php echo $data->is_signed == "0" ? "<span class='label label-danger'>Belum Tandatangan</span>" : ""; ?>
                  <?php echo $data->is_signed == "3" ? "<span class='label label-danger'>Dikoreksi</span>" : ""; ?>
              </p>
               <hr>
              <strong><i class="fa fa-user margin-r-5"></i> Korektor </strong>
              <p class="text-muted">
                  <?php 
                    if(isset($korektor) && is_array($korektor) && count($korektor)):
                      $no = 1;
                    foreach ($korektor as $record) {
                        echo $record->korektor_ke.". ";
                        echo $record->NAMA;
                    ?>
                      <?php echo $record->is_corrected == "1" ? "<span class='label label-success'>Sudah Koreksi</span>" : ""; ?>
                      <?php echo $record->is_corrected == "2" ? "<span class='label label-warning'>Sedang Koreksi</span>" : ""; ?>
                      <?php echo $record->is_corrected == "" ? "<span class='label label-danger'>Belum Koreksi</span>" : ""; ?>
                      <?php echo $record->is_corrected == "3" ? "<span class='label label-danger'>Sudah koreksi (Dikembalikan)</span>" : ""; ?>
                    <?php
                        echo "<br>";
                        $no++;
                      }
                    endif;

                  ?>
                  
              </p>
              
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3">
        </div>