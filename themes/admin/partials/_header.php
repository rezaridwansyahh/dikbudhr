<?php
  Assets::add_css( array(
    'font-awesome.min.css',
    'ionicons.min.css',
  ));

  if (isset($shortcut_data) && is_array($shortcut_data['shortcut_keys'])) {
  //  Assets::add_js($this->load->view('ui/shortcut_keys', $shortcut_data, true), 'inline');
  }
  $module = $this->uri->segment(1);
  $mainmenu = $this->uri->segment(2);
  $menu = $this->uri->segment(3);
  $submenu = $this->uri->segment(4);

?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title><?php echo isset($toolbar_title) ? $toolbar_title .' : ' : ''; ?> <?php echo $this->settings_lib->item('site.title') ?></title>
  <link rel="shortcut icon" href="<?php echo base_url(); ?>favicon.ico">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="noindex" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/dist/css/skins/_all-skins.min.css">
    <script src="<?php echo base_url(); ?>themes/admin/plugins/jQuery/jquery-2.2.3.min.js"></script>
     
    <script src="<?php echo base_url(); ?>themes/admin/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url(); ?>themes/admin/plugins/datatables/dataTables.bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>themes/admin/plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js"></script>
    <script src="<?php echo base_url(); ?>themes/admin/plugins/datatables/extensions/Responsive/js/dataTables.responsive.js"></script>
    <script src="<?php echo base_url(); ?>themes/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
    
    <!-- Select2 -->
    <script src="<?php echo base_url(); ?>themes/admin/plugins/select2/select2.full.min.js"></script>

    <!-- sweet alert -->
    <script src="<?php echo base_url(); ?>themes/admin/js/sweetalert.js"></script>
    <link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/css/sweetalert.css">
   
    <link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/select2/select2.min.css">
    <!-- daterange picker -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/daterangepicker/daterangepicker.css">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/datepicker/datepicker3.css">

    <link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css">

     <link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/jquery-scrolling-tabs/jquery.scrolling-tabs.css">
     <script src="<?php echo base_url(); ?>assets/js/jquery-scrolling-tabs/jquery.scrolling-tabs.js"></script>
     <script src="<?php echo base_url(); ?>assets/plugins/jstree/dist/jstree.js"></script>
     
     

     <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/jstree/dist/themes/default/style.min.css">
     
     <style>
        #overlay {
            position: fixed;
            top: 0;
            z-index: 2000;
            width: 100%;
            height: 100%;
            display: none;
            background: rgba(0, 0, 0, 0.6);
        }

        .cv-spinner {
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 4px #ddd solid;
            border-top: 4px #2e93e6 solid;
            border-radius: 50%;
            animation: sp-anime 0.8s infinite linear;
        }

        @keyframes sp-anime {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(359deg);
            }
        }

        .is-hide {
            display: none;
        }
    </style>

    <?php echo Assets::css(null, true); ?> 
</head> 

<body class="skin-blue sidebar-mini <?php echo (isset($collapse) && $collapse) ? "sidebar-collapse" : ""; ?>">
  <div id="overlay">
    <div class="cv-spinner">
      <span class="spinner"></span>
    </div>
  </div>
<div id="wrapper">

<header class="main-header">

    <!-- Logo -->
    <a href="#" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><img src="<?php echo base_url();?>assets/images/logo.png" height="25"/></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg">
        <img src="<?php echo base_url();?>assets/images/logo.png" height="25"/> 
          <?php
        echo trim($this->settings_lib->item('site.title'));
      ?></span>
    </a>

    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
              <i class="fa fa-bell-o"></i>
              <?php
              $jmlnotifpindah = isset($countnotifpindahunits) ? $countnotifpindahunits : 0;
              $jmlnotifupdate = isset($count_notif_update) ? $count_notif_update : 0;
              $jmlallnotif = (int)$jmlnotifupdate + (int)$jmlnotifpindah;
              ?>
              <span class="label label-warning"><?php echo isset($jmlallnotif) ? $jmlallnotif : "0"; ?></span>
            </a>
            <ul class="dropdown-menu">
              <?php if(isset($countnotifpindahunits) && $countnotifpindahunits>0)
              { ?>
              <li class="header">Anda memiliki <?php echo isset($countnotifpindahunits) ? $countnotifpindahunits : ""; ?> notifikasi Permintaan Mutasi</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <div class="slimScrollDiv">
                  <ul class="menu">
                  <li>
                    <?php
                       $nomor_urut=1;
                       if(isset($notifpindahunits) && is_array($notifpindahunits) && count($notifpindahunits)):
                        foreach ($notifpindahunits as $recordnotif) {
                          ?>
                          <a href="<?php echo base_url()."admin/layanan/pindah_unit/viewdetil/".$recordnotif->ID; ?>" class="show-modal">
                            <i class="fa fa-warning text-yellow"></i> 
                              <?php echo $recordnotif->NAMA; ?>
                          </a>
                        <?php
                        }
                      endif;
                      ?>
                  </li>
                  </ul>
                </div>
              </li>
            <?php }else{
            ?>
            <li class="header">Anda tidak memiliki notifikasi Mutasi Pegawai</li>
              <li>
            <?php
            } ?>

            <?php if(isset($count_notif_update) && $count_notif_update>0){ ?>
              <li class="header">Anda memiliki <?php echo isset($count_notif_update) ? $count_notif_update : "0"; ?> notifikasi Perubahan data pegawai</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <div class="slimScrollDiv">
                  <ul class="menu">
                  <li>
                    <?php
                       $nomor_urut = 1;
                       $linknotif = "";
                       if(isset($notifupdatemandiri) && is_array($notifupdatemandiri) && count($notifupdatemandiri)):
                        foreach ($notifupdatemandiri as $recordnotif) {
                          if(trim($recordnotif->NAMA_KOLOM) == "RIWAYAT PENDIDIKAN"){
                            $linknotif = base_url()."pegawai/riwayatpendidikan/verifikasi/".$recordnotif->PNS_ID."/".$recordnotif->ID_TABEL;
                          }else if(trim($recordnotif->NAMA_KOLOM) == "RIWAYAT KEPANGKATAN"){
                            $linknotif = base_url()."pegawai/riwayatkepangkatan/verifikasi/".$recordnotif->PNS_ID."/".$recordnotif->ID_TABEL;
                          }else if(trim($recordnotif->NAMA_KOLOM) == "RIWAYAT JABATAN"){
                            $linknotif = base_url()."pegawai/riwayatjabatan/verifikasi/".$recordnotif->PNS_ID."/".$recordnotif->ID_TABEL;
                          }
                          else{
                            $linknotif = base_url()."admin/kepegawaian/pegawai/viewupdate/".$recordnotif->ID;
                            // $linknotif = "<a href='".base_url()."admin/kepegawaian/pegawai/viewupdate/".$recordnotif->ID."' class='btn btn-sm btn-warning show-modal'><i class='glyphicon glyphicon-edit'></i> </a>";  
                          }
                          ?>
                          <a href="<?php echo $linknotif; ?>" class="show-modal" title="Verifikasi Update" tooltip="Verifikasi Update">
                            <i class="fa fa-warning text-yellow"></i> 
                              <?php echo $recordnotif->NAMA; ?> 
                              [<?php echo $recordnotif->NAMA_KOLOM; ?>]
                          </a>
                        <?php
                        }
                      endif;
                      ?>
                  </li>
                  <li>
                    <?php if (!$this->auth->has_permission('Pegawai.Kepegawaian.VerifikasiUpdate3') && $this->auth->has_permission('Pegawai.Kepegawaian.VerifikasiUpdate')) : ?>
                      <center>
                        <a href="<?php echo base_url()."admin/kepegawaian/pegawai/viewupdatemandiri1/"; ?>" title="Verifikasi Update" tooltip="Verifikasi Update">Lihat Semua</a>
                      </center>
                    </li>
                  <?php endif; ?>
                    <?php if ($this->auth->has_permission('Pegawai.Kepegawaian.VerifikasiUpdate3')) : ?>
                    <li>
                      <center>
                        <a href="<?php echo base_url()."admin/kepegawaian/pegawai/viewupdatemandiri/"; ?>" title="Verifikasi Update" tooltip="Verifikasi Update">Lihat Semua</a>
                      </center>
                    </li>
                    <?php endif; ?>
                  </ul>
                </div>
              </li>
            <?php }else{
            ?>
            <li class="header">Anda tidak memiliki notifikasi Update data mandiri</li>
              <li>
            <?php

            } ?>
            </ul>

          </li>
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="<?php echo base_url();?><?php echo (isset($current_user->PHOTO) && $current_user->PHOTO != "") ? $current_user->PHOTO : 'noimage.jpg'; ?>" class="user-image" alt="Photo Profile">
              <span><?php echo (isset($current_user->display_name) && !empty($current_user->display_name)) ? $current_user->display_name : ($this->settings_lib->item('auth.use_usernames') ? $current_user->username : $current_user->email); ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="<?php echo base_url();?>assets/images/noimage.jpg" class="img-circle" alt="User Image">

                <p>
                  <?php echo (isset($current_user->display_name) && !empty($current_user->display_name)) ? $current_user->display_name : ($this->settings_lib->item('auth.use_usernames') ? $current_user->username : $current_user->email); ?>
                  <small>Role : <?php echo isset($current_user->role_name) ? $current_user->role_name : "" ?></small>
                </p>
              </li>
              <!-- Menu Body -->
               
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="<?php echo site_url(SITE_AREA .'/settings/users/edit') ?>" class="btn btn-warning btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="<?php echo site_url('logout'); ?>" class="btn btn-warning btn-flat">Sign out</a>
                </div>
                 
              </li>
               <?php if($this->session->userdata('username_real') != ""){ ?>
                <li class="user-footer">
                    <div>
                      <a href="<?php echo base_url(); ?>admin/settings/users/loginback/<?php echo $this->session->userdata('username_real'); ?>" class="btn btn-block btn-social btn-flickr">Kembali ke <?php echo $this->session->userdata('username_real'); ?></a>
                    </div>
                  </li>
              <?php } ?>
              
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>
        </ul>
      </div>

    </nav>
  </header>
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="<?php echo base_url();?><?php echo (isset($current_user->PHOTO) && $current_user->PHOTO != "") ? $current_user->PHOTO : 'noimage.jpg'; ?>" class="img-circle" alt="Profile Photo">
        </div>
        <div class="pull-left info">
          <p><?php echo (isset($current_user->display_name) && !empty($current_user->display_name)) ? $current_user->display_name : ($this->settings_lib->item('auth.use_usernames') ? $current_user->username : $current_user->email); ?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- search form -->
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="keyword" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
        <li class="header">MAIN NAVIGATION</li>
        <?php if ($this->auth->has_permission('Pegawai.Kepegawaian.Pribadi')) : ?>
          <li class="treeview <?php echo $submenu == 'profile' ? 'active' : '' ?>">
            <a href="<?php echo base_url();?>admin/kepegawaian/pegawai/profilen">
                <i class="fa fa-user"></i>
                <span>Data Pribadi</span>    
              </a>
          </li>
        <?php endif; ?>
        <?php if ($this->auth->has_permission('Dashboard.Pribadi.View')) : ?>
          <li class="treeview <?php echo $menu == 'dashboard' ? 'active' : '' ?>">
            <a href="<?php echo base_url();?>admin/reports/dashboard/pribadi">
                <i class="fa fa-dashboard"></i>
                <span>Dashboard Pribadi</span>    
              </a>
          </li>
        <?php endif; ?>
        <?php if ($this->auth->has_permission('Dashboard.Reports.View')) : ?>
          <li class="treeview <?php echo $menu == 'dashboard' ? 'active' : '' ?>">
            <a href="<?php echo base_url();?>admin/reports/dashboard">
                <i class="fa fa-dashboard"></i>
                <span>Dashboard</span>    
              </a>
          </li>
        <?php endif; ?>
        <?php if ($this->auth->has_permission('Arsip_digital.Reports.DashboardAdmin')) : ?>
          <li class="treeview <?php echo $menu == 'DashboardAdmin' ? 'active' : '' ?>">
            <a href="<?php echo base_url();?>admin/reports/arsip_digital">
                <i class="fa fa-bar-chart"></i>
                <span>Dashboard Arsip</span>    
              </a>
          </li>
        <?php endif; ?>
        <?php if ($this->auth->has_permission('Sk_ds.DashboardDs.View')) : ?>
          <li class="treeview <?php echo $menu == 'sk_ds' ? 'active' : '' ?>">
            <a href="<?php echo base_url();?>admin/sk/sk_ds/dashboard">
                <i class="fa fa-dashboard"></i>
                <span>Dashboard DS</span>    
              </a>
          </li>
        <?php endif; ?>
        
         <?php if ($this->auth->has_permission('Rekap.Reports.View')) : ?>
          <li class="treeview <?php echo $module == 'rekap' ? 'active' : '' ?>">
            <a href="#">
                <i class="fa fa-folder"></i>
                <span>Rekap </span>    
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
              <ul class="treeview-menu">
                  <?php if ($this->auth->has_permission('Rekap.Reports.Satker')) : ?>
                  <li>
                      <a href="<?php echo base_url();?>rekap/satkers">
                          <i class="fa fa-circle-o"></i>
                          <span>Per Satker </span>    
                      </a>
                    </li>
                  
                  <?php endif; ?>
                  <?php if ($this->auth->has_permission('Rekap.Reports.Eis')) : ?>
          				<li>
                      <a href="<?php echo base_url();?>rekap/eis">
                          <i class="fa fa-circle-o"></i>
                          <span>EIS </span>    
                      </a>
                    </li>
          				
                  <?php endif; ?>
                  <?php if ($this->auth->has_permission('Rekap.Reports.Eise')) : ?>
                  <li>
                    <a href="<?php echo base_url();?>rekap/eis_per_eselon">
                        <i class="fa fa-circle-o"></i>
                        <span>EIS Per Eselon </span>    
                    </a>
                  </li>
                <?php endif; ?>
                <?php if ($this->auth->has_permission('Rekap.Reports.Golongan_usia')) : ?>
                <li>
                  <a href="<?php echo base_url();?>rekap/golongan_usia">
                      <i class="fa fa-circle-o"></i>
                      <span>Golongan dan Range Usia </span>    
                  </a>
                </li>
                <?php endif; ?>
                <?php if ($this->auth->has_permission('Rekap.Reports.BupUsia')) : ?>
                <li>
                  <a href="<?php echo base_url();?>rekap/bup_usia">
                      <i class="fa fa-circle-o"></i>
                      <span>BUP dan Usia </span>    
                  </a>
                </li>
                <?php endif; ?>
                <?php if ($this->auth->has_permission('Rekap.Reports.Genderusia')) : ?>
                <li>
                  <a href="<?php echo base_url();?>rekap/gender_usia">
                      <i class="fa fa-circle-o"></i>
                      <span>Gender dan Usia </span>    
                  </a>
                </li>
                <?php endif; ?>
                <?php if ($this->auth->has_permission('Rekap.Reports.Pendidikan_usia')) : ?>
                <li>
                  <a href="<?php echo base_url();?>rekap/pendidikan_usia">
                      <i class="fa fa-circle-o"></i>
                      <span>Pendidikan dan Usia </span>    
                  </a>
                </li>
                <?php endif; ?>
                <?php if ($this->auth->has_permission('Rekap.Reports.Golongan_gender')) : ?>
                <li>
                  <a href="<?php echo base_url();?>rekap/golongan_gender">
                      <i class="fa fa-circle-o"></i>
                      <span>Golongan dan Gender </span>    
                  </a>
                </li>
                <?php endif; ?>
                <?php if ($this->auth->has_permission('Rekap.Reports.Golongan_pendidikan')) : ?>
                <li>
                  <a href="<?php echo base_url();?>rekap/golongan_pendidikan">
                      <i class="fa fa-circle-o"></i>
                      <span>Golongan dan Pendidikan </span>    
                  </a>
                </li>
                <?php endif; ?>
                <?php if ($this->auth->has_permission('Rekap.Reports.Pendidikan_gender')) : ?>
                <li>
                  <a href="<?php echo base_url();?>rekap/pendidikan_gender">
                      <i class="fa fa-circle-o"></i>
                      <span>Pendidikan dan Gender </span>    
                  </a>
                </li>
                <?php endif; ?>
                <?php if ($this->auth->has_permission('Rekap.Reports.Agama_gender')) : ?>
                <li>
                  <a href="<?php echo base_url();?>rekap/agama_gender">
                      <i class="fa fa-circle-o"></i>
                      <span>Agama dan Gender </span>    
                  </a>
                </li>
                <?php endif; ?>
                <?php if ($this->auth->has_permission('Rekap.Reports.Status_pegawai')) : ?>
                <li>
                  <a href="<?php echo base_url();?>rekap/stats-pegawai-jabatan">
                      <i class="fa fa-circle-o"></i>
                      <span>Jumlah Pegawai per Jabatan </span>    
                  </a>
                </li>
                <?php endif; ?>
              </ul>
          </li>
        <?php endif; ?>
        <!--
         <?php if ($this->auth->has_permission('Petajabatan.Reports.View')) : ?>
          <li class="treeview <?php echo $menu == 'petajabatan' ? 'active' : '' ?>">
            <a href="<?php echo base_url();?>admin/reports/petajabatan">
                <i class="fa fa-list"></i>
                <span>Peta Jabatan</span>    
              </a>
          </li>
        <?php endif; ?>
        -->
        <?php if ($this->auth->has_permission('Site.Masters.View')) : ?>
        <li class="treeview <?php echo $mainmenu == 'masters' ? 'active' : '' ?>">
          <a href="#">
            <i class="fa fa-laptop"></i>
            <span>MASTER DATA</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <?php if ($this->auth->has_permission('Agama.Masters.View')) : ?>
            <li><a href="<?php echo base_url();?>admin/masters/agama"><i class="fa fa-circle-o"></i>Agama</a></li>
            <?php endif; ?>
            <?php if ($this->auth->has_permission('Ref_jabatan.Masters.View')) : ?>
            <li><a href="<?php echo base_url();?>admin/masters/ref_jabatan"><i class="fa fa-circle-o"></i>Ref Jabatan</a></li>
            <?php endif; ?>
            <?php if ($this->auth->has_permission('Jabatan.Masters.View')) : ?>
            <li><a href="<?php echo base_url();?>admin/masters/jabatan"><i class="fa fa-circle-o"></i>Jabatan</a></li>
            <?php endif; ?>
            <?php if ($this->auth->has_permission('MasterPendidikan.Masters.View')) : ?>
            <li><a href="<?php echo base_url();?>pegawai/masterpendidikan"><i class="fa fa-circle-o"></i>Pendidikan</a></li>
            <?php endif; ?>
             <?php if ($this->auth->has_permission('Tkpendidikan.Masters.View')) : ?>
            <li><a href="<?php echo base_url();?>admin/masters/tkpendidikan"><i class="fa fa-circle-o"></i>Tk Pendidikan</a></li>
            <?php endif; ?>
            <?php if ($this->auth->has_permission('Golongan.Masters.View')) : ?>
            <li><a href="<?php echo base_url();?>admin/masters/golongan"><i class="fa fa-circle-o"></i>Golongan</a></li>
            <?php endif; ?>
            <?php if ($this->auth->has_permission('Petajabatan.Masters.View')) : ?>
             <li><a href="<?php echo base_url();?>admin/masters/petajabatan/permen"><i class="fa fa-circle-o"></i>Permen Petajabatan</a></li>
             <?php endif; ?>
            <?php if ($this->auth->has_permission('Petajabatan.Masters.View')) : ?>
            <li><a href="<?php echo base_url();?>admin/masters/petajabatan"><i class="fa fa-circle-o"></i>Petajabatan</a></li>
            <?php endif; ?>

             <li><a href="<?php echo base_url();?>pegawai/manage_unitkerja/index"><i class="fa fa-circle-o"></i>Unit Kerja</a></li>
             <?php if ($this->auth->has_permission('Jenis_satker.Masters.View')) : ?>
             <li><a href="<?php echo base_url();?>admin/masters/jenis_satker"><i class="fa fa-circle-o"></i>Jenis Satker</a></li>
             <?php endif; ?>
             <?php if ($this->auth->has_permission('Peraturan_otk.Masters.View')) : ?>
             <li><a href="<?php echo base_url();?>admin/masters/peraturan_otk"><i class="fa fa-circle-o"></i>Permen OTK</a></li>
             <?php endif; ?>
             <li><a href="<?php echo base_url();?>api/manage_api/index"><i class="fa fa-circle-o"></i>WS API</a></li>
             <li><a href="<?php echo base_url();?>api/manage_application/index"><i class="fa fa-circle-o"></i>WS Application</a></li>
             <?php if ($this->auth->has_permission('Daftar_rohaniawan.Masters.View')) : ?>
              <li><a href="<?php echo base_url();?>admin/masters/daftar_rohaniawan"><i class="fa fa-circle-o"></i>Daftar Rohaniawan</a></li>
              <?php endif; ?>
              <?php if ($this->auth->has_permission('Jenis_izin.Masters.View')) : ?>
              <li><a href="<?php echo base_url();?>admin/masters/jenis_izin"><i class="fa fa-circle-o"></i>Jenis Status Presensi/Absensi</a></li>
              <?php endif; ?>
              <?php if ($this->auth->has_permission('Jenis_arsip.Masters.View')) : ?>
              <li><a href="<?php echo base_url();?>admin/masters/jenis_arsip"><i class="fa fa-circle-o"></i>Jenis Arsip Digital</a></li>
              <?php endif; ?>

              <li <?php echo $menu == 'tte' ? 'class="active"' : '' ?>>
                <a href="#"><i class="fa fa-circle-o"></i> TTE
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu">
                  <?php if($this->auth->has_permission("Tte.Masters.View")){ ?>
                    <li><a href="<?php echo base_url();?>admin/masters/tte"><i class="fa fa-circle-o"></i> Master Proses TTE</a></li>
                  <?php } ?>
                  
                  <?php if($this->auth->has_permission("Tte.Masters.View")){ ?>
                    <li><a href="<?php echo base_url();?>admin/masters/tte/variable"><i class="fa fa-circle-o"></i> Variabel</a></li>
                  <?php } ?>
                </ul>
              </li>
              <!-- master data mutasi -->
              <?php if ($this->auth->has_permission('MasterLayanan.Masters.View')) : ?>
                <li><a href="<?php echo base_url();?>pegawai/master_layanan"><i class="fa fa-circle-o"></i>Layanan</a></li>
                <?php endif; ?>
                <?php if ($this->auth->has_permission('MasterLayananStatus.Masters.View')) : ?>
                <li><a href="<?php echo base_url();?>pegawai/master_layanan_status"><i class="fa fa-circle-o"></i>Status Layanan</a></li>
                <?php endif; ?>        
          <?php if ($this->auth->has_permission('ManageTemplates.Masters.View')) : ?>
                <li><a href="<?php echo base_url();?>templates/manage_templates"><i class="fa fa-circle-o"></i>Template Dokumen</a></li>
                <?php endif; ?> 
              <!--  end masterdata mutasi -->
          </ul>
          
        </li>
         <?php endif; ?>
        
      <?php if ($this->auth->has_permission('Site.Kepegawaian.View')) : ?>
        <li class="treeview <?php echo $mainmenu == 'kepegawaian' ? 'active' : '' ?>">
          <a href="#">
            <i class="fa fa-folder"></i> <span>Profil Pegawai</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <?php if ($this->auth->has_permission('Pegawai.Kepegawaian.View')) : ?>
            <li><a href="<?php echo base_url();?>admin/kepegawaian/pegawai"><i class="fa fa-circle-o"></i> Data Pegawai</a></li>
            <?php endif; ?>
            <?php if ($this->auth->has_permission('Pegawai.Kepegawaian.Viewppnpn')) : ?>
              <li><a href="<?php echo base_url();?>admin/kepegawaian/pegawai/ppnpn"><i class="fa fa-circle-o"></i> Data PPNPN</a></li>
            <?php endif; ?>
            <?php if ($this->auth->has_permission('Pegawai.Kepegawaian.ViewPensiun')) : ?>
            <li><a href="<?php echo base_url();?>admin/kepegawaian/pegawai/pensiun"><i class="fa fa-circle-o"></i> Data Pegawai Pensiun</a></li>
            <?php endif; ?>
            <?php if ($this->auth->has_permission('Pegawai.Kepegawaian.Review')) : ?>
            <li><a href="<?php echo base_url();?>admin/kepegawaian/pegawai/review"><i class="fa fa-circle-o"></i> Perbandingan Pegawai</a></li>
            <?php endif; ?>
            <?php if ($this->auth->has_permission('Pegawai.Kepegawaian.VerifikasiUpdate')) : ?>
            <li><a href="<?php echo base_url();?>admin/kepegawaian/pegawai/viewupdatemandiri1"><i class="fa fa-circle-o"></i> Verifikasi Update Level Satker</a></li>
            <?php endif; ?>
            <?php if ($this->auth->has_permission('Pegawai.Kepegawaian.VerifikasiUpdate3')) : ?>
            <li><a href="<?php echo base_url();?>admin/kepegawaian/pegawai/viewupdatemandiri"><i class="fa fa-circle-o"></i> Verifikasi Update Level Biro</a></li>
            <?php endif; ?>
          </ul>
        </li>
         <?php endif; ?>
         <?php if ($this->auth->has_permission('Site.Layanan.View')) : ?>
          <li class="treeview <?php echo $mainmenu == 'layanan' ? 'active' : '' ?>">
          <a href="#">
            <i class="fa fa-share"></i> <span>Layanan</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li <?php echo $menu == 'pindah_unit' ? 'class="active"' : '' ?>>
              <a href="#"><i class="fa fa-circle-o"></i> Pindah Antar Unit
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <?php if($this->auth->has_permission("Pindah_unit.Layanan.View")){ ?>
                <li><a href="<?php echo base_url();?>admin/layanan/pindah_unit"><i class="fa fa-circle-o"></i> Pegawai</a></li>
              <?php } ?>
              <?php if($this->auth->has_permission("Pindah_unit.Layanan.UnitAsal")){ ?>
                <li><a href="<?php echo base_url();?>admin/layanan/pindah_unit/asal"><i class="fa fa-circle-o"></i> Unit Asal</a></li>
              <?php } ?>
              <?php if($this->auth->has_permission("Pindah_unit.Layanan.UnitTujuan")){ ?>
                <li><a href="<?php echo base_url();?>admin/layanan/pindah_unit/tujuan"><i class="fa fa-circle-o"></i> Unit Tujuan</a></li>
              <?php } ?>
              <?php if($this->auth->has_permission("Pindah_unit.Layanan.Sestama")){ ?>
                <li><a href="<?php echo base_url();?>admin/layanan/pindah_unit/sestama"><i class="fa fa-circle-o"></i> Sestama</a></li>
              <?php } ?>
              <?php if($this->auth->has_permission("Pindah_unit.Layanan.Biro")){ ?>
                <li><a href="<?php echo base_url();?>admin/layanan/pindah_unit/biro"><i class="fa fa-circle-o"></i> Biro</a></li>
              <?php } ?>
              
              </ul>
            </li>
            <li <?php echo $menu == 'pengajuan_tubel' ? 'class="active"' : '' ?>>
              <a href="#"><i class="fa fa-circle-o"></i> Pengajuan Tubel
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <?php if($this->auth->has_permission("Pengajuan_tubel.Layanan.View")){ ?>
                  <li><a href="<?php echo base_url();?>admin/layanan/pengajuan_tubel"><i class="fa fa-circle-o"></i> Pengajuanku</a></li>
                <?php } ?>
                
                <?php if($this->auth->has_permission("Pengajuan_tubel.Layanan.ViewAdm")){ ?>
                  <li><a href="<?php echo base_url();?>admin/layanan/pengajuan_tubel/viewadm"><i class="fa fa-circle-o"></i> Pengajuan All</a></li>
                <?php } ?>
              </ul>
            </li>
          </ul>
        </li>
        <?php endif; ?>
      <?php if ($this->auth->has_permission('Site.Reports.View')) : ?>
        <li class="treeview <?php echo $mainmenu == 'reports' ? 'active' : '' ?>">
          <a href="#">
            <i class="fa fa-folder"></i> <span>Laporan</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <?php if ($this->auth->has_permission('Rekap.Reports.DUK')) : ?>
            <li><a href="<?php echo base_url();?>pegawai/duk"><i class="fa fa-circle-o"></i>Daftar Urut Kepangkatan</a></li>
            <?php endif; ?>
            <?php if ($this->auth->has_permission('Rekap.DukSatker.View')) : ?>
            <li><a href="<?php echo base_url();?>pegawai/duk/duksatker"><i class="fa fa-circle-o"></i>Daftar Urut Kepangkatan Satker</a></li>
            <?php endif; ?>
            <!--
            <li><a href="<?php echo base_url();?>admin/reports/pegawai/kelompokjabatan"><i class="fa fa-circle-o"></i>Daftar kelompok Jabatan</a></li>
            
            <?php if ($this->auth->has_permission('Petajabatan.Masters.View')) : ?>
            <li><a href="<?php echo base_url();?>admin/reports/petajabatan/kebutuhanjabatan"><i class="fa fa-circle-o"></i>Petajabatan</a></li>
            <?php endif; ?>
            -->
            <?php if ($this->auth->has_permission('Rekap.Reports.Kuota')) : ?>
            <li><a href="<?php echo base_url();?>admin/reports/petajabatan"><i class="fa fa-table"></i>Peta Jabatan</a></li>
            <?php endif; ?>
            <?php if ($this->auth->has_permission('Petajabatan.Reports.Request')) : ?>
            <li><a href="<?php echo base_url();?>admin/reports/petajabatan/req_formasi"><i class="fa fa-table"></i>Request Formasi</a></li>
            <?php endif; ?>
            
            <?php if ($this->auth->has_permission('Rekap.Reports.JabatanKosong')) : ?>
            <li><a href="<?php echo base_url();?>admin/reports/petajabatan/kosong"><i class="fa fa-table"></i>kuota jabatan kosong</a></li>
            <?php endif; ?>
            <?php if ($this->auth->has_permission('Rekap.Reports.Struktur')) : ?>
            <li><a href="<?php echo base_url();?>petajabatan/struktur"><i class="fa fa-circle-o"></i>Struktur Organisasi</a></li>
            <?php endif; ?>
            <?php if ($this->auth->has_permission('Bezzeting.Reports.View')) : ?>
            <li><a href="<?php echo base_url();?>admin/reports/bezzeting"><i class="fa fa-table"></i>Bezzeting</a></li>
            <?php endif; ?>
            <li <?php echo $menu == 'kondisi_pegawai' ? 'class="active"' : '' ?>>
              <a href="#"><i class="fa fa-circle-o"></i> Kondisi Pegawai
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <?php if($this->auth->has_permission("Kondisi_pegawai.Reports.View")){ ?>
                <li><a href="<?php echo base_url();?>admin/reports/kondisi_pegawai"><i class="fa fa-circle-o"></i> Tahunan</a></li>
                <li><a href="<?php echo base_url();?>admin/reports/kondisi_pegawai/bulanan"><i class="fa fa-circle-o"></i> Bulanan</a></li>
                 
              <?php } ?>
              </ul>
            </li>
            <li <?php echo $menu == 'proyeksi_pensiun' ? 'class="active"' : '' ?>>
              <a href="#"><i class="fa fa-circle-o"></i> Proyeksi Pensiun
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <?php if($this->auth->has_permission("Proyeksi_pensiun.Reports.View")){ ?>
                <li><a href="<?php echo base_url();?>admin/reports/proyeksi_pensiun"><i class="fa fa-circle-o"></i> Per Periode</a></li>
                <li><a href="<?php echo base_url();?>admin/reports/proyeksi_pensiun/jenisjabatan"><i class="fa fa-circle-o"></i> Per Jenis Jabatan</a></li>
                <li><a href="<?php echo base_url();?>admin/reports/proyeksi_pensiun/jabatan"><i class="fa fa-circle-o"></i> Per Jabatan</a></li>
              <?php } ?>
              </ul>
            </li>
          </ul> 
        </li>
      <?php endif; ?>
      <?php if ($this->auth->has_permission('Site.Reports.View')) : ?>
        <li class="treeview <?php echo $menu == 'baperjakat' ? 'active' : '' ?>">
          <a href="#">
            <i class="fa fa-folder"></i> <span>Baperjakat</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">  
            <?php if ($this->auth->has_permission('Baperjakat.Kepegawaian.View')) : ?>
            <li><a href="<?php echo base_url();?>admin/kepegawaian/baperjakat"><i class="fa fa-circle-o"></i>Periode Baperjakat</a></li>
            <?php endif; ?>
            <?php if ($this->auth->has_permission('Baperjakat.Kepegawaian.Create')) : ?>
            <li><a href="<?php echo base_url();?>petajabatan/struktur/baperjakat"><i class="fa fa-circle-o"></i>Cari Kandidat</a></li>
            <?php endif; ?>
          </ul>
 
        </li>
      <?php endif; ?>
      <?php if ($this->auth->has_permission('Sk_ds.SK.View')) : ?>
        <li class="treeview <?php echo (($menu == 'sk_ds' and $mainmenu != "arsip")  or ( $mainmenu == "tte")) ? 'active' : '' ?>">
          <a href="#">
            <i class="fa fa-folder"></i> <span>Tandatangan Elektronik </span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">  
            <?php if ($this->auth->has_permission('Sk_ds.Sk.Viewall')) : ?>
            <li><a href="<?php echo base_url();?>admin/sk/sk_ds/viewall"><i class="fa fa-circle-o"></i>Daftar Semua SK</a></li>
            <?php endif; ?>
            <?php if ($this->auth->has_permission('Sk_ds.Sk.Validasi')) : ?>
            <li><a href="<?php echo base_url();?>admin/sk/sk_ds/validasi"><i class="fa fa-circle-o"></i>Koreksi SK</a></li>
            <?php endif; ?>
            <?php if ($this->auth->has_permission('Sk_ds.Sk.Tandatangan')) : ?>
            <li><a href="<?php echo base_url();?>admin/sk/sk_ds"><i class="fa fa-circle-o"></i>Tanda Tangan SK</a></li>
            <?php endif; ?>
            <?php if ($this->auth->has_permission('Sk_ds.Sk.Skkoreksi')) : ?>
            <li><a href="<?php echo base_url();?>admin/sk/sk_ds/skkoreksi"><i class="fa fa-circle-o"></i>SK Dikoreksi</a></li>
            <?php endif; ?>
            
            <?php if ($this->auth->has_permission('Sk_ds.Sk.ReqBarier')) : ?>
            <li><a href="<?php echo base_url();?>admin/sk/sk_ds/getbarier"><i class="fa fa-circle-o"></i>Get Barier</a></li>
            <?php endif; ?>
            <?php if ($this->auth->has_permission('Sk_ds.Sk.ReqToken')) : ?>
            <li><a href="<?php echo base_url();?>admin/sk/sk_ds/gettoken"><i class="fa fa-circle-o"></i>Request Token</a></li>
            <?php endif; ?>
            <?php if ($this->auth->has_permission('Sk_ds.Sk.ViewLog')) : ?>
            <li><a href="<?php echo base_url();?>admin/sk/sk_ds/viewlog"><i class="fa fa-circle-o"></i>Log Tandatangan</a></li>
            <?php endif; ?>
            <?php if ($this->auth->has_permission('Site.Tte.View')) : ?>
            <li <?php echo $mainmenu == 'tte' ? 'class="active"' : '' ?>>
                <a href="#"><i class="fa fa-circle-o"></i>Buat Dokumen TTE
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu">
                  <?php if($this->auth->has_permission("Tte.Tte.View")){ ?>
                    <li><a href="<?php echo base_url();?>admin/tte/tte"><i class="fa fa-circle-o"></i> Daftar Draft TTE</a></li>
                  <?php } ?>
                  <?php if($this->auth->has_permission("Tte.Tte.Create")){ ?>
                    <?php if (isset($rec_kategori_ds) && is_array($rec_kategori_ds) && count($rec_kategori_ds)):?>
                            <?php foreach($rec_kategori_ds as $kategori):?>
                              <li><a href="<?php echo base_url();?>admin/tte/tte/create/<?php e($kategori->id); ?>"><i class="fa fa-circle-o"></i> <?php echo $kategori->nama_proses; ?></a>
                            <?php endforeach;?>
                        <?php endif;?>
                    </li>
                  <?php } ?>
                </ul>
              </li>
            <?php endif; ?>
          </ul>
 
        </li>
      <?php endif; ?>
      <?php if ($this->auth->has_permission('Site.Arsip.View')) : ?>
        <li class="treeview <?php echo $mainmenu == 'arsip' ? 'active' : '' ?>">
          <a href="#">
            <i class="fa fa-folder"></i> <span>Dokumen Elektronik</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">  
            <?php if ($this->auth->has_permission('Sk_ds.Arsip.ViewEs1')) : ?>
            <li><a href="<?php echo base_url();?>admin/arsip/sk_ds/viewalles"><i class="fa fa-circle-o"></i>SK Elektronik Sekretariat Utama</a></li>
            <?php endif; ?>
            <?php if ($this->auth->has_permission('Sk_ds.Arsip.ViewSatker')) : ?>
            <li><a href="<?php echo base_url();?>admin/arsip/sk_ds/viewallsatker"><i class="fa fa-circle-o"></i>SK Elektronik Satker</a></li>
            <?php endif; ?>
            <?php if ($this->auth->has_permission('Sk_ds.Arsip.Pegawai')) : ?>
            <li><a href="<?php echo base_url();?>admin/arsip/sk_ds/viewallpegawai"><i class="fa fa-circle-o"></i>SK Elektronik Pegawai</a></li>
            <?php endif; ?>
            <?php if ($this->auth->has_permission('Arsip_digital.Arsip.ViewAll')) : ?>
            <li><a href="<?php echo base_url();?>admin/arsip/arsip_digital/"><i class="fa fa-file-o"></i>Arsip Digital</a></li>
            <?php endif; ?>
          </ul>
 
        </li>
      <?php endif; ?>

      <?php if ($this->auth->has_permission('Site.Izin.View')) : ?>
        <li class="treeview <?php echo $mainmenu == 'izin' ? 'active' : '' ?>">
          <a href="#">
            <i class="fa fa-clock-o"></i> <span>Status Absensi/Presensi</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">  
            <?php if ($this->auth->has_permission('Izin_pegawai.KirimAbsen.View')) : ?>
              <li class="treeview <?php echo $submenu == 'perizinan' ? 'active' : '' ?>">
                <a href="<?php echo base_url();?>admin/izin/izin_pegawai/perizinan">
                    <i class="fa fa-file-o"></i>
                    <span>Pengajuan</span>    
                  </a>
              </li>
            <?php endif; ?>
            <?php if ($this->auth->has_permission('Izin_pegawai.Izin.View')) : ?>
            <li class="treeview <?php echo $menu == 'izin_pegawai' && $submenu == 'index' ? 'active' : '' ?>">
              <a href="<?php echo base_url();?>admin/izin/izin_pegawai/index">
                <i class="fa fa-circle-o"></i>
                <span>Pencarian</span>
                </a>
              </li>
            <?php endif; ?>
            <?php if ($this->auth->has_permission('Izin_pegawai.Izin.LaporanPegawai')) : ?>
            <li class="treeview <?php echo $menu == 'izin_pegawai' && $submenu == 'laporanpegawai' ? 'active' : '' ?>">
              <a href="<?php echo base_url();?>admin/izin/izin_pegawai/laporanpegawai">
                <i class="fa fa-circle-o"></i>
                <span>Laporan</span>
                </a>
              </li>
            <?php endif; ?>
            <!--
            <?php if ($this->auth->has_permission('Izin_pegawai.KirimAbsen.View')) : ?>
              <li class="treeview <?php echo $submenu == 'absen' ? 'active' : '' ?>">
                <a href="<?php echo base_url();?>admin/izin/izin_pegawai/absen">
                    <i class="fa fa-clock-o"></i>
                    <span>Status Presensi</span>    
                  </a>
              </li>
            <?php endif; ?>
            -->
            <?php if ($this->auth->has_permission('Izin_pegawai.Izin.Persetujuan') or $is_pejabat_cuti) : ?>
            <li class="treeview <?php echo $menu == 'izin_pegawai' && $submenu == 'verifikasi' ? 'active' : '' ?>">
              <a href="<?php echo base_url();?>admin/izin/izin_pegawai/verifikasi"><i class="fa fa-circle-o"></i>Persetujuan</a>
            </li>
            <?php endif; ?>
            <?php if ($this->auth->has_permission('Sisa_cuti.Izin.View')) : ?>
            <li><a href="<?php echo base_url();?>admin/izin/sisa_cuti"><i class="fa fa-circle-o"></i>Sisa Cuti</a></li>
            <?php endif; ?>
            <?php if ($this->auth->has_permission('Setting_atasan.Izin.View')) : ?>
            <li><a href="<?php echo base_url();?>admin/izin/izin_pegawai/setting"><i class="fa fa-circle-o"></i>Setting Atasan</a></li>
            <?php endif; ?>
            <?php if ($this->auth->has_permission('Izin_pegawai.Izin.ViewAll')) : ?>
            <li><a href="<?php echo base_url();?>admin/izin/izin_pegawai/viewall"><i class="fa fa-circle-o"></i>Usulan Izin (Admin)</a></li>
            <?php endif; ?>
            <?php if ($this->auth->has_permission('Izin_pegawai.Izin.ViewSatker')) : ?>
            <li><a href="<?php echo base_url();?>admin/izin/izin_pegawai/viewallsatker"><i class="fa fa-circle-o"></i>Usulan Izin (Satker)</a></li>
            <?php endif; ?>

            <li><a href="<?php echo base_url();?>admin/izin/izin_pegawai/pengajuanjadwalbdr"><i class="fa fa-circle-o"></i>Pengajuan Jadwal BDR</a></li>
             
            <!--
            <?php if ($this->auth->has_permission('Izin_pegawai.Izin.Persetujuan')) : ?>
            <li><a href="<?php echo base_url();?>admin/izin/izin_pegawai/pybmc"><i class="fa fa-circle-o"></i>Persetujuan PYBMC</a></li>
            <?php endif; ?>
          -->
          </ul>
 
        </li>
      <?php endif; ?>
      <!-- menu mutasi -->
      <?php if ($this->auth->has_permission('Site.Transaksi.View')) : ?>
      <li class="treeview <?php echo $mainmenu == 'layanan' ? 'active' : '' ?>">
          <a href="#">
            <i class="fa fa-laptop"></i>
            <span>Transaksi</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
          <li >
                  <a href="#"><i class="fa fa-circle-o"></i> KGB
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu">
            
                <?php if ($this->auth->has_permission('LayananKGB.View.PengelolaKGBInstansi')) : ?>
                  <li><a href="<?php echo base_url();?>kgb/kgb_instansi"><i class="fa fa-circle-o"></i> KGB Instansi</a></li>
            <?php endif; ?>
            <?php if ($this->auth->has_permission('LayananKGB.View.PengelolaKGBSatker')) : ?>
                  <li><a href="<?php echo base_url();?>kgb/kgb_satker"><i class="fa fa-circle-o"></i> KGB Satker</a></li>
            <?php endif; ?>
            </ul>
          </li> 
           <li >
                  <a href="#"><i class="fa fa-circle-o"></i> PPO
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
              <ul class="treeview-menu">
                <?php if ($this->auth->has_permission('LayananPPO.View.PengelolaPPOInstansi')) : ?>
                      <li><a href="<?php echo base_url();?>ppo/ppo_instansi"><i class="fa fa-circle-o"></i> PPO Instansi</a></li>
                <?php endif; ?>
                <?php if ($this->auth->has_permission('LayananPPO.View.PengelolaPPOSekretariat')) : ?>
                      <li><a href="<?php echo base_url();?>ppo/ppo_deputi"><i class="fa fa-circle-o"></i> PPO Sekretariat</a></li>
                <?php endif; ?>
                <?php if ($this->auth->has_permission('LayananPPO.View.PengelolaPPOSatker')) : ?>
                      <li><a href="<?php echo base_url();?>ppo/ppo_satker"><i class="fa fa-circle-o"></i> PPO Satker</a></li>
                <?php endif; ?>
              </ul>
           </li>
          
          
           <li >
                  <a href="#"><i class="fa fa-circle-o"></i> KPO
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu">
                     <?php if ($this->auth->has_permission('LayananKpo.View.PengelolaKPOInstansi')) : ?>
                      <li><a href="<?php echo base_url();?>kpo/kpo-instansi"><i class="fa fa-circle-o"></i> KPO Instansi</a></li>
                    <?php endif; ?>
                    <?php if ($this->auth->has_permission('LayananKpo.View.PengelolaKPOSekretariat')) : ?>
                      <li><a href="<?php echo base_url();?>kpo/kpo-sekretariat"><i class="fa fa-circle-o"></i> KPO Sekretariat</a></li>
                    <?php endif; ?> 
                    <?php if ($this->auth->has_permission('LayananKpo.ViewAll.PengelolaKPOSekretariat')) : ?>
                      <li><a href="<?php echo base_url();?>kpo/kpo-sekretariat/indexall"><i class="fa fa-circle-o"></i> KPO Sekretariat All</a></li>
                    <?php endif; ?> 
                    <?php if ($this->auth->has_permission('LayananKpo.View.PengelolaKPOSatker')) : ?>
                          <li><a href="<?php echo base_url();?>kpo/kpo-satker"><i class="fa fa-circle-o"></i> KPO Satker</a></li>
                    <?php endif; ?>
              </ul>
           </li>
            
          </ul>  

        </li>    
      <?php endif; ?>
      <!-- end menu mutasi -->
      <?php if ($this->auth->has_permission('Site.Developer.View')) : ?>
        <li class="treeview <?php echo $mainmenu == 'developer' ? 'active' : '' ?>">
          <a href="#">
            <i class="fa fa-folder"></i> <span>DEVELOPER</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="<?php echo base_url();?>admin/developer/sysinfo"><i class="fa fa-circle-o"></i> Informasi Sistem</a></li>
            <li><a href="<?php echo base_url();?>admin/reports/activities"><i class="fa fa-circle-o"></i> Log sistem</a></li>
            <!--
            <li><a href="<?php echo base_url();?>admin/developer/builder"><i class="fa fa-circle-o"></i> Module Builder</a></li>
             <li>
              <a href="<?php echo base_url();?>admin/settings/emailer"><i class="fa fa-circle-o"></i> Database Tools
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="<?php echo base_url();?>admin/developer/database"><i class="fa fa-circle-o"></i> Maintenance</a></li>
                <li><a href="<?php echo base_url();?>admin/developer/database/backups"><i class="fa fa-circle-o"></i> Backups</a></li>
                <li><a href="<?php echo base_url();?>admin/developer/migrations"><i class="fa fa-circle-o"></i> Migrations</a></li>
              </ul>
            </li>
            -->
          </ul>
        </li>
    <?php endif; ?>


    

    <?php if ($this->auth->has_permission('Penilaian.Ippns.View')) : ?>
      <li class="treeview <?php echo $menu == 'dashboard' ? 'active' : '' ?>">
        <a href="<?php echo base_url();?>penilaian/ippns">
          <i class="fa fa-dashboard"></i>
          <span>IPPNS</span>
        </a>
      </li>
    <?php endif; ?>
    
    <?php if ($this->auth->has_permission('Site.Settings.View')) : ?>
        <li class="treeview <?php echo $mainmenu == 'settings' ? 'active' : '' ?>">
          <a href="#">
            <i class="fa fa-share"></i> <span>Pengatuan Aplikasi</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="<?php echo base_url();?>admin/settings/settings"><i class="fa fa-circle-o"></i> Pengaturan</a></li>
            <li><a href="<?php echo base_url();?>admin/settings/roles"><i class="fa fa-circle-o"></i> Role</a></li>
            <li><a href="<?php echo base_url();?>admin/settings/users"><i class="fa fa-circle-o"></i> User</a></li>
            <li><a href="<?php echo base_url();?>admin/settings/permissions"><i class="fa fa-circle-o"></i> Permissions</a></li>
            <!--
            <li>
              <a href="<?php echo base_url();?>admin/settings/emailer"><i class="fa fa-circle-o"></i> Email
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="<?php echo base_url();?>admin/settings/emailer"><i class="fa fa-circle-o"></i> Setting</a></li>
                <li><a href="<?php echo base_url();?>admin/settings/emailer/template"><i class="fa fa-circle-o"></i> Template</a></li>
        
        <li><a href="<?php echo base_url();?>admin/settings/emailer/queue"><i class="fa fa-circle-o"></i> Antrian</a></li>
        
              </ul>
            </li>
            -->
          </ul>
        </li>
    <?php endif; ?>
   
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
<script>
    function showModalX(callableName,callableFn,parent) {
        $('.perhatian').fadeOut(300, function(){});
            var title = $(parent).attr("tooltip");
            $.ajax({
            url: $(parent).attr("href"),
            type: 'post',
            beforeSend: function (xhr) {
                $("#loading-all").show();
            },
            success: function (content, status, xhr) {
                
                var json = null;
                var is_json = true;
                try {
                    json = $.parseJSON(content);
                } catch (err) {
                    is_json = false;
                }
                if (is_json == false) {
                    $("#modal-custom-body").html(content);
                    $("#myModalcustom-Label").html(title);
                    $("#modal-custom-global").modal('show');
                    $("#modal-custom-global").off(callableName);
                    $("#modal-custom-global").on(callableName,callableFn);
                    $("#loading-all").hide();
                } else {
                    alert("Error");
                }
            }
            }).fail(function (data, status) {
            if (status == "error") {
                alert("Error");
            } else if (status == "timeout") {
                alert("Error");
            } else if (status == "parsererror") {
                alert("Error");
            }
            });
        }
</script>