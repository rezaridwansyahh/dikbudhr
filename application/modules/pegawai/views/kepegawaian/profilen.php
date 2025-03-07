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
    $tab_pane_keluarga = "tab_pane_keluarga";//uniqid("tab_pane_pindah_unit_kerja");
    $tab_pane_arsip = "tab_pane_arsip";
    
    
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
                    <?php echo isset($pegawai->GELAR_DEPAN) ? $pegawai->GELAR_DEPAN : ''; ?>  <?php echo isset($pegawai->NAMA) ? $pegawai->NAMA : ''; ?> <?php echo isset($pegawai->GELAR_BELAKANG) ? $pegawai->GELAR_BELAKANG : ''; ?>
                </h3>
              <p class="text-muted text-center">
                    <?php if($pegawai->JENIS_JABATAN_ID == "1") {  ?>
                       <b><?php if($pegawai->JENIS_JABATAN_ID == "1") { echo isset($NAMA_JABATAN_REAL) ? $NAMA_JABATAN_REAL  : ""; } ?></b>
                       <br>(<?php echo $JENIS_JABATAN; ?>)<br>
                       <b>TMT <?php if($pegawai->JENIS_JABATAN_ID == "1") { echo isset($pegawai->TMT_JABATAN) ? $convert->fmtDate($pegawai->TMT_JABATAN,"dd month yyyy")  : ""; } ?></b>           
                    <?php } ?>
                    <?php if($pegawai->JENIS_JABATAN_ID == "2") {  ?>
                        <b><?php if($pegawai->JENIS_JABATAN_ID == "2") { echo isset($NAMA_JABATAN_REAL) ? $NAMA_JABATAN_REAL  : ""; } ?></b>
                        <br>(<?php echo $JENIS_JABATAN; ?>)<br>
                        <b>TMT <?php if($pegawai->JENIS_JABATAN_ID == "2") { echo isset($pegawai->TMT_JABATAN) ? $convert->fmtDate($pegawai->TMT_JABATAN,"dd month yyyy")  : ""; } ?></b>
                    <?php } ?>
                    <?php if($pegawai->JENIS_JABATAN_ID == "4") { ?>
                        <b><?php if($pegawai->JENIS_JABATAN_ID == "4") { echo isset($NAMA_JABATAN_REAL) ? $NAMA_JABATAN_REAL  : ""; }?></b>
                        <br>(<?php echo $JENIS_JABATAN; ?>)<br>
                        <b>TMT <?php if($pegawai->JENIS_JABATAN_ID == "4") { echo isset($pegawai->TMT_JABATAN) ? $convert->fmtDate($pegawai->TMT_JABATAN,"dd month yyyy")  : ""; } ?></b>    
                    <?php } ?>

              </p>

                <?php if ($this->auth->has_permission('Pegawai.Kepegawaian.Ubahfoto')) : ?>
                    <a href="<?php echo base_url();?>admin/kepegawaian/pegawai/uploadfoto/<?php echo $PNS_ID; ?>" tooltip="Upload Foto" class="show-modal btn btn-primary btn-block"><i class="fa fa-photo"></i> Ubah foto </a>
                <?PHP endif; ?>
                <?php if($model!="update"){ ?>
                <?php if ($this->auth->has_permission('Pegawai.Kepegawaian.Updatemandiri')) : ?>
                    <a href="<?php echo base_url();?>admin/kepegawaian/pegawai/profilen/<?php echo urlencode(base64_encode($id)); ?>/update" tooltip="Upload Foto" class="btn btn-warning btn-block"><i class="fa fa-edit"></i> Ajukan Perubahan Data </a>
                <?PHP endif; ?>
                <?PHP }else{
                ?>
                    <a href="<?php echo base_url();?>admin/kepegawaian/pegawai/profilen/<?php echo urlencode(base64_encode($id)); ?>" class="btn btn-success btn-block"><i class="fa fa-user"></i> Lihat Profile </a>
                <?php
                } ?>
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
                  <?php
                    echo isset($NAMA_JABATAN) ? $NAMA_JABATAN : "-";
                  ?>
              </p>
              <hr>
              <strong><i class="fa fa-book margin-r-5"></i> Pendidikan</strong>

              <p class="text-muted">
                <?php 
                    if($selectedPendidikanID){
                        echo $selectedPendidikanID->NAMA;
                    }
                ?>
              </p>

              <hr>

              <strong><i class="fa fa-map-marker margin-r-5"></i> Alamat</strong>

              <p class="text-muted"><?php echo set_value('ALAMAT', isset($pegawai->ALAMAT) ? $pegawai->ALAMAT : ''); ?></p>

              <hr>
              <strong><i class="fa fa-users margin-r-5"></i> Masa Kerja</strong>
              <p class="text-muted">
                  <?php 
                    echo isset($recpns_aktif->masa_kerja_th) ? $recpns_aktif->masa_kerja_th  : ""; ?> 
                    Tahun 
                    <?php echo isset($recpns_aktif->masa_kerja_bl) ? $recpns_aktif->masa_kerja_bl  : ""; ?> Bulan
              </p>
              <hr>
              <!--
              <strong><i class="fa fa-pencil margin-r-5"></i> Keahlian</strong>

              <p>
                <span class="label label-danger">UI Design</span>
                <span class="label label-success">Coding</span>
                <span class="label label-info">Javascript</span>
                <span class="label label-warning">PHP</span>
                <span class="label label-primary">Node.js</span>
              </p>

              <hr>
              -->
              <strong><i class="fa fa-file-text-o margin-r-5"></i> Unit Organisasi</strong>
              <p> 
                <ul>
                  <?php 
                      foreach($parent_path_array_unor as $node){
                          echo "<li><strong>".$node->NAMA_UNOR."</strong></li>";        
                      }
                  ?>
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
                        <a href="#<?php echo $tab_pane_personal_id; ?>" data-toggle="tab" aria-expanded="true"> Data Personal </a>
                    </li>
                    <li class="">
                        <a href="#<?php echo $tab_pane_keluarga; ?>" data-toggle="tab" aria-expanded="false"> Keluarga </a>
                    </li>
                    <li class="">
                        <a href="#<?php echo $tab_pendidikan; ?>" data-toggle="tab" aria-expanded="false"> Pendidikan </a>
                    </li>
                    <li class="">
                        <a href="#<?php echo $tab_pane_penilaian; ?>" data-toggle="tab" aria-expanded="false"> Penilaian </a>
                    </li>
                    <li class="">
                        <a href="#<?php echo $tab_pane_riwayat; ?>" data-toggle="tab" aria-expanded="false"> Riwayat </a>
                    </li>
                    <?php if ($this->auth->has_permission('Arsip_digital.Arsip.View')) : ?>
                    <li class="">
                        <a href="#<?php echo $tab_pane_arsip; ?>" data-toggle="tab" aria-expanded="false"> Arsip </a>
                    </li>
                    <?php endif; ?>
                </ul>
                <div class="tab-content">
                    <?php 
                        if($model=="update"){
                          $this->load->view('kepegawaian/tab_pane_personaledit',array('TAB_ID'=>$tab_pane_personal_id));
                        }else{
                          $this->load->view('kepegawaian/tab_pane_personal',array('TAB_ID'=>$tab_pane_personal_id));  
                        }                       
                        $this->load->view('kepegawaian/tabkeluarga',array('TAB_ID'=>$tab_pane_keluarga));
                        $this->load->view('kepegawaian/tabpendidikan',array('TAB_ID'=>$tab_pendidikan));
                        $this->load->view('kepegawaian/tab_pane_penilaian',array('TAB_ID'=>$tab_pane_penilaian));
                        $this->load->view('kepegawaian/tab_pane_riwayat',array('TAB_ID'=>$tab_pane_riwayat));
                        if ($this->auth->has_permission('Arsip_digital.Arsip.View')) :
                          $this->load->view('kepegawaian/tab_pane_arsip',array('TAB_ID'=>$tab_pane_arsip));
                        endif;
                    ?>
                </div>
            </div>
        </div>
        <!-- /.col -->
      </div>
