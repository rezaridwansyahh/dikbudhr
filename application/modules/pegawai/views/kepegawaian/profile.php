
<?php 
	$this->load->library('convert');
 	$convert = new convert();
    $tab_pane_pendidikan_id = "tab_pane_pendidikan";//uniqid("tab_pane_pendidikan");
    $tab_pane_personal_id = "tab_pane_personal";//uniqid("tab_pane_personal");
    $tab_pane_kepangkatan_id = "tab_pane_kepangkatan";//uniqid("tab_pane_kepangkatan");
    $tab_pane_pindah_unit_kerja_id = "tab_pane_pindah_unit_kerja";//uniqid("tab_pane_pindah_unit_kerja");
    $tab_pane_prestasi_kerja = "tab_pane_prestasi_kerja";//uniqid("tab_pane_prestasi_kerja");
    $tab_pane_diklat_struktural_id = "tab_pane_diklat_struktural";//uniqid("tab_pane_diklat_struktural");
    $tab_pane_diklat_fungsional_id = "tab_pane_diklat_fungsional";//uniqid("tab_pane_diklat_fungsional");
    $tab_pane_rwt_jabatan = "tab_pane_rwt_jabatan";
    $tab_pane_rwt_pekerjaan = "tab_pane_rwt_pekerjaan";
    $tab_pane_rwt_hukdis = "tab_pane_rwt_hukdis";
    $tab_pane_rwt_kursus = "tab_pane_rwt_kursus";
    $tab_pane_rwt_assesmen = "tab_pane_rwt_assesmen";
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
<div class="admin-box box box-primary profile">
	<div class="box-body">
		<div class="row">
                            <div class="col-md-2">
                                <ul class="list-unstyled profile-nav">
                                    <li>
                                        <img src="<?php echo base_url(); ?>assets/images/<?php echo (isset($pegawai->PHOTO) && $pegawai->PHOTO != "") ? $pegawai->PHOTO : 'noimage.jpg'; ?>" class="img-responsive pic-bordered" id="photopegawai" alt=""/>
                                        <center>
                                        <?php if ($this->auth->has_permission('Pegawai.Kepegawaian.Ubahfoto')) : ?>
                                        <a href="<?php echo base_url();?>admin/kepegawaian/pegawai/uploadfoto/<?php echo $PNS_ID; ?>" tooltip="Upload Foto" class="show-modal btn btn-small btn-warning margin"><i class="fa fa-photo"></i> Ubah foto </a>
                                        <?PHP endif; ?>
                                        </center>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="col-md-8 profile-info">
                                        <h1 class="font-green sbold uppercase"><?php echo isset($pegawai->GELAR_DEPAN) ? $pegawai->GELAR_DEPAN : ''; ?>  <?php echo isset($pegawai->NAMA) ? $pegawai->NAMA : ''; ?> <?php echo isset($pegawai->GELAR_BELAKANG) ? $pegawai->GELAR_BELAKANG : ''; ?></h1>
                                       <!-- <h4><b>PNS ID</b> <?php echo isset($pegawai->PNS_ID) ? $pegawai->PNS_ID : ''; ?></h4> -->
                                        <h4><b>NIP</b> <?php echo isset($pegawai->NIP_BARU) ? $pegawai->NIP_BARU : ''; ?></h4>
                                        <ul class="list-inline">
                                        	<li>
                                                TEMPAT/TGL LAHIR <i class="fa fa-map-marker"></i> <?php echo isset($pegawai->TEMPAT_LAHIR_NAMA) ? $pegawai->TEMPAT_LAHIR : 'ALAMAT'; ?> <i class="fa fa-calendar"></i> <?php echo isset($pegawai->TGL_LAHIR) ? $convert->fmtDate($pegawai->TGL_LAHIR,"dd month yyyy") : 'TGL_LAHIR'; ?> 
                                            </li>
                                            <li>
                                                <i class="fa fa-map-marker"></i> <?php echo isset($pegawai->ALAMAT) ? $pegawai->ALAMAT : 'ALAMAT'; ?></li>
                                        </ul>
                                    </div>
                                    <!--end col-md-8-->
                                    <div class="col-md-4">
                                        <div class="portlet sale-summary">
                                            <div class="portlet-title" style="padding:5px;">
                                                <div class="hide caption font-red sbold"> <strong><?php echo isset($unor_induk) ? $unor_induk : '-'; ?> </strong></div>
                                            </div>
                                            <style>
                                                ul.path_unor > li + li::before {
                                                    content: "> ";
                                                }
                                            </style>
                                            <div class="portlet-body" style="padding:5px;">
                                                <ul class="list-unstyled path_unor">
                                                    <?php 
                                                        foreach($parent_path_array_unor as $node){
                                                            echo "<li><strong>".$node->NAMA_UNOR."</strong></li>";        
                                                        }
                                                    ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end col-md-4-->
                                </div>
                                

                                <div class="nav-tabs-custom">
                                    <ul id="tab-insides-here" class="nav nav-tabs">
                                        <li class="active">
                                            <a href="#<?php echo $tab_pane_personal_id; ?>" data-toggle="tab" aria-expanded="true"> Data Personal </a>
                                        </li>
                                        <li class="">
                                            <a href="#<?php echo $tab_pane_pendidikan_id; ?>" data-toggle="tab" aria-expanded="false"> Pendidikan </a>
                                        </li>
                                       
                                        <li class="">
                                            <a href="#<?php echo $tab_pane_kepangkatan_id;?>" data-toggle="tab" aria-expanded="false"> Kepangkatan </a>
                                        </li>

                                         <li class="">
                                            <a href="#<?php echo $tab_pane_pindah_unit_kerja_id;?>" data-toggle="tab" aria-expanded="false"> Unit Kerja </a>
                                        </li>

                                         <li class="">
                                            <a href="#<?php echo $tab_pane_prestasi_kerja;?>" data-toggle="tab" aria-expanded="false"> Prestasi Kerja </a>
                                        </li>
                                         <li class="">
                                            <a href="#<?php echo $tab_pane_diklat_struktural_id;?>" data-toggle="tab" aria-expanded="false"> Diklat Struktural </a>
                                        </li>
                                        <li class="">
                                            <a href="#<?php echo $tab_pane_diklat_fungsional_id;?>" data-toggle="tab" aria-expanded="false"> Diklat Fungsional </a>
                                        </li>
                                        <li class="">
                                            <a href="#<?php echo $tab_pane_rwt_jabatan;?>" data-toggle="tab" aria-expanded="false"> Jabatan </a>
                                        </li>
                                        <li class="">
                                            <a href="#<?php echo $tab_pane_rwt_pekerjaan;?>" data-toggle="tab" aria-expanded="false"> Pekerjaan </a>
                                        </li>
                                        <li class="">
                                            <a href="#<?php echo $tab_pane_rwt_hukdis;?>" data-toggle="tab" aria-expanded="false"> Hukuman Disiplin </a>
                                        </li>
                                        <li class="">
                                            <a href="#<?php echo $tab_pane_rwt_kursus;?>" data-toggle="tab" aria-expanded="false"> Kursus </a>
                                        </li>
                                        <li class="">
                                            <a href="#<?php echo $tab_pane_rwt_assesmen;?>" data-toggle="tab" aria-expanded="false"> Assesmen </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                        <?php 
                                            $this->load->view('kepegawaian/tab_pane_personal',array('TAB_ID'=>$tab_pane_personal_id));                                         
                                            $this->load->view('kepegawaian/tab_pane_riwayat_pendidikan',array('TAB_ID'=>$tab_pane_pendidikan_id));
                                            $this->load->view('kepegawaian/tab_pane_riwayat_kepangkatan',array('TAB_ID'=>$tab_pane_kepangkatan_id));
                                            $this->load->view('kepegawaian/tab_pane_riwayat_pindah_unit_kerja',array('TAB_ID'=>$tab_pane_pindah_unit_kerja_id));
                                            $this->load->view('kepegawaian/tab_pane_riwayat_prestasi_kerja',array('TAB_ID'=>$tab_pane_prestasi_kerja));
                                            $this->load->view('kepegawaian/tab_pane_riwayat_diklat_struktural',array('TAB_ID'=>$tab_pane_diklat_struktural_id));
                                            $this->load->view('kepegawaian/tab_pane_riwayat_diklat_fungsional',array('TAB_ID'=>$tab_pane_diklat_fungsional_id));
                                            $this->load->view('kepegawaian/tab_pane_riwayat_jabatan',array('TAB_ID'=>$tab_pane_rwt_jabatan));
                                            $this->load->view('kepegawaian/tab_pane_riwayat_pekerjaan',array('TAB_ID'=>$tab_pane_rwt_pekerjaan));
                                            $this->load->view('kepegawaian/tab_pane_huk_dis',array('TAB_ID'=>$tab_pane_rwt_hukdis));
                                            $this->load->view('kepegawaian/tab_pane_rwt_kursus',array('TAB_ID'=>$tab_pane_rwt_kursus));
                                            $this->load->view('kepegawaian/tab_pane_rwt_assesmen',array('TAB_ID'=>$tab_pane_rwt_assesmen));
                                        ?>
                                        
                                        <!-- PRIVACY SETTINGS TAB -->
                                        <div class="tab-pane" id="kegiatan">
                                            <div class="row">
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                                    Kegiatan
                                                </div>
                                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                                    <b>
                                                                                                              </b>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- END PRIVACY SETTINGS TAB -->
                                    </div>
                                </div>
                            </div>
                        </div>
    </div>
</div>


 <script>
    $(document).ready(function(){
        $('.nav-tabs').scrollingTabs();
        // Javascript to enable link to tab
        var url = document.location.toString();
        if (url.match('#')) {
            console.log( $('.nav-tabs a[href="#' + url.split('#')[1] + '"]'));
            $('.nav-tabs a[href="#' + url.split('#')[1] + '"]').tab('show');
        } //add a suffix

        // Change hash for page-reload
        $('.nav-tabs a').on('shown.bs.tab', function (e) {
            window.location.hash = e.target.hash;
        })
    });
    
 </script>
