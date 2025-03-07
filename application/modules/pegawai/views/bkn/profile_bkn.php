<script src="<?php echo base_url(); ?>themes/admin/js/jqueryvalidation/jquery.validate.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/daterangepicker/daterangepicker.css">
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/datepicker/datepicker3.css">
<?php 
  $this->load->library('convert');
  $convert = new convert();
    $tab_riwayat = "tab_riwayat";
    $tab_pane_personal_id = "tab_pane_personal_id";
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
<div class="row">
        <div class="col-md-3">

          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile">

              <img src="<?php echo base_url(); ?><?php echo $foto_pegawai; ?>" class="img-responsive pic-bordered" id="photopegawai" alt="Photo" width="100%">
                <h3 class="profile-username text-center">
                    <?php echo isset($pegawai_bkn->gelarDepan) ? $pegawai_bkn->GELAR_DEPAN : ''; ?>  <?php echo isset($pegawai_bkn->nama) ? $pegawai_bkn->nama : ''; ?> <?php echo isset($pegawai_bkn->gelarBelakang) ? $pegawai_bkn->gelarBelakang : ''; 
                    ?>
                </h3>
              <p class="text-muted text-center">
                   <?php echo isset($pegawai_bkn->jabatanNama) ? $pegawai_bkn->jabatanNama : ''; ?><br>
                   <?php echo $pegawai_bkn->jabatanNama != $NAMA_JABATAN_REAL ? "<i class='fa fa-check-circle text-danger' data-toggle='tooltip' title='Profile ".$NAMA_JABATAN_REAL."'></i>" : "<i class='fa fa-check-circle text-success' data-toggle='tooltip' title='Data sama'></i>"; ?>
                   (<?php echo isset($pegawai_bkn->tmtJabatan) ? $pegawai_bkn->tmtJabatan : ''; ?>)<br>
                   <?php echo isset($pegawai_bkn->jenisJabatanId) ? $pegawai_bkn->jenisJabatan : ''; ?>
                   <?php echo $pegawai_bkn->jenisJabatanId != $pegawai->JENIS_JABATAN_ID ? "<i class='fa fa-check-circle text-danger' data-toggle='tooltip' title='Profile ".$pegawai->JENIS_JABATAN_ID."'></i>" : "<i class='fa fa-check-circle text-success' data-toggle='tooltip' title='Data sama'></i>"; ?>
              </p>

                 
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

          <!-- About Me Box -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Profile Pegawai</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <strong><i class="fa fa-user margin-r-5"></i> Jabatan (Sesuai Peta Jabatan)</strong>
              <p class="text-muted">
                  <?php echo isset($pegawai_bkn->jabatanNama) ? $pegawai_bkn->jabatanNama : ''; ?>
              </p>
              <hr>
              <strong><i class="fa fa-book margin-r-5"></i> Pendidikan</strong>

              <p class="text-muted">
                <?php echo isset($pegawai_bkn->pendidikanTerakhirNama) ? $pegawai_bkn->pendidikanTerakhirNama : ''; ?>
              </p>

              <hr>

              <strong><i class="fa fa-map-marker margin-r-5"></i> Alamat</strong>

              <p class="text-muted"><?php echo isset($pegawai_bkn->alamat) ? $pegawai_bkn->alamat : ''; ?>
                <?php echo $pegawai_bkn->alamat != $pegawai->ALAMAT ? "<i class='fa fa-check-circle text-danger' data-toggle='tooltip' title='Profile ".$pegawai->ALAMAT."'></i>" : "<i class='fa fa-check-circle text-success' data-toggle='tooltip' title='Data sama'></i>"; ?>
              </p>
              <hr>
              <strong><i class="fa fa-users margin-r-5"></i> Masa Kerja</strong>
              <p class="text-muted">
                  <?php echo isset($pegawai_bkn->masaKerja) ? $pegawai_bkn->masaKerja : ''; ?>
              </p>
              <hr>
              
              <strong><i class="fa fa-file-text-o margin-r-5"></i> Unit Organisasi</strong>
              <p> 
                <ul>
                  <?php echo isset($pegawai_bkn->unorNama) ? $pegawai_bkn->unorNama : ''; ?>
                  <?php echo $pegawai_bkn->unorNama != $NAMA_UNOR ? "<a href='#' class='update_profile' kode='unorId' nip='".$pegawai_bkn->nipBaru."'><i class='fa fa-check-circle text-danger' data-toggle='tooltip' title='Profile ".$NAMA_UNOR."'></i></a>" : "<i class='fa fa-check-circle text-success' data-toggle='tooltip' title='Data sama'></i>"; ?>
                  <br><?php echo isset($pegawai_bkn->unorIndukNama) ? $pegawai_bkn->unorIndukNama : ''; ?>
                </ul>
                </p>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
           <div class="nav-tabs-custom">
                <ul id="tab-insides-here" class="nav nav-tabs">
                    <li class="active">
                        <a href="#<?php echo $tab_pane_personal_id; ?>" data-toggle="tab" aria-expanded="true"> Data Personal BKN</a>
                    </li>
                    <li class="">
                        <a href="#<?php echo $tab_riwayat; ?>" data-toggle="tab" aria-expanded="false"> Riwayat BKN</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <?php 
                        $this->load->view('bkn/tab_pane_personal_bkn',array('TAB_ID'=>$tab_pane_personal_id));
                        $this->load->view('bkn/tab_riwayat_bkn',array('TAB_ID'=>$tab_riwayat));
                    ?>
                </div>
            </div>
        </div>
        <!-- /.col -->
      </div>
