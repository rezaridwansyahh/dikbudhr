
<?php 
    $this->load->library('convert');
    $convert = new convert();
    $tab_pane_prestasi_kerja = "tab_pane_prestasi_kerja";
    $tab_pane_kinerja = "tab_pane_riwayat_kinerja";
    $tab_pane_rwt_assesmen = "tab_pane_rwt_assesmen";
    $tab_pane_rwt_assesmenadmin = "tab_pane_rwt_assesmenadmin";
    $tab_pane_rwt_nine_box = "tab_pane_rwt_nine_box";
    $tab_pane_rwt_ujikom = "tab_pane_rwt_ujikom";
    
?>
<?php
$id = isset($pegawai->ID) ? $pegawai->ID : '';
$PNS_ID = isset($pegawai->PNS_ID) ? $pegawai->PNS_ID : '';

?>
 <div class="tab-pane" id="<?php echo $TAB_ID;?>">
    <div class="nav-tabs-custom">
        <ul id="tab-insides-penilaian" class="nav nav-tabs">
            <?php if($this->auth->has_permission("RiwayatPrestasiKerja.Kepegawaian.View")){ ?>
            <li class="active">
                <a href="#<?php echo $tab_pane_prestasi_kerja;?>" data-toggle="tab" aria-expanded="false">SKP</a>
            </li>
            <?php } ?>

            <?php if($this->auth->has_permission("RiwayatKinerja.Kepegawaian.View")){ ?>
            <li class="">
                <a href="#<?php echo $tab_pane_kinerja;?>" data-toggle="tab" aria-expanded="false"> Kinerja</a>
            </li>
            <?php } ?>
            
            <?php if($this->auth->has_permission("Riwayatassesmen.Kepegawaian.ViewAdmin")){ ?>
            <li class="">
                <a href="#<?php echo $tab_pane_rwt_assesmenadmin;?>" data-toggle="tab" aria-expanded="false"> Asesmen (Admin)</a>
            </li>
            <?php } ?>
            
            <?php if($this->auth->has_permission("Riwayatassesmen.Kepegawaian.ViewPegawai")){ ?>
            <li class="">
                <a href="#<?php echo $tab_pane_rwt_assesmen;?>" data-toggle="tab" aria-expanded="false"> Assesmen </a>
            </li>
            <?php } ?>
            
            <?php if($this->auth->has_permission("Riwayatassesmen.Kepegawaian.Nine_box")){ ?>
            <li class="">
                <a href="#<?php echo $tab_pane_rwt_nine_box;?>" data-toggle="tab" aria-expanded="false"> Assesmen Nine Box</a>
            </li>
            <?php } ?>


            <?php if($this->auth->has_permission("RiwayatPrestasiKerja.Kepegawaian.View")){ ?>
            <li class="">
                <a href="#<?php echo $tab_pane_rwt_ujikom;?>" data-toggle="tab" aria-expanded="false">Ujikom</a>
            </li>
            <?php } ?>

        </ul>
        <div class="tab-content">
            <?php 
            if($this->auth->has_permission("RiwayatPrestasiKerja.Kepegawaian.View")){
                $this->load->view('kepegawaian/tab_pane_riwayat_prestasi_kerja',array('TAB_ID'=>$tab_pane_prestasi_kerja));
            }
            if($this->auth->has_permission("RiwayatKinerja.Kepegawaian.View")){
                $this->load->view('kepegawaian/tab_pane_riwayat_kinerja',array('TAB_ID'=>$tab_pane_kinerja));
            }
            if($this->auth->has_permission("Riwayatassesmen.Kepegawaian.View")){
                $this->load->view('kepegawaian/tab_pane_rwt_assesmen',array('TAB_ID'=>$tab_pane_rwt_assesmen));
            }
            if($this->auth->has_permission("Riwayatassesmen.Kepegawaian.ViewAdmin")){
                $this->load->view('kepegawaian/tab_pane_rwt_assesmen_admin',array('TAB_ID'=>$tab_pane_rwt_assesmenadmin));
            }
            if($this->auth->has_permission("Riwayatassesmen.Kepegawaian.Nine_box")){
                $this->load->view('kepegawaian/tab_pane_rwt_nine_box',array('TAB_ID'=>$tab_pane_rwt_nine_box));
            }

            if($this->auth->has_permission("RiwayatPrestasiKerja.Kepegawaian.View")){
                $this->load->view('kepegawaian/tab_pane_rwt_ujikom',array('TAB_ID'=>$tab_pane_rwt_ujikom));
            }
            ?>
        </div>
    </div>
</div>                  
